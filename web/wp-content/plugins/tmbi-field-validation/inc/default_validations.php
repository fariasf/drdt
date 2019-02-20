<?php

class TMBI_Field_Validation_Default_Rules {
	public function __construct() {
		add_filter( 'tmbi_field_validation_post_rules', array( __CLASS__, 'set_default_rules' ) );
		add_filter( 'tmbi_field_validation_project_rules', array( __CLASS__, 'set_default_rules' ) );
		add_filter( 'tmbi_field_validation_listicle_rules', array( __CLASS__, 'set_default_rules' ) );
		add_filter( 'tmbi_field_validation_collection_rules', array( __CLASS__, 'set_default_rules' ) );
		add_filter( 'tmbi_field_validation_error_messages', array( __CLASS__, 'set_default_error_messages' ), 10, 2 );
	}

	public static function set_default_rules( $rules ) {
		$rules['post_title'] = [ 'required' ];
		$rules['post_excerpt'] = [ 'required' ];
		$rules['post_category'] = [ 'required' ];
		$rules['yoast_wpseo_metadesc'] = [ 'required' ];

		// This one is an integer (attachment ID), so we need to make sure it's at least "1"
		$rules['_thumbnail_id'] = [
			'required',
			'integer',
			'min' => 1,
		];

		return $rules;
	}

	public static function set_default_error_messages( $messages, $failed_rules ) {
		if ( ! empty( $failed_rules['post_title']['required'] ) ) {
			$messages[] = __( 'Title is required' );
		}

		if ( ! empty( $failed_rules['post_excerpt']['required'] ) ) {
			$messages[] = __( 'Excerpt is required' );
		}

		if ( ! empty( $failed_rules['post_excerpt']['post_category'] ) ) {
			$messages[] = __( 'Category is required' );
		}

		if ( ! empty( $failed_rules['yoast_wpseo_metadesc']['required'] ) ) {
			$messages[] = __( 'Meta Description is required' );
		}

		if ( ! empty( $failed_rules['_thumbnail_id'] ) ) {
			$messages[] = __( 'Featured Image is required' );
		}

		return $messages;
	}
}
