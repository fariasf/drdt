<?php

function color_options( $wp_customize ) {
	$wp_customize->add_setting(
		'bumblebee_default_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_default_color',
			array(
				'description' => __( 'Site wide font color, used on interior pages' ),
				'label'       => __( 'Font Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_default_color',
				'priority'    => '40',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_accent_hover_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_accent_hover_color',
			array(
				'description' => __( 'Link Color' ),
				'label'       => __( 'Link & Anchor Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_accent_hover_color',
				'priority'    => '75',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_nav_bg_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_nav_bg_color',
			array(
				'description' => __( 'Change the nav background color' ),
				'label'       => __( 'Navigation Background Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_nav_bg_color',
				'priority'    => '75',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_nav_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_nav_color',
			array(
				'description' => __( 'Change the nav text color' ),
				'label'       => __( 'Navigation Text Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_nav_color',
				'priority'    => '76',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_bg_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_footer_bg_color',
			array(
				'description' => __( 'Change the footer background color' ),
				'label'       => __( 'Footer Background Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_footer_bg_color',
				'priority'    => '77',
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_footer_text_color',
		array(
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_footer_text_color',
			array(
				'description' => __( 'Change the footer text color' ),
				'label'       => __( 'Footer Text Color' ),
				'section'     => 'colors',
				'settings'    => 'bumblebee_footer_text_color',
				'priority'    => '78',
			)
		)
	);

	$wp_customize->add_section(
		'bumblebee_fonts',
		array(
			'title'       => __( 'Font Options', 'bumblebee' ),
			'description' => __( 'Change the Heading & Body Fonts' ),
			'priority'    => 33,
		)
	);
}
add_action( 'customize_register', 'color_options' );
