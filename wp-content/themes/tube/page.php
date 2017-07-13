<?php
/**
 * The static page template 
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
   
// TODO: Page level setting for showing excerpt under title
$show_excerpt_on_page = apply_filters( 'tube_show_excerpt_on_page', true );  

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
            
            <?php if (  $show_excerpt_on_page && get_the_excerpt() ): ?>
               <div class="excerpt">
                 <?php the_excerpt(); ?>
               </div>  
            <?php endif; ?>
            
            <?php do_action('tube_masthead_bottom'); ?>
            
          </div> <!-- .col -->  
        </div> <!-- .row -->      
      </div> <!-- .container -->        
    </div> <!-- .page-masthead-content -->  
    
    <?php do_action('tube_after_masthead_content'); ?>
      
  </header> <!-- .page-masthead -->        

  <?php do_action('tube_after_masthead'); ?>
  
  <?php get_sidebar( 'top' ); ?>
  
  <div class="content-block">
    <div class="container">
      <div class="row">     
        <div class="<?php echo esc_attr( $content_columns ); ?>"> 
            
            <?php do_action('tube_before_page_content'); ?>           
          
            <div class="page-content">
              
              <?php do_action('tube_content_top'); ?>
              
              <?php the_content(); ?> 
              
              <?php do_action('tube_content_bottom'); ?>
              
            </div><!-- .page-content  -->  
            
            <?php do_action('tube_after_page_content'); ?>    
                    
            <?php comments_template(); ?>
            
            <?php do_action('tube_after_comments'); ?>
            <?php do_action('tube_after_page_comments'); ?>  
            
                  
        </div> <!-- .col -->  
      </div> <!-- .row -->      
    </div> <!-- .container -->
  </div> <!-- .content-block --> 
  
<?php endwhile; ?>      

<?php get_sidebar( 'bottom' ); ?>

<?php get_footer(); ?>