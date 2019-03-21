<?php
get_header();
/**
 * Add JS dependcies (Owl Carousel and styles).
 */
wp_register_style( 'owl-carousel-css', get_stylesheet_directory_uri() . '/owl-carousel-min.css', array(), '1.0.0' );
wp_enqueue_style( 'owl-carousel-css' );

wp_register_style( 'owl-theme-default', get_stylesheet_directory_uri() . '/owl-theme-default-min.css', array(), '1.0.0' );
wp_enqueue_style( 'owl-theme-default' );

wp_enqueue_script( 'owl-carousel-js', get_stylesheet_directory_uri() . '/js/util/owl-carousel-min.js', array( 'jquery' ), '1.0.0', false );

/**
 * Add custom JS
 */
wp_enqueue_script( 'video-hub-js', get_stylesheet_directory_uri() . '/js/video-hub.js', array( 'jquery' ), '1.0.0', false );


// Add landing page body class to the head
add_filter( 'body_class', 'tmbi_child_add_body_class' );
function tmbi_child_add_body_class( $classes ) {
	$classes[] = 'video-hub-archive';
	return $classes;
}


add_action( 'genesis_loop', function() {
	echo '<div class="contain">';
	dynamic_sidebar( 'video-hub-page' );
	echo '</div>';
});

get_footer();
