<?php
// Add a custom widgetized video hub page, to replace the default archive template

require_once( 'widgets/class-hero-video.php' );
require_once( 'widgets/carousel.php' );

/**
 * Register our sidebar
 */
function video_hub_page_sidebar() {
	register_sidebar(
		array(
			'name' => __( 'Video Hub Page', 'tmbi-theme-v3' ),
			'id' => 'video-hub-page',
			'description' => __( 'Video Hub Page', 'tmbi-theme-v3' ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		)
	);
}
add_action( 'widgets_init', 'video_hub_page_sidebar' );

/**
 * Load our custom template
 */
if ( is_active_sidebar( 'video-hub-page' ) ) {
	add_filter( 'template_include', 'tmbi_theme_v3_custom_video_hub' );
	function tmbi_theme_v3_custom_video_hub( $template ) {
		if ( is_post_type_archive( 'video' ) ) {
			$template = get_stylesheet_directory() . '/archive-videos.php';
		}
		return ( $template );
	}
}
