<?php
/**
 * Template name: Front Page
 */

// handles ajax requests for paginated post archives
if ( isset( $_POST['ajax'] ) ) {
	include( 'ajax.php' );
	exit;
}

get_header();
?>
	<!-- lost the game -->
	<div class="site-content">
		<main id="main">
			<article <?php post_class( 'loop-single' ); ?>>
				<div class="loop-hero">
					<div class="inside">
						<?php the_field( "hero_content" ); ?>
					</div>
				</div>
				<div class="loop-body">
					<div class="inside">
						<?php the_field( "content" ); ?>
					</div>
				</div>
			</article>
		</main>
	</div>

<?php
get_footer();