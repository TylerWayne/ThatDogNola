<?php
/*----------------------------------------------------------------------------*\
	GRID PRODUCTS SHORTCODE
\*----------------------------------------------------------------------------*/

if ( ! class_exists( 'MPC_WC_Grid_Products' ) ) {
	class MPC_WC_Grid_Products {
		public $shortcode  = 'mpc_wc_grid_products';
		public $parts = array();

		function __construct() {
			add_shortcode( $this->shortcode, array( $this, 'shortcode_template' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( $this->shortcode, array( $this, 'shortcode_map' ) );
			} else {
				add_action( 'init', array( $this, 'shortcode_map_fallback' ) );
			}

			/* Autocomplete */
			add_filter( 'vc_autocomplete_' . $this->shortcode . '_ids_callback', 'MPC_Autocompleter::suggest_wc_product', 10, 1 );
			add_filter( 'vc_autocomplete_' . $this->shortcode . '_ids_render', 'MPC_Autocompleter::render_wc_product', 10, 1 );
			add_filter( 'vc_autocomplete_' . $this->shortcode . '_taxonomies_callback', 'MPC_Autocompleter::suggest_wc_category', 10, 1 );
			add_filter( 'vc_autocomplete_' . $this->shortcode . '_taxonomies_render', 'MPC_Autocompleter::render_wc_category', 10, 1 );

			$this->parts = array(
				'flex_begin'    => '<div class="mpc-flex">',
				'inline_begin'  => '<div class="mpc-inline-box">',
				'block_begin'   => '<div class="mpc-block-box">',
				'buttons_begin' => '<div class="mpc-thumb__buttons mpc-transition">',
				'thumb_begin'   => '<div class="mpc-thumb__content-wrap">',

				'add_to_cart'   => '',
				'title'         => '',
				'thumbnail'     => '',
				'rating'        => '',
				'buttons'       => '',

				'section_end'   => '</div>',
			);
		}

		function shortcode_map_fallback() {
			vc_map( $this->shortcode_map() );
		}

		/* Enqueue all styles/scripts required by shortcode */
		function enqueue_shortcode_scripts() {
			wp_enqueue_style( $this->shortcode . '-css', MPC_MASSIVE_URL . '/shortcodes/' . $this->shortcode . '/css/' . $this->shortcode .  '.css', array(), MPC_MASSIVE_VERSION );
			wp_enqueue_script( $this->shortcode . '-js', MPC_MASSIVE_URL . '/shortcodes/' . $this->shortcode . '/js/' . $this->shortcode .  MPC_MASSIVE_MIN . '.js', array( 'jquery' ), MPC_MASSIVE_VERSION );
		}

		/* Build query */
		function build_query( $atts ) {
			$args = array(
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'paged' => (int) $atts[ 'paged' ],
				'post_type' => 'product',
				'orderby' => $atts[ 'orderby' ],
				'order' => $atts[ 'order' ],
				'posts_per_page' => -1,
			);

			if( $atts[ 'source_type' ] != 'ids' ) {
				$args[ 'posts_per_page' ] = (int) $atts[ 'items_number' ];

				if( $atts[ 'taxonomies' ] != '' ) {
					$tax_types = get_taxonomies( array( 'public' => true ) );

					$terms = get_terms( array_keys( $tax_types ), array(
						'hide_empty' => false,
						'include' => $atts[ 'taxonomies' ],
					) );

					$args[ 'tax_query' ] = array();

					$tax_queries = array();
					foreach ( $terms as $t ) {
						if ( ! isset( $tax_queries[ $t->taxonomy ] ) ) {
							$tax_queries[ $t->taxonomy ] = array(
								'taxonomy' => $t->taxonomy,
								'field' => 'id',
								'terms' => array( $t->term_id ),
								'relation' => 'IN'
							);
						} else {
							$tax_queries[ $t->taxonomy ]['terms'][] = $t->term_id;
						}
					}

					$args['tax_query'] = array_values( $tax_queries );
					$args['tax_query']['relation'] = 'OR';
				}
			}

			if( $atts[ 'source_type' ] == 'ids' ) {
				if( $atts[ 'ids' ] != '' ) {
					$args[ 'post__in' ] = explode( ', ', $atts[ 'ids' ] );
				}
			}

			return $args;
		}

		/* Get Posts */
		function get_posts( $atts ) {
			$args = $this->build_query( $atts );

			$query = new WP_Query( $args );

			return $query;
		}

		/* Return Pagination content */
		public function get_paginated_content( $data, $atts ) {
			$data_query = $this->get_posts( $data );
			if( !$data_query->have_posts() ) return '';

			global $MPC_WC_Product;

			$MPC_WC_Product->query = $data_query;

			/* Prepare */
			$content = '<div class="mpc-pagination--settings" data-current="' . esc_attr( $data_query->query[ 'paged' ] ) . '" data-pages="' . esc_attr( $data_query->max_num_pages ) . '"></div>';
			$content .= $MPC_WC_Product->pagination_content( $atts );
			wp_reset_postdata();

			return $content;
		}

		/* Return shortcode markup for display */
		function shortcode_template( $atts, $content = null ) {
			if( !class_exists( 'WooCommerce' ) ) {
				return '';
			}

			/* Enqueues */
			wp_enqueue_script( 'mpc-massive-isotope-js', MPC_MASSIVE_URL . '/assets/js/libs/isotope.min.js', array( 'jquery' ), '', true );

			global $MPC_Pagination, $MPC_WC_Product, $MPC_Shortcode, $mpc_ma_options;
			if ( $mpc_ma_options[ 'single_js_css' ] !== '1' ) {
				$this->enqueue_shortcode_scripts();
			}

			$grid_atts = shortcode_atts( array(
				'class'                     => '',
				'preset'                    => '',
				'cols'                      => '2',
				'gap'                       => '0',

				'source_type'               => 'taxonomies',
				'taxonomies'                => '',
				'ids'                       => '',
				'order'                     => 'ASC',
				'orderby'                   => 'date',
				'items_number'              => '6',
				'paged'                     => '1',

				'mpc_pagination__preset'    => '',
			), $atts );

			/* Build Query */
			$data_query = $this->get_posts( $grid_atts );
			if( !$data_query->have_posts() ) return '';

			/* Prepare */
			$css_id    = $this->shortcode_styles( $grid_atts );
			$animation = MPC_Parser::animation( $grid_atts );

			/* Query Atts for Pagination */
			$query_atts = array(
				'source_type'        => $grid_atts[ 'source_type' ],
				'taxonomies'         => $grid_atts[ 'taxonomies' ],
				'ids'                => $grid_atts[ 'ids' ],
				'order'              => $grid_atts[ 'order' ],
				'orderby'            => $grid_atts[ 'orderby' ],
				'items_number'       => $grid_atts[ 'items_number' ],
				'target'             => $css_id,
				'callback'           => 'MPC_WC_Grid_Products',
			);
			$sh_atts = $grid_atts[ 'cols' ] != '' ? ' data-grid-cols="' . (int) esc_attr( $grid_atts[ 'cols' ] ) . '"' : '';
			$sh_atts .= $animation;

			/* Get Posts */
			$css_settings = array(
				'id' => $css_id,
				'selector' => '.mpc-wc-grid-products[id="' . $css_id . '"] .mpc-wc-product'
			);
			$MPC_Shortcode[ 'pagination' ] = $data_query;
			$MPC_WC_Product->default_parts();
			$MPC_WC_Product->query = &$data_query;

			/* Generate markup & template */
			$content = $MPC_WC_Product->shortcode_template( $atts, null, null, $css_settings );

			/* Shortcode classes | Animation | Layout */
			$classes = ' mpc-init';
			$classes .= $animation != '' ? ' mpc-animation' : '';
			$classes .= $MPC_WC_Product->classes;
			$classes .= ' ' . esc_attr( $grid_atts[ 'class' ] );

			$sh_atts .= $MPC_WC_Product->attributes;

			/* Shortcode Output */
			$return = '<div id="' . $css_id . '" class="mpc-wc-grid-products' . $classes . '" ' . $sh_atts . '>';
				$return .= $content;
			$return .= '</div>';
			$return .= '<script type="text/javascript">var _' . $css_id . '_atts = ' . json_encode( $atts ) . ', _' . $css_id . '_query = ' . json_encode( $query_atts ) . '</script>';

			/* Prepare Pagination */
			$return .= $MPC_Pagination->shortcode_template( $grid_atts[ 'mpc_pagination__preset' ], null, null, $css_id );

			/* Restore original Post Data */
			wp_reset_postdata();
			$MPC_WC_Product->default_parts();

			return $return;
		}

		/* Generate shortcode styles */
		function shortcode_styles( $styles ) {
			global $mpc_massive_styles;
			$css_id = uniqid( $this->shortcode . '_' . rand( 1, 100 ) );
			$style  = '';
			$styles[ 'gap' ] = $styles[ 'gap' ] != '' ? $styles[ 'gap' ] . ( is_numeric( $styles[ 'gap' ] ) ? 'px' : '' ) : '';

			// Gap
			if( $styles[ 'gap' ] != '' ) {
				$style .= '.mpc-wc-grid-products[id="' . $css_id . '"] {';
					$style .= 'margin-left: -' . $styles[ 'gap' ] . ';';
					$style .= 'margin-bottom: -' . $styles[ 'gap' ] . ';';
				$style .= '}';

				$style .= '.mpc-wc-grid-products[id="' . $css_id . '"] .mpc-product__wrapper {';
					$style .= 'margin-left: ' . $styles[ 'gap' ] . ';';
					$style .= 'margin-bottom: ' . $styles[ 'gap' ] . ';';
				$style .= '}';
			}

			$mpc_massive_styles .= $style;

			return $css_id;
		}

		/* Map all shortcode options to Visual Composer popup */
		function shortcode_map() {
			if ( ! function_exists( 'vc_map' ) ) {
				return '';
			}


			$base = array(
				array(
					'type'        => 'mpc_preset',
					'heading'     => __( 'Main Preset', 'mpc' ),
					'param_name'  => 'preset',
					'tooltip'     => MPC_Helper::style_presets_desc(),
					'value'       => '',
					'shortcode'   => $this->shortcode,
					'wide_modal'  => true,
					'description' => __( 'Choose preset or create new one.', 'mpc' ),
				),
			);

			$base_ext = array(
				array(
					'type'             => 'mpc_slider',
					'heading'          => __( 'Gap', 'mpc' ),
					'param_name'       => 'gap',
					'tooltip'          => __( 'Choose gap between grid items.', 'mpc' ),
					'min'              => 0,
					'max'              => 100,
					'step'             => 1,
					'value'            => 0,
					'unit'             => 'px',
					'edit_field_class' => 'vc_col-sm-12 vc_column mpc-advanced-field',
				),
			);

			$source = array(
				array(
					'type'       => 'mpc_divider',
					'title'      => __( 'Source', 'mpc' ),
					'param_name' => 'source_section_divider',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => __( 'Data source', 'mpc' ),
					'param_name'       => 'source_type',
					'tooltip'          => __( 'Select source type for carousel.', 'mpc' ),
					'value'            => array(
						__( 'Products Ids', 'mpc' ) => 'ids',
						__( 'Products Category', 'mpc' ) => 'taxonomies',
					),
					'std'              => 'taxonomies',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'autocomplete',
					'heading'          => __( 'Posts', 'mpc' ),
					'param_name'       => 'ids',
					'tooltip'          => __( 'Define list of posts displayed by this grid.', 'mpc' ),
					'settings'         => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true,
					),
					'std'              => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency'       => array( 'element' => 'source_type', 'value' => array( 'ids' ), ),
				),
				array(
					'type'               => 'autocomplete',
					'heading'            => __( 'Taxonomies or Tags', 'mpc' ),
					'param_name'         => 'taxonomies',
					'tooltip'            => __( 'Define posts tags, categories or custom taxonomies. It will filter the posts to display only the ones with specified tag/category.', 'mpc' ),
					'settings'           => array(
						'multiple'       => true,
						'min_length'     => 1,
						'groups'         => true,
						'unique_values'  => true,
						'display_inline' => true,
						'delay'          => 500,
						'auto_focus'     => true,
					),
					'std'                => '',
					'param_holder_class' => 'vc_not-for-custom',
					'edit_field_class'   => 'vc_col-sm-6 vc_column',
					'dependency'         => array( 'element' => 'source_type', 'value_not_equal_to' => array( 'ids' ), ),
				),
				array(
					'type'               => 'dropdown',
					'heading'            => __( 'Sort by', 'mpc' ),
					'param_name'         => 'orderby',
					'tooltip'            => __( 'Select posts sorting parameter.', 'mpc' ),
					'value'              => array(
						__( 'Date', 'mpc' )               => 'date',
						__( 'Order by post ID', 'mpc' )   => 'ID',
						__( 'Author', 'mpc' )             => 'author',
						__( 'Title', 'mpc' )              => 'title',
						__( 'Last modified date', 'mpc' ) => 'modified',
						__( 'Number of comments', 'mpc' ) => 'comment_count',
						__( 'Random order', 'mpc' )       => 'rand',
					),
					'std'                => 'date',
					'edit_field_class'   => 'vc_col-sm-4 vc_column mpc-advanced-field',
					'dependency'         => array(
						'element'            => 'source_type',
						'value_not_equal_to' => array( 'ids' ),
					),
				),
				array(
					'type'               => 'dropdown',
					'heading'            => __( 'Order', 'mpc' ),
					'param_name'         => 'order',
					'tooltip'            => __( 'Select posts sorting order.', 'mpc' ),
					'value'              => array(
						__( 'Descending', 'mpc' ) => 'DESC',
						__( 'Ascending', 'mpc' )  => 'ASC',
					),
					'std'                => 'ASC',
					'edit_field_class'   => 'vc_col-sm-4 vc_column mpc-advanced-field',
					'dependency'         => array(
						'element'            => 'source_type',
						'value_not_equal_to' => array( 'ids' ),
					),
				),
				array(
					'type'             => 'mpc_text',
					'heading'          => __( 'Items Per Page', 'mpc' ),
					'param_name'       => 'items_number',
					'tooltip'          => __( 'Define maximum number of displayed posts per page. If the number of posts meeting the above parameters is smaller it will only show those posts.', 'mpc' ),
					'value'            => '6',
					'addon'            => array(
						'icon'  => 'dashicons dashicons-slides',
						'align' => 'prepend',
					),
					'edit_field_class' => 'vc_col-sm-4 vc_column mpc-advanced-field',
					'label'            => '',
					'validate'         => true,
				)
			);

			/* General */
			$rows_cols = MPC_Snippets::vc_rows_cols( array( 'cols' => array( 'min' => 1, 'max' => 4, 'default' => 2 ), 'rows' => false ) );
			$animation = MPC_Snippets::vc_animation_basic();
			$class     = MPC_Snippets::vc_class();

			/* Pagination */
			$integrate_pagination = vc_map_integrate_shortcode( 'mpc_pagination', 'mpc_pagination__', __( 'Pagination', 'mpc' ) );

			/* Integrate Item */
			$item_exclude   = array( 'exclude_regex' => '/animation_in(.*)|source_section_divider|^id$/', );
			$integrate_item = vc_map_integrate_shortcode( 'mpc_wc_product', '', '', $item_exclude );

			$params = array_merge(
				$base,
				$rows_cols,
				$base_ext,
				$source,

				$integrate_item,

				$integrate_pagination,
				$animation,
				$class
			);

			if( !class_exists( 'WooCommerce' ) ) {
				$params = array(
					array(
						'type'             => 'custom_markup',
						'param_name'       => 'woocommerce_notice',
						'value'            => '<p class="mpc-warning mpc-active"><i class="dashicons dashicons-warning"></i>' . __( 'Please install and activate <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> plugin in order to use this shortcode! :)', 'mpc' ) . '</p>',
						'edit_field_class' => 'vc_col-sm-12 vc_column mpc-woocommerce-field',
					),
				);
			}

			return array(
				'name'        => __( 'Grid Products', 'mpc' ),
				'description' => __( 'Grid with products', 'mpc' ),
				'base'        => $this->shortcode,
				'icon'        => 'mpc-shicon-wc-grid-products',
				'category'    => __( 'MPC', 'mpc' ),
				'params'      => $params,
			);
		}
	}
}

if ( class_exists( 'MPC_WC_Grid_Products' ) ) {
	global $MPC_WC_Grid_Products;
	$MPC_WC_Grid_Products = new MPC_WC_Grid_Products;
}

if ( class_exists( 'MPCShortCode_Base' ) ) {
	class WPBakeryShortCode_mpc_wc_grid_products extends MPCShortCode_Base {}
}
