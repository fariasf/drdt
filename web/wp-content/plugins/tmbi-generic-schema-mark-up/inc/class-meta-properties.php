<?php
/**
 * Custom Meta properties Settings
 *
 * @package     Custom meta properties
 */

/**
 * Class Meta_Properties
 */
class Meta_Properties {
	const MPPN  = '<meta name="pageName" content = "%s">';
	const MPD   = '<meta name="description" content = "%s">';
	const MPCID = '<meta name="contentId" content = "%s">';

	/**
	 * Custom meta properties
	 */
	public static function custom_meta_properties() {
		global $post;

		$page_title = $post->post_title;
		$p_id       = $post->ID;
		$p_excerpt  = $post->post_excerpt;
		$yoast_desc = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );

		if ( $yoast_desc ) {
			$page_description = $yoast_desc;
		} elseif ( ! $yoast_desc && $p_excerpt ) {
			$page_description = $p_excerpt;
		} else {
			$page_description = get_the_excerpt( $post );
		}
		$allowed_html = [
			'meta' => [
				'name'    => [],
				'content' => [],
			],
		];
		$output       = sprintf( self::MPPN, $page_title ) . PHP_EOL;
		$output      .= sprintf( self::MPD, htmlentities( wp_strip_all_tags( $page_description ) ) ) . PHP_EOL;
		$output      .= sprintf( self::MPCID, $p_id ) . PHP_EOL;
		print( wp_kses( $output, $allowed_html ) );
	}
}
