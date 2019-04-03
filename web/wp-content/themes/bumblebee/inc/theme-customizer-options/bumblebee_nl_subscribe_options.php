<?php

/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nl_subscribe_options( $wp_customize ) {
	$wp_customize->add_setting( 'bumblebee_footer_nl_subscribe_image' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_footer_nl_subscribe_image',
			array(
				'title'    => __( 'Header Newsletter Image', 'bumblebee' ),
				'label'    => __( 'Upload an image', 'bumblebee' ),
				'section'  => 'bumblebee_logos',
				'settings' => 'bumblebee_footer_nl_subscribe_image',
			)
		)
	);

	$wp_customize->add_section(
		'bumblebee_header_nl',
		array(
			'title'       => __( 'Header Newsletter', 'bumblebee' ),
			'description' => __( 'Header & Sticky Newsletter Images' ),
			'priority'    => 5,
		)
	);

	$wp_customize->add_setting( 'bumblebee_header_subscribe_image' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_header_subscribe_image',
			array(
				'title'    => __( 'Newsletter Image', 'bumblebee' ),
				'label'    => __( 'Add the Newsletter Image', 'bumblebee' ),
				'section'  => 'bumblebee_header_nl',
				'settings' => 'bumblebee_header_subscribe_image',
				'priority' => '20',
			)
		)
	);

	$wp_customize->add_setting( 'bumblebee_header_subscribe_image_sticky' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_header_subscribe_image_sticky',
			array(
				'title'    => __( 'Newsletter Image (Sticky)', 'bumblebee' ),
				'label'    => __( 'Add the Newsletter Image (Sticky)', 'bumblebee' ),
				'section'  => 'bumblebee_header_nl',
				'settings' => 'bumblebee_header_subscribe_image_sticky',
				'priority' => '20',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_header_subscribe_width',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'bumblebee_header_subscribe_width',
		array(
			'type'        => 'number',
			'section'     => 'bumblebee_header_nl',
			'label'       => __( 'Newsletter Image Width' ),
			'description' => __( 'Enter only numbers, i.e: 180' ),
			'settings'    => 'bumblebee_header_subscribe_width',
			'priority'    => '30',
		)
	);

	$wp_customize->add_setting(
		'bumblebee_header_subscribe_url',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'bumblebee_header_subscribe_url',
		array(
			'type'        => 'url',
			'section'     => 'bumblebee_header_nl',
			'label'       => __( 'Newsletter Link URL' ),
			'description' => __( 'Add the URL' ),
			'priority'    => '35',
			'input_attrs' => array(
				'placeholder' => __( 'https://www.tmbi.com' ),
			),
		)
	);

	$wp_customize->add_section(
		'bumblebee_nl',
		array(
			'title'       => __( 'Newsletters', 'bumblebee' ),
			'description' => __( 'Customize what the newsletter sections should look like. Footer, In-Content and Full-Width Archive' ),
			'priority'    => 40,
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_nl_heading_text',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => 'Sign Up For Our Newsletters',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'bumblebee_footer_nl_heading_text',
		array(
			'type'        => 'text',
			'section'     => 'bumblebee_nl',
			'label'       => __( 'Newsletter Heading Text' ),
			'description' => __( 'Enter the text here, i.e: Sign Up For Newsletters' ),
			'settings'    => 'bumblebee_footer_nl_heading_text',
		)
	);

	$wp_customize->add_setting( 'bumblebee_footer_nl_heading_text_color' );

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_footer_nl_heading_text_color',
			array(
				'label'    => __( 'Newsletter Heading Text Color' ),
				'section'  => 'bumblebee_nl',
				'settings' => 'bumblebee_footer_nl_heading_text_color',
				'priority' => '10',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_diy_university_text',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => 'DIY UNIVERSITY Online Courses',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'bumblebee_diy_university_text',
		array(
			'type'        => 'text',
			'section'     => 'bumblebee_nl',
			'label'       => __( 'DIY University Text' ),
			'description' => __( 'Enter the text here, i.e: DIY UNIVERSITY Online Courses' ),
			'settings'    => 'bumblebee_diy_university_text',
		)
	);

	$wp_customize->add_setting( 'bumblebee_footer_nl_subscribe_image' );

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'bumblebee_footer_nl_subscribe_image',
			array(
				'label'       => __( 'Newsletter Image', 'bumblebee' ),
				'description' => __( 'Add the "Subscribe to Newsletter" Image', 'bumblebee' ),
				'section'     => 'bumblebee_nl',
				'settings'    => 'bumblebee_footer_nl_subscribe_image',
				'priority'    => '20',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_nl_subscribe_image_width',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'bumblebee_footer_nl_subscribe_image_width',
		array(
			'type'        => 'number',
			'section'     => 'bumblebee_nl',
			'label'       => __( 'Newsletter Image Width' ),
			'description' => __( 'Enter only numbers, i.e: 300' ),
			'settings'    => 'bumblebee_footer_nl_subscribe_image_width',
			'priority'    => '30',
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_nl_subscribe_url',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'bumblebee_footer_nl_subscribe_url',
		array(
			'type'        => 'url',
			'section'     => 'bumblebee_nl',
			'label'       => __( 'Newsletter Link URL' ),
			'description' => __( 'Add the URL' ),
			'priority'    => '35',
			'input_attrs' => array(
				'placeholder' => __( 'https://www.tmbi.com' ),
			),
		)
	);
}
add_action( 'customize_register', 'nl_subscribe_options' );
