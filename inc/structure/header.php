<?php
/**
 * Header
 *
 * @package Olympus
 */

if ( ! function_exists( 'olympus_get_header' ) ) {
	/**
	 * Build the header.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_header() { ?>
		<header id="masthead" class="site-header" <?php olympus_do_microdata( 'header' ); ?>>
			<div class="site-header-inner container">
				<?php
				/**
				 * olympus_before_header_content hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked olympus_add_logo - 5
				 */
				do_action( 'olympus_before_header_content' );

				/**
				 * olympus_after_header_content hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked olympus_add_navigation - 5
				 */
				do_action( 'olympus_after_header_content' );
				?>
			</div><!-- .site-header-inner -->
		</header><!-- #masthead -->
		<?php
	}
	add_action( 'olympus_header', 'olympus_get_header' );
}

if ( ! function_exists( 'olympus_add_logo' ) ) {
	/**
	 * Build the logo
	 *
	 * @since 1.0.0
	 */
	function olympus_add_logo() {
		/**
		 * olympus_before_logo hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'olympus_before_logo' );
		?>

		<div class="site-branding">
			<?php
			if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title site-logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
				<?php
			}
			?>
		</div>

		<?php
		/**
		 * olympus_after_logo hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'olympus_after_logo' );
	}
	add_action( 'olympus_before_header_content', 'olympus_add_logo', 5 );
}

/**
 * Add skip to content link before the header.
 *
 * @since 1.0.0
 */
function olympus_do_skip_to_content_link() {
	printf(
		'<a class="screen-reader-text skip-link" href="#content" title="%1$s">%2$s</a>',
		esc_attr__( 'Skip to content', 'olympuswp' ),
		esc_html__( 'Skip to content', 'olympuswp' )
	);
}
add_action( 'olympus_before_header', 'olympus_do_skip_to_content_link', 2 );
