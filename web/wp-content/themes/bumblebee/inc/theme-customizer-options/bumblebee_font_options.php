<?php

/**
 * Custom fonts
 */
function bumblebee_get_font_url() {
	$font_url = '';

	/*
	Translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'bumblebee' ) ) {
		$subsets = 'latin,latin-ext';

		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'bumblebee' );

		if ( 'cyrillic' !== $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext'; } elseif ( 'greek' !== $subset ) {
			$subsets .= ',greek,greek-ext'; } elseif ( 'vietnamese' !== $subset ) {
				$subsets .= ',vietnamese'; }

			$font_option = str_replace( ' ', '+', get_theme_mod( 'bumblebee_fonts', 'Open Sans' ) );
			$query_args  = array(
				'family' => $font_option . ':400italic,700italic,400,700',
				'subset' => $subsets,
			);
			$font_url    = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}
/**
 * Sanitize custom fonts
 *
 * @param string $input font options.
 */
function bumblebee_sanitize_fonts( $input ) {
	$valid = array(
		'Open Sans'        => 'Open Sans',
		'Cormorant'        => 'Cormorant',
		'Lato'             => 'Lato',
		'Playfair Display' => 'Playfair Display',
		'Roboto Slab'      => 'Roboto Slab',
		'Raleway'          => 'Raleway',
		'Titillium Web'    => 'Titillium Web',
		'Ubuntu'           => 'Ubuntu',

	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

add_action( 'wp_enqueue_scripts', 'bumblebee_enqueue_scripts_styles' );
/**
 * Enqueue scripts
 */
function bumblebee_enqueue_scripts_styles() {
	$font_url = bumblebee_get_font_url();
	if ( ! empty( $font_url ) ) {
		wp_enqueue_style( 'bumblebee-fonts', esc_url_raw( $font_url ), array(), '1.0.0' ); }
}

/**
 * Default customizer color
 */
function bumblebee_customizer_get_default_accent_color() {
	return '#ffffff';
}

/**
 * Putting it all together
 *
 * @param string $mce_css editor styles.
 */
function bumblebee_mce_css( $mce_css ) {
	$font_url = bumblebee_get_font_url();

	if ( empty( $font_url ) ) {
		return $mce_css; }

	if ( ! empty( $mce_css ) ) {
		$mce_css .= ','; }

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'bumblebee_mce_css' );


/**
 * Customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function font_options( $wp_customize ) {
	$wp_customize->add_setting(
		'bumblebee_menu_fonts',
		array(
			'default'           => 'Open Sans',
			'sanitize_callback' => 'bumblebee_sanitize_fonts',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bumblebee_menu_fonts',
			array(
				'label'    => __( 'Select Menu Font', 'bumblebee' ),
				'section'  => 'bumblebee_fonts',
				'settings' => 'bumblebee_menu_fonts',
				'type'     => 'select',
				'choices'  => array(
					'Open Sans'        => 'Open Sans',
					'Cormorant'        => 'Cormorant',
					'Lato'             => 'Lato',
					'Playfair Display' => 'Playfair Display',
					'Roboto Slab'      => 'Roboto Slab',
					'Raleway'          => 'Raleway',
					'Titillium Web'    => 'Titillium Web',
					'Ubuntu'           => 'Ubuntu',
				),
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_fonts',
		array(
			'default'           => 'Open Sans',
			'sanitize_callback' => 'bumblebee_sanitize_fonts',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bumblebee_fonts',
			array(
				'label'    => __( 'Select Heading Font', 'bumblebee' ),
				'section'  => 'bumblebee_fonts',
				'settings' => 'bumblebee_fonts',
				'type'     => 'select',
				'choices'  => array(
					'Open Sans'        => 'Open Sans',
					'Cormorant'        => 'Cormorant',
					'Lato'             => 'Lato',
					'Playfair Display' => 'Playfair Display',
					'Roboto Slab'      => 'Roboto Slab',
					'Raleway'          => 'Raleway',
					'Titillium Web'    => 'Titillium Web',
					'Ubuntu'           => 'Ubuntu',
				),
			)
		)
	);

	$wp_customize->add_setting(
		'bumblebee_body_fonts',
		array(
			'default'           => 'Open Sans',
			'sanitize_callback' => 'bumblebee_sanitize_fonts',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bumblebee_body_fonts',
			array(
				'label'    => __( 'Select Body Font', 'bumblebee' ),
				'section'  => 'bumblebee_fonts',
				'settings' => 'bumblebee_body_fonts',
				'type'     => 'select',
				'choices'  => array(
					'Open Sans'        => 'Open Sans',
					'Cormorant'        => 'Cormorant',
					'Lato'             => 'Lato',
					'Playfair Display' => 'Playfair Display',
					'Roboto Slab'      => 'Roboto Slab',
					'Raleway'          => 'Raleway',
					'Titillium Web'    => 'Titillium Web',
					'Ubuntu'           => 'Ubuntu',
				),
			)
		)
	);
}
add_action( 'customize_register', 'font_options' );
