<?php


// Customize the title of the page
function ck_custom_archive_title( $title ) {
	// For taxonomies, show the term name instead of "Archive: {Term Name}"
	if ( is_tax() || is_category() ) {
		$title = single_term_title();
	}
	
	return $title;
}

add_filter( 'get_the_archive_title', 'ck_custom_archive_title', 10, 2 );


/*
 * remove crap
 */

function kill_wp_embed() {
	wp_deregister_script( 'wp-embed' );
}

add_action( 'wp_enqueue_scripts', 'kill_wp_embed' );


function replace_jquery() {
	if ( !is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', false, null );
		wp_enqueue_script( 'jquery' );
	}
}

add_action( 'init', 'replace_jquery' );


// remove crap
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_generator' );

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
}
	, 1 );


// remove emoji
add_filter( 'emoji_svg_url', '__return_false' );

function disable_wp_emojicons() {
	
	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	
	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', function( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}else{
			return array();
		}
	} );
}

add_action( 'init', 'disable_wp_emojicons' );

