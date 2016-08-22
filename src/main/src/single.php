<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post();

		get_template_part( 'content', get_post_format() );
	?>
		<?php /*
   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( has_post_thumbnail() ) { ?>
				<div class="niss-image"><?php the_post_thumbnail( 'detail-image' );  ?></div>
              <div class="niss-category"><p><?php the_category(', ') ?></p></div>
            <?php } ?>

       			<div class="niss-copy">
                <h1><?php the_title(); ?></h1>
                 <p class="niss-date"> <?php the_time(get_option('date_format')); ?></p>
           		 <?php the_content(); ?>
                 <p><?php the_tags(); ?></p>


                <div class="clear"></div>
				<?php comments_template(); ?>
                </div>
       </div>
	   */ ?>
	<?php endwhile; endif; ?>

       <div class="post-nav">
               <div class="post-prev"><?php previous_post_link('%link'); ?> </div>
			   <div class="post-next"><?php next_post_link('%link'); ?></div>
        </div>

<?php get_footer(); ?>
