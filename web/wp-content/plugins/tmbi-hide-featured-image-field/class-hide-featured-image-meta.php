<?php
/**
Hide Featured Image Meta

@package     Hide Featured Image Meta
Plugin Name: TMBI Hide Featured Image
Version: 1.0.0
Description: Create ability to hide featured image on posts. DRDT-80
Author: Samuel
Text Domain: tmbi-hide-featured-image-field
 */

/**
 *  Class Hide featured image meta.
 */
class Hide_Featured_Image_Meta {
	const META_BOX_ID    = 'hide_featured_image';
	const META_BOX_TITLE = 'Hide Feature Image';

	/**
	 *  Init.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'admin_init' ) );
		add_action( 'save_post', array( __CLASS__, 'hide_featured_save_metabox' ) );
	}


	/**
	 *  Add the metabox.
	 */
	public static function admin_init() {
		add_meta_box(
			self::META_BOX_ID,
			self::META_BOX_TITLE,
			array( __CLASS__, 'hide_featured_image_metabox' ),
			array( 'post', 'page' ),
			'side',
			'default'
		);
	}

	/**
	 * Use to add the hide feautred image metabox.
	 *
	 * @param string $post post content.
	 */
	public static function hide_featured_image_metabox( $post ) {
		$meta = get_post_meta( $post->ID );
		wp_nonce_field( self::META_BOX_ID . '_meta_box', self::META_BOX_ID . '_meta_box_nonce' );

		?>
		<p>
			<label>
				<input type="checkbox" name="hide_featured_image" value="1"
					<?php
					if ( isset( $meta['hide_featured_image'] ) ) {
						checked( $meta['hide_featured_image'][0], '1' ); }
					?>
				/>
				<?php esc_attr_e( 'Hide featured image' ); ?>
			</label>
		</p>
		<?php

	}

	/**
	 * Use to save the hide feautred image metabox.
	 *
	 * @param int $post_id post id.
	 */
	public static function hide_featured_save_metabox( $post_id ) {
		if ( ! isset( $_POST['hide_featured_image_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['hide_featured_image_meta_box_nonce'] ), 'hide_featured_image_meta_box' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		}

		if ( isset( $_POST['hide_featured_image'] ) ) {
			update_post_meta( $post_id, 'hide_featured_image', '1' );
		} else {
			update_post_meta( $post_id, 'hide_featured_image', '' );
		}
	}
}

add_action( 'init', array( 'Hide_Featured_Image_Meta', 'init' ) );



