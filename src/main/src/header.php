<!DOCTYPE html>

	<!-- meta -->
  <html <?php language_attributes();?>>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('sitename'); ?> <?php wp_title(); ?></title>

	<!-- styles
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
     <link rel="stylesheet" type="text/css" media="all" href="<?php //echo get_template_directory_uri(); ?>/css/reset.css" />
	 -->

    <?php
      $options = get_option('plugin_options');
      //$niss_logo = $options['niss_logo'];
      //$niss_responsive = $options['niss_responsive'];
    ?>

    <?php if ($niss_responsive != 'no') { ?>
      <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
      -->
      <!--<link rel="stylesheet" type="text/css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" href="<?php echo get_template_directory_uri(); ?>/css/mobile.css" />
      -->
  <?php  } ?>

 	<!-- wp head -->
	<?php wp_head(); ?>
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<!--fancybox-->
	<?php if(!is_front_page() ){ ?>

      <!--
				<script type="text/javascript">
                    $(document).ready(function() {
                        $('.fancybox').fancybox({
                            openEffect  : 'none',
                            closeEffect : 'none',

                            prevEffect : 'none',
                            nextEffect : 'none',

                            closeBtn  : false,

                            helpers : {
                                title : {
                                    type : 'inside'
                                },
                                buttons	: {}
                            },

							beforeLoad: function() {
								this.title = (this.index + 1) + ' of ' + this.group.length + ($(this.element).attr('caption') ? ' - ' + $(this.element).attr('caption') : '');
							}
                        });
                    });
                </script>
        -->

      <!--
				<script type="text/javascript">
                    $(document).ready(function() {
                        $('.fancybox').fancybox({
                            openEffect  : 'none',
                            closeEffect : 'none',

                            closeBtn  : false,

                            helpers : {
                                title : {
                                    type : 'inside'
                                },
                                buttons	: {}
                            },

                            afterLoad : function() {
                                this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                            }
                        });
                    });
                </script>
      -->
			<?php
		}
	?>



</head>
<body <?php body_class(); ?>>
<div id="wrap">
	<div id="header">
		<div id="title">
			<a href="<?php echo home_url(); ?>/" title="<?php get_bloginfo( 'name' ); ?>" rel="home">
			<p><?php bloginfo('sitename'); ?></p>
			</a>

			<?php /*
				<a href="<?php echo home_url( '/' ); ?>"  title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">

					 <?php if ($niss_logo != '') {?>
						 <img src="<?php echo $niss_logo; ?>" alt="<?php bloginfo('sitename'); ?>">
					 <?php } else { ?>
						   <img src="<?php echo get_template_directory_uri(); ?>/images/light/logo.png" alt="<?php bloginfo('sitename'); ?>">
					 <?php } ?>
				</a>
			*/?>
		</div>
		<div class="subtitle">
			<p><?php bloginfo('description'); ?></p>
		</div>

       <?php if ( has_nav_menu( 'main_nav' ) ) { ?>
  		 <div id="nav"><?php wp_nav_menu( array( 'theme_location' => 'main_nav' ) ); ?></div>
       <?php } else { ?>
 	 	 <div id="nav"><ul><?php wp_list_pages("depth=1&title_li=");  ?></ul></div>
	   <?php } ?>

   </div>


<!-- // header -->
