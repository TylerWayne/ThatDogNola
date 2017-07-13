<?php
/**
 * Partial for taxonomy item list
 *
 * @package .TUBE
 * @subpackage Partials
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */

global $term;

$term_description = $term->description;

$term_link = get_term_link( $term, $term->taxonomy );

$term_count = $term->count;
?>
<li class="tube-termslist-item">
      
  <div class="post-title-wrap">
    <a class="h5 post-title break-word" href="<?php echo esc_url($term_link); ?>">
      <?php echo sanitize_text_field($term->name); ?> <!--<small class="badge"><?php echo intval($term_count); ?></small>-->
    </a>
  </div>  
   <?php if ( $term_description != '' ): ?>
     <p class="excerpt no-bottom">
       <?php echo wp_filter_post_kses($term_description); ?>    
     </p>
  <?php endif; ?> 
 
</li>