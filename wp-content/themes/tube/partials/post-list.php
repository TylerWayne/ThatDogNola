<?php
/**
 * Partial for post lists on index/archive/search.
 *
 * @package .TUBE
 * @subpackage Partials
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */

global $tube_theme;

$post_id = get_the_id();

// set detault post image size
$default_post_list_image_size = 'tube-img-sm-cropped';

$override_post_list_image_size = get_query_var( 'tube_post_list_image_size' );

$post_image_size = ( $override_post_list_image_size ) ? $override_post_list_image_size : $default_post_list_image_size;

// TODO: Ability to choose exceprt hide / show in customizer
$show_excerpt_in_post_list = false;
?>

<li <?php post_class(); ?> id="post-<?php the_ID(); ?>">   
       
    <?php do_action('tube_post_list_before_thumbnail'); ?>
    
    <?php if ( has_post_thumbnail() ) : ?>
      
      <div class="thumbnail">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $post_image_size ); ?></a>
      </div>
      
    <?php endif; ?>
      
    <?php do_action('tube_post_list_after_thumbnail'); ?>
    
    <div class="post-content">              
        
      <?php do_action('tube_post_list_before_title_wrap'); ?>
        
      <div class="post-title-wrap">
        
        <?php do_action('tube_post_list_before_title'); ?>
          
        <a class="h5 post-title break-word" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>  
        
        <?php do_action('tube_post_list_after_title'); ?> 
              
        <?php echo wp_kses_post( $tube_theme::$tube_post_meta -> get_post_meta_output() ); ?>
        
        <?php do_action('tube_post_list_after_meta'); ?>
        
        <?php if ( $show_excerpt_in_post_list && get_the_excerpt() ): ?>
           <div class="excerpt">
             <?php the_excerpt(); ?>
          </div>  
        <?php endif; ?>    
        
        <?php do_action('tube_post_list_after_excerpt'); ?>
     
      </div> <!-- .post-title-wrap -->
      
      <?php do_action('tube_post_list_after_title_wrap'); ?>
      
    </div> <!-- .post-content -->
    

</li>