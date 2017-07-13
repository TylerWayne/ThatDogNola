<?php
/**
 * Tube_Back_Compat
 *
 * Add ability to ensure a specific version of WordPress.
 * 
 * Code is based on code from twentysixteen theme, Copyright 2014-2015 WordPress.org
 *
 * @package .TUBE
 * @subpackage Classes
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Back_Compat {
  
  public static $instance;
  
  public static $upgrade_message;

  public static function init() {
    
    if (is_null(self::$instance)) 
      self::$instance = new Tube_Back_Compat();
    
    return self::$instance;
    
  }
  
  
  // Constructor

  function __construct() {    

    $min_version = Tube_Theme :: $min_wp_version;
    
    self :: $upgrade_message = sprintf( 
      _x( 'The .TUBE Theme requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'WP version upgrade required message, must have %1$s and %2$s placeholders', 'tube' ), 
      $min_version,
      $GLOBALS['wp_version'] 
    );
    
    add_action( 'after_switch_theme', array( $this, 'tube_switch_theme' ) );
     
    add_action( 'load-customize.php', array( $this, 'tube_theme_customize' ) );

    add_action( 'template_redirect', array( $this, 'tube_theme_preview' ) );
    
  }
  
  
  /**
   * Prevent switching to .TUBE Theme on old versions of WordPress.
   *
   * Switches to the default theme.
   *
   * @since .TUBE Theme 1.0
   */
  function tube_switch_theme() {
    
    switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
  
    if ( ! isset( $_GET['activated'] ) ):
    
      return;
    
    endif;
    
    unset( $_GET['activated'] );
  
    add_action( 'admin_notices', array( $this, 'tube_upgrade_notice' ) );
    
  }
  
  
  /**
   * Adds a message for unsuccessful theme switch.
   *
   * Prints an update nag after an unsuccessful attempt to switch to
   * .TUBE Theme on non-compatible WordPress versions
   *
   * @since .TUBE Theme 1.0
   *
   * @global string $wp_version WordPress version.
   */
   
  function tube_upgrade_notice() {
    
    $message = self :: $upgrade_message;
    
    printf( '<div class="error"><p>%s</p></div>', $message );
    
  }
  
  /**
   * Prevents the Customizer from being loaded on non-compatible WordPress versions
   *
   * @since .TUBE Theme 1.0
   *
   * @global string $wp_version WordPress version.
   */
  function tube_theme_customize() {
    
    wp_die( self :: $upgrade_message, '', array(
    
      'back_link' => true,
      
    ) );
    
  }
  
  /**
   * Prevents the Theme Preview from being loaded on non-compatible WordPress versions
   *
   * @since .TUBE Theme 1.0
   *
   * @global string $wp_version WordPress version.
   */
  function tube_theme_preview() {
  
    if ( ! isset( $_GET['preview'] ) ):
    
      return;
    
    endif;
    
    wp_die( self :: $upgrade_message );
      
  }
    
 
 
}
