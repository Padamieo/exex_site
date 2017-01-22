<!DOCTYPE html>

	<!-- meta -->
  <html <?php language_attributes();?> class="landing" >
	<meta charset="<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('sitename'); ?> <?php wp_title(); ?></title>

 	<!-- wp head -->
	<?php wp_head(); ?>
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

</head>

<body <?php body_class(); ?>>

<div id="wrap">
	<div id="header">

    <?php if ( has_nav_menu( 'user_nav' ) ) { ?>
      <div id="user-nav"><?php wp_nav_menu( array( 'theme_location' => 'user_nav' ) ); ?></div>
    <?php } else { ?>
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
    <?php }

    if ( is_active_sidebar( 'motg_landing') ) {
      echo '<div id="landing-area">';
      dynamic_sidebar( 'motg_landing' );
      echo '</div>';
    }
    echo '<div class="scrollTo"><a href="#about">Read More About</a></div>'
    ?>

   </div>


<!-- // header -->
