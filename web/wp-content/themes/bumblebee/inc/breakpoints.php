<?php

add_filter( 'get_current_breakpoints', 'set_default_breakpoints', 1 );
function set_default_breakpoints( $breakpoints ) {
	if ( empty( $breakpoints ) {
		$breakpoints = array(
			'large_screen' => 1024,
			'desktop'      => 769,
			'tablet'       => 481,
			'mobile'       => 0,
		);
	}
	return $breakpoints;
}
