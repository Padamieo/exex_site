<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
           
       
   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        <?php if ( has_post_thumbnail() ) { ?>			
				<div class="niss-image"><?php the_post_thumbnail( 'detail-image' );  ?></div>
        <?php } ?>    
             
	
                                

       			<div class="niss-copy">
                <h1><?php the_title(); ?></h1>
           		 <?php the_content(); ?> 
                
                 <?php wp_link_pages(); ?>
                
					<?php comments_template(); ?> 
                 
         		</div>
                
               
                
                
       </div>
       
		<?php endwhile; endif; ?>
       

<?php get_footer(); ?>
