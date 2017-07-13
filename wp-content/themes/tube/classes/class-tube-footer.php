<?php
/**
 * Tube_Footer
 * 
 * Setttings to customize the page footer
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
    
class Tube_Footer {
  
  const GET_TUBE_BASE_URL = 'https://www.get.tube/wordpress';
  
  public static $instance;
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Footer();
      return self::$instance;
  }
  
  
  // Constructor  
  
  function __construct() {
    
    // add Footer section to the Customizer
    add_action( 'customize_register', array($this, 'customize_register_tube_footer_section'), 89, 1 );
    
    // add Footer Link options to the Customizer
    add_action( 'customize_register', array($this, 'customize_register_tube_footer_link'), 89, 1 );
    
    // add Copyright options to the Customizer
    add_action( 'customize_register', array($this, 'customize_register_tube_copyright'), 89, 1 );   

    // filters for the custom copyright statement    
    add_filter( 'tube_filter_copyright', 'stripslashes' ); 
    add_filter( 'tube_filter_copyright', 'wpautop' );      
    add_filter( 'tube_filter_copyright', 'wptexturize' );            
    
    
    // add footer menu to footer
    add_action( 'tube_footer', array( $this, 'the_footer_menu') );
    
    // add social links to footer
    add_action( 'tube_footer', array( $this, 'the_social_links') );

    // function to draw the custom copyright statement
    add_action( 'tube_footer', array( $this, 'the_copyright') ); 
    
    // add legal menu to footer
    add_action( 'tube_footer', array( $this, 'the_legal_menu') );
    
    // add .TUBE link to footer
    add_action( 'tube_footer', array($this, 'the_tube_footer_link') );
    
    // filters for the footer link text
    add_filter( 'tube_filter_tube_footer_link_text', 'stripslashes' ); 
    add_filter( 'tube_filter_tube_footer_link_text', 'wptexturize' ); 
   
  }

  // add footer section to the Customizer  
  public function customize_register_tube_footer_section($wp_customize){

    // Add the footer link section
    $wp_customize->add_section( 'tube_customize_section_footer' , array(
        'title'       => _x( 'Footer', 'Customizer: footer section title', 'tube' ),
        'priority'    => 300,
    ) ); 

  }

  // add .TUBE Link options to the Customizer  
  public function customize_register_tube_footer_link($wp_customize){
            
    
    // Add setting for show / not show footer link
    $wp_customize->add_setting( 'tube_show_footer_link', array(
          'default'      => '1',
          'transport' => 'refresh',
         'sanitize_callback' => array( $this, 'sanitize_show_footer_link' ),
      )
    );
    
    // Add control for show / not show footer link
    $wp_customize->add_control( 'tube_show_footer_link',  array(
        'type' => 'radio',
        'label' => _x( 'Show .TUBE Link', 'Customizer: .TUBE footer link control label', 'tube' ),
        'description' => _x( 'Show some love for the .TUBE WordPress Theme with a link in your footer.', 'Customizer: .TUBE footer link control description', 'tube' ),
        'section' => 'tube_customize_section_footer',
        'choices' => array(
        '1' => _x('Yes, show a .TUBE Theme link', 'Customizer: .TUBE footer link control value', 'tube'),
        '0' => _x('No, please don&rsquo;t show a link', 'Customizer: .TUBE footer link control value', 'tube'),
       ),
      )
    );
    
    
  }
  
  
  
  // .TUBE FOOTER LINK  
  
  // simple sanitizer to ensure footer link is set to 1 or 0
  
  function sanitize_show_footer_link( $show_footer_link ){    
   
    // see if footer link is true
    if ( $show_footer_link == 1 ):
      return 1;
    endif;
    
    // nope, it's off  
    return 0;
    
  }  
  
  
  // function to get the footer link
  
  static function get_the_tube_footer_link(){                
    
    // see if the link should be shown
    $show_footer_link = get_theme_mod('tube_show_footer_link', 0);
    
    // don't show, so do nothing
    if( ! $show_footer_link ):
      return;
    endif;
        
    // set up a little HTML snippet for the link     
    $footer_link_output = '<a href="%link_url%" rel="nofollow" class="btn btn-default btn-sm tube-footer-link">
  <span class="btn-text">%link_text%</span><span class="btn-icon"><i class="fa fa-chevron-circle-right"></i></span></a>';  
    
    // get the link
    $footer_link_url = self :: get_tube_footer_link_url();
    
    // get the text
    $footer_link_text = self :: get_tube_footer_link_text();    
    
    // make sure there's a link and text
    if ( ! $footer_link_url || ! $footer_link_text ) :
      return;
    endif;       
    
    // insert the text and url into the link
    $footer_link_output = str_replace(
       array( '%link_url%','%link_text%' ), 
       array( esc_url( $footer_link_url ), esc_attr( $footer_link_text ) ), 
       $footer_link_output
    );
    
    //return the output
    return $footer_link_output;
    
  }


  // function to draw the footer link
  // called via 'tube_footer' action in __construct above
  function the_tube_footer_link(){
                    
    // get the footer link
    $footer_link = self :: get_the_tube_footer_link();
    
    // echo out the footer link
    echo wp_kses_post( $footer_link );
    
  }
  
  
  // function to get the footer link URL
  static function get_tube_footer_link_url(){
     
    // get the base URL
    $url = self::GET_TUBE_BASE_URL;
      
    // filter the base URL
    $url = apply_filters('tube_filter_tube_footer_link_url', $url);
    
    // return the URL
    return $url; 
    
  }
  
  
  // function to get the footer link text
  static function get_tube_footer_link_text(){
     
    // get the base text
    $footer_link_text = _x( 'About the .TUBE Theme', 'Footer: .TUBE link button text', 'tube' );
    
    // allow it to be filtered
    // NOTE: Theme adds texturize and strip slashes above
    $footer_link_text = apply_filters('tube_filter_tube_footer_link_text', $footer_link_text);
    
    // return the URL
    return $footer_link_text; 
    
  }
  
  
  
  
  // COPYRIGHT   
  
  // add copyright options to the Customizer  
  public function customize_register_tube_copyright($wp_customize){    

    
    // Add setting for footer Copyright    
    $wp_customize->add_setting( 'tube_copyright', array(
        'default' => self::get_default_copyright(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
      )
    );
    
    // Add control for footer Copyright   
    $wp_customize->add_control( 'tube_copyright', 
      array(
        'type' => 'textarea',
        'label' => _x( 'Copyright Statement', 'Customizer: copyright option label', 'tube' ),
        'description' => _x('Use %site_name%, %year% and/or %tagline% as placeholders.', 'Customizer: copryright control description', 'tube'), 
        'input_attrs' => array(
          'style' => 'height: 100px;',
        ),
        'section' => 'tube_customize_section_footer',
      )
    );
    
  }
  
  
  // function to get the default copyright statement
    
  static function get_default_copyright(){      
    
    // set up the default copyright value
    $default_copyright = _x("&copy; &#37;site_name&#37;", 'Customizer: copyright option default value', 'tube');
    
    // allow it to be filtered
    $default_copyright = apply_filters( 'tube_filter_default_copyright', $default_copyright);
    
    // return the copyright
    return $default_copyright;
      
  }
       
       
  // get the copyright statement     
  // called via 'tube_footer' action in __construct above
  
  static function get_the_copyright(){        
      
    // get the default copyright value w/ placeholders
    $default_copyright_text = self :: get_default_copyright();
    
    // get the theme  copyright value w/ placeholders, or default
    $copyright_text_raw = get_theme_mod( 'tube_copyright', $default_copyright_text );
    
    $copyright_text_raw = apply_filters( 'tube_filter_copyright_raw', $copyright_text_raw );
    
     // if no text, do nothing
    if ( ! $copyright_text_raw ):
      return;
    endif;
    
    // do the replacements
    $copyright_text = str_replace(
          array("&#37;", "%site_name%", "%tagline%", "%year%"), 
          array( "%", get_bloginfo( 'name' )  , get_bloginfo( 'description' ), current_time('Y')), 
          $copyright_text_raw
    );
    
    
    // filter the composed copyright
    // NOTE: Theme adds texturize, autop, and strip slashes above
    $copyright_text = apply_filters( 'tube_filter_copyright', $copyright_text );
    
     // if no text, do nothing
    if ( ! $copyright_text ):
      return;
    endif;
    
    // start output buffer
    ob_start();
    
    // output the composed copyright
    ?>
    <div id="copyright">
      <?php echo wp_kses_post($copyright_text); ?>
    </div>
    <?php 
    
    // grab the output and return
    $output = ob_get_clean();
    
    return $output;
      
  }


  // draws the copyright statement     
  // called via 'tube_footer' action in __construct above
  
  function the_copyright(){        
                    
    // get the copyright
    $copyright = self :: get_the_copyright();
    
    // echo out the copyright
    echo wp_kses_post( $copyright );
      
  }
  
  
  
  
  // SOCIAL ICONS   
  
  // gets a list of social icons in the footer
  
  // NOTE: This makes use of the Yoast SEO Plugin option 'wpseo_social'
  // https://yoast.com/wordpress/plugins/seo/
  
  function get_the_social_links(){
       
    // get links data from Yoast
    $social_links = get_option('wpseo_social');
       
    // if no social links, do nothing
    if ( ! is_array( $social_links ) ):
      return NULL;         
    endif;

    // Create lookup table of potential sites (name, button class, icon)
    $social_sites = array(
      'youtube_url' => array( _x('YouTube', 'Footer: social site name', 'tube'), 'btn-youtube', 'fa-youtube-square'),
      'facebook_site' => array( _x('Facebook', 'Footer: social site name', 'tube'), 'btn-facebook', 'fa-facebook-square'),
      'twitter_site' => array( _x('Twitter', 'Footer: social site name', 'tube'), 'btn-twitter', 'fa-twitter-square'),
      'instagram_url' => array( _x('Instagram', 'Footer: social site name', 'tube'), 'btn-instagram', 'fa-instagram'),
      'pinterest_url' => array( _x('Pinterest', 'Footer: social site name', 'tube'), 'btn-pinterest', 'fa-pinterest-square'),
      'linkedin_url' => array( _x('LinkedIn', 'Footer: social site name', 'tube'), 'btn-linkedin', 'fa-linkedin-square'),
      'google_plus_url' => array( _x('Google+', 'Footer: social site name', 'tube'), 'btn-google-plus', 'fa-google-plus-square'),
     );
    
      
    $social_links_output = array();
    
    // loop through all the link options
    // remove unused options, add URL to in-use options
    foreach ( $social_sites as $social_site => $social_site_data ):    
      
      // check if each social site is in this site's social links
      if ( ! array_key_exists($social_site, $social_links) || $social_links[$social_site] == '' ):
       
        unset( $social_sites[$social_site] );
        continue;
       
      endif;  
        
      // get the URL for the socail site   
      $url = $social_links[$social_site];
     
      // if twitter handle, add the domain
      if ( $social_site == 'twitter_site' ):
        $url = 'https://twitter.com/' . $url;
      endif;
     
      // add the URL to the links output          
      $social_sites[$social_site][] = $url;

    endforeach;
       
       
    // filter the sites before output
    $social_sites = apply_filters( 'tube_filter_social_links_output', $social_sites );
   
    // make sure there are options filled in
    if ( count( $social_sites ) == 0 ):
      return NULL;         
    endif;       
      
    // start output buffer
    ob_start();
    
    
    // output the composed links     
    ?>
    <ul class="tube-social-links list-inline">
      <?php foreach ( $social_sites as $social_site => $social_site_data ): ?><li>
        <a href="<?php echo esc_url( $social_site_data[3] ); ?>" class=" btn-social <?php echo esc_attr( $social_site_data[1] ); ?>" title="<?php echo esc_attr($social_site_data[0]); ?>"><span class="btn-icon"><i class="fa <?php echo esc_attr($social_site_data[2]); ?>"></i></span></a>
      </li><?php endforeach; ?>
    </ul>
    <?php 
    
    // grab the output and return
    $output = ob_get_clean();
    
    return $output;
      
  }
  
  
   
  // draws a list of social icons in the footer
  // called via 'tube_footer' action in __construct above
   
  function the_social_links(){       
                    
    // get the social links
    $social_links = self :: get_the_social_links();
    
    // echo out the social links
    echo wp_kses_post( $social_links );
      
  }

     
  
  
  // FOOTER AND LEGAL MENUS   
  
  // draws the footer menu
  // called via 'tube_footer' action in __construct above
  function the_footer_menu( $args = NULL ){
    
    // call to draw simple list menu (in class-tube-frontend-menus.php)
    Tube_Theme::$tube_frontend_menus -> draw_list_menu( 'footer-menu', array( 'only_parents' => true ) );
    
  }
  
  // draws the legal menu    
  // called via 'tube_footer' action in __construct above
  function the_legal_menu( $args = NULL){
    
    // call to draw simple list menu (in class-tube-frontend-menus.php)
    Tube_Theme::$tube_frontend_menus -> draw_list_menu( 'legal-menu' );
    
  }
    
  
  
}