<?php
/**
 * Tube_Post_Meta
 * 
 * Functions to display post meta on frontend, show hidden custom fields in admin
 * 
 * @package .TUBE
 * @subpackage Classes
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
    
class Tube_Post_Meta {
  
  public static $instance;
    
  public static function init() {
    
    if ( is_null( self::$instance ) )
      self::$instance = new Tube_Theme();
    
    return self::$instance;
    
  }    
    
    
  // Constructor
  
  function __construct() {    
                
  }

    
  

  // Display the post meta data for the post

  function get_post_meta_output( $args = NULL) {
  
    $defaults = array(
      'classes' => array('sans-serif', 'no-bottom', 'text-muted'),
      'comment_count' => false,
      'link_author' => false,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    // filter the $args
    $args = apply_filters( 'tube_filter_post_meta_args', $args );    
    
    // local var for post_id
    $post_id = get_the_id();
    
    // classes for the post meta
    $classes = $args['classes'];
    
    // filter the classes
    $classes = apply_filters( 'tube_filter_post_meta_classes', $classes );
    
    // sanitize the classes
    foreach ( $classes as $index => $class ):
      
      $classes[ $index ]  = sanitize_html_class($class);
      
    endforeach;
      
    // turn classes into a string
    $classes = implode(' ' , $classes);
    
    // Special icon for sticky posts
    // example of icon to return in the filter: <i class="fa fa-thumb-tack text-muted"></i>';      
    $sticky_icon = apply_filters( 'tube_filter_sticky_icon', '' );    
        
    // get the author ID
    $author_id = get_the_author_meta( 'ID' );
    
    // get the author URL
    $author_url = get_author_posts_url( $author_id );    
    
    // filter for author URL
    // NOTE: This is used by the .TUBE Video Curator Plugin to wipe the author URL
    // TODO: Update curator plugin to filter on author_link
    $author_url = apply_filters( 'tube_filter_post_meta_author_url', $author_url );  
    
    // if no URL, then don't link the author
    if ( $author_url == '' ):
      
        $args['link_author'] = false;
      
    endif;
    
    // get the author Display Name
    $author_displayname = get_the_author();
    
    // filter for author display name
    // NOTE: This is used by the .TUBE Video Curator Plugin to show video creator name instead of author
    // TODO: Update curator plugin to filter on get_the_author
    $author_displayname = apply_filters( 'tube_filter_post_meta_author_displayname', $author_displayname );   
  
    ob_start();
    ?>
    <ul class="list-inline list-inline-delimited post-meta <?php echo esc_attr($classes); ?>">                  
    <?php  if ( is_sticky( $post_id ) && $sticky_icon ): ?>
      <li class="sticky-icon"
        <?php echo wp_kses_post($sticky_icon); ?>      
    </li><?php endif; ?><li class="date">
      <div title="<?php echo esc_attr( get_the_time( _x('l, F jS, Y, g:i a', 'Post meta: time title attribute (use PHP date / time formatting)', 'tube'), $post_id )); ?>"><?php echo get_the_date( get_option( 'date_format' ), $post_id ); ?></div>
      </li><?php  if ( $author_displayname ): ?><li class="author">
        <span><?php if ( $args['link_author'] ) echo '<a href="'.esc_url($author_url).'">' ?><?php echo esc_html($author_displayname); ?><?php if ( $args['link_author'] ) echo '</a>' ?></span>        
      </li><?php endif; ?><?php if ( $args['comment_count'] && ( have_comments( $post_id ) || comments_open( $post_id ) ) ) : ?><li>
          <a href="#comments-wrap" class="scroll-to-hash">
            <?php 
            comments_number( 
              _x('No Comments', 'Post meta: No comments', 'tube'), 
              _x('1 Comment', 'Post meta: One comment', 'tube'), 
              _x('% Comments', 'Post meta: More than one comments', 'tube')
            );  
            ?>
          </a>
       </li><?php endif; ?>        
       </ul>
    <?php
    
    $output = ob_get_clean();
    
    // clear out any whitespace
    $output = preg_replace(array('/[\t\n]/'), array(''), $output);
  
    return $output;
  
  }
  
  
    
  
}