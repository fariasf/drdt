<?php
/** Archive Grid template

 * @package bumblebee
 */

$archive_grid_analytics = 'data-analytics-metrics=\'{"name":"' . get_the_title() . '","module":"content navigation","position":"individual content well"}\'';

$excerpt = get_post_meta( $post_id, 'dek', true );
if ( ! $excerpt ) {
	$excerpt = get_the_excerpt( $post_id );
}

?>
<div class="pure-u-1 pure-u-sm-1-4 category-card">
	<div class="single-recipe">
		<?php bumblebee_post_thumbnail( 'grid-thumbnail', 'individual content well', 'content navigation' ); ?>
		<div class="recipe-content">
			<?php the_title( '<h5 class="entry-title"><a ' . $archive_grid_analytics . ' href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
			<div class="recipe-excerpt"><?php echo wp_kses_post( $excerpt ); ?></div>
		</div>

	</div>
</div>
