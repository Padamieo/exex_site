
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'detail-image' ); ?>

		<div id="niss-image" class="niss-image">
				<?php the_post_thumbnail( 'detail-image' );  ?>
		<?php /* Left incase i'd like to include later
		<div class="clear"></div>
		<div class="niss-category"><p><?php the_category(', ') ?></p></div>
		*/ ?>
		</div>

	 <?php } ?>         
		<div class="clear"></div>
		<div class="niss-copy">
		<h1><?php the_title(); ?></h1>
		 <?php /*
		 <p class="niss-date"> <?php the_time(get_option('date_format')); ?></p>
		 */ ?>
		 <?php the_content(); ?> 
		 
		<p><?php the_tags(); ?></p>
		<p>Categories: <?php the_category(', ') ?></p>
		
		<div class="clear"></div>
		<?php comments_template(); ?> 
		</div>  
</div>