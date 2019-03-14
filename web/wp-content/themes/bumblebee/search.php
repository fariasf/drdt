<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package bumblebee
 */

get_header();
?>
<style type="text/css">
	<?php require get_stylesheet_directory() . '/search.css'; ?>
</style>
	<section id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h2 class="page-title">
				<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'bumblebee' ), '<span>' . get_search_query() . '</span>' );
				?>
				</h2>
				<hr>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
				get_template_part( 'template-parts/content', 'search' );
			endwhile;
			?>
			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php

get_footer();
