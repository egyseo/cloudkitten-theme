<?php
/**
 * Template Name: Content Blocks
 */
?><?php
if ( ! have_posts() ) {
	get_template_part( '404' );
	
	return;
}

get_header();

the_post();

?>
	
	<div class="site-content">
		<main id="main">
			<article <?php post_class( 'loop-single' ); ?>>
				
				<?php
				if ( have_rows( 'content_blocks' ) ):
					$i = 1;
					while( have_rows( 'content_blocks' ) ) : $row = the_row( true );
						
						
						if ( $bg_color = get_sub_field( "bg_color" ) ) {
							$bg_opacity = get_sub_field( "bg_color_opacity" );
							list( $r, $g, $b ) = sscanf( $bg_color, "#%02x%02x%02x" );
							$rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $bg_opacity . ')';
						}
						$text_color = strtolower( get_sub_field( "text_color" ) );
						
						$bg_image = get_sub_field( "bg_image" );
						if ( $bg_image && $rgba ) {
							
							$style = 'background: url(' . $bg_image['sizes']['large'] . ') ' . $rgba . ';';
						}
						?>
						<div class="content-block text-color-<?php echo $text_color; ?>" style="<?php echo $style; ?>">
							<div class="inside">
								<?php
								if ( $title = get_sub_field( 'title' ) ) {
									if ( $i == 1 ) {
										echo '<h1>' . $title . '</h1>';
									} else {
										echo '<h2>' . $title . '</h2>';
									}
								}
								the_sub_field( 'text' );
								if ( $buttons = get_sub_field( 'buttons' ) ) {
									if ( count( $buttons ) ) {
										foreach ( get_sub_field( 'buttons' ) as $btn ) {
											$title = $btn['button']['title'] ?: 'Learn More';
											echo '<a href="', esc_attr( $btn['button']['url'] ), '" class="button-styled button-white"', esc_attr( $btn['button']['target'] ), '>', esc_html( $title ), '</a> ';
										}
									}
								}
								
								?>
							</div>
						</div>
						<?php
						$i ++;
					endwhile;
				endif;
				?>
			</article>
		</main>
		
		<?php
		if ( apply_filters( "sidebar_enabled", true ) ) {
			get_sidebar();
		}
		?>
	
	</div>

<?php
get_footer();