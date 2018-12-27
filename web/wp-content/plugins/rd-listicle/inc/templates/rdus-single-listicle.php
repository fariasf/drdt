<?php
// Remove author and date
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
// Remove Pagination
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
add_action( 'genesis_entry_footer', function() { ?>
	<div class="">
		<?php echo do_shortcode( get_theme_mod( 'shortcode_text_area' ) ); ?>
	</div>
<?php }, 9 );
// Render Newsletter form
add_action( 'genesis_after_entry', 'rdus_child_render_newsletter', 9 );
function rdus_child_render_newsletter() {
	if ( class_exists( 'Newsletter_Controller' ) ) {
		$nc = Newsletter_Controller::get_instance();
		$nc->print_form();
	}
}
//Render Taboola Tags
add_action( 'genesis_after_entry', 'rdus_child_render_taboola', 9 );
function rdus_child_render_taboola() {
	do_action( 'taboola_render_slideshow' );
}
// Call genesis to generate the markup
genesis();
