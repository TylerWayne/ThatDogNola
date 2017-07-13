<?php
/**
 * Tube_Check_Plugins
 * 
 * Checks for required plugins for the .TUBE Theme
 * 
 * This is used by hosting partners (e.g. Epik.com) to ensure the plugin gets 
 * activated for new accounts who opt into the .TUBE Theme and Video Curator.
 * 
 * It works by looking for an option 'tube_video_curator_is_required'
 * If that option is found, and set to 1, the plugin is auto activated.
 * 
 * https://wordpress.org/plugins/tube-video-curator/
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
  
class Tube_Check_Plugins {
  public static $instance;
  
  
  public static function init() {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Check_Plugins();
      return self::$instance;
  }


  // Constructor

  function __construct() {
    
    // Check if .TUBE plugin is required
    add_action( 'admin_init', array( $this, 'check_required_plugins' ) );
      
  }
  
  
    // Check if .TUBE plugin is required
  function check_required_plugins() {
    
    // make sure current user has correct permissions
    if ( ! current_user_can('activate_plugins') ):
      return;
    endif;
    
    // TODO: Allow this to support an array of plugins.
    
    // set the option key
    $plugin_req_option_key = 'tube_video_curator_is_required';   
    
    // see if the plugin required option is set
    $plugin_required = get_option( $plugin_req_option_key );
    
    // plugin isn't required, do nothing
    if ( ! $plugin_required ):
      return;
    endif;    
    
    // set the path to the plugin
    $plugin_path = '/tube-video-curator/tube-video-curator.php' ; 
    
    // if plugin is already activated, do nothing
    if( is_plugin_active( $plugin_path ) ):
      return;
    endif;
    
    // activate the .TUBE Video Curator plugin
    $this -> do_activate_plugin( $plugin_req_option_key, $plugin_path );
    
    
  }

  
  function do_activate_plugin( $plugin_req_option_key, $plugin_path ) {
        
    // activate the plugin
    $activate = activate_plugin( $plugin_path );
    
    // DOH, there was an error
    if ( is_wp_error( $activate ) ) {
        
      // TODO: Better error handling for failed activation
      return false;
      
    }
    
    // set the required option back to empty
    update_option( $plugin_req_option_key, 0, true );
    
    // good to go
    return true;
    
  }
  
  
}