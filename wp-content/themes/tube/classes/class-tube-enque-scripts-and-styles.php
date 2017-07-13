<?php
/**
 * Tube_Enque_Scripts_And_Styles
 * 
 * Functions to enqueu the CSS and JS needed for the theme
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Enque_Scripts_And_Styles {
  
  public static $instance;
  
  
  public static function init() {
    
    if ( is_null( self::$instance ) )
        self::$instance = new Tube_Enque_Scripts_And_Styles();
    
    return self::$instance;
    
  }


  // Constructor

  function __construct() {
    
    // Enque Scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_tube_scripts' ) );    

    // Enque stylesheets
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_tube_styles' ) ); 

    // Enque stylesheets
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_google_fonts' ) );
  
  }
          
  
  // Enque JS
  
  function enqueue_tube_scripts() {    
      
    // Theme scripts
    wp_enqueue_script(
      'tube',
      get_template_directory_uri() . "/js/tube.js",
      array('jquery'),
      '1.1.1.1',
      //rand(5000, 15000),
      true // in footer
    );
    
    
    // Boostrap scripts
    wp_enqueue_script(
      'bootstrap',
      get_template_directory_uri() . "/lib/bootstrap-3.3.7-dist/js/bootstrap.min.js",
      array('jquery'),
      '3.3.7',
      true // in footer
    );  
    

    // html5 shiv.
    wp_enqueue_script( 
      'tube-html5', 
      get_template_directory_uri() . '/js/html5shiv.js', 
      array(), 
      '3.7.3' 
    );      
    wp_script_add_data( 'tube-html5', 'conditional', 'lt IE 9' );

  }
  
  
  
  // Enque CSS
  function enqueue_tube_styles() {      
       
    // Bootstrap      
    wp_enqueue_style(
      'bootstrap',
        get_template_directory_uri() . "/lib/bootstrap-3.3.7-dist/css/bootstrap.min.css",
      array( ),
      '3.3.7'
    );
           
    // Bootstrap MS      
    wp_enqueue_style(
      'bootstrap-ms',
      get_template_directory_uri() . "/css/bootstrap-ms/bootstrap-ms.css",
      array('bootstrap'),
      '3.3.7'
    );   
     
    // Font Awesome      
    wp_enqueue_style(
      'font-awesome',
      get_template_directory_uri() . "/lib/font-awesome-4.7.0/css/font-awesome.min.css",
      array('bootstrap'),
      '4.7.0'
    );  
    
    
    // Theme Styles
    wp_enqueue_style(
      'tube',
      get_stylesheet_uri(),
      array('bootstrap-ms'),
      filemtime( get_stylesheet_directory() . '/style.css' )
    );
      
      
  }


  // Enqueing of Google Fonts
  
  function enqueue_google_fonts(){    
       
    // gets an array with the current font(s) [NOTE: Default uses only one]
    $google_fonts_families = self :: get_google_fonts_families();   
    
    // create a string version of the font list
    $google_fonts_families = implode( '|', $google_fonts_families );      
    
    // get the font subsets
    $google_fonts_subsets  = self :: get_google_fonts_subsets();   

    $google_fonts_url = add_query_arg( array(
      'family' => urlencode( $google_fonts_families ),
      'subset' => urlencode( $google_fonts_subsets ),
    ), 'https://fonts.googleapis.com/css' );  

    // Register & enque the google fonts
    wp_enqueue_style(
      'tube-google-fonts',  
      $google_fonts_url,
      array('tube'),
      '0.0.1'
    ); 
      
  }
  
  // Get the current Google Font(s)  
  // TODO: Allow user to select font family in Customizer
  
  static function get_google_fonts_families() {    
        
    // Array of fonts
    $google_fonts_families = array(
      'Roboto Condensed:300,400,700',
    );
    
    // filter the google fonts
    $google_fonts_families = apply_filters( 'tube_filter_google_fonts', $google_fonts_families );   
      
    return $google_fonts_families;
      
  }
      
     
  // Get the current Google Font Subset
  
  // TODO: Allow user to select font subsets in Customizer
  
  static function get_google_fonts_subsets() {    
        
    // String of subsets
    $google_fonts_subsets = 'latin,latin-ext';
    
    // filter the google fonts subsets
    $google_fonts_subsets = apply_filters( 'tube_filter_google_fonts_subsets', $google_fonts_subsets );  
      
    return $google_fonts_subsets;
      
  }
     
  
  
     
}