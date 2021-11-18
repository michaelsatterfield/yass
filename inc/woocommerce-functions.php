<?php
/**
 * Custom functions that used for Woocommerce
 *
 * @package Olympus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'olympus_woocommerce_shop_products_title' ) ) {
	/**
	 * Shop Page product titles
	 *
	 * @since 1.0.0
	 */
	function olympus_woocommerce_shop_products_title() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
			echo '<h2 class="woocommerce-loop-product__title">' . esc_html( get_the_title() ) . '</h2>';
		echo '</a>';
	}
}

if ( ! function_exists( 'olympus_woocommerce_shop_products_category' ) ) {
	/**
	 * Add and/or Remove Categories from shop archive page.
	 *
	 * @since 1.0.0
	 */
	function olympus_woocommerce_shop_products_category() {
		if ( apply_filters( 'olympus_woocommerce_shop_parent_category', true ) ) { ?>
			<span class="olympus-product-category">
				<?php
				global $product;
				$product_categories = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( get_the_ID(), ';', '', '' ) : $product->get_categories( ';', '', '' );

				$product_categories = htmlspecialchars_decode( wp_strip_all_tags( $product_categories ) );
				if ( $product_categories ) {
					list( $parent_cat ) = explode( ';', $product_categories );
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo apply_filters( 'olympus_woocommerce_shop_product_categories', esc_html( $parent_cat ), get_the_ID() );
				}
				?>
			</span>
			<?php
		}
	}
}

if ( ! function_exists( 'olympus_woocommerce_shop_out_of_stock' ) ) {
	/**
	 * Add Out of Stock to the Shop page
	 *
	 * @hooked woocommerce_shop_loop_item_title - 8
	 *
	 * @since 1.0.0
	 */
	function olympus_woocommerce_shop_out_of_stock() {
		$out_of_stock        = get_post_meta( get_the_ID(), '_stock_status', true );
		$out_of_stock_string = apply_filters( 'olympus_woocommerce_shop_out_of_stock_string', __( 'Out of stock', 'olympuswp' ) );
		if ( 'outofstock' === $out_of_stock ) {
			?>
			<span class="olympus-product-out-of-stock"><?php echo esc_html( $out_of_stock_string ); ?></span>
			<?php
		}
	}
}

if ( ! function_exists( 'olympus_woocommerce_shop_product_content' ) ) {
	/**
	 * Product content.
	 *
	 * @since 1.0.0
	 */
	function olympus_woocommerce_shop_product_content() {

		do_action( 'olympus_woo_shop_before_products_wrap' );
		echo '<div class="olympus-products-wrap">';

			/**
			 * Product Category.
			 */
			do_action( 'olympus_woo_shop_category_before' );
			olympus_woocommerce_shop_products_category();
			do_action( 'olympus_woo_shop_category_after' );

			/**
			 * Product Title.
			 */
			do_action( 'olympus_woo_shop_title_before' );
			olympus_woocommerce_shop_products_title();
			do_action( 'olympus_woo_shop_title_after' );

			/**
			 * Product Price.
			 */
			do_action( 'olympus_woo_shop_price_before' );
			woocommerce_template_loop_price();
			do_action( 'olympus_woo_shop_price_after' );

			/**
			 * Product Rating.
			 */
			do_action( 'olympus_woo_shop_rating_before' );
			woocommerce_template_loop_rating();
			do_action( 'olympus_woo_shop_rating_after' );

			/**
			 * Product Add To Cart.
			 */
			do_action( 'olympus_woo_shop_add_to_cart_before' );
			woocommerce_template_loop_add_to_cart();
			do_action( 'olympus_woo_shop_add_to_cart_after' );

		echo '</div>';
		do_action( 'olympus_woo_shop_after_products_wrap' );

	}
}
