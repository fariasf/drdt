<?php
/**
 * Bumblebee customizer options
 *
 * @link https://developer.wordpress.org/themes/customize-api/customizer-objects/
 *
 * @package bumblebee
 */

/**
 * Remove default customizer options
 */
function remove_customizer_options_sections() {
	global $wp_customize;
	$wp_customize->remove_section( 'title_tagline' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'static_front_page' );
}
add_action( 'customize_register', 'remove_customizer_options_sections', 20 );

include_once( 'theme-customizer-options/bumblebee_color_options.php' );            // Color Options
include_once( 'theme-customizer-options/bumblebee_font_options.php' );             // Font Options
include_once( 'theme-customizer-options/bumblebee_under_nav_banner_options.php' ); // Banner Options, under the Navigation
include_once( 'theme-customizer-options/bumblebee_logo_options.php' );             // Logo Options
include_once( 'theme-customizer-options/bumblebee_nl_subscribe_options.php' );     // Newsletter & Subscribe Options, Header, Footer & In-Content
include_once( 'theme-customizer-options/bumblebee_button_options.php' );           // Button Color Options
include_once( 'theme-customizer-options/bumblebee_facebook_metatag_options.php' ); // Facebook Metatag Options


/**
 * Custom styles into head
 */
function bumblebee_add_customizer_styles() {

	$accent_hover_color     = get_theme_mod( 'bumblebee_accent_hover_color' );
	$nav_bg_color           = get_theme_mod( 'bumblebee_nav_bg_color' );
	$nav_text_color         = get_theme_mod( 'bumblebee_nav_color' );
	$banner_text_color      = get_theme_mod( 'bumblebee_banner_text_color' );
	$footer_bg_color        = get_theme_mod( 'bumblebee_footer_bg_color' );
	$footer_text_color      = get_theme_mod( 'bumblebee_footer_text_color' );
	$footer_nl_text_color   = get_theme_mod( 'bumblebee_footer_nl_heading_text_color' );
	$in_content_nl_bg_color = get_theme_mod( 'bumblebee_in_content_nl_bg_color' );
	$font_default_color     = get_theme_mod( 'bumblebee_default_color' );
	$font_menu_option       = get_theme_mod( 'bumblebee_menu_fonts' );
	$font_heading_option    = get_theme_mod( 'bumblebee_fonts' );
	$font_body_option       = get_theme_mod( 'bumblebee_body_fonts' );
	$button_bg_color        = get_theme_mod( 'bumblebee_button_bg_color' );
	$button_bg_hover_color  = get_theme_mod( 'bumblebee_button_bg_color_hover' );
	$button_text_color      = get_theme_mod( 'bumblebee_button_text_color' );

	?>
	<style>
		.main-navigation,
		.newsletter-sign-below-header {
			font-family: "<?php echo esc_html( $font_menu_option ); ?>" !important;
		}

		.header .main-navigation,
		.header .pure-menu-list ul {
			background: <?php echo esc_html( $nav_bg_color ); ?>;
		}

		.header .pure-menu-list ul {
			z-index: 1;
		}

		.header .main-navigation .menu-desktop-focus-menu-container ul li a,
		.header .menu-text,
		.header .menu-text a,
		.header .pure-menu-list ul li a span {
			color: <?php echo esc_html( $nav_text_color ); ?>;
		}

		.header .hamburger-menu {
			background-color: <?php echo esc_html( $nav_text_color ); ?>;
		}

		.header .pure-menu-list ul li:not(:last-child) {
			border-bottom: 2px dotted <?php echo esc_html( $nav_text_color ); ?>;
		}

		.header .main-navigation.sticky #search-form-wrapper.visible,
		.header .main-navigation.sticky #search-form-wrapper.visible .close-btn {
			background-color: <?php echo esc_html( $nav_bg_color ); ?>;
		}
		.header .main-navigation.sticky #search-form-wrapper.visible .search-button {
			background-color: <?php echo esc_html( $button_bg_color ); ?>;
		}
		.newsletter-sign-below-header.hide-on-mobile .nl-signup-link h4,
		.newsletter-sign-below-header.hide-on-mobile .nl-signup-link h4 .nl-right-arrow {
			color: <?php echo esc_html( $banner_text_color ); ?>;
			fill: <?php echo esc_html( $banner_text_color ); ?>;
		}
		.header #search-form-wrapper {
			background-color: <?php echo esc_html( $nav_bg_color ); ?>;
		}

		main {
			font-family: "<?php echo esc_html( $font_body_option ); ?>" !important;
		}

		h1, h2, h3, h4, h5, h6 {
			font-family: "<?php echo esc_html( $font_heading_option ); ?>" !important;
		}

		main.site-content {
			color: <?php echo esc_html( $font_default_color ); ?>;
		}

		a {
			color: <?php echo esc_html( $accent_hover_color ); ?>;
		}

		.post-content a {
			color: <?php echo esc_html( $accent_hover_color ); ?> !important;
			border-bottom: 1px solid <?php echo esc_html( $accent_hover_color ); ?> !important;
		}

		.post-category-label {
			border-bottom: none !important;
		}

		.footer,
		.no-js .accessibility-menu,
		.no-js .accessibility-menu .menu-hamburger-menu-container #menu {
			background: <?php echo esc_html( $footer_bg_color ); ?> !important;
		}

		.footer ul li a,
		.footer .footer-brand-links-container ul.footer-brand-links li:not(:last-child):after {
			color: <?php echo esc_html( $footer_text_color ); ?> !important;
		}

		.read-more,
		.more {
			background: <?php echo esc_html( $button_bg_color ); ?>;
			color: <?php echo esc_html( $button_text_color ); ?>;
		}

		.read-more:hover,
		.more:hover {
			background: <?php echo esc_html( $button_bg_hover_color ); ?>;
		}

		footer .newsletter h3 {
			color: <?php echo esc_html( $footer_nl_text_color ); ?>;
		}

		.footer .newsletter form button {
			background-color: <?php echo esc_html( $button_bg_color ); ?>;
			color: <?php echo esc_html( $button_text_color ); ?>;
		}

		.footer .newsletter form button:hover {
			background-color: <?php echo esc_html( $button_bg_hover_color ); ?>;
		}

		.in-content-nl-container .diyu-logo {
			background-color: <?php echo esc_html( $in_content_nl_bg_color ); ?>;
		}

		@media screen and (max-width: 767px) {
			.header .hamburger-menu {
				background-color: <?php echo esc_html( $nav_bg_color ); ?>;
			}
		}
	}
	</style>

	<?php
}
add_action( 'wp_head', 'bumblebee_add_customizer_styles' );
