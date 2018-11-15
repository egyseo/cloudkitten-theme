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
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#00aba9">
	<meta name="theme-color" content="#ffffff">
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="Rosie Leung" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/includes/images/opengraph.png" />
</head>
<body <?php body_class(); ?>>


<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
	
	<header id="masthead" class="site-header">
		<div class="inside"<?php the_field( 'header-inside', 'options' ); ?>>
			<div class="logo">
				<a href="<?php echo site_url(); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/includes/images/logo_horiz.min.svg" alt="Logo" />
				</a>
			</div>
		</div>
		<?php /*
		<div class="header-right">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button type="button" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">MENU</button>
				<?php wp_nav_menu( array(
					'container'      => '',
					'theme_location' => 'header_primary',
				) ); ?>
			</nav><!-- #site-navigation -->
		</div>
 */ ?>
	</header><!-- #masthead -->
	
	<main id="content" class="site-content clearfix">
		<div class="inside">
