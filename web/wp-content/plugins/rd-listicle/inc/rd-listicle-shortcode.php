<?php
/*
Plugin Name: RD Listicle Shortcode
Version: 1.0.2
Description: Adds Custom Shortcode
Author: 45PRESS
Plugin URI:
*/
class RD_Listicle_Shortcode extends WP_Base {
	const FILE_SPEC          = __DIR__;
	const VERSION            = '1.0.2';
	const EMBED_DIGITAL_DATA = 'embedListicle_digitalData';

	public $version = self::VERSION;
	public $depends = array();

	public function __construct() {
		parent::__construct();
	}
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_shortcode( 'rd_listicle', array( $this, 'render_shortcode' ) );
		add_filter( 'better_image_credits_wrapper', array( $this, 'add_image_wrapper' ) );
	}

	public function add_image_wrapper( $img_with_caption ) {
		return '<div class="image-wrapper">' . $img_with_caption . '</div>';
	}

	public function set_version( $version = null ) {
		if ( isset( $version ) ) {
			$this->version = $version;
		}
	}
	/**
	 * Register and enqueue JS assets
	 */
	public function enqueue_scripts() {
		global $post;
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'rd_listicle' ) ) {
			wp_enqueue_style(
				'owl-carousel',
				$this->get_asset_url( 'css/owl.carousel.min.css' ),
				$this->depends, $this->version
			);
			wp_enqueue_style(
				'rd-listicles',
				$this->get_asset_url( 'css/rd-listicles.css' ),
				$this->depends,
				$this->version
			);
			wp_enqueue_script(
				'owl-carousel',
				$this->get_asset_url( 'js/owl.carousel.min.js' ),
				array( 'jquery' ),
				$this->version,
				self::IN_FOOTER
			);
			wp_enqueue_script(
				'rd-listicles',
				$this->get_asset_url( 'js/rd-listicles.js' ),
				array( 'jquery', 'owl-carousel' ),
				$this->version,
				self::IN_FOOTER
			);
		}
	}
	/**
	 * @param array $atts
	 * @return string
	 */
	public function render_shortcode( $atts = array() ) {
		ob_start();
		if ( empty( $atts['id'] ) ) :
			esc_html_e( 'Attribute ID is required for the listicle shortcode.', 'rd_listicles' );
		else :
			$this->build_html( $this->get_listicle_content( $atts['id'] ), $atts );
			?>
			<?php
		endif;
		return ob_get_clean();
	}
	private function build_html( $listicle_items = array(), $atts = array() ) {
		$listicle_class = '';
		if ( ! empty( $listicle_items ) ) :

			if ( ! empty( $atts['type'] ) ) {
				$listicle_class = 'listicle-mini';
			}
			printf( '<div class="listicle-wrap %s">', $listicle_class );

			if ( ! empty( $atts['title'] ) ) {
				printf( '<div class="listicle-title">%s</div>', $atts['title'] );
			}
			?>
			<div class="listicle-nav">
				<a href="#"><span class="dashicons dashicons-arrow-left" style="font-size: 26px;"></span></a>
				<span><?php printf( '%d / %d', 1, count( $listicle_items ) ); ?></span>
				<a href="#"><span class="dashicons dashicons-arrow-right" style="font-size: 26px;"></span></a>
			</div>
			<div class="listicle-carousel owl-carousel">
				<?php
				foreach ( $listicle_items as $listicle_item ) :
					?>
					<div>
						<?php
							echo $listicle_item;
						?>
					</div>
					<?php endforeach; ?>
			</div>
		</div>
		<?php endif;
	}
	private function lazyload_html( $listicle_items = array() ) {
		if ( count( $listicle_items ) > 1 ) {
			for ( $i = 1; $i < count( $listicle_items ); $i++ ) {
				$dom = new DOMDocument();
				$dom->loadHTML( mb_convert_encoding( $listicle_items[$i], 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NODEFDTD );
				$tags = $dom->getElementsByTagName( 'img' );
				if ( $tags->length > 0 ) {
					$tag = $tags->item( 0 );
					$src = $tag->getAttribute( 'src' );
					$tag->removeAttribute( 'src' );
					$tag->setAttribute( 'data-src', $src );
					$class = $tag->getAttribute( 'class' );
					$tag->setAttribute( 'class', 'owl-lazy ' . $class );
					$listicle_items[$i] = preg_replace( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML() );
				}
			}
		}
		return $listicle_items;
	}
	private function insert_ads( $listicle_items = array() ) {
		if ( count( $listicle_items ) > 3 ) {
			$listicle_items_with_ads = array();
			ob_start();
			if ( function_exists( 'rdnap_render_ad_slot' ) ) {
				rdnap_render_ad_slot( 'in-article' );
			}
			$ads = ob_get_clean();
			$chunks = array_chunk( $listicle_items, 3 );
			foreach ( $chunks as $index => $chunk_pages ) {
				$listicle_items_with_ads = array_merge( $listicle_items_with_ads, $chunk_pages );
				$is_last_chunk = ( $index == count( $chunks ) - 1 );
				if ( ! $is_last_chunk ) {
					$listicle_items_with_ads[] = $ads;
				}
				$listicle_items = $listicle_items_with_ads;
			}
		}
		return $listicle_items;
	}

	private function get_listicle_content( $listicle_id = 0 ) {
		if ( ! is_numeric( $listicle_id ) || $listicle_id === 0 ) {
			return ( array() );
		}
		$listicle_post = get_post( $listicle_id );

		if ( class_exists( 'RD_Adobe_DTM' ) ) {
			//{"page":{"sitename":"rd","pageName":"rd:Parenting:Advice:article:listicle in article","content":{"contentName":"listicle in article","tmbiBrand":"rd-com","contentID":251510,"contentType":"article","category":"parenting","tags":"","contentCost":"0","publishedDate":"2017-10-03","modifiedDate":"2017-10-03","author":"Jey Saravana","authorRole":"administrator"},"subCategory":"Parenting","subsubCategory":"Advice","category":{"subCategory":"Parenting","subsubCategory":"Advice","pageType":"parenting"}}};
			$data_layer  = array();
			$categories  = RD_Adobe_DTM::get_sub_categories();

			$data_layer['page.subCategory']                = $categories['subcategory'];
			$data_layer['page.subsubCategory']             = $categories['subsubcategory'];
			$data_layer['page.sitename']                   = RD_Adobe_DTM::get_nickname();
			$data_layer['page.content.cardNo']             = 1;
			$data_layer['page.content.slideShowMulti']     = true;
			$data_layer['page.content.embededSlideTitle']  = $listicle_post->post_title;

			$listicle_variables = RD_Adobe_DTM::parse_data_layer_config( $data_layer );

			wp_localize_script( 'rd_adobe_dtm', self::EMBED_DIGITAL_DATA, $listicle_variables );
			wp_enqueue_script( 'rd_adobe_dtm' );
		}

		$listicle_content = $this->insert_ads( $this->lazyload_html( explode( '<!--nextpage-->', $listicle_post->post_content ) ) );
		return ( $listicle_content );
	}
}
