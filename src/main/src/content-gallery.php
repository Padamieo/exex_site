   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
				<div id="niss-image"class="niss-image">
					<?php gallery_attachment(); ?>
				</div>         
				<div class="clear"></div>
       			<div class="niss-copy">
                <h1><?php the_title(); ?></h1>
				<?php the_content(); echo get_post_meta($post->ID, 'custom_text', true);  ?>
				
				<p><?php the_tags(); ?></p>
				<p>Categories: <?php the_category(', ') ?></p>
                
                <div class="clear"></div>
				<?php comments_template(); ?> 
                </div>
 
       </div>
<script type="text/javascript">
//made by padam - left expanded for further error checking as of 13/02/13
/*function sizesettings(){
    var count = $(".gallery").size();
	if($(window).width() > 480){
		if(count % 3  == 0){
			if(count > 9){
				$(".gallery").css({"width": "192.5px"});
			}else{
				$(".gallery").css({"width": "256.6px"});
			}
		}else if(count % 2 == 0){
			if(count < 5){
				$(".gallery").css({"width": "384px"});
			}else{
				if(count % 5 == 0){
					$(".gallery").css({"width": "192.5px"});
					$('#niss-image .gallery').slice(-3).css({"width": "256px"});
				}else{
					$(".gallery").css({"width": "192.5px"});
				}
			}
		}else{
			if(count >= 10){
				$(".gallery").css({"width": "192.5px"});
				$('#niss-image .gallery').slice(4,-4).css({"width": "256px"});
			}else{
				if(count == 7){
					$(".gallery").css({"width": "192.5px"});
					$('#niss-image .gallery').slice(-3).css({"width": "256px"});
				}else{
					$(".gallery").css({"width": "256.6px"});
					$('#niss-image .gallery').slice(-2).css({"width": "384px"});
				}
			}
		}
	}else{
		$(".gallery").css({"width": "320px"});
	}
}

$(window).load(function () {
	if($(window).width() < 480){
		$(".gallery").css({"width": "320px"});
	}else if($(window).width() > 480){
		sizesettings();
	}
});

$(window).resize(function() {
	if($(window).width() < 480){
		$(".gallery").css({"width": "320px"});
	}else if($(window).width() > 480){
		sizesettings();
	}
});
*/
function sizesettings(){var count=$(".gallery").size();if($(window).width()>480)if(count%3==0)if(count>9)$(".gallery").css({"width":"192.5px"});else $(".gallery").css({"width":"256.6px"});else if(count%2==0)if(count<5)$(".gallery").css({"width":"384px"});else if(count%5==0){$(".gallery").css({"width":"192.5px"});$("#niss-image .gallery").slice(-3).css({"width":"256px"})}else $(".gallery").css({"width":"192.5px"});else if(count>=10){$(".gallery").css({"width":"192.5px"});$("#niss-image .gallery").slice(4,
-4).css({"width":"256px"})}else if(count==7){$(".gallery").css({"width":"192.5px"});$("#niss-image .gallery").slice(-3).css({"width":"256px"})}else{$(".gallery").css({"width":"256.6px"});$("#niss-image .gallery").slice(-2).css({"width":"384px"})}else $(".gallery").css({"width":"320px"})}$(window).load(function(){if($(window).width()<480)$(".gallery").css({"width":"320px"});else if($(window).width()>480)sizesettings()});
$(window).resize(function(){if($(window).width()<480)$(".gallery").css({"width":"320px"});else if($(window).width()>480)sizesettings()});

</script>