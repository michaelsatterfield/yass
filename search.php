<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
			
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					olympus_do_template_part( 'search' );

				endwhile;

				/**
				 * olympus_after_loop hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'olympus_after_loop', 'search' );

			else :

				olympus_do_template_part( 'none' );

			endif;

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
