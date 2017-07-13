<?php
/**
 * Tube_Theme_Supports Class
 * 
 * Adds various theme supports like thumnails, title tags, etc
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
 
/**
 * .TUBE Tube_Theme_Supports Class
 */

class Tube_Theme_Supports

{
  public static $instance;
  
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Theme_Supports();
      return self::$instance;
  }


  // Constructor

  function __construct()
  {
      
    add_action( 'after_setup_theme', array( $this, 'tube_theme_support' ) );  
    
    add_action( 'init', array( $this, 'tube_add_excerpts_to_pages' ) );   
  
  }

   
        
  // add textdomain and various theme supports
  function tube_theme_support() {
    
    // Load the text domain
    load_theme_textdomain( 'tube', get_template_directory() .'/languages' );
    
        
    // Add title tag theme support
    add_theme_support( 'title-tag' );
    
    
    // Add thumbnail theme support
    add_theme_support( 'post-thumbnails' );
    
    
    // Add Automatic Feed Links theme support
    add_theme_support( 'automatic-feed-links' );
    
    
    // Add HTML5 theme support
    $supports_html5 = array(
      'comment-form',
      'gallery',
      'caption',
    );
    
    add_theme_support( 'html5', $supports_html5 );    
        
          
    // Add custom header theme support
    $args = array(
      'width'         => '1480',
      'height'        => '832',
      'flex-height'   => true,
      'flex-width'    => true,
      'header-text'   => false,
      'video'         => true,
      'video-active-callback' => array( $this, 'check_allow_header_video' ),
    );    
    
    add_theme_support( 'custom-header', $args );
    
  }
          
        
  // add excerpts to the page post type
  function tube_add_excerpts_to_pages() {    
        
    // Add excerpt capability to pages
    add_post_type_support( 'page', 'excerpt' );
    
  }
  
  
        
          
        
  // see if we're on a home (i.e. latest posts) that also the front page
  function check_allow_header_video() {    
        
    return ( is_front_page() && is_home() );
    
  }
  

        
}