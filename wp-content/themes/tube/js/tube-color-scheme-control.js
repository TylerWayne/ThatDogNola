/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
	var cssTemplate = wp.template( 'tube-color-scheme' ),
		colorSchemeKeys = [
			'tube_footer_background_color',
			'tube_page_background_color',
			'tube_link_color',
			'tube_main_text_color',
			'tube_secondary_text_color',
      'tube_footer_text_color',
      'tube_footer_link_color',
      'tube_site_masthead_bg_color',
      'tube_heading_color'
		],
		colorSettings = [
			'tube_footer_background_color',
			'tube_page_background_color',
			'tube_link_color',
			'tube_main_text_color',
			'tube_secondary_text_color',
      'tube_footer_text_color',
      'tube_footer_link_color',
      'tube_site_masthead_bg_color',
      'tube_heading_color'
		];

	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			if ( 'tube_color_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {
					var colors = colorScheme[value].colors;

					// Update Background Color.
					var color = colors[0];
					api( 'tube_footer_background_color' ).set( color );
					api.control( 'tube_footer_background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Page Background Color.
					color = colors[1];
					api( 'tube_page_background_color' ).set( color );
					api.control( 'tube_page_background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Link Color.
					color = colors[2];
					api( 'tube_link_color' ).set( color );
					api.control( 'tube_link_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Main Text Color.
					color = colors[3];
					api( 'tube_main_text_color' ).set( color );
					api.control( 'tube_main_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Secondary Text Color.
					color = colors[4];
					api( 'tube_secondary_text_color' ).set( color );
					api.control( 'tube_secondary_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

          // Update Footer Text Color.
          color = colors[5];
          api( 'tube_footer_text_color' ).set( color );
          api.control( 'tube_footer_text_color' ).container.find( '.color-picker-hex' )
            .data( 'data-default-color', color )
            .wpColorPicker( 'defaultColor', color );

          // Update Footer Link Color.
          color = colors[6];
          api( 'tube_footer_link_color' ).set( color );
          api.control( 'tube_footer_link_color' ).container.find( '.color-picker-hex' )
            .data( 'data-default-color', color )
            .wpColorPicker( 'defaultColor', color );

          // Update Site Masthead Background Color.
          color = colors[7];
          api( 'tube_site_masthead_bg_color' ).set( color );
          api.control( 'tube_site_masthead_bg_color' ).container.find( '.color-picker-hex' )
            .data( 'data-default-color', color )
            .wpColorPicker( 'defaultColor', color );

          // Update Heading Color.
          color = colors[8];
          api( 'tube_heading_color' ).set( color );
          api.control( 'tube_heading_color' ).container.find( '.color-picker-hex' )
            .data( 'data-default-color', color )
            .wpColorPicker( 'defaultColor', color );
            
            
				} );
			}
		}
	} );

	// Generate the CSS for the current Color Scheme.
	function updateCSS() {
		var scheme = api( 'tube_color_scheme' )(),
			css,
			colors = _.object( colorSchemeKeys, colorScheme[ scheme ].colors );

		// Merge in color scheme overrides.
		_.each( colorSettings, function( setting ) {
			colors[ setting ] = api( setting )();
		} );

		// Add additional color.
		// jscs:disable
		colors.border_color_rgb = Color( colors.tube_main_text_color ).toCSS( 'rgba', 0.2 );
    colors.tube_link_color_rgb = Color( colors.tube_link_color ).toCSS( 'rgba', 0.6 );
    colors.tube_page_background_color_rgb = Color( colors.tube_page_background_color ).toCSS( 'rgba', 0.9 );
		// jscs:enable

		css = cssTemplate( colors );

    //console.log(css);
    
		api.previewer.send( 'update-color-scheme-css', css );
	}

	// Update the CSS whenever a color setting is changed.
	_.each( colorSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( updateCSS );
		} );
	} );
} )( wp.customize );
