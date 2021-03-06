<?php

  // load website css
  add_action("wp_enqueue_scripts", "enqueue_niss_styles", 1);
  function enqueue_niss_styles() {
  	if ( !is_admin() ) {
  		wp_register_style('css.style', (get_template_directory_uri()."/css/style.css"),false,false,false);
  		wp_enqueue_style('css.style');
  	}
  }

  // load website js and jquery
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
	    wp_enqueue_script( 'livereload', '//localhost:1337/livereload.js', false, false, true );
	  }
	}
	add_action( 'wp_enqueue_scripts', 'livereload' );

	// clean up / remove unused rss and blog things
  function removeHeadLinks() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
  }
  add_action('init', 'removeHeadLinks');
  remove_action('wp_head', 'wp_generator');

  // setup main menu
	add_action( 'init', 'register_main_menu' );
	function register_main_menu() {
  	register_nav_menu( 'main_nav', __( 'Main Menu' ) );
    register_nav_menu( 'user_nav', __( 'User Menu' ) );
	}

  //setup footer widget area
	if (function_exists('register_sidebar')) {
  	register_sidebar(array(
  		'name' => 'Footer',
  		'id'   => 'motg_footer',
  		'description'   => 'Footer Widget Area',
  		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-copy">',
  		'after_widget'  => '</div></div>',
  		'before_title'  => '<h3>',
  		'after_title'   => '</h3>'
  	));

    register_sidebar(array(
      'name' => 'landing',
      'id'   => 'motg_landing',
      'description'   => 'Landing Widget Area',
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

  //load in our woocommerce elements
	if (class_exists('woocommerce')) {
	  include_once(ABSPATH . 'wp-content/themes/motg/functions_woocommerce.php');
	}

  // changes text on account button when logged in.
  function user_menu_change(){
    if (class_exists('woocommerce')) {

      if(get_option('woocommerce_myaccount_page_id') == get_the_ID()){
        $class_input = 'menu-item menu-item-type-post_type menu-item-object-page page-item-'.get_the_ID().' current_page_item';
      }else{
        $class_input = 'menu-item menu-item-type-post_type menu-item-object-page';
      }

      if ( is_user_logged_in() ) {
        $title = 'My Account';
      }else{
        $title = 'Login / Register';
      }

  		$account_link = '<li class="'.$class_input.'" ><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'">'.$title.'</a></li>';

      $inputs = array(
        'theme_location' => 'user_nav',
        'items_wrap'		=> '<ul id="%1$s" class="%2$s"> '.$account_link.' %3$s</ul>'
      );

    }else{
      $inputs = array( 	'container_class' => 'container', 'theme_location' => 'user_nav');
    }
  	return $inputs;
  }

  //changes the about page link only for the landing page
  function change_about($item_output, $item) {
    if(is_page_landing() !== false){
      if ('About' == $item->title) {
        if($item->object_id == get_the_ID()){
          $url = '#about';
        }else{
          $url = $item->url;
        }
          return '<a href="'.$url.'">'.$item->title.'</a>';
      }
    }
    return $item_output;
  }
  add_filter( 'walker_nav_menu_start_el','change_about', 10, 2 );


  //return the date time the event starts
  //this can be improved by having it stored as a theme setting
  function time_till_event(){
    //https://developer.wordpress.org/themes/customize-api/customizer-objects/
    return '2017-06-24 12:00';
  }

  //takes time_till_event and shows a countdown
  function countdown_cal(){
    $time_till = time_till_event();
    $date = gmdate('U', strtotime($time_till));
    $nice_date = gmdate('d/m/y', strtotime($time_till));
    $diff = $date - gmdate('U');
    $diff_days = floor($diff / (24 * 60 * 60));
    //$diff_hours = floor($diff % (24 * 60 * 60) / 3600);
    return 'Event In: '.$diff_days.' Days ('.$nice_date.')';
  }

  // adds class to landing page when template used
  function is_landing_page(){
    global $template;
    return ( is_page_landing() !== false ? 'class="landing"' : '');
  }

  // determines if landing page template is used
  function is_page_landing(){
    global $template;
    return strpos($template, 'page-landing');
  }

  //Disable RSS Feeds functions
  add_action('do_feed', array( $this, 'disabler_kill_rss' ), 1);
  add_action('do_feed_rdf', array( $this, 'disabler_kill_rss' ), 1);
  add_action('do_feed_rss', array( $this, 'disabler_kill_rss' ), 1);
  add_action('do_feed_rss2', array( $this, 'disabler_kill_rss' ), 1);
  add_action('do_feed_atom', array( $this, 'disabler_kill_rss' ), 1);

  if(function_exists('disabler_kill_rss')) {
  	function disabler_kill_rss(){
  		wp_die( _e("No feeds available.", 'ippy_dis') );
  	}
  }

  //Remove feed link from header
  remove_action( 'wp_head', 'feed_links_extra', 3 ); //Extra feeds such as category feeds
  remove_action( 'wp_head', 'feed_links', 2 ); // General feeds: Post and Comment Feed

  include_once(ABSPATH . 'wp-content/themes/motg/hide_comments.php');

	//this needs some work
	add_shortcode('thumbnail','thumbnail_in_content');
	function thumbnail_in_content( $atts ) {
		global $post;
        if ( has_post_thumbnail() ) {

            $var = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            $styles = 'background-image:url('.$var[0].');';

            $array = array('height');
            foreach ($array as &$current) {
                if($atts[$current]){
                    $styles = $styles." ".$current.":".$atts[$current].";";
                }
            }

            if($atts['title']){
                $array = array('text-align', 'font-size');
                $present = false;
                foreach ($array as &$current) {
                    if($atts[$current]){
                        $present = true;
                        $titlestyles = $titlestyles." ".$current.":".$atts[$current].";";
                    }
                }
                $addstyles = ($present ? 'style="'.$titlestyles.'"' : '' );
                
                $content = "<p ".$addstyles." >".$atts['title']."</p>";
            }

            $construct = '<div class="featured-insert" style="'.$styles.'">'.$content.'</div>';

            if($atts['url']){
                $construct = '<a class="featured clearfix" href="'.$atts['url'].'">'.$construct.'</a>';
            }
        }
        return $construct;
	}

?>
