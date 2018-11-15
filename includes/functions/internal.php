<?php

function fix_jquery_script_tag( $tag, $handle ) {
	if ( $handle == 'jquery' ) {
		return str_replace( '<script', '<script integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"', $tag );
	}
	
	return $tag;
}


function remove_type_in_script_tag( $tag ) {
	return str_replace( " type='text/javascript'", '', $tag );
}

add_filter( 'script_loader_tag', 'remove_type_in_script_tag' );


// Clean up <head>
function rs_optimize_head() {
	if ( has_action( 'wp_head', 'feed_links_extra' ) ) {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_action( 'wp_head', 'feed_links_extra', 30 );
	}
	
	if ( has_action( 'wp_head', 'feed_links' ) ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		add_action( 'wp_head', 'feed_links', 30 );
	}
	
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wp_generator' );
}

add_action( 'after_setup_theme', 'rs_optimize_head' );


// Render shortcodes in widget content
function rs_allow_shortcodes_in_widgets() {
	add_filter( 'widget_text', 'shortcode_unautop' );
	add_filter( 'widget_text', 'do_shortcode' );
}

add_action( 'init', 'rs_allow_shortcodes_in_widgets' );


// Add classes to the body tag
function rs_more_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'front-page';
	}
	
	// Display some classes regarding the user's role
	$user = wp_get_current_user();
	
	if ( $user && ! empty( $user->roles ) ) {
		foreach ( $user->roles as $role ) {
			$classes[] = 'user-role-' . $role;
		}
		$classes[] = 'logged-in';
	} else {
		$classes[] = 'user-role-none not-logged-in';
	}
	
	return $classes;
}

add_filter( 'body_class', 'rs_more_body_classes' );


function kill_wp_embed() {
	wp_deregister_script( 'wp-embed' );
}

add_action( 'wp_enqueue_scripts', 'kill_wp_embed' );


// remove crap
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

function remove_json_api() {
	
	// Remove the REST API lines from the HTML Header
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	
	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	
	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );
	
	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	
	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	
	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	
	// Remove all embeds rewrite rules.
	//add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	
}

add_action( 'after_setup_theme', 'remove_json_api' );

function disable_json_api() {
	
	// Filters for WP-API version 1.x
	add_filter( 'json_enabled', '__return_false' );
	add_filter( 'json_jsonp_enabled', '__return_false' );
	
	// Filters for WP-API version 2.x
	add_filter( 'rest_enabled', '__return_false' );
	add_filter( 'rest_jsonp_enabled', '__return_false' );
	
}

add_action( 'after_setup_theme', 'disable_json_api' );

function wpb_disable_feed() {
	wp_die( 'No feed available' );
}

add_action( 'do_feed', 'wpb_disable_feed', 1 );
add_action( 'do_feed_rdf', 'wpb_disable_feed', 1 );
add_action( 'do_feed_rss', 'wpb_disable_feed', 1 );
add_action( 'do_feed_rss2', 'wpb_disable_feed', 1 );
add_action( 'do_feed_atom', 'wpb_disable_feed', 1 );
add_action( 'do_feed_rss2_comments', 'wpb_disable_feed', 1 );
add_action( 'do_feed_atom_comments', 'wpb_disable_feed', 1 );

/**
 * Remove feed links from wp_head
 */
add_action( 'wp_head', function() {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}, 1 );


// remove emoji
add_filter( 'emoji_svg_url', '__return_false' );

function ck_disable_wp_emoji() {
	
	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', function( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	} );
	
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'init', 'ck_disable_wp_emoji' );