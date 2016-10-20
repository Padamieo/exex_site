
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="niss-copy">
		<h1><?php the_title(); ?></h1>
		 <?php the_content(); ?> 

		<p><?php the_tags(); ?></p>
		<p>Categories: <?php the_category(', ') ?></p>
		
		<div class="clear"></div>
		<?php comments_template(); ?> 
		</div> 
</div>