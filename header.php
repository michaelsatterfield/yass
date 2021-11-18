<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Olympus
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php olympus_do_microdata( 'body' ); ?>>
	<?php
	/**
	 * wp_body_open.
	 */
	wp_body_open();

	/**
	 * olympus_before_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked olympus_do_skip_to_content_link - 1
	 */
	do_action( 'olympus_before_header' );

	/**
	 * olympus_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked olympus_get_header - 10
	 */
	do_action( 'olympus_header' );

	/**
	 * olympus_after_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked olympus_get_page_header - 10
	 */
	do_action( '');
//    olympus_after_header

    ?>

	<div id="page" class="site">
		<?php
		/**
		 * olympus_inside_site hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'olympus_inside_site' );
		?>
		<div id="content" class="site-content container">
			<?php
			/**
			 * olympus_inside_container hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'olympus_inside_container' );
			?>
