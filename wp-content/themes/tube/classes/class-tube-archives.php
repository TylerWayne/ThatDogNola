<?php
/**
 * Tube_Archives
 * 
 * Functions to customize Archive titles, etc
 * 
 * @package .TUBE
 * @subpackage Classes
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.1.2
 */
 
    
class Tube_Archives {
  
  public static $instance;
    
  public static function init() {
    
    if ( is_null( self::$instance ) )
      self::$instance = new Tube_Archives();
    
    return self::$instance;
    
  }    
    
    
  // Constructor  
  function __construct() {              

    // customize archive titles to remove prefix
    add_filter('get_the_archive_title', array( $this, 'customize_the_archive_title' ) );
    
    // do a wpautop filter on the archive description
    add_filter( 'get_the_archive_description', 'wpautop', 15 );
                      
  }

    
    
  // Return an alternate title, without prefix, for every type used in the get_the_archive_title().
  // http://wordpress.stackexchange.com/a/203884/60021
  function customize_the_archive_title($title) {
    
    if ( is_category() ):
      
        $title = single_cat_title( '', false );
      
    elseif ( is_tag() ):
      
        $title = single_tag_title( '', false );
        
    elseif ( is_author() ):
      
        $title = '<span class="vcard">' . get_the_author() . '</span>';
        
    elseif ( is_year() ):
      
        $title = get_the_date( _x( 'Y', 'yearly archives title label date format', 'tube' ) );
        
    elseif ( is_month() ):
      
        $title = get_the_date( _x( 'F Y', 'monthly archives title label date format', 'tube' ) );
        
    elseif ( is_day() ):
      
        $title = get_the_date( _x( 'F j, Y', 'daily archives title label date format', 'tube' ) );
        
    elseif ( is_tax( 'post_format' ) ):
      
        if ( is_tax( 'post_format', 'post-format-aside' ) ):
          
            $title = _x( 'Asides', 'post format archives title label', 'tube' );
          
        elseif ( is_tax( 'post_format', 'post-format-gallery' ) ):
          
            $title = _x( 'Galleries', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-image' ) ):
          
            $title = _x( 'Images', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-video' ) ):          
          
            $title = _x( 'Videos', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-quote' ) ):
          
            $title = _x( 'Quotes', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-link' ) ):
          
            $title = _x( 'Links', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-status' ) ):
          
            $title = _x( 'Statuses', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-audio' ) ):
          
            $title = _x( 'Audio', 'post format archives title label', 'tube' );
            
        elseif ( is_tax( 'post_format', 'post-format-chat' ) ):
          
            $title = _x( 'Chats', 'post format archives title label', 'tube' );
            
        endif;
        
    elseif ( is_post_type_archive() ):
      
        $title = post_type_archive_title( '', false );
        
    elseif ( is_tax() ):
      
        $title = single_term_title( '', false );
        
    elseif ( is_search() ):
      
        $title = NULL;
        
    else:
      
        $title = _x( 'Archives', 'Generic archive title label', 'tube');
        
    endif;
    
    return $title;
  }
    
  
  // Get the label text for the top of the archive (index.php)      
  function  get_archive_label(){

    $label = NULL;
    
    if ( is_author() ) : 
                
      $label = _x('Author', 'Author archive masthead label', 'tube');
    
    elseif ( is_category() ) :                  
      
      $label = _x('Category', 'Category archive masthead label', 'tube');
    
    elseif ( is_tag() ) :                    
        
        $label = _x('Tag', 'Tag archive masthead label', 'tube');
    
    elseif ( is_tax() ) :
      
        $the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );                  
        
        $label = $the_tax->labels->name;
        
    elseif ( is_day() ) :                 
        
        $label = _x('Day', 'Day archive masthead label', 'tube');
          
    elseif ( is_month() ) :         
        
        $label = _x('Month', 'Month archive masthead label', 'tube');                    
          
    elseif ( is_year() ) : 
        
        $label = _x('Year', 'Year archive masthead label', 'tube');
        
    elseif ( is_search() ) :       
  
        $label = Tube_Theme::$tube_labels -> get_search_results_header_label();                  
          
    elseif ( is_post_type_archive() ) :
       
        $label= NULL; 
       
        $label = apply_filters( 'tube_post_type_archive_label', $label );
     
    endif;
               
    return $label;
    
  }
  
}

