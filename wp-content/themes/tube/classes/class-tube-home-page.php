<?php
/**
 * Tube_Home_Page
 * 
 * Setttings to customize the (non-static) home page (i.e. latest posts page)
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
    
class Tube_Home_Page{
  
  public static $instance;
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Home_Page();
      return self::$instance;
  }
  
  
  // Constructor  
  
  function __construct() {
    
    // Add home settings
    add_action( 'customize_register', array( $this, 'add_home_settings' ), 99 );
    
    //  add the headline and excerpt to the home masthead
    add_action( 'tube_home_masthead_content', array( $this, 'draw_home_headline' ) );    
    add_action( 'tube_home_masthead_content', array( $this, 'draw_home_excerpt' ) );    
    
    // add filters for the headline
    add_filter( 'tube_filter_home_headline', 'stripslashes' );
    add_filter( 'tube_filter_home_headline', 'wptexturize' );
    
    // add filters for the excerpt
    add_filter( 'tube_filter_home_excerpt', 'stripslashes' );
    add_filter( 'tube_filter_home_excerpt', 'wptexturize' );
    add_filter( 'tube_filter_home_excerpt', 'wpautop' );
    
  }
    

  function add_home_settings( $wp_customize ) {
  
       
   // move the site icon to the new logo section
   //$wp_customize->get_control('site_icon')->section = 'tube_customize_section_logo';
       
    $show_on_front = get_option( 'show_on_front' );
    
      
    // Add the Home / Latest Posts Page section
    $wp_customize->add_section( 'tube_customize_section_home' , array(
        'title'       => _x( 'Home / Latest Posts Settings', 'Customizer: Home / latest posts settings section title', 'tube' ),
        'priority'    => 55,
        'description'    => _x('These settings control the &#8220;Latest Posts&#8221; page which is usually your home page, unless you use a static front page.', 'Customizer: Home section description', 'tube' )
    ) ); 
            
            
            
    // Add setting for Home headline 
    $wp_customize->add_setting( 'tube_home_headline', array(
        'default' => self::get_default_home_headline(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
      )
    );
    
    // Add control for Home headline 
    $wp_customize->add_control( 'tube_home_headline', array(
            'label' => _x( 'Page Headline', 'Customizer: home headline control label', 'tube' ),
            'description' => _x('Use %site_name% and/or %tagline% as placeholders.', 'Customizer: home headline control description', 'tube'), 
            'section' => 'tube_customize_section_home',
        'type'     => 'text',
        )
    );    
    
    
    
    
    // Add setting for Home Excerpt 
    $wp_customize->add_setting( 'tube_home_excerpt', array(
        'default' => self::get_default_home_excerpt(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
      )
    );
    
    $wp_customize->add_control( 'tube_home_excerpt', array(
        'type' => 'textarea',
        'label'    => _x( 'Page Excerpt', 'Customizer: page excerpt control label', 'tube' ),
        'description' => _x('Use %site_name% and/or %tagline% as placeholders.', 'Customizer: page excerpt control description', 'tube'), 
        'section'  => 'tube_customize_section_home',
        'input_attrs' => array(
          'style' => 'height: 100px;',
        ),
      )
    );
    
    
    
    $default_gloryshot_position = tube_theme::$tube_images -> get_default_gloryshot_position();
    
    // Add setting for Home gloryshot position 
    $wp_customize->add_setting( 'tube_home_gloryshot_position', array(
        'default' => $default_gloryshot_position,
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
      )
    );
    
    $gloryshot_positions = tube_theme::$tube_images -> get_gloryshot_positions();
    
    $wp_customize->add_control( 'tube_home_gloryshot_position', array(
        'label'    => _x( 'Image Position', 'Customizer: home &#8220;hero&#8220; image position label', 'tube' ),
        'description' => _x('Choose how the image will be anchored to the page.', 'Customizer: home &#8220;hero&#8220; image position description', 'tube'), 
        'section'  => 'header_image',
        'type'     => 'radio',
        'choices'  => $gloryshot_positions,
      )
    ); 
       
    

    
    
    // Add setting for Home Excerpt 
    $wp_customize->add_setting( 'tube_home_sticky_posts_limit', array(
        'default' => self::get_default_sticky_posts_limit(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
      )
    );
    
    
    
    $wp_customize->add_control( 'tube_home_sticky_posts_limit', array(
        'label'    => _x( '# of Featured Posts to Show', 'Customizer: featured "sticky" posts limit label', 'tube' ),
          'description' => _x('Choose how many "sticky" posts to feature on the home page. You can make a post sticky in the post editor or quick-edit.', 'Customizer: featured "sticky" posts limit description', 'tube'), 
        'section'  => 'tube_customize_section_home',
        'type'     => 'select',
        'choices'  => array( 0 => _x('None', 'Customizer: label for no / zero featured "sticky" posts', 'tube'), 2 => '2', 4 => '4', 6 => '6', 8 => '8'),
      )
    ); 

  
  }

    
  static function get_default_home_headline(){
    
    $default_home_headline = "&#37;site_name&#37;";
    
    $default_home_headline = apply_filters( 'tube_filter_default_home_headline', $default_home_headline);
        
    return $default_home_headline;
    
  }       
               
               
  static function draw_home_headline(){
    
    $default_home_headline = self :: get_default_home_headline();
    
    $headline_text_raw = get_theme_mod('tube_home_headline', $default_home_headline);
    
    $headline_text_raw = apply_filters( 'tube_filter_home_headline_raw', $headline_text_raw );
    
    $headline_text = str_replace(
      array("&#37;", "%site_name%", "%tagline%"), 
      array( "%", get_bloginfo( 'name' )  , get_bloginfo( 'description' )), 
      $headline_text_raw
    );      
                 
    $headline_text = apply_filters( 'tube_filter_home_headline', $headline_text);
                 
    if (! $headline_text):
      return;
    endif;
    ?>
    
    <h1 class="post-title">
      <?php echo wp_kses_post($headline_text); ?>
    </h1>   
    
    <?php
      
  }
   
    
  static function get_default_home_excerpt(){
    
    $default_home_excerpt = "&#37;tagline&#37;";
    
    $default_home_excerpt = apply_filters( 'tube_filter_default_home_excerpt', $default_home_excerpt);
    
    return $default_home_excerpt;
    
  }       
  
              
   static function draw_home_excerpt(){
      
      $default_home_excerpt = self :: get_default_home_excerpt();
      
      $excerpt_text = get_theme_mod('tube_home_excerpt', $default_home_excerpt);
                
      $excerpt_text = str_replace(
        array("&#37;", "%site_name%", "%tagline%"), 
        array( "%", get_bloginfo( 'name' )  , get_bloginfo( 'description' )), 
        $excerpt_text
      );
                   
      $excerpt_text = apply_filters( 'tube_filter_home_excerpt', $excerpt_text );
                   
      if (! $excerpt_text):
        return;
      endif;
      
      ?>
      <div class="excerpt">
        <?php echo wp_kses_post( $excerpt_text ); ?>
      </div>   
      <?php
      
  }
   
    
    
    
  function get_home_gloryshot_position(){
    
    $default_home_gloryshot_position = tube_theme::$tube_images -> get_default_gloryshot_position();
    
    $home_gloryshot_position = get_theme_mod( 'tube_home_gloryshot_position', $default_home_gloryshot_position );
    
    return $home_gloryshot_position;
    
  }      
    
  
    
  // gets the default number of sticky posts to show  
  static function get_default_sticky_posts_limit(){    
    
    $default_sticky_posts_limit = 2;
    
    $default_sticky_posts_limit = apply_filters( 'tube_filter_default_sticky_posts_limit', $default_sticky_posts_limit);
    
    return $default_sticky_posts_limit;
    
  }
  
    
  // gets the number of sticky posts to show  
  function get_sticky_posts_limit(){
    
    $default_sticky_posts_limit = $this -> get_default_sticky_posts_limit();
    
    $sticky_posts_limit = get_theme_mod( 'tube_home_sticky_posts_limit', $default_sticky_posts_limit );
    
    $sticky_posts_limit = apply_filters( 'tube_filter_sticky_posts_limit', $sticky_posts_limit);
    
    return $sticky_posts_limit;
    
  }
   
  // gets the sticky posts
  function get_sticky_posts(){
    
    // Get all Sticky Posts
    $sticky_posts = get_option( 'sticky_posts' );
    
    // if no sticky posts, do nothing
    if ( ! $sticky_posts ):
    
      return;
      
    endif;
      
    // Set the limit
    $sticky_posts_limit = $this -> get_sticky_posts_limit();   
    
    // if limit is zero do nothing
    if ( 0 == absint($sticky_posts_limit) ):
    
      return;
      
    endif;  
    
    /* Query Sticky Posts */
    $sticky_posts_query = new WP_Query( array( 
      'post__in' => $sticky_posts, 
      'ignore_sticky_posts' => 1, 
      'orderby' => 'date', 
      'order' => 'DESC' , 
      'posts_per_page' => $sticky_posts_limit 
    ) );
    
    // return the results
    return $sticky_posts_query;
    
    
  }
  
}