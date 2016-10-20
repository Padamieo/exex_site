
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="niss-copy">
		<a href="<?php echo get_the_content(); ?>" target="_blank" title="<?php the_title(); ?>">
		<h1>Link - <?php the_title(); ?></h1>
		</a>
		<?php //the_content(); ?> 
		<?php the_excerpt(); ?> 
		<p><?php the_tags(); ?></p>
		<p>Categories: <?php the_category(', ') ?></p>
		
		<div class="clear"></div>
		<?php comments_template(); ?> 
		</div> 
</div>