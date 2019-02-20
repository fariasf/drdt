<?php
/**
 * Plugin Name: TMBI Grapeshot
 * Description: Integrates Grapeshot
 * Plugin URI: https://readersdigest.atlassian.net/browse/PLT-561
 * Author: Facundo Farias/MJ
 * Author URI: https://facundofarias.com.ar
 * Version: 1.0.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: grapeshot
 */

defined( 'ABSPATH' ) or exit;

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require 'grapeshot-cli.php';
}
class Grapeshot {
	static $enable_client_side   = true;
	static $enable_server_side   = true;
	static $server_side_metadata = 'DEFAULT';

	public function __construct() {
		add_action( 'wp', array( __CLASS__, 'maybe_enable_grapeshot' ) );
	}

	public static function maybe_enable_grapeshot() {
		/**
		 * Enable or disable Grapeshot client-side integration.
		 *
		 * @since 1.0.0
		 *
		 * @param bool  $enabled Client-side integration is enabled.
		 */
		static::$enable_client_side = apply_filters( 'grapeshot_enable_client_side', get_option( 'grapeshot_enable_client_side', static::$enable_client_side ) );

		/**
		 * Enable or disable Grapeshot server-side integration.
		 *
		 * @since 1.0.0
		 *
		 * @param bool  $enabled Server-side integration is enabled.
		 */
		static::$enable_server_side = apply_filters( 'grapeshot_enable_server_side', get_option( 'grapeshot_enable_server_side', static::$enable_server_side ) );


		// Try to use server-side metadata if available.
		if ( static::$enable_server_side && is_single() ) {
			self::get_server_side_metadata();
			if ( static::$server_side_metadata !== 'DEFAULT' ) {
				add_filter( 'ads_global_targeting', array( __CLASS__, 'set_gs_cat_server_side' ) );
			}
		}

		// If we don't have that (because server-side is disabled, or because we're looking at a non-single page, or because it just wasn't available)
		// and we have client-side enable, add the script.
		if ( static::$enable_client_side && static::$server_side_metadata === 'DEFAULT' ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		}
	}

	public static function enqueue_scripts() {
		/**
		 * Change the Grapeshot URL for client-side integration.
		 *
		 * @since 1.0.0
		 *
		 * @param string  $grapeshot_url Grapeshot client-side URL.
		 */
		$grapeshot_url = apply_filters( 'grapeshot_url', get_option( 'grapeshot_url', '//trustedmediabrands.grapeshot.co.uk/main/channels.cgi?url=' ) );
		?>
		<script type="text/javascript">
			var gs_channels="DEFAULT";
			var gsurl=window.location!=window.top.location?document.referrer:window.location;
			document.write('<scr'+'ipt type="text/javascript" src="<?php echo $grapeshot_url; ?>'+encodeURIComponent(gsurl)+'"></scr'+'ipt>');
		</script>
		<?php
	}

	public static function set_gs_cat_server_side( $targeting ) {
		$targeting['gs_cat'] = static::$server_side_metadata;
		return $targeting;
	}

	public static function get_server_side_metadata() {
		if ( is_single() ) {
			global $post;
			$gs_channels = json_decode( get_post_meta( $post->ID, 'gs_channels' , true ) );
			if ( ! empty( $gs_channels ) ) {
				static::$server_side_metadata = $gs_channels;
			}
		}
	}
}
$gs_ins = new Grapeshot();
