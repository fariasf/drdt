<?php
/*
Plugin Name: RD Listicle UI
Version: 0.6
Description: Adds Custom Navigation controls
Author: Jey
Plugin URI: https://readersdigest.atlassian.net/browse/WPDT-3106
*/

class RD_Listicle_UI extends WP_Base {
	const VERSION           = '0.6';
	const PROCESSOR_SLUG    = 'listicle-ui';
	const JS_FILE           = 'js/listicle_get.js';
	const JSONURL_NAMESPACE = 'listicle/v1';
	const REST_API_METHOD   = 'GET';
	const PAGES_SET         = 10; //Loading the number of pages at first
	const IN_FOOTER         = true;
	const FILE_SPEC         = __DIR__;
	const JS_SLUG           = 'listicle_scroll';
	const JS_SCRIPT         = 'js/listicle-scroll.js';
	const RECENT_POST_LIMIT = 30;
	const NXTSLIDE_TRAN_PRE = 'list_next_slides_';

	public $depends = array( 'jquery' );

	public function __construct() {

		if ( ! is_admin() ) {
			add_filter( 'the_content', array( $this, 'listicle_content' ), 11, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_listicle_scripts' ) );
			add_action( 'rest_api_init', array( $this, 'listicle_register_api_hooks' ) );
			add_filter( 'template_include', array( $this, 'listicle_plugin_template' ) );
			add_filter( 'content_pagination', array( $this, 'slideshow_to_listicle_conversion' ), 99, 2 );
			add_filter( 'the_posts', array( $this, 'listicle_add_re_circ_content' ), 11, 2 );


			if ( static::is_rd() ) {
				add_action( 'genesis_entry_header', array( $this, 'rd_listicle_entry_header' ), 16 );
				add_action( 'genesis_entry_header', array( $this, 'rd_listicle_video_non_nav' ), 99 );
				add_action( 'genesis_entry_content', array( $this, 'rd_listicle_entry_footer' ), 99 );
			}
		}
	}

	/**
	 * Add recirc module to listicle. will be valid for FHM and RD
	 * @param $post
	 * @param $wp_query \WP_Query
	 *
	 * @return array
	 */
	public function listicle_add_re_circ_content( $post, $wp_query ) {

		if (
			$wp_query->is_main_query()
			&& (
				( ( ! WP_Base::is_fhm() ) && isset( $wp_query->query['page'] ) && $wp_query->query['page'] )
				|| ( WP_Base::is_fhm() && ! isset( $wp_query->query['view-all'] ) )
			)
			&& has_filter( 're_circ_content' )
		) {
			if ( $post && is_array( $post ) && is_object( $post[0] ) ) {
				$endcard = '';
				$re_circ_content = apply_filters( 're_circ_content', $post[0] );
				if ( $post[0]->post_type === 'listicle' && ! empty( $re_circ_content ) ) {
					$endcard .= '<br/>';
					$endcard .= '<!--nextpage-->';
					$endcard .= '<div class="re_circ_content">';
					$endcard .= $re_circ_content;
					$endcard .= '</div>';
					$post[0]->post_content .= $endcard;
				}
			}
		}
		return ( $post );
	}

	/**
	 * Converts a slideshow (multi-page post) into a listicle (single-page post showing all the content)
	 * @param $pages
	 * @param $post
	 *
	 * @return array
	 */
	public function slideshow_to_listicle_conversion( $pages, $post ) {
		if ( is_single() && WP_Base::is_tmbi_theme_v3() && ! WP_Base::is_card() && WP_Base::is_listicle() ) {
			$wrapper_open = '<div class="listicle-page">';
			$wrapper_close = '</div>';
			return array( apply_filters( 'the_content', $wrapper_open . implode( $wrapper_close . $wrapper_open, $pages ) . $wrapper_close ) );
		}
		return $pages;
	}

	public function rd_listicle_entry_header() {

		if ( WP_Base::is_tmbi_theme_v3() ) {
			return '';
		}

		$page = (int) get_query_var( 'page' );
		if ( $page ) {
			RD_Listicle_UI::rdnap_listicle_navigation( 'top' );
		}
	}

	public function rd_listicle_video_non_nav() {
		if ( WP_Base::is_tmbi_theme_v3() ) {
			return '';
		}
		if ( static::is_listicle() && ! static::is_card() ) {
			do_action( 'rd_listicle_video' );
		}
	}

	public function listicle_plugin_template( $template ) {
		if ( WP_Base::is_tmbi_theme_v3() ) {
			return $template;
		}

		$post_types = array( 'listicle' );

		if ( is_singular( $post_types ) && function_exists( 'genesis' ) ) {
			$template = __DIR__ . '/templates/rdus-single-listicle.php';
		}

		return ( $template );
	}

	public function rd_listicle_entry_footer() {
		if ( WP_Base::is_tmbi_theme_v3() ) {
			return '';
		}
		RD_Listicle_UI::rdnap_listicle_navigation( 'bottom' );
	}

	//Hookup REST API
	public function listicle_register_api_hooks() {

		register_rest_route( self::JSONURL_NAMESPACE, '/post/(?P<type>[\w-]+)/(?P<id>\d+)/(?P<slide>\d+)', array(
			'methods' => self::REST_API_METHOD,
			'callback' => array( $this, 'get_listicle_data' ),
			'args' => array( 'id', 'slide', 'type' ),
		) );

	}

	/**
	 * Render navigation within multipage listicle view,
	 * and switch between single page and multipage view for listicle.
	 */
	public static function rdnap_listicle_navigation( $location = 'top' ) {
		$page = (int) get_query_var( 'page' );

		if ( ! is_singular( 'listicle' ) || ( $location == 'top' && ! $page ) ) {
			return;
		}
		$position = 'header';
		if ( $location != 'top' ) {
			$position = 'footer';
		}
		//$post = get_post();
		global $numpages;

		$last_slide_style = '';
		$next_text        = 'Next';

		if ( $page == $numpages ) {
			$last_slide_style = 'last_slide';
			$next_text        = 'Next Slideshow';
		}

		//$numpages = $post->numpages;

		// Only render the markup once, re-use it again from $markup. This skirts an
		// issue where $page is reset after the_content() is called in content-single.php.
		// Assume it's correct the first time this is called.
		//
		// WordPress.
		static $markup;

		if ( ! isset( $markup ) ) {
			// Lop off any numeric index from permalink in case this is internally promoted
			// as multi-page:
			$permalink = preg_replace( '#/\d+/?$#', '/', get_permalink() );

			$prev_url = trailingslashit( trailingslashit( $permalink ) . ( $page - ( $page > 1 ? 1 : 0 ) ) );
			$next_url = trailingslashit( trailingslashit( $permalink ) . ( $page + ( $page < $numpages ? 1 : 0 ) ) );

			ob_start();

			?>

			<div class="listicle-article--nav">
				<?php if ( $page ) { ?>
				<div class="listicle-article--nav-bg">
					<div class="listicle-article--page-link listicle-article--page-link__previous">
						<a data-listicle-analytics-metrics='{"link_name":"previous","link_module":"listicles","link_pos":"<?php echo $position; ?>","page_template":"listicle detail page"}' class="js--prev js--listicle-nav <?php if ( $page < 2 ) { echo 'listicle-article--page-link__disabled'; } ?>" <?php echo $page <= 1 ? 'data-href' : 'href'; ?>="<?php echo esc_url( $prev_url ); ?>" title="<?php esc_attr_e( 'Previous', 'rdnap' ); ?>" >
						<i class="fa fa-chevron-left"></i>
						<span class="listicle-article--page-link--label"><?php esc_html_e( 'Previous', 'rdnap' ); ?></span>
						</a>
					</div>
					<div class="listicle-article--switch-container">
						<span class="listicle-article--photo-counter">
							<?php printf( '<span class="rd-card-index">%d</span>/<span class="rd-card-count">%d</span>', $page, $numpages ); ?>
						</span>
						<a data-analytics-metrics='{"link_name":"view as list","link_module":"listicles","link_pos":"<?php echo $position; ?>","page_template":"listicle detail page"}' class="listicle-article--switch" href="<?php echo esc_attr( $permalink ); ?>" title="<?php esc_attr_e( 'View as List', 'rdnap' ); ?>">
							<?php esc_html_e( 'View as List', 'rdnap' ); ?>
						</a>
					</div>

					<div class="listicle-article--page-link listicle-article--page-link__next">
						<a data-listicle-analytics-metrics='{"link_name":"next","link_module":"listicles","link_pos":"<?php echo $position; ?>","page_template":"listicle detail page"}' class="js--next js--listicle-nav <?php echo $last_slide_style; ?>" href="<?php echo esc_url( $next_url ); ?>" title="<?php esc_attr_e( $next_text, 'rdnap' ); ?>">
							<span class="listicle-article--page-link--label"><?php esc_html_e( $next_text, 'rdnap' ); ?></span>
							<i class="fa fa-chevron-right"></i>
							<div class="slideout_next">
								<span class="title"></span>
								<span class="image"><img src=""></span>
							</div>
						</a>
					</div>
				</div> <!-- /.listicle-article--nav-bg -->
			<?php } else { ?>
					<div class="listicle-article--switch-container__alone">
						<a data-analytics-metrics='{"link_name":"view as listicle","link_module":"listicles","link_pos":"header","page_template":"listicle detail page"}' class="listicle-article--switch" href="<?php echo trailingslashit( $permalink ); ?>1" title="View as Slideshow">
							View as Slideshow
						</a>
					</div>
				<?php } ?>
			</div>
			<?php

			$markup = ob_get_clean();
		}

		echo '<div id="listicle-nav-' . esc_attr( $location ) . '" class="listicle-article--nav-container listicle-article--nav-container__' . esc_attr( $location ) . '">';
		if ( $location === 'top' ) {
			do_action( 'rd_listicle_video' );
		}
		echo $markup;
		echo '</div>';

	}

	public function get_listicle_data($data) {
		global $post, $pages, $numpages;
		$post    = get_post( $data['id'] );
		$page_no = ( ( $data['slide'] % self::PAGES_SET ) == 0 ) ? $data['slide'] - 1 : $data['slide'];
		setup_postdata( $post );

		$page_floor = floor( $page_no / self::PAGES_SET );
		$start_page = ( $data['type'] == 'prev' ? ( $page_floor ) : ( $page_floor + 1 ) ) * self::PAGES_SET ;

		$end_page   = $start_page + self::PAGES_SET;
		$docs = array();
		for ( $i = $start_page; $i < $end_page; $i++ ) {
			if ( $pages[ $i ] != null ) {
				$docs[] = array(
								'page' => $i + 1,
								'data' => $pages[$i],
								);
			}
		}

		return( $docs );
	}

	public function listicle_content( $listicle_content ) {

		if ( WP_Base::is_tmbi_theme_v3() ) {
			return $listicle_content;
		}

		global $post, $pages, $page, $multipage, $numpages;
		if ( get_post_type( $post ) == 'listicle' && $multipage && get_query_var( 'page' ) ) {
			//listicle contents going here
			//total_number of pages should be in local variable for js access
			$current_page_index = get_query_var( 'page' ) - 1;
			$total_pages = $numpages;
			$starting_page = floor( $current_page_index / self::PAGES_SET ) * self::PAGES_SET;
			$current_set = floor( $current_page_index / self::PAGES_SET ) + 1;

			$listicle_content = '<div class="rd-listicle">';

			for ( $i = $starting_page; $i < self::PAGES_SET * $current_set ; $i++ ) {
				$page_no = $i + 1;

				if ( $page_no == get_query_var( 'page' ) ) {
					$listicle_content .= '<div class="rd-listicle-page listicle-page-' . $page_no . '">';
				} else {
					$listicle_content .= '<div class="rd-listicle-page listicle-page-' . $page_no . ' rd-card-hidden">';
				}

				if ( ! empty( $pages[$i] ) ) {
					$listicle_content .= wpautop( do_shortcode( $pages[$i] ) );
				}
				$listicle_content .= '</div>';
			}
			$listicle_content .= '</div>';

		}
		return( $listicle_content );
	}

	public function enqueue_listicle_scripts() {
		global $post, $numpages;
		setup_postdata( $post );

		if ( self::is_listicle() ) {
			if ( ! WP_Base::is_tmbi_theme_v3() ) {
				wp_enqueue_script(
					self::PROCESSOR_SLUG,
					$this->get_asset_url( self::JS_FILE ),
					$this->depends,
					self::VERSION,
					self::IN_FOOTER
				);

				wp_localize_script(
					self::PROCESSOR_SLUG, 'LIST', array(
						'restapi'        => array(
							'nexturl'         => rest_url( self::JSONURL_NAMESPACE . '/post/next/' ),
							'prevurl'         => rest_url( self::JSONURL_NAMESPACE . '/post/prev/' ),
							'method'          => self::REST_API_METHOD,
							'post_id'         => $post->ID,
							'pages_set'       => self::PAGES_SET,
							'number_of_pages' => $numpages,
						),
						'next_slideshow' => $this->get_next_slideshow( $post->ID ),
					)
				);
			}


			// Load scroll adobe dtm only for listicles in home category //
			if ( class_exists( 'JS_Listicle' ) && JS_Listicle::is_supported() ) {
				wp_register_script(
						self::JS_SLUG,
						$this->get_asset_url( self::JS_SCRIPT ),
						$this->depends,
						self::VERSION,
						self::IN_FOOTER
					);
				wp_enqueue_script( self::JS_SLUG );

			}
		}
	}


	/*
	 * Get next slideshow details
	 *
	 * @param int $post_id current post id
	 * @return false/array slideshow details url, name and image
	 */
	public function get_next_slideshow( $post_id ) {

		$categories = get_the_category( $post_id );
		$category   = '';
		if ( ! empty( $categories ) ) {
			$category = $categories[0]->term_id;
		}

		$args = array(
			'post_type'      => 'listicle',
			'category__in'   => array( $category ),
			'numberposts'    => self::RECENT_POST_LIMIT,
			'post__not_in'   => array( $post_id ),
			'post_status'    => 'publish',
		);

		$transient_name = self::NXTSLIDE_TRAN_PRE . $category;
		if ( ( false === ( $next_post_ids = get_transient( $transient_name ) ) ) || empty( get_transient( $transient_name ) ) ) {
			$next_post     = wp_get_recent_posts( $args );
			$next_post_ids = wp_list_pluck( $next_post, 'ID' );
			set_transient( $transient_name, $next_post_ids, 1 * HOUR_IN_SECONDS );
		}

		if ( count( $next_post_ids ) > 0 ) {
			$index        = rand( 0, ( count( $next_post_ids ) - 1 ) );
			$next_post_id = $next_post_ids[$index];

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next_post_id ), array( 150, 150 ), true );
			if ( count( $thumb ) < 4 || $thumb[3] !== true ) {
				$thumb_url = get_the_post_thumbnail_url( $next_post_id );
			} else {
				$thumb_url = $thumb[0];
			}

			$next_slideshow = array(
				'thumb' => $thumb_url,
				'title' => get_the_title( $next_post_id ),
				'url'   => get_the_permalink( $next_post_id ) . '1/',
			);

			return ( $next_slideshow );
		}
		return ( false );
	}
}
