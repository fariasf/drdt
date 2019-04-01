<?php

$tax_terms = get_partial_option( 'terms' );

if ( ! $tax_terms && ! is_array( $tax_terms ) ) {
	return;
}

ob_start();
?>

<div class="tax-list">
	<span><?php echo __( 'Browse By:', 'tmbi-theme-v3' ); ?> </span>
	<ul>
		%s
	</ul>
</div>

<?php
$base_template  = ob_get_clean();
$adobe_template = 'data-analytics-metrics=\'{"link_name":"%s","link_module":"content navigation","link_pos":"browse by hubs"}\'';
$list_template  = '<li><a href="%s" %s >%s</a></li>';

$term_list = '';
foreach ( $tax_terms as $term ) {
	$adobe_att  = sprintf( $adobe_template, $term->name );
	$term_list .= sprintf( $list_template, trailingslashit( $term->link ), $adobe_att, $term->name );
}

printf( $base_template, $term_list );

?>
