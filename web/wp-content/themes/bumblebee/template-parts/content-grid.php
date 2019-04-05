<?php
/** Content grid template

 * @package bumblebee
 */

?>
<div class="single-container pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
	<div class="single-recipe">
		<?php bumblebee_post_thumbnail( 'grid-thumbnail', 'individual content well', 'content navigation' ); ?>
		<div class="recipe-content">
			<?php the_title( '<h4 class="entry-title"><a data-analytics-metrics=\'{"name":"' . get_the_title() . '","module":"content navigation","position":"individual content well"}\' href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
		</div>
	</div>
</div>
