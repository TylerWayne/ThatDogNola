<?php
/**
 * Tube_Frontend_Menus
 * 
 * Add support for frontend menus, auto-create main menu, etc
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Frontend_Menus

{
  public static $instance;
  
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Frontend_Menus();
      return self::$instance;
  }


  // Constructor

  function __construct()
  {
    
    // Add menu support 
    add_action( 'after_setup_theme', array( $this, 'add_theme_menu_support' ) );
    
    // Register frontend menus
    add_action( 'init', array( $this, 'register_frontend_menus' ) );
    
    // Create the main menu
    add_action( 'init', array( $this, 'assign_primary_menu_locations' ) );
    
    // remove title attributes from wp_nav_menu, wp_page_menu, and wp_list_categories
    add_filter( 'wp_nav_menu', array( $this, 'suppress_menu_title_attribute') );    
    add_filter( 'wp_page_menu', array( $this, 'suppress_menu_title_attribute') );    
    add_filter( 'wp_list_categories', array( $this, 'suppress_menu_title_attribute') );
    
  }
  
  
  // Add menu support 
  function add_theme_menu_support() {
    
    add_theme_support( 'menus' );
    
  }
  
  
  // Register frontend menus 
  function register_frontend_menus() {
    
    // Register main menu
    register_nav_menu('main-menu', _x( '1. Header Navbar', 'Menu name', 'tube' ));
    
    // Register footer menu
    register_nav_menu('footer-menu', _x( '2. Footer Navbar', 'Menu name', 'tube' ));
    
    // Register legal menu
    register_nav_menu('legal-menu', _x( '3. Legal Navbar', 'Menu name', 'tube' ));  

  }
  

  
  // Register menus 
  function assign_primary_menu_locations() {    
    
    // check if this has been done in the past and do nothing if so
    if ( get_theme_mod( 'primary_menu_locations_assigned' ) == 1 ) :      
      
      return;
      
    endif;
    
    // set theme mod so we know not to do this again
    set_theme_mod( 'primary_menu_locations_assigned', 1 );    
           
    // check if a primary menu exists
    $primary_menu = wp_get_nav_menu_object( 'Primary Menu' );
        
    // do nothing if menu doesn't already exist   
    if( ! $primary_menu ):     
      
      return;
    
    endif;
    
    // get the ID for the menu
    $primary_menu_id = $primary_menu->term_id;
        
    // get available locations
    $menu_locations = get_theme_mod('nav_menu_locations');
    
    // add the new menu to the Main Menu theme location
    $menu_locations['main-menu'] = $primary_menu_id; 
    
    // add the new menu to the Footer Menu  theme location
    $menu_locations['footer-menu'] = $primary_menu_id; 
    
    // update the menu locations
    set_theme_mod('nav_menu_locations', $menu_locations);

  }


  
  // Remove title attributes from menu items
  function suppress_menu_title_attribute( $menu ){
     
    return $menu = preg_replace('/ title=\"(.*?)\"/', '', $menu );
    
  }


  // function to draw simple list menu
  public function draw_list_menu( $menu_location_slug, $args = NULL ){    
 
    $defaults = array(
      'only_parents' => NULL,
    );
    $args = wp_parse_args( $args, $defaults );
    
    $menu_items = $this -> get_nav_menu_items( $menu_location_slug );         
    
    $menu_items = apply_filters( 'tube_filter_'.$menu_location_slug.'_items', $menu_items );
    
    if ( ! $menu_items ): 
      return;
    endif;    
        
    $menu_list = '<ul class="list-inline list-inline-delimited" id="menu-' . esc_attr($menu_location_slug) . '">';
  
    foreach ( (array) $menu_items as $key => $menu_item ):
      
      if ( ! $args['only_parents'] || $menu_item -> menu_item_parent == 0 ) :
        
        $title = $menu_item->title;
        
        $url = $menu_item->url;
        
        $menu_list .= '<li><a href="' . esc_url($url) . '">' . wp_kses_post($title) . '</a></li>';
        
      endif;
      
    endforeach;
    
    $menu_list .= '</ul>';  
    
    $menu_list = apply_filters( 'tube_filter_'.$menu_location_slug.'_html', $menu_list );
    
    echo wp_kses_post($menu_list);      
    
  }


  private function get_nav_menu_items( $menu_location_slug ){
    
    $locations = get_nav_menu_locations();
    
    if ( ! is_array($locations) || ! array_key_exists($menu_location_slug, $locations) ):
      return NULL;
    endif;
                  
    $menu = wp_get_nav_menu_object(  $locations[ $menu_location_slug ] );
  
    if ( ! $menu ) :
      return NULL; 
    endif;
    
    $menu_items = wp_get_nav_menu_items($menu->term_id);
  
    if ( ! is_array($menu_items) || ( count($menu_items) == 0 ) ):
      
      return NULL; 
      
    endif;
    
    return $menu_items;

  }
  
  
}