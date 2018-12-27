<?php

require( 'listicle-debug.php' );

/**
 * Class Listicle_CPT
 * @link https://developer.wordpress.org/resource/dashicons/#list-view
 */
class Listicle_CPT extends Custom_Post_Type_Controller {
	const NAME             = 'Listicles';
	const SINGULAR_NAME    = 'Listicle';
	const DESCRIPTION      = 'Listicle post type';
	const MENU_ICON        = 'dashicons-list-view';
	const TEXT_DOMAIN      = 'listicle-post-type';
	const ADD_TO_ADMIN_BAR = true; // label => show_in_admin_bar
	const REST_BASE        = 'listicle';

	/**
	 * Default taxonomy setting override in your child for alternatives
	 */
	public function set_taxonomies() {
		$this->taxonomies = array(
			'category',
			'post_tag',
			'brand',
			'source',
			'mag_issue_date',
			'sponsor',
		);
	}

	public function debug_cpt(  $cpt = null, $msg = null  ) {
		Listicle_Debug::display_listicle_data( $cpt, $msg );
	}

}
