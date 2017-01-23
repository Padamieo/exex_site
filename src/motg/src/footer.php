<footer>
	<?php
		if ( is_active_sidebar( 'motg_footer')) {
	  	echo '<div id="footer-area">';
			dynamic_sidebar( 'motg_footer' );
	    echo '</div>';
		}
	?>
	<div id="copyright">
		<p>
			<?php
		echo '<b>'.bloginfo("name").'</b> Is a <a href="">Extraordinary Excursions</a> event. Happening on 24/06/2017.';
		?>
		</p>
	</div>
</footer>
<?php wp_footer(); ?>
</div><!-- // wrap -->
</body>
</html>
