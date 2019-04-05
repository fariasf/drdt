<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package bumblebee
 */

get_header();
?>
<section class="pure-g page-content">
	<div class="content">
		<?php dynamic_sidebar( 'not-found-widget' ); ?>
	</div><!-- #primary -->
</section>
	

<?php
get_footer();
