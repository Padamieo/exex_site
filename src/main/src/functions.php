<?php

	// Add RSS links to <head> section
	//add_theme_support('automatic-feed-links') ;

  // Load websites CSS and fonts
  add_action("wp_enqueue_scripts", "enqueue_niss_styles", 1);
  function enqueue_niss_styles() {
  	if ( !is_admin() ) {
  		wp_register_style('css.style', (get_template_directory_uri()."/css/style.css"),false,false,false);
  		//wp_register_style('font.ubuntu', ('http://fonts.googleapis.com/css?family=Ubuntu:300,400,700'),false,false,false);

  		wp_enqueue_style('css.style');
  		//wp_enqueue_style('font.ubuntu');

  	}
  }

	function enqueue_niss_scripts() {
	if ( !is_admin() ) {
	  wp_deregister_script('jquery');

	  wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js", false, null, false);
		wp_register_script('niss_scripts', get_template_directory_uri() . '/js/scripts.js', 'jquery',  null, false);

		wp_enqueue_script('jquery');
		wp_enqueue_script('niss_scripts');
	}
}
add_action("wp_enqueue_scripts", "enqueue_niss_scripts", 0);

	// // Load Fancybox setups
	// function fancybox($input){
	//
	// 		wp_register_script('jquery.fancybox', (get_template_directory_uri()."/js/jquery.fancybox.js"),'jquery',false,false);
	// 		wp_register_script('jquery.fancybox_gallery', (get_template_directory_uri()."/js/jquery.fancybox_gallery.js"),false,true);
	// 		wp_register_script('jquery.fancybox_regular', (get_template_directory_uri()."/js/jquery.fancybox_regular.js"),false,true);
	//
	// 		wp_register_style('jquery.fancybox_style', (get_template_directory_uri()."/css/jquery.fancybox.css"),false,false,false);
	// 		wp_register_style('jquery.fancybox_style_buttons', (get_template_directory_uri()."/css/jquery.fancybox_buttons.css"),false,false,false);
	//
	// 		if($input == 'gallery'){
	// 			wp_enqueue_script('jquery.fancybox');
	// 			wp_enqueue_script('jquery.fancybox_gallery');
	// 			wp_enqueue_style('jquery.fancybox_style');
	// 			wp_enqueue_style('jquery.fancybox_style_buttons');
	// 		}elseif($input == 'regular'){
	// 			wp_enqueue_script('jquery.fancybox');
	// 			wp_enqueue_script('jquery.fancybox_regular');
	// 			wp_enqueue_style('jquery.fancybox_style');
	// 			wp_enqueue_style('jquery.fancybox_style_buttons');
	// 		}
	//
	// }

	//make scripts load asynchronously
	function make_script_async( $tag, $handle, $src ){
	  if ( !is_admin() ) {
	    return str_replace( ' src', 'defer="defer" src', $tag );
	  }else{
	    return $tag;
	  }
	}
	add_filter( 'script_loader_tag', 'make_script_async', 10, 3 );

	//this will check if site is live or not
	function is_live(){
		if('http://nothingisstillsomething.co.uk' == get_site_url()){
			return true;
		}else{
			return false;
		}
	}

	//add livereload script to the footer provided sites not live
	function livereload() {
	  if(!is_live()){
	    wp_enqueue_script( 'livereload', '//localhost:35729/livereload.js', false, false, true );
	  }
	}
	add_action( 'wp_enqueue_scripts', 'livereload' );

	// content width
	if ( !isset( $content_width ))  {
		$content_width = 710;
	}

	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
			remove_action( 'wp_head', 'feed_links_extra', 3 ); // removes RSS links added by Padam
			remove_action( 'wp_head', 'feed_links', 2 ); // removes RSS links added by Padam
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

	// Niss post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size('summary-image', 310, 9999);
	add_image_size('detail-image', 770, 9999);


    // menu
	add_action( 'init', 'register_niss_menu' );

	function register_niss_menu() {
		register_nav_menu( 'main_nav', __( 'Main Menu' ) );
	}

     //setup footer widget area
	if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Footer',
    		'id'   => 'niss_footer',
    		'description'   => 'Footer Widget Area',
    		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-copy">',
    		'after_widget'  => '</div></div>',
    		'before_title'  => '<h3>',
    		'after_title'   => '</h3>'
    	));
	}

	// hide blank excerpts
	function custom_excerpt_length( $length ) {
		return 0;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	function new_excerpt_more($more) {
    global $post;
		return '';
	}
	add_filter('excerpt_more', 'new_excerpt_more');

	// Add Post Formats Support
	add_theme_support( 'post-formats', array( 'aside', 'video', 'quote', 'link', 'image', 'gallery') );

	// Niss theme options
	include 'options/admin-menu.php';

	// call images on gallery post format improve later to include videos?
	function gallery_attachment($size = thumbnail) {

		if($images = get_children(array(
			'post_parent'    => the_ID,
			'post_type'      => 'attachment',
			'numberposts'    => -1, // show all
			'post_status'    => null,
			'post_mime_type' => 'image',
		))) {
			$i = 0;
			foreach($images as $image) {
				$post_id	= get_the_ID();
				$custom_post_id = get_featured_image_display($image->ID);

				if($custom_post_id == $post_id){

					$image_url   = wp_get_attachment_url($image->ID);
					$image_title = apply_filters('the_title',$image->post_title);
					$thumb_url = wp_get_attachment_thumb_url($image->ID);
					$image_alt_tag = $image->_wp_attachment_image_alt;

					if($i < 11){
						echo '<a class="tint-gal fancybox" href="'.$image_url.'" data-fancybox-group="gallery-'.$post_id.'" caption="'.$image_title.'">';
							echo '<img class="gallery" src="'.$thumb_url.'" alt="'.$image_alt_tag.'"></img></a>';
						$i++;
					}
				}
			}
		}
	}
	/* // NOT USED BUT MIGHT DO IN FUTURE [FOR ABOVE]
		$attimg   = wp_get_attachment_image($image->ID,$size);
		$attlink  = get_attachment_link($image->ID);
		$postlink = get_permalink($image->post_parent);
		$img_desc = $image->post_content;
	*/

/////////////////////////////////////////////////////////////////////
// 										NEW FOR CUSTOM IMAGES 											//
/////////////////////////////////////////////////////////////////////

	/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function myplugin_add_meta_box() {

	$screens = array( 'post' );

	foreach ( $screens as $screen ) {
		add_meta_box( 'myplugin_sectionid',	__( 'My Post Section Title', 'myplugin_textdomain' ), 'myplugin_meta_box_callback', $screen );
	}
}
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function myplugin_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'myplugin_save_meta_box_data', 'myplugin_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	echo '<label for="myplugin_new_field">';
	_e( 'Description for this field', 'myplugin_textdomain' );
	echo '</label> ';
	echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['myplugin_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['myplugin_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}
add_action( 'save_post', 'myplugin_save_meta_box_data' );

// USE THIS // http://mikejolley.com/2012/12/21/using-the-new-wordpress-3-5-media-uploader-in-plugins/

/////////////////////////////////////////////////////////////////////
// 										END FOR CUSTOM IMAGES 											//
/////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////// CLEAN UP
// call special attachments webgl unity videos
function special_attachment($size = thumbnail) {

	if($images = get_children(array(
		'post_parent'    => the_ID,
		'post_type'      => 'attachment_special',
		'numberposts'    => -1, // show all
		'post_status'    => null,
	))) {
		$i = 0;
		foreach($images as $image) {
			$post_id	= get_the_ID();
			$custom_post_id = get_post_meta($image->ID, 'custom_post_id', true);

			if($post_id == $custom_post_id){

				$image_fancybox = get_post_meta($image->ID, 'custom_checkbox', true);
				$image_type = get_post_meta($image->ID, 'custom_radio', true);

				//echo $image_type; USED FOR TESTING
				if($image_type == 1 || $image_type == ''){
					//Video
					$image_url   = wp_get_attachment_url($image->ID);
					$image_title = apply_filters('the_title',$image->post_title);
					$image_content = $image->post_content;
					$image_excerpt = $image->post_excerpt;
					$content = apply_filters('the_content', $image_content);

					//echo '<a class="tint fancybox '.$test.'" href="'.$image_url.'" title="'.$image_title.'">';
					//echo '<img class="gallery" src="'.$thumb_url.'" alt="'.$image_alt_tag.'"></img></a>';

					echo $content;


					if($image_fancybox == 'on'){
						//CLEAN UP!
						echo '<a class="fancybox" href="'.$image_url.'" title="'.$image_title.'">View Ci';
						echo '</a>';
						echo '<div class="clear"></div>';
					}

					//echo $image_excerpt;
					//echo $image_title;
				}
				elseif($image_type == 2){
					//webgl - future projects
				}else{
					//unity3d - needs to insert into header somehow
				}
			}
		}
	}
}

/*-------------------------------------------------------------------------------------------*/
/* attachment_special Post Type */
/* adding in the special media input type*/
/*-------------------------------------------------------------------------------------------*/
class attachment_special {

	function attachment_special() {
		add_action('init',array($this,'create_post_type'));
	}

	function create_post_type() {
		$labels = array(
		    'name' => 'Special Media',
		    'singular_name' => 'Special Media',
		    'add_new' => 'Add New',
		    'all_items' => 'All Items',
		    'add_new_item' => 'Add New Item',
		    'edit_item' => 'Edit Item',
		    'new_item' => 'New Item',
		    'view_item' => 'View Item',
		    'search_items' => 'Search Items',
		    'not_found' =>  'No Items found',
		    'not_found_in_trash' => 'No Items found in trash',
		    'parent_item_colon' => 'Parent Item:',
		    'menu_name' => 'Special Media'
		);
		$args = array(
			'labels' => $labels,
			'description' => "Special Media Additions : Video, SWF, Unity, HTML5 & Canvas.",
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 10,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title','editor','excerpt','custom-fields','revisions'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'your-slug', 'with_front' => 'before-your-slug'),
			'query_var' => true,
			'can_export' => true
		);
		register_post_type('attachment_special',$args);
	}
}

$attachment_special = new attachment_special();

/*-------------------------------------------------------------------------------------------*/
/* META for now untill names is given*/
/* allowing a image or media to be asigned to a post via id*/
/*-------------------------------------------------------------------------------------------*/
// Add the Meta Box
function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box', // $id
        'Custom Meta Box', // $title
        'show_custom_meta_box', // $callback
        'attachment_special', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box');

    // Field Array
    $prefix = 'custom_';
    $custom_meta_fields = array(
		array (
			'label' => 'Special Media Type',
			'desc'  => 'A description for the field.',
			'id'    => $prefix.'radio',
			'type'  => 'radio',
			'options' => array (
				'one' => array (
					'label' => 'Video',
					'value' => '1'
				),
				'two' => array (
					'label' => 'Unity3D (TBC)',
					'value' => '2'
				),
				'three' => array (
					'label' => 'Webgl (TBC)',
					'value' => '3'
				)
			)
		) ,
		array(
			'label'=> 'Fancybox',
			'desc'  => 'Allows viewing in lightbox',
			'id'    => $prefix.'checkbox',
			'type'  => 'checkbox'
		),
		array(
			'label' => 'Display On:',
			'desc' => 'Choose where to display the special media item (overwrites are possible).',
			'id'    =>  $prefix.'post_id',
			'type' => 'post_list',
			'post_type' => array('post','page')
		)
);

// The Callback
function show_custom_meta_box() {
global $custom_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($custom_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['type']) {
                    // case items will go here

					// radio
					case 'radio':
						foreach ( $field['options'] as $option ) {
							echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						}
					break;

					// checkbox
					case 'checkbox':
						echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
							<label for="'.$field['id'].'">'.$field['desc'].'</label>';
					break;

					// post_list
					case 'post_list':
					$items = get_posts( array (
						'post_type' => $field['post_type'],
						'posts_per_page' => -1
					));
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">
								<option value="">Select One</option>'; // Select One
							foreach($items as $item) {
								echo '<option value="'.$item->ID.'"',$meta == $item->ID ? ' selected="selected"' : '','>'.$item->post_type.': '.$item->post_title.'</option>';
							} // end foreach
						echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;

                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_custom_meta($post_id) {
    global $custom_meta_fields;

    // verify nonce
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }

    // loop through fields and save the data
    foreach ($custom_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}
add_action('save_post', 'save_custom_meta');

//below probably need to be improved to a drop down select option later

	//Adding a "display on" field to the media uploader $form_fields array
	function add_display_field_to_media_uploader( $form_fields, $post ) {
		$form_fields['display_field'] = array(
			'label' => __('Display On:'),
			'value' => get_post_meta( $post->ID, '_custom_display', true ),
			'helps' => 'Set a page to display this media on (for use with Posts gallery format)'
		);

		return $form_fields;
	}
	add_filter( 'attachment_fields_to_edit', 'add_display_field_to_media_uploader', null, 2 );

	//Save our new "display" field
	function add_display_field_to_media_uploader_save( $post, $attachment ) {
		if ( ! empty( $attachment['display_field'] ) )
			update_post_meta( $post['ID'], '_custom_display', $attachment['display_field'] );
		else
			delete_post_meta( $post['ID'], '_custom_display' );

		return $post;
	}
	add_filter( 'attachment_fields_to_save', 'add_display_field_to_media_uploader_save', null, 2 );

	//Display our new "display on" field
	function get_featured_image_display( $attachment_id = null ) {
		$attachment_id = ( empty( $attachment_id ) ) ? get_post_thumbnail_id() : (int) $attachment_id;
		if ( $attachment_id )
			return get_post_meta( $attachment_id, '_custom_display', true );
	}
?>
