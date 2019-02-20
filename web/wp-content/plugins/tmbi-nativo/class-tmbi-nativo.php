<?php
/**
 * TMBI Nativo
 *
 * @package TMBI Nativo
Plugin Name: TMBI Nativo
Version: 1.0
Description: Adds the nativo script to the header for TOH,FHM <a href='https://readersdigest.atlassian.net/browse/WPDT-4699' target='_blank'>Read more at WPDT-4699 ...</a>
Author: DRDT Team
Text Domain: tmbi-nativo
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause
 */

/**
 * Including file for the settings.
 *
 * @file
 */
require 'inc/class-tmbi-nativo-in-articles.php';

/**
 *  Class TMBI_Nativo.
 */
class TMBI_Nativo {
	const VERSION            = '1.0';
	const NATIVO_CUSTOM_TYPE = 'nativo_custom_post';

	/**
	 * Add hooks and filters for this module.
	 *
	 * @action init
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_nativo_post_type' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_nativo_page' ) );
		// Register custom type nativo that will be used to create template for nativo custom content.
		add_action( 'template_redirect', array( __CLASS__, 'include_print_recipe_template' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'render_nativo_script' ) );
		add_filter( 'script_loader_tag', array( __CLASS__, 'nativo_async_tag' ), 10, 3 );
	}

	/**
	 * Add nativo script to all the pages.
	 */
	public function render_nativo_script() {
		wp_enqueue_script( 'nativo-script', '//s.ntv.io/serve/load.js', array( 'jquery' ), '1.0', true );
	}

	/**
	 * Add async to the nativo script
	 *
	 * @param string $tag script tag.
	 * @param string $handle script handle.
	 * @param string $src script source.
	 * @return string
	 */
	public function nativo_async_tag( $tag, $handle, $src ) {
		if ( 'nativo-script' !== $handle ) {
			return $tag;
		}
		$async_tag = "<script src='$src' async></script>";
		return $async_tag;
	}

	/**
	 * Template redirect to page for print.
	 */
	public function include_print_recipe_template() {
		if ( ( get_query_var( self::NATIVO_CUSTOM_TYPE ) === 'nativo-content' ) && is_singular( self::NATIVO_CUSTOM_TYPE ) ) {
			add_filter(
				'template_include',
				function () {
					return locate_template( array( 'page.php' ) );
				}
			);
		}
	}

	/**
	 * Instantiate the methods.
	 */
	public static function register_nativo_post_type() {
		$args = array(
			'public'              => false,
			'publicly_queryable'  => true,
			'label'               => 'Nativo Sponsored Post',
			'exclude_from_search' => true,
			'can_export'          => false,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
		);

		register_post_type( self::NATIVO_CUSTOM_TYPE, $args );
	}

	/**
	 * Check if ad full page is created and OK. If not, recreate it.
	 *
	 * @action admin_init
	 */
	public function add_nativo_page() {
		$page_id = get_option( 'nativo_template_post_id', false );

		if ( ! $page_id ) {
			self::nativo_create_page();
		}
		$page = get_post( $page_id );

		if ( null === $page || strcmp( $page->post_status, 'publish' ) !== 0 || strstr( $page->post_name, 'nativo-content' ) === false || strcmp( $page->post_type, self::NATIVO_CUSTOM_TYPE ) !== 0 || strcmp( $page->post_title, 'NATIVO PAGE' ) !== 0 || strcmp( $page->post_content, '<span class="nativo_body"></span>' ) !== 0 ) {
			self::nativo_create_page();
		}
	}

	/**
	 * Create the ad template post.
	 */
	public static function nativo_create_page() {

		// Remove any post with reserved post_name.
		$args = array(
			'name'      => 'nativo-content',
			'post_type' => self::NATIVO_CUSTOM_TYPE,
		);

		$query = new WP_Query( $args );

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				wp_delete_post( $post->ID, false );
			}
		}

		$template_post    = array(
			'post_title'     => 'NATIVO PAGE',
			'post_content'   => '<span class="nativo_body"></span>',
			'post_type'      => self::NATIVO_CUSTOM_TYPE,
			'post_status'    => 'publish',
			'post_name'      => 'nativo-content',
			'post_date'      => '2000-01-01 00:00:00',
			'post_date_gmt'  => '2000-01-01 00:00:00',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		);
		$template_post_id = wp_insert_post( $template_post );

		update_option( 'nativo_template_post_id', $template_post_id );
	}
}

$tmbi_nativo = new TMBI_Nativo();
