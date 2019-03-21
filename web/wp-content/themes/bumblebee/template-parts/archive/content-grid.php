<?php
/** Archive Grid template

 * @package bumblebee
 */

$archive_grid_analytics = 'data-analytics-metrics=\'{"name":"' . get_the_title() . '","module":"content navigation","position":"individual content well"}\'';

$excerpt = get_post_meta( $post_id, 'dek', true );
if ( ! $excerpt ) {
	$excerpt = get_post( $post_id )->post_excerpt;
	/*-- Use post content if neither Dek nor the Excerpt is present for a content type ---*/
	if ( ! $excerpt ) {
		$excerpt = get_post( $post_id )->post_content;
	}
}
$excerpt = strip_shortcodes( $excerpt );
?>
<div class="pure-u-1 pure-u-sm-1-4 category-card">
	<div class="single-recipe">
		<?php bumblebee_post_thumbnail( 'grid-thumbnail', 'individual content well', 'content navigation' ); ?>
		<div class="recipe-content">
			<?php the_title( '<h5 class="entry-title"><a ' . $archive_grid_analytics . ' href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
			<?php echo $excerpt; ?>
		</div>

	</div>
</div>
