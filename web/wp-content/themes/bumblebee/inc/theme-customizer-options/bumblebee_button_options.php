<?php

/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function button_options( $wp_customize ) {
	$wp_customize->add_section(
		'bumblebee_buttons',
		array(
			'title'       => __( 'Buttons', 'bumblebee' ),
			'description' => __( 'Customize the button colors' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting( 'bumblebee_button_bg_color' );

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_button_bg_color',
			array(
				'label'    => __( 'Button BG Color' ),
				'section'  => 'bumblebee_buttons',
				'settings' => 'bumblebee_button_bg_color',
				'priority' => '77',
			)
		)
	);

	$wp_customize->add_setting( 'bumblebee_button_bg_color_hover' );

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_button_bg_color_hover',
			array(
				'label'    => __( 'Button BG Hover Color' ),
				'section'  => 'bumblebee_buttons',
				'settings' => 'bumblebee_button_bg_color_hover',
				'priority' => '77',
			)
		)
	);

	$wp_customize->add_setting( 'bumblebee_button_text_color' );

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bumblebee_button_text_color',
			array(
				'label'    => __( 'Button Text Color' ),
				'section'  => 'bumblebee_buttons',
				'settings' => 'bumblebee_button_text_color',
				'priority' => '77',
			)
		)
	);
}
add_action( 'customize_register', 'button_options' );
