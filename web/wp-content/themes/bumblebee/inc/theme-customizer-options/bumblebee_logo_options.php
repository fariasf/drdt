<?php

/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function logo_options( $wp_customize ) {
	$wp_customize->add_section(
		'bumblebee_logos',
		array(
			'title'       => __( 'Custom Logos', 'bumblebee' ),
			'description' => __( 'Add custom logos for the Header & Footer' ),
			'priority'    => 1,
		)
	);

	$wp_customize->add_setting( 'bumblebee_header_logo' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_header_logo',
			array(
				'title'    => __( 'Header Logo', 'bumblebee' ),
				'label'    => __( 'Upload a header logo', 'bumblebee' ),
				'section'  => 'bumblebee_logos',
				'settings' => 'bumblebee_header_logo',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_header_logo_width',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'bumblebee_header_logo_width',
		array(
			'type'        => 'number',
			'section'     => 'bumblebee_logos',
			'label'       => __( 'Header Logo Width' ),
			'description' => __( 'Enter only numbers, i.e: 100' ),
			'settings'    => 'bumblebee_header_logo_width',
		)
	);

	$wp_customize->add_setting( 'bumblebee_sticky_logo' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_sticky_logo',
			array(
				'title'    => __( 'Sticky Header Logo', 'bumblebee' ),
				'label'    => __( 'Upload the logo for the sticky header', 'bumblebee' ),
				'section'  => 'bumblebee_logos',
				'settings' => 'bumblebee_sticky_logo',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_sticky_logo_width',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'bumblebee_sticky_logo_width',
		array(
			'type'        => 'number',
			'section'     => 'bumblebee_logos',
			'label'       => __( 'Sticky Logo Width' ),
			'description' => __( 'Enter only numbers, i.e: 100' ),
			'settings'    => 'bumblebee_sticky_logo_width',
		)
	);

	$wp_customize->add_setting( 'bumblebee_footer_logo' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_footer_logo',
			array(
				'title'    => __( 'Footer Logo', 'bumblebee' ),
				'label'    => __( 'Upload a footer logo', 'bumblebee' ),
				'section'  => 'bumblebee_logos',
				'settings' => 'bumblebee_footer_logo',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_logo_width',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'bumblebee_footer_logo_width',
		array(
			'type'        => 'number',
			'section'     => 'bumblebee_logos',
			'label'       => __( 'Footer Logo Width' ),
			'description' => __( 'Enter only numbers, i.e: 100' ),
			'settings'    => 'bumblebee_footer_logo_width',
		)
	);
}
add_action( 'customize_register', 'logo_options' );
