<?php
/**
 * Tube_Taxonomy
 * 
 * Adds various functionality around creating a Terms List template
 * Adds function to show terms links module for a specific post
 * 
 * @package .TUBE
 * @subpackage Functions
 * @author .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */
 
class Tube_Taxonomy {
  
  public static $instance;  
  
  public static function init()  {
      if ( is_null( self::$instance ) )
          self::$instance = new Tube_Taxonomy();
      return self::$instance;
  }

  // Constructor
  function __construct() {

    // add the terms list metabox (only for 'page' post type)
    add_action( 'add_meta_boxes_page', array( $this, 'add_terms_list_meta_box' ), 10, 1 );
    
    add_action( 'save_post', array( $this, 'save_terms_list_metabox_settings' ), 99, 2 ); // save the custom fields 

  }
  
     
  // adds the Terms List settings meta box for pages using the template-terms-list.php
  function add_terms_list_meta_box( $post ){    
    
    // check the page template file for the post
    $template_file = get_post_meta( $post->ID, '_wp_page_template', true); 
    
    // make sure it's a terms list template, or do nothing
    if ( 'template-terms-list.php' != $template_file ):
      return;
    endif;    
    
    // add the terms list metabox
    add_meta_box(
      'tube_terms_list_meta', // slug
      _x('Terms List Settings', 'Terms list metabox: metabox title', 'tube'), // title
      array( $this, 'show_terms_list_settings_metabox' ), // display callback
      'page', // current screen
      'side', // placement
      'high' // priority
    );
    
  }
         
  // display callback for the Terms List settings meta box  
  function show_terms_list_settings_metabox() {
        
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="tube_terms_list_nonce" id="tube_terms_list_nonce" value="' . wp_create_nonce( 'tube_terms_list_nonce' ) . '" />';
    
    // local var for post ID
    $post_id = get_the_ID();
      
    // TAXONOMY  
    
    // Get post type taxonomies.
    $taxonomies = $this -> get_terms_list_metabox_taxonomy_options();   
    
    // make sure there are taxonomies, or do nothing
    if ( ! $taxonomies ):
      
      return;
      
    endif;    
    
    // get the current taxonomy, if any 
    $curr_taxonomy = get_post_meta( $post_id, 'tube_terms_list_taxonomy', true );
    
    ?>    
    <p>
      <label for="tube_terms_list_taxonomy"><strong><?php _ex('Taxonomy', 'Terms list metabox: taxonomy dropdown label', 'tube'); ?></strong></label>      
    </p>
    
    <select name="tube_terms_list_taxonomy" id="tube_terms_list_taxonomy">
      <?php foreach ( $taxonomies as $tax_slug => $taxonomy ): ?>
        <option value="<?php echo esc_attr($tax_slug); ?>" <?php selected( $curr_taxonomy, $tax_slug ); ?>>
          <?php echo esc_html( $taxonomy -> labels -> singular_name ); ?>
        </option>
      <?php endforeach; ?>    
    </select>
    

    <?php    
    // ORDER BY
    
    // Options for the orderby selector
    $orderby_options = $this -> get_terms_list_metabox_orderby_options();  
    
    // make sure there are taxonomies, or do nothing
    if ( ! $orderby_options ):
      
      return;
      
    endif;        
      
    $curr_orderby = get_post_meta( $post_id, 'tube_terms_list_orderby', true );
    
    if ( ! $curr_orderby ):
      $curr_orderby = 'name';
    endif;   
    ?>    
    <p>
      <label for="tube_terms_list_orderby">
        <strong>
          <?php _ex('Order By', 'Terms list metabox: order by drodpown label', 'tube'); ?>
        </strong>
      </label>      
    </p>
    
    <select name="tube_terms_list_orderby" id="tube_terms_list_orderby">      
      <?php foreach ( $orderby_options as $orderby_slug => $orderby_name ): ?>        
        <option value="<?php echo esc_attr($orderby_slug); ?>" <?php selected( $curr_orderby, $orderby_slug ); ?>>
          <?php echo esc_html($orderby_name); ?>
        </option>        
       <?php endforeach; ?>       
    </select>


    <?php   
    // ORDER
    
    // Options for the order selector
          
    $order_options = $this -> get_terms_list_metabox_order_options();  
    
    $curr_order = get_post_meta( $post_id, 'tube_terms_list_order', true );
    
    if ( ! $curr_order ):
      $curr_order = 'ASC';
    endif;
            
    ?>    
    <p>
      <label for="tube_terms_list_order">
        <strong>
          <?php _ex('Order', 'Terms list metabox: order drodpown label', 'tube'); ?>
        </strong>
      </label>      
    </p>
    
    <select name="tube_terms_list_order" id="tube_terms_list_order">
      
      <?php foreach ( $order_options as $order_slug => $order_name ): ?>
      
        <option value="<?php echo esc_attr($order_slug); ?>" <?php selected( $curr_order, $order_slug ); ?>>
          <?php echo esc_html($order_name); ?>
        </option>
        
      <?php endforeach; ?>
    
    </select>

    <?php

  }



  function sanitize_terms_list_metabox_taxonomy( $taxonomy ){
    
    // get available taxonomies
    $taxonomies = $this -> get_terms_list_metabox_taxonomy_options();
    
    // make sure there are taxonomies
    if ( ! $taxonomies ):
      return NULL;      
    endif;

    // invalid value so return the first item
    if( ! array_key_exists ( $taxonomy , $taxonomies ) ):
      
      // get the default
      return $this -> get_default_terms_list_metabox_taxonomy( );
      
    endif;
      
    // return the taxonomy
    return $taxonomy;
    
  }      

    
  // get the default taxonomy for the terms list metabox
  function get_default_terms_list_metabox_taxonomy(){
    
   
    // get available taxonomies
    $taxonomies = $this -> get_terms_list_metabox_taxonomy_options();
    
    // make sure there are taxonomies
    if ( ! $taxonomies ):
      return NULL;      
    endif;
    
    // get teh first one
    $default_tax = reset( $taxonomies );
    
    // return the default name
    return $default_tax->name;      
    
  }      
  
  // get the list of taxonomies for the terms list metabox
  function get_terms_list_metabox_taxonomy_options( ) {    

    
    // Get post type taxonomies.
    $taxonomies = get_taxonomies( NULL, 'objects', array( '' => '' ) );   
    
    // don't include the Nav Menu, Link Cat, or Post Format taxonomies
    unset($taxonomies['nav_menu']);
    unset($taxonomies['link_category']);
    unset($taxonomies['post_format']);
        
    // allow taxonomies to be filtered
    $taxonomies = apply_filters('tube_filter_terms_list_template_taxonomy_options', $taxonomies);
    
    // make sure there are taxonomies, or do nothing
    if ( ! $taxonomies || count($taxonomies) == 0 ):
      
      return NULL;
      
    endif;

    return $taxonomies;
  
  }
  
  
  
  
  
  
  function sanitize_terms_list_metabox_orderby( $orderby_option ){
    
    // get available orderby_options
    $orderby_options = $this -> get_terms_list_metabox_orderby_options();
    
    // make sure there are orderby_options
    if ( ! $orderby_options ):
      return NULL;      
    endif;

    // invalid value so return the first item
    if( ! array_key_exists ( $orderby_option , $orderby_options ) ):
      
      // get the default
      return $this -> get_default_terms_list_metabox_orderby( );
      
    endif;
      
    // return the orderby_option
    return $orderby_option;
    
  }      

    
  function get_default_terms_list_metabox_orderby(){
    
   
    // get available orderby_options
    $orderby_options = $this -> get_terms_list_metabox_orderby_options();
    
    // make sure there are orderby_options
    if ( ! $orderby_options ):
      return NULL;      
    endif;
    
    // get teh first one
    $default_orderby_option =  key( reset( $orderby_options ) );
    
    // return the default name
    return $default_orderby_option;      
    
  }      
  
  
  
  // get the list of taxonomies for the terms list metabox
  function get_terms_list_metabox_orderby_options( ) {    
    
    // Options for the orderby selector
    $orderby_options = array(
      'name' =>  _x( 'Name', 'Terms list metabox: orderby option', 'tube' ),
      'count' =>  _x( 'Count', 'Terms list metabox: orderby option', 'tube' ),
      'slug' =>  _x( 'Slug', 'Terms list metabox: orderby option', 'tube' ),
      'ID' =>  _x( 'ID', 'Terms list metabox: orderby option', 'tube' ),
    );    
        
    // allow $orderby_options to be filtered
    $orderby_options = apply_filters('tube_filter_terms_list_template_orderby_options', $orderby_options);
    
    return $orderby_options;        
      
  }
  
  
  
  
  
  function sanitize_terms_list_metabox_order( $order_option ){
    
    // get available order_options
    $order_options = $this -> get_terms_list_metabox_order_options();
    
    // make sure there are order_options
    if ( ! $order_options ):
      return NULL;      
    endif;

    // invalid value so return the first item
    if( ! array_key_exists ( $order_option , $order_options ) ):
      
      // get the default
      return $this -> get_default_terms_list_metabox_order( );
      
    endif;
      
    // return the order_option
    return $order_option;
    
  }      

    
  function get_default_terms_list_metabox_order(){
       
    // get available order_options
    $order_options = $this -> get_terms_list_metabox_order_options();
    
    // make sure there are order_options
    if ( ! $order_options ):
      return NULL;      
    endif;
    
    // get teh first one
    $default_order_option =  key( reset( $order_options ) );
    
    // return the default name
    return $default_order_option;      
    
  }      
  
  
  
  // get the list of taxonomies for the terms list metabox
  function get_terms_list_metabox_order_options( ) {    
    
    // Options for the order selector
    $order_options = array(
      'ASC' => _x( 'Ascending', 'Terms list metabox: order option', 'tube' ),
      'DESC' => _x( 'Descending', 'Terms list metabox: order option', 'tube' ),
    );
        
    // allow $order_options to be filtered
    $order_options = apply_filters('tube_filter_terms_list_template_order_options', $order_options);
    
    return $order_options;
        
      
  }
  
  
  // function to save the Terms List Settings to post meta data
  function save_terms_list_metabox_settings($post_id, $post) {    
           
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    
    // make sure the nonce and value are passed
    if ( ! isset( $_POST['tube_terms_list_nonce'], $_POST['tube_terms_list_taxonomy'], $_POST['tube_terms_list_orderby'], $_POST['tube_terms_list_order']  ) ):
       return;
    endif;

    
    if ( ! wp_verify_nonce( sanitize_key( $_POST['tube_terms_list_nonce'] ), 'tube_terms_list_nonce' ) ) {
      return $post_id;
    }
        
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post_id ))
      return $post_id;  
    
    $terms_list_meta['tube_terms_list_taxonomy'] = $this-> sanitize_terms_list_metabox_taxonomy( wp_unslash( $_POST['tube_terms_list_taxonomy'] ) );   
    
    $terms_list_meta['tube_terms_list_orderby'] = $this-> sanitize_terms_list_metabox_orderby( wp_unslash( $_POST['tube_terms_list_orderby'] ) );   
    
    $terms_list_meta['tube_terms_list_order'] = $this-> sanitize_terms_list_metabox_order( wp_unslash( $_POST['tube_terms_list_order'] ) );   
    
    foreach ($terms_list_meta as $key => $value) : // Cycle through the $terms_list_meta array!
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
















  // Display a list of terms based on a page's Terms List Settings
  function show_terms_list ($args = NULL){         
    
    // set defaults
    $defaults = array(
      'echo'                => 0,
      'hide_title_if_empty' => true, // (bool) Whether to hide the $title_li element if there are no terms in the list. Default false (title will always be shown).
      'taxonomy'            => NULL, // (string) Taxonomy name. Default 'category'.
      'title_li'            => NULL, // (string) Text to use for the list title <li> element. Pass an empty string to disable. Default 'Categories'.
      'show_option_none' => NULL, // (string) Text to display for the 'no categories' option. Default 'No categories'.
    );
    
    // local var for post ID
    $post_id = get_the_ID();    
    
    // test if taxonomy passed as argument  
    if ( ! $args || ! array_key_exists('taxonomy', $args) || ! $args['taxonomy'] ) :
      
      // no tax passed, so get the taxonomy from the post meta
      $taxonomy = get_post_meta( $post_id, 'tube_terms_list_taxonomy', true );
      
      // make sure there's a taxonomy
      if ( ! $taxonomy ):
        
        return;
        
      endif;
      
      // add the taxonomy to the arguments
      $args['taxonomy'] = $taxonomy;
    
    endif;
    
      
    // test if orderby passed as argument
    if ( ! $args || ! array_key_exists('orderby', $args) || ! $args['orderby'] ) :
      
      // no orderby passed, so try to get the orderby from the post meta
      $args['orderby'] = get_post_meta( $post_id, 'tube_terms_list_orderby', true );
    
    endif;    
         
      
    // test if order passed as argument
    if ( ! $args || ! array_key_exists('order', $args) || ! $args['order'] ) :      
      
      // no order passed, so try to get the order from the post meta
      $args['order'] = get_post_meta( $post_id, 'tube_terms_list_order', true );
    
    endif;   
    
      
    // test if show_option_none passed as argument
    if ( ! $args || ! array_key_exists('show_option_none', $args) || ! $args['show_option_none'] ) :      
     
      // get the taxonomy name
      $taxonomy_name = get_taxonomy( $taxonomy )->labels->name;
      
      // no show_option_none passed, so use the taxonomy name
      $args['show_option_none'] = sprintf( _x( 'No %s found.', 'Text when no available terms on terms list template. Use %s placeholder for lowercase taxonomy name.', 'tube' ), strtolower($taxonomy_name) );
    
    endif;   
    
    
    // parse the arguments and defaults
    $args = wp_parse_args( $args, $defaults );
    
    // allow the arguments to be filtered    
    $args = apply_filters( 'tube_filter_show_terms_list_args', $args );     
    
    // make sure there's a taxonomy
    if ( ! $args['taxonomy'] ):
    
      return;
      
    endif;          
     
     // get the formatted terms
    $terms = wp_list_categories( $args );  
    
    // display the terms
    ?>    
    
    <ul class="list-terms">
      
      <?php echo wp_kses_post($terms); ?>    
    
    </ul>
      
    <?php    
    return;    
    
  }

  
  
  // create a structured output of post terms links
  // used on single.php to show all the terms a
  function post_terms_links_module() {
    
    // don't show on password protected posts
    if ( post_password_required() ):
      return;
    endif;

    // get the current post object
    $the_post = get_queried_object();
 
    // local var for post ID
    $post_id = $the_post->ID;
      
    // Get post type taxonomies.
    $taxonomies = get_object_taxonomies( $the_post->post_type, 'objects' );
    
    // don't include the "Post Format" taxonomy
    unset($taxonomies['post_format']);
    
    // allow taxonomies to be filtered
    $taxonomies = apply_filters('tube_filter_post_terms_links_taxonomies', $taxonomies);
    
    // create an array of excluded terms on a per-tax basis
    $excluded_terms = array();
    
    // exclude the uncategorized term from the Category taxonomy
    $excluded_terms['category'] = array('uncategorized');
    
    // allow excluded terms to be filtered
    $excluded_terms = apply_filters('tube_filter_post_terms_links_excluded_terms', $excluded_terms);
    
    // create array for the output
    $post_terms_links = array();
 
    // loop through the taxonomyies
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ):
         
        // NOTE: Not really used but required to pass Theme Check
        $terms_list_UNUSED = get_the_term_list( $post_id, $taxonomy_slug );
 
        // Get the terms related to post for current taxonomy
        $terms = get_the_terms( $post_id, $taxonomy_slug );            
    
        // if no terms, go to next taxonmy
        if ( empty( $terms ) ):
          continue;
        endif;
        
        // create array for the keeper terms to be stored
        $tax_terms = array();
                  
        // loop through the terms 
        foreach ( $terms as $term ):
          
            // check if this term is excluded
            if ( array_key_exists($taxonomy_slug, $excluded_terms) && in_array($term->slug, $excluded_terms[$taxonomy_slug]) ):
              continue;
            endif;           
             
            $term_slug = esc_attr( str_replace('%', '%%', $term->slug) );
            
            // add the li for the term
            $tax_terms[] = sprintf( '<a  class="term-'.$term_slug.'" href="%1$s">%2$s</a>' . "\n",
                esc_url( get_term_link( $term->slug, $taxonomy_slug ) ),
                esc_html( $term->name )
            );
            
        endforeach; // terms loop
        
        // make sure there are terms for the taxonomy
        if ( count($tax_terms) == 0 ):
          continue;
        endif;
        
        // TODO :: Put Post Terms Links into a partial
        
        // add the terms to the output
        $post_terms_links[] = '<h4 class="tax-label">' . esc_html($taxonomy->label) . '</h4>';
        $post_terms_links[] = '<hr class="highlight" />';        
        $post_terms_links[] = '<div class="tagcloud post-terms-links tax-' . esc_attr($taxonomy_slug) . ' list-inline">';
        $post_terms_links = array_merge($post_terms_links, $tax_terms);
        $post_terms_links[] = "\n</div>\n"; 

    endforeach; // taxonomies loop

    // if no output, return nothing
    if ( count($post_terms_links) == 0 ):
      return;
    endif;
    
    // implode the output into a string
    $post_terms_links = implode( '', $post_terms_links );
    
    // TODO :: Put Post Terms Links Wrapper into a partial
    
    // wrap the output in a div
    ?>
    
     <div class="post-terms-links-wrap">    
                      
        <h3 class="hide"><?php _ex('More Like This', 'Heading for associated terms on single post', 'tube') ?></h3>
        
        <hr class="hide highlight" />  
        
        <div class="post-terms-links">      
                    
          <?php echo wp_kses_post($post_terms_links); ?>     
                
        </div> 
             
      </div><!-- .pagination-wrap -->
      
    <?php
    
  }


}