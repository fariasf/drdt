<?php
/**
 * The template for video hub pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bumblebee
 */

/**
 * Including file for the video widgets.
 *
 * @file
 */
require_once 'widgets/class-hero-video-widget.php';
require_once 'widgets/class-video-carousel-widget.php';

/**
 * Video hub page sidebar.
 */
function video_hub_page_sidebar() {
	register_sidebar(
		array(
			'name'          => __( 'Video Hub Page', 'tmbi-theme-v3' ),
			'id'            => 'video-hub-page',
			'description'   => __( 'Video Hub Page', 'tmbi-theme-v3' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}
add_action( 'widgets_init', 'video_hub_page_sidebar' );

/**
 * Load our custom template
 */
if ( is_active_sidebar( 'video-hub-page' ) ) {
	add_filter( 'template_include', 'tmbi_theme_v3_custom_video_hub' );
	/**
	 *  Load custom video page.
	 *
	 * @param string $template template.
	 */
	function tmbi_theme_v3_custom_video_hub( $template ) {
		if ( is_post_type_archive( 'video' ) ) {
			$template = get_stylesheet_directory() . '/archive-videos.php';
		}
		return ( $template );
	}
}
