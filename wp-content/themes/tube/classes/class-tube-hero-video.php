<?php
/**
 * Tube_Hero_Video
 * 
 * Adds the "Hero" video placement option to the .TUBE Video Curator Plugin
 * Adds hook to place the video inside the page masthead
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Hero_Video {
  
  
  public static $instance;  
  
  public static $current_embed;  
  
  
  public static function init() {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Hero_Video();
      return self::$instance;
  }


  // Constructor
  function __construct() {
    
    global $tube_video_curator;
    
    // make sure the tube_video_importer plugin is active
    if ( ! $tube_video_curator ) :
      
      return;
      
    endif;

    // support for additional video placement options
    add_filter( 'tube_vc_filter_video_placement_options', array( $this, 'add_hero_video_placement_option') );    
    
    
    // try to update legacy "hero-above" video placements
    add_action( 'init', array( $this, 'update_hero_above_setting'), 5 );
    
    // try to insert the video into the hero placement in the masthead
    add_action( 'template_redirect', array( $this, 'tube_check_hero_placement') );
  
  }
  
   /**
   * Attempts to update legacy "hero" or "hero-above" placement settings
   */  
  function update_hero_above_setting( ){
    
    // get the video placement option
    $video_placement = get_option( 'tube_vc_video_placement' );    
        
    // if it's not a legacy value do nothing
    if ( ( $video_placement != 'theatre' ) && ( $video_placement != 'theatre-above' ) ):
      return;
    endif;
    
    // update to new "hero" option
    update_option( 'tube_vc_video_placement', 'hero' );    
    
  }
  
  
  
   /**
   * Filter the .TUBE Plugin video placement options to include "hero"
   */
  function add_hero_video_placement_option( $placements ) {
    
    // set up the hero placement
    $hero =  array(
      'hero' => _x('<strong>Hero:</strong> Insert video inside the &#8220;hero&#8220; area, above the post title', 'Video curator video placement option description', 'tube')
    );
    
    // merge the original placements with the new placement
    $placements = array_merge($hero, $placements);
    
    // return the placements
    return $placements;
    
  }
      
    
    
    
    
  // try to insert the video into the hero placement in the masthead  
    
  function tube_check_hero_placement( ){   
    
    
    // make sure it's a singular post with an ID, else do nothing
    if ( ! ( is_singular() && get_the_ID() ) ):
      
      return;
      
    endif;
    
    
    // get the video placement option
    $video_placement = get_option( 'tube_vc_video_placement' );
    
    // make sure it's hero, else do nothing
    if ( 'hero' != $video_placement ):
      
      return;
      
    endif;
    
    // get the embed code     
    $video_embed = Tube_Video_Curator::$tube_embed -> get_video_embed( get_the_ID() );
    
    // if no video embed do nothing    
    if ( ! $video_embed ):
      
      return;
      
    endif;               
        
    // add a body class filter to add 'has-background-video' to the body class
    add_filter( 'body_class', array( $this, 'add_embed_video_to_body_class' ) ); 

    //add_action( $hook, array( Tube_Theme::$tube_images, 'open_scrim_div' ), 100 );
    add_action( 'tube_gloryshot', array( $this, 'tube_insert_video_into_masthead'), 100 );
    
    return;
    
  }
    
    
  //  add 'has-featured-video' to the body class
  function add_embed_video_to_body_class( $classes ) {
    
    $classes[] = 'has-featured-video';
    
    $classes[] = 'has-embed-video';
    
    return $classes;
    
  }
  
  
   /**
   * Attempts to insert the video into the masthead if the "hero" option is set
   */  
  function tube_insert_video_into_masthead( ){
    
    // get the embed code     
    $video_embed = Tube_Video_Curator::$tube_embed -> get_video_embed( get_the_ID() );
    
    if ( ! $video_embed ):
      return;
    endif;
    
    // display the embed
    echo '<div class="embed-sizer">' . $video_embed . '</div>';      
    
  }
  
  
  

}