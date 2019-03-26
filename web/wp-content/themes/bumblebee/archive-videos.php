<?php
/**
 * The template for video hub pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bumblebee
 */

add_filter(
	'ad_unit_path_2',
	function () {
		return 'video';
	}
);

add_filter(
	'ad_unit_path_3',
	function () {
		return 'videocategory';
	}
);
get_header();
/**
 * Add JS dependcies (Owl Carousel and styles).
 */
wp_enqueue_style( 'video-hub', get_stylesheet_directory_uri() . '/video.css', array(), '1.0.0' );
wp_register_style( 'owl-carousel-css', get_stylesheet_directory_uri() . '/css/owl-carousel-min.css', array(), '1.0.0' );
wp_enqueue_style( 'owl-carousel-css' );

wp_register_style( 'owl-theme-default', get_stylesheet_directory_uri() . '/css/owl-theme-default-min.css', array(), '1.0.0' );
wp_enqueue_style( 'owl-theme-default' );

wp_enqueue_script( 'owl-carousel-js', get_stylesheet_directory_uri() . '/js/util/owl-carousel-min.js', array( 'jquery' ), '1.0.0', false );

/**
 * Add custom JS
 */
wp_enqueue_script( 'video-hub-js', get_stylesheet_directory_uri() . '/js/src/video-hub.js', array( 'jquery' ), '1.0.0', false );

wp_enqueue_script( 'video-hover-js', get_stylesheet_directory_uri() . '/js/src/video-hover-play.js', array( 'jquery' ), '1.0.0', false );


echo '<div class="contain">';
dynamic_sidebar( 'video-hub-page' );
echo '</div>';

get_footer();
