<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Olympus
 */

if ( ! function_exists( 'olympus_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 *
	 * @since 1.0.0
	 */
	function olympus_body_classes( $classes ) {
		$layout = olympus_get_layout();

		// Value have default, but we like to be extra careful.
		$classes[] = ( $layout ) ? $layout : 'full-width';

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'olympus-sidebar' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}
	add_filter( 'body_class', 'olympus_body_classes' );
}

if ( ! function_exists( 'olympus_pingback_header' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @since 1.0.0
	 */
	function olympus_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
	add_action( 'wp_head', 'olympus_pingback_header' );
}

if ( ! function_exists( 'olympus_get_media_query' ) ) {
	/**
	 * Get our media queries.
	 *
	 * @param string $name Name of the media query.
	 * @return string The full media query.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_media_query( $name ) {
		$desktop = apply_filters( 'olympus_desktop_media_query', '(min-width:1025px)' );
		$tablet = apply_filters( 'olympus_tablet_media_query', '(min-width: 769px) and (max-width: 1024px)' );
		$mobile = apply_filters( 'olympus_mobile_media_query', '(max-width:768px)' );
		$mobile_menu = apply_filters( 'olympus_mobile_menu_media_query', $mobile );

		$queries = apply_filters(
			'olympus_media_queries',
			array(
				'desktop' => $desktop,
				'tablet' => $tablet,
				'mobile' => $mobile,
				'mobile-menu' => $mobile_menu,
			)
		);

		return $queries[ $name ];
	}
}

if ( ! function_exists( 'olympus_get_microdata' ) ) {
	/**
	 * Get any necessary microdata.
	 *
	 * @param string $context The element to target.
	 * @return string Our final attribute to add to the element.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_microdata( $context ) {
		$data = false;

		if ( 'body' === $context ) {
			$type = 'WebPage';

			if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
				$type = 'Blog';
			}

			if ( is_search() ) {
				$type = 'SearchResultsPage';
			}

			$type = apply_filters( 'olympus_body_itemtype', $type );

			$data = sprintf(
				'itemtype="https://schema.org/%s" itemscope',
				esc_html( $type )
			);
		}

		if ( 'header' === $context ) {
			$data = 'itemtype="https://schema.org/WPHeader" itemscope';
		}

		if ( 'navigation' === $context ) {
			$data = 'itemtype="https://schema.org/SiteNavigationElement" itemscope';
		}

		if ( 'article' === $context ) {
			$type = apply_filters( 'olympus_article_itemtype', 'CreativeWork' );

			$data = sprintf(
				'itemtype="https://schema.org/%s" itemscope',
				esc_html( $type )
			);
		}

		if ( 'text' === $context ) {
			$data = 'itemprop="text"';
		}

		if ( 'post-author' === $context ) {
			$data = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
		}

		if ( 'comment-body' === $context ) {
			$data = 'itemtype="https://schema.org/Comment" itemscope';
		}

		if ( 'comment-author' === $context ) {
			$data = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
		}

		if ( 'sidebar' === $context ) {
			$data = 'itemtype="https://schema.org/WPSideBar" itemscope';
		}

		if ( 'footer' === $context ) {
			$data = 'itemtype="https://schema.org/WPFooter" itemscope';
		}

		if ( $data ) {
			return apply_filters( "olympus_{$context}_microdata", $data );
		}
	}
}

if ( ! function_exists( 'olympus_do_microdata' ) ) {
	/**
	 * Output our microdata for an element.
	 *
	 * @param string $context The element to target.
	 *
	 * @since 1.0.0
	 */
	function olympus_do_microdata( $context ) {
		echo olympus_get_microdata( $context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in function.
	}
}

if ( ! function_exists( 'olympus_get_svg_icon' ) ) {
	/**
	 * Create SVG icons.
	 *
	 * @param string $icon The icon to get.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_svg_icon( $icon ) {
		$output = '';

		if ( 'menu-bars' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M0 96c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24z"></path></svg><svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em"><path d="M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z"></path></svg>';
		}

		if ( 'arrow-up' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 611.986 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M596.145,405.201L347.627,129.608c-11.418-11.494-26.691-16.551-41.633-15.631c-14.967-0.945-30.215,4.112-41.633,15.631 L15.842,405.201c-21.123,21.251-21.123,55.731,0,76.982c21.123,21.25,55.399,21.25,76.522,0l213.629-236.898l213.629,236.898 c21.123,21.25,55.398,21.25,76.521,0C617.268,460.933,617.268,426.452,596.145,405.201z"/></svg>';
		}

		if ( 'arrow-left' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 792.082" role="img" xml:space="preserve" width="1em" height="1em"><path d="M317.896,396.024l304.749-276.467c27.36-27.36,27.36-71.677,0-99.037s-71.677-27.36-99.036,0L169.11,342.161 c-14.783,14.783-21.302,34.538-20.084,53.897c-1.218,19.359,5.301,39.114,20.084,53.897l354.531,321.606 c27.36,27.36,71.677,27.36,99.037,0s27.36-71.677,0-99.036L317.896,396.024z"/></svg>';
		}

		if ( 'arrow-down' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 612.007 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M596.168,130.647c-21.119-21.169-55.382-21.169-76.526,0L306.013,366.44L92.384,130.647 c-21.144-21.169-55.382-21.169-76.525,0c-21.144,21.169-21.144,55.458,0,76.627l248.504,274.31 c11.438,11.438,26.672,16.482,41.651,15.54c14.953,0.942,30.213-4.102,41.65-15.54l248.505-274.31 C617.287,186.105,617.287,151.817,596.168,130.647z"/></svg>';
		}

		if ( 'arrow-right' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 792.049" role="img" xml:space="preserve" width="1em" height="1em"><path d="M622.955,342.127L268.424,20.521c-27.36-27.36-71.677-27.36-99.037,0c-27.36,27.36-27.36,71.676,0,99.037 l304.749,276.468L169.387,672.492c-27.36,27.359-27.36,71.676,0,99.036s71.677,27.36,99.037,0l354.531-321.606 c14.783-14.783,21.302-34.538,20.084-53.897C644.225,376.665,637.738,356.911,622.955,342.127z"/></svg>';
		}

		if ( 'nav-left-arrow' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 386.242 386.242" role="img" xml:space="preserve" width="1em" height="1em"><path d="M374.212,182.3H39.432l100.152-99.767c4.704-4.704,4.704-12.319,0-17.011 c-4.704-4.704-12.319-4.704-17.011,0L3.474,184.61c-4.632,4.632-4.632,12.379,0,17.011l119.1,119.1 c4.704,4.704,12.319,4.704,17.011,0c4.704-4.704,4.704-12.319,0-17.011L39.432,206.36h334.779c6.641,0,12.03-5.39,12.03-12.03 S380.852,182.3,374.212,182.3z"/></svg>';
		}

		if ( 'nav-right-arrow' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 384.97 384.97" role="img" xml:space="preserve" width="1em" height="1em"><path d="M384.97,192.487c0-3.212-1.323-6.28-3.525-8.59L262.357,63.606c-4.704-4.752-12.319-4.74-17.011,0 c-4.704,4.74-4.704,12.439,0,17.179l98.564,99.551H12.03C5.39,180.337,0,185.774,0,192.487c0,6.713,5.39,12.151,12.03,12.151 h331.868l-98.552,99.551c-4.704,4.74-4.692,12.439,0,17.179c4.704,4.74,12.319,4.74,17.011,0l119.088-120.291 C383.694,198.803,384.934,195.675,384.97,192.487z"/></svg>';
		}

		if ( 'scroll-up' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 240.835 240.835" role="img" xml:space="preserve" width="1em" height="1em"><path d="M129.007,57.819c-4.68-4.68-12.499-4.68-17.191,0L3.555,165.803c-4.74,4.74-4.74,12.427,0,17.155 c4.74,4.74,12.439,4.74,17.179,0l99.683-99.406l99.671,99.418c4.752,4.74,12.439,4.74,17.191,0c4.74-4.74,4.74-12.427,0-17.155 L129.007,57.819z"/></svg>';
		}

		if ( 'meta-author' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 428.974 428.975" role="img" xml:space="preserve" width="1em" height="1em"><path d="M414.101,373.866l-106.246-56.188l-4.907-15.332c-1.469-5.137-3.794-10.273-8.576-11.623 c-1.519-0.428-3.441-3.201-3.689-5.137l-2.836-29.813c-0.156-2.553,0.868-4.844,2.216-6.453 c8.14-9.754,12.577-21.051,14.454-33.967c0.944-6.494,4.323-12.483,6.059-18.879l6.812-35.649 c0.711-4.681,0.573-8.289-4.659-10.103c-1.443-0.503-2.699-2.894-2.699-6.479l0.069-67.264 c-1.111-16.28-9.731-28.869-21.957-37.631c-23.354-16.739-65.175-8.977-51.526-36.281c0.806-1.607,0.549-4.399-5.062-2.335 c-20.936,7.703-76.701,28.057-90.71,38.616c-12.538,9.449-20.844,21.351-21.957,37.631l0.069,67.264c0,2.96-1.255,5.976-2.7,6.479 c-5.233,1.813-5.37,5.422-4.659,10.103l6.814,35.649c1.732,6.396,5.113,12.386,6.058,18.879 c1.875,12.916,6.315,24.213,14.453,33.967c1.347,1.609,2.372,3.9,2.216,6.453l-2.836,29.813c-0.249,1.936-2.174,4.709-3.69,5.137 c-4.783,1.35-7.109,6.486-8.577,11.623l-4.909,15.332l-106.25,56.188c-2.742,1.449-4.457,4.297-4.457,7.397v39.343 c0,4.621,3.748,8.368,8.37,8.368h391.4c4.622,0,8.37-3.747,8.37-8.368v-39.343C418.557,378.163,416.842,375.315,414.101,373.866z"/></svg>';
		}

		if ( 'meta-date' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M256,239c-9.374,0-17,7.626-17,17c0,9.374,7.626,17,17,17c9.374,0,17-7.626,17-17C273,246.626,265.374,239,256,239z"/><path d="M437.02,74.981C388.667,26.629,324.38,0,256,0S123.333,26.629,74.981,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.667,74.98,181.019C123.333,485.371,187.62,512,256,512s132.667-26.629,181.019-74.98 C485.371,388.667,512,324.38,512,256S485.371,123.333,437.02,74.981z M48,271h-2c-8.284,0-15-6.716-15-15s6.716-15,15-15h2 c8.284,0,15,6.716,15,15S56.284,271,48,271z M241,46c0-8.284,6.716-15,15-15s15,6.716,15,15v2c0,8.284-6.716,15-15,15 s-15-6.716-15-15V46z M119.528,413.685l-1.414,1.415c-5.858,5.858-15.355,5.858-21.213,0c-5.858-5.858-5.858-15.355,0-21.213 l1.414-1.414c5.858-5.858,15.355-5.858,21.213,0C125.386,398.331,125.386,407.827,119.528,413.685z M119.528,119.528 c-5.858,5.858-15.355,5.858-21.213,0l-1.414-1.414c-5.858-5.858-5.858-15.355,0-21.213c5.858-5.858,15.355-5.858,21.213,0 l1.414,1.415C125.386,104.173,125.386,113.67,119.528,119.528z M271,466c0,8.284-6.716,15-15,15s-15-6.716-15-15v-2 c0-8.284,6.716-15,15-15s15,6.716,15,15V466z M368,271h-67.469c-6.276,18.58-23.86,32-44.531,32c-25.916,0-47-21.084-47-47 c0-20.671,13.42-38.255,32-44.531V112c0-8.284,6.716-15,15-15s15,6.716,15,15v99.469c13.866,4.684,24.848,15.665,29.531,29.531 H368c8.284,0,15,6.716,15,15S376.284,271,368,271z M415.099,415.099c-5.858,5.858-15.355,5.858-21.213,0l-1.414-1.415 c-5.858-5.857-5.858-15.355,0-21.213c5.858-5.858,15.355-5.858,21.213,0l1.414,1.414 C420.957,399.744,420.957,409.241,415.099,415.099z M415.099,118.114l-1.414,1.414c-5.858,5.858-15.355,5.858-21.213,0 c-5.858-5.858-5.858-15.355,0-21.213l1.414-1.415c5.858-5.858,15.355-5.858,21.213,0 C420.957,102.758,420.957,112.256,415.099,118.114z M466,271h-2c-8.284,0-15-6.716-15-15s6.716-15,15-15h2c8.284,0,15,6.716,15,15 S474.284,271,466,271z"/></svg>';
		}

		if ( 'meta-cat' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M403.626,85.731H139.537c-8.181,0-14.813-6.632-14.813-14.813v-7.412c0-8.341-6.762-15.103-15.103-15.103H30.206 C13.524,48.403,0,61.926,0,78.609v254.163l38.757-188.557c1.416-6.888,7.478-11.831,14.51-11.831h380.565v-16.447 C433.832,99.254,420.308,85.731,403.626,85.731z"/><path d="M481.79,162.01H65.345v0L7.058,445.577c-1.912,9.304,5.194,18.02,14.693,18.02h433.357 c15.397,0,28.33-11.581,30.023-26.885l26.682-241.174C513.792,177.649,499.787,162.01,481.79,162.01z"/></svg>';
		}

		if ( 'meta-comments' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.999 511.999" role="img" xml:space="preserve" width="1em" height="1em"><path d="M113.304,396.177c-41.405-40.668-66.722-93.368-72.6-150.216c-22.006,27.818-33.938,61.937-33.883,97.745 c0.037,23.29,5.279,46.441,15.212,67.376L1.551,470.689c-3.521,10.247-0.949,21.373,6.713,29.036 c5.392,5.392,12.501,8.264,19.812,8.264c3.076,0,6.188-0.508,9.223-1.551l59.609-20.483c20.935,9.933,44.086,15.175,67.376,15.212 c36.509,0.049,71.256-12.338,99.361-35.168C207.133,460.886,154.416,436.556,113.304,396.177z"/><path d="M510.156,401.842L480.419,315.3c14.334-29.302,21.909-61.89,21.96-94.679c0.088-57.013-21.97-110.92-62.112-151.79 C400.117,27.952,346.615,4.942,289.615,4.039C230.51,3.104,174.954,25.586,133.187,67.352 C91.42,109.119,68.934,164.674,69.87,223.782c0.903,56.999,23.913,110.502,64.79,150.652 c40.79,40.064,94.56,62.116,151.451,62.114c0.112,0,0.23,0,0.34,0c32.79-0.051,65.378-7.626,94.68-21.96l86.544,29.738 c3.606,1.239,7.304,1.843,10.959,1.843c8.688,0,17.136-3.412,23.545-9.822C511.284,427.241,514.34,414.021,510.156,401.842z M307.004,295.328H195.331c-8.416,0-15.238-6.823-15.238-15.238c0-8.416,6.823-15.238,15.238-15.238h111.672 c8.416,0,15.238,6.823,15.238,15.238C322.241,288.506,315.42,295.328,307.004,295.328z M376.892,232.659h-181.56 c-8.416,0-15.238-6.823-15.238-15.238s6.823-15.238,15.238-15.238h181.56c8.416,0,15.238,6.823,15.238,15.238 S385.308,232.659,376.892,232.659z M376.892,169.988h-181.56c-8.416,0-15.238-6.823-15.238-15.238 c0-8.416,6.823-15.238,15.238-15.238h181.56c8.416,0,15.238,6.823,15.238,15.238C392.13,163.165,385.308,169.988,376.892,169.988z"/></svg>';
		}

		if ( 'tags' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" role="img" xml:space="preserve" width="1em" height="1em"><path d="M444.07,67.946H302.344c-13.613,0-26.409,5.301-36.034,14.927L65.872,283.312c-9.626,9.625-14.927,22.422-14.927,36.034 s5.301,26.409,14.927,36.034L207.596,497.1c9.934,9.934,22.984,14.9,36.033,14.9s26.099-4.967,36.033-14.902l200.44-200.44 c9.626-9.626,14.927-22.422,14.927-36.034v-141.72C495.029,90.806,472.169,67.946,444.07,67.946z M376.124,237.81 c-28.099,0-50.959-22.86-50.959-50.959s22.86-50.959,50.959-50.959s50.959,22.86,50.959,50.959S404.223,237.81,376.124,237.81z"/><path d="M410.097,0H268.371c-13.613,0-26.409,5.301-36.034,14.927L31.899,215.366c-9.626,9.625-14.927,22.422-14.927,36.034 c0,10.647,3.256,20.788,9.276,29.31c3.999-7.81,9.219-15.04,15.603-21.422L242.288,58.849 c16.041-16.041,37.369-24.876,60.056-24.876h141.724c4.942,0,9.78,0.448,14.493,1.263C451.918,14.81,432.709,0,410.097,0z"/></svg>';
		}

		if ( 'error' === $icon ) {
			$output = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" xml:space="preserve" class=""><path d="M84.446,317.403H13.082C5.649,317.403,0,312.348,0,303.724c0-2.378,39.246-146.295,39.246-146.295 c3.568-7.434,10.11-10.11,16.354-10.11c6.839,0,23.99,4.614,23.99,13.536c0,1.487,0.073,4.603-0.82,6.386L49.655,281.423h34.791 v-31.221c0-8.622,9.812-12.191,19.327-12.191c9.814,0,19.328,3.568,19.328,12.191v31.221h13.975 c8.029,0,12.192,8.921,12.192,18.138c0,8.92-5.949,17.842-12.192,17.842h-13.975v35.087c0,8.029-9.515,12.191-19.328,12.191 c-9.515,0-19.327-4.163-19.327-12.191V317.403z" fill="#e5bf63"/><path d="M390.515,199.571c6.446-23.746,11.462-42.142,11.462-42.142c3.568-7.434,10.11-10.11,16.354-10.11 c6.839,0,23.99,4.614,23.99,13.536c0,1.487,0.073,4.603-0.82,6.386l-29.114,114.182h34.791v-31.221 c0-8.622,9.812-12.191,19.327-12.191c9.814,0,19.328,3.568,19.328,12.191v31.221h13.975c8.029,0,12.192,8.921,12.192,18.138 c0,8.92-5.949,17.842-12.192,17.842h-13.975v35.087c0,8.029-9.515,12.191-19.328,12.191c-9.515,0-19.327-4.163-19.327-12.191 v-35.087h-71.365c-7.433,0-13.082-5.055-13.082-13.679c0-1.191,9.84-37.869,19.666-74.197L390.515,199.571z" fill="#e5bf63"/><circle xmlns="http://www.w3.org/2000/svg" cx="256.009" cy="263.073" r="88.481" fill="#f9572b"/><path xmlns="http://www.w3.org/2000/svg" d="M267.088,263.076l30.777-30.777c3.06-3.06,3.06-8.023,0-11.082c-3.062-3.06-8.023-3.06-11.083,0 l-30.777,30.777l-30.777-30.777c-3.062-3.06-8.023-3.06-11.083,0c-3.06,3.06-3.06,8.023,0,11.082l30.777,30.777l-30.777,30.777  c-3.06,3.06-3.06,8.023,0,11.082c1.531,1.53,3.537,2.296,5.542,2.296s4.011-0.765,5.542-2.296l30.777-30.777l30.777,30.777  c1.531,1.53,3.537,2.296,5.542,2.296s4.011-0.765,5.542-2.296c3.06-3.06,3.06-8.023,0-11.082L267.088,263.076z" fill="#ffffff"/></svg>';
		}

		$output = apply_filters( 'olympus_svg_icon_list', $output, $icon );

		$classes = array(
			'oly-icon',
			'icon-' . $icon,
		);

		$output = sprintf(
			'<span class="%1$s">%2$s</span>',
			implode( ' ', $classes ),
			$output
		);

		return apply_filters( 'olympus_svg_icon', $output, $icon );
	}
}

/**
 * Check if we should include the default template part.
 *
 * @since 1.0.0
 * @param string $template The template to get.
 */
function olympus_do_template_part( $template ) {
	if ( apply_filters( 'olympus_do_template_part', true, $template ) ) {
		if ( 'archive' === $template || 'index' === $template ) {
			get_template_part( 'template-parts/content', get_post_format() );
		}

		if ( 'page' === $template ) {
			get_template_part( 'template-parts/content', 'page' );
		}

		if ( 'single' === $template ) {
			get_template_part( 'template-parts/content', 'single' );
		}

		if ( 'search' === $template ) {
			get_template_part( 'template-parts/content', 'search' );
		}

		if ( 'none' === $template ) {
			get_template_part( 'template-parts/content', 'none' );
		}

		if ( 'elementor_library' === $template ) {
			get_template_part( 'template-parts/content', 'elementor_library' );
		}
	}
}

if ( ! function_exists( 'olympus_get_post_meta' ) ) {
	/**
	 * Post meta
	 *
	 * @return string post meta markup.
	 */
	function olympus_get_post_meta() {
		$time_string   = esc_html( get_the_date() );
		$modified_date = esc_html( get_the_modified_date() );
		$posted_on     = sprintf(
			esc_html( '%s' ),
			$time_string
		);
		$modified_on   = sprintf(
			esc_html( '%s' ),
			$modified_date
		);

		echo '<div class="entry-meta">';
		echo '<ul>';

		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'olympus_post_author_output',
			sprintf(
				'<li class="post-meta-author posted-by vcard author">%4$s<a class="url fn n" href="%1$s" title="%2$s" rel="author" itemprop="url">%3$s</a></li>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				/* translators: 1: Author name */
				esc_attr( sprintf( __( 'View all posts by %s', 'olympuswp' ), get_the_author() ) ),
				esc_html( get_the_author() ),
				olympus_get_svg_icon( 'meta-author' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in function.
			)
		);

		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'olympus_post_date_output',
			sprintf(
				'<li class="post-meta-date posted-on">%3$s<span class="published" itemprop="datePublished">%1$s</span><span class="updated" itemprop="dateModified">%2$s</span></li>',
				$posted_on,
				$modified_on,
				olympus_get_svg_icon( 'meta-date' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in function.
			)
		);

		if ( get_the_category_list( __( ', ', 'olympuswp' ) ) ) {
			echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'olympus_post_cat_output',
				sprintf(
					'<li class="post-meta-cat">%2$s<span class="cat-links">%1$s</span></li>',
					get_the_category_list( __( ', ', 'olympuswp' ) ),
					olympus_get_svg_icon( 'meta-cat' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in function.
				)
			);
		}

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<li class="post-meta-comments">' . olympus_get_svg_icon( 'meta-comments' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in function.
				echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'olympus_post_comments_output',
					'',
					'comments-link'
				);
				comments_popup_link( __( 'Leave a comment', 'olympuswp' ), __( '1 Comment', 'olympuswp' ), __( '% Comments', 'olympuswp' ) );
			echo '</li> ';
		}
		echo '</ul>';
		echo '</div>';

	}
}

if ( ! function_exists( 'olympus_excerpt_more' ) ) {
	/**
	 * Prints the read more HTML to post excerpts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link.
	 */
	function olympus_excerpt_more( $more ) {
		$read_more_text    = apply_filters( 'olympus_post_read_more', __( 'Read More &raquo;', 'olympuswp' ) );
		$read_more_classes = apply_filters( 'olympus_post_read_more_class', array( 'read-more-link' ) );

		$post_link = sprintf(
			esc_html( '%s' ),
			'<a class="' . esc_attr( implode( ' ', $read_more_classes ) ) . '" href="' . esc_url( get_permalink() ) . '"> ' . the_title( '<span class="screen-reader-text">', '</span>', false ) . ' ' . $read_more_text . '</a>'
		);

		$output = ' &hellip;<p class="read-more"> ' . $post_link . '</p>';

		return apply_filters( 'olympus_excerpt_more_output', $output );
	}
	add_filter( 'excerpt_more', 'olympus_excerpt_more' );
}

if ( ! function_exists( 'olympus_post_navigation_template' ) ) {
	/**
	 * Added this filter to modify the post navigation template to remove the h2 tag from screen reader text.
	 *
	 * @since 1.0.0
	 */
	function olympus_post_navigation_template() {

		$new_template = '
		        <nav class="navigation %1$s" role="navigation" aria-label="%4$s">
		                <span class="screen-reader-text">%2$s</span>
		                <div class="nav-links">%3$s</div>
		        </nav>';

		return $new_template;

	}
	add_filter( 'navigation_markup_template', 'olympus_post_navigation_template' );
}

if ( ! function_exists( 'olympus_single_post_nav' ) ) {
	/**
	 * Get Post Navigation
	 *
	 * Checks post navigation, if exists return as button.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Post Navigation Buttons
	 */
	function olympus_single_post_nav() {

		/* translators: used to get the icon */
		$nav_next = __( 'Next %s', 'olympuswp' ) . olympus_get_svg_icon( 'nav-right-arrow' );
		/* translators: used to get the icon */
		$nav_prev = olympus_get_svg_icon( 'nav-left-arrow' ) . __( 'Previous %s', 'olympuswp' );

		if ( is_rtl() ) {
			/* translators: used to get the icon */
			$nav_next = __( 'Next %s', 'olympuswp' ) . olympus_get_svg_icon( 'nav-left-arrow' );
			/* translators: used to get the icon */
			$nav_prev = olympus_get_svg_icon( 'nav-right-arrow' ) . __( 'Previous %s', 'olympuswp' );
		}

		$post_obj = get_post_type_object( get_post_type() );

		$next_text = sprintf(
			$nav_next,
			$post_obj->labels->singular_name
		);

		$prev_text = sprintf(
			$nav_prev,
			$post_obj->labels->singular_name
		);

		/**
		 * Filter the post pagination markup
		 */
		the_post_navigation(
			apply_filters(
				'olympus_single_post_navigation',
				array(
					'next_text' => $next_text,
					'prev_text' => $prev_text,
				)
			)
		);

	}
}

if ( ! function_exists( 'olympus_comment_form_default_fields' ) ) {
	/**
	 * Function filter comment form's default fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Array of comment form's default fields.
	 * @return array        Comment form fields.
	 */
	function olympus_comment_form_default_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );

		$fields['author'] = '<div class="olympus-comment-wrap oly-row"><p class="comment-form-author oly-col oly-col-3">' .
					'<label for="author" class="screen-reader-text">' . esc_html( __( 'Name*', 'olympuswp' ) ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					'" placeholder="' . esc_attr( __( 'Name*', 'olympuswp' ) ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['email']  = '<p class="comment-form-email oly-col oly-col-3">' .
					'<label for="email" class="screen-reader-text">' . esc_html( __( 'Email*', 'olympuswp' ) ) . '</label><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" placeholder="' . esc_attr( __( 'Email*', 'olympuswp' ) ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['url']    = '<p class="comment-form-url oly-col oly-col-3">' .
					'<label for="url" class="screen-reader-text">' . esc_html( __( 'Website', 'olympuswp' ) ) . '</label><input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) .
					'" placeholder="' . esc_attr( __( 'Website', 'olympuswp' ) ) . '" size="30" /></label></p></div>';

		return apply_filters( 'olympus_comment_form_default_fields', $fields );
	}
	add_filter( 'comment_form_default_fields', 'olympus_comment_form_default_fields' );
}

if ( ! function_exists( 'olympus_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $nav_id The id of our navigation.
	 */
	function olympus_content_nav( $nav_id ) {
		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}
		?>
		<nav id="<?php echo esc_attr( $nav_id ); ?>" class="paging-navigation">
			<span class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'olympuswp' ); ?></span>

			<?php
			if ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages.

				if ( function_exists( 'the_posts_pagination' ) ) {
					the_posts_pagination(
						array(
							'mid_size' => apply_filters( 'olympus_pagination_mid_size', 1 ),
							'prev_text' => apply_filters(
								'olympus_previous_link_text',
								sprintf(
									/* translators: left arrow */
									__( '%s Previous', 'olympuswp' ),
									'<span aria-hidden="true">&larr;</span>'
								)
							),
							'next_text' => apply_filters(
								'olympus_next_link_text',
								sprintf(
									/* translators: right arrow */
									__( 'Next %s', 'olympuswp' ),
									'<span aria-hidden="true">&rarr;</span>'
								)
							),
							'before_page_number' => sprintf(
								'<span class="screen-reader-text">%s</span>',
								_x( 'Page', 'prepends the pagination page number for screen readers', 'olympuswp' )
							),
						)
					);
				}
			endif;
			?>
		</nav>
		<?php
	}
}

/**
 * Add our post navigation after post loops.
 *
 * @since 1.0.0
 * @param string $template The template of the current action.
 */
function olympus_do_post_navigation( $template ) {
	$templates = apply_filters(
		'olympus_post_navigation_templates',
		array(
			'index',
			'archive',
			'search',
		)
	);

	if ( in_array( $template, $templates ) && apply_filters( 'olympus_show_post_navigation', true ) ) {
		echo olympus_content_nav( 'oly-pagination' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'olympus_after_loop', 'olympus_do_post_navigation' );

if ( ! function_exists( 'olympus_comment_form_defaults' ) ) {
	/**
	 * Function filter comment form arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args   Comment form arguments.
	 * @return array
	 */
	function olympus_comment_form_defaults( $args ) {
		if ( 'post' == get_post_type() ) {
			$args['id_form']           = 'oly-commentform';
			$args['title_reply']       = __( 'Leave a Comment', 'olympuswp' );
			$args['cancel_reply_link'] = __( 'Cancel Reply', 'olympuswp' );
			$args['label_submit']      = __( 'Post Comment &raquo;', 'olympuswp' );
			$args['comment_field']     = '<div class="oly-row comment-textarea"><fieldset class="comment-form-comment"><div class="comment-form-textarea oly-col"><label for="comment" class="screen-reader-text">' . esc_html( __( 'Type here..', 'olympuswp' ) ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr( __( 'Type here..', 'olympuswp' ) ) . '" cols="45" rows="8" aria-required="true"></textarea></div></fieldset></div>';
		}
		return apply_filters( 'olympus_comment_form_defaults', $args );
	}
	add_filter( 'comment_form_defaults', 'olympus_comment_form_defaults' );
}

if ( ! function_exists( 'olympus_enqueue_meta_box_scripts' ) ) {
	/**
	 * Adds any scripts for this meta box.
	 *
	 * @param string $hook The current admin page.
	 *
	 * @since 1.0.0
	 */
	function olympus_enqueue_meta_box_scripts( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			$post_types = get_post_types( array( 'public' => true ) );
			$screen = get_current_screen();
			$post_type = $screen->id;

			if ( in_array( $post_type, (array) $post_types ) ) {
				wp_enqueue_style( 'olympus-layout-metabox', get_template_directory_uri() . '/assets/css/admin/meta-box.css', array(), OLY_VERSION );
			}
		}
	}
	add_action( 'admin_enqueue_scripts', 'olympus_enqueue_meta_box_scripts' );
}

if ( ! function_exists( 'olympus_list_comments' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * @param  string $comment Comment.
	 * @param  array  $args    Comment arguments.
	 * @param  number $depth   Depth.
	 * @return mixed          Comment markup.
	 *
	 * @since 1.0.0
	 */
	function olympus_list_comments( $comment, $args, $depth ) {
		$commenter          = wp_get_current_commenter();
		$show_pending_links = ! empty( $commenter['comment_author'] );
		?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<div class="comment-info">
					<div class="comment-avatar">
						<?php
						if ( 0 != $args['avatar_size'] ) {
							echo get_avatar( $comment, $args['avatar_size'] );
						}
						?>
					</div><!-- .comment-avatar -->

					<header class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$comment_author = get_comment_author_link( $comment );

							if ( '0' == $comment->comment_approved && ! $show_pending_links ) {
								$comment_author = get_comment_author( $comment );
							}

							printf(
								/* translators: %s: Comment author link. */
								'<b itemprop="name" class="fn">%s</b>',
								$comment_author // phpcs:ignore
							);
							?>
						</div><!-- .comment-avatar -->

						<div class="comment-date">
							<?php
							printf(
								'<a href="%s"><time datetime="%s">%s</time></a>',
								esc_url( get_comment_link( $comment, $args ) ),
								get_comment_time( 'c' ), // phpcs:ignore
								sprintf(
									/* translators: 1: Comment date, 2: Comment time. */
									__( '%1$s at %2$s', 'olympuswp' ), // phpcs:ignore
									get_comment_date( '', $comment ), // phpcs:ignore
									get_comment_time() // phpcs:ignore
								)
							);
							?>
						</div><!-- .comment-date -->
					</header><!-- .comment-meta -->
				</div><!-- .comment-info -->

				<section class="comment-content">
					<?php comment_text(); ?>
				</section><!-- .comment-content -->

				<div class="comment-links">
					<?php
					edit_comment_link( __( 'Edit', 'olympuswp' ), ' <span class="edit-link">', '</span>' );

					if ( '1' == $comment->comment_approved || $show_pending_links ) {
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<span class="reply-link">',
									'after'     => '</span>',
								)
							)
						);
					}
					?>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'olympuswp' ); ?></em>
				<?php endif; ?>
			</article><!-- .comment-body -->
		<?php
	}
}

if ( ! function_exists( 'olympus_register_layout_meta_box' ) ) {
	/**
	 * Generate the layout metabox
	 *
	 * @since 1.0.0
	 */
	function olympus_register_layout_meta_box() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		global $post;

		$blog_id = get_option( 'page_for_posts' );

		// No need for the Layout metabox on the blog page.
		if ( $blog_id && (int) $blog_id === (int) $post->ID ) {
			return;
		}

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $type ) {
			if ( 'attachment' !== $type ) {
				add_meta_box(
					'olympus_layout_options_meta_box',
					esc_html__( 'Layout', 'olympuswp' ),
					'olympus_do_layout_meta_box',
					$type,
					'side'
				);
			}
		}
	}
	add_action( 'add_meta_boxes', 'olympus_register_layout_meta_box' );
}

if ( ! function_exists( 'olympus_do_layout_meta_box' ) ) {
	/**
	 * Build our meta box.
	 *
	 * @param object $post All post information.
	 *
	 * @since 1.0.0
	 */
	function olympus_do_layout_meta_box( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'olympus_layout_nonce' );

		$meta = (array) get_post_meta( $post->ID );
		$layout = ( isset( $meta['olympus-layout-meta'][0] ) ) ? $meta['olympus-layout-meta'][0] : '';
		$title = ( isset( $meta['olympus-disable-title'][0] ) ) ? $meta['olympus-disable-title'][0] : '';
		?>

		<div id="olympus-meta-box-container">
			<div class="olympus-meta-box-content">
				<div id="olympus-layout-sidebars">
					<div class="olympus_layouts">
						<label for="olympus-sidebar-layout" class="olympus-layout-metabox-section-title"><?php esc_html_e( 'Sidebar Layout', 'olympuswp' ); ?></label>

						<select name="olympus-layout-meta" id="olympus-sidebar-layout">
							<option value="no-sidebar" <?php selected( $layout, 'no-sidebar' ); ?>><?php esc_html_e( 'No Sidebars', 'olympuswp' ); ?></option>
							<option value="full-width" <?php selected( $layout, 'full-width' ); ?>><?php esc_html_e( 'Full Width', 'olympuswp' ); ?></option>
							<option value="right-sidebar" <?php selected( $layout, 'right-sidebar' ); ?>><?php esc_html_e( 'Right Sidebar', 'olympuswp' ); ?></option>
							<option value="left-sidebar" <?php selected( $layout, 'left-sidebar' ); ?>><?php esc_html_e( 'Left Sidebar', 'olympuswp' ); ?></option>
						</select>
					</div>
				</div>

				<div id="olympus-layout-disable-elements">
					<label class="olympus-layout-metabox-section-title"><?php esc_html_e( 'Disable Elements', 'olympuswp' ); ?></label>
					<div class="olympus_disable_elements">
						<label for="meta-olympus-disable-title" title="<?php esc_attr_e( 'Content Title', 'olympuswp' ); ?>">
							<input type="checkbox" name="olympus-disable-title" id="meta-olympus-disable-title" value="true" <?php checked( $title, 'true' ); ?>>
							<?php esc_html_e( 'Content Title', 'olympuswp' ); ?>
						</label>
					</div>

					<?php do_action( 'olympus_layout_disable_elements_section', $meta ); ?>
				</div>
				<?php do_action( 'olympus_layout_meta_box_content', $meta ); ?>
			</div>
		</div>

		<?php
	}
	add_action( 'save_post', 'olympus_save_layout_meta_data' );
}

if ( ! function_exists( 'olympus_save_layout_meta_data' ) ) {
	/**
	 * Saves the sidebar layout meta data.
	 *
	 * @param string $post_id The option name to look up.
	 *
	 * @since 1.0.0
	 */
	function olympus_save_layout_meta_data( $post_id ) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['olympus_layout_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['olympus_layout_nonce'] ), basename( __FILE__ ) ) ) ? true : false;

		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$sidebar_layout_key   = 'olympus-layout-meta';
		$sidebar_layout_value = filter_input( INPUT_POST, $sidebar_layout_key, FILTER_SANITIZE_STRING );

		if ( $sidebar_layout_value ) {
			update_post_meta( $post_id, $sidebar_layout_key, $sidebar_layout_value );
		} else {
			delete_post_meta( $post_id, $sidebar_layout_key );
		}

		$disable_content_title_key   = 'olympus-disable-title';
		$disable_content_title_value = filter_input( INPUT_POST, $disable_content_title_key, FILTER_SANITIZE_STRING );

		if ( $disable_content_title_value ) {
			update_post_meta( $post_id, $disable_content_title_key, $disable_content_title_value );
		} else {
			delete_post_meta( $post_id, $disable_content_title_key );
		}

		do_action( 'olympus_layout_meta_box_save', $post_id );
	}
	add_action( 'save_post', 'olympus_save_layout_meta_data' );
}

if ( ! function_exists( 'olympus_get_defaults' ) ) {
	/**
	 * Set default options
	 *
	 * @since 1.0.0
	 */
	function olympus_get_defaults() {
		return apply_filters(
			'olympus_option_defaults',
			array(
				'logo_width' => '',
				'hide_title' => '',
				'container_width' => '1290',
				'general_layout' => 'right-sidebar',
				'blog_layout' => 'right-sidebar',
				'single_post_layout' => 'right-sidebar',
				'shop_layout' => 'no-sidebar',
				'single_product_layout' => 'no-sidebar',
				'shop_columns' => '4',
				'tablet_shop_columns' => '2',
				'mobile_shop_columns' => '1',
				'shop_no_of_products' => '12',
				'footer_columns' => '4',
				'global_color' => '#e5bf63',
				'global_color_hover' => '#e8c573',
				'background_color' => '#ffffff',
				'text_color' => '#777777',
				'link_color' => '#e5bf63',
				'link_color_hover' => '#333333',
				'post_title_color' => '#333333',
				'post_title_color_hover' => '#e5bf63',
				'button_background_color' => '#e5bf63',
				'button_background_color_hover' => '#e8c573',
				'button_color' => '#ffffff',
				'button_color_hover' => '#ffffff',
			)
		);
	}
}

if ( ! function_exists( 'olympus_get_default_fonts' ) ) {
	/**
	 * Set default options.
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_default_fonts( $filter = true ) {
		$defaults = array(
			'font_body' => '',
			'font_body_category' => '',
			'font_body_variants' => '',
			'body_font_weight' => 'normal',
			'body_font_transform' => 'none',
			'body_font_size' => '14',
			'body_line_height' => '1.8', // no unit.
			'mobile_body_font_size' => '14',
			'font_heading_1' => 'inherit',
			'font_heading_1_category' => '',
			'font_heading_1_variants' => '',
			'heading_1_weight' => 'normal',
			'heading_1_transform' => 'none',
			'heading_1_font_size' => '35',
			'heading_1_line_height' => '1.2', // em.
			'mobile_heading_1_font_size' => '24',
			'font_heading_2' => 'inherit',
			'font_heading_2_category' => '',
			'font_heading_2_variants' => '',
			'heading_2_weight' => 'normal',
			'heading_2_transform' => 'none',
			'heading_2_font_size' => '28',
			'heading_2_line_height' => '1.2', // em.
			'mobile_heading_2_font_size' => '20',
			'font_heading_3' => 'inherit',
			'font_heading_3_category' => '',
			'font_heading_3_variants' => '',
			'heading_3_weight' => 'normal',
			'heading_3_transform' => 'none',
			'heading_3_font_size' => '24',
			'heading_3_line_height' => '1.2', // em.
			'font_heading_4' => 'inherit',
			'font_heading_4_category' => '',
			'font_heading_4_variants' => '',
			'heading_4_weight' => 'normal',
			'heading_4_transform' => 'none',
			'heading_4_font_size' => '20',
			'heading_4_line_height' => '', // em.
			'font_heading_5' => 'inherit',
			'font_heading_5_category' => '',
			'font_heading_5_variants' => '',
			'heading_5_weight' => 'normal',
			'heading_5_transform' => 'none',
			'heading_5_font_size' => '18',
			'heading_5_line_height' => '', // em.
			'font_heading_6' => 'inherit',
			'font_heading_6_category' => '',
			'font_heading_6_variants' => '',
			'heading_6_weight' => 'normal',
			'heading_6_transform' => 'none',
			'heading_6_font_size' => '15',
			'heading_6_line_height' => '', // em.
		);

		if ( $filter ) {
			return apply_filters( 'olympus_font_option_defaults', $defaults );
		}

		return $defaults;
	}
}

if ( ! function_exists( 'olympus_typography_default_fonts' ) ) {
	/**
	 * Set the default system fonts.
	 *
	 * @since 1.0.0
	 */
	function olympus_typography_default_fonts() {
		$fonts = array(
			'inherit',
			'System Stack',
			'Arial, Helvetica, sans-serif',
			'Century Gothic',
			'Comic Sans MS',
			'Courier New',
			'Georgia, Times New Roman, Times, serif',
			'Helvetica',
			'Impact',
			'Lucida Console',
			'Lucida Sans Unicode',
			'Palatino Linotype',
			'Segoe UI, Helvetica Neue, Helvetica, sans-serif',
			'Tahoma, Geneva, sans-serif',
			'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
		);

		return apply_filters( 'olympus_typography_default_fonts', $fonts );
	}
}

if ( ! function_exists( 'olympus_get_option' ) ) {
	/**
	 * A wrapper function to get our options.
	 *
	 * @param string $option The option name to look up.
	 * @return string The option value.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_option( $option ) {
		$defaults = olympus_get_defaults();

		if ( ! isset( $defaults[ $option ] ) ) {
			return;
		}

		$options = wp_parse_args(
			get_option( 'olympus_settings', array() ),
			$defaults
		);

		return $options[ $option ];
	}
}

if ( ! function_exists( 'olympus_get_layout' ) ) {
	/**
	 * Get the layout for the current page.
	 *
	 * @return string The sidebar layout location.
	 *
	 * @since 1.0.0
	 */
	function olympus_get_layout() {
		$layout = olympus_get_option( 'general_layout' );

		if ( is_home()
			|| is_archive()
			|| is_search()
			|| is_tax() ) {
			$layout = olympus_get_option( 'blog_layout' );
		}

		if ( is_404()
			|| is_singular( 'elementor_library' ) ) {
			$layout = 'no-sidebar';
		}

		if ( is_single() ) {
			$layout = olympus_get_option( 'single_post_layout' );
		}

		if ( is_singular() ) {
			$layout_meta = get_post_meta( get_the_ID(), 'olympus-layout-meta', true );

			if ( $layout_meta ) {
				$layout = $layout_meta;
			}
		}

		return apply_filters( 'olympus_sidebar_layout', $layout );
	}
}

if ( ! function_exists( 'olympus_display_sidebar' ) ) {
	/**
	 * Display sidebar.
	 *
	 * @since 1.0.0
	 */
	function olympus_display_sidebar() {
		// Return if full width or full screen.
		if ( in_array( olympus_get_layout(), array( 'full-width', 'no-sidebar' ) ) ) {
			return;
		}

		// Add the default sidebar.
		get_sidebar();
	}
	add_action( 'olympus_sidebar', 'olympus_display_sidebar' );
}

if ( ! function_exists( 'olympus_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @since 1.0.0
	 */
	function olympus_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
}
