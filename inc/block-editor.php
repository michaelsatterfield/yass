<?php
/**
 * Integration with Gutenberg.
 *
 * @package Olympus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add CSS to the admin side of the block editor.
 *
 * @since 1.0.0
 */
function olympus_enqueue_backend_block_editor() {
	wp_enqueue_style( 'olympus-block-editor-style', get_template_directory_uri() . '/assets/css/admin/block-editor.css', false, OLY_VERSION, 'all' );
	wp_add_inline_style( 'olympus-block-editor-style', wp_strip_all_tags( olympus_do_inline_block_editor_css() ) );
}
add_action( 'enqueue_block_editor_assets', 'olympus_enqueue_backend_block_editor' );
add_action( 'enqueue_block_editor_assets', 'olympus_enqueue_google_fonts' );

/**
 * Write our CSS for the block editor.
 *
 * @since 1.0.0
 */
function olympus_do_inline_block_editor_css() {
	$settings = get_option( 'olympus_settings', array() );
	$defaults = olympus_get_defaults();

	$css = new Olympus_CSS();

	if ( ! empty( $settings['global_color'] ) ) {
		$css->set_selector( '.wp-block-button__link' );
		$css->add_property( 'background-color', $settings['global_color'], $defaults['global_color'] );

		$css->set_selector( '.editor-styles-wrapper a:hover' );
		$css->add_property( 'color', $settings['global_color'], $defaults['global_color'] );
	}

	if ( ! empty( $settings['global_color_hover'] ) ) {
		$css->set_selector( '.wp-block-button__link:hover' );
		$css->add_property( 'background-color', $settings['global_color_hover'], $defaults['global_color_hover'] );
	}

	if ( ! empty( $settings['background_color'] ) ) {
		$css->set_selector( 'body.block-editor-page, .editor-styles-wrapper' );
		$css->add_property( 'background-color', $settings['background_color'], $defaults['background_color'] );
		$css->add_property( 'color', $settings['text_color'], $defaults['text_color'] );
	}

	if ( ! empty( $settings['link_color'] ) ) {
		$css->set_selector( '.editor-styles-wrapper a' );
		$css->add_property( 'color', $settings['link_color'], $defaults['link_color'] );
	}

	if ( ! empty( $settings['link_color_hover'] ) ) {
		$css->set_selector( '.editor-styles-wrapper a:hover' );
		$css->add_property( 'color', $settings['link_color_hover'], $defaults['link_color_hover'] );
	}

	if ( ! empty( $settings['button_background_color'] ) ) {
		$css->set_selector( 'body .block-editor-block-list__layout .wp-block-button .wp-block-button__link' );
		$css->add_property( 'background-color', $settings['button_background_color'], $defaults['button_background_color'] );
	}

	if ( ! empty( $settings['button_background_color_hover'] ) ) {
		$css->set_selector( 'body .block-editor-block-list__layout .wp-block-button .wp-block-button__link:hover' );
		$css->add_property( 'background-color', $settings['button_background_color_hover'], $defaults['button_background_color_hover'] );
	}

	if ( ! empty( $settings['button_color'] ) ) {
		$css->set_selector( 'body .block-editor-block-list__layout .wp-block-button .wp-block-button__link' );
		$css->add_property( 'color', $settings['button_color'], $defaults['button_color'] );
	}

	if ( ! empty( $settings['button_color_hover'] ) ) {
		$css->set_selector( 'body .block-editor-block-list__layout .wp-block-button .wp-block-button__link:hover' );
		$css->add_property( 'color', $settings['button_color_hover'], $defaults['button_color_hover'] );
	}

	return $css->css_output();
}
