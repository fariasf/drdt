<?php
/*
Plugin Name: RD Listicle Video
Version: 1.0
Description: Add a video to listicles
Plugin URI: https://readersdigest.atlassian.net/browse/WPDT-3392
Author: Chris Hurst, Facundo Farias
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause
*/

class RD_Listicle_video extends WP_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'add_meta_boxes', array( $this, 'listicle_embed_video_metabox' ) );
		add_action( 'save_post', array( $this, 'listicle_video_save_metabox' ) );
		add_action( 'rd_listicle_video', array( $this, 'embedded_video_player' ) );

	}

	// Front End Render
	public function embedded_video_player() {
		$embed_video = get_post_meta( get_the_ID(), '_listicle_video', true );

		if ( ! empty( $embed_video ) ) {
			echo '<div class="listicle-video-wrapper">';
			echo do_shortcode( $embed_video );
			echo '</div>';
		}
	}

	// Add the metabox
	public function listicle_embed_video_metabox() {
		add_meta_box( 'video_embed_section', 'Video Embed', array( $this, 'listicle_embed_video_calback' ), 'listicle', 'side', 'high', null );
	}

	// Print the metabox content
	public function listicle_embed_video_calback( $post ) {

		// Create a nonce field.
		wp_nonce_field( 'listicle_video_embed', 'listicle_video_embed_nonce' );

		// Retrieve a previously saved value, if available.
		$url = get_post_meta( $post->ID, '_listicle_video', true );

		// Create the metabox field mark-up.
		echo '<p>';
			echo '<label>Video URL</label><textarea rows="3" style="width: 100%;" type="text" name="listicle_video" size="10" class="regular-text">';
			echo esc_html( $url );
			echo'</textarea>';
			echo 'Use the [rd-video] shortcode. Example: <kbd>[rd-video id="1234"]</kbd>.<br>You can add text before or after the [rd-video] shortcode.';
		echo '</p>';
	}


	// Save the metabox.
	public function listicle_video_save_metabox( $post_id ) {
		// Check if our nonce is set & valid
		if ( ! empty( $_POST['listicle_video_embed'] ) ) {
			if ( ! isset( $_POST['listicle_video_embed_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['listicle_video_embed'] ) ) ) {
					$nonce = sanitize_text_field( wp_unslash( $_POST['listicle_video_embed_nonce'] ) );
			}
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check for and sanitize user input.
		if ( ! isset( $_POST['listicle_video'] ) ) {
			return;
		}

		$url = esc_html( $_POST['listicle_video'] );

		// Update the meta fields in the database, or clean up after ourselves.
		if ( empty( $url ) ) {
			delete_post_meta( $post_id, '_listicle_video' );
		} else {
			update_post_meta( $post_id, '_listicle_video', $url );
		}
	}
}
