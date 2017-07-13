<?php
/**
 * The 404 page not found template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */

        
// columns for the masthead content area 
$masthead_columns = apply_filters( 'tube_filter_masthead_columns', 'col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 col-xl-6 col-xl-push-3' );  

get_header(); 
?>

<div class="gloryshot-wrap">
  <?php do_action('tube_gloryshot'); ?>  
</div>
  
<?php do_action('tube_before_masthead'); ?>

<header class="page-masthead text-center">
  <div class="page-masthead-content">        
    <div class="container">
      <div class="row">     
        <div class="<?php echo esc_attr( $masthead_columns ); ?>"> 
              
            
              <?php do_action('tube_masthead_top'); ?>
            
              <h1 class="post-title"><?php _ex( 'Sorry!', '404 page H1 in page masthead', 'tube'); ?></h1>                   
              
              <p class="lead excerpt">
                <?php sprintf( _ex( 'Page not found. Please start at the <a href="%1$s">home page</a>.', '404 page next steps message', 'tube'), esc_url(home_url()) ); ?>
              </p>
              
              <div class="tube-page-search-form">
                <?php get_search_form(); ?>               
              </div>
                        
              <?php do_action('tube_masthead_bottom'); ?>
          
        </div> <!-- .col -->  
      </div> <!-- end .row -->      
    </div> <!-- end .container -->         
  </div> <!-- .page-masthead-content -->      
</header> <!-- end .page-masthead -->    
      

<?php do_action('tube_after_masthead'); ?>

<?php get_sidebar( 'bottom' ); ?>

<?php get_footer(); ?>