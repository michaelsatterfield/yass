function olympus_colors_live_update( id, selector, property, default_value, get_value ) {
	default_value = typeof default_value !== 'undefined' ? default_value : 'initial';
	get_value = typeof get_value !== 'undefined' ? get_value : '';

	wp.customize( 'olympus_settings[' + id + ']', function( value ) {
		value.bind( function( newval ) {
			default_value = ( '' !== get_value ) ? wp.customize.value('olympus_settings[' + get_value + ']')() : default_value;
			newval = ( '' !== newval ) ? newval : default_value;

			if ( jQuery( 'style#' + id ).length ) {
				jQuery( 'style#' + id ).html( selector + '{' + property + ':' + newval + ';}' );
			} else {
				jQuery( 'head' ).append( '<style id="' + id + '">' + selector + '{' + property + ':' + newval + '}</style>' );
				setTimeout(function() {
					jQuery( 'style#' + id ).not( ':last' ).remove();
				}, 1000);
			}
		} );
	} );
}

function olympus_typography_live_update( id, selector, property, unit, media, settings ) {
	settings = typeof settings !== 'undefined' ? settings : 'olympus_settings';
	wp.customize( settings + '[' + id + ']', function( value ) {
		value.bind( function( newval ) {
			// Get our unit if applicable
			unit = typeof unit !== 'undefined' ? unit : '';

			var isTablet = ( 'tablet' == id.substring( 0, 6 ) ) ? true : false,
				isMobile = ( 'mobile' == id.substring( 0, 6 ) ) ? true : false;

			if ( isTablet ) {
				if ( '' == wp.customize(settings + '[' + id + ']').get() ) {
					var desktopID = id.replace( 'tablet_', '' );
					newval = wp.customize(settings + '[' + desktopID + ']').get();
				}
			}

			if ( isMobile ) {
				if ( '' == wp.customize(settings + '[' + id + ']').get() ) {
					var desktopID = id.replace( 'mobile_', '' );
					newval = wp.customize(settings + '[' + desktopID + ']').get();
				}
			}

			if ( 'buttons_font_size' == id && '' == wp.customize('olympus_settings[buttons_font_size]').get() ) {
				newval = wp.customize('olympus_settings[body_font_size]').get();
			}

			// We're using a desktop value
			if ( ! isTablet && ! isMobile ) {

				var tabletValue = ( typeof wp.customize(settings + '[tablet_' + id + ']') !== 'undefined' ) ? wp.customize(settings + '[tablet_' + id + ']').get() : '',
					mobileValue = ( typeof wp.customize(settings + '[mobile_' + id + ']') !== 'undefined' ) ? wp.customize(settings + '[mobile_' + id + ']').get() : '';

				// The tablet setting exists, mobile doesn't
				if ( '' !== tabletValue && '' == mobileValue ) {
					media = olympus_live_preview.desktop + ', ' + olympus_live_preview.mobile;
				}

				// The tablet setting doesn't exist, mobile does
				if ( '' == tabletValue && '' !== mobileValue ) {
					media = olympus_live_preview.desktop + ', ' + olympus_live_preview.tablet;
				}

				// The tablet setting doesn't exist, neither does mobile
				if ( '' == tabletValue && '' == mobileValue ) {
					media = olympus_live_preview.desktop + ', ' + olympus_live_preview.tablet + ', ' + olympus_live_preview.mobile;
				}

			}

			// Check if media query
			media_query = typeof media !== 'undefined' ? 'media="' + media + '"' : '';

			jQuery( 'head' ).append( '<style id="' + id + '" ' + media_query + '>' + selector + '{' + property + ':' + newval + unit + ';}</style>' );
			setTimeout(function() {
				jQuery( 'style#' + id ).not( ':last' ).remove();
			}, 1000);

			setTimeout("jQuery('body').trigger('olympus_spacing_updated');", 1000);
		} );
	} );
}

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Logo width
	wp.customize( 'olympus_settings[logo_width]', function( value ) {
		value.bind( function( newval ) {
			$( '.site-branding img' ).css( 'width', newval + 'px' );

			if ( '' == newval ) {
				$( '.site-branding img' ).css( 'width', '' );
			}
		} );
	} );

	// Container width
	wp.customize( 'olympus_settings[container_width]', function( value ) {
		value.bind( function( newval ) {
			if ( $( 'style#container_width' ).length ) {
				$( 'style#container_width' ).html( 'body .container{max-width:' + newval + 'px;}' );
			} else {
				$( 'head' ).append( '<style id="container_width">body .container{max-width:' + newval + 'px;}</style>' );
				setTimeout(function() {
					$( 'style#container_width' ).not( ':last' ).remove();
				}, 100);
			}
		} );
	} );

	// Shop columns
	wp.customize( 'olympus_settings[shop_columns]', function( value ) {
		value.bind( function( newval ) {
			if ( $( 'style#shop_columns' ).length ) {
				$( 'style#shop_columns' ).html( '.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}' );
			} else {
				$( 'head' ).append( '<style id="shop_columns">.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}</style>' );
				setTimeout(function() {
					$( 'style#shop_columns' ).not( ':last' ).remove();
				}, 100);
			}
		} );
	} );
	wp.customize( 'olympus_settings[tablet_shop_columns]', function( value ) {
		value.bind( function( newval ) {
			if ( $( 'style#tablet_shop_columns' ).length ) {
				$( 'style#tablet_shop_columns' ).html( '@media (min-width: 769px) and (max-width: 1024px){.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}}' );
			} else {
				$( 'head' ).append( '<style id="tablet_shop_columns">@media (min-width: 769px) and (max-width: 1024px){.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}}</style>' );
				setTimeout(function() {
					$( 'style#tablet_shop_columns' ).not( ':last' ).remove();
				}, 100);
			}
		} );
	} );
	wp.customize( 'olympus_settings[mobile_shop_columns]', function( value ) {
		value.bind( function( newval ) {
			if ( $( 'style#mobile_shop_columns' ).length ) {
				$( 'style#mobile_shop_columns' ).html( '@media (max-width:768px){.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}}' );
			} else {
				$( 'head' ).append( '<style id="mobile_shop_columns">@media (max-width:768px){.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product{width:calc( 100% / ' + newval + ' - 30px);}}</style>' );
				setTimeout(function() {
					$( 'style#mobile_shop_columns' ).not( ':last' ).remove();
				}, 100);
			}
		} );
	} );

	/**
	 * Global color
	 */
	olympus_colors_live_update( 'global_color', 'button, input[type="button"], input[type="reset"], input[type="submit"], p.demo_store, .woocommerce-store-notice, .woocommerce span.onsale, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range', 'background-color', '#e5bf63' );
	olympus_colors_live_update( 'global_color', 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="tel"]:focus, input[type="range"]:focus, input[type="date"]:focus, input[type="month"]:focus, input[type="week"]:focus, input[type="time"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="color"]:focus, textarea:focus, .widget h2, .woocommerce.widget_shopping_cart .total, .woocommerce .widget_shopping_cart .total', 'border-color', '#e5bf63' );
	olympus_colors_live_update( 'global_color', 'a:hover, .entry-meta ul li .oly-icon, .woocommerce-account .woocommerce-MyAccount-navigation ul .is-active a, .woocommerce-account .woocommerce-MyAccount-navigation ul a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce p.stars a', 'color', '#e5bf63' );

	/**
	 * Global color hover
	 */
	olympus_colors_live_update( 'global_color_hover', 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, #add_payment_method #payment div.payment_box', 'background-color', '#e8c573' );
	olympus_colors_live_update( 'global_color_hover', '.woocommerce-cart #payment div.payment_box::before, .woocommerce-checkout #payment div.payment_box::before, #add_payment_method #payment div.payment_box::before', 'border-color', '#e8c573' );

	/**
	 * Body background color
	 */
	olympus_colors_live_update( 'background_color', 'body', 'background-color', '#ffffff' );

	/**
	 * Text color
	 */
	olympus_colors_live_update( 'text_color', 'body', 'color', '#777777' );

	/**
	 * Link color
	 */
	olympus_colors_live_update( 'link_color', 'a, a:visited', 'color', '#333333' );

	/**
	 * Link color hover
	 */
	olympus_colors_live_update( 'link_color_hover', 'a:hover', 'color', '#e5bf63' );

	/**
	 * Blog Post Title color
	 */
	olympus_colors_live_update( 'post_title_color', '.entry-title a', 'color', '#333333' );

	/**
	 * Blog Post Title color hover
	 */
	olympus_colors_live_update( 'post_title_color_hover', '.entry-title a:hover', 'color', '#e5bf63' );

	/**
	 * Button background color
	 */
	olympus_colors_live_update( 'button_background_color', 'button, input[type="button"], input[type="reset"], input[type="submit"]', 'background-color', '#e5bf63' );

	/**
	 * Button background color hover
	 */
	olympus_colors_live_update( 'button_background_color_hover', 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover', 'background-color', '#e8c573' );

	/**
	 * Button color
	 */
	olympus_colors_live_update( 'button_color', 'button, input[type="button"], input[type="reset"], input[type="submit"]', 'color', '#ffffff' );

	/**
	 * Button color hover
	 */
	olympus_colors_live_update( 'button_color_hover', 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover', 'color', '#ffffff' );

	/**
	 * Body font size, weight and transform
	 */
	olympus_typography_live_update( 'body_font_size', 'body, button, input, select, textarea', 'font-size', 'px', olympus_live_preview.desktop );
	olympus_typography_live_update( 'mobile_body_font_size', 'body, button, input, select, textarea', 'font-size', 'px', olympus_live_preview.mobile );
	olympus_typography_live_update( 'body_line_height', 'body', 'line-height', '' );
	olympus_typography_live_update( 'paragraph_margin', 'p, .entry-content > [class*="wp-block-"]:not(:last-child)', 'margin-bottom', 'em' );
	olympus_typography_live_update( 'body_font_weight', 'body, button, input, select, textarea', 'font-weight' );
	olympus_typography_live_update( 'body_font_transform', 'body, button, input, select, textarea', 'text-transform' );

	/**
	 * H1 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_1_font_size', 'h1', 'font-size', 'px', olympus_live_preview.desktop );
	olympus_typography_live_update( 'mobile_heading_1_font_size', 'h1', 'font-size', 'px', olympus_live_preview.mobile );
	olympus_typography_live_update( 'heading_1_weight', 'h1', 'font-weight' );
	olympus_typography_live_update( 'heading_1_transform', 'h1', 'text-transform' );
	olympus_typography_live_update( 'heading_1_line_height', 'h1', 'line-height', 'em' );

	/**
	 * H2 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_2_font_size', 'h2', 'font-size', 'px', olympus_live_preview.desktop );
	olympus_typography_live_update( 'mobile_heading_2_font_size', 'h2', 'font-size', 'px', olympus_live_preview.mobile );
	olympus_typography_live_update( 'heading_2_weight', 'h2', 'font-weight' );
	olympus_typography_live_update( 'heading_2_transform', 'h2', 'text-transform' );
	olympus_typography_live_update( 'heading_2_line_height', 'h2', 'line-height', 'em' );

	/**
	 * H3 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_3_font_size', 'h3', 'font-size', 'px' );
	olympus_typography_live_update( 'heading_3_weight', 'h3', 'font-weight' );
	olympus_typography_live_update( 'heading_3_transform', 'h3', 'text-transform' );
	olympus_typography_live_update( 'heading_3_line_height', 'h3', 'line-height', 'em' );

	/**
	 * H4 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_4_font_size', 'h4', 'font-size', 'px' );
	olympus_typography_live_update( 'heading_4_weight', 'h4', 'font-weight' );
	olympus_typography_live_update( 'heading_4_transform', 'h4', 'text-transform' );
	olympus_typography_live_update( 'heading_4_line_height', 'h4', 'line-height', 'em' );

	/**
	 * H5 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_5_font_size', 'h5', 'font-size', 'px' );
	olympus_typography_live_update( 'heading_5_weight', 'h5', 'font-weight' );
	olympus_typography_live_update( 'heading_5_transform', 'h5', 'text-transform' );
	olympus_typography_live_update( 'heading_5_line_height', 'h5', 'line-height', 'em' );

	/**
	 * H6 font size, weight and transform
	 */
	olympus_typography_live_update( 'heading_6_font_size', 'h6', 'font-size', 'px' );
	olympus_typography_live_update( 'heading_6_weight', 'h6', 'font-weight' );
	olympus_typography_live_update( 'heading_6_transform', 'h6', 'text-transform' );
	olympus_typography_live_update( 'heading_6_line_height', 'h6', 'line-height', 'em' );
}( jQuery ) );
