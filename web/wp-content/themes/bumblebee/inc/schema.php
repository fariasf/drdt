<?php
/**
 * Generic Schema markup & Meta properties
 *
 * @package bumblebee
 */

/**
 * Fetching blogposting schmea markup from TMBI Generic schema markup plugin
 */
function bumblebee_blogposting_schema_markup() {
	if ( ! is_admin() ) {
		if ( class_exists( 'BlogPosting_Schema_Markup' ) ) {
			BlogPosting_Schema_Markup::blog_posting_schema_mark_up();
		}
	}
}
add_action( 'wp_head', 'bumblebee_blogposting_schema_markup' );

/**
 * Fetching meta properties from TMBI Generic schema markup plugin
 */
function bumblebee_custom_meta_properties() {
	if ( ! is_admin() ) {
		if ( class_exists( 'Meta_Properties' ) ) {
			Meta_Properties::custom_meta_properties();
		}
	}
}
add_action( 'wp_head', 'bumblebee_custom_meta_properties' );

