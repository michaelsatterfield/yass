<?php
/**
 * WooCommerce Compatibility.
 *
 * @link https://woocommerce.com/
 *
 * @package Olympus
 */

// If WooCommerce exist.
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

if ( ! class_exists( 'Olympus_Woocommerce' ) ) :

	/**
	 * Olympus WooCommerce Compatibility
	 *
	 * @since 1.0.0
	 */
	class Olympus_Woocommerce {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once get_template_directory() . '/inc/woocommerce-functions.php';

			add_filter( 'woocommerce_enqueue_styles', array( $this, 'enqueue_styles' ) );

			add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );

			add_action( 'widgets_init', array( $this, 'widgets_init' ), 15 );
			add_filter( 'olympus_get_sidebar', array( $this, 'woo_sidebar' ) );

			add_filter( 'olympus_sidebar_layout', array( $this, 'layouts' ) );
			add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ) );
			add_filter( 'loop_shop_per_page', array( $this, 'shop_no_of_products' ) );

			add_filter( 'woocommerce_show_page_title', '__return_false' );
			add_filter( 'woocommerce_product_description_heading', '__return_false' );
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
			add_action( 'wp', array( $this, 'product_content' ), 5 );

			add_filter( 'woocommerce_product_get_rating_html', array( $this, 'rating_markup' ), 10, 3 );

		}

		/**
		 * Subcategory Count Markup
		 *
		 * @param  array $styles  Css files.
		 *
		 * @return array
		 */
		public function enqueue_styles( $styles ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$url = get_template_directory_uri() . '/assets/css/woocommerce/';

			$styles = array(
				'woocommerce-layout'      => array(
					'src'     => $url . 'woocommerce-layout' . $suffix . '.css',
					'deps'    => '',
					'version' => OLY_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
				'woocommerce-smallscreen' => array(
					'src'     => $url . 'woocommerce-smallscreen' . $suffix . '.css',
					'deps'    => 'woocommerce-layout',
					'version' => OLY_VERSION,
					'media'   => 'only screen and (max-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', '768px' ) . ')', // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
					'has_rtl' => true,
				),
				'woocommerce-general'     => array(
					'src'     => $url . 'woocommerce' . $suffix . '.css',
					'deps'    => '',
					'version' => OLY_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
			);

			return $styles;
		}

		/**
		 * Setup theme
		 *
		 * @since 1.0.0
		 */
		public function setup_theme() {
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}

		/**
		 * Sidebar.
		 *
		 * @since 1.0.0
		 */
		public function widgets_init() {
			register_sidebar(
				apply_filters(
					'olympus_woocommerce_sidebar_init',
					array(
						'name'          => esc_html__( 'WooCommerce Sidebar', 'olympuswp' ),
						'id'            => 'olympus-woo-sidebar',
						'description'   => __( 'This sidebar will be used on your products pages.', 'olympuswp' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				)
			);
		}

		/**
		 * Assign woo sidebar for store page.
		 *
		 * @param array $sidebar Default argument array.
		 *
		 * @since 1.0.0
		 */
		public function woo_sidebar( $sidebar ) {

			if ( is_shop()
				|| is_product_taxonomy()
				|| is_checkout()
				|| is_cart()
				|| is_account_page()
				|| is_product() ) {
				$sidebar = 'olympus-woo-sidebar';
			}

			return $sidebar;
		}

		/**
		 * Tweaks the layouts for WooCommerce archives and single product posts.
		 *
		 * @param array $layout Default argument array.
		 *
		 * @since 1.0.0
		 */
		public function layouts( $layout ) {
			if ( is_shop()
				|| is_product_taxonomy()
				|| is_checkout()
				|| is_cart()
				|| is_account_page() ) {
				$layout = olympus_get_option( 'shop_layout' );
			} elseif ( is_product() ) {
				$layout = olympus_get_option( 'single_product_layout' );
			}
			return $layout;
		}

		/**
		 * Update Shop columns
		 *
		 * @param  int $col Shop Column.
		 * @return int
		 */
		public function shop_columns( $col ) {

			$col = olympus_get_option( 'shop_columns' );
			return $col;
		}

		/**
		 * Check if the current page is a Product Subcategory page or not.
		 *
		 * @param integer $category_id Current page Category ID.
		 * @return boolean
		 */
		public function is_subcategory( $category_id = null ) {
			if ( is_tax( 'product_cat' ) ) {
				if ( empty( $category_id ) ) {
					$category_id = get_queried_object_id();
				}
				$category = get_term( get_queried_object_id(), 'product_cat' );
				if ( empty( $category->parent ) ) {
					return false;
				}
				return true;
			}
			return false;
		}

		/**
		 * Products per page
		 *
		 * @return int
		 */
		public function shop_no_of_products() {
			$taxonomy_page_display = get_option( 'woocommerce_category_archive_display', false );
			if ( is_product_taxonomy() && 'subcategories' === $taxonomy_page_display ) {
				if ( $this->is_subcategory() ) {
					$products = olympus_get_option( 'shop_no_of_products' );
					return $products;
				}
				$products = wp_count_posts( 'product' )->publish;
			} else {
				$products = olympus_get_option( 'shop_no_of_products' );
			}
			return $products;
		}

		/**
		 * Product Content.
		 *
		 * @return void
		 */
		public function product_content() {
			// Remove defaults actions.
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

			// Add sale flash.
			add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 9 );

			// Add Out of Stock.
			add_action( 'woocommerce_shop_loop_item_title', 'olympus_woocommerce_shop_out_of_stock', 8 );

			// Add custom products content.
			add_action( 'woocommerce_after_shop_loop_item', 'olympus_woocommerce_shop_product_content' );
		}

		/**
		 * Rating Markup
		 *
		 * @since 1.0.0
		 * @param  string $html  Rating Markup.
		 * @param  float  $rating Rating being shown.
		 * @param  int    $count  Total number of ratings.
		 * @return string
		 */
		public function rating_markup( $html, $rating, $count ) {

			if ( 0 == $rating ) {
				$html  = '<div class="star-rating">';
				$html .= wc_get_star_rating_html( $rating, $count );
				$html .= '</div>';
			}
			return $html;
		}
	}

endif;

Olympus_Woocommerce::get_instance();
