<?php

/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function facebook_metatag_options( $wp_customize ) {
	$wp_customize->add_section(
		'bumblebee_facebook_id',
		array(
			'title'       => __( 'Facebook Meta Tag', 'bumblebee' ),
			'description' => __( 'Add the FB ID' ),
			'priority'    => 45,
		)
	);

	$wp_customize->add_setting(
		'bumblebee_facebook_id_string',
		array(
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'bumblebee_facebook_id_string',
		array(
			'type'        => 'text',
			'section'     => 'bumblebee_facebook_id',
			'label'       => __( 'Facebook ID' ),
			'description' => __( 'Enter FB ID here, i.e: 1234567890' ),
			'settings'    => 'bumblebee_facebook_id_string',
		)
	);
}
add_action( 'customize_register', 'facebook_metatag_options' );
