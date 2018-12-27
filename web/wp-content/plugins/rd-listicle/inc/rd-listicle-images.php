<?php
/*
Plugin Name: RD Listicle Images
Version: 1.0
Description: Add Custom Image Sizes for Listicles <a href='https://readersdigest.atlassian.net/browse/WPDT-3138' target='_blank'>Read more at WPDT-3138 ...</a>
Author: Chris Hurst
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause
*/

class RD_Listicle_Image_Sizes extends WP_Base {

	public function __construct() {

		add_action( 'init', array( $this, 'rd_listicle_image_sizes' ) );
		add_filter( 'image_size_names_choose', array( $this, 'rd_listicle_images' ) );

		// Better Image Credits breaks alignment for images. It runs with priority 20.
		add_filter( 'the_content', array( $this, 'preserve_image_alignment_for_listicles' ), 19 );
		add_filter( 'the_content', array( $this, 'restore_image_alignment_for_listicles' ), 21 );

	}

	public function rd_listicle_image_sizes() {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'listicle-full', 760, 506, true );
		add_image_size( 'listicle-full-medium', 767, 514, true );
		add_image_size( 'listicle-full-small', 600, 400, true );
		add_image_size( 'listicle-half', 380, 254, true );
		add_image_size( 'listicle-half-medium', 384, 258, true );
		add_image_size( 'listicle-half-small', 600, 400, true );
	}


	public function rd_listicle_images($sizes) {
		$addsizes = array(
			'listicle-full' => __( 'Listicle Full Width' ),
			'listicle-half' => __( 'Listicle Half Width' ),
		);
		$newsizes = array_merge( $sizes, $addsizes );
		return $newsizes;
	}

	public function preserve_image_alignment_for_listicles( $content ) {
		global $post;

		if ( isset( $post ) && $post->post_type === 'listicle' ) {
			$aligned_images = '/(<img.*?\bclass=["|\'].*?)(align(left|right))(.*?>)/';
			$with_extra_class = '$1$2 _rda$3$4';

			// For aligned images, add an extra class _rdaleft or _rdaright, preserving the original to let BIC do its job
			$content = preg_replace( $aligned_images, $with_extra_class, $content );
		}

		return $content;
	}

	public function restore_image_alignment_for_listicles( $content ) {
		global $post;

		if ( isset( $post ) && $post->post_type === 'listicle' ) {
			$with_extra_class = '/(<img.*?\bclass=["|\'].*?)(_rda(left|right))(.*?>)/';
			$restored_alignment = '$1align$3$4';

			// Now replace _rdaleft with alignleft and _rdaright with alignright
			$content = preg_replace( $with_extra_class, $restored_alignment, $content );
			preg_match_all( '/(<img[^>]+>)/i', $content, $images_array );
			if ( isset( $images_array ) ) {
				$content = $this->get_images_with_srcset( $images_array[0], $content );
			}
		}
		return $content;
	}

	public function get_images_with_srcset( $images, $content ) {
		$wp_image_id_pattern = '/wp-image-(\d*)/';
		foreach ( $images as $image ) {
			if ( strpos( $image, 'srcset="' ) == false ) {
				// get the class string
				preg_match( '/class="([^"]+)/i', $image, $imgclass );
				if ( ! empty( $imgclass ) ) {
					preg_match( $wp_image_id_pattern, $imgclass[1], $matches );
					if ( ! empty( $matches ) ) {
						$srcsets    = wp_get_attachment_image_srcset( $matches[1], array( 'listicle-full', 'listicle-full-medium', 'listicle-full-small', 'listicle-half', 'listicle-half-medium', 'listicle-half-small' ) );
						if ( $srcsets ) {
							$srcset     = ' srcset="'.$srcsets.'" />';
							$img_tag    = str_ireplace( '/>', $srcset, $image );
							$content    = str_replace( $image, $img_tag, $content );
						}
					}
				}
			}
		}
		return $content;
	}

}
