<?php
/**
 * TMBI Nativo
 *
 * @package TMBI Nativo
 *  This is for the settings page of header bidder.
 */

/**
 * Class TMBI_Nativo_In_Articles
 */
class TMBI_Nativo_In_Articles {
	const CADENCE = 3;

	/**
	 * Instantiate the methods.
	 *
	 * @action init
	 */
	public static function init() {
		add_filter( 'in_article_nativo', array( __CLASS__, 'inject_nativo' ), 10, 3 );
		add_filter( 'the_content', array( __CLASS__, 'nativo_units_in_article' ), 13 );
	}

	/**
	 * Insert nativo tags in the content
	 *
	 * @param string $content for getting the post content.
	 * @return string
	 */
	public function nativo_units_in_article( $content ) {
		global $post;
		if ( ! ( $post && is_object( $post ) && 'post' === $post->post_type ) ) {
			return $content;
		}
		$paragraphs       = array_filter( explode( '</p>', trim( $content ) ) );
		$total_paragraphs = count( $paragraphs );
		foreach ( $paragraphs as $i => &$paragraph ) {
			$word_count          = count( explode( ' ', wp_strip_all_tags( $paragraph ) ) );
			$paragraph          .= '</p>';  // restore the closing </p> tag.
			$paragraph_number    = $i + 1;
			$nativo_flag_and_tag = apply_filters( 'in_article_nativo', $word_count, $paragraph_number, $total_paragraphs );
			if ( '' !== $nativo_flag_and_tag['nativo_tag'] ) {
				$paragraph .= $nativo_flag_and_tag['nativo_tag'];
				break;
			}
		}
		return implode( '', $paragraphs );
	}

	/**
	 * Inject a nativo ad unit (if the current paragraphs matches the defined cadence).
	 *
	 * @param string $word_count for number of words.
	 * @param string $paragraph_number for number of paragraphs.
	 * @param string $total_paragraphs for total paragraphs.
	 * @return array()
	 */
	public function inject_nativo( $word_count, $paragraph_number, $total_paragraphs ) {
		$nativo_ad_tag     = '';
		$is_last_paragraph = $paragraph_number === $total_paragraphs;
		if ( self::CADENCE === $paragraph_number && 100 >= $word_count ) {
			$nativo_ad_tag = $this->get_in_article_ad_markup( '1' );
		} elseif ( self::CADENCE > $paragraph_number && 100 >= $word_count && ! $is_last_paragraph ) {
			$nativo_ad_tag = $this->get_in_article_ad_markup( '1' );
		}
		return array(
			'nativo_tag' => $nativo_ad_tag,
		);
	}

	/**
	 * Get the markup for the nativo ad units to inject.
	 *
	 * @param int $nativo_unit_number for nativo tag.
	 * @return string
	 */
	public function get_in_article_ad_markup( $nativo_unit_number ) {
		return '<div id="nativo' . $nativo_unit_number . '"></div>';
	}

}

add_action( 'init', array( 'TMBI_Nativo_In_Articles', 'init' ) );
