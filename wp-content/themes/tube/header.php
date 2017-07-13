<?php
/**
 * The header template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
?><!doctype html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<?php

// get the main menu
$main_menu_escaped = wp_nav_menu( array(
    'theme_location'    => 'main-menu',
    'container'         => 'false',
    'menu_class'        => 'nav navbar-nav navbar-right',
    'fallback_cb'       => 'Tube_wp_bootstrap_navwalker::fallback',
    'walker'            => new Tube_wp_bootstrap_navwalker(),
    'echo'            => false
   )
);

?>

  <head>
    
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
  	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
  	 <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  	<?php endif; ?>

    <!-- wordpress head functions -->
    <?php wp_head(); ?>
    <!-- end of wordpress head -->

  </head>
  
  <body <?php body_class(); ?>  itemscope itemtype="http://schema.org/WebSite">
  
  <?php do_action( 'tube_before_nav' ); ?>

  <nav class="navbar tube-site-navbar navbar-fixed-top">
    <div class="container">
      
      <div class="navbar-header">        
            
        <button type="button" id="nav-icon4" class="pull-right menu-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span></span>
          <span></span>
          <span></span>
        </button>
        
        <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo(  ) ) : ?>
           <?php the_custom_logo(); ?>
        <?php else : ?>
            <a href="<?php echo esc_url( home_url() ); ?>" class="navbar-brand site-name">
               <?php echo get_bloginfo( 'name' ); ?>
            </a>
        <?php endif; ?>
        
        <button type="button" class="pull-right searchbar-toggle collapsed" data-toggle="collapse" data-target="#masthead-searchbar" aria-expanded="false" aria-controls="navbar">
          
          <span class="sr-only"><?php _x( 'Toggle search', 'Label for search show/hide toggle', 'tube' ); ?></span>
          <i class="fa fa-fw fa-search"></i>
        </button>
        
      </div>
      
      <div id="navbar" class="navbar-collapse collapse">        
        
        <div class="hidden-xs hidden-ms">
          
            <div class="masthead-search-form navbar-form navbar-right">
              <?php get_search_form(); ?>
            </div>                        
        
        </div>
        
        <?php
        // display the main menu, if there is one
        if ( $main_menu_escaped ):
          echo $main_menu_escaped;
        endif;
        ?>        
        
      </div><!--/.nav-collapse -->
          
      <div id="masthead-searchbar" class="collapse hidden-sm hidden-md hidden-lg hidden-xl text-center">

          <div class="masthead-search-form">
            <?php get_search_form(); ?>
          </div>
        
      </div>      
        
    </div><!-- /.container -->    
      
  </nav>

<?php do_action( 'tube_after_nav' ); ?>

<?php do_action('tube_before_site_content'); ?>

<div class="site-content"><!-- .site-content -->