<?php
/**
 * Template Name: Portfolio
 */
?><?php
if ( ! have_posts() ) {
	get_template_part( '404' );
	
	return;
}

get_header();

the_post();

$cheapskate = false;

function output_employment_education( $key ) { ?>
	
	<div class="portfolio-<?php echo $key; ?>">
		<?php
		$output = array();
		while( have_rows( $key ) ) : the_row();
			if ( get_sub_field( "hide" ) ) {
				continue;
			}
			ob_start();
			?>
			<div class="job-or-school">
				<div class="what-who">
					<h3><?php the_sub_field( 'title' ); ?></h3>
					<div class="who"><?php the_sub_field( 'organization' ); ?></div>
				</div>
				<div class="when-where">
					<div class="when"><?php the_sub_field( 'years' ); ?></div>
					<div class="where"><?php the_sub_field( 'location' ); ?></div>
				</div>
				<div class="desc bullet">
					<?php echo get_sub_field( 'bullets' ); ?>
				</div>
			</div>
			<?php
			$output[] = ob_get_clean();
		endwhile;
		
		$total = count( $output );
		$half  = ceil( $total / 2 );
		?>
		<div class="col-1-2">
			<?php
			for ( $i = 0; $i < $half; $i ++ ) {
				echo $output[ $i ];
			}
			?>
		</div>
		<div class="col-2-2">
			<?php
			for ( ; $i < $total; $i ++ ) {
				echo $output[ $i ];
			}
			?>
		</div>
	</div>
	<?php
	
}


?>
	
	<article <?php post_class( 'loop-single' ); ?>>
		
		<header class="loop-header">
			<?php the_title( '<h1 class="loop-title">', '</h1>' ); ?>
		</header>
		
		<div class="loop-body">
			
			<?php if ( has_post_thumbnail() ) { ?>
				<div class="loop-image">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</div>
			<?php } ?>
			
			<div class="loop-content">
				<?php the_content(); ?>
			</div>
			
			<?php
			if ( ! post_password_required() ) :
				?>
				
				<div id="page-anchors" class="page-anchors">
					<ul class="page-anchors-list">
						<?php
						$sections = array( 'Experience', 'Skills', 'Testimonials', 'Portfolio', 'Education', 'Personal', 'Contact' );
						foreach ( $sections as $section ) {
							$lower = strtolower( $section );
							echo '<li><a href="#' . $lower . '">' . do_shortcode( '[svg src="icons/icon-' . $lower . '.svg"]' ) . $section . '</a></li>';
						}
						?>
					</ul>
				</div>
				
				<?php
				if ( have_rows( 'employment' ) ):
					echo '<h2 id="experience">Experience</h2>';
					output_employment_education( 'employment' );
					/*if ( get_field( "employment_footer" ) ) {
						?>
						<div class="job-footer">
							<?php the_field( "employment_footer" ) ?>
						</div>
						<?php
					}*/
				endif;
				
				if ( have_rows( 'portfolio_skills' ) ): ?>
					<h2 id="skills">Skills</h2>
					<div class="portfolio-skills">
						<?php while( have_rows( 'portfolio_skills' ) ) : the_row(); ?>
							<div class="skill">
								<h3 class="h4">
									<?php echo do_shortcode( get_sub_field( 'title' ) ); ?>
								</h3>
								<div class="desc">
									<?php echo get_sub_field( 'content' ); ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php
				
				endif;
				
				if ( have_rows( 'portfolio_testimonials' ) ):
					?>
					<h2 id="testimonials">Testimonials</h2>
					<div class="portfolio-testimonials">
						<?php while( have_rows( 'portfolio_testimonials' ) ) : the_row(); ?>
							<div class="testimonial">
								<div class="desc">
									<?php the_sub_field( 'feedback' ); ?>
								</div>
								<div class="source">
									<?php if ( $image = get_sub_field( 'photo' ) ) : ?>
										<img src="<?php echo $image['url']; ?>" alt="<?php the_sub_field( 'name' ); ?>" />
									<?php endif; ?>
									<h3 class="h4"><?php the_sub_field( 'name' ); ?></h3>
									<div class="company"><?php the_sub_field( 'company' ); ?></div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
				
				<div id="portfolio" class="portfolio-projects">
					<?php if ( have_rows( 'portfolio_highlights' ) ): ?>
						<h2>Portfolio</h2>
						<div class="projects-wrapper">
							<?php while( have_rows( 'portfolio_highlights' ) ) : the_row(); ?>
								<div class="portfolio-project">
									<?php
									$image = get_sub_field( 'thumbnail' );
									$url   = get_sub_field( 'url' );
									$host  = parse_url( $url )['host'];
									if ( substr( $host, 0, 4 ) == 'www.' ) {
										$host = substr( $host, 4 );
									}
									?>
									<div class="project-top">
										<div class="project-thumb">
											<?php if ( $cheapskate ): ?>
												<img src="https://loremflickr.com/400/280" />
											<?php else: ?>
												<img src="<?php echo $image['url']; ?>" alt="<?php the_sub_field( 'title' ); ?>" />
											<?php endif; ?>
										</div>
										<div class="project-details">
											<a target="_blank" class="vertical-center" href="<?php echo $url; ?>" rel="nofollow external"><?php echo $host; ?> &rarr;</a>
										</div>
									</div>
									<div class="project-desc">
										<h3><a target="_blank" href="<?php echo $url; ?>" rel="nofollow external"><?php the_sub_field( 'title' ); ?></a></h3>
										<p><?php the_sub_field( 'description' ); ?></p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
					
					<?php if ( get_field( 'additional_websites' ) ): ?>
						<div class="more-projects-wrapper">
							<h3>Other Websites</h3>
							<div class="portfolio-more-projects">
								<?php the_field( 'additional_websites' ); ?>
							</div>
						</div>
					<?php endif; ?>
				
				</div>
				
				<?php
				if ( have_rows( 'education' ) ):
					echo '<h2 id="education">Education</h2>';
					output_employment_education( 'education' );
				endif;
				
				if ( get_field( 'portfolio_personal' ) ):
					
					?>
					<h2 id="personal">Personal</h2>
					<div class="portfolio-personal">
						<?php
						the_field( 'portfolio_personal' );
						if ( have_rows( "portfolio_personal_events" ) ) {
							?>
							<div class="personal-events">
								<?php while( have_rows( "portfolio_personal_events" ) ) {
									the_row(); ?>
									<div class="personal-event">
										<div class="year"><?php the_sub_field( 'year' ); ?></div>
										<div class="desc"><?php the_sub_field( 'desc' ); ?></div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php
				endif;
				
				$left  = get_field( 'portfolio_contact_left' );
				$right = get_field( 'portfolio_contact_right' );
				if ( $left || $right ):
					?>
					<h2 id="contact">Contact</h2>
					<div class="portfolio-contact">
						<div class="contact-details"><?php echo $left; ?></div>
						<div class="contact-form"><?php echo $right; ?></div>
					</div>
				<?php endif; ?>
			
			<?php endif; ?>
		</div>
	
	</article>

<?php get_sidebar(); ?>

<?php
get_footer();