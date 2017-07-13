<?php
/**
 * Tube_Labels
 * 
 * Setttings to customize various labels and text strings
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.5
 */
 
 
class Tube_Labels{
  
  public static $instance;
  
  public static function init()
  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Labels();
      return self::$instance;
  }
  
  
  // Constructor  
  
  function __construct() {
    
    // Add home settings
    add_action( 'customize_register', array( $this, 'add_label_settings' ), 99 );
    
    add_action( 'customize_register', array( $this, 'customize_register_post_list_label_taxonomy' ), 100 , 1);
      
    add_action( 'tube_post_list_before_title', array( $this, 'add_label_above_post_list_title' ), 100 );
    
    // add cosmetic filters for the labels
    add_filter( 'tube_filter_sticky_posts_heading', 'stripslashes' );
    add_filter( 'tube_filter_sticky_posts_heading', 'wptexturize' );
      
    add_filter( 'tube_filter_posts_list_heading', 'stripslashes' );
    add_filter( 'tube_filter_posts_list_heading', 'wptexturize' );
    
    add_filter( 'tube_filter_no_results_heading', 'stripslashes' );
    add_filter( 'tube_filter_no_results_heading', 'wptexturize' );
    
    add_filter( 'tube_filter_all_posts_button_text', 'stripslashes' );
    add_filter( 'tube_filter_all_posts_button_text', 'wptexturize' );
    
    add_filter( 'tube_filter_search_placeholder', 'stripslashes' );
    add_filter( 'tube_filter_search_placeholder', 'wptexturize' );
    
    add_filter( 'tube_filter_search_results_header_label', 'stripslashes' );
    add_filter( 'tube_filter_search_results_header_label', 'wptexturize' );
    
    add_filter( 'tube_filter_search_no_results_message', 'stripslashes' );
    add_filter( 'tube_filter_search_no_results_message', 'wptexturize' );     
    
  }
    

  function add_label_settings( $wp_customize ) {         
    
    // Add the Home / Latest Posts Page section
    $wp_customize->add_section( 'tube_customize_section_labels' , array(
        'title'       => _x( 'Labels & Text Strings', 'Customizer: labels section title', 'tube' ),
        'priority'    => 155,
        'description'    => _x('These settings control various labels and text strings on the site.', 'Customizer: labels section description', 'tube' ),
    ) ); 
                            
    
       
    // Add setting for Sticky Posts Heading
    $wp_customize->add_setting( 'tube_sticky_posts_heading', array(
        'default' => self::get_default_sticky_posts_heading(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_filter_post_kses',
      )
    );
    
    // Add control for Sticky Posts Heading 
    $wp_customize->add_control( 'tube_sticky_posts_heading', array(
          'label' => _x( 'Sticky / Featured Posts Heading', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('The heading above sticky posts featured on the home page.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );   
       
       
       
    // Add setting for Posts List Heading
    $wp_customize->add_setting( 'tube_posts_list_heading', array(
        'default' => self::get_default_posts_list_heading(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_filter_post_kses',
      )
    );
    
    // Add control for Posts List Heading 
    $wp_customize->add_control( 'tube_posts_list_heading', array(
          'label' => _x( 'Posts List Heading', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('The heading above post lists on the home page, index / archive pages, and below single post.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );       
    
       
       
       
    // Add setting for No Results Heading
    $wp_customize->add_setting( 'tube_no_results_heading', array(
        'default' => self::get_default_no_results_heading(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_filter_post_kses',
      )
    );
    
    // Add control for No Results Heading
    $wp_customize->add_control( 'tube_no_results_heading', array(
          'label' => _x( 'No Results Heading', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('The heading above post lists when there are no results.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );       
    
       
       
    // Add setting for All Posts Button Text
    $wp_customize->add_setting( 'tube_all_posts_button_text', array(
        'default' => self::get_default_all_posts_button_text(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_filter_post_kses',
      )
    );
    
    // Add control for All Posts Button Text
    $wp_customize->add_control( 'tube_all_posts_button_text', array(
          'label' => _x( 'All Posts Button Text', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('The button to view all posts under the recent posts below single post.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );       
    

       
       
    // Add setting for Search Placeholder
    $wp_customize->add_setting( 'tube_search_placeholder', array(
        'default' => self::get_default_search_placeholder(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
      )
    );
    
    // Add control for Search Placeholder
    $wp_customize->add_control( 'tube_search_placeholder', array(
          'label' => _x( 'Search Placholder', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('Appears inside the search box in the site header.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );   

       
       
    // Add setting for Search Results Heading Label
    $wp_customize->add_setting( 'tube_search_results_header_label', array(
        'default' => self::get_default_search_results_header_label(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
      )
    );
    
    // Add control for Search Results Heading Label
    $wp_customize->add_control( 'tube_search_results_header_label', array(
          'label' => _x( 'Search Results Heading Label', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('Appears at the top of the search results page.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );   


       
    // Add setting for No Search Results Message
    $wp_customize->add_setting( 'tube_search_no_results_message', array(
        'default' => self::get_default_search_no_results_message(),
        'type'      => 'theme_mod',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
      )
    );
    
    // Add control for No Search Results Message
    $wp_customize->add_control( 'tube_search_no_results_message', array(
          'label' => _x( 'No Search Results Message', 'Customizer: labels & headings control label', 'tube' ),
          'description' => _x('Appears on the page when there are no search results.', 'Customizer: labels & headings control description', 'tube'), 
          'section' => 'tube_customize_section_labels',
          'type'     => 'text',
        )
    );   
       

  
  }
   

  
  
  
  static function get_default_sticky_posts_heading(){
    
    $default_sticky_posts_heading = _x('Featured Posts', 'Customizer: default featured posts heading', 'tube');
    
    $default_sticky_posts_heading = apply_filters( 'tube_filter_default_sticky_posts_heading', $default_sticky_posts_heading);
        
    return $default_sticky_posts_heading;
    
  }     
    
    
  function get_sticky_posts_heading(){
    
    $default_sticky_posts_heading = self :: get_default_sticky_posts_heading();
    
    $sticky_posts_heading = get_theme_mod( 'tube_sticky_posts_heading', $default_sticky_posts_heading );
    
    $sticky_posts_heading = apply_filters( 'tube_filter_sticky_posts_heading', $sticky_posts_heading);

    return $sticky_posts_heading;
    
  }      
  
      
       
    
  static function get_default_posts_list_heading(){
    
    $default_posts_list_heading = _x('Latest Posts', 'Customizer: default latest posts heading', 'tube');
    
    $default_posts_list_heading = apply_filters( 'tube_filter_default_posts_list_heading', $default_posts_list_heading);
        
    return $default_posts_list_heading;
    
  }     
    
    
  function get_posts_list_heading(){
    
    $default_posts_list_heading = self :: get_default_posts_list_heading();
    
    $posts_list_heading = get_theme_mod( 'tube_posts_list_heading', $default_posts_list_heading );
    
    $posts_list_heading = apply_filters( 'tube_filter_posts_list_heading', $posts_list_heading);

    return $posts_list_heading;
    
  }      


       
    
  static function get_default_no_results_heading(){
    
    $default_no_results_heading = _x('Sorry, no results.', 'Customizer: default no results heading', 'tube');
    
    $default_no_results_heading = apply_filters( 'tube_filter_default_no_results_heading', $default_no_results_heading);
        
    return $default_no_results_heading;
    
  }     
    
    
  function get_no_results_heading(){
    
    $default_no_results_heading = self :: get_default_no_results_heading();
    
    $no_results_heading = get_theme_mod( 'tube_no_results_heading', $default_no_results_heading );
    
    $no_results_heading = apply_filters( 'tube_filter_no_results_heading', $no_results_heading);

    return $no_results_heading;
    
  }      
  
  
    
  static function get_default_all_posts_button_text(){
    
    $default_all_posts_button_text = _x('See All New Posts', 'Customizer: default see all new posts button text', 'tube');
    
    $default_all_posts_button_text = apply_filters( 'tube_filter_default_all_posts_button_text', $default_all_posts_button_text);
        
    return $default_all_posts_button_text;
    
  }     
    
    
  function get_all_posts_button_text(){
    
    $default_all_posts_button_text = self :: get_default_all_posts_button_text();
    
    $all_posts_button_text = get_theme_mod( 'tube_all_posts_button_text', $default_all_posts_button_text );
        
    $all_posts_button_text = apply_filters( 'tube_filter_all_posts_button_text', $all_posts_button_text);

    return $all_posts_button_text;
    
  }      


       
    
  static function get_default_search_placeholder(){
    
    $default_search_placeholder = _x('Search&hellip;', 'Customizer: default search placeholder', 'tube');
    
    $default_search_placeholder = apply_filters( 'tube_filter_default_search_placeholder', $default_search_placeholder);
        
    return $default_search_placeholder;
    
  }     
    
    
  function get_search_placeholder(){
    
    $default_search_placeholder = self :: get_default_search_placeholder();
    
    $search_placeholder = get_theme_mod( 'tube_search_placeholder', $default_search_placeholder );
        
    $search_placeholder = apply_filters( 'tube_filter_search_placeholder', $search_placeholder);

    return $search_placeholder;
    
  }      
  
  
  
    
  static function get_default_search_results_header_label(){
    
    $default_search_results_header_label = _x('Search for', 'Customizer: default search results page masthead label', 'tube');
    
    $default_search_results_header_label = apply_filters( 'tube_filter_default_search_results_header_label', $default_search_results_header_label);
        
    return $default_search_results_header_label;
    
  }     
    
    
  function get_search_results_header_label(){
    
    $default_search_results_header_label = self :: get_default_search_results_header_label();
    
    $search_results_header_label = get_theme_mod( 'tube_search_results_header_label', $default_search_results_header_label );
        
    $search_results_header_label = apply_filters( 'tube_filter_search_results_header_label', $search_results_header_label);

    return $search_results_header_label;
    
  }      
  
  
  static function get_default_search_no_results_message(){
    
    $default_search_no_results_message = _x('Please try another search.', 'Customizer: default no search results message', 'tube');
    
    $default_search_no_results_message = apply_filters( 'tube_filter_default_search_no_results_message', $default_search_no_results_message);
        
    return $default_search_no_results_message;
    
  }     
    
    
  function get_search_no_results_message(){
    
    $default_search_no_results_message = self :: get_default_search_no_results_message();
    
    $search_no_results_message = get_theme_mod( 'tube_search_no_results_message', $default_search_no_results_message );
        
    $search_no_results_message = apply_filters( 'tube_filter_search_no_results_message', $search_no_results_message);

    return $search_no_results_message;
    
  }      
  
  
  
  

   
   
  public function customize_register_post_list_label_taxonomy($wp_customize){

    
    // Add setting for post list label taxonomy
    $wp_customize->add_setting( 
      'tube_mod_post_list_label_taxonomy',
      array(
          'default'      => '',
          'transport' => 'refresh',
          'sanitize_callback' => array( $this, 'sanitize_post_list_label_taxonomy' ),
      )
    );
    
    $args = array(
      'public' => true
    );
    
    $taxonomies_raw = get_taxonomies( $args, 'objects');
    $taxonomies = array();
    $taxonomies[''] = 'None';
    
    $i = 0;
    foreach($taxonomies_raw as $taxonomy){
      $taxonomies[$taxonomy->name] = $taxonomy->labels->singular_name;
    }
  
    // Add control for post list label taxonomy
    $wp_customize->add_control(
        'tube_mod_post_list_label_taxonomy',
        array(
            'type' => 'select',
            'label' => _x( 'Post List Label Taxonomy', 'Customizer: labels & headings control label', 'tube' ),
            'description' => _x( 'Show a term label above post title lists. Will show Yoast SEO &#8220;primary&#8221; term if available.', 'Customizer: labels & headings control description', 'tube' ),
            'section' => 'tube_customize_section_labels',
            'choices' => $taxonomies,
            'priority' => '300'
        )
    );
    
    
    
  }
   
  
  function sanitize_post_list_label_taxonomy( $post_list_label_taxonomy ){    
   
    if ( ! taxonomy_exists( $post_list_label_taxonomy ) ):
      return '';
    endif;
    
    return $post_list_label_taxonomy;      
    
  }  

  
  
      
  function add_label_above_post_list_title(  ) {
    
    // get a list of post types
    $allowed_post_types = get_post_types();
    
    // filter the post types that get the label
    $allowed_post_types = apply_filters( 'tube_filter_label_above_post_list_post_types', $allowed_post_types );    
    
    // make sure there are allowed post types
    if ( ! is_array( $allowed_post_types) ):
       return;
    endif;
    
    // get the current post object
    $the_post = get_post( get_the_id() );
        
    // make sure current post type is allowed
    if ( ! in_array( $the_post -> post_type , $allowed_post_types ) ):
       return;
    endif;
    
    // get the taxonomy for the labels (if any)
    $post_list_label_taxonomy = get_theme_mod( 'tube_mod_post_list_label_taxonomy', '' );

    // if no taxonomy, do nothing
    if( ! $post_list_label_taxonomy ):
      return;
    endif;
    
   // get the label for the primary term for that taxonomy for this post
    $label = $this -> get_primary_term_as_label( $post_list_label_taxonomy );
    
    // if no lable, do nothing
    if ( ! $label ):
      return;
    endif;    
    
    // output the label
    ?>    
    <div class="label-wrap">
        <?php echo wp_kses_post( $label ); ?>
    </div> <!-- .label-wrap -->  
    <?php
  }

  
  //http://www.joshuawinn.com/using-yoasts-primary-category-in-wordpress-theme/
  
  function get_primary_term_as_label( $taxonomy = 'category', $link_term = true ){
     
         
    $term_slug = '';
    $term_name = '';      
    $term_link = '';
      
    // SHOW YOAST PRIMARY CATEGORY, OR FIRST CATEGORY
    $terms = get_the_terms( get_the_id(), $taxonomy);
    
    // If post has terms for this taxonomy
    if ( $terms ){
      
      
      if ( class_exists('WPSEO_Primary_Term') ):
        
        // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
        $wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, get_the_id() );
        
        $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
        
        $term = get_term( $wpseo_primary_term );
        
        if (is_wp_error($term)) :
          $term = $terms[0];
        endif;
      
      else:
        
        // use the first term
        $term = $terms[0];
        
      endif;
      
      
      $term_slug = $term->slug;
      $term_name = $term->name;
      $term_link = get_term_link( $term, $taxonomy );
          
      ob_start();
     
      // Create the HTML snippet for the term label

      if ( ! empty($term_name) ):
        
        if ( $link_term == true && !empty($term_link) ):
          echo '<label class="label label-default term-label term-'. esc_attr($term_slug) .'">';
            echo '<a href="'.esc_url($term_link).'">'.esc_html($term_name).'</a>';
          echo '</label>';
        else:
          echo '<label class="label term-label term-'. esc_attr($term_slug) .'">';
            echo esc_html($term_name);
          echo '</label>';
        endif;
        
      endif;
      
      $output = ob_get_clean();
      
      return $output;
      
    }
  
  }
  
  
  
}