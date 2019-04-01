<?php
/**
 * Including file to load krux tracking.
 *
 * @package  Krux tracking
 */

add_action( 'wp_enqueue_scripts', 'load_krux_tracking' );

/**
 * To load the krux script.
 */
function load_krux_tracking() {

	$script_url  = 'js/krux-tracking.js';
	$script_slug = 'krux-tracking';
	$version     = '1.0.0';
	wp_register_script(
		$script_slug,
		plugins_url( $script_url, __FILE__ ),
		array( 'jquery' ),
		$version,
		true
	);
	wp_enqueue_script( $script_slug );
}
