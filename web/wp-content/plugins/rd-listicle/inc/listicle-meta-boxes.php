<?php
/**
 * Created by PhpStorm.
 * User: rpandit
 * Date: 10/20/2016
 * Time: 8:33 PM
 */

class Listicle_Meta_Boxes extends WP_Base {
	const VERSION       = '1.3';
	const SCRIPT_NAME   = 'category-validation';
	const SCRIPT_FILE   = 'js/category-validation.js';
	const IN_FOOTER     = true;
	public $depends     = array( 'jquery' );
	const FILE_SPEC     = __DIR__;

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_validate_category_script' ) );
		}
	}

	public function enqueue_validate_category_script() {
		wp_register_script(
			self::SCRIPT_NAME,
			$this->get_asset_url( self::SCRIPT_FILE ),
			$this->depends,
			self::VERSION,
			self::IN_FOOTER
		);
		if ( ! WP_Base::is_tip() ) {
			wp_enqueue_script( self::SCRIPT_NAME );
		}
	}

}
