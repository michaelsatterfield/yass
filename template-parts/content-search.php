<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Olympus
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php olympus_do_microdata( 'article' ); ?>>

	<?php olympus_post_thumbnail(); ?>

	<header class="entry-header">
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<?php olympus_get_post_meta(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content" <?php olympus_do_microdata( 'text' ); ?>>
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
