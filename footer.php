<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Olympus
 */

?>

			</div><!-- #content -->
		</div><!-- #page -->

		<?php
		/**
		 * olympus_before_footer hook.
		 *
		 * @since 1.0.0
		 */
		do_action( '' );

		/**
		 * olympus_before_footer_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'olympus_before_footer_content' );

		/**
		 * olympus_footer hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked olympus_get_footer
		 */
		do_action( '' );

		/**
		 * olympus_after_footer_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( '' );

		/**
		 * olympus_after_footer hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked olympus_scroll_up - 10
		 */
		do_action( '' );

		wp_footer();
		?>

	</body>
</html>
