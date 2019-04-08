<?php
/**
 * BlogPosting Markup Settings
 *
 * @package     BlogPosting Markup Settings
 */

/**
 * Class BlogPosting_Schema_Markup
 */
class BlogPosting_Schema_Markup {
	const CONTEXT        = 'https://schema.org';
	const BLOG_POST_TYPE = 'BlogPosting';
	const SMT            = "\n<script type=\"application/ld+json\">\n%s\n</script>";

	/**
	 * This will have the value of blogpostdata
	 *
	 * @var array
	 */
	public static $blogposting_data;

	/**
	 * Creating the array format of blogposting schema
	 */
	public static function blog_posting_schema_mark_up() {
		if ( ! is_singular() ) {
			return;
		}
		global $post;
		$post_id = $post->ID;
		// Date and time at which the post gets created.
		$post_date = get_the_date( 'Y-m-d' );

		// Date and time at which the post gets modified.
		$modified_date = get_the_modified_date( 'Y-m-d' );
		$description   = Schema_Data::get_schema_description();
		// Get image details.
		$image             = Schema_Data::get_schema_image();
		$post_image        = '';
		$post_image_width  = '';
		$post_image_height = '';

		if ( ! empty( $image ) ) {
			$post_image        = $image[0];
			$post_image_width  = $image[1];
			$post_image_height = $image[2];
		} else {
			$post_image = $image;
		}
		$post_url               = wp_get_canonical_url();
		$title                  = $post->post_title;
		$author_details         = Schema_Data::get_the_authors();
		$publisher_details      = Schema_Data::get_publisher_details();
		$site_name              = esc_html( get_bloginfo( 'name' ) );
		$default_schema         = array(
			'@context'         => self::CONTEXT,
			'@type'            => self::BLOG_POST_TYPE,
			'headline'         => $title,
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => $post_url,
			),
			'description'      => $description,
			'datePublished'    => $post_date,
			'dateModified'     => $modified_date,
			'author'           => $author_details,
			'image'            => array(
				'@type'  => 'ImageObject',
				'url'    => $post_image,
				'height' => $post_image_height,
				'width'  => $post_image_width,
			),
			'publisher'        => $publisher_details,
		);
		self::$blogposting_data = apply_filters( 'blogposting_schema_marup', $default_schema );
		if ( ! empty( self::$blogposting_data ) ) {
			$json         = wp_json_encode( self::$blogposting_data, JSON_PRETTY_PRINT );
			$allowed_html = [
				'script' => [
					'type' => [],
				],
			];
			printf( wp_kses( self::SMT . PHP_EOL, $allowed_html ), wp_kses_post( $json ) );
		}
	}
}