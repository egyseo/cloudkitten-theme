<?php

// ADMIN: custom css
function theme_enqueue_admin_scripts() {
	wp_enqueue_style( 'theme-admin', get_template_directory_uri() . '/includes/assets/admin.css', array(), false );
}

add_action( 'admin_enqueue_scripts', 'theme_enqueue_admin_scripts', 30 );


// ADMIN: custom admin bar css on front end
function custom_admin_bar_css() { ?>
	<style type="text/css" media="screen">
		html {
			margin-top: 32px;
			height: calc(100% - 32px);
		}
		
		@media ( max-width: 782px ) {
			html {
				margin-top: 46px;
				height: calc(100% - 46px);
			}
		}
	</style>
	<?php
}

add_theme_support( 'admin-bar', array( 'callback' => 'custom_admin_bar_css' ) );


// DASHBOARD: Hide default welcome dashboard message
remove_action( 'welcome_panel', 'wp_welcome_panel' );


// DASHBOARD: Hide widgets from the dashboard screen
function rs_tweaks_clean_dashboard() {
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	
	// Allowed
	// remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	// remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
}

add_action( 'admin_init', 'rs_tweaks_clean_dashboard' );


// ADMIN FOOTER: Customize wp footer text
function rl_custom_wp_footer() {
	?>
	<strong>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></strong> &ndash; Built by <a href="https://rosieleung.com/">Rosie Leung</a>, powered by <a href="http://wordpress.org/">WordPress</a>.
	<?php
}
add_filter( 'admin_footer_text', 'rl_custom_wp_footer' );


// ADMIN BAR: removes unnecessary links from the admin bar: WordPress logo, comments, themes, customize
function rs_simplify_admin_bar() {
	global $wp_admin_bar;
	
	if ( !$wp_admin_bar || !method_exists( $wp_admin_bar, 'remove_node' ) ) {
		return;
	}
	
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'themes' );
	$wp_admin_bar->remove_node( 'customize' );
}

add_action( 'wp_before_admin_bar_render', 'rs_simplify_admin_bar' );



// EDITOR: add css to the wysiwyg editor
function ck_theme_add_editor_styles() {
	add_editor_style( 'includes/assets/editor-style.css' );
	// add_editor_style( get_template_directory_uri() . '/style.css' );
}
add_action( 'admin_init', 'ck_theme_add_editor_styles' );


// EDITOR: styleselect is the "Formats" dropdown, which has the button option seen below
function rs_custom_visual_editor_styles( $buttons ) {
	$first = array_shift( $buttons );
	array_unshift( $buttons, 'styleselect' );
	array_unshift( $buttons, $first );
	
	return $buttons;
}

add_filter( 'mce_buttons_2', 'rs_custom_visual_editor_styles' );


// EDITOR: Adds a button option under "Formats", which must be assigned to a link
function rs_custom_visual_editor_style_formats( $init_array ) {
	// Define the style_formats array
	$style_formats = array(
		
		array(
			'title'    => 'Button',
			'selector' => 'a',
			'classes'  => 'button',
		),
	
	);
	
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );
	
	return $init_array;
	
}

add_filter( 'tiny_mce_before_init', 'rs_custom_visual_editor_style_formats' );


// LOGIN: custom css
function login_page_custom_css() {
	if ( $logoID = get_field( 'logo', 'options' ) ) {
		$src = wp_get_attachment_image_src( $logoID, 'thumbnail' );
		?>
		<style type="text/css">
			body.login #login h1 a {
				background: url(<?php echo $src[0]; ?>) no-repeat center;
				width: <?php echo $src[1]; ?>px;
				height: <?php echo $src[2]; ?>px;
				background-size: contain;
			}
		</style>
		<?php
	}
}

add_action( 'login_head', 'login_page_custom_css' );

// LOGIN: custom url for logo
function custom_logo_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headerurl', 'custom_logo_url' );

// LOGIN: page title
function custom_login_title() {
	return get_bloginfo( 'title' );
}

add_filter( 'login_headertitle', 'custom_login_title' );