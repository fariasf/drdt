<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bumblebee
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="content-image">
		<?php bumblebee_post_thumbnail( 'large' ); ?>
	</div>
	<div class="content-container">
		<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'bumblebee' ) );
			if ( $categories_list ) {
				echo '<span class="cat-links">';
				/* translators: 1: list of categories. */
<<<<<<< HEAD
				printf( esc_html__( '%1$s', 'bumblebee' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput
=======
				printf( esc_html( '%1$s' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput
>>>>>>> 36e8eb10b14f732f48d9e4a7f2bb6e242c5b696f
				echo '</span>';
			}
			the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		}
		?>
<<<<<<< HEAD
		</header><!-- .entry-header -->

		<div class="entry-summary">
		<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div><!-- .content-container -->
</article><!-- #post-<?php the_ID(); ?> -->
=======

		</header><!-- .entry-header -->

		<div class="entry-summary">
		<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->


>>>>>>> 36e8eb10b14f732f48d9e4a7f2bb6e242c5b696f
