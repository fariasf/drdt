<?php
/*
Plugin Name: TMBI Custom Social Share
Description: This plugin adds the custom social share instead of addThis. <a href='https://readersdigest.atlassian.net/browse/WPDT-7345' target='_blank'>Read more at WPDT-7345</a>
Version: 1.0.0
Author: Samuel
License: GPL

	Copyright (C) 2017, Archanadevi, Archanadevi.1@ness.com, (Archanadevi DOT 1 AT ness DOT com), Manjunatha, Manjunatha.Mariyappa@ness.com, (Manjunatha.Mariyappa AT ness DOT com)
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

		* Redistributions of source code must retain the above copyright notice, this
		  list of conditions and the following disclaimer.

		* Redistributions in binary form must reproduce the above copyright notice,
		  this list of conditions and the following disclaimer in the documentation
		  and/or other materials provided with the distribution.

		* Neither the name of the {organization} nor the names of its
		  contributors may be used to endorse or promote products derived from
		  this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
	IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
	FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
	DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
	SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
	OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
	OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

require 'inc/load-krux-tracking.php';
require 'inc/register-acf-long-pin-image-field.php';

/**
 * Class TMBI_Custom_Social_Share
 */
class TMBI_Custom_Social_Share {

	public static $default_share_icons = array( 'FACEBOOK', 'TWITTER', 'PINTEREST', 'EMAIL' );
	private static $meta_title         = '';
	private static $meta_desc          = '';

	/**
	 * Init the custom social share.
	 */
	public static function init() {
		add_action( 'print_custom_social_share', array( __CLASS__, 'render_social_sharing_icons' ) );
		add_filter( 'wpseo_opengraph_title', array( __CLASS__, 'tmbi_override_og_twitter_pinterest_title' ), 9 );
		add_filter( 'wpseo_twitter_title', array( __CLASS__, 'tmbi_override_og_twitter_pinterest_title' ), 9 );
		add_filter( 'wpseo_opengraph_desc', array( __CLASS__, 'tmbi_override_og_description' ), 11 );
	}

	/**
     * To print the social share icon.
	 * @param string $data to pass data.
	 */
	public static function render_social_sharing_icons( $data ) {

		if ( is_single() || get_post_type( get_the_ID() ) === 'listicle' || is_archive() ) {
			$page_url = get_the_permalink( get_the_ID() );
			// WPDT-7797: changed from http to https //
			$page_url = str_replace( 'http://', 'https://', $page_url );

			// Title to be shared
			$page_title = wp_trim_words( self::$meta_title, 50 );

			//Description to be shared
			$page_desc = wp_trim_words( self::$meta_desc, 50 );

			// Used for pinterest, can use long pin image if needed
			$page_image = '';
			$longpin_image_id = get_field( 'long_pin_file', get_the_ID() );
			if ( isset( $longpin_image_id ) && ! empty( $longpin_image_id ) ) {
				$page_image = wp_get_attachment_url( $longpin_image_id['ID'] );
			}
			if ( $page_image == '' ) {
				$page_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			}
			$position_prop = 'social share-left sticky nav';

			print( '<ul class="pure-menu-list social-menu">' );
			foreach ( self::$default_share_icons as $social_icons ) {

				switch ( strtoupper( trim( $social_icons ) ) ) {
					case 'FACEBOOK' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" data-analytics-metrics='<?php echo self::tmbi_adobe_analytics( 'facebook', $position_prop ); ?>' id="fb-share" onClick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( $page_url ); ?>&t<?php echo urlencode( $page_title ); ?>=&v=3','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"><img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/facebook-letter-logo.svg" alt="facebook" /></a></li> <?php
						break;

					case 'TWITTER' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" data-analytics-metrics='<?php echo self::tmbi_adobe_analytics( 'twitter', $position_prop ); ?>' id="twitter-share" onClick="window.open('https://twitter.com/share?url=<?php echo urlencode( $page_url ); ?>&amp;text=<?php echo urlencode( $page_title ); ?>&amp;hashtags=','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"><img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/twitter-logo.svg" alt="twitter" /></a></li> <?php
						break;

					case 'PINTEREST' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" data-analytics-metrics='<?php echo self::tmbi_adobe_analytics( 'pinterest', $position_prop ); ?>' id="pinterest-share" onClick="window.open('https://pinterest.com/pin/create/button/?url=<?php echo urlencode( $page_url ); ?>&media=<?php echo urlencode( $page_image ); ?>&description=<?php echo urlencode( $page_title ); ?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"><img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/pinterest-social-visual-website-logotype.svg" alt="Pinterest" /></a></li> <?php
						break;

					case 'EMAIL' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" data-analytics-metrics='<?php echo self::tmbi_adobe_analytics( 'email', $position_prop ); ?>'  id="email_a_friend" onClick="window.open('<?php echo self::tmbi_custom_email(); ?>','_self')" href="javascript: void(0)" > <img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/envelope.svg" alt="Email" /></a></li> <?php
						break;

					case 'LINKEDIN' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" target="_blank" id="linkedin-share" onClick="window.open('https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $page_url; ?>&title=<?php echo $page_title; ?>&summary=<?php echo $page_desc; ?>&source=','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"> <img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/linkedin.svg" alt="Linkedin" /></a></li> <?php
						break;

					case 'REDDIT' : ?>
                        <li class="social-share-item"><a class="pure-menu-link" target="_blank" id="reddit-share" onClick="window.open('http://reddit.com/submit?url=<?php echo $page_url; ?>&amp;title=<?php echo $page_title; ?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)"> <img class="social-icons" src="<?php echo plugin_dir_url( __FILE__ ); ?>./images/reddit.svg" alt="Reddit" /></a></li> <?php
						break;

					default : break;
				}
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
		// WPDT-7797: changed from http to https //
		$current_url = str_replace( 'http://', 'https://', $current_url );
		$current_url = $current_url.'/?_cmp=stf';
		/* if ( ! WP_Base::is_canadian_site() ) {
			$current_url = $current_url.'/?_cmp=stf';
		} elseif ( WP_Base::is_rdc() ) {
			$current_url = $current_url.'/?utm_source=rd_tellafriend&utm_medium=referral';
		} elseif ( WP_Base::is_bhc() ) {
			$current_url = $current_url.'/?utm_source=bh_tellafriend&utm_medium=referral';
		} elseif ( WP_Base::is_srd() ) {
			$current_url = $current_url.'/?utm_source=srd_tellafriend&utm_medium=referral';
		} */
		$subject = ( self::$meta_title ? self::$meta_title : get_the_title() );
		$subject = str_replace( '’', "'", $subject );
		$line_break = '%0D%0A';
		$body = "I thought you might like this...$line_break $line_break";
		/* if ( WP_Base::is_srd() ) {
			$body = "J'ai pensé que vous pourriez aimer ça…$line_break $line_break";
		} */
		$current_url = urlencode( $current_url );
		$body .= "$subject $line_break $line_break";
		$body .= "$current_url $line_break $line_break";

		$body .= "The Pros at Construction Pro Tips $line_break $line_break";
		$body .= "-- $line_break $line_break";
		$body .= 'For more great tips and tricks for the pros, from the pros, along with tool reviews, industry news and business advice sign up for our newsletter https://bit.ly/2sFBsRR';

		/* if ( WP_Base::is_toh() ) {
			$body .= "Happy Cooking, $line_break";
			$body .= "Your friends from Taste of Home $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great recipes and cooking inspiration signup for our newsletters https://bit.ly/2RcXV7b';
		} elseif ( WP_Base::is_fhm() ) {
			$body .= "You Can Do It! $line_break";
			$body .= "Your friends at Family Handyman $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great DIY projects for your home and yard sign up for our newsletter https://bit.ly/2REFp80';
		} elseif ( WP_Base::is_rd() ) {
			$body .= "Happy Reading, $line_break";
			$body .= "Your friends at Reader's Digest $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great content sign up for our newsletter https://bit.ly/2MpTW1M';
		} elseif ( WP_Base::is_cpt() ) {
			$body .= "The Pros at Construction Pro Tips $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great tips and tricks for the pros, from the pros, along with tool reviews, industry news and business advice sign up for our newsletter https://bit.ly/2sFBsRR';
		} elseif ( WP_Base::is_rdc() ) {
			$body .= "Happy Reading, $line_break";
			$body .= "Your friends at Reader's Digest $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great content sign up for our newsletter - https://www.readersdigest.ca/newsletter/';
		} elseif ( WP_Base::is_bhc() ) {
			$body .= "Happy Reading, $line_break";
			$body .= "Your friends at Best Health $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'For more great content sign up for our newsletter  - https://www.besthealthmag.ca/newsletter/';
		} elseif ( WP_Base::is_srd() ) {
			$body .= "Bonne lecture! $line_break";
			$body .= "Vos amis de Sélection.ca $line_break $line_break";
			$body .= "-- $line_break $line_break";
			$body .= 'Restez informés : abonnez-vous à notre infolettre https://www.selection.ca/infolettre/';
		} */
		return 'mailto:?subject='.addslashes( $subject ).'&body='.addslashes( $body );
	}

	/**
     * To override the og twitter pinterest title.
	 * @param string $title twitter title.
	 */
	public static function tmbi_override_og_twitter_pinterest_title( $title ) {
		$sf_og_text = '';
		global $post;
		if ( is_singular() ) {
			$socialflow_fb_text = get_post_meta( get_the_ID(), 'sf_title_facebook', true );

			if ( ! $socialflow_fb_text ) {
				$socialflow_fb_text = $post->post_title;
			}
			$sf_og_text = $socialflow_fb_text;
		}
		self::$meta_title = $sf_og_text ?? $title;
		return self::$meta_title;
	}

	/**
	 * To override the og twitter pinterest description.
	 * @param string $title twitter description.
	 */
	public static function tmbi_override_og_description( $title ) {
		$sf_og_desc = '';
		global $post;
		if ( is_singular() ) {
			$socialflow_fb_text = get_post_meta( get_the_ID(), 'sf_description_facebook', true );

			if ( $socialflow_fb_text == '' ) {
				$post_excerpt = strip_tags( $post->post_excerpt );
				if ( $post_excerpt == '' ) {
					$sf_og_desc = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
				} else {
					$sf_og_desc = trim( $post_excerpt );
				}
			} else {
				$sf_og_desc = $socialflow_fb_text;
			}
		}
		self::$meta_desc = $sf_og_desc ?? $title;
		return self::$meta_desc;
	}

	/**
     * To add the adobe analytics.
	 * @param $title title.
	 * @param $position_prop position.
	 */
	public static function tmbi_adobe_analytics( $title, $position_prop ) {
		$adobe = '{"link_name":"' . $title . '","link_module":"recipe engagement","link_pos":"' . $position_prop . '"}';
		return $adobe;
	}
}
add_action( 'init', array( 'TMBI_Custom_Social_Share', 'init' ) );
