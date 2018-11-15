<?php

include_once 'includes/functions/general.php'; // general functions, customizations
include_once 'includes/functions/shortcodes.php'; // shortcodes


// Theme Configuration
function theme_scripts() {
	$theme         = wp_get_theme();
	$theme_version = $theme->get( 'Version' );
	
	wp_enqueue_style( get_stylesheet(), get_stylesheet_uri(), array(), $theme_version );
	wp_enqueue_style( 'gfonts', 'https://fonts.googleapis.com/css?family=Muli:400,400i,700|Roboto+Slab:400,700' );
	wp_enqueue_script( 'imagesloaded', 'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'main', get_template_directory_uri() . '/includes/assets/main.js', array( 'jquery', 'imagesloaded' ), $theme_version, true );
	
	//wp_enqueue_style( 'flickity', 'https://npmcdn.com/flickity@2.0/dist/flickity.css', null );
	//wp_enqueue_script( 'flickity', 'https://cdnjs.cloudflare.com/ajax/libs/flickity/2.0.3/flickity.pkgd.min.js', array( 'jquery' ), null, true );
	
	
}

add_action( 'wp_enqueue_scripts', 'theme_scripts', 20 );

function add_jquery_to_non_admin() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' ); // do not forget this
		wp_deregister_script( 'jquery-migrate' ); // do not forget this
		
		wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', false, null, true );
		wp_enqueue_script( 'jquery' );
		
		add_filter( 'script_loader_tag', 'fix_jquery_script_tag', 10, 2 );
	}
}

add_action( 'init', 'add_jquery_to_non_admin' );

// move gravity forms to footer
add_filter( 'gform_init_scripts_footer', '__return_true' );

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


function theme_setup() {
	
	// 1. Theme Features
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
	) );
	
	
	// 2. Menus
	$menus = array(
		'header_primary' => 'Header',
		'footer_primary' => 'Footer',
		'mobile_primary' => 'Mobile',
	);
	
	register_nav_menus( $menus );
	
	/*
		// 3. Sidebars
		$sidebars = array(
			'sidebar' => array(
				'Sidebar',
				'Default sidebar.',
			),
		);

		foreach ($sidebars as $key => $bar) {
			register_sidebar(array(
				'id' => $key,
				'name' => $bar[0],
				'description' => $bar[1],

				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
		}*/
	
}

add_action( 'after_setup_theme', 'theme_setup' );
