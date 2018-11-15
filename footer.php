</div><!-- #content -->
</main>

<footer id="colophon" class="site-footer" role="contentinfo">
	<?php //<div class="back-to-top"><a href="#" id="back-to-top">Back to top ↑</a></div>?>
	<div class="inside">
		<div class="copyright">
			<img src="<?php echo get_template_directory_uri(); ?>/includes/images/logo_moose.min.svg" alt="Logo" />
			<p>Logo and website designed and created by Rosie Leung.</p>
		</div>
		<?php
		/*
		<?php wp_nav_menu( array(
			'theme_location' => 'secondary',
			'menu_id'        => 'footer-menu',
			'container'      => 'nav',
		) ); ?>
		<div class="copyright">
			&copy; <?php echo date( "Y" ); ?> Rosie Leung
		</div>
		*/
		?>
	</div>
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>