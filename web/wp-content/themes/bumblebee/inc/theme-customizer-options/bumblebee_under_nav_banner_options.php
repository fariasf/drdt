<?php

/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function under_nav_banner_options( $wp_customize ) {
	$wp_customize->add_section(
		'bumblebee_post_nav_banner',
		array(
			'title'       => __( 'Under Nav Banner', 'bumblebee' ),
			'description' => __( 'Customize the banner, under the main navigation' ),
			'priority'    => 31,
		)
	);

	$wp_customize->add_setting(
		'bumblebee_banner_text',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => 'Sign Up For Our Newsletters',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'bumblebee_banner_text',
		array(
			'type'        => 'text',
			'section'     => 'bumblebee_post_nav_banner',
			'label'       => __( 'Banner Text' ),
			'description' => __( 'Enter the text here, i.e: Sign Up For Newsletters' ),
			'settings'    => 'bumblebee_banner_text',
		)
	);

	$wp_customize->add_setting(
		'bumblebee_banner_text_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_setting(
		'bumblebee_banner_url',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'bumblebee_banner_url',
		array(
			'type'        => 'url',
			'section'     => 'bumblebee_post_nav_banner',
			'label'       => __( 'Banner Link URL' ),
			'description' => __( 'Add the URL' ),
			'input_attrs' => array(
				'placeholder' => __( 'https://www.tmbi.com' ),
			),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_banner_text_color',
			array(
				'description' => __( 'Change the color if the banner text' ),
				'label'       => __( 'Banner Text Color' ),
				'section'     => 'bumblebee_post_nav_banner',
				'settings'    => 'bumblebee_banner_text_color',
				'priority'    => '77',
			)
		)
	);
}
add_action( 'customize_register', 'under_nav_banner_options' );
