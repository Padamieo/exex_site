<?php
get_header();

if (have_posts()) :
	echo '<div id="post-area">';
		while (have_posts()) : the_post();

				echo '<div id="post-'.get_the_ID().'"';
				post_class();
				echo '>';
					$link = (get_post_format() == 'link' ? get_the_content() : get_the_permalink());
					if ( has_post_thumbnail() ) {

						//$var = the_post_thumbnail( 'summary-image' );

						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'summary-image' );

						echo '<div class="niss-image" style="background-image: url('.$image[0].'); height:'.$image[2].'px; width:'.$image[1].'px;">';

							echo '<div class="niss-category"><p>';
								the_category(', ');
							echo '</p></div>';

							echo '<a class="tint-view" href="'.$link.'">';

							echo '</a>';
						echo '</div>';
						//echo '<div class="clear"></div>';
					}
						echo '<div class="niss-copy"><h2><a href="'.$link.'">'.get_the_title().'</a></h2>';
						the_excerpt();
						echo '</div>';
				echo '</div>';

		endwhile;
	echo '</div>';
else :

endif;

next_posts_link('<p class="view-older">View More Portfolio Submissions</p>');

get_footer();
