<?php
/**
 * The index template (for home / posts, archives, etc)
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */ 
 
// get the posts list heading
$posts_list_heading = Tube_Theme::$tube_labels -> get_posts_list_heading();
                
// get the no results heading
$no_results_heading = Tube_Theme::$tube_labels -> get_no_results_heading();

// get the paged title (in functions-pagination.php)
$paginated_query_label = Tube_Theme::$tube_pagination -> get_paginated_query_label();

$archive_label = Tube_Theme::$tube_archives -> get_archive_label();

// special stuff for the home page
if ( is_home() ):    
  
  // test if we're on the first page
  if ( ! get_query_var('paged') || ( get_query_var('paged') == 1 ) ):
    
    // Get the sticky / features posts heading
    $sticky_posts_heading = Tube_Theme::$tube_labels -> get_sticky_posts_heading( );
    
    // Get Sticky Posts
    $sticky_posts_query = Tube_Theme::$tube_home_page -> get_sticky_posts();
    
  endif;

endif;
          
  
// special stuff for search results
if ( is_search() ):  
  
  $posts_list_heading = sprintf( _x('Results for %1$s', 'Search results heading', 'tube'), esc_attr( get_search_query() ) );
  
  $search_no_results_message = Tube_Theme::$tube_labels -> get_search_no_results_message();
  
endif;

        
// columns for the masthead content area 
$masthead_columns = apply_filters( 'tube_filter_masthead_columns', 'col-sm-10 col-sm-push-1 col-xl-8 col-xl-push-2' );   


// columns for the page content area 
$content_columns = apply_filters( 'tube_filter_content_columns', 'col-md-12 col-xl-10 col-xl-push-1' );  


// standard WP header                           
get_header(); 
?>
  
         
    
  <div class="gloryshot-wrap <?php if ( $paginated_query_label ) echo ' gloryshot-wrap-xs'; ?>">
    <?php do_action('tube_gloryshot'); ?>  
  </div>
 
 <?php do_action('tube_before_masthead'); ?>
    
    <header class="page-masthead <?php //if ( $paginated_query_label ) echo ' page-masthead-xs'; ?> text-center">
    
      <?php do_action('tube_before_masthead_content'); ?>
 
    
      <div class="page-masthead-content">  
        <div class="container">
          <div class="row">     
            <div class="<?php echo esc_attr( $masthead_columns ); ?>"> 
                 
                <?php do_action('tube_masthead_top'); ?>
                
                <?php 
                if ( is_home() ) : 
                  
                  do_action('tube_home_masthead_content'); 
                    
                else:
                ?>
                                  
                  <?php if ( $archive_label ) : ?>
                    <div class="label label-default">
                      <?php echo wp_kses_post( $archive_label ); ?>
                    </div>
                  <?php endif; ?>
                    
                  <?php the_archive_title( '<h1 class="post-title">', '</h1>' ); ?>
                  
                  <?php the_archive_description( '<div class="excerpt">', '</div>' ); ?>
                  
                  <?php if ( is_search() ) : ?>
                    <div class="tube-page-search-form">
                      <?php get_search_form(); ?>               
                    </div>
                  <?php endif; ?>
                      
                <?php endif; ?>
                      
                <?php do_action('tube_masthead_bottom'); ?>
                  
            </div> <!-- .col -->  
          </div> <!-- end .row -->      
        </div> <!-- end .container -->         
      </div> <!-- .page-masthead-content -->
      
      <?php do_action('tube_after_masthead_content'); ?>
      
    </header> <!-- end .content-block -->           

  <?php  do_action('tube_after_masthead'); ?>
  
  <?php get_sidebar( 'top' ); ?>

  <div class="content-block">
    <div class="container">
      <div class="row">
        <div class="<?php echo esc_attr( $content_columns ); ?>">  
            
          <?php do_action('tube_before_page_content'); ?> 
          
          <div class="page-content">
            
              <?php do_action('tube_content_top'); ?>                
          

              <?php 
              // see if there are any sticky posts
              if ( isset($sticky_posts_query) && $sticky_posts_query && $sticky_posts_query->have_posts() ):                 
                ?>
                                          
                <h3>
                  <?php echo wp_kses_post($sticky_posts_heading); ?>
                </h3>
                
                <hr class="highlight" />
                
                <ul class="list-posts list-posts-grid grid-12 clearfix">
                  <?php                   
                  while ( $sticky_posts_query->have_posts() ) :
                    
                    // setup post data
                    $sticky_posts_query->the_post(); 
                    
                    // set the image size
                    $sticky_post_image_size = apply_filters( 'tube_sticky_post_image_size', 'tube-img-md-cropped');
                    
                    // add image size to post object (it gets used in the partial)
                    set_query_var( 'tube_post_list_image_size', $sticky_post_image_size );
                    
                    // get the partial for post list
                    get_template_part( 'partials/post-list' ); 
                    
                    // reset the image size
                    set_query_var( 'tube_post_list_image_size', NULL );
                    
                  endwhile; 
              
                  // reset the post data from the sticky query
                  wp_reset_postdata();
                  ?>
                </ul>
                        
              <?php 
              
              endif; 
    
              ?>


              <?php if ( have_posts() ) : ?>
                
                  <h3>
                    <?php echo wp_kses_post( $posts_list_heading ); ?> 
                    <small>
                      <?php echo wp_kses_post( $paginated_query_label ); ?>
                    </small>
                  </h3>
                  
                  <hr class="highlight" />
    
                  <ul class="list-posts list-posts-grid grid-123 clearfix">
                    <?php      
                    // Start the loop.
                    while ( have_posts() ) : the_post();      
                      get_template_part( 'partials/post-list' ); 
                    // End the loop.
                    endwhile;
                    ?>
                  </ul>
                  
                  <?php 
                                        
                  // TODO:: Theme customization for pagenums vs prevnext
                  
                  $pagination_type = 'paginated';
                  
                  switch ($pagination_type):
                    
                    case 'prevnext':
                      
                      Tube_Theme::$tube_pagination -> custom_posts_nav_link();
                      break;
                    
                    default:
                      Tube_Theme::$tube_pagination -> custom_the_posts_pagination();
                      break;
                      
                  endswitch;

                else: // no posts for this archive or query ?>                  
                
                  <h3>
                    <?php echo wp_kses_post( $no_results_heading ); ?> 
                    <?php if ( is_search() ): ?>
                     <small><?php echo wp_kses_post( $search_no_results_message );  ?></small>
                    <?php endif; ?>                     
                  </h3>
                  
              <?php endif; ?>
    
              <?php  do_action('tube_content_bottom'); ?>
              
          </div><!-- .page-content -->
            
          <?php do_action('tube_after_page_content'); ?>    
            
        </div> <!-- /.col -->
      </div> <!-- end .row -->
    </div> <!-- end .container -->
  </div> <!-- end .content-block --> 

<?php get_sidebar( 'bottom' ); ?>

<?php get_footer(); ?>