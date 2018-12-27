<?php
/**
 * Created by PhpStorm.
 * User: rpandit
 * Date: 9/10/2016
 * Time: 7:07 PM
 */

class Listicle_Post_Navigation extends WP_Base {
	const VERSION               = '1.2';
	const POST_TYPE             = 'listicle';
	const TRANSIENT_EXPIRATION  = 100;	// SECONDS
	const TRANSIENT_PREFIX      = 'singe_page_content-';
	const CARD_INTERVAL         = 4;
	const CSS_FILE              = 'css/editor-style-listicle.css';
	const IN_FOOTER             = true;
	const FILE_SPEC             = __DIR__;

	public function __construct() {
		add_filter( 'post_type_link', array( $this, 'listicle_permalink' ), 10, 4 );
		add_action( 'pre_get_posts', array( $this, 'set_listicle_post_type' ) );
		add_filter( 'the_content', array( $this, 'set_post_content_navigation' ) );
		add_filter( 'redirect_canonical', array( $this, 'listicle_redirect_canonical' ), 10, 2 );
		add_action( 'wpseo_canonical', array( $this, 'setup_canonical_link' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'rd_listicle_admin_styles' ) );
		add_filter( 'mce_buttons_2', array( $this, 'rd_listicle_styles' ), 10, 2 );
		add_filter( 'tiny_mce_before_init', array( $this, 'listicle_custom_toolbar' ) );
		add_action( 'admin_head', array( $this, 'hide_add_form_icon' ) );
		//add_filter( 'the_posts', array( $this, 'taboola_gallery_end_card_listicle_data' ), 10, 1 );
	}

	public function set_listicle_post_type( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( ! is_admin() && ! $query->get( 'pagename' ) && ( ! $query->get( 'post_type' ) || 'post' === $query->get( 'post_type' ) ) ) {
			$query->set( 'post_type', array( 'post', self::POST_TYPE, 'slideshows', 'quiz' ) );
		}
	}

	public function listicle_permalink( $post_link, $post, $leavename, $sample  ) {

		if ( ! $post instanceof WP_Post ) {
			return  ( $post_link );
		}

		$typenow = $post->post_type;
		if ( $post->post_type !== self::POST_TYPE ) {
			return  ( $post_link );
		}

		if ( $post->post_type === self::POST_TYPE ) {

			parse_str( parse_url( $post_link, PHP_URL_QUERY ), $parts );

			// Only modify "pretty" permalinks
			if ( ! empty( $parts['p'] ) ) {
				return  ( $post_link );
			}

			$category = $this->set_category_post( $post );
			if ( $category ) {
				if ( $sample ) {
					$post_link = trailingslashit( get_term_link( $category ) ) . '%postname%';
				} else {
					$post_link = trailingslashit( get_term_link( $category ) ) . $post->post_name;
				}
			} else {
				if ( $sample ) {
					$post_link = home_url( self::POST_TYPE . '/' . '%postname%' );
				} else {
					$post_link = home_url( self::POST_TYPE . '/' . $post->post_name );
				}
			}
			$post_link = trailingslashit( $post_link );
		}
		return ( $post_link );
	}

	public function set_category_post( $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( $primary_cat = get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true ) ) {
			$term = get_term( $primary_cat, 'category' );

			if ( ! is_wp_error( $term ) && $term ) {
				return ( $term );
			}
		}
		$categories = get_the_terms( $post->ID, 'category' );
		if ( ! empty( $categories ) ) {
			return ( $categories[0] ) ;
		}
	}

	public function set_post_content_navigation( $content ) {

		if ( WP_Base::is_tmbi_theme_v3() ) {
			return $content;
		}

		global $pages;

		if ( RD_URL_Magick::is_amp_page() ) {
			return ( $content );
		}

		if ( is_singular( self::POST_TYPE ) && ! get_query_var( 'page' ) ) {

			ob_start();
			do_action( 'render_mediabong_ads_only_player' );
			$media_html = ob_get_clean();

			$postcontent = '';
			if ( count( $pages ) > 0 ) {
				for ( $i = 0; $i < count( $pages ); $i++ ) {
					$postcontent .= '<div class="rd-card">' . wpautop( $pages[ $i ] ) . '</div>' . PHP_EOL;
					if ( ($i + 1) % self::CARD_INTERVAL === 0 ) {
						if ( function_exists( 'genesis' ) ) {
							$postcontent .= $this->generate_ad_markup();
						} else {
							ob_start();
							echo '<p class="continues-below">' . esc_html__( 'Content continues below ad', 'rdnap' ) . '</p>';
							do_action( 'rd_gpt_render_ad', 'listicle-between-cards' );
							$postcontent .= ob_get_clean();
						}
					}
					if ( $i === 0 ) {
						$postcontent .= $media_html;
					} elseif ( $i === 1 ) {
						ob_start();
						do_action( 'taboola_render_consumer_affairs_unit_tags' );
						$consumer_affairs = ob_get_clean();
						$postcontent .= $consumer_affairs;
					}
				}
				return( $postcontent );
			}
		}

		return ( $content );
	}

	private function generate_ad_markup() {
		$ad_id = abs( crc32( uniqid() ) );
		$ad_data = '<p class="continues-below">' . esc_html__( 'Content continues below ad', 'rdnap' ) . '</p>';
		$ad_data .= '<div class="ad-wrapper text-center adunit-lazy" id="rd-in-listicle-ad-' . $ad_id . '"></div>';
		return $ad_data;

	}

	public function listicle_redirect_canonical( $redirect_url, $requested_url ) {
		$do_redirect = true;
		if ( preg_match( '/1/',$requested_url ) ) {
			$do_redirect = false;
		}
		return ( $do_redirect );
	}

	public function setup_canonical_link() {
		global $post;
		$post = get_post( $post );
		if ( ! is_singular() ) {
			return;
		}
		if ( ! $id = get_queried_object_id() ) {
			return;
		}
		$url = get_permalink( $post->ID );
		$page = get_query_var( 'page' );
		if ( $page >= 2 ) {
			if ( '' == get_option( 'permalink_structure' ) ) {
				$url = add_query_arg( 'page', $page, $url );
			} else {
				$url = trailingslashit( $url );
			}
		}
		return ($url);
	}

	public function rd_listicle_admin_styles() {
		wp_enqueue_style(
			'listicle-editor-styles',
			$this->get_asset_url( self::CSS_FILE ),
			array(),
			self::VERSION
		);

	}

	public function rd_listicle_styles( $buttons, $id ) {

		if ( 'content' != $id ) {
			return $buttons; }

		//array_unshift( $buttons, 'styleselect' );
		array_unshift( $buttons, 13, 0, 'wp_page' );
		return $buttons;
	}

	public function listicle_custom_toolbar( $initArray ) {
		global $post;

		if ( ! $post ) {
			return $initArray;
		}

		$post_type = get_post_type( $post->ID );

		$style_formats = array(

				array(
					'title'   => 'List Item Headline',
					'block'   => 'h2',
					'wrapper' => false,
				),
				array(
					'title'    => 'List Item Caption',
					'block'    => 'p',
					'selector' => 'p',
					'classes'  => 'listicle-para',
					'wrapper'  => true,

				),
			);


		if ( 'listicle' == $post_type ) {
			// Insert the array, JSON ENCODED, into 'style_formats'
			$initArray['style_formats'] = json_encode( $style_formats );
			$initArray['toolbar1'] = 'styleselect,wp_page,|,formatselect|,bold,italic,|,alignleft,aligncenter,alignright,|,pastetext,removeformat,|,undo,redo,|,bullist,numlist,|,link,unlink,|,spellchecker,fullscreen';
			$initArray['toolbar2'] = 'underline,forecolor,pastetext,|,pasteword,removeformat,charmap,|,outdent,indent,|,undo,redo,';
		}

		return $initArray;
	}
	public function hide_add_form_icon() {
		global $post_type;
		if ( (isset( $_GET['post_type'] ) && $_GET['post_type'] == 'listicle') || ($post_type == 'listicle') ) {
			echo "<style type='text/css'>a#add_gform,a.nf-insert-form {display:none!important;}</style>\n";
		}
	}

	public function taboola_gallery_end_card_listicle_data( $post ) {
		if ( $post && is_array( $post ) && is_object( $post[0] ) ) {
			$endcard = '';
			if ( $post[0]->post_type === self::POST_TYPE  ) {
				$endcard .= '<br/>';
				$endcard .= '<!--nextpage-->';
				$endcard .= '<div class="recirculation-slide">';
				$endcard .= '<div id="taboola-end-of-gallery-thumbnails"></div>';
				$endcard .= '</div>';
				$post[0]->post_content .= $endcard;
				return ( $post );
			}
			else {
				return ( $post );
			}
		}
	}
}
