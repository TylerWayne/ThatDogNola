<?php
/**
 * Tube_Custom_Logo
 * 
 * Add custom logo support to the Theme
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
    
class Tube_Custom_Logo {
  
  public static $instance;
  
  public static function init() {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Custom_Logo();
      return self::$instance;
  }  
  
  // Constructor    
  function __construct() {
    
    // Add custom logo support
    add_action( 'after_setup_theme', array( $this, 'add_custom_logo_support' ) );  
    
  }
    
  // function to add custom logo spoort in customizer
  function add_custom_logo_support(){
    
    // arguments for add theme support
    $custom_logo_args =  array(
      'flex-height' => true,
      'flex-width'  => true
    );
  
    // filter the arguments
    $custom_logo_args = apply_filters('tube_filter_custom_logo_args', $custom_logo_args);
    
    // add the theme support
    add_theme_support( 'custom-logo', $custom_logo_args );
    
  }

  
}