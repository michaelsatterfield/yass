<?php
/**
 * Olympus Theme Customizer
 *
 * @package Olympus
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function olympus_customize_register( $wp_customize ) {
	$defaults = olympus_get_defaults();

	// Controls.
	require_once trailingslashit( dirname( __FILE__ ) ) . 'controls/class-olympus-range-slider-control.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	if ( method_exists( $wp_customize, 'register_control_type' ) ) {
		$wp_customize->register_control_type( 'Olympus_Range_Slider_Control' );
	}

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'olympus_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'olympus_customize_partial_blogdescription',
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[logo_width]',
			array(
				'default' => $defaults['logo_width'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_empty_absint',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Olympus_Range_Slider_Control(
				$wp_customize,
				'olympus_settings[logo_width]',
				array(
					'label' => __( 'Logo Width', 'olympuswp' ),
					'section' => 'title_tagline',
					'settings' => array(
						'desktop' => 'olympus_settings[logo_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 20,
							'max' => 1200,
							'step' => 10,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 50,
				)
			)
		);

		$wp_customize->add_section(
			'olympus_general',
			array(
				'title' => __( 'General', 'olympuswp' ),
				'priority' => 20,
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[hide_title]',
			array(
				'default' => $defaults['hide_title'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'olympus_settings[hide_title]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide page title', 'olympuswp' ),
				'section' => 'olympus_general',
				'priority' => 2,
			)
		);

		$wp_customize->add_section(
			'olympus_layout',
			array(
				'title' => __( 'Layout', 'olympuswp' ),
				'priority' => 21,
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[container_width]',
			array(
				'default' => $defaults['container_width'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_integer',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Olympus_Range_Slider_Control(
				$wp_customize,
				'olympus_settings[container_width]',
				array(
					'type' => 'olympus-range-slider',
					'label' => __( 'Container Width', 'olympuswp' ),
					'section' => 'olympus_layout',
					'settings' => array(
						'desktop' => 'olympus_settings[container_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 700,
							'max' => 2000,
							'step' => 5,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 0,
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[general_layout]',
			array(
				'default' => $defaults['general_layout'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'olympus_settings[general_layout]',
			array(
				'type' => 'select',
				'label' => __( 'Sidebar Layout', 'olympuswp' ),
				'section' => 'olympus_layout',
				'choices' => array(
					'full-width' => __( 'Full Width', 'olympuswp' ),
					'left-sidebar' => __( 'Sidebar / Content', 'olympuswp' ),
					'right-sidebar' => __( 'Content / Sidebar', 'olympuswp' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'olympuswp' ),
				),
				'settings' => 'olympus_settings[general_layout]',
				'priority' => 30,
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[blog_layout]',
			array(
				'default' => $defaults['blog_layout'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'olympus_settings[blog_layout]',
			array(
				'type' => 'select',
				'label' => __( 'Blog Layout', 'olympuswp' ),
				'section' => 'olympus_layout',
				'choices' => array(
					'full-width' => __( 'Full Width', 'olympuswp' ),
					'left-sidebar' => __( 'Sidebar / Content', 'olympuswp' ),
					'right-sidebar' => __( 'Content / Sidebar', 'olympuswp' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'olympuswp' ),
				),
				'settings' => 'olympus_settings[blog_layout]',
				'priority' => 35,
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[single_post_layout]',
			array(
				'default' => $defaults['single_post_layout'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'olympus_settings[single_post_layout]',
			array(
				'type' => 'select',
				'label' => __( 'Single Post Layout', 'olympuswp' ),
				'section' => 'olympus_layout',
				'choices' => array(
					'full-width' => __( 'Full Width', 'olympuswp' ),
					'left-sidebar' => __( 'Sidebar / Content', 'olympuswp' ),
					'right-sidebar' => __( 'Content / Sidebar', 'olympuswp' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'olympuswp' ),
				),
				'settings' => 'olympus_settings[single_post_layout]',
				'priority' => 36,
			)
		);

		// If WooCommerce exist.
		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_setting(
				'olympus_settings[shop_layout]',
				array(
					'default' => $defaults['shop_layout'],
					'type' => 'option',
					'sanitize_callback' => 'olympus_sanitize_choices',
				)
			);

			$wp_customize->add_control(
				'olympus_settings[shop_layout]',
				array(
					'type' => 'select',
					'label' => __( 'Shop Layout', 'olympuswp' ),
					'section' => 'olympus_layout',
					'choices' => array(
						'full-width' => __( 'Full Width', 'olympuswp' ),
						'left-sidebar' => __( 'Sidebar / Content', 'olympuswp' ),
						'right-sidebar' => __( 'Content / Sidebar', 'olympuswp' ),
						'no-sidebar' => __( 'Content (no sidebars)', 'olympuswp' ),
					),
					'settings' => 'olympus_settings[shop_layout]',
					'priority' => 40,
				)
			);

			$wp_customize->add_setting(
				'olympus_settings[single_product_layout]',
				array(
					'default' => $defaults['single_product_layout'],
					'type' => 'option',
					'sanitize_callback' => 'olympus_sanitize_choices',
				)
			);

			$wp_customize->add_control(
				'olympus_settings[single_product_layout]',
				array(
					'type' => 'select',
					'label' => __( 'Single Product Layout', 'olympuswp' ),
					'section' => 'olympus_layout',
					'choices' => array(
						'full-width' => __( 'Full Width', 'olympuswp' ),
						'left-sidebar' => __( 'Sidebar / Content', 'olympuswp' ),
						'right-sidebar' => __( 'Content / Sidebar', 'olympuswp' ),
						'no-sidebar' => __( 'Content (no sidebars)', 'olympuswp' ),
					),
					'settings' => 'olympus_settings[single_product_layout]',
					'priority' => 41,
				)
			);

			$wp_customize->add_setting(
				'olympus_settings[shop_columns]',
				array(
					'default' => $defaults['shop_columns'],
					'type' => 'option',
					'sanitize_callback' => 'olympus_sanitize_empty_absint',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'olympus_settings[tablet_shop_columns]',
				array(
					'default' => $defaults['tablet_shop_columns'],
					'type' => 'option',
					'sanitize_callback' => 'olympus_sanitize_empty_absint',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'olympus_settings[mobile_shop_columns]',
				array(
					'default' => $defaults['mobile_shop_columns'],
					'type' => 'option',
					'sanitize_callback' => 'olympus_sanitize_empty_absint',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Olympus_Range_Slider_Control(
					$wp_customize,
					'olympus_settings[shop_columns]',
					array(
						'label' => __( 'Shop Columns', 'olympuswp' ),
						'section' => 'olympus_layout',
						'settings' => array(
							'desktop' => 'olympus_settings[shop_columns]',
							'tablet' => 'olympus_settings[tablet_shop_columns]',
							'mobile' => 'olympus_settings[mobile_shop_columns]',
						),
						'choices' => array(
							'desktop' => array(
								'min' => 1,
								'max' => 6,
								'step' => 1,
								'edit' => true,
							),
							'tablet' => array(
								'min' => 1,
								'max' => 6,
								'step' => 1,
								'edit' => true,
							),
							'mobile' => array(
								'min' => 1,
								'max' => 6,
								'step' => 1,
								'edit' => true,
							),
						),
						'priority' => 50,
					)
				)
			);

			$wp_customize->add_setting(
				'olympus_settings[shop_no_of_products]',
				array(
					'default' => $defaults['shop_no_of_products'],
					'type' => 'option',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'olympus_settings[shop_no_of_products]',
				array(
					'type' => 'number',
					'label' => __( 'Products Per Page', 'olympuswp' ),
					'section' => 'olympus_layout',
					'settings' => 'olympus_settings[shop_no_of_products]',
					'priority' => 50,
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				)
			);
		}

		$wp_customize->add_setting(
			'olympus_settings[footer_columns]',
			array(
				'default' => $defaults['footer_columns'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_integer',
			)
		);

		$wp_customize->add_control(
			new Olympus_Range_Slider_Control(
				$wp_customize,
				'olympus_settings[footer_columns]',
				array(
					'label' => __( 'Footer Columns', 'olympuswp' ),
					'section' => 'olympus_layout',
					'settings' => array(
						'desktop' => 'olympus_settings[footer_columns]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 4,
							'step' => 1,
							'edit' => true,
						),
					),
					'priority' => 50,
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[global_color]',
			array(
				'default' => $defaults['global_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[global_color]',
				array(
					'label' => __( 'Global Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[global_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[global_color_hover]',
			array(
				'default' => $defaults['global_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[global_color_hover]',
				array(
					'label' => __( 'Global Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[global_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[background_color]',
			array(
				'default' => $defaults['background_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[background_color]',
				array(
					'label' => __( 'Background Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[background_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[text_color]',
			array(
				'default' => $defaults['text_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[text_color]',
				array(
					'label' => __( 'Text Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[text_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[link_color]',
			array(
				'default' => $defaults['link_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[link_color]',
				array(
					'label' => __( 'Link Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[link_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[link_color_hover]',
			array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[link_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[link_color_hover]',
			array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[link_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[post_title_color]',
			array(
				'default' => $defaults['post_title_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[post_title_color]',
				array(
					'label' => __( 'Blog Post Title Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[post_title_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[post_title_color_hover]',
			array(
				'default' => $defaults['post_title_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[post_title_color_hover]',
				array(
					'label' => __( 'Blog Post Title Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[post_title_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[button_background_color]',
			array(
				'default' => $defaults['button_background_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[button_background_color]',
				array(
					'label' => __( 'Button Background Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[button_background_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[button_background_color_hover]',
			array(
				'default' => $defaults['button_background_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[button_background_color_hover]',
				array(
					'label' => __( 'Button Background Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[button_background_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[button_color]',
			array(
				'default' => $defaults['button_color'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[button_color]',
				array(
					'label' => __( 'Button Color', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[button_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'olympus_settings[button_color_hover]',
			array(
				'default' => $defaults['button_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'olympus_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'olympus_settings[button_color_hover]',
				array(
					'label' => __( 'Button Color Hover', 'olympuswp' ),
					'section' => 'colors',
					'settings' => 'olympus_settings[button_color_hover]',
				)
			)
		);
	}
}
add_action( 'customize_register', 'olympus_customize_register' );

/**
 * Sanitize a positive number, but allow an empty value.
 *
 * @since 1.0.0
 * @param string $input The value to check.
 */
function olympus_sanitize_empty_absint( $input ) {
	if ( '' == $input ) {
		return '';
	}

	return absint( $input );
}

/**
 * Sanitize integers.
 *
 * @param string $input The value to check.
 */
function olympus_sanitize_integer( $input ) {
	return absint( $input );
}

/**
 * Sanitize checkbox values.
 *
 * @param string $checked The value to check.
 */
function olympus_sanitize_checkbox( $checked ) {
	// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison -- Intentially loose.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize choices.
 *
 * @since 1.3.24
 * @param string $input The value to check.
 * @param object $setting The setting object.
 */
function olympus_sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control.
	// associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it.
	// otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize colors.
 * Allow blank value.
 *
 * @param string $color The color to check.
 */
function olympus_sanitize_hex_color( $color ) {
	// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison -- Intentially loose.
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return '';
}

/**
 * Sanitize our Google Font variants
 *
 * @param string $input The value to check.
 */
function olympus_sanitize_variants( $input ) {
	if ( is_array( $input ) ) {
		$input = implode( ',', $input );
	}
	return sanitize_text_field( $input );
}

/**
 * Sanitize integers that can use decimals.
 *
 * @param string $input The value to check.
 */
function olympus_sanitize_decimal_integer( $input ) {
	return abs( floatval( $input ) );
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function olympus_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function olympus_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function olympus_customize_preview_js() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script(
		'olympus-customizer',
		trailingslashit( get_template_directory_uri() ) . 'inc/controls/js/customizer' . $suffix . '.js',
		array( 'customize-preview' ),
		OLY_VERSION,
		true
	);

	wp_localize_script(
		'olympus-customizer',
		'olympus_live_preview',
		array(
			'mobile' => olympus_get_media_query( 'mobile' ),
			'tablet' => olympus_get_media_query( 'tablet' ),
			'desktop' => olympus_get_media_query( 'desktop' ),
			'isRTL' => is_rtl(),
		)
	);
}
add_action( 'customize_preview_init', 'olympus_customize_preview_js', 100 );

/**
 * Add misc inline scripts to our controls.
 *
 * We don't want to add these to the controls themselves, as they will be repeated
 * each time the control is initialized.
 */
function olympus_do_control_inline_scripts() {
	wp_localize_script(
		'olympus-typography-customizer',
		'olympus_customize',
		array(
			'nonce' => wp_create_nonce( 'olympus_customize_nonce' ),
		)
	);

	$number_of_fonts = apply_filters( 'olympus_number_of_fonts', 200 );

	wp_localize_script(
		'olympus-typography-customizer',
		'olympusTypography',
		array(
			'googleFonts' => apply_filters( 'olympus_typography_customize_list', olympus_get_all_google_fonts( $number_of_fonts ) ),
		)
	);

	wp_localize_script( 'olympus-typography-customizer', 'typography_defaults', olympus_typography_default_fonts() );
}
add_action( 'customize_controls_enqueue_scripts', 'olympus_do_control_inline_scripts', 100 );
