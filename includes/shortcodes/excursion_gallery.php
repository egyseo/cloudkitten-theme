<?php

add_shortcode( "adventure_gallery", "ck_excursion_gallery_fun" );
function ck_excursion_gallery_fun() {
	ob_start();
	if ( have_rows( 'adventures', 'options' ) ) { ?>
		<section class="excursion_gallery">
			<?php
			while( have_rows( 'adventures', 'options' ) ) {
				the_row();
				$link      = get_sub_field( "link" );
				$linkOpen  = $link ? '<a href="' . $link . '">' : '';
				$linkClose = $link ? '</a>' : '';
				if ( ! ( $title = get_sub_field( "title" ) ) ) {
					continue;
				}
				?>
				<div class="item">
					<div class="img">
						<?php echo $linkOpen . wp_get_attachment_image( get_sub_field( "photo" ), "thumbnail-cropped" ) . $linkClose; ?>
					</div>
					<div class="content">
						<?php echo wp_get_attachment_image( get_sub_field( "photo" ), "thumbnail-cropped", false, array( 'class' => 'bg' ) ); ?>
						<h3 class="title"><?php echo $linkOpen . $title . $linkClose; ?></h3>
						<time class="small"><?php the_sub_field( "date" ); ?></time>
						<div class="desc"><?php the_sub_field( "desc" ); ?></div>
						<div class="buttons"><?php
							if ( have_rows( "buttons" ) ) {
								while( have_rows( "buttons" ) ) {
									the_row();
									$button = get_sub_field( "button" );
									$title  = $button['title'] ?: 'Read more';
									echo '<a href="' . $button['url'] . '" class="arrow-link">' . $title . '</a>';
								}
							}
							?></div>
					</div>
				</div>
				<?php
			}
			?>
		</section>
		<?php
	}
	
	return ob_get_clean();
}