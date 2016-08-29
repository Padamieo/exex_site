
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
			<div id="niss-image"class="niss-image">
				<?php special_attachment(); ?>
			</div>    
			<div class="clear"></div>
			<div class="niss-copy">
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>

			<p><?php the_tags(); ?></p>
			<p>Categories: <?php the_category(', ') ?></p>

<?php wp_reset_postdata(); ?> <!-- IM NOT SURE WHAT THIS DOES FIND OUT-->
			
			<div class="clear"></div>
			<?php comments_template(); ?> 
			</div>

   </div>