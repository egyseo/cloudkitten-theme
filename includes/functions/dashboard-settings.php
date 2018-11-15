<?php

// "FROM" EMAIL SETTINGS

function register_from_email_settings() {
	add_settings_field(
		'from_email',         // id
		'"From" Email',       // setting title
		'from_email_display', // display callback
		'general',            // settings page
		'default'             // settings section
	);
	register_setting(
		'general',            // option page
		'from_email',         // option name
		'sanitize_from_email' // validation callback
	);
}

add_action( 'admin_init', 'register_from_email_settings' );

function sanitize_from_email( $value ) {
	// sanitizes from_email as if it were admin_email
	return sanitize_option( 'admin_email', $value );
}

function from_email_display() {
	echo '<div id="from_email"><input name="from_email" type="email" class="regular-text ltr" value="';
	echo get_option( 'from_email' ) ?: get_option( 'admin_email' );
	echo '" /><p class="description">This address sends email notifications to users.</p></div>';
	?>
	<script type="text/javascript">
		var tbody = document.querySelector('tbody');
		tbody.insertBefore(document.getElementById('from_email').parentNode.parentNode, tbody.children[5]);
		document.querySelector('label[for="admin_email"]').textContent = "Admin Email";
		document.getElementById('admin-email-description').textContent = "This address receives email notifications from the website.";
	</script>
	<?php
}

// Change the default "from" name
function rs_tweaks_default_from_name( $name ) {
	if ( empty( $name ) || $name == "WordPress" ) {
		return get_bloginfo( 'title' );
	}
	
	return $name;
}

add_filter( 'wp_mail_from_name', 'rs_tweaks_default_from_name', 11 );

// If sending from admin email, send from the custom "From email" in Settings > General instead
function rs_tweaks_default_from_email( $email ) {
	$admin_email = get_option( 'admin_email' );
	$from_email  = get_option( 'from_email' );
	
	if ( $from_email && ( empty( $email ) || $email == $admin_email ) ) {
		return $from_email;
	}
	
	return $email;
}

add_filter( 'wp_mail_from', 'rs_tweaks_default_from_email', 11 );

// Make Gravity Forms default to "from email" set in Settings > General
// gf_apply_filters( array( 'gform_notification', $form['id'] ), $notification, $form, $lead );
function gf_override_default_from_address( $notification, $form, $lead ) {
	if ( $notification['from'] == "{admin_email}" ) {
		$notification['from'] = get_option( 'admin_email' );
	}
	
	$notification['from']     = apply_filters( 'wp_mail_from', $notification['from'] );
	$notification['fromName'] = apply_filters( 'wp_mail_from_name', $notification['fromName'] );
	
	return $notification;
}

add_filter( 'gform_notification', 'gf_override_default_from_address', 20, 3 );


// CROPPED THUMBNAIL IMAGE SIZE SETTINGS

function register_cropped_thumbnail_settings() {
	add_settings_field(
		'cropped_thumbnail',
		'Cropped thumbnail size',
		'crop_thumb_display',
		'media',
		'default'
	);
	register_setting(
		'media',
		'crop_thumb_size_w',
		'intval'
	);
	register_setting(
		'media',
		'crop_thumb_size_h',
		'intval'
	);
}

add_action( 'admin_init', 'register_cropped_thumbnail_settings' );

function crop_thumb_display() {
	?>
	
	<fieldset id="crop_thumb">
		<legend class="screen-reader-text"><span>Cropped thumbnail size</span></legend>
		<label for="crop_thumb_size_w">Width</label>
		<input name="crop_thumb_size_w" type="number" step="1" min="0" class="small-text" value="<?php echo get_option( "crop_thumb_size_w", 200 ); ?>">
		<br>
		<label for="crop_thumb_size_h">Height</label>
		<input name="crop_thumb_size_h" type="number" step="1" min="0" class="small-text" value="<?php echo get_option( "crop_thumb_size_h", 200 ); ?>">
	</fieldset>
	
	<script type="text/javascript">
		// move the cropped thumb under the regular thumb and prefixes the latter's inputs with "max"
		var table = document.getElementById("crop_thumb").parentNode.parentNode.parentNode;
		table.insertBefore(document.getElementById('crop_thumb').parentNode.parentNode, table.childNodes[1]);
		document.getElementById('thumbnail_size_w').previousSibling.previousSibling.textContent = "Max Width";
		document.getElementById('thumbnail_size_h').previousSibling.previousSibling.textContent = "Max Height";
	</script>
	<?php
}

// adds new images sizes using given dimensions, with fallbacks
$width  = get_option( "crop_thumb_size_w", 200 );
$height = get_option( "crop_thumb_size_h", 200 );
add_image_size( 'thumbnail-cropped', $width, $height, true );


// remove medium_large thumbnails
add_filter( 'intermediate_image_sizes', function( $sizes ) {
	return array_filter( $sizes, function( $val ) {
		return 'medium_large' !== $val; // Filter out 'medium_large'
	} );
} );


add_filter( 'jpeg_quality', function( ) { return 90; } );