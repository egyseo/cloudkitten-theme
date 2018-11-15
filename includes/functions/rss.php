<?php

// Add featured image to rss items as a media tag
function rs_rss_feature_image_to_rss( $content ) {
	$image_id = get_post_thumbnail_id();
	$image = wp_get_attachment_image_src( $image_id, 'rssfeed' );

	if ( $image ) {
		$type = 'image/jpg';
		if ( substr($image[0], -3) == 'png' ) $type = 'image/png';

		echo "\t";
		echo sprintf(
			'<media:content url="%s" medium="image" width="%s" height="%s" type="%s" />',
			esc_attr($image[0]),
			esc_attr($image[1]),
			esc_attr($image[2]),
			esc_attr($type)
		);
		echo "\n";
	}
}
add_action( 'rss2_item', 'rs_rss_feature_image_to_rss' );

// Add media namespace to RSS
function rs_rss_image_ns_to_rss() {
	echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n\t";
}
add_action( 'rss2_ns', 'rs_rss_image_ns_to_rss' );


// RSS FEED IMAGE SIZE SETTINGS
function register_rssfeed_settings() {
	add_settings_field(
		'rssfeed',             // id
		'RSS feed image size', // setting title
		'rssfeed_display',     // display callback
		'media',               // settings page
		'default'              // settings section
	);
	register_setting(
		'media',               // option page
		'rssfeed_w',           // option name
		'intval'               // validation callback
	);
	register_setting(
		'media',               // option page
		'rssfeed_h',           // option name
		'intval'               // validation callback
	);
}
function rssfeed_display() {
	echo 'Width <input type="number" class="small-text" step="1" min="0" name="rssfeed_w" value="';
	echo get_option( "rssfeed_w",560);
	echo '" /> ';
	echo 'Height <input type="number" class="small-text" step="1" min="0" name="rssfeed_h" value="';
	echo get_option( "rssfeed_h",280 );
	echo '" />';
}
// adds new images sizes using given dimensions, with fallbacks
$width = get_option( "rssfeed_w",560);
$height = get_option( "rssfeed_h",280);
add_image_size( 'rssfeed', $width, $height, true );

add_action( 'admin_init', 'register_rssfeed_settings' );