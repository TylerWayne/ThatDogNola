<?php
/**
 * Tube_Customizer
 * 
 * Setttings to add capabilities and tweak the Customizer
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Customizer {
  
  public static $instance;  
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Customzier();
      return self::$instance;
  }


  // Constructor
  function __construct()
  {
  
    // rename "Site Identity" section
    add_action( 'customize_register', array( $this, 'rename_sections' ) );  
  
  }        
   
       
  // change the "Site Identity" title to something more user friendly
  function rename_sections( $wp_customize ) {    
     
     // rename title_tagline section  
     $wp_customize->get_section('title_tagline')->title = _x(  'Logo, Title & Tagline', 'Customizer: site identity section title', 'tube' );
     
     // rename header media section  
     $wp_customize->get_section('header_image')->title = _x( 'Home / Latest Posts Media', 'Customizer: Home / latest posts media section title', 'tube' );
     
  } 

        
}