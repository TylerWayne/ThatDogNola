<?php
/**
 * Tube_Theme
 * 
 * Set up the theme and associated classes and base functions.
 * 
 * @package .TUBE
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
  

global $tube_theme;
$tube_theme = Tube_Theme::init();

class Tube_Theme { 
  
  public static $instance;
  
  public static $min_wp_version;
  
  public static $tube_customizer;  
  
  public static $tube_colors;  
  
  public static $tube_custom_logo;  
  
  public static $tube_home_page; 
  
  public static $tube_labels; 
  
  public static $tube_footer;  
  
  public static $tube_enque;    

  public static $tube_pagination;    

  public static $tube_taxonomy;  

  public static $tube_images;

  public static $tube_embeds;

  public static $tube_hero_video;

  public static $tube_comments;

  public static $tube_theme_supports;
  
  public static $tube_frontend_menus;

  public static $tube_sidebars;

  public static $tube_check_plugins;

  public static $tube_post_meta;

  public static $tube_latest_posts;

  public static $tube_archives;
  
  
  public static function init() {
    
    if ( is_null( self::$instance ) )
      self::$instance = new Tube_Theme();
    
    return self::$instance;
    
  }
    
    
    
  // Constructor
  
  function __construct() {
    
    self :: $min_wp_version = '4.7';
    
    add_action( 'init', array( $this, 'check_wp_version_compatibility' ) );
    
    if ( ! is_admin() ):
      // Include the boostrap nav-walker class
      require_once get_template_directory() . '/lib/class-tube-bootstrap-nav-walker.php';    
    endif;
       
    // Get the Tube_Customizer instance
    require_once get_template_directory() . '/classes/class-tube-customizer.php';
    self::$tube_customizer = new Tube_Customizer(); 
        
    // Get the Tube_Colors instance
    require_once get_template_directory() . '/classes/class-tube-colors.php';
    self::$tube_colors = new Tube_Colors();
    
    // Get the Tube_Custom_Logo instance
    require_once get_template_directory() . '/classes/class-tube-custom-logo.php';
    self::$tube_custom_logo = new Tube_Custom_Logo();
    
    // Get the Tube_Home_Page instance
    require_once get_template_directory() . '/classes/class-tube-home-page.php';
    self::$tube_home_page = new Tube_Home_Page();
    
    // Get the Tube_Labels instance
    require_once get_template_directory() . '/classes/class-tube-labels.php';
    self::$tube_labels = new Tube_Labels();
    
    // Get the Tube_Footer instance
    require_once get_template_directory() . '/classes/class-tube-footer.php';
    self::$tube_footer = new Tube_Footer(); 
        
    // Get the Tube_Enque_Scripts_And_Styles instance
    require_once get_template_directory() . '/classes/class-tube-enque-scripts-and-styles.php';
    self::$tube_enque = new Tube_Enque_Scripts_And_Styles();         
    
    // Get the Tube_Pagination instance
    require_once get_template_directory() . '/classes/class-tube-pagination.php';
    self::$tube_pagination = new Tube_Pagination();
            
    // Get the Tube_Taxonomy instance
    require_once get_template_directory() . '/classes/class-tube-taxonomy.php';
    self::$tube_taxonomy = new Tube_Taxonomy();
    
    // Get the $tube_images instance
    require_once get_template_directory() . '/classes/class-tube-images.php';
    self::$tube_images = new Tube_Images();    
    
    // Get the $tube_images instance
    require_once get_template_directory() . '/classes/class-tube-embeds.php';
    self::$tube_embeds  = new Tube_Embeds();   
    
    // Get the Tube_Hero_Video instance
    require_once get_template_directory() . '/classes/class-tube-hero-video.php';
    self::$tube_hero_video = new Tube_Hero_Video();   
    
    // Get the Tube_Comments instance
    require_once get_template_directory() . '/classes/class-tube-comments.php';
    self::$tube_comments = new Tube_Comments();   
    
    // Get the Tube_Theme_Supports instance
    require_once get_template_directory() . '/classes/class-tube-theme-supports.php';
    self::$tube_theme_supports = new Tube_Theme_Supports();   
    
    // Get the Tube_Frontend_Menus instance
    require_once get_template_directory() . '/classes/class-tube-frontend-menus.php';
    self::$tube_frontend_menus = new Tube_Frontend_Menus();   
    
    // Get the Tube_Sidebars instance
    require_once get_template_directory() . '/classes/class-tube-sidebars.php';
    self::$tube_sidebars = new Tube_Sidebars();  
    
    // Get the Tube_Check_Plugins instance
    require_once get_template_directory() . '/classes/class-tube-check-plugins.php';
    self::$tube_check_plugins = new Tube_Check_Plugins();   
    
    // Get the Tube_Post_Meta instance
    require_once get_template_directory() . '/classes/class-tube-post-meta.php';
    self::$tube_post_meta = new Tube_Post_Meta();    
    
    // Get the Tube_Lastest_Posts instance
    require_once get_template_directory() . '/classes/class-tube-latest-posts.php';
    self::$tube_latest_posts = new Tube_Lastest_Posts();  
    
    // Get the Tube_Archives instance
    require_once get_template_directory() . '/classes/class-tube-archives.php';
    self::$tube_archives = new Tube_Archives();  
    
    // set the content width
    add_action( 'init', array( $this, 'set_content_width' ) );

    // Custom password form for password protected posts
    add_filter( 'the_password_form', array( $this, 'custom_password_form' ) );

    // ignore sticky posts on homepage
    add_action( 'pre_get_posts', array( $this, 'ignore_sticky_on_homepage' ) );    
                
  }

    
    
  /**
   * Ensure we've got a new enough version of WordPress
   */
  function check_wp_version_compatibility() {

    $min_version = self :: $min_wp_version;
  
    if ( version_compare( $GLOBALS['wp_version'], $min_version, '<' ) ) :
      
      require get_template_directory() . '/classes/class-tube-back-compat.php';
      
      Tube_Back_Compat::init();
    
    endif;
   
   
  }
    
    
  // Custom password form for password protected posts
  // Function basically makes the form Bootstrap friendly
  // Per https://wordpress.slack.com/archives/themereview/p1479139580001199 this is NOT plugin territory
  
  function custom_password_form() {
    
    // local var for post_id
    $post_id = get_the_id();
      
    $label = 'pwbox-'.( empty( $post_id ) ? rand() : $post_id );
      
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
    $o .= '<div class="form-group">';
    $o .= '<label for="' . esc_attr($label) . '">';
    $o .= _x( "This content is password protected. Please enter your password below.", 'Protected posts form intro text', 'tube' );
    $o .= '</label>';
     $o .= '<input name="post_password" id="' . esc_attr($label) . '" type="password" size="20" maxlength="20" class="form-control" />';
    $o .= '</div>';
    $o .= '<input class="btn btn-primary btn-sm" type="submit" name="Submit" value="' . esc_attr_x( "Enter", 'Protected posts form button text', 'tube' ) . '" />';
    $o .= '</form>';

    return $o;
    
  }
  
  
    
    
  // Ignore sticky posts for main homepage query
  
  function ignore_sticky_on_homepage( $query ) {    
    
    if ( $query->is_home() && $query->is_main_query() ):
      
      $query->set( 'ignore_sticky_posts', '1' );
    
    endif;
    
  }
      
 
  // Set the site content width    
  
  function set_content_width(  ) {
    
    global $content_width;    
    
    // if already set, do nothing
    if ( isset( $content_width ) ) :
      return;
    endif;
    
    // set default to 1170;
    $content_width = 1170;
    
    // filter the content width
    $content_width = apply_filters( 'tube_filter_content_width', $content_width );
    
    // return content width
    return $content_width;
    
  }
  
  
  
    
  
}
