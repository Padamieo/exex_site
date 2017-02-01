<?php /* Template Name: landing page */ ?>
<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

   		<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

        <?php
					echo '<div id="about" class="pagebody">';
				?>
        <h1 class="kont grey"><?php the_title(); ?></h1>
     		 	<?php the_content(); ?>
					<?php //wp_link_pages(); ?>
					<?php //comments_template(); ?>
     		</div>

       </div>

			 <?php
			 if ( has_post_thumbnail() ) {
				//echo '<div class="featured-image">'.the_post_thumbnail( 'detail-image' ).'</div>';
			 }
			 ?>

		<?php endwhile; endif; ?>

<?php get_footer(); ?>
