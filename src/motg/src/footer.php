
	<?php
		if ( is_active_sidebar( 'motg_footer')) {
	  	echo '<div id="footer-area">';
			dynamic_sidebar( 'motg_footer' );
	    echo '</div>';
		}
	?>

	<div id="copyright">
		<!--<p>
		<?php
		//echo date("Y").'  '.bloginfo("name").' | <a href="mailto:'.antispambot("michaeladamlockwood@googlemail.com?Subject=Hello").'" title="Contact e-mail address" target="_blank">moc.liamelgoog@doowkcolmadaleahcim</a></p>';
		?>
		<?php
		global $blog_id;
		echo $blod_id;
		//$current_blog_details = get_blog_details( $blog_id );
//echo $current_blog_details->blogname;
		echo bloginfo("name").' Is An ExEx.events event'; ?>
	</div>

</div><!-- // wrap -->

	<?php wp_footer(); ?>

</body>
</html>
