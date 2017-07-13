<?php
/**
 * Tube_Sidebars
 * 
 * Registers any sidebars for the .TUBE Theme
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Sidebars

{
  public static $instance;
  
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Sidebars();
      return self::$instance;
  }


  // Constructor

  function __construct()
  {
        
    // register the bottom .TUBE "sidebar"
    add_action( 'widgets_init', array( $this, 'register_bottom_sidebar') );
    
    // register the top .TUBE "sidebar"
    add_action( 'widgets_init', array( $this, 'register_top_sidebar') );
  
  }
  
   
  function register_top_sidebar( $wp_customize ){
    // set up the sidebar arguments
    $args = array(
        'name' => _x( 'Top / Above Content', 'Sidebar name', 'tube' ),
        'id' => 'top',
        'description' => _x( 'Widgets in this area will be shown ABOVE THE CONTENT on all posts and pages.', 'Sidebar description', 'tube' ),
        'before_widget' => '<div id="%1$s" class="content-block widget %2$s"><div class="container"><div class="row"><div class="col-md-10 col-md-push-1 col-lg-8 col-lg-push-2">',
        'after_widget'  => '</div></div></div></div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2><hr class="highlight" />',
    );
    
    // register it
    register_sidebar( $args );

  }
   
  function register_bottom_sidebar( $wp_customize ){
    // set up the sidebar arguments
    $args = array(
        'name' => _x( 'Bottom / Below Content', 'Sidebar name', 'tube' ),
        'id' => 'bottom',
        'description' => _x( 'Widgets in this area will be shown BELOW THE CONTENT on all posts and pages.', 'Sidebar description', 'tube' ),
        'before_widget' => '<div id="%1$s" class="content-block widget %2$s"><div class="container"><div class="row"><div class="col-md-10 col-md-push-1 col-lg-8 col-lg-push-2">',
        'after_widget'  => '</div></div></div></div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2><hr class="highlight" />',
    );
    
    // register it
    register_sidebar( $args );

  }

}