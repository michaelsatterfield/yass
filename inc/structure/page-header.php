<?php // phpcs:ignore WPThemeReview.Templates.ReservedFileNamePrefix.ReservedTemplatePrefixFound
/**
 * Page Header
 *
 * @package Olympus
 */

if ( ! function_exists( 'olympus_get_title' ) ) {
	/**
	 * Get the title for the current page.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_title() {
		$title = olympus_get_option( 'hide_title' );

		if ( is_singular() ) {
			$layout_meta = get_post_meta( get_the_ID(), 'olympus-disable-title', true );

			if ( $layout_meta ) {
				$title = $layout_meta;
			}
		}

		return apply_filters( 'olympus_hide_title', $title );
	}
}

if ( ! function_exists( 'olympus_post_id' ) ) {
	/**
	 * Store current post ID
	 *
	 * @since 1.0.0
	 */
	function olympus_post_id() {

		// Default value.
		$id = '';

		// If singular get_the_ID.
		if ( is_singular() ) {
			$id = get_the_ID();
		} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
			// Get ID of WooCommerce product archive.
			$shop_id = wc_get_page_id( 'shop' );
			if ( isset( $shop_id ) ) {
				$id = $shop_id;
			}
		} elseif ( is_home() && $page_for_posts = get_option( 'page_for_posts' ) ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
			// Posts page.
			$id = $page_for_posts;
		}

		// Apply filters.
		$id = apply_filters( 'olympus_post_id', $id );

		// Sanitize.
		$id = $id ? $id : '';

		// Return ID.
		return $id;

	}
}

if ( ! function_exists( 'olympus_page_title' ) ) {
	/**
	 * Page title
	 *
	 * @since 1.0.0
	 */
	function olympus_page_title() {

		// Default title is null.
		$title = null;

		// Get post ID.
		$post_id = olympus_post_id();

		// Homepage - display blog description if not a static page.
		if ( is_front_page() && ! is_singular( 'page' ) ) {

			if ( get_bloginfo( 'description' ) ) {
				$title = get_bloginfo( 'description' );
			} else {
				$title = esc_html__( 'Recent Posts', 'olympuswp' );
			}

		} elseif ( is_home() && ! is_singular( 'page' ) ) {
			// Homepage posts page.
			$title = get_the_title( get_option( 'page_for_posts', true ) );
		} elseif ( is_search() ) {
			// Search needs to go before archives.
			global $wp_query;
			$title = '<span id="search-results-count">' . $wp_query->found_posts . '</span> ' . esc_html__( 'Search Results Found', 'olympuswp' );
		} elseif ( is_archive() ) {
			// Archives.

			if ( is_author() ) {
				// Author.
				$title = get_the_archive_title();
			} elseif ( is_post_type_archive() ) {
				// Post Type archive title.
				$title = post_type_archive_title( '', false );
			} elseif ( is_day() ) {
				// Daily archive title.
				/* translators: used to get the date */
				$title = sprintf( esc_html__( 'Daily Archives: %s', 'olympuswp' ), get_the_date() );
			} elseif ( is_month() ) {
				// Monthly archive title.
				/* translators: used to get the monthly date */
				$title = sprintf( esc_html__( 'Monthly Archives: %s', 'olympuswp' ), get_the_date( esc_html_x( 'F Y', 'Page title monthly archives date format', 'olympuswp' ) ) );
			} elseif ( is_year() ) {
				// Yearly archive title.
				/* translators: used to get the yearly date */
				$title = sprintf( esc_html__( 'Yearly Archives: %s', 'olympuswp' ), get_the_date( esc_html_x( 'Y', 'Page title yearly archives date format', 'olympuswp' ) ) );
			} else {
				// Categories/Tags/Other.

				// Get term title.
				$title = single_term_title( '', false );

				// Fix for plugins that are archives but use pages.
				if ( ! $title ) {
					global $post;
					$title = get_the_title( $post_id );
				}
			}
		} elseif ( is_404() ) {
			// 404 Page.
			$title = esc_html__( '404: Page Not Found', 'olympuswp' );
		} elseif ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url() ) {
			// Fix for WooCommerce My Accounts pages.
			$endpoint       = WC()->query->get_current_endpoint();
			$endpoint_title = WC()->query->get_endpoint_title( $endpoint );
			$title          = $endpoint_title ? $endpoint_title : $title;
		} elseif ( $post_id ) {
			// Anything else with a post_id defined.

			if ( is_singular( 'page' ) || is_singular( 'attachment' ) ) {
				// Single Pages.
				$title = get_the_title( $post_id );
			} elseif ( is_singular( 'post' ) ) {
				// Single blog posts.
				$title = get_the_title();
			} else {
				// Other posts.
				$title = get_the_title( $post_id );
			}
		}

		// Last check if title is empty.
		$title = $title ? $title : get_the_title();

		// Apply filters and return title.
		return apply_filters( 'olympus_title', $title );

	}
}

if ( ! function_exists( 'olympus_get_page_header' ) ) {
	/**
	 * Page header template
	 *
	 * @since 1.0.0
	 */
	function olympus_get_page_header() {
		
		// Return if page header is disabled.
		if ( olympus_get_title() ) {
			return;
		}

		$breadcrumb_enable = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
		$wpseo_option      = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;
		if ( ! is_array( $wpseo_option ) ) {
			unset( $wpseo_option );
			$wpseo_option = array(
				'breadcrumbs-enable' => $breadcrumb_enable,
			);
		}

		// Wrapper classes.
		$class = 'olympus-page-header';
		if ( ( function_exists( 'yoast_breadcrumb' )
			&& true === $wpseo_option['breadcrumbs-enable'] )
			|| function_exists( 'bcn_display' )
			|| function_exists( 'rank_math_the_breadcrumbs' ) ) {
			$class .= ' has-breadcrumb';
		}

		// If Yoast SEO Bradcrumbs enabled.
		$breadcrumb_enable = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
		$wpseo_option      = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;
		if ( ! is_array( $wpseo_option ) ) {
			unset( $wpseo_option );
			$wpseo_option = array(
				'breadcrumbs-enable' => $breadcrumb_enable,
			);
		}
		?>

		<header class="<?php echo esc_attr( $class ); ?>">

			<div class="container">
				<h2 class="olympus-page-header-title"><?php echo wp_kses_post( olympus_page_title() ); ?></h2>

				<?php
				if ( function_exists( 'yoast_breadcrumb' ) && true === $wpseo_option['breadcrumbs-enable'] ) {
					// Check if breadcrumb is turned on from WPSEO option.
					yoast_breadcrumb( '<div id="olympus-breadcrumbs-yoast" >', '</div>' );
				} elseif ( function_exists( 'bcn_display' ) ) {
					// Check if breadcrumb is turned on from Breadcrumb NavXT plugin.
					return '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">' . bcn_display() . '</div>';
				} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
					// Check if breadcrumb is turned on from Rank Math plugin.
					rank_math_the_breadcrumbs();
				}
				?>
			</div>

		</header><!-- .olympus-page-header -->
		<?php
	}
	add_action( 'olympus_after_header', 'olympus_get_page_header', 10 );
}
