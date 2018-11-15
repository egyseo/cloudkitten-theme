<?php
/*
global $_wp_additional_image_sizes;

$default_image_sizes = get_intermediate_image_sizes();

foreach ( $default_image_sizes as $size ) {
	$image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
	$image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
	$image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
}

if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
	$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
}

echo '<pre>';
print_r( $image_sizes);
echo '</pre>';
//*/
?>

</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="back-to-top"><a href="#" id="back-to-top" title="Back to top">↑</a></div>
	<div class="inside">
		<div class="footer-col">
			<?php
			// Footer - Primary Menu
			if ( has_nav_menu( 'footer_primary' ) ) {
				$args = array(
					'theme_location' => 'footer_primary',
					'menu'           => 'Footer - Primary',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);
				
				echo '<nav class="nav-menu nav-footer nav-primary">';
				echo '<h2>Main</h2>';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			?>
		</div>
		
		<div class="footer-col">
			<?php
			// Footer - Secondary Menu
			if ( has_nav_menu( 'footer_secondary' ) ) {
				$args = array(
					'theme_location' => 'footer_secondary',
					'menu'           => 'Footer - Secondary',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);
				
				echo '<nav class="nav-menu nav-footer nav-secondary">';
				echo '<h2>Adventures</h2>';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			?>
		</div>
		
		<div class="footer-col">
			<?php the_field( "footer_content", "options" ); ?>
		</div>
	</div>
	<div class="copyright">
		<div class="inside">
			<p>Logo and website &copy; <?php echo date( 'Y' ); ?> Rosie Leung</p>
			<div id="footer-logo" class="footer-logo" title="Deploy dog">
				<?php echo file_get_contents( get_template_directory_uri() . '/includes/images/logo_moose.min.svg' ); ?>
			</div>
		</div>
	</div>
</footer>

</div>


<?php
// Mobile Nav Menu
if ( has_nav_menu( 'mobile_primary' ) || has_nav_menu( 'mobile_secondary' ) ) {
	
	?>
	
	<div id="mobile-nav">
		
		<div class="inside">
			<div class="mobile-menu">
				<?php
				// Mobile - Primary Menu
				if ( has_nav_menu( 'mobile_primary' ) ) {
					$args = array(
						'theme_location' => 'mobile_primary',
						'menu'           => 'Mobile - Primary',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);
					
					echo '<nav class="nav-menu nav-mobile nav-primary">';
					wp_nav_menu( $args );
					echo '</nav>';
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

?>

<?php wp_footer(); ?>

</body>
</html>