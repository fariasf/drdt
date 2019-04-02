<?php
/**
TMBI Custom Social Share

@package TMBI Custom Social Share
Plugin Name: TMBI Custom Social Share
Version: 1.0.0
Description: This plugin adds the custom social share. DRDT-80
Author: Samuel
Text Domain: tmbi-custom-social-share
 */

/**
 * Including file for load krux tracking.
 *
 * @file
 */
require 'inc/load-krux-tracking.php';

/**
 * Including file for register scf long pin image field.
 *
 * @file
 */
require 'inc/register-acf-long-pin-image-field.php';

/**
 * Including file for register wpseo opengraph twitter title and desc.
 *
 * @file
 */
require 'inc/class-wpseo-opengraph-twitter-title.php';

/**
 * Class TMBI_Custom_Social_Share
 */
class TMBI_Custom_Social_Share extends WPSEO_Opengraph_Twitter_Title {
	/**
	 * This will have the value of share icons
	 *
	 * @var array
	 */
	public static $default_share_icons = array( 'FACEBOOK', 'TWITTER', 'PINTEREST', 'EMAIL' );

	/**
	 * Init the custom social share.
	 */
	public static function init() {
		add_action( 'print_custom_social_share', array( __CLASS__, 'render_social_sharing_icons' ) );
	}

	/**
	 * To print the social share icon.
	 *
	 * @param string $data to pass data.
	 */
	public static function render_social_sharing_icons( $data ) {

		if ( is_single() || get_post_type( get_the_ID() ) === 'listicle' || is_archive() ) {
			$page_url = get_the_permalink( get_the_ID() );
			$page_url = str_replace( 'http://', 'https://', $page_url );

			$page_title = wp_trim_words( self::$meta_title, 50 );
			$page_desc  = wp_trim_words( self::$meta_desc, 50 );

			$page_image       = '';
			$longpin_image_id = get_field( 'long_pin_file', get_the_ID() );
			if ( isset( $longpin_image_id ) && ! empty( $longpin_image_id ) ) {
				$page_image = wp_get_attachment_url( $longpin_image_id['ID'] );
			}
			if ( '' === $page_image ) {
				$page_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			}
			$position_prop = 'social share-left sticky nav';
			$allowed_tags  = array(
				'a'   => array(
					'class'                  => array(),
					'data-analytics-metrics' => array(),
					'id'                     => array(),
					'onClick'                => array(),
					'href'                   => array(),
					'target'                 => array(),
				),
				'img' => array(
					'class' => array(),
					'src'   => array(),
					'alt'   => array(),
				),
			);
			print( '<ul class="pure-menu-list social-menu">' );
			foreach ( self::$default_share_icons as $social_icons ) {
				print( '<li class="social-share-item">' );
				switch ( strtoupper( trim( $social_icons ) ) ) {
					case 'FACEBOOK':
						echo wp_kses( '<a class="pure-menu-link" data-analytics-metrics=' . self::tmbi_adobe_analytics( 'facebook', $position_prop ) . ' id="fb-share" onClick="window.open(\'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $page_url ) . '&t' . rawurlencode( $page_title ) . '=&v=3\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"><img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . '/images/facebook-letter-logo.svg" alt="facebook" /></a>', $allowed_tags );

						break;

					case 'TWITTER':
						echo wp_kses( '<a class="pure-menu-link" data-analytics-metrics=' . self::tmbi_adobe_analytics( 'twitter', $position_prop ) . ' id="twitter-share" onClick="window.open(\'https://twitter.com/share?url=' . rawurlencode( $page_url ) . '&amp;text=' . rawurlencode( $page_title ) . '&amp;hashtags=\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"><img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . './images/twitter-logo.svg" alt="twitter" /></a>', $allowed_tags );

						break;

					case 'PINTEREST':
						echo wp_kses( '<a class="pure-menu-link" data-analytics-metrics=' . self::tmbi_adobe_analytics( 'pinterest', $position_prop ) . ' id="pinterest-share" onClick="window.open(\'https://pinterest.com/pin/create/button/?url=' . rawurlencode( $page_url ) . '&media=' . rawurlencode( $page_image ) . '&description=' . rawurlencode( $page_title ) . ',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"><img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . './images/pinterest-social-visual-website-logotype.svg" alt="Pinterest" /></a>', $allowed_tags );

						break;

					case 'EMAIL':
						echo wp_kses( '<a class="pure-menu-link" data-analytics-metrics=' . self::tmbi_adobe_analytics( 'email', $position_prop ) . '  id="email_a_friend" onClick="window.open(\'' . self::tmbi_custom_email() . '\',\'_self\')" href="javascript: void(0)" > <img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . './images/envelope.svg" alt="Email" /></a>', $allowed_tags );

						break;

					case 'LINKEDIN':
						echo wp_kses( '<a class="pure-menu-link" target="_blank" id="linkedin-share" onClick="window.open(\'https://www.linkedin.com/shareArticle?mini=true&url=' . $page_url . '&title=' . $page_title . '&summary=' . $page_desc . '&source=\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"> <img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . './images/linkedin.svg" alt="Linkedin" /></a>', $allowed_tags );

						break;

					case 'REDDIT':
						echo wp_kses( '<a class="pure-menu-link" target="_blank" id="reddit-share" onClick="window.open(\'http://reddit.com/submit?url=' . $page_url . '&amp;title=' . $page_title . '\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"> <img class="social-icons" src="' . plugin_dir_url( __FILE__ ) . './images/reddit.svg" alt="Reddit" /></a>', $allowed_tags );

						break;

					default:
						break;
				}
				print( '</li>' );
			}

			print( '</ul>' );
		}
	}

	/**
	 * Adding custom email template.
	 */
	public static function tmbi_custom_email() {
		global $wp;
		$current_url = home_url( $wp->request );

		$current_url = str_replace( 'http://', 'https://', $current_url );
		$current_url = $current_url . '/?_cmp=stf';

		$subject    = ( self::$meta_title ? self::$meta_title : get_the_title() );
		$subject    = str_replace( '’', "'", $subject );
		$line_break = '%0D%0A';
		$body       = "I thought you might like this...$line_break $line_break";

		$current_url = rawurlencode( $current_url );
		$body       .= "$subject $line_break $line_break";
		$body       .= "$current_url $line_break $line_break";

		$body .= "The Pros at Construction Pro Tips $line_break $line_break";
		$body .= "-- $line_break $line_break";
		$body .= 'For more great tips and tricks for the pros, from the pros, along with tool reviews, industry news and business advice sign up for our newsletter https://bit.ly/2sFBsRR';

		return 'mailto:?subject=' . addslashes( $subject ) . '&body=' . addslashes( $body );
	}

	/**
	 * To add the adobe analytics.
	 *
	 * @param string $title title.
	 * @param string $position_prop position.
	 */
	public static function tmbi_adobe_analytics( $title, $position_prop ) {
		$adobe = '{"link_name":"' . $title . '","link_module":"recipe engagement","link_pos":"' . $position_prop . '"}';
		return $adobe;
	}
}
add_action( 'init', array( 'TMBI_Custom_Social_Share', 'init' ) );
