<?php
/**
 * Tube_Images
 * 
 * Various functions to set image sizes, add custom gloryshots to pages, etc
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Images

{
  public static $instance;
  
  
  public static function init() {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Images();
      return self::$instance;
  }


  // Constructor

  function __construct() {
   
    // add the custom image sizes
    add_action('after_setup_theme', array( $this, 'custom_image_sizes' ));
   
    // add the gloryshot CSS if needed
    add_action('wp_enqueue_scripts', array( $this, 'add_gloryshot_to_page' ), 20);
   
    // add the home video CSS if needed
    add_action('wp_enqueue_scripts', array( $this, 'add_home_background_video_to_page' ), 30);
   
    // add the Video Background plugin video if needed
    add_action('wp_enqueue_scripts', array( $this, 'add_video_background_plugin_video_to_page' ), 20);

    // add the gloryshot position metabox
    add_filter( 'admin_post_thumbnail_html', array( $this, 'add_gloryhsot_position_to_featured_image_metabox' ) );

    // add action to update gloryshot position on save
    add_action( 'save_post', array( $this, 'save_gloryshot_position' ), 99, 2 ); // save the custom fields 
  
  }
  
  
  
  function custom_image_sizes() {
    
    // Thumbnail sizes
    add_image_size('tube-img-lg', 1480, 9999);
    
    add_image_size('tube-img-md', 980, 9999);
    
    add_image_size('tube-img-md-cropped', 980, 551, true);
    
    add_image_size('tube-img-sm', 480, 9999);
    
    add_image_size('tube-img-sm-cropped', 480, 270, true);
    
  }

  
  
  // add the gloryshot CSS if needed
  function add_gloryshot_to_page() {
    
    // single post / page
    // OR home (i.e. latest posts page) that's NOT the "front" page
    if ( ( is_singular() && get_the_ID() ) ||  ( is_home() && ! is_front_page() ) ):
      
      
      if ( is_home() ):
        
        // get post ID for homepage (i.e. latest posts)
        $post_id = get_option( 'page_for_posts' );
      
      else:
        // local var for post ID
        $post_id = get_the_ID();
      
      endif;
      
      
      // make sure there's a post thumbnail
      if ( ! has_post_thumbnail( $post_id ) ):
        
        return;
        
      endif;      
      
      $gloryshot_id = get_post_thumbnail_id( $post_id );
      
      if ( $gloryshot_id ): 
        
        $gloryshot_position = get_post_meta( $post_id, 'tube_gloryshot_position', true );
        
      endif;
           
      
    // home (i.e. latest posts page) that IS the "front" page
    elseif ( is_home() && is_front_page() ):
      
      if ( get_custom_header() ) :   
  
        // get the header image data
        $header_image_data = get_custom_header( );

        // try to get ID from header image header
        $gloryshot_id = ( is_object($header_image_data) && property_exists($header_image_data, 'attachment_id') ) ? $header_image_data->attachment_id : false;
        
        if ( $gloryshot_id ):         
      
            $gloryshot_position = Tube_Theme::$tube_home_page -> get_home_gloryshot_position();
          
        endif;
        
      endif;
      
    elseif ( is_category() || is_tag() || is_tax() ):
      
      // get the term ID
      $term_id = get_queried_object_id();
      
      // image id is stored as term meta with key 'image'
      $gloryshot_id = get_term_meta( $term_id, 'image', true );  
      
      if ( $gloryshot_id ): 
        
        // try to get the position for the image
        $gloryshot_position = get_term_meta( $term_id, 'tube_gloryshot_position', true );
        
      endif;
      
    endif;
  
      
    // if no gloryshot_id, return nothing
    if ( 
      ! isset( $gloryshot_id ) || 
      ! $gloryshot_id || 
      $gloryshot_position == 'none' 
    ):
      
      return;
      
    endif;
    
    
    // use the tube "lg" image             
    $gloryshot_image_size = 'tube-img-lg';
    
    // get the image src data
    $gloryshot_image_src_data = wp_get_attachment_image_src( $gloryshot_id, $gloryshot_image_size );
    
    // make sure there's actually an image
    if ( ! $gloryshot_image_src_data ):          
    
       return;
          
    endif;
        
    $gloryshot_url = $gloryshot_image_src_data[0];
      
      
    // get the default gloryshot position if none specified
    if ( ! isset( $gloryshot_position ) || ! $gloryshot_position ):
      
      $gloryshot_position = $this -> get_default_gloryshot_position( );
      
    endif;
    
    // add a body class filter to add 'has-gloryshot' to the body class
    add_filter( 'body_class', array( $this, 'add_gloryshot_to_body_class' ) );
    
    // open the "scrim" div inside the masthead
    //add_action( 'tube_before_masthead', array( $this, 'open_scrim_div' ), 100 );
    
    // close the "scrim" div inside the masthead
    //add_action( 'tube_before_masthead', array( $this, 'close_scrim_div' ), 100 );
    
    ob_start();
    ?>
      .gloryshot-wrap:before{        
        background-image:url(<?php echo esc_url( $gloryshot_url ); ?>); 
        background-repeat: no-repeat; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-position:<?php echo esc_attr( $gloryshot_position ); ?>;
      }
    <?php
    
    $output = ob_get_clean();
    
    wp_add_inline_style( 'tube', $output );
    
  }

  //  add 'has-gloryshot' to the body class
  function add_gloryshot_to_body_class( $classes ) {
    
    $classes[] = 'has-gloryshot';
    
    return $classes;
    
  }
    
    // open the "scrim" div inside the masthead
  function open_scrim_div( ) {
    
    ?>
    <div class="scrim"><!-- -->
    <?php
    
  }
    
    // close the "scrim" div inside the masthead
  function close_scrim_div( ) {
    
    ?>
    </div> <!-- .scrim -->
    <?php    
    
  }
  
  
    
  
  // get the homepage video url
  function get_home_background_video_url( $try_plugins_too = false ) {
    
    // get the video
    $home_video_id = get_theme_mod( 'tube_home_background_video' );  
    
    // no video, so return nothing  
    if ( ! $home_video_id ):      
      
      return;
      
    endif;   
    
    return wp_get_attachment_url( $home_video_id );
  
  }
  
  
  // add the homepage video if needed
  function add_home_background_video_to_page() {
    
    // only show on homepage (i.e. latest posts) that is also front page
    
    if ( ! ( is_home() && is_front_page() ) ):    
      
      return;
      
    endif;
    
    
    // oops, no header video, so return nothing
    if ( ! has_header_video() ): 
      
      return;
      
    endif;
    
    // add a body class filter to add 'has-background-video' to the body class
    add_filter( 'body_class', array( $this, 'add_background_video_to_body_class' ) );    
    
    // add the video code inside the masthead
    add_action( 'tube_gloryshot', array( $this, 'add_background_video_to_gloryshot' ), 100 );
    
  }
  
  //  add 'has-background-video' to the body class
  function add_background_video_to_body_class( $classes ) {
    
    $classes[] = 'has-featured-video';
    
    $classes[] = 'has-background-video';
    
    return $classes;
    
  }
  
  
  // add the video code to the masthead
  function add_background_video_to_gloryshot( ) {
  
    ?>    
    <div class="vidbg-container">      
      <?php the_custom_header_markup(); ?>
    </div>
    
    <?php
    return;
    ob_start();
    ?>
          
      var isIOS = /iPad|iPhone|iPod/.test(navigator.platform);
      
      if (isIOS) {      

          var canvasVideo = new Tube_CanvasVideoPlayer({
              videoSelector: '.home-video',
              canvasSelector: '.home-video-canvas',
              timelineSelector: false,
              autoplay: true,
              loop: true,
              audio: false
          });  
          
      }else {
      
          // Use HTML5 video
          document.querySelectorAll('.home-video-canvas')[0].style.display = 'none';
      
      }  

    <?php
    
    $video_bg_script = ob_get_clean();
    
    wp_add_inline_script( 'tube', $video_bg_script );
    
  }
    
  
    
  
  
  // add the video_background_plugin video if needed
  function add_video_background_plugin_video_to_page() {
    
    
    // make sure it's a singular post with an ID, else do nothing
    if ( ! ( is_singular() && get_the_ID() ) ):
    
      return;
      
    endif;
    
    // try using the "Video Background" plugin
    $video_background_url = get_post_meta( get_the_ID(), 'vidbg_metabox_field_mp4', true);

    if ( ! $video_background_url ) :
      
        return;
     
    endif;    
      
    // add a body class filter to add 'has-background-video' to the body class
    add_filter( 'body_class', array( $this, 'add_background_video_to_body_class' ) );    
    
    // open the "scrim" div inside the masthead
    add_action( 'tube_masthead_top', array( $this, 'open_scrim_div' ), 100 );
    
    // close the "scrim" div inside the masthead
    add_action( 'tube_masthead_bottom', array( $this, 'close_scrim_div' ), 120 );
    
  }
  
    
  

  function add_gloryhsot_position_to_featured_image_metabox($content) {
    
    $gloryshot_positions = $this -> get_gloryshot_positions();
    
    ob_start();
    ?>
      <hr />
      <p>
        <strong><?php _ex( 'Image Position', 'Featured image metabox: image position heading', 'tube' ); ?></strong><br />
        <em><?php _ex( 'Chose where the image is anchored to the page masthead.', 'Featured image metabox: image position description', 'tube' ); ?></em>
      </p>
      <?php
    
      // Noncename needed to verify where the data originated
      echo '<input type="hidden" name="tube_gloryshot_position_nonce" id="tube_gloryshot_position_nonce" value="' . wp_create_nonce( 'tube_gloryshot_position_nonce' ) . '" />';
            
      $curr_gloryshot_position = get_post_meta( get_the_ID(), 'tube_gloryshot_position', true );
      
      if ( !$curr_gloryshot_position ):
        $curr_gloryshot_position = $this -> get_default_gloryshot_position();
      endif;
      
      foreach ( $gloryshot_positions as $position_key => $gloryshot_position ):
        
        $position_slug = 'tube-gloryshot-position-' . esc_attr( sanitize_title_with_dashes( $position_key ) );
        ?>
        <input type="radio" name="tube_gloryshot_position" value="<?php echo esc_attr($position_key); ?>" id="<?php echo esc_attr($position_slug); ?>" <?php checked( $curr_gloryshot_position, $position_key ); ?> /> <label for="<?php echo esc_attr($position_slug); ?>"><?php echo esc_html($gloryshot_position); ?></label><br />
        <?php
        
      endforeach;
      
      
    $position_html = ob_get_clean();
    
    return $content . $position_html;
    
  }



    
  function save_gloryshot_position( $post_id, $post ){
    
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    
    // make sure the nonce and value are passed
    if ( ! isset( $_POST['tube_gloryshot_position_nonce'], $_POST['tube_gloryshot_position']  ) ):
       return;
    endif;
  
    // verify the sanitized the nonce    
    if ( ! wp_verify_nonce( sanitize_key( $_POST['tube_gloryshot_position_nonce'] ), 'tube_gloryshot_position_nonce' )) {
      return;
    }
         
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post_id ))
      return $post_id;  
    
    // unslash and sanitize the value
    $post_meta['tube_gloryshot_position'] = $this -> sanitize_gloryshot_position( wp_unslash( $_POST['tube_gloryshot_position'] ) );    
    
    
    foreach ($post_meta as $key => $value) : // Cycle through the $terms_list_meta array!
      if( $post->post_type == 'revision' ) return; // Don't store custom data twice
      $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
      if(get_post_meta($post_id, $key, FALSE)) : // If the custom field already has a value
        update_post_meta($post_id, $key, $value);
      else: // If the custom field doesn't have a value
        add_post_meta($post_id, $key, $value);
      endif;
      if(!$value) delete_post_meta($post_id, $key); // Delete if blank
    endforeach;
    
    return;
    
  }      

    
  function sanitize_gloryshot_position( $position ){
    
    // get available positions
    $gloryshot_positions = $this -> get_gloryshot_positions();
    
    // invalid value so return the default
    if( ! array_key_exists ( $position , $gloryshot_positions ) ):
      return $this -> get_default_gloryshot_position();
    endif;
      
    // return the position
    return $position;
    
  }      

    
  function get_default_gloryshot_position(){
    
    $default_gloryshot_position = 'center center';
    
    $default_gloryshot_position = apply_filters( 'tube_filter_default_gloryshot_position', $default_gloryshot_position);
    
    return $default_gloryshot_position;
    
  }      


  // create an array of potential CSS values for the gloryshot position
  
  function get_gloryshot_positions() {
    
    $gloryshot_positions = array(
      'center center'  => _x('center center', 'Featured image metabox: gloryshot position label ', 'tube'),
      'center top'  => _x('center top', 'Featured image metabox: gloryshot position label ', 'tube'),
      'right top'  => _x('right top', 'Featured image metabox: gloryshot position label ', 'tube'),
      'right center'  => _x('right center', 'Featured image metabox: gloryshot position label ', 'tube'),
      'right bottom'  => _x('right bottom', 'Featured image metabox: gloryshot position label ', 'tube'),
      'center bottom'  => _x('center bottom', 'Featured image metabox: gloryshot position label ', 'tube'),
      'left bottom'  => _x('left bottom', 'Featured image metabox: gloryshot position label ', 'tube'),
      'left center'  => _x('left center', 'Featured image metabox: gloryshot position label ', 'tube'),
      'left top'  => _x('left top', 'Featured image metabox: gloryshot position label ', 'tube'),
      'none'  => _x('none / do not show', 'Featured image metabox: gloryshot position label ', 'tube')
    );
    
    return $gloryshot_positions;    
        
  }
  
  
  
}