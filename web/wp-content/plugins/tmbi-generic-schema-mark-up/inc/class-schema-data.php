<?php
/**
 * Fetching Schema data
 *
 * @package Fetching Schema data
 */

/**
 * Class Schema_Data
 */
class Schema_Data {
	const DEFAULT_AUTHOR = array(
		'@type' => 'Organization',
		'name'  => 'Construction Pro Tips',
	);

	/**
	 * Site Logo Url.
	 *
	 * @var string
	 */
	public $site_logo_url = 'https://www.constructionprotips.com/wp-content/uploads/sites/9/2017/11/cropped-cpt-logo-1.png';

	/**
	 * Get author data.
	 *
	 * @return array|string
	 */
	public function get_the_authors() {
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
		return $author_list ?? static::DEFAULT_AUTHOR;
	}

	/**
	 * Get Schema Description.
	 *
	 * @return mixed
	 */
	public function get_schema_description() {

		global $post;
		$description = get_post_meta( $post->ID, 'dek', true );
		return $description;

	}

	/**
	 * Get schema Image.
	 *
	 * @return array|false|string
	 */
	public function get_schema_image() {
		global $post;
		$post_id = $post->ID;
		$image   = '';

		if ( has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
		} else {
			$image = $this->site_logo_url;
		}
		return $image;

	}
}
