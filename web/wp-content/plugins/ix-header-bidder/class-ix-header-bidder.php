<?php
/**
 * IX Header Bidder
 *
 *  @package     IX Header Bidder
 *  Plugin Name: IX Header Bidder.
 *  Version: 1.0.0 .
 *  Description: IX Header Bidder. <a href='https://readersdigest.atlassian.net/browse/WPDT-3432' target='_blank'>Read more at WPDT-3432</a>.
 *  Author: DRDT Team.
 *  Plugin URI: https://readersdigest.atlassian.net/browse/WPDT-3432.
 *  Text Domain: ix-header-bidder.
 */

/**
 * Including file for the settings.
 *
 * @file
 */
require 'inc/class-ix-settings.php';

/**
 *  Class Header Bidder.
 */
class IX_Header_Bidder {
	/**
	 * This will have the value of the script
	 *
	 * @var String
	 */
	private static $script_url = '';
	const VERSION              = '1.0.0';

	/**
	 *  Constructor.
	 */
	public static function init() {
		self::$script_url = IX_Settings::$script_url;
		if ( self::$script_url ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
			add_action( 'wp_footer', array( __CLASS__, 'remove_script_if_blocked' ), 1 );
		}
	}

	/**
	 *  Script enqueue.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'ix-header-bidder', self::$script_url, array(), self::VERSION, true );
	}

	/**
	 * Remove Header Bidder (for ?variant=noads).
	 */
	public static function remove_script_if_blocked() {
		if ( apply_filters( 'is_service_blocked', false, 'ix' ) ) {
			wp_dequeue_script( 'ix-header-bidder' );
		}
	}
}

add_action( 'init', array( 'IX_Header_Bidder', 'init' ) );
