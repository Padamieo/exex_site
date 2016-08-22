
	<?php
		if ( is_active_sidebar( 'niss_footer')) {
	  	echo '<div id="footer-area">';
			dynamic_sidebar( 'niss_footer' );
	    echo '</div>';
		}
	?>

	<div id="copyright">
		<p>&copy; <?php echo date("Y").'  '.bloginfo("name").' | <a href="mailto:'.antispambot("michaeladamlockwood@googlemail.com?Subject=Hello").'" title="Contact e-mail address" target="_blank">moc.liamelgoog@doowkcolmadaleahcim</a></p>';
		?>
	</div>

</div><!-- // wrap -->

	<?php wp_footer(); ?>

</body>
</html>
