<?php
/**
 * Tube_Pagination
 * 
 * Various functions to provide enhanced pagination, formatting, etc
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
 
class Tube_Pagination
{
  public static $instance;
  
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Pagination();
      return self::$instance;
  }


  // Constructor

  function __construct()
  {

    // previous / next post (used on single)
    add_filter('next_post_link', array( $this, 'custom_next_post_link_attributes' ) );
    add_filter('previous_post_link', array( $this, 'custom_previous_post_link_attributes' ) );
    
    // previous / next page (used on paginated single)
    add_filter('wp_link_pages_args', array( $this, 'custom_wp_link_pages_args' ) );
    
    // previous / next image (used on attachment)
    add_filter('next_image_link', array( $this, 'custom_next_image_link_attributes' ) );
    add_filter('previous_image_link', array( $this, 'custom_previous_image_link_attributes' ) );
    
    // previous / next posts (used on archives if not numbered pagination)
    add_filter( 'next_posts_link_attributes', array( $this, 'custom_next_posts_link_attributes' ) );
    add_filter( 'previous_posts_link_attributes', array( $this, 'custom_previous_posts_link_attributes' ) );
    
    // customize the navigation markup template (used for numeric pagination)   
    add_filter( 'navigation_markup_template', array( $this, 'custom_navigation_markup_template' ) );
    
    //  filter the pagination label
    add_filter( 'tube_filter_pagination_label', 'wptexturize' );   
    
  }
  
 
  // add custom attributes to the previous post link
  function custom_previous_post_link_attributes( $link ){
    
    $attributes = 'class="btn btn-sm btn-primary previous"';
      
    $attributes = apply_filters( 'tube_filter_previous_post_link_attributes', $attributes);
    
    return str_replace('<a href=', '<a '.$attributes.' href=', $link);
    
  } 
    
  // add custom attributes to the next post link
   function custom_next_post_link_attributes( $link ){
    
    // TODO: Create array of classes, allow for filtering, sanitize class names
    
    $attributes = 'class="btn btn-sm btn-primary next"';
      
    $attributes = apply_filters( 'tube_filter_next_post_link_attributes', $attributes);
    
    return str_replace('<a href=', '<a '.$attributes.' href=', $link);
    
  }
   
    
  // customize paginator for a paginated single post
   function custom_wp_link_pages_args( $params ){
    
      // set up the link_pages arguments (for paginated posts)        
      $link_pages_args =  array(
        'before'      => '<div class="pagination-wrap page-links"><span class="page-links-title">' . _x( 'Pages:', 'Paginated post pagination label', 'tube' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . _x( 'Page', 'Paginated post pagination page label for screenreaders', 'tube' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
        'echo'             => 1
      );
      
      return $link_pages_args;      

  }
  
    
  // links to go to prev / next post (used on single post page)  
  
  // This serves as a wrapper for 'next_post_link / previous_post_link' to add formatting, etc
  
  function get_prevnext_post_links( $args = NULL ) {
    
    // get the current post object
    $the_post = get_queried_object(); 
    
    // get the current post's post type
    $post_type_obj = get_post_type_object( $the_post -> post_type );
  
    // get the singular name of the post type
    $post_type_name = $post_type_obj->labels->singular_name;
  
  
    $prev_label_text = sprintf(
      '%1$s %2$s', 
      _x('Previous', 'Pagination: prev / next post links (used on single post page)', 'tube'), 
      esc_html( $post_type_name )
    );
        
    $next_label_text = sprintf(
      '%1$s %2$s', 
      _x('Next', 'Pagination: prev / next post links (used on single post page)', 'tube'), 
      esc_html( $post_type_name )
    );
        
    
    // set the default labels
    $defaults = array(
      'prev_label' => '<span class="btn-icon"><i class="fa fa-chevron-circle-left"></i></span> <span class="btn-text">' . $prev_label_text . '</span>',
      'next_label' => '<span class="btn-text">' . $next_label_text . '</span> <span class="btn-icon"><i class="fa fa-chevron-circle-right"></i></span></span>',
      'in_same_term' => false,
      'excluded_terms' => '',
      'taxonomy' => 'category'
    );  
        
    // parse the arguments
    $args = wp_parse_args( $args, $defaults );   
    
    // filter the arguments
    $args = apply_filters( 'tube_filter_prevnext_post_links_args', $args );    
    
    // get the next post link
    $next_post_link = get_next_post_link('%link', $args['next_label'], $args['in_same_term'], $args['excluded_terms'], $args['taxonomy']);
    
    // get the previous post link
    $prev_post_link = get_previous_post_link('%link', $args['prev_label'], $args['in_same_term'], $args['excluded_terms'], $args['taxonomy']);   
    
    // if neither previous nor next, do nothing
    if ( ! $next_post_link && ! $prev_post_link ):
      return;
    endif;
    
    // genereate the prev / next post links HTML
    ob_start();
    ?>
    <div class="pagination-wrap pagination-prevnext clearfix"> 
        
      <?php 
      if ( $prev_post_link ):
        echo $prev_post_link;
      endif;
      
      if ( $next_post_link ):
        echo $next_post_link;
      endif;
      ?>
        
    </div>
    
    <?php
    
    // grab the output and return
    $output = ob_get_clean();
    
    return $output;
    
  }
  
  
  // output the custom prevnext_post_links
  function prevnext_post_links( $args = NULL ) {
    
    $prevnext_post_links = $this -> get_prevnext_post_links($args);
    
    echo wp_kses_post( $prevnext_post_links ); 
    
  }
  
  
  
  
  
 
  // add custom attributes to the previous image link
  function custom_previous_image_link_attributes( $link ){
    
    $attributes = 'class="btn btn-sm btn-primary previous"';
      
    $attributes = apply_filters( 'tube_filter_previous_image_link_attributes', $attributes);
    
    return str_replace('<a href=', '<a '.$attributes.' href=', $link);
    
  } 
    
  // add custom attributes to the next image link
   function custom_next_image_link_attributes( $link ){
    
    // TODO: Create array of classes, allow for filtering, sanitize class names
    
    $attributes = 'class="btn btn-sm btn-primary next"';
      
    $attributes = apply_filters( 'tube_filter_next_image_link_attributes', $attributes);
    
    return str_replace('<a href=', '<a '.$attributes.' href=', $link);
    
  }
   
   
  
    
  // links to go to prev / next image (used on single attachment page)  
  
  // This serves as a wrapper for 'next_post_link / previous_post_link' to add formatting, etc
  function get_prevnext_image_links( $args = NULL ){
  
    $prev_label_text = _x('Previous Image', 'Pagination: prev / next image links (used on single attachment page)', 'tube');
    $next_label_text = _x('Next Image', 'Pagination: prev / next image links (used on single attachment page)', 'tube');
    
    // set the default labels
    $defaults = array(
      'prev_label' => '<span class="btn-icon"><i class="fa fa-chevron-circle-left"></i></span> <span class="btn-text">' . $prev_label_text . '</span>',
      'next_label' => '<span class="btn-text">' . $next_label_text . '</span> <span class="btn-icon"><i class="fa fa-chevron-circle-right"></i></span></span>'
    );  
    
    // parse the arguments
    $args = wp_parse_args( $args, $defaults );    
  
    // filter the arguments
    $args = apply_filters( 'tube_filter_prevnext_image_link_args', $args );    
    
    // get the next image link
    ob_start();
    next_image_link(false, $args['next_label']);
    $next_image_link = ob_get_clean();
    
    
    // get the previous image link
    ob_start();
    previous_image_link(false, $args['prev_label']); 
    $prev_image_link = ob_get_clean();
    
    // if neither previous nor next, do nothing
    if ( ! $next_image_link && ! $prev_image_link ):
      return;
    endif;
    
  
    ob_start();
    ?>
    <div class="pagination-wrap pagination-prevnext clearfix">         
        
      <?php 
      if ( $prev_image_link ):
        echo $prev_image_link;
      endif;
      
      if ( $next_image_link ):
        echo $next_image_link;
      endif;
      ?>
        
    </div>
    <?php
    
    $output = ob_get_clean();
    
    return $output;
         
  }
  
  
  // output the custom prevnext_post_links
  function prevnext_image_links( $args = NULL ) {
    
    $prevnext_image_links = $this -> get_prevnext_image_links( $args );
    
    echo wp_kses_post( $prevnext_image_links ); 
    
  }
  
  
  
  
  
  
  
  
 
  // add custom attributes to the previous posts link
  function custom_previous_posts_link_attributes( $attr ){  
    
    $attributes = 'class="previous btn btn-sm btn-default"';
      
    $attributes = apply_filters( 'tube_filter_previous_posts_link_attributes', $attributes);
    
    return $attributes;
    
  } 
    
    
  // add custom attributes to the next posts link
   function custom_next_posts_link_attributes( $attr ){  
    
    $attributes = 'class="next btn btn-sm btn-default"';
      
    $attributes = apply_filters( 'tube_filter_next_posts_link_attributes', $attributes);
    
    return $attributes;
    
  }
 
 
  // gets a custom formatted version of get_posts_nav_link
  
  function get_custom_posts_nav_link( $args = NULL ) {
    
    
    // set the defaults

    $defaults = array(
      'sep'       => '',  
      'prelabel'  => sprintf( 
        '<span class="btn-icon"><i class="fa fa-chevron-circle-left"></i></span> <span class="btn-text">%1$s</span>', 
        _x('Previous', 'Pagination: prev / next posts nav (used on archive pages)', 'tube') 
      ),  
      'nxtlabel'  => sprintf( 
        '<span class="btn-text">%1$s</span> <span class="btn-icon"><i class="fa fa-chevron-circle-right"></i></span>', 
        _x('Next', 'Pagination: prev / next posts nav (used on archive pages)', 'tube') 
      )
    );  
    
    // parse the arguments
    $args = wp_parse_args( $args, $defaults );    
  
    // filter the arguments
    $args = apply_filters( 'tube_filter_posts_nav_link_args', $args );    
    
    // call the native get_posts_nav_link
    $posts_nav_link = get_posts_nav_link( $args );        
    
    // if no posts nav link, do nothing
    if ( ! $posts_nav_link ):
      return;
    endif;
    
    // generate the posts_nav_link HTML
    ob_start();
    ?>
    
    <div class="pagination-wrap pagination-archive pagination-prevnext clearfix">
      <?php echo $posts_nav_link; ?>
    </div>
    
    <?php    
    
    // grab the output and return
    $output = ob_get_clean();
    
    return $output;
    
  }

    
  // output custom formatted version of get_posts_nav_link
  
  function custom_posts_nav_link( $args = NULL ) {
    
    $posts_nav_link = $this -> get_custom_posts_nav_link($args);
    
    echo wp_kses_post( $posts_nav_link ); 
    
  }


  
  function custom_navigation_markup_template(){
    
    $template = '<nav class="navigation pagination-wrap pagination-archive pagination-paged %1$s" role="navigation"><h2 class="screen-reader-text">%2$s</h2><div class="nav-links">%3$s</div></nav>';
    
    return $template;
    
  }
    
     
     
     
  // Custom page number based pagination for archives
  
  // This is a wrapper for get_the_posts_pagination
  // allowing customization and filtering of the arguments
  
  // NOTE: Some plugins that depend on the_posts_pagination may not work as expected
  
  // ATTN THEME REVIEWER: This is an OK way to replace WP functionality per...
  // https://wordpress.slack.com/archives/themereview/p1479250498001468
  
  function get_custom_the_posts_pagination( $the_query = NULL, $args = '' ) {
     
    $defaults = array(
    
      'prev_text' => '<i class="fa fa-chevron-circle-left"></i>',
      'next_text' => '<i class="fa fa-chevron-circle-right"></i>',
      'mid_size' => 2,

    );

    $args = wp_parse_args($args, $defaults);

    $args = apply_filters( 'tube_filter_the_posts_pagination_args', $args );   
   
    // get the posts pagination
    $pagination = get_the_posts_pagination( $args );
    
    // return the pagination    
    return $pagination; 
    
  }    
    
  
  // output custom formatted version of get_the_posts_pagination
  
  function custom_the_posts_pagination( $the_query = NULL, $args = '' ) {  
    
    $the_posts_pagination = $this -> get_custom_the_posts_pagination( $the_query, $args );
    
    echo wp_kses_post( $the_posts_pagination ); 
    
  }
        
    
  // creates a label to use on paginated archives (e.g. Page X of Y)
  
  function get_paginated_query_label( $query = NULL, $do_on_first_page = false){

    global $wp_query;
    
    // if no query passed in, use global wp_query
    if ( ! $query ):
      
      $query = $wp_query;
      
    endif;  
    
    // get the current pagination number, default to 1
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1 ; 
    
    // if first page and NOT boolean to return on first page is true, do nothing
    if ( $paged == 1 && ! $do_on_first_page ): 
      
      return;
      
    endif;

    // get the raw label with placeholders
    
    $label_raw = _x( 'Page %1$d of %2$s', 'Paginated archives label', 'tube' );   
    
    // allow filtering of the raw label
    $label_raw = apply_filters( 'tube_filter_paginated_query_label_raw', $label_raw );
    
    // if no label, do nothing
    if ( ! $label_raw ):
      
      return;
      
    endif;
    
    // replace the placeholders and percent signs        
    $paginated_query_label = sprintf(  $label_raw, intval( $paged ), intval( $query->max_num_pages ) );
             
    // filter the composed label         
    $paginated_query_label = apply_filters( 'tube_filter_paginated_query_label', $paginated_query_label );
    
    // return the pagination label
    return $paginated_query_label;
    
  }

}
 

    

  
   