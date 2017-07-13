<?php
/**
 * Tube_Lastest_Posts
 * 
 * Functions to show the "latest posts"
 * 
 * @package .TUBE
 * @subpackage Classes
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
    
class Tube_Lastest_Posts {
  
  public static $instance;

  private static $latest_posts_module_posts_per_page;  
    
  public static function init() {
    
    if ( is_null( self::$instance ) )
      self::$instance = new Tube_Lastest_Posts();
    
    return self::$instance;
    
  }    
    
    
  // Constructor  
  function __construct() {              

    // set the default number of posts in the latest posts module
    add_action( 'init', array( $this, 'set_latest_posts_module_default_posts_per_page' ) );
                      
  }
      
   
  // set the default number of posts in the latest posts module  
  function set_latest_posts_module_default_posts_per_page(  ) {
    
    self::$latest_posts_module_posts_per_page = 3;
    
  }
   
  // set the number of posts in the latest posts module  
  function set_latest_posts_module_posts_per_page( $posts_per_page ) {
    
    self::$latest_posts_module_posts_per_page = $posts_per_page;
    
  }
    
  // get the number of posts in the latest posts module  
  function get_latest_posts_module_posts_per_page(  ) {
    
    $posts_per_page = self::$latest_posts_module_posts_per_page;
      
    $posts_per_page = apply_filters( 'tube_filter_latest_posts_module_posts_per_page', $posts_per_page );
    
    $posts_per_page = intval( $posts_per_page );
    
    return $posts_per_page;
    
  }
  
  
  
  // returns the URL for the posts page, home or otherwise
  
  function get_posts_page_url() {
    
    // get a link to all posts
    if ( ( 'page' == get_option( 'show_on_front' ) ) && get_option( 'page_for_posts' ) ) :
      
      return get_permalink( get_option( 'page_for_posts' ) );
      
    else:
      
      return home_url( '' );
      
    endif;
    
  }
    
    
  // Content block of the latest posts
  // This is triggered on dynamic_sidebar
  
  function latest_posts_module( $sidebar_index, $sidebar_active ) {
    
    // make sure we're on the bottom sidebar
    if ( 'bottom' != $sidebar_index ) return;
    
    $posts_per_page = $this -> get_latest_posts_module_posts_per_page();
     
    $query_args =  array( 
      'post__not_in' => array( get_the_id() ), // ignore the current post
      'posts_per_page' => $posts_per_page, // show up to three posts per page
      'ignore_sticky_posts' => 1
     );
  
    $latest_posts_query = new WP_Query( $query_args );
    
    // if no posts, do nothing
    if ( ! $latest_posts_query || ! $latest_posts_query->have_posts() ):
      return;
    endif;    
      
    // get the posts list heading
    $posts_list_heading = Tube_Theme::$tube_labels -> get_posts_list_heading( );
    
    // get the button text
    $all_posts_button_text = Tube_Theme::$tube_labels -> get_all_posts_button_text( );
    
    // get the all posts URL
    $all_posts_url = $this -> get_posts_page_url();    
    
    ?>
      
      <div class="content-block latest-posts">
        <div class="container">
          <div class="row">     
            <div class="col-md-12 col-xl-10 col-xl-push-1">
                                
              <h3><?php echo wp_kses_post($posts_list_heading); ?> </h3>
              
              <hr class="highlight" />
              
              <ul class="list-posts list-posts-grid grid-123 clearfix">
                
                <?php                 
                while ( $latest_posts_query->have_posts() ) :
                  
                  // setup the post data
                  $latest_posts_query->the_post(); 
                  
                  // get the partial for post list
                  get_template_part( 'partials/post-list' ); 
                  
                endwhile; 
                
                // reset the post data from latests posts query
                wp_reset_postdata();
                ?>
              </ul>
              
              <div class="pagination-wrap">
                <a href="<?php echo esc_url( $all_posts_url ); ?>" class="btn btn-primary">
                  <?php echo wp_kses_post($all_posts_button_text); ?>
                </a>
              </div> <!-- .pagination-wrap -->
              
            </div> <!-- .col -->  
          </div> <!-- /.row -->      
        </div> <!-- /.container -->
      </div> <!-- /.content-block--> 
    
    <?php
   
    
  }
  
  
    
  
}