<?php

class Listicle_Debug extends Debug {
	const DEBUG = false; // turn on debugging for intrinsic testing (required for CMS)

	/**
	 * @return bool
	 */
	public static function is_listicle_debug_active() {
		return( ( isset( $_GET['debug'] ) && $_GET['debug'] === 'listicle' ) || self::DEBUG );
	}

	/**
	 * @param $listicle_data
	 * @param null $additiona_msg
	 */
	public static function print_listicle_data( $listicle_data, $additional_msg = null ) {
		if ( self::is_listicle_debug_active() ) {
			print('<h2>Listicle obj</h2>' . PHP_EOL);
			if ( $additional_msg ) {
				print( $additional_msg . '<br>' .PHP_EOL );
			}
			if ( is_array( $listicle_data ) ) {
				var_dump( $listicle_data );
			} else {
				print( $listicle_data );
			}
			print('<h3>Listicle obj</h3>' . PHP_EOL);
			exit;
		}
	}

	/**
	 * @param $listicle_data
	 * @param $prefix_msg
	 */
	public static function display_listicle_data( $listicle_data, $prefix_msg ) {
		if ( self::is_listicle_debug_active() && is_admin() ) {
			$exported_msg = var_export( $listicle_data, true );
			$am = new Admin_Message( $prefix_msg . $exported_msg );
			$am->display_admin_normal_message();
		} elseif ( self::is_listicle_debug_active() && is_page( 'debug' ) ) {
			// http://dev.rdnap/debug/ example
			print( $prefix_msg . '<br>' . PHP_EOL );
			var_dump( $listicle_data );
		}
	}
}
