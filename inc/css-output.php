<?php
/**
 * Output all of our dynamic CSS.
 *
 * @package Olympus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'olympus_base_css' ) ) {
	/**
	 * Add the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	function olympus_base_css() {
		$settings = wp_parse_args(
			get_option( 'olympus_settings', array() ),
			olympus_get_defaults()
		);
		$defaults = olympus_get_defaults();

		$css = new Olympus_CSS();

		$css->set_selector( 'button, input[type="button"], input[type="reset"], input[type="submit"]' );
		$css->add_property( 'background-color', $settings['global_color'], $defaults['global_color'] );

		$css->set_selector( 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="tel"]:focus, input[type="range"]:focus, input[type="date"]:focus, input[type="month"]:focus, input[type="week"]:focus, input[type="time"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="color"]:focus, textarea:focus, .widget h2' );
		$css->add_property( 'border-color', $settings['global_color'], $defaults['global_color'] );

		$css->set_selector( 'a:hover, .entry-meta ul li .oly-icon' );
		$css->add_property( 'color', $settings['global_color'], $defaults['global_color'] );

		$css->set_selector( 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover' );
		$css->add_property( 'background-color', $settings['global_color_hover'], $defaults['global_color_hover'] );

		$css->set_selector( 'body' );
		$css->add_property( 'background-color', $settings['background_color'], $defaults['background_color'] );
		$css->add_property( 'color', $settings['text_color'], $defaults['text_color'] );

		$css->set_selector( 'a' );
		$css->add_property( 'color', $settings['link_color'], $defaults['link_color'] );

		$css->set_selector( 'a:hover' );
		$css->add_property( 'color', $settings['link_color_hover'], $defaults['link_color_hover'] );

		$css->set_selector( '.entry-title a' );
		$css->add_property( 'color', $settings['post_title_color'], $defaults['post_title_color'] );

		$css->set_selector( '.entry-title a:hover' );
		$css->add_property( 'color', $settings['post_title_color_hover'], $defaults['post_title_color_hover'] );

		$css->set_selector( 'button, input[type="button"], input[type="reset"], input[type="submit"]' );
		$css->add_property( 'background-color', $settings['button_background_color'], $defaults['button_background_color'] );

		$css->set_selector( 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover' );
		$css->add_property( 'background-color', $settings['button_background_color_hover'], $defaults['button_background_color_hover'] );

		$css->set_selector( 'button, input[type="button"], input[type="reset"], input[type="submit"]' );
		$css->add_property( 'color', $settings['button_color'], $defaults['button_color'] );

		$css->set_selector( 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover' );
		$css->add_property( 'color', $settings['button_color_hover'], $defaults['button_color_hover'] );

		if ( $settings['container_width'] ) {
			$css->set_selector( '.container' );
			$css->add_property( 'max-width', absint( $settings['container_width'] ), $defaults['container_width'], 'px' );
		}

		if ( $settings['logo_width'] ) {
			$css->set_selector( '.site-branding img' );
			$css->add_property( 'width', absint( $settings['logo_width'] ), $defaults['logo_width'], 'px' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$tablet_col = $settings['tablet_shop_columns'];
			$mobile_col = $settings['mobile_shop_columns'];

			$css->set_selector( 'p.demo_store, .woocommerce-store-notice, .woocommerce span.onsale, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range' );
			$css->add_property( 'background-color', $settings['global_color'], $defaults['global_color'] );

			$css->set_selector( '.woocommerce.widget_shopping_cart .total, .woocommerce .widget_shopping_cart .total' );
			$css->add_property( 'border-color', $settings['global_color'], $defaults['global_color'] );

			$css->set_selector( '.woocommerce-account .woocommerce-MyAccount-navigation ul .is-active a, .woocommerce-account .woocommerce-MyAccount-navigation ul a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce p.stars a' );
			$css->add_property( 'color', $settings['global_color'], $defaults['global_color'] );

			$css->set_selector( '.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, #add_payment_method #payment div.payment_box' );
			$css->add_property( 'background-color', $settings['global_color_hover'], $defaults['global_color_hover'] );

			$css->set_selector( '.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li a:focus' );
			$css->add_property( 'color', $settings['global_color_hover'], $defaults['global_color_hover'] );

			$css->set_selector( '.woocommerce-cart #payment div.payment_box::before, .woocommerce-checkout #payment div.payment_box::before, #add_payment_method #payment div.payment_box::before' );
			$css->add_property( 'border-color', $settings['global_color_hover'], $defaults['global_color_hover'] );

			if ( '2' !== $tablet_col ) {
				$css->start_media_query( olympus_get_media_query( 'tablet' ) );
				$css->set_selector( '.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product' );
				$css->add_property( 'width', 'calc( 100% / ' . $tablet_col . ' - 30px)', $defaults['tablet_shop_columns'] );
				$css->stop_media_query();
			}

			if ( '1' !== $mobile_col ) {
				$css->start_media_query( olympus_get_media_query( 'mobile' ) );
				$css->set_selector( '.woocommerce .site-main ul.products li.product, .woocommerce-page .site-main ul.products li.product' );
				$css->add_property( 'width', 'calc( 100% / ' . $mobile_col . ' - 30px)', $defaults['mobile_shop_columns'] );
				$css->stop_media_query();
			}
		}

		do_action( 'olympus_base_css', $css );

		return apply_filters( 'olympus_base_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'olympus_font_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 */
	function olympus_font_css() {

		$settings = wp_parse_args(
			get_option( 'olympus_settings', array() ),
			olympus_get_default_fonts()
		);

		$defaults = olympus_get_default_fonts( false );

		$css = new Olympus_CSS();

		$body_family = olympus_get_font_family_css( 'font_body', 'olympus_settings', olympus_get_default_fonts() );
		$h1_family = olympus_get_font_family_css( 'font_heading_1', 'olympus_settings', olympus_get_default_fonts() );
		$h2_family = olympus_get_font_family_css( 'font_heading_2', 'olympus_settings', olympus_get_default_fonts() );
		$h3_family = olympus_get_font_family_css( 'font_heading_3', 'olympus_settings', olympus_get_default_fonts() );
		$h4_family = olympus_get_font_family_css( 'font_heading_4', 'olympus_settings', olympus_get_default_fonts() );
		$h5_family = olympus_get_font_family_css( 'font_heading_5', 'olympus_settings', olympus_get_default_fonts() );
		$h6_family = olympus_get_font_family_css( 'font_heading_6', 'olympus_settings', olympus_get_default_fonts() );

		$css->set_selector( 'body, button, input, select, textarea' );
		$css->add_property( 'font-family', $defaults['font_body'] !== $settings['font_body'] ? $body_family : null );
		$css->add_property( 'font-weight', $settings['body_font_weight'], $defaults['body_font_weight'] );
		$css->add_property( 'text-transform', $settings['body_font_transform'], $defaults['body_font_transform'] );
		$css->add_property( 'font-size', absint( $settings['body_font_size'] ), $defaults['body_font_size'], 'px' );

		if ( $defaults['body_line_height'] !== $settings['body_line_height'] ) {
			$css->set_selector( 'body' );
			$css->add_property( 'line-height', floatval( $settings['body_line_height'] ), $defaults['body_line_height'] );
		}

		$css->set_selector( 'h1' );
		$css->add_property( 'font-family', $defaults['font_heading_1'] !== $settings['font_heading_1'] ? $h1_family : null );
		$css->add_property( 'font-weight', $settings['heading_1_weight'], $defaults['heading_1_weight'] );
		$css->add_property( 'text-transform', $settings['heading_1_transform'], $defaults['heading_1_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_1_font_size'] ), $defaults['heading_1_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $settings['heading_1_line_height'] ), $defaults['heading_1_line_height'], 'em' );

		$css->set_selector( 'h2' );
		$css->add_property( 'font-family', $defaults['font_heading_2'] !== $settings['font_heading_2'] ? $h2_family : null );
		$css->add_property( 'font-weight', $settings['heading_2_weight'], $defaults['heading_2_weight'] );
		$css->add_property( 'text-transform', $settings['heading_2_transform'], $defaults['heading_2_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_2_font_size'] ), $defaults['heading_2_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $settings['heading_2_line_height'] ), $defaults['heading_2_line_height'], 'em' );

		$css->set_selector( 'h3' );
		$css->add_property( 'font-family', $defaults['font_heading_3'] !== $settings['font_heading_3'] ? $h3_family : null );
		$css->add_property( 'font-weight', $settings['heading_3_weight'], $defaults['heading_3_weight'] );
		$css->add_property( 'text-transform', $settings['heading_3_transform'], $defaults['heading_3_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_3_font_size'] ), $defaults['heading_3_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $settings['heading_3_line_height'] ), $defaults['heading_3_line_height'], 'em' );

		$css->set_selector( 'h4' );
		$css->add_property( 'font-family', $defaults['font_heading_4'] !== $settings['font_heading_4'] ? $h4_family : null );
		$css->add_property( 'font-weight', $settings['heading_4_weight'], $defaults['heading_4_weight'] );
		$css->add_property( 'text-transform', $settings['heading_4_transform'], $defaults['heading_4_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_4_font_size'] ), $defaults['heading_4_font_size'], 'px' );
		if ( ! empty( $settings['heading_4_line_height'] ) ) {
			$css->add_property( 'line-height', floatval( $settings['heading_4_line_height'] ), $defaults['heading_4_line_height'], 'em' );
		}

		$css->set_selector( 'h5' );
		$css->add_property( 'font-family', $defaults['font_heading_5'] !== $settings['font_heading_5'] ? $h5_family : null );
		$css->add_property( 'font-weight', $settings['heading_5_weight'], $defaults['heading_5_weight'] );
		$css->add_property( 'text-transform', $settings['heading_5_transform'], $defaults['heading_5_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_5_font_size'] ), $defaults['heading_5_font_size'], 'px' );
		if ( ! empty( $settings['heading_5_line_height'] ) ) {
			$css->add_property( 'line-height', floatval( $settings['heading_5_line_height'] ), $defaults['heading_5_line_height'], 'em' );
		}

		$css->set_selector( 'h6' );
		$css->add_property( 'font-family', $defaults['font_heading_6'] !== $settings['font_heading_6'] ? $h6_family : null );
		$css->add_property( 'font-weight', $settings['heading_6_weight'], $defaults['heading_6_weight'] );
		$css->add_property( 'text-transform', $settings['heading_6_transform'], $defaults['heading_6_transform'] );
		$css->add_property( 'font-size', absint( $settings['heading_6_font_size'] ), $defaults['heading_6_font_size'], 'px' );
		if ( ! empty( $settings['heading_6_line_height'] ) ) {
			$css->add_property( 'line-height', floatval( $settings['heading_6_line_height'] ), $defaults['heading_6_line_height'], 'em' );
		}

		$css->start_media_query( olympus_get_media_query( 'mobile' ) );
		$css->set_selector( 'body, button, input, select, textarea' );
		$css->add_property( 'font-size', absint( $settings['mobile_body_font_size'] ), $defaults['mobile_body_font_size'], 'px' );

		$css->set_selector( 'h1' );
		$css->add_property( 'font-size', absint( $settings['mobile_heading_1_font_size'] ), $defaults['mobile_heading_1_font_size'], 'px' );

		$css->set_selector( 'h2' );
		$css->add_property( 'font-size', absint( $settings['mobile_heading_2_font_size'] ), $defaults['mobile_heading_2_font_size'], 'px' );
		$css->stop_media_query();

		do_action( 'olympus_typography_css', $css );

		return apply_filters( 'olympus_typography_css_output', $css->css_output() );
	}
}

/**
 * Get all of our dynamic CSS.
 */
function olympus_get_dynamic_css() {
	$css = olympus_base_css() . olympus_font_css();

	return apply_filters( 'olympus_dynamic_css', $css );
}

/**
 * Enqueue our dynamic CSS.
 */
function olympus_enqueue_dynamic_css() {
	$css = olympus_get_dynamic_css();

	if ( $css ) {
		wp_add_inline_style( 'olympus-style', wp_strip_all_tags( $css ) );
	}
}
add_action( 'wp_enqueue_scripts', 'olympus_enqueue_dynamic_css', 50 );
