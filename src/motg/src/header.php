<!DOCTYPE html>

	<!-- meta -->
  <?php
  function is_landing_page(){
    global $template;
    return (strpos($template, 'page-landing') !== false ? 'class="landing"' : '');
  }

  ?>

  <html <?php language_attributes();?> <?php echo is_landing_page(); ?>>
	<meta charset="<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('sitename'); ?> <?php wp_title(); ?></title>

 	<!-- wp head -->
	<?php wp_head(); ?>
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

</head>

<body <?php body_class(); ?>>

<div id="wrap">
	<div id="header">

    <?php if ( has_nav_menu( 'user_nav' ) ) {
			//wp_nav_menu( menu_with_woocommere_setup() ); //this needs work


			?>
    		<div id="user-nav">
          <?php wp_nav_menu( array( 'theme_location' => 'user_nav' ) ); ?>
          <div class="countdown"><p><?php echo countdown_cal(); ?></p></div>
        </div>
			<?php

    } else { ?>
      <div id="user-nav"><ul><?php wp_list_pages("depth=1&title_li=");  ?></ul></div>
    <?php } ?>

    <div class="subtitle">
      <p class="someSelector"><?php bloginfo('description'); ?></p>
    </div>

		<div id="title">
			<a href="<?php echo home_url(); ?>/" title="<?php get_bloginfo( 'name' ); ?>" rel="home">
				<p class="someSelector"><?php bloginfo('sitename'); ?></p>
			</a>
		</div>

    <?php if ( has_nav_menu( 'main_nav' ) ) { ?>
    <div id="nav"><?php wp_nav_menu( array( 'theme_location' => 'main_nav' ) ); ?></div>
    <?php } else { ?>
    <div id="nav"><ul><?php wp_list_pages("depth=1&title_li=");  ?></ul></div>
    <?php } ?>

   </div>


<!-- // header -->
