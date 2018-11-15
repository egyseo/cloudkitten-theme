<?php

add_filter( 'gform_pre_send_email', 'before_email' );
function before_email( $email ) {
	$email['subject'] = $email['subject'] ? : 'Website inquiry';
	return $email;
}


// move gravity forms to footer
add_filter( 'gform_init_scripts_footer', '__return_true' );