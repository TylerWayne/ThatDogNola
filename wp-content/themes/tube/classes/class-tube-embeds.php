<?php
/**
 * Tube_Embeds
 * 
 * Functions to customize oembed codes
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Embeds {
  
  public static $instance;
  
  
  public static function init() {
    
    if ( is_null( self::$instance ) )
        self::$instance = new Tube_Embeds();
    
    return self::$instance;
    
  }


  // Constructor

  function __construct() {

    // filter to parse oembeds
    add_filter('oembed_dataparse', array( $this, 'custom_oembed_dataparse' ), 90, 3);
  
  }
  
  
  // adds custom markup / wrapper to oembed HTML
    
  function custom_oembed_dataparse( $return, $data, $url ) {
  
    // parse the oembed URL
    $url_parsed = parse_url($url);
    
    // assume no provider
    $provider_name_class = NULL;
        
    // try to get the URL host
    if ( property_exists($data, 'provider_name') ):
      
      // isolate the host
      $provider_name_class = 'embed-' . strtolower( $data -> provider_name );
      
    endif;
    
    // assume no padding for wrapper div  
    $padding_style = NULL;    
        
    // assume no custom responsive embeds
    $responsive_class = NULL;    
      
    // get the width
    $width = property_exists($data, 'width') ? intval( $data -> width ) : NULL;
    
    // get the height  
    $height = property_exists($data, 'height') ? intval( $data -> height ) : NULL;
                       
    // test to see if known width and height
    if ( $width && $height ):
      
      // calculate the padding
      $padding_style = ' padding-bottom: ' . ($height / $width) * 100 . '%';
      
      // set responsive class
      $responsive_class = 'embed-responsive';
    
    endif;
  
    // create the new oembed snippet
    $output = '<div class="embed-wrap ' . esc_attr($provider_name_class) . ' ' . esc_attr($responsive_class) . '" style="' . esc_attr($padding_style) . '">' . $return . '</div>';
    
    // return the composed HTML
    return $output;
    
  }

}