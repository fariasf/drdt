<?php
/** Featured template

 * @package bumblebee
 */

$home_featured_analytics = 'data-analytics-metrics=\'{"name":"' . get_the_title() . '","module":"content navigation","position":"marquee"}\'';
$featured_heading_text1   = get_theme_mod( 'bumblebee_home_featured_text1' );
$featured_url1            = get_theme_mod( 'bumblebee_home_featured_url1' );
$featured_heading_text2   = get_theme_mod( 'bumblebee_home_featured_text2' );
$featured_url2            = get_theme_mod( 'bumblebee_home_featured_url2' );

?>
<div class="pure-u-1 pure-u-sm-1-5">
	<div class="single-recipe single-item">
		<img src="<?php echo esc_html( get_theme_mod( 'bumblebee_home_featured_image1' ) ); ?>" alt="">
		<div class="recipe-content">
			<a data-analytics-metrics='{"name":"<?php echo esc_url( get_theme_mod( '$featured_url1' ) ); ?>","module":"content navigation","position":"marquee"}' href="<?php echo esc_url( $featured_url1 ); ?>"><h5><?php echo esc_html( $featured_heading_text1 ); ?></h5></a>
		</div>

	</div>
</div>
<div class="pure-u-1 pure-u-sm-1-5">
	<div class="single-recipe single-item">
		<img src="<?php echo esc_html( get_theme_mod( 'bumblebee_home_featured_image2' ) ); ?>" alt="">
		<div class="recipe-content">
			<a data-analytics-metrics='{"name":"<?php echo esc_url( get_theme_mod( '$featured_url2' ) ); ?>","module":"content navigation","position":"marquee"}' href="<?php echo esc_url( $featured_url2 ); ?>"><h5><?php echo esc_html( $featured_heading_text2 ); ?></h5></a>
		</div>

	</div>
</div>
