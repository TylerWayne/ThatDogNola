<?php
/**
 * Tube_Colors
 * 
 * Add custom color schemes and color editing support to Customizer
 *
 * Code is based on code from twentysixteen theme, Copyright 2014-2015 WordPress.org
 * 
 * @package .TUBE
 * @subpackage Customizer
 * @author  .TUBE gTLD <https://www.get.tube>
 * @copyright Copyright 2017, TUBE®
 * @link https://www.get.tube/wordpress
 * @since 1.0.0.0
 */


class Tube_Colors {
    
    public static $instance;
    
    public static function init() {
        
        if ( is_null( self::$instance ) )
            self::$instance = new Tube_Colors();
        
        return self::$instance;
        
    }
    
    // Constructor  
    


    function __construct() {
      
        add_action( 'after_setup_theme',  array($this,'migrate_custom_css' ) );
        
        // add postMessage support for select fields (NOT USED)
        // add_action( 'customize_register', array($this, 'add_postmessage_support') );    
        
        // register the color settings and contols
        add_action( 'customize_register', array(
             $this,
            'register_color_settings_and_controls' 
        ), 99 );
        
        // enque the main color scheme CSS
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'color_scheme_css' 
        ), 11 );
        
        // Add a listener to the Color Scheme control to update other color controls to new values/default
        add_action( 'customize_controls_enqueue_scripts', array(
             $this,
            'customize_control_js' 
        ) );
        
        // generates a CSS template for for instant display in the Customizer preview.
        add_action( 'customize_controls_print_footer_scripts', array(
             $this,
            'color_scheme_css_template' 
        ) );
        
        // Live-update changed settings in real time in the Customizer preview
        // TODO: This script has some non-color aspects that should be split out
        add_action( 'customize_preview_init', array(
             $this,
            'customize_preview_js' 
        ) );
        
        // Enque scripts allowing custom colors to over-ride the scheme defaults
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'secondary_text_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'footer_text_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'footer_link_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'footer_background_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'site_masthead_bg_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'page_background_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'link_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'heading_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'main_text_color_css' 
        ), 12 );
        
        add_action( 'wp_enqueue_scripts', array(
             $this,
            'custom_css' 
        ), 12 );
        
    }
    
    
    function migrate_custom_css() {
      if ( function_exists( 'wp_update_custom_css_post' ) ):
        $custom_css = get_theme_mod( 'tube_mod_custom_css' );
        if ( $custom_css ) :
          $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
          $return = wp_update_custom_css_post( $core_css . $custom_css );
          if ( ! is_wp_error( $return ) ) :
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            remove_theme_mod( 'tube_mod_custom_css' );
          endif;
        endif;
      endif;
    }
      
      
    /**
     * Adds postMessage support for site title and description for the Customizer.
     *
     * @since 1.0.0.0
     *
     * @param WP_Customize_Manager $wp_customize The Customizer object.
     */
    function add_postmessage_support( $wp_customize ) {
        
        $wp_customize->get_setting( 'blogname' )->transport        = 'refresh';
        $wp_customize->get_setting( 'blogdescription' )->transport = 'refresh';
        
    }
    
    
    /**
     * Registers all of the color settings and controls for the Customizer.
     *
     * @since 1.0.0.0
     *
     * @param WP_Customize_Manager $wp_customize The Customizer object.
     */
    
    function register_color_settings_and_controls( $wp_customize ) {
        
        
        $color_scheme = $this->get_color_scheme();
        
        
        //$wp_customize->get_setting( 'blogname' )->transport         = 'refresh';
        //$wp_customize->get_setting( 'blogdescription' )->transport  = 'refresh';
        
        
        
        
        
        // Add color scheme setting and control.
        $wp_customize->add_setting( 'tube_color_scheme', array(
             'default' => 'default',
            'sanitize_callback' => array(
                 $this,
                'sanitize_color_scheme' 
            ),
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( 'tube_color_scheme', array(
             'label' => _x( 'Color Scheme', 'Color schemes: color scheme selector label', 'tube' ),
            'section' => 'colors',
            'type' => 'select',
            'choices' => $this->get_color_scheme_choices(),
            'priority' => 1 
        ) );
        
        
        // Add site masthead background color setting and control.
        $wp_customize->add_setting( 'tube_site_masthead_bg_color', array(
             'default' => $color_scheme[7],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_site_masthead_bg_color', array(
             'label' => _x( 'Site Masthead Background Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add page background color setting and control.
        $wp_customize->add_setting( 'tube_page_background_color', array(
             'default' => $color_scheme[1],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_page_background_color', array(
             'label' => _x( 'Page Background Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        // Add heading color setting and control.
        $wp_customize->add_setting( 'tube_heading_color', array(
             'default' => $color_scheme[8],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_heading_color', array(
             'label' => _x( 'Heading Color (e.g. H2)', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add link color setting and control.
        $wp_customize->add_setting( 'tube_link_color', array(
             'default' => $color_scheme[2],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_link_color', array(
             'label' => _x( 'Link Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add main text color setting and control.
        $wp_customize->add_setting( 'tube_main_text_color', array(
             'default' => $color_scheme[3],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_main_text_color', array(
             'label' => _x( 'Main Text Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add secondary text color setting and control.
        $wp_customize->add_setting( 'tube_secondary_text_color', array(
             'default' => $color_scheme[4],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_secondary_text_color', array(
             'label' => _x( 'Secondary Text Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        
        // Add (footer) background color setting and control.
        $wp_customize->add_setting( 'tube_footer_background_color', array(
             'default' => $color_scheme[0],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_footer_background_color', array(
             'label' => _x( 'Footer Background Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add footer text color setting and control.
        $wp_customize->add_setting( 'tube_footer_text_color', array(
             'default' => $color_scheme[5],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_footer_text_color', array(
             'label' => _x( 'Footer Text Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        // Add footer text color setting and control.
        $wp_customize->add_setting( 'tube_footer_link_color', array(
             'default' => $color_scheme[5],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage' 
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tube_footer_link_color', array(
             'label' => _x( 'Footer Link Color', 'Color schemes: color option label', 'tube' ),
            'section' => 'colors' 
        ) ) );
        
        
        
        
        // Add setting for Custom CSS
        $wp_customize->add_setting( 'tube_mod_custom_css', array(
             'default' => NULL,
            'type' => 'theme_mod',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_text_field' 
        ) );
        
        
        // Add control for Custom CSS
        $wp_customize->add_control( 'tube_mod_custom_css', array(
        'type' => 'textarea',
             'label' => _x( 'Custom CSS', 'Custom CSS: textarea label', 'tube' ),
              'input_attrs' => array(
                'disabled' => 'disabled',
                'style' => 'height: 100px;',
              ),
            'description' => _x( 'Custom CSS has been migrated to the native Additional CSS area.', 'Custom CSS: help text', 'tube' ),
            'section' => 'colors',
        ) );
        
        
    }
    
    /**
     * Registers color schemes for .TUBE Theme.
     *
     * Can be filtered with {@see 'tube_color_schemes'}.
     *
     * The order of colors in a colors array:
     * 1. Main Background Color.
     * 2. Page Background Color.
     * 3. Link Color.
     * 4. Main Text Color.
     * 5. Secondary Text Color.
     *
     * @since 1.0.0.0
     *
     * @return array An associative array of color scheme options.
     */
    function get_color_schemes() {
        /**
         * Filter the color schemes registered for use with .TUBE Theme.
         *
         * The default schemes include 'default', 'dark', 'gray', 'red', and 'yellow'.
         *
         * @since 1.0.0.0
         *
         * @param array $schemes {
         *     Associative array of color schemes data.
         *
         *     @type array $slug {
         *         Associative array of information for setting up the color scheme.
         *
         *         @type string $label  Color scheme label.
         *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
         *                              Colors are defined in the following order: Main background, page
         *                              background, link, main text, secondary text.
         *     }
         * }
         */
        
        $color_schemes = array(
                'default' => array(
                'label' => _x( 'Default', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#393a40', // tube_footer_background_color (footer)
                    '#ffffff', // tube_page_background_color (site content)
                    '#007acc', // tube_link_color
                    '#393a40', // tube_main_text_color
                    '#8e8da0', // tube_secondary_text_color
                    '#e3e3e7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#acb6bf', // tube_site_masthead_bg_color
                    '#5f5c68' // tube_heading_color
                ) 
            ),
            'citrusblast' => array(
                'label' => _x( 'Citrus Blast', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#f9a603', // tube_footer_background_color (footer)
                    '#f7efe2', // tube_page_background_color (site content)
                    '#f70025', // tube_link_color
                    '#393a40', // tube_main_text_color
                    '#8e8da0', // tube_secondary_text_color
                    '#ecf1f7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#f25c00', // tube_site_masthead_bg_color
                    '#5f5c68' // tube_heading_color
                ) 
            ),
            'miamivice' => array(
                 'label' => _x( 'Miami Vice', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#00a3b4', // tube_footer_background_color (footer)
                    '#f7f7f7', // tube_page_background_color (site content)
                    '#00a3b4', // tube_link_color
                    '#393a40', // tube_main_text_color
                    '#8e8da0', // tube_secondary_text_color
                    '#d4dadd', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#e0078c', // tube_site_masthead_bg_color
                    '#e0078c' // tube_heading_color
                ) 
            ),
            'organichoney' => array(
                 'label' => _x( 'Organic Honey', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#e9d001', // tube_footer_background_color (footer)
                    '#f5f3e6', // tube_page_background_color (site content)
                    '#ba3c0b', // tube_link_color
                    '#3e3919', // tube_main_text_color
                    '#857466', // tube_secondary_text_color
                    '#3f331a', // tube_footer_text_color
                    '#ba3c0b', // tube_footer_link_color
                    '#e9d001', // tube_site_masthead_bg_color
                    '#67a031' // tube_heading_color
                ) 
            ),
            'eclecticeggplant' => array(
                 'label' => _x( 'Eclectic Eggplant', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#efa602', // tube_footer_background_color (footer)
                    '#ffffff', // tube_page_background_color (site content)
                    '#760d21', // tube_link_color
                    '#393a40', // tube_main_text_color
                    '#8e8da0', // tube_secondary_text_color
                    '#e6e6e6', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#432f54', // tube_site_masthead_bg_color
                    '#3d091f' // tube_heading_color
                ) 
            ),
            
            
            
            
            'murica' => array(
                 'label' => _x( '&rsquo;Murica', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#393a40', // tube_footer_background_color (footer)
                    '#f6f6f6', // tube_page_background_color (site content)
                    '#cc2b31', // tube_link_color
                    '#222222', // tube_main_text_color  
                    '#767676', // tube_secondary_text_color
                    '#ecf1f7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#0061aa', // tube_site_masthead_bg_color
                    '#0a3665' // tube_heading_color
                ) 
            ),
            'bumblebee' => array(
                 'label' => _x( 'Bumble Bee', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#393a40', // tube_footer_background_color (footer)
                    '#ffffff', // tube_page_background_color (site content)
                    '#e5bf2b', // tube_link_color
                    '#3d3d3d', // tube_main_text_color  
                    '#8e8da0', // tube_secondary_text_color
                    '#ecf1f7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#fed430', // tube_site_masthead_bg_color
                    '#5f5c68' // tube_heading_color
                ) 
            ),
            'freshair' => array(
                 'label' => _x( 'Fresh Air', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#d6ec27', // tube_footer_background_color (footer)
                    '#f7f7f4', // tube_page_background_color (site content)
                    '#2982d6', // tube_link_color
                    '#3f3f47', // tube_main_text_color  
                    '#817a91', // tube_secondary_text_color
                    '#00214c', // tube_footer_text_color
                    '#35abf4', // tube_footer_link_color
                    '#2982d6', // tube_site_masthead_bg_color
                    '#1d5b95' // tube_heading_color
                ) 
            ),
            'nolancyan' => array(
                 'label' => _x( 'Nolan Cyan', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#1093a7', // tube_footer_background_color (footer)
                    '#f1f9fa', // tube_page_background_color (site content)
                    '#1ad3f1', // tube_link_color
                    '#24454c', // tube_main_text_color  
                    '#589ba4', // tube_secondary_text_color
                    '#e3e3e7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#1093a7', // tube_site_masthead_bg_color
                    '#24454c' // tube_heading_color
                ) 
            ),
            'maizeandblue' => array(
                 'label' => _x( 'Maize &amp; Blue', 'Color schemes: scheme name', 'tube' ),
                'colors' => array(
                     '#005596', // tube_footer_background_color (footer)
                    '#ffffff', // tube_page_background_color (site content)
                    '#fdb913', // tube_link_color
                    '#444444', // tube_main_text_color  
                    '#8e8da0', // tube_secondary_text_color
                    '#e3e3e7', // tube_footer_text_color
                    '#ffffff', // tube_footer_link_color
                    '#fdb913', // tube_site_masthead_bg_color
                    '#005596' // tube_heading_color
                ) 
            ) 
        );
        
        
        return apply_filters( 'tube_color_schemes', $color_schemes );
    }
    
    /**
     * Retrieves the current color scheme.
     *
     * Create your own get_color_scheme() function to override in a child theme.
     *
     * @since 1.0.0.0
     *
     * @return array An associative array of either the current or default color scheme HEX values.
     */
    function get_color_scheme() {
        
        // get current scheme option
        $color_scheme_option = get_theme_mod( 'tube_color_scheme', 'default' );
        
        // get all of the schemes
        $color_schemes = $this->get_color_schemes();
        
        // make sure the selected scheme exists
        if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
            
            // return the colors from the selected scheme
            return $color_schemes[$color_scheme_option]['colors'];
            
        }
        
        // selected option not found so return default  
        return $color_schemes['default']['colors'];
    }
    
    
    
    /**
     * Retrieves an array of color scheme choices
     *
     * Create your own get_color_scheme_choices() function to override
     * in a child theme.
     *
     * @since 1.0.0.0
     *
     * @return array Array of color schemes.
     */
    function get_color_scheme_choices() {
        
        $color_schemes = $this->get_color_schemes();
        
        $color_scheme_control_options = array();
        
        foreach ( $color_schemes as $color_scheme => $value ) {
            
            $color_scheme_control_options[$color_scheme] = $value['label'];
            
        }
        
        return $color_scheme_control_options;
        
    }
    
    
    
    
    /**
     * Handles sanitization for .TUBE Theme color schemes.
     *
     * @since 1.0.0.0
     *
     * @param string $value Color scheme name value.
     * @return string Color scheme name.
     */
    function sanitize_color_scheme( $value ) {
        
        $color_schemes = $this->get_color_scheme_choices();
        
        if ( !array_key_exists( $value, $color_schemes ) ) {
            return 'default';
        }
        
        return $value;
    }
    
    
    
    /**
     * Enqueues front-end CSS for color scheme.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function color_scheme_css() {
        
        $color_scheme_option = get_theme_mod( 'tube_color_scheme', 'default' );
        
        
        // Don't do anything if the default color scheme is selected.
        if ( 'default' === $color_scheme_option ) {
            return;
        }
        
        $color_scheme = $this->get_color_scheme();
        
        
        // Convert main text hex color to rgba.
        $color_textcolor_rgb = $this->hex2rgb( $color_scheme[3] );
        
        // If the rgba values are empty return early.
        if ( empty( $color_textcolor_rgb ) ) {
            return;
        }
        
        // Convert main text hex color to rgba.
        $color_linkcolor_rgb = $this->hex2rgb( $color_scheme[2] );
        
        // If the rgba values are empty return early.
        if ( empty( $color_linkcolor_rgb ) ) {
            return;
        }
        
        // Convert main text hex color to rgba.
        $color_tube_page_background_color_rgb = $this->hex2rgb( $color_scheme[1] );
        
        // If the rgba values are empty return early.
        if ( empty( $color_tube_page_background_color_rgb ) ) {
            return;
        }
        
        // If we get this far, we have a custom color scheme.
        $colors = array(
          'tube_footer_background_color' => $color_scheme[0],
          'tube_page_background_color' => $color_scheme[1],
          'tube_link_color' => $color_scheme[2],
          'tube_main_text_color' => $color_scheme[3],
          'tube_secondary_text_color' => $color_scheme[4],
          'tube_footer_text_color' => $color_scheme[5],
          'tube_footer_link_color' => $color_scheme[6],
          'tube_site_masthead_bg_color' => $color_scheme[7],
          'tube_heading_color' => $color_scheme[8],
          'border_color_rgb' => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.2)', $color_textcolor_rgb ),
          'tube_link_color_rgb' => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.6)', $color_linkcolor_rgb ),
          'tube_page_background_color_rgb' => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.9)', $color_tube_page_background_color_rgb ) 
        );      
                
        $color_scheme_css = $this->get_color_scheme_css( $colors );

        wp_add_inline_style( 'tube', $color_scheme_css );
        
    }
    
    
    /**
     * Binds the JS listener to make Customizer color_scheme control.
     *
     * Passes color scheme data as colorScheme global.
     *
     * @since 1.0.0.0
     */
    function customize_control_js() {
        wp_enqueue_script( 'tube-color-scheme-control', get_template_directory_uri() . '/js/tube-color-scheme-control.js', array(
             'customize-controls',
            'iris',
            'underscore',
            'wp-util' 
        ), '1.0.0.0', true );
        wp_localize_script( 'tube-color-scheme-control', 'colorScheme', $this->get_color_schemes() );
    }
    
    /**
     * Binds JS handlers to make the Customizer preview reload changes asynchronously.
     *
     * @since 1.0.0.0
     */
    function customize_preview_js() {
        wp_enqueue_script( 'tube-customize-preview', get_template_directory_uri() . '/js/tube-customize-preview.js', array(
             'customize-preview' 
        ), '1.0.0.0', true );
    }
    
    
    /**
     * Outputs an Underscore template for generating CSS for the color scheme.
     *
     * The template generates the css dynamically for instant display in the
     * Customizer preview.
     *
     * @since 1.0.0.0
     */
    function color_scheme_css_template() {
        $colors = array(
             'tube_footer_background_color' => '{{ data.tube_footer_background_color }}',
            'tube_page_background_color' => '{{ data.tube_page_background_color }}',
            'tube_link_color' => '{{ data.tube_link_color }}',
            'tube_main_text_color' => '{{ data.tube_main_text_color }}',
            'tube_secondary_text_color' => '{{ data.tube_secondary_text_color }}',
            'tube_footer_text_color' => '{{ data.tube_footer_text_color }}',
            'tube_footer_link_color' => '{{ data.tube_footer_link_color }}',
            'tube_site_masthead_bg_color' => '{{ data.tube_site_masthead_bg_color }}',
            'tube_heading_color' => '{{ data.tube_heading_color }}',
            'border_color_rgb' => '{{ data.border_color_rgb }}',
            'tube_page_background_color_rgb' => '{{ data.tube_page_background_color_rgb }}' 
        );
        
?>
   <script type="text/html" id="tmpl-tube-color-scheme">
      <?php echo $this->get_color_scheme_css( $colors ); ?>
   </script>
    <?php
    }
    
    function get_site_masthead_bg_color_css( $color ) {
        
        
        $css_raw = '
    
      /* Site Masthead Background Color */
      .tube-site-navbar {
        background-color: %1$s;
      }
      
      .masthead-search-form input {
        border-color: %1$s;
      }
      
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the page background color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function site_masthead_bg_color_css() {
        
        $color_scheme = $this->get_color_scheme();
        
        $default_color = $color_scheme[7];
        
        $tube_site_masthead_bg_color = get_theme_mod( 'tube_site_masthead_bg_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_site_masthead_bg_color === $default_color ) {
            return;
        }
        
        $css = $this->get_site_masthead_bg_color_css( $tube_site_masthead_bg_color );
        
        wp_add_inline_style( 'tube', $css );
        
    }
    
    
    
    
    function get_page_background_color_css( $color, $rgb_color ) {
        
        $css_raw = '/* Custom Page Background Color */
      .page-masthead,
     .btn-default,
    .btn-default:hover,
    .content-block {
        background-color: %1$s;
      }
     
    .pagination .page-numbers.current, 
    .pagination>.active>a,
    .pagination>.active>a:focus,
    .pagination>.active>a:hover,
    .pagination>.active>span,
    .pagination>.active>span:focus,
    .pagination>.active>span:hover {
        color: %1$s;
    }
      
      

      mark222,
      ins222,    
      .menu-toggle222.toggled-on,
      .menu-toggle222.toggled-on:hover,
      .menu-toggle222.toggled-on:focus,
      .pagination222 .prev,
      .pagination222 .next,
      .pagination222 .prev:hover,
      .pagination222 .prev:focus,
      .pagination222 .next:hover,
      .pagination222 .next:focus,
      .pagination222 .nav-links:before,
      .pagination222 .nav-links:after,
      .widget_calendar222 tbody a,
      .widget_calendar222 tbody a:hover,
      .widget_calendar222 tbody a:focus,
      .page-links222 a,
      .page-links222 a:hover,
      .page-links222 a:focus {
        color: %1$s;
      }
  
      @media screen and (min-width: 56.875em) {
        .main-navigation ul ul li {
          background-color: %1$s;
        }
  
        .main-navigation ul ul:after {
          border-top-color: %1$s;
          border-bottom-color: %1$s;
        }
      }
      
      
      
    ';
        
        return sprintf( $css_raw, $color, $rgb_color );
        
    }
    
    /**
     * Enqueues front-end CSS for the page background color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function page_background_color_css() {
        $color_scheme               = $this->get_color_scheme();
        $default_color              = $color_scheme[1];
        $tube_page_background_color = get_theme_mod( 'tube_page_background_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_page_background_color === $default_color ) {
            return;
        }
        
        
        
        // Convert main text hex color to rgba.
        $tube_page_background_color_rgb = $this->hex2rgb( $tube_page_background_color );
        
        
        // If the rgba values are empty return early.
        if ( empty( $tube_page_background_color_rgb ) ) {
            return;
        }
        
        $scrim_color = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.95)', $tube_page_background_color_rgb );
        
        $css = $this->get_page_background_color_css( $tube_page_background_color, $scrim_color );
        
        wp_add_inline_style( 'tube', $css );
        
    }
    
    
    
    
    
    
    function get_link_color_css( $color, $shadow_color ) {
        
        
        $css_raw = '
    /* Custom Link Color */
.btn-link,
.btn-link:hover,
.btn-default,
.btn-default:hover,
.tube-search-form  button,
.pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover,
  .pagination>li>a,
.pagination>li>span,
    a, .text-primary, 
  a small, 
  a222 .small, 
  a.text-primary,
  .panel-primary>.panel-heading,
  .dropdown-menu>li>a,
  a.h1, a.h2, a.h3, a.h4, a.h5, a.h6 ,
a h1, a h2, a h3, a h4, a h5, a h6,
a .h1, a .h2, a .h3, a .h4, a .h5, a .h6,
      .menu-toggle:hover,
      .menu-toggle:focus,
      a:hover,
      a:focus,
      .main-navigation a:hover,
      .main-navigation a:focus,
      .dropdown-toggle:hover,
      .dropdown-toggle:focus,
      .social-navigation a:hover:before,
      .social-navigation a:focus:before,
      .post-navigation a:hover .post-title,
      .post-navigation a:focus .post-title,
      .tagcloud a:hover,
      .tagcloud a:focus,
      .site-branding .site-title a:hover,
      .site-branding .site-title a:focus,
      .entry-title a:hover,
      .entry-title a:focus,
      .entry-footer a:hover,
      .entry-footer a:focus,
      .comment-metadata a:hover,
      .comment-metadata a:focus,
      .required,
      .site-info a:hover,
      .site-info a:focus {
        color: %1$s;
      }
      
     .pagination .page-numbers.current, 
    .pagination>.active>a,
    .pagination>.active>a:focus,
    .pagination>.active>a:hover,
    .pagination>.active>span,
    .pagination>.active>span:focus,
    .pagination>.active>span:hover,
    .gb-primary,
      .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus, .btn-primary:active:focus, .tube-site-navbar .navbar-toggle, .tube-site-navbar .navbar-toggle:hover, .dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover,
      mark222,
      ins222,
      .pagination222 .prev:hover,
      .pagination222 .prev:focus,
      .pagination222 .next:hover,
      .pagination222 .next:focus,
      .widget_calendar tbody a,
      .page-links a:hover,
      .page-links a:focus {
        background-color: %1$s;
      }
      

    .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus, .btn-primary:active:focus, 
    .tube-site-navbar 
    .navbar-toggle, 
    .tube-site-navbar 
    .navbar-toggle:hover,
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="url"]:focus,
    input[type="password"]:focus,
    input[type="search"]:focus,
    textarea:focus,
    .form-control:focus,
    .tagcloud a:hover,
    .tagcloud a:focus {
        border-color: %1$s;
      }
      
      .form-control:focus {
          -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px %2$s;
          box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px %2$s;
      }
    
    
      @media screen and (min-width: 56.875em) {
        .main-navigation li:hover > a,
        .main-navigation li.focus > a {
          color: %1$s;
        }
      }
     
      
    ';
        
        
        return sprintf( $css_raw, $color, $shadow_color );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the link color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function link_color_css() {
        $color_scheme    = $this->get_color_scheme();
        $default_color   = $color_scheme[2];
        $tube_link_color = get_theme_mod( 'tube_link_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_link_color === $default_color ) {
            return;
        }
        
        
        // Convert main text hex color to rgba.
        $color_linkcolor_rgb = $this->hex2rgb( $tube_link_color );
        
        // If the rgba values are empty return early.
        if ( empty( $color_linkcolor_rgb ) ) {
            return;
        }
        
        // If we get this far, we have a custom color scheme.
        $shadow_color = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.6)', $color_linkcolor_rgb );
        
        $css = $this->get_link_color_css( $tube_link_color, $shadow_color );
        
        wp_add_inline_style( 'tube', $css );
    }
    
    
    
    
    
    function get_heading_color_css( $color ) {
        
        
        $css_raw = '
    
      /* Heading Color */
     .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6  {
        color: %1$s;
      }
      
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    /**
     * Enqueues front-end CSS for the main text color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function heading_color_css() {
        $color_scheme       = $this->get_color_scheme();
        $default_color      = $color_scheme[8];
        $tube_heading_color = get_theme_mod( 'tube_heading_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_heading_color === $default_color ) {
            return;
        }
        
        $css = $this->get_heading_color_css( $tube_heading_color );
        
        wp_add_inline_style( 'tube', $css );
    }
    
    
    
    
    function get_main_text_color_css( $color, $border_color_rgb ) {
        
        
        $css_raw = '
    
      /* Main Text Color */
      body,
      .page-masthead .post-title small,
      .text-default  {
              color: %1$s
      }
  
      /* Border Color */
     .btn-default,
     .btn-default:hover,
      .dropdown-menu,
      .list-group-item,
      .page-masthead,
      .content-block,
      blockquote,      
      ul.list-terms li,
      ul.list-divided li,
      ul.list-posts-grid .post-title-wrap,
      fieldset,
      pre,
      abbr,
      acronym,
      table,
      th,
      td,
      .embed-wrap, 
       .form-control,
       input[type="text"],
      input[type="email"],
      input[type="url"],
      input[type="password"],
      input[type="search"],
      textarea,
      .main-navigation li,
      .main-navigation .primary-menu,
      .menu-toggle,
      .dropdown-toggle:after,
      .social-navigation a,
      .image-navigation,
      .comment-navigation,
      .tagcloud a,
      .entry-content,
      .entry-summary,
      .page-links a,
      .page-links > span,
      .comment,
      .pingback,
      .trackback,
      .no-comments,
      .comment.bypostauthor .col-comment-meta-and-text,
      .tagcloud a,
      .widecolumn .mu_register .mu_alert {
        border-color: %1$s; /* Fallback for IE7 and IE8 */
        border-color: %2$s;
      }
  
      code {
        background-color: %1$s; /* Fallback for IE7 and IE8 */
        background-color: %2$s;
      }
      
     
    ';
        
        return sprintf( $css_raw, $color, $border_color_rgb );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the main text color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function main_text_color_css() {
        $color_scheme         = $this->get_color_scheme();
        $default_color        = $color_scheme[3];
        $tube_main_text_color = get_theme_mod( 'tube_main_text_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_main_text_color === $default_color ) {
            return;
        }
        
        // Convert main text hex color to rgba.
        $tube_main_text_color_rgb = $this->hex2rgb( $tube_main_text_color );
        
        // If the rgba values are empty return early.
        if ( empty( $tube_main_text_color_rgb ) ) {
            return;
        }
        
        // If we get this far, we have a custom color scheme.
        $border_color_rgb = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.2)', $tube_main_text_color_rgb );
        
        $css = $this->get_main_text_color_css( $tube_main_text_color, $border_color_rgb );
        
        wp_add_inline_style( 'tube', $css );
        
    }
    
    
    
    
    function get_secondary_text_color_css( $color ) {
        
        
        $css_raw = '
    /* Custom Secondary Text Color */
  
      /**
       * IE8 and earlier will drop any block with CSS3 selectors.
       * Do not combine these styles with the next block.
       */
      body:not(.search-results) .entry-summary {
        color: %1$s;
      }
      
      .help-block,
      .text-muted,
  .list-inline-delimited > li:before,
  .h1 .small, .h1 small, .h2 .small, .h2 small, .h3 .small, .h3 small, .h4 .small, .h4 small, .h5 .small, .h5 small, .h6 .small, .h6 small, h1 .small, h1 small, h2 .small, h2 small, h3 .small, h3 small, h4 .small, h4 small, h5 .small, h5 small, h6 .small, h6 small,  
    .text-muted,
    h6.sense-heading,
      blockquote,
      .post-password-form label,
      .post-navigation .meta-nav,
      .image-navigation,
      .comment-navigation,
      .widget_recent_entries .post-date,
      .widget_rss .rss-date,
      .widget_rss cite,
      .site-description,
      .author-bio,
      .entry-footer,
      .entry-footer a,
      .sticky-post,
      .taxonomy-description,
      .entry-caption,
      .comment-metadata,
      .pingback .edit-link,
      .comment-metadata a,
      .comment-form label,
      .comment-notes,
      .comment-awaiting-moderation,
      .logged-in-as,
      .form-allowed-tags,
      .site-info,
      .site-info a,
      .wp-caption .wp-caption-text,
      .gallery-caption,
      .widecolumn label,
      .widecolumn .mu_register label {
        color: %1$s;
      }
  
      .widget_calendar tbody a:hover,
      .widget_calendar tbody a:focus {
        background-color: %1$s;
      }
      
      blockquote {
        border-color: %1$s;
      }
      
      
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    /**
     * Enqueues front-end CSS for the secondary text color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function secondary_text_color_css() {
        $color_scheme              = $this->get_color_scheme();
        $default_color             = $color_scheme[4];
        $tube_secondary_text_color = get_theme_mod( 'tube_secondary_text_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_secondary_text_color === $default_color ) {
            return;
        }
        
        $css = $this->get_secondary_text_color_css( $tube_secondary_text_color );
        
        wp_add_inline_style( 'tube', $css );
        
    }
    
    
    
    
    
    function get_footer_text_color_css( $color ) {
        
        
        $css_raw = '
     /* Custom Footer Text Color */
  
    #site-footer p,
    #site-footer .list-inline-delimited li:before {
      color: %1$s;
    }
  
  
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the footer text color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function footer_text_color_css() {
        $color_scheme           = $this->get_color_scheme();
        $default_color          = $color_scheme[5];
        $tube_footer_text_color = get_theme_mod( 'tube_footer_text_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_footer_text_color === $default_color ) {
            return;
        }
        
        $css = $this->get_footer_text_color_css( $tube_footer_text_color );
        
        wp_add_inline_style( 'tube', $css );
    }
    
    
    
    
    
    function get_footer_link_color_css( $color ) {
        
        
        $css_raw = ' 
    /* Custom Footer Link Color */
  
      #site-footer a,
      #site-footer a.btn-social,
      #site-footer a.btn-social:hover {
        color: %1$s;
      }
  
        #site-footer .btn-default,
        #site-footer .btn-default:hover {
        background-color: %1$s;
      }
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the footer link color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function footer_link_color_css() {
        $color_scheme           = $this->get_color_scheme();
        $default_color          = $color_scheme[6];
        $tube_footer_link_color = get_theme_mod( 'tube_footer_link_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $tube_footer_link_color === $default_color ) {
            return;
        }
        
        $css = $this->get_footer_link_color_css( $tube_footer_link_color );
        
        wp_add_inline_style( 'tube', $css );
    }
    
    
    
    
    
    
    function get_footer_background_color_css( $color ) {
        
        
        $css_raw = ' 
    /* Custom Footer Background Color */
    
      body {
        background-color: %1$s;
    }
      
      #site-footer .btn-default  {
        color: %1$s;
      }
  
    ';
        
        return sprintf( $css_raw, $color );
        
    }
    
    
    /**
     * Enqueues front-end CSS for the footer link color.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
    function footer_background_color_css() {
        $color_scheme            = $this->get_color_scheme();
        $default_color           = $color_scheme[0];
        $footer_background_color = get_theme_mod( 'tube_footer_background_color', $default_color );
        
        // Don't do anything if the current color is the default.
        if ( $footer_background_color === $default_color ) {
            return;
        }
        
        $css = $this->get_footer_background_color_css( $footer_background_color );

        wp_add_inline_style( 'tube', $css );
    }
    
    
    
    
    
    /**
     * Enqueues front-end CSS for any Custom CSS.
     *
     * @since 1.0.0.0
     *
     * @see wp_add_inline_style()
     */
     
     // TODO: Deprecate this function, handled by core Additional CSS
    function custom_css() {
        
        $custom_css = get_theme_mod( 'tube_mod_custom_css', NULL );
        
        // Don't do anything if no custom CSS
        if ( !$custom_css ) {
            return;
        }
        
        wp_add_inline_style( 'tube', $custom_css );
        
    }
    
    
    
    
    /**
     * Converts a HEX value to RGB.
     *
     * @since 1.0.0.0
     *
     * @param string $color The original color, in 3- or 6-digit hexadecimal form.
     * @return array Array containing RGB (red, green, and blue) values for the given
     *               HEX code, empty array otherwise.
     */
    function hex2rgb( $color ) {
        $color = trim( $color, '#' );
        
        if ( strlen( $color ) === 3 ) {
            $r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
            $g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
            $b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
        } else if ( strlen( $color ) === 6 ) {
            $r = hexdec( substr( $color, 0, 2 ) );
            $g = hexdec( substr( $color, 2, 2 ) );
            $b = hexdec( substr( $color, 4, 2 ) );
        } else {
            return array();
        }
        
        return array(
             'red' => $r,
            'green' => $g,
            'blue' => $b 
        );
    }
    
    
    
    
    
    /**
     * Returns CSS for the color schemes.
     *
     * @since 1.0.0.0
     *
     * @param array $colors Color scheme colors.
     * @return string Color scheme CSS.
     */
    function get_color_scheme_css( $colors ) {
        
        
        $colors = wp_parse_args( $colors, array(
             'tube_footer_background_color' => '',
            'tube_page_background_color' => '',
            'tube_link_color' => '',
            'tube_main_text_color' => '',
            'tube_secondary_text_color' => '',
            'tube_footer_text_color' => '',
            'tube_footer_link_color' => '',
            'tube_site_masthead_bg_color' => '',
            'tube_heading_color' => '',
            'border_color_rgb' => '',
            'tube_link_color_rgb' => '',
            'tube_page_background_color_rgb' => '' 
        ) );
        
        
        $css['tube_footer_background_color'] = $this->get_footer_background_color_css( $colors['tube_footer_background_color'] );
        $css['tube_page_background_color']   = $this->get_page_background_color_css( $colors['tube_page_background_color'], $colors['tube_page_background_color_rgb'] );
        $css['tube_link_color']              = $this->get_link_color_css( $colors['tube_link_color'], $colors['tube_link_color_rgb'] );
        $css['tube_main_text_color']         = $this->get_main_text_color_css( $colors['tube_main_text_color'], $colors['border_color_rgb'] );
        $css['tube_secondary_text_color']    = $this->get_secondary_text_color_css( $colors['tube_secondary_text_color'] );
        $css['tube_footer_text_color']       = $this->get_footer_text_color_css( $colors['tube_footer_text_color'] );
        $css['tube_footer_link_color']       = $this->get_footer_link_color_css( $colors['tube_footer_link_color'] );
        $css['tube_site_masthead_bg_color']  = $this->get_site_masthead_bg_color_css( $colors['tube_site_masthead_bg_color'] );
        $css['tube_heading_color']           = $this->get_heading_color_css( $colors['tube_heading_color'] );
        //$css['border_color_rgb'] = $this -> get_border_color_rgb_css( $colors['border_color_rgb'] );
        //$css['tube_link_color_rgb'] = $this -> get_link_color_rgb_css( $colors['tube_link_color_rgb'] );
        
        $css_output = implode( '', $css );
        
        
return <<<CSS
/* Color Scheme */
{$css_output}
CSS;
}
    
}