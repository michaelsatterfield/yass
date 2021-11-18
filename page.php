<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Olympus
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php
			/**
			 * olympus_before_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'olympus_before_main_content' );
			
			while ( have_posts() ) :
				the_post();

				olympus_do_template_part( 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.

			/**
			 * olympus_after_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'olympus_after_main_content' );
			?>
			
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'olympus_sidebar' );
get_footer();
