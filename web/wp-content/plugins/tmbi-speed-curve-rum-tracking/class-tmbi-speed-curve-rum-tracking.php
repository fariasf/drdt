<?php
/**
 * Plugin Name: TMBI SpeedCurve RUM Tracking
 * Plugin URI: https://readersdigest.atlassian.net/browse/DRDT-148
 * Description: This plugins provide us Real-Time Performance Measurement using SpeedCurve rum tracking.
 * Version: 1.0.0
 * Author: Prasanth Bendra
 * License: GPL2
 */

class TMBI_SPEED_CURVE_RUM_TRACKING {

	const VERSION 				= '1.0.0';
	const DEPENDS 				= 'jquery';
	const IN_FOOTER 			= true;
	const PRIORITY 				= 10;
	const SCRIPT_NAME 			= 'speed-curve';
	const SCRIPT_FILE 			= 'js/speed-curve.js';
	const SC_SCRIPT_URL 		= 'https://cdn.speedcurve.com/js/lux.js?id=128112127';
	const SC_SCRIPT_SLUG 		= 'speedcurve-base';
	const CUSTOM_SCRIPT_URL     = 'js/custom-script.js';
	const CUSTOM_SCRIPT_SLUG    = 'custom-script';

	/**
	 * Constructor.
	 * 
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'tmbi_speed_curve_script' ), 100001 );
	}

	/**
	 * Load all the scripts.
	 * 
	 * @return void
	 */
	public function tmbi_speed_curve_script() {
		wp_register_script(
				self::SCRIPT_NAME, plugins_url( self::SCRIPT_FILE, __FILE__ ), array( self::DEPENDS ), self::VERSION, self::IN_FOOTER
		);
		wp_enqueue_script( self::SCRIPT_NAME );

		wp_register_script(
				self::SC_SCRIPT_SLUG, self::SC_SCRIPT_URL, [], self::VERSION, self::IN_FOOTER
		);
		wp_enqueue_script( self::SC_SCRIPT_SLUG );

		wp_register_script(
			self::CUSTOM_SCRIPT_SLUG, plugins_url( self::CUSTOM_SCRIPT_URL, __FILE__ ), array( 'jquery', self::SC_SCRIPT_SLUG, self::SCRIPT_NAME ), self::VERSION, self::IN_FOOTER
		);
		wp_localize_script( self::CUSTOM_SCRIPT_SLUG, 'LUX_label', $this->get_lux_label() );
		wp_enqueue_script( self::CUSTOM_SCRIPT_SLUG );
	}

	/**
	 * Get lux labels for each page.
	 * 
	 * @return string
	 */
	public function get_lux_label() {
		$page_type = page_type();

		$labels = array(
			'article'       => 'Article',
			'homepage'      => 'HomePage',
			'search'        => 'Search',
			'slideshow'     => 'Slideshow',
			'card'          => 'Slideshow',
			'slide'         => 'Slideshow',
			'listicle'      => 'Listicle',
			'category'      => 'Hub Page',
			'archive'       => 'Hub Page',
			'tag'           => 'Hub Page',
			'page'          => 'Content Page',
		);

		$result = isset( $labels[$page_type] )? $labels[$page_type] : null;
		return $result;
	}

}

new TMBI_SPEED_CURVE_RUM_TRACKING();
