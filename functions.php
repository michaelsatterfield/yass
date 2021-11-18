<?php
/**
 * Olympus functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Olympus
 */

if ( ! defined( 'OLY_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'OLY_VERSION', '1.0.9' );
}

if ( ! function_exists( 'olympus_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function olympus_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Olympus, use a find and replace
		 * to change 'olympuswp' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'olympuswp', get_template_directory() . '/languages' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'main-menu' => esc_html__( 'Primary', 'olympuswp' ),
			)
		);

		// Add theme support for various features.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		add_theme_support( 'woocommerce' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'yoast-seo-breadcrumbs' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo',
			array(
				'height' => 80,
				'width' => 350,
				'flex-width' => true,
				'flex-height' => true,
			)
		);

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( 'assets/css/admin/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'olympus_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function olympus_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'olympuswp' ),
			'id'            => 'olympus-sidebar',
			'description'   => esc_html__( 'This sidebar will be used as your main sidebar.', 'olympuswp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'olympuswp' ),
			'id'            => 'olympus-footer-1',
			'description'   => esc_html__( 'This sidebar will be used for your footer.', 'olympuswp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'olympuswp' ),
			'id'            => 'olympus-footer-2',
			'description'   => esc_html__( 'This sidebar will be used for your footer.', 'olympuswp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'olympuswp' ),
			'id'            => 'olympus-footer-3',
			'description'   => esc_html__( 'This sidebar will be used for your footer.', 'olympuswp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'olympuswp' ),
			'id'            => 'olympus-footer-4',
			'description'   => esc_html__( 'This sidebar will be used for your footer.', 'olympuswp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'olympus_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function olympus_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Main style.css file.
	wp_enqueue_style( 'olympus-style', get_template_directory_uri() . '/assets/css/style' . $suffix . '.css', array(), OLY_VERSION );
	wp_style_add_data( 'olympus-style', 'rtl', 'replace' );

	// Navigation script.
	wp_enqueue_script( 'olympus-navigation', get_template_directory_uri() . '/assets/js/navigation' . $suffix . '.js', array(), OLY_VERSION, true );

	wp_localize_script(
		'olympus-navigation',
		'olyMenu',
		apply_filters(
			'olympus_localize_js_args',
			array(
				'openSubMenuLabel' => esc_attr__( 'Open Sub-Menu', 'olympuswp' ),
				'closeSubMenuLabel' => esc_attr__( 'Close Sub-Menu', 'olympuswp' ),
			)
		)
	);
	
	// Scroll Top script.
	wp_enqueue_script( 'olympus-scroll-top', get_template_directory_uri() . '/assets/js/scroll-top' . $suffix . '.js', array(), OLY_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'olympus_scripts' );

/**
 * Enqueue scripts and styles.
 */
function olympus_customizer_scripts() {
	wp_enqueue_style( 'olympus-customizer-style', get_template_directory_uri() . '/inc/controls/css/customizer.css', array(), OLY_VERSION );
	wp_style_add_data( 'olympus-customizer-style', 'rtl', 'replace' );
}
add_action( 'customize_controls_enqueue_scripts', 'olympus_customizer_scripts' );

/**
 * Get all necessary theme files
 */
$olympus_dir = get_template_directory();

require $olympus_dir . '/inc/class-olympus-css.php';
require $olympus_dir . '/inc/css-output.php';
require $olympus_dir . '/inc/block-editor.php';

/**
 * Implement site structure.
 */
require $olympus_dir . '/inc/structure/header.php';
require $olympus_dir . '/inc/structure/navigation.php';
require $olympus_dir . '/inc/structure/page-header.php';
require $olympus_dir . '/inc/structure/footer.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require $olympus_dir . '/inc/template-functions.php';

/**
 * WooCommerce class.
 */
require $olympus_dir . '/inc/class-olympus-woocommerce.php';

/**
 * Customizer additions.
 */
require $olympus_dir . '/inc/customizer.php';
require $olympus_dir . '/inc/typography.php';
