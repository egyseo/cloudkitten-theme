<?php

// Only continue if Yoast is active
$active_plugins = get_option( "active_plugins" );
if ( ! in_array( "wordpress-seo/wp-seo.php", $active_plugins ) ) {
	return;
}

// fix breadcrumbs for w3c validator
function ck_make_valid_breadcrumbs( $link_output ) {
	$link_output = str_replace( ' xmlns:v="http://rdf.data-vocabulary.org/#"', ' ', $link_output );
	
	return $link_output;
}

add_filter( 'wpseo_breadcrumb_output', 'ck_make_valid_breadcrumbs' );

// pretend this page doesn't exist while I decide what to do with it
function ck_hide_excursions_page_temp( $link_output ) {
	$link_output = str_replace( '/adventures/', '/about/', $link_output );
	$link_output = str_replace( 'Adventures', 'About', $link_output );
	$link_output = str_replace( '/excursions/', '/about/', $link_output );
	$link_output = str_replace( 'Excursions', 'About', $link_output );
	
	return $link_output;
}

add_filter( 'wpseo_breadcrumb_output', 'ck_hide_excursions_page_temp' );
