<?php
/** Hero template

 * @package bumblebee
 */

$home_hero_analytics  = 'data-analytics-metrics=\'{"name":"' . get_the_title() . '","module":"content navigation","position":"marquee"}\'';
$marquee_heading_text = get_theme_mod( 'bumblebee_home_marquee_heading_text' );
$marquee_textarea     = get_theme_mod( 'bumblebee_home_marquee_textarea' );
$marquee_url          = get_theme_mod( 'bumblebee_home_marquee_url' );
?>
<div class="pure-u-1 pure-u-sm-3-5">
	<div class="hero-container ">
		<div class="pure-g ">
			<a data-analytics-metrics='{"name":"<?php echo esc_url( get_theme_mod( 'bumblebee_home_marquee_url' ) ); ?>","module":"content navigation","position":"marquee"}' href="<?php echo esc_url( $marquee_url ); ?>" class="pure-u-sm-2-5 hero-image">
				<img src="<?php echo esc_html( get_theme_mod( 'bumblebee_home_marquee_image' ) ); ?>" alt="">
			</a>
			<div class="pure-u-sm-3-5">
				<div class="hero-text">
					<a data-analytics-metrics='{"name":"<?php echo esc_url( get_theme_mod( 'bumblebee_home_marquee_url' ) ); ?>","module":"content navigation","position":"marquee"}' href="<?php echo esc_url( $marquee_url ); ?>"><h3><?php echo esc_html( $marquee_heading_text ); ?></h3></a>
					<div class="hero-excerpt"><?php echo esc_html( $marquee_textarea ); ?></div>
					<a data-analytics-metrics='{"name":"<?php echo esc_url( get_theme_mod( 'bumblebee_home_marquee_url' ) ); ?>","module":"content navigation","position":"marquee"}' href="<?php echo esc_url( $marquee_url ); ?>" class="read-more">read more</a>
				</div>
			</div>
		</div>
	</div>
</div>
