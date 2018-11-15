<?php

add_shortcode( 'year', function() { return date( 'Y' ); } );

// [svg src="icons/icon-foo.svg"]
function svg_shortcode_func( $atts ) {
	$a = shortcode_atts( array(
		'src' => '',
	), $atts );
	
	return file_get_contents( get_template_directory() . '/includes/images/' . strtolower( $a['src'] ) );
}

add_shortcode( 'svg', 'svg_shortcode_func' );


// if gravity forms sends &sent=true in the query, show a confirmation instead of the content/a blank form
function show_confirmation_or_gform_fun( $atts, $content = null ) {
	if ( isset( $_GET['sent'] ) && $_GET['sent'] == 'true' ) {
		echo '<p>Thanks for getting in touch!</p>';
		echo '<p><a href="' . get_permalink( 6 ) . '">Back to my portfolio &rarr;</a></p>';
	} else {
		return do_shortcode( $content );
	}
}

add_shortcode( 'show_confirmation_or_gform', 'show_confirmation_or_gform_fun' );


// gallery wrapper to make the aquoid parameters consistent
function ck_gallery_fun( $atts ) {
	$a = shortcode_atts( array(
		'album_id' => '',
	), $atts );
	if ( $a['album_id'] ) {
		ob_start();
		?>
		<div class="inside wide photo-gallery">
			<h2>Photos</h2>
			<?php echo do_shortcode( "[gallery type='google' view='photos' album_id='" . $a['album_id'] . "' count='25' more='Load More' main_size='1600' tile_size='470' layout='random']" ); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

add_shortcode( 'ck_gallery', 'ck_gallery_fun' );



