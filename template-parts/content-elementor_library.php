<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part for displaying Elementor Library post type
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Olympus
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php olympus_do_microdata( 'article' ); ?>>
	<div class="entry-content">
		<?php
		the_content();
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
