<?php
/**
 * The single post template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */

global $tube_theme;

// add action to show the previous / next posts links after the content
add_action('tube_after_post_content', array( $tube_theme::$tube_pagination, 'prevnext_post_links' ), 100 );
add_action('tube_before_post_content', array( $tube_theme::$tube_pagination, 'prevnext_post_links' ), 100 );

// add action to show the post terms links module (i.e. More Like This)
add_action('tube_after_post_content', array( $tube_theme::$tube_taxonomy, 'post_terms_links_module' ), 200 );

// add action to show the latest posts module before the bottom sidebar
add_action('dynamic_sidebar_before', array( $tube_theme::$tube_latest_posts, 'latest_posts_module' ), 100, 2 );
    
// columns for the masthead content area 
$masthead_columns = get_post_meta( get_the_ID(), 'tube_masthead_columns', true );

if ( ! $masthead_columns ) :
  $masthead_columns = 'col-sm-10 col-sm-push-1 col-xl-8 col-xl-push-2';
endif;

$masthead_columns = apply_filters( 'tube_filter_masthead_columns', $masthead_columns );   

// columns for the page content area 
$content_columns = get_post_meta( get_the_ID(), 'tube_content_columns', true );

if ( ! $content_columns ) :
  $content_columns = 'col-md-10 col-md-push-1 col-lg-8 col-lg-push-2';
endif;

$content_columns = apply_filters( 'tube_filter_content_columns', $content_columns );   

get_header(); 

?>        
               
<?php while ( have_posts() ) : the_post(); ?>
          
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>  

    <div class="gloryshot-wrap">
      <?php do_action('tube_gloryshot'); ?>  
    </div>
      
    <?php do_action('tube_before_masthead'); ?>
    
    <header class="page-masthead text-center ">
      
      <?php do_action('tube_before_masthead_content'); ?>
    
      <div class="page-masthead-content">
        <div class="container">
          <div class="row">  
            <div class="<?php echo esc_attr( $masthead_columns ); ?>"> 
              
                <?php do_action('tube_masthead_top'); ?>
                
                <h1 class="post-title"><?php the_title(); ?></h1>   
         
                <?php echo wp_kses_post( $tube_theme::$tube_post_meta -> get_post_meta_output( array( 'comment_count' => true ) ) ); ?> 
                
                <?php do_action('tube_masthead_bottom'); ?>                    
               
            </div> <!-- .col -->  
          </div> <!-- end .row -->      
        </div> <!-- end .container -->         
      </div> <!-- .page-masthead-content -->   
        
      <?php do_action('tube_after_masthead_content'); ?>
         
    </header> <!-- end .page-masthead -->       
    
    <?php do_action('tube_after_masthead'); ?>  
        
    <?php get_sidebar( 'top' ); ?>                                          
                
    <div class="content-block">
      <div class="container">             
         <div class="row">     
            <div class="<?php echo esc_attr( $content_columns ); ?>">       
               
              <?php do_action('tube_before_post_content'); ?>                 
    
              <div class="post-content">
                      
                <?php do_action('tube_content_top'); ?>
                
                <div class="the-content">
                  <?php the_content(); ?>
                </div> <!-- .the-content -->
                <?php
                // show the pages links
                // Note: there's a filter for the arguments in class-tube-pagination.php
                wp_link_pages();
                ?> 
                                  
                <?php do_action('tube_content_bottom'); ?>
                      
              </div> <!-- .post-content -->                     
               
              <?php do_action('tube_after_post_content'); ?>
                    
              <?php comments_template(); ?>  
               
              <?php do_action('tube_after_comments'); ?>
              <?php do_action('tube_after_post_comments'); ?>
                     
            </div> <!-- .col -->            
          </div> <!-- .row -->      
        </div> <!-- .container -->
      </div> <!-- .content-block --> 
      
      <?php get_sidebar( 'bottom' ); ?>
                
  </article>  
           
<?php endwhile; // The Loop ends here ?>     



<?php get_footer(); ?>
