<?php

/**
 * Framework
 */

class TMBI_Field_Validation_Framework {
	public function __construct() {
		add_filter( 'wp_insert_post_data', array( __CLASS__, 'maybe_return_to_draft' ), 99, 2 );
		add_action( 'admin_notices', array( __CLASS__, 'maybe_display_validation_errors' ) );
		add_filter( 'tmbi_validate_post', array( __CLASS__, 'run_validation' ), 10, 2 );
	}

	public static function maybe_return_to_draft( $data, $postarr ) {
		if ( $postarr['post_status'] != 'publish' ) {
			// Only validate when trying to publish;
			return $data;
		}

		$validation_result = self::validate( $postarr );
		$is_valid = $validation_result['is_valid'];

		if ( ! $is_valid ) {
			$data['post_status'] = 'draft';

			// We need a closure to `use` the results of the validation inside the filter
			add_filter(
				'redirect_post_location',
				function( $location ) use ( $validation_result ) {
					$url_with_messages = add_query_arg( 'tmbi-field-validation-errors', $validation_result['errors'], $location );
					return remove_query_arg( 'message', $url_with_messages );
				}
			);
		}
		return $data;
	}

	private static function validate( $postarr ) {
		$postarr = apply_filters( 'tmbi_field_validation_sanitize_post', $postarr );
		$validation_errors = apply_filters( 'tmbi_validate_post', array(), $postarr );

		return array(
			'errors' => $validation_errors,
			'is_valid' => empty( $validation_errors ),
		);
	}

	public static function maybe_display_validation_errors() {
		if ( array_key_exists( 'tmbi-field-validation-errors', $_GET ) ) {
			$messages = apply_filters( 'tmbi_field_validation_error_messages', array(), $_GET['tmbi-field-validation-errors'] );
			?>
			<div class="notice notice-error">
				<p>
					There are some validation errors, the post was returned to Draft.
					<?php if ( count( $messages ) > 0 ) : ?>
					<ul>
						<?php foreach ( $messages as $message ) : ?>
							<li><?php echo $message; ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

				</p>
			</div>
			<?php
		}
	}

	public static function run_validation( $errors, $postarr ) {
		$cpt = $postarr['post_type'];
		$all_cpt_rules = apply_filters( 'tmbi_field_validation_rules', array() );
		$this_cpt_rules = apply_filters( "tmbi_field_validation_{$cpt}_rules", $all_cpt_rules );

		$form = new Form\Validator( $this_cpt_rules );

		if ( ! $form->validate( $postarr ) ) {
			$errors += $form->getErrors();
		}

		return $errors;
	}
}


