<?php
/**
 * Byline (author) snippet
 *
 * Integrates with Co-Authors Plus if available.
 *
 * @package bumblebee
 * @todo Render author links
 */

?>

<?php
$the_author_id     = get_the_author_meta( 'ID' );
$the_author_avatar = get_avatar( $the_author_id, 32 );
$author_link       = get_author_posts_url( $the_author_id );

// Display the avatar of the first co-author.
if ( function_exists( 'get_coauthors' ) ) {
	$coauthors = get_coauthors();
	if ( ! empty( $coauthors ) ) {
		$the_author_id     = $coauthors[0]->data->ID;
		$the_author_avatar = coauthors_get_avatar( $coauthors[0], 32 );
		$author_link       = get_author_posts_url( $the_author_id );
	}
}
?>

<div class="byline">
	<?php echo wp_kses_post( $the_author_avatar ); ?>
	<span class="author-name"><a class="author_link" href="<?php echo wp_kses_post( $author_link ); ?>"><?php function_exists( 'coauthors' ) ? coauthors() : the_author(); ?></a></span>
</div>
