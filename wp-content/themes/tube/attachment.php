<?php
/**
 * The single image template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.1.2
 */
 
global $tube_theme;

// add action to show the previous / next posts links after the content
add_action('tube_before_post_content', array( $tube_theme::$tube_pagination, 'prevnext_image_links' ), 100 );
add_action('tube_after_post_content', array( $tube_theme::$tube_pagination, 'prevnext_image_links' ), 100 );


if (have_posts()) :
  while (have_posts()) :
    the_post();   

    $post_parent_id = $post->post_parent;
    $post_parent_data = get_post($post_parent_id);
    $post_parent_title = $post_parent_data->post_title;
    $post_parent_permalink = get_permalink($post_parent_id);
                
  endwhile;
endif; 


        
// columns for the masthead content area 
$masthead_columns = apply_filters( 'tube_filter_masthead_columns', 'col-sm-10 col-sm-push-1 col-xl-8 col-xl-push-2' );   


// columns for the page content area 
$content_columns = apply_filters( 'tube_filter_content_columns', 'col-md-10 col-md-push-1 col-lg-8 col-lg-push-2' );  


get_header(); 
?>

<?php while ( have_posts() ) : the_post(); ?>
          
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="gloryshot-wrap">
      <?php echo wp_get_attachment_image( get_the_id(), 'tube-img-lg' , false, array('class' => "img-responsive thumbnail", 'style'=>'border:none;margin:0 auto;')); ?>
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
         
                <ul class="list-inline list-inline-delimited post-meta sans-serif no-bottom text-muted">                  
                  <li class="date">
                    <a href="<?php echo esc_url($post_parent_permalink); ?>"><i class="fa fa-arrow-circle-left"></i>&nbsp; <?php echo wp_kses_post($post_parent_title); ?></a>
                  </li>      
                </ul>
                
                <?php do_action('tube_masthead_bottom'); ?>                    
               
            </div> <!-- .col -->  
          </div> <!-- end .row -->      
        </div> <!-- end .container -->         
      </div> <!-- .page-masthead-content -->   
        
      <?php do_action('tube_after_masthead_content'); ?>
         
    </header> <!-- end .page-masthead -->       
    
    <?php do_action('tube_after_masthead'); ?>  
        
    <?php get_sidebar( 'top' ); ?>     
    
    
    <div class="content-block single-image-content">
      <div class="container">             
         <div class="row">     
            <div class="<?php echo esc_attr( $content_columns ); ?>"> 
              
              <?php do_action('tube_before_post_content'); ?>                 
    
              <div class="post-content">
                      
                <?php do_action('tube_content_top'); ?>    
                        
                <div class="the-content">     
                  <?php the_content(); ?>  
                </div> <!-- .the-content -->                     
                    
                <?php do_action('tube_content_bottom'); ?>
                      
              </div> <!-- .post-content -->      
  
  
  
                <?php       
                // don't show current image or parent post featured image
                $excluded_images = array(
                  //get_the_id(), 
                  get_post_thumbnail_id( $post_parent_id )
                );
                
                $excluded_images = apply_filters( 'tube_filter_single_image_excluded_images', $excluded_images );
                                    
                if ( $images = get_posts(array(
                  'post_parent' => $post_parent_id,
                  'order' => 'ASC',
                  'orderby' => 'menu_order',
                  'post_type' => 'attachment',
                  'numberposts' => -1,
                  'post_mime_type' => 'image',
                  'exclude' => $excluded_images, 
                ))) :
                ?>
              
                  <hr />
                  
                  <ul class="list-posts list-posts-grid grid-234 clearfix">
                    
                    <?php       
                    global $post;
                    
                    
                    $current_image_id = get_the_id();
                    
                    foreach( $images as $post ) :
                      
                      setup_postdata($post);                      
                      
                      $style = 'border:none;margin:0 auto;';
                    
                      $is_current = ( $current_image_id == get_the_id()) ;
                      
                      if ( $is_current ):
                        $style .= 'opacity:.5;';
                      endif;
                      ?>
                      <li>
                         <?php if ( ! $is_current ): ?><a href="<?php echo get_permalink( get_the_id() ) ?>"><?php endif; ?>
                           <?php echo wp_get_attachment_image( get_the_id(), 'tube-img-sm-cropped' , false, array('class' => 'img-responsive thumbnail', 'style'=>$style)); ?>
                         <?php if ( ! $is_current ): ?></a><?php endif; ?>
                         
                      </li>
                    <?php                      
                    endforeach; 
                    
                    wp_reset_postdata();
                    
                    ?>             
                     
                  </ul>
                
                <?php endif; ?>               
                  
              <?php do_action('tube_after_post_content'); ?>      
                
            </div> <!-- .col -->            
          </div> <!-- .row -->      
        </div> <!-- .container -->
      </div> <!-- .content-block --> 

    <?php get_sidebar( 'bottom' ); ?>
                
  </article>         

<?php endwhile; // The Loop ends here ?>     

<?php get_footer(); ?>