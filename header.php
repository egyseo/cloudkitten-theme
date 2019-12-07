<!doctype html><!--
 __                           __
/  \  __  _           _  __  /  \
|   \/  \/ \         / \/  \/   |
\___________\_______/___________/
             |     |
             \ o o /
              \___/ \_____v
                     |___|
                     /\ /\
                    /  X  \
                   /  / \  \
-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php
	ob_start();
	wp_head();
	echo preg_replace( "/^([^\t ])/m", "\t$1", ob_get_clean() ); ?>
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=2">
	<link rel="manifest" href="/site.webmanifest?v=2">
	<link rel="mask-icon" href="/safari-pinned-tab.svg?v=2" color="#5bbad5">
	<link rel="shortcut icon" href="/favicon.ico?v=2">
	<meta name="msapplication-TileColor" content="#00aba9">
	<meta name="theme-color" content="#ffffff">
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="Rosie Leung" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/includes/images/opengraph.png" />
</head>
<body <?php body_class(); ?>>

<div class="inside mobile-menu-button-wrapper">
	<button type="button" class="mobile-menu-button">
			<span class="menu-bars">
				<span></span>
				<span></span>
				<span></span>
			</span>
		<span class="mobile-text">
				<span class="mobile-hidden">Menu</span>
				<span class="mobile-visible">Close</span>
			</span>
	</button>
</div>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
	
	<header id="masthead" class="site-header">
		<div class="inside"<?php the_field( 'header-inside', 'options' ); ?>>
			<div class="logo">
				
				<?php if ( shortcode_exists( 'rl_alert_tag' ) ) {
					echo do_shortcode( '[rl_alert_tag]' );
				}; ?>
				
				<a href="<?php echo site_url(); ?>">
					<picture>
						<img src="<?php echo get_template_directory_uri(); ?>/includes/images/logo_horiz.min.svg" alt="Logo" />
					</picture>
				
				</a>
			</div>
			
			<?php
			
			
			// Primary Menu
			if ( has_nav_menu( 'header_primary' ) ) {
				$args = array(
					'theme_location' => 'header_primary',
					'menu'           => 'Header - Primary',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);
				
				echo '<nav class="nav-menu nav-header nav-primary">';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			
			// Secondary Menu
			if ( has_nav_menu( 'header_secondary' ) ) {
				$args = array(
					'theme_location' => 'header_secondary',
					'menu'           => 'Header - Secondary',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);
				
				echo '<nav class="nav-menu nav-header nav-secondary">';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			?>
		
		</div>
	</header><!-- #masthead -->
	
	<?php if ( shortcode_exists( 'rl_alerts' ) ) {
		echo do_shortcode( '[rl_alerts]' );
	}; ?>
	
	<div id="content"<?php if ( apply_filters( "sidebar_enabled", true ) ) {
		echo ' class=" has-sidebar"';
	} ?>>
	
