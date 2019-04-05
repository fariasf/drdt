<?php
/**
 * Opengraph twitter
 *
 * @package WPSEO Opengraph Twitter Title
 *  This is for changing the title and desc.
 */

/**
 * Class WPSEO_Opengraph_Twitter_Title
 */
class WPSEO_Opengraph_Twitter_Title {
	/**
	 * This will have the value of title
	 *
	 * @var string
	 */
	public static $meta_title = '';

	/**
	 * This will have the value of description
	 *
	 * @var string
	 */
	public static $meta_desc = '';

	/**
	 * Init the opengraph twitter title and desc.
	 */
	public static function init() {
		add_filter( 'wpseo_opengraph_title', array( __CLASS__, 'tmbi_override_og_twitter_pinterest_title' ), 9 );
		add_filter( 'wpseo_twitter_title', array( __CLASS__, 'tmbi_override_og_twitter_pinterest_title' ), 9 );
		add_filter( 'wpseo_opengraph_desc', array( __CLASS__, 'tmbi_override_og_description' ), 11 );
	}

	/**
	 * To override the og twitter pinterest title.
	 *
	 * @param string $title twitter title.
	 */
	public static function tmbi_override_og_twitter_pinterest_title( $title ) {
		$sf_og_text = '';
		global $post;
		if ( is_singular() ) {
			$socialflow_fb_text = get_post_meta( get_the_ID(), 'sf_title_facebook', true );

			if ( ! $socialflow_fb_text ) {
				$socialflow_fb_text = $post->post_title;
			}
			$sf_og_text = $socialflow_fb_text;
		}
		self::$meta_title = $sf_og_text ?? $title;
		return self::$meta_title;
	}

	/**
	 * To override the og twitter pinterest description.
	 *
	 * @param string $title twitter description.
	 */
	public static function tmbi_override_og_description( $title ) {
		$sf_og_desc = '';
		global $post;
		if ( is_singular() ) {
			$socialflow_fb_text = get_post_meta( get_the_ID(), 'sf_description_facebook', true );

			if ( '' === $socialflow_fb_text ) {
				$post_excerpt = wp_strip_all_tags( $post->post_excerpt );
				if ( '' === $post_excerpt ) {
					$sf_og_desc = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
				} else {
					$sf_og_desc = trim( $post_excerpt );
				}
			} else {
				$sf_og_desc = $socialflow_fb_text;
			}
		}
		self::$meta_desc = $sf_og_desc ?? $title;
		return self::$meta_desc;
	}

}
add_action( 'init', array( 'WPSEO_Opengraph_Twitter_Title', 'init' ) );
