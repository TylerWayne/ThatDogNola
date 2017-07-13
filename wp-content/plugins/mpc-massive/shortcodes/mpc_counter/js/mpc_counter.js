/*----------------------------------------------------------------------------*\
	COUNTER SHORTCODE
\*----------------------------------------------------------------------------*/
( function( $ ) {
	"use strict";

	function fast_init( $this ) {
		$this.text( $this.attr( 'data-to' ) );
	}

	function delay_init( $this ) {
		if ( typeof CountUp !== 'undefined' ) {
			var _options = $this.data( 'options' ),
				_counter = new CountUp( $this[0], _options.initial, _options.value, _options.decimals, _options.duration, _options );
			console.log( _options );
			_counter.start();
		} else {
			setTimeout( function() {
				delay_init( $this );
			}, 50 );
		}
	}

	function init_shortcode( $counter ) {
		$counter.trigger( 'mpc.inited' );
	}

	if ( typeof window.InlineShortcodeView != 'undefined' ) {
		window.InlineShortcodeView_mpc_counter = window.InlineShortcodeView.extend( {
			rendered: function() {
				var $counter = this.$el.find( '.mpc-counter' );

				$counter.addClass( 'mpc-waypoint--init' );

				_mpc_vars.$body.trigger( 'mpc.icon-loaded', [ $counter ] );
				_mpc_vars.$body.trigger( 'mpc.font-loaded', [ $counter ] );
				_mpc_vars.$body.trigger( 'mpc.inited', [ $counter ] );

				delay_init( $counter.find( '.mpc-counter--target' ) );

				window.InlineShortcodeView_mpc_counter.__super__.rendered.call( this );
			}
		} );
	}

	var $counters = $( '.mpc-counter' );

	$counters.each( function() {
		var $counter = $( this ),
		    $parent = $counter.parents( '.mpc-container' );

		if( $parent.length ) {
			$parent.one( 'mpc.parent-init', function() {
				delay_init( $counter.find( '.mpc-counter--target' ) );
			} );
		} else if ( $counter.is( '.mpc-waypoint--init' ) ) {
			delay_init( $counter.find( '.mpc-counter--target' ) );
		} else {
			$counter.one( 'mpc.waypoint', function() {
				if( !$counter.is( '.mpc-init--fast' ) ) {
					delay_init( $counter.find( '.mpc-counter--target' ) );
				}
			});
		}

		$counter.one( 'mpc.init', function () {
			if( $counter.is( '.mpc-init--fast' ) ) {
				fast_init( $counter.find( '.mpc-counter--target' ) );
			}

			init_shortcode( $counter );
		} );

		$counter.one( 'mpc.init-fast', function() {
			fast_init( $counter.find( '.mpc-counter--target' ) );
		} );
	} );
} )( jQuery );
