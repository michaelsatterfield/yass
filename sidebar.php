<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Olympus
 */

// Return if full width or no sidebar.
if ( in_array( olympus_get_layout(), array( 'full-width', 'no-sidebar' ) ) ) {
    return;
}

// Sidebar.
$sidebar = apply_filters( 'olympus_get_sidebar', 'olympus-sidebar' ) ?>

<aside id="secondary" class="widget-area" <?php olympus_do_microdata( 'sidebar' ); ?>>
    <?php
    if ( is_active_sidebar( $sidebar ) ) {
        dynamic_sidebar( $sidebar );
    }
    ?>
</aside><!-- #secondary -->
