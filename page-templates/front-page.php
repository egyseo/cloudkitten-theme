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
	<style type="text/css">
		html {
			height: 100%;
		}
		
		body {
			min-height: 100%;
			margin: 0;
			position: relative;
			background: url(https://rosieleung.com/wp-content/themes/cloudkitten/includes/images/snad.png), radial-gradient(#0000, #00000030), linear-gradient(#7e9cc3, #30527b);
		}
		
		.center {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}
	</style>
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
<body>

<div class="center">
	<a href="/" title="Portfolio"><img src="<?php echo get_template_directory_uri(); ?>/includes/images/logo_square.min.svg" alt="Rosie Leung" /></a>
</div>

<?php wp_footer(); ?>

</body>
</html>