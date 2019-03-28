<?php
/**
 * BlogPosting Markup Settings
 *
 * @package     BlogPosting Markup Settings
 *  This is for Blogposting schema mark up.
 */

/**
 * Class BlogPosting_Schema_Markup
 */
class BlogPosting_Schema_Markup {
	const CONTEXT        = 'https://schema.org';
	const BLOG_POST_TYPE = 'BlogPosting';
	const SMT            = "\n<script type=\"application/ld+json\">\n%s\n</script>";

	/**
	 * This will have the value of the logo url
	 *
	 * @var String
	 */
	public $site_logo_url = 'https://www.constructionprotips.com/wp-content/uploads/sites/9/2017/11/cropped-cpt-logo-1.png';

	/**
	 * This will have the value of blogpostdata
	 *
	 * @var array
	 */
	public $blogposting_data;

	/**
	 * Blogposting constructor.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'wp_head', array( $this, 'blog_posting_schema_mark_up' ) );
		}
	}

	/**
	 * Creating the array format of blogposting schema
	 */
	public function blog_posting_schema_mark_up() {
		if ( is_archive() || is_front_page() || is_home() || is_page() ) {
			return;
		}
		$schema_data = new Schema_Data();
		global $post;
		$post_id = $post->ID;
		// Date and time at which the post gets created.
		$post_date = get_the_date( 'Y-m-d' );

		// Date and time at which the post gets modified.
		$modified_date = get_the_modified_date( 'Y-m-d' );
		$description   = $schema_data->get_schema_description();
		// Get image details.
		$image             = $schema_data->get_schema_image();
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
		$publisher_details      = Schema_Data::DEFAULT_AUTHOR;
		$author_details         = $schema_data->get_the_authors();
		$this->blogposting_data = array(
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
			'publisher'        => array(
				'@type' => $publisher_details['@type'],
				'name'  => $publisher_details['name'],
				'logo'  => array(
					'@type'  => 'ImageObject',
					'url'    => $this->site_logo_url,
					'width'  => 198,
					'height' => 60,
				),
			),
		);
		if ( ! empty( $this->blogposting_data ) ) {
			$json         = wp_json_encode( $this->blogposting_data, JSON_PRETTY_PRINT );
			$allowed_html = [
				'script' => [
					'type' => [],
				],
			];
			printf( wp_kses( self::SMT . PHP_EOL, $allowed_html ), wp_kses_post( $json ) );
		}
	}
}
