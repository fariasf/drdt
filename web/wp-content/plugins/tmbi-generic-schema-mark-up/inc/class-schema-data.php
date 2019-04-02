<?php
/**
 * Fetching Schema data
 *
 * @package Fetching Schema data
 * This common schema data can be used for all the schema type in future
 */

/**
 * Class Schema_Data
 */
class Schema_Data {
	const LOGO_URL = 'https://www.constructionprotips.com/wp-content/uploads/sites/9/2017/11/cropped-cpt-logo-1.png';

	/**
	 * Get author data.
	 *
	 * @return array|string
	 */
	public static function get_the_authors() {
		global $post;
		$post_id = $post->ID;

		// Read the Post Author's Name
		// If Co-Author plugin is active get the list of authors else get the author.
		$creators_list = array();
		if ( function_exists( 'get_coauthors' ) ) {
			$authors = get_coauthors( $post_id );
			foreach ( $authors as $creator ) {
				$creator_tag = array(
					'@type' => 'Person',
					'name'  => $creator->display_name,
				);
				array_push( $creators_list, $creator_tag );
			}
		}
		if ( count( $creators_list ) > 0 ) {
			$author_list = $creators_list;
		} else {
			$creator_name         = $post->post_author;
			$creator_display_name = get_the_author_meta( 'display_name', $creator_name );
			// if username and display name exists.
			if ( $creator_name && $creator_display_name ) {
				$author_list = array(
					'@type' => 'Person',
					'name'  => $creator_display_name,
				);
			}
		}
		$site_name      = esc_html( get_bloginfo( 'name' ) );
		$default_author = array(
			'@type' => 'Organization',
			'name'  => $site_name,
		);
		return $author_list ?? $default_author;
	}

	/**
	 * Get Schema Description.
	 *
	 * @return mixed
	 */
	public static function get_schema_description() {

		global $post;
		$description = get_post_meta( $post->ID, 'dek', true );
		return $description;

	}

	/**
	 * Get schema Image.
	 *
	 * @return array|false|string
	 */
	public static function get_schema_image() {
		global $post;
		$post_id = $post->ID;
		$image   = '';

		if ( has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
		} else {
			$image = self::LOGO_URL;
		}
		return $image;

	}

	/**
	 * Get publisher details
	 */
	public static function get_publisher_details() {
		$site_name         = esc_html( get_bloginfo( 'name' ) );
		$publisher_default = array(
			'@type' => 'Organization',
			'name'  => $site_name,
			'logo'  => array(
				'@type'  => 'ImageObject',
				'url'    => self::LOGO_URL,
				'width'  => 198,
				'height' => 60,
			),
		);
		return $publisher_default;
	}
}
