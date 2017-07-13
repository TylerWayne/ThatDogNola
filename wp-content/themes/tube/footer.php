<?php
/**
 * The footer template
 *
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
?>
</div><!-- .site-content -->
  
<?php do_action('tube_after_site_content'); ?>

<?php do_action('tube_before_footer'); ?>

<footer id="site-footer">

  <div class="container text-center">    
        
    <?php do_action('tube_footer'); ?>       
      
  </div><!-- .container -->
  
</footer><!-- .site-footer -->

<?php do_action('tube_after_footer'); ?>  

<?php wp_footer(); ?>

<?php do_action('tube_after_wp_footer'); ?>  

</body>
</html>