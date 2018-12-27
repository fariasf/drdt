<?php
/**
 * Created by PhpStorm.
 * User: rpandit
 * Date: 9/11/2016
 * Time: 11:16 AM
 */
class Media_Attachments extends WP_Base {
	const VERSION       = '1.2';
	const IN_FOOTER     = true;
	const FIELD_LABEL   = 'Mute this image ?';
	const FIELD_HELP    = "When checked, this slide's image will not appear in the single-page view ?";
	const SCRIPT_NAME   = 'image-mute';
	const SCRIPT_FILE   = 'js/image-mute.js';
	public $post_types  = array( 'listicle' );
	public $depends     = array( 'jquery' );
	const FILE_SPEC     = __DIR__;

	public function __construct() {
		if ( is_admin() ) {
			add_filter( 'attachment_fields_to_edit', array( $this, 'add_field' ), 10, 2 );
			add_filter( 'attachment_fields_to_save', array( $this, 'save_field' ), 10, 2 );
			add_filter( 'manage_media_columns', array( &$this, 'manage_media_columns' ) );
			add_action( 'manage_media_custom_column', array( &$this, 'manage_media_custom_column' ), 10, 2 );
		}
		if ( ! is_admin() ) {
			add_filter( 'the_content', array( $this, 'mute_listicle_content_images_list_version' ), 20 );
			add_action( 'wp_enqueue_scripts', array( $this, 'listicle_enqueue_scripts' ) );
		}
	}

	public function listicle_enqueue_scripts() {
		if ( is_single() && WP_Base::is_listicle() ) {
			if ( in_array( get_post_type(), $this->post_types ) ) {
				wp_register_script(
					self::SCRIPT_NAME,
					$this->get_asset_url( self::SCRIPT_FILE ),
					$this->depends,
					self::VERSION,
					self::IN_FOOTER
				);
				wp_enqueue_script( self::SCRIPT_NAME );
			}
		}
	}

	public function add_field( $form_fields, $post ) {
		$isMute = (bool) get_post_meta( $post->ID, 'mute', true );
		$checked = ($isMute) ? 'checked' : '';

		$form_fields['isMute'] = array(
			'label' => self::FIELD_LABEL,
			'input' => 'html',
			'html' => "<input type='checkbox' {$checked} name='attachments[{$post->ID}][mute]' id='attachments[{$post->ID}][mute]' />",
			'value' => $isMute,
			'helps' => self::FIELD_HELP,
		);
		return ( $form_fields );
	}

	public function save_field($post, $attachment) {
		if ( empty( $attachment['mute'] ) ) {
			delete_post_meta( $post['ID'], 'mute' );
		}
		if ( isset( $attachment['mute'] ) && ! empty( $attachment['mute'] ) ) {
			$is_attachment_mute = get_post_meta( $post['ID'], 'mute', true );
			if ( $is_attachment_mute != esc_attr( $attachment['mute'] ) ) {
				update_post_meta( $post['ID'], 'mute', esc_attr( $attachment['mute'] ) );
			}
		}
		return ($post);
	}

	public function mute_listicle_content_images_list_version($content) {
		if ( is_single() ) {
			global $post;
			if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
				return ($content);
			}

			/**
			 * there is something intrinsically wrong with this code I have seen this line repeated
			 * numerous times in several different files
			 */
			$is_amp_page = RD_URL_Magick::is_amp_page( RD_URL_Magick::get_current_page_url() );
			if ( empty( $is_amp_page['endpoint'] ) ) {
				$selected_images = $attachment_ids = array();
				if ( ! empty( $post->post_content ) && in_array( $post->post_type, $this->post_types ) ) {
					if ( preg_match_all( '/<img[^>]+\>/i', $post->post_content, $matches, PREG_SET_ORDER ) ) {
						for ( $i = 0; $i < count( $matches ); $i++ ) {
							$image = $matches[ $i ][0];
							if ( false === strpos( $image, ' listicle-image-attr-list-version="' ) && preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) &&
								($attachment_id = absint( $class_id[1] ))
							) {

								/*
                                 * If exactly the same image tag is used more than once, overwrite it.
                                * All identical tags will be replaced later with 'str_replace()'.
								*/
								$selected_images[ $image ] = $attachment_id;
								// Overwrite the ID when the same image is included more than once.
								$attachment_ids[ $attachment_id ] = true;
							}
						}
						if ( count( $attachment_ids ) > 1 ) {
							/*
                             * Warm object cache for use with 'get_post_meta()'.
                            *
                            * To avoid making a database call for each image, a single query
                            * warms the object cache with the meta information for all images.
							*/
							update_meta_cache( 'post', array_keys( $attachment_ids ) );
						}
						//print_r($selected_images );die;
						foreach ( $selected_images as $image => $attachment_id ) {
							$image_meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
							$is_attachment_mute = get_post_meta( $attachment_id, 'mute', true );
							if ( $is_attachment_mute ) {
								$is_attachment_mute = 'mute';
								$image_meta['is_attachment_mute'] = $is_attachment_mute;
							}
							$content = str_replace( $image, $this->wp_image_add_mute_attr( $image, $image_meta, $attachment_id ), $content );
						}
					}
				}
				return ($content);
			}
		}
		return ($content);
	}
	public function wp_image_add_mute_attr( $image, $image_meta, $attachment_id ) {
		// Ensure the image meta exists.
		if ( empty( $image_meta['sizes'] ) ) {
			return ( $image );
		}

		$image_src = preg_match( '/src="([^"]+)"/', $image, $match_src ) ? $match_src[1] : '';
		list( $image_src ) = explode( '?', $image_src );

		// Return early if we couldn't get the image source.
		if ( ! $image_src ) {
			return ( $image );
		}

		// Bail early if an image has been inserted and later edited.
		if ( preg_match( '/-e[0-9]{13}/', $image_meta['file'], $img_edit_hash ) &&
				strpos( wp_basename( $image_src ), $img_edit_hash[0] ) === false ) {

					return ( $image );
		}

		if ( ! $image_meta ) {
			return ( $image );
		}
		$is_mute = false;
		if ( ! empty( $image_meta['is_attachment_mute'] ) ) {
			$is_mute = $image_meta['is_attachment_mute'];
		}

		if ( $is_mute && $image ) {
			// Format the 'id ' string and escape attributes.
			$attr = sprintf( ' id = listicle-image-attr-list-version ', esc_attr( $is_mute ) );
			// Add 'srcset' and 'sizes' attributes to the image markup.
			$image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $attr . ' />', $image );
		}

				return ( $image );
	}

	public function manage_media_columns($defaults) {
		$defaults['mute'] = 'Mute Attachments';
		return ( $defaults );
	}

	public function manage_media_custom_column($column, $post_id) {
		if ( $column == 'mute' ) {
			$is_mute = esc_attr( get_post_meta( $post_id, 'mute', true ) );
			$is_mute = ( $is_mute == 'on' ) ? 'Yes' : 'No';
			print ( $is_mute );
		}
	}
}
