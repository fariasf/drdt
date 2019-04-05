<?php
/**
 * Including file to register acf long pin image fields.
 *
 * @package  Register acf long pin image fields
 */

add_action( 'acf/init', 'tmbi_register_acf_long_pin_image_fields', 12 );

/**
 * To register the acf long pin image fields.
 */
function tmbi_register_acf_long_pin_image_fields() {
	$toh_longpin_file_slug = 'field_toh_long_pin_image_upload';
	$tmbi_longpin_slug     = 'field_fhm_long_pin_image';
	$support_post_type     = get_post_types( '', 'names' );

	acf_add_local_field_group(
		array(
			'key'      => 'group_fhm_longpin_fields',
			'title'    => 'Long Pin Image',
			'fields'   => array(
				array(
					'key'      => $toh_longpin_file_slug,
					'label'    => '<p>Legacy Pin Image URL - (The default legacy long pin image - if field is blank, no legacy image exists. Please use "Add long Pin Image" field to update or choose a new Long Pin Image)</p>',
					'name'     => 'long_pin',
					'type'     => 'text',
					'disabled' => 'true',
				),
				array(
					'key'   => $tmbi_longpin_slug,
					'label' => '<p>Add long Pin Image to post item.</p>',
					'name'  => 'long_pin_file',
					'type'  => 'image',
				),
			),
			'location' => set_post_type_array( $support_post_type ),
		)
	);
}

/**
 * To set the post type.
 *
 * @param array $post_type_array post type array.
 */
function set_post_type_array( $post_type_array ) {
	$post_types = array();
	foreach ( $post_type_array as $post_type ) {
		$post_types[] = array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $post_type,
			),
		);
	}
	return ( $post_types );
}
