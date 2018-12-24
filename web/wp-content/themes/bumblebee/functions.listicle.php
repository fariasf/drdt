<?php
add_filter( 'the_content', function( $content ) {
	global $post;

//	echo "asfdsdfasfdasdfasdf";
//	echo "<pre>";
//	print_r( $content );

	// the_content is not the ideal hook to alter the markup, as it applies
	// to all posts loaded in the loop amd may run more than once. this
	// prevents that from happening.
	static $the_content_was_altered;

	$should_alter_the_content = is_singular()
	                            && is_main_query()
	                            && in_the_loop()
	                            && $post
	                            && is_object( $post )
	                            && WP_Base::is_listicle( $post )
	                            && ! $the_content_was_altered;


	// Markup helpers
	$section_open = '<section class="listicle-page-group-container">';
	$content_wrapper_open = '<div class="listicle-page-container">';

	// Re-create the content with the markup we need
	if ( $should_alter_the_content ) {
		$in_between_cards_ads_cadence = get_theme_mod( 'ad_cadence_listicle' ) ?: 3;
		$nativo_ads_cadence = 8;

		$new_content = '';

		// Cards are wrapped inside a .listicle-page div
		$delimiter = '<div class="listicle-page">';
		$cards = array_filter( explode( $delimiter, $content ) );

		// Replace h2 to h4 only on fhm
		if ( WP_Base::is_fhm() ) {
			$cards = array_map( 'fhm_replace_h2_to_h4', $cards );
		}

		$cards = preg_filter( '/^/', $delimiter, $cards ); // Add back $delimiter to the beginning of each page
		$total_cards = count( $cards );


		$section_number = 0;
		// The first section contains the category link, title, byline, dek, and the first 4 cards. And it's own sidebar ad.
		$new_content .= $section_open;
		// Genesis `echo`es the markup, we need to capture it with output buffers.
		ob_start();
		//toh_render_category_label();
		$category_link_markup = ob_get_clean();

		ob_start();
		//Content_Brand_Meta::content_brand_logo();
		$content_brand_logo = ob_get_clean();

		ob_start();
		//genesis_do_post_title();
		$title_markup = ob_get_clean();

		ob_start();
//		toh_render_byline();
		$byline_markup = ob_get_clean();

		ob_start();
//		toh_render_dek();
		$dek_markup = ob_get_clean();

		ob_start();
		do_action( 'sponsor_ad' );
		$sponsor_markup = ob_get_clean();

		$new_content .= $content_wrapper_open;
		$new_content .= $category_link_markup;
		$new_content .= $content_brand_logo;
		$new_content .= $title_markup;
		$new_content .= $byline_markup;
		$new_content .= $dek_markup;
		$new_content .= $sponsor_markup;
		for ( $i = 0; $i < $in_between_cards_ads_cadence; $i++ ) {
			$new_content .= array_shift( $cards );
		}
		$new_content .= toh_get_exit_section_markup( [ 'TOH_Sidebar_Top', 'Read_Next_Right_Rail' ] );
		// After each section there's an ad
		if ( count( $cards ) ) {
			$section_number++;
			$new_content .= apply_filters( 'ads_between_cards', '', $section_number, [], $post );
		}

		// Next sections are dynamically generated based on the number of cards
		// There may be CTAs in between, at some point
		while ( count( $cards ) > 0 ) {
			$section_number++;
			// I'd like to indent this block for readability but validation fails with the for loop
			$new_content .= $section_open;
			$new_content .= $content_wrapper_open;
			$limit = min( $in_between_cards_ads_cadence, count( $cards ) );
			for ( $i = 0; $i < $limit; $i++ ) {
				$new_content .= array_shift( $cards );

				if ( ( ( $total_cards - count( $cards ) ) % $nativo_ads_cadence == 0 ) && ( ( $total_cards - count( $cards ) ) < $total_cards ) ) {
					static $nativo_ad_no = 1;
					$new_content .= '<div id="nativo' . $nativo_ad_no . '"></div>';
					$nativo_ad_no++;
				}
			}
			$widgets = null;
			// Special ad for the second section
			if ( $section_number == 2 ) {
				$widgets = [ 'TOH_Sidebar_Middle' ];
			}
			$new_content .= toh_get_exit_section_markup( $widgets );

			// Don't end the listicle with an ad (or only add an ad if there are more content cards)
			if ( count( $cards ) ) {
				$new_content .= apply_filters( 'ads_between_cards', '', $section_number, [], $post );
			}
		}

		$content = $new_content;
		$the_content_was_altered = true;
	}
	return $content;
});

function toh_get_exit_section_markup( $widgets = null ) {
	// Markup helpers
	$section_close = '</section>';
	$content_wrapper_close = '</div>';
	$aside_open = '<aside class="ad-wrapper"><div class="sidebar-sticky-wrapper">';
	$aside_close = '</div></aside>';

	$demo_ad = '<div id="listicle-between-cards-ad-'. rand() .'" class="adunit-lazy sidebar-ad" data-ad="sidebar-ad" ></div>';

	global $wp_widget_factory;

	$widgets_markup = $demo_ad;
	if ( is_array( $widgets ) ) {
		$widgets_markup = '';
		foreach ( $widgets as $widget ) {
			$the_widget_markup = '';

			if ( array_key_exists( $widget, $wp_widget_factory->widgets ) ) {
				ob_start();
				the_widget( $widget );
				$the_widget_markup = ob_get_clean();
			}

			$widgets_markup .= $the_widget_markup;
		}
	}

	$response = '';
	$response .= $content_wrapper_close;
	$response .= $aside_open;
	$response .= $widgets_markup;
	$response .= $aside_close;
	$response .= $section_close;
	return $response;
}