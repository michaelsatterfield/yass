<?php
/**
 * Footer
 *
 * @package Olympus
 */

if ( ! function_exists( 'olympus_get_footer' ) ) {
	/**
	 * Build our footer.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_footer() { ?>
		<footer id="colophon" class="site-footer" <?php olympus_do_microdata( 'footer' ); ?>>
			<div class="site-info">
				<?php olympus_footer_widgets(); ?>
				<div class="copyright-bar">
					<div class="container">
						<?php
						/**
						 * olympus_copyright hook.
						 *
						 * @since 1.0.0
						 *
						 * @hooked olympus_add_copyright
						 */
						do_action( 'olympus_copyright' );
						?>
					</div>
				</div>
			</div>
		</footer>
		<?php
	}
	add_action( 'olympus_footer', 'olympus_get_footer' );
}



if ( ! function_exists( 'olympus_footer_widgets' ) ) {
	/**
	 * Build our footer widgets.
	 *
	 * @since 1.0.0
	 */
	function olympus_footer_widgets() {
		$widgets = olympus_get_option( 'footer_columns' );

		if ( ! empty( $widgets ) && 0 !== $widgets ) :

			$footer_1 = is_active_sidebar( 'olympus-footer-1' );
			$footer_2 = is_active_sidebar( 'olympus-footer-2' );
			$footer_3 = is_active_sidebar( 'olympus-footer-3' );
			$footer_4 = is_active_sidebar( 'olympus-footer-4' );

			// If no footer widgets exist, we don't need to continue.
			if ( ! $footer_1 && ! $footer_2 && ! $footer_3 && ! $footer_4 ) {
				return;
			}
			?>
			<div id="footer-widgets">
				<div class="container">
					<div class="footer-widgets footer-col-<?php echo esc_attr( $widgets ); ?>">
						<?php
						if ( $widgets >= 1 && $footer_1 ) {
							?>
							<div class="footer-1 footer-col">
								<?php dynamic_sidebar( 'olympus-footer-1' ); ?>
							</div>
							<?php
						}

						if ( $widgets >= 2 && $footer_2 ) {
							?>
							<div class="footer-2 footer-col">
								<?php dynamic_sidebar( 'olympus-footer-2' ); ?>
							</div>
							<?php
						}

						if ( $widgets >= 3 && $footer_3 ) {
							?>
							<div class="footer-3 footer-col">
								<?php dynamic_sidebar( 'olympus-footer-3' ); ?>
							</div>
							<?php
						}

						if ( $widgets >= 4 && $footer_4 ) {
							?>
							<div class="footer-4 footer-col">
								<?php dynamic_sidebar( 'olympus-footer-4' ); ?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			<?php
		endif;
		/**
		 * olympus_after_footer_widgets hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'olympus_after_footer_widgets' );
	}
}

if ( ! function_exists( 'olympus_add_copyright' ) ) {
	/**
	 * Add the copyright to the footer
	 *
	 * @since 1.0.0
	 */
	function olympus_add_copyright() {
		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'olympus_copyright_output',
			sprintf(
				'<span class="copyright">
					&copy; %1$s %2$s
				</span>
				- <a href="%3$s" itemprop="url">%4$s</a>',
				date( 'Y' ),
				get_bloginfo( 'name' ),
				esc_url( 'https://zeus-elementor.com' ),
				__( 'Built with Olympus', 'olympuswp' )
			)
		);
	}
	add_action( 'olympus_copyright', 'olympus_add_copyright' );
}

if ( ! function_exists( 'olympus_scroll_up' ) ) {
	/**
	 * Build the back to top button
	 *
	 * @since 1.0.0
	 */
	function olympus_scroll_up() {
		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'olympus_scroll_up_output',
			sprintf(
				'<a href="#" class="oly-scroll-up" aria-label="%1$s" rel="nofollow">
					%2$s
				</a>',
				esc_attr__( 'Scroll Up', 'olympuswp' ),
				olympus_get_svg_icon( 'scroll-up' )
			)
		);
	}
	add_action( 'olympus_after_footer', 'olympus_scroll_up' );
}
