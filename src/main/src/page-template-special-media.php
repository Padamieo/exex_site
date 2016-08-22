<?
/*
Template Name: Special Media Inclusion
needs work now working
*/
?>

<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
           
       
   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        <?php if ( has_post_thumbnail() ) { ?>			
			<div class="niss-image"><?php the_post_thumbnail( 'detail-image' );  ?></div>
        <?php }else{
			//special_attachment();
			?>
			<div id="unityPlayer">
				<div class="missing">
					<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
						<img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" />
					</a>
				</div>
			</div>
			<?php
		}?>
				<div class="niss-copy">
                <h1><?php the_title(); ?></h1>
           		 <?php the_content(); ?> 
                
                 <?php wp_link_pages(); ?>
                
					<?php comments_template(); ?> 
                 
         		</div>
                
               
                
                
       </div>
       
		<?php endwhile; endif; ?>
<?php get_footer(); ?>
