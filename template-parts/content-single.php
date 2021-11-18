<?php
/**
 * Template part for displaying single posts
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
			<?php the_title(); ?>
		</h2>
		<?php olympus_get_post_meta(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'olympuswp' ),
				'after'  => '</div>',
			)
		);

		$term_separator = apply_filters( 'olympus_term_separator', _x( ', ', 'Used between list items, there is a space after the comma.', 'olympuswp' ), 'tags' );
		$tags_list = get_the_tag_list( '', $term_separator );

		if ( $tags_list ) {
			echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'olympus_tag_list_output',
				sprintf(
					'<span class="tags-links">%3$s<span class="screen-reader-text">%1$s </span>%2$s</span> ',
					esc_html_x( 'Tags', 'Used before tag names.', 'olympuswp' ),
					$tags_list,
					olympus_get_svg_icon( 'tags' )
				)
			);
		}
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php olympus_single_post_nav(); ?>
