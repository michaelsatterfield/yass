<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

				if ( is_singular( 'elementor_library' ) ) {

					olympus_do_template_part( 'elementor_library' );

				} else {
					olympus_do_template_part( 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				}

			endwhile;

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
