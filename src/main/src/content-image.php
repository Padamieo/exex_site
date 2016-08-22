
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'detail-image' ); ?>

		<div id="niss-image" class="niss-image">
			<a class="tint fancybox" href="<?php echo $image[0];?>" caption="<?php echo trim(strip_tags( $attachment->post_title )); ?>">
				<?php //the_post_thumbnail( 'detail-image' );  ?>
				<?php the_post_thumbnail('detail-image', 'title='.trim(strip_tags( $attachment->post_title ))); ?>
				<?php echo apply_filters('the_title',$attachment->post_title); ?>
			</a>
		</div>

	 <?php } ?>         
		<div class="clear"></div>
		<div class="niss-copy">
		<h1><?php the_title(); ?></h1>
		 <?php the_content(); ?>
		 
		 <p><?php the_tags(); ?></p>
		 <p>Categories: <?php the_category(', ') ?></p>
		
		<div class="clear"></div>
		<?php comments_template(); ?> 
		</div>
</div>