<?php

// Namespaces for MQ library
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class GrapeShot_CLI extends WP_CLI_Command {

	public $post_types = array( 'post', 'page', 'recipe', 'listicle', 'collection', 'project', 'tip', 'marquee','joke','video','quiz','nicestplace2018' );

	private $gs_endpoint;
	private $json_data;
	private $mq_connection;
	private $queue_name;
	const API_TIMEOUT = 180;
	private $mq_host  = 'jenkins.rda.net';
	private $mq_port  = 5672;
	private $mq_user  = 'wpdttest';
	private $mq_pass  = 'notsafe123';
	private $mq_vhost = '/';


	public function export() {
		WP_CLI::log( 'Process started: ' . date_i18n( 'Y-m-d H:i:s e' ) );
		$args = array(
			'post_type'      => $this->post_types,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'date_query'     => array(
				array(
					'column' => 'post_modified_gmt',
					'after'  => '24 hours ago',
				),
			),
		);
		$post_ids = get_posts( $args );

		//Check to make sure we have some posts to update.
		if ( empty( $post_ids ) ) {
			WP_CLI::log( 'No posts to update ' . date_i18n( 'Y-m-d H:i:s e' ) );
			return;
		}
		// Get the gs url;
		$this->get_grapeshot_url();
		$found_posts = count( $post_ids );
		WP_CLI::log( 'Found ' . $found_posts . ' posts.' );
		$post_data = array();
		foreach ( $post_ids as $post_id ) {
			$meta_data = array();
			$meta_data['id'] = $post_id;
			$meta_data['url'] = get_permalink( $post_id );
			$meta_data['site_url'] = get_site_url();
			$post_data['post_data'] = $meta_data;
			$gs_response = $this->get_meta_from_gs( $meta_data['url'] );
			if ( $gs_response ) {
				$this->update_gs_meta_data( $post_id, $gs_response );
			} else {
				$this->send_data_to_grapeshot_mq( $post_data );
			}
		}

		WP_CLI::log( $found_posts . ' posts have been updated with gs meta data: ' . date_i18n( 'Y-m-d H:i:s e' ) );
	}

	// Update all exising content with Gs meta data
	public function export_all() {
		WP_CLI::log( 'Process started: ' . date_i18n( 'Y-m-d H:i:s e' ) );
		$args = array(
			'post_type'      => $this->post_types,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
		);
		$post_ids = get_posts( $args );

		//Check to make sure we have some posts to update.
		if ( empty( $post_ids ) ) {
			WP_CLI::log( 'No posts to update ' . date_i18n( 'Y-m-d H:i:s e' ) );
			return;
		}

		$found_posts = count( $post_ids );
		WP_CLI::log( 'Found ' . $found_posts . ' posts.' );
		$post_data = array();
		foreach ( $post_ids as $post_id ) {
			$meta_data = array();
			$meta_data['id'] = $post_id;
			$meta_data['url'] = get_permalink( $post_id );
			$meta_data['site_url'] = get_site_url();
			$post_data['post_data'] = $meta_data;
			$this->send_data_to_grapeshot_mq( $post_data );
		}
		WP_CLI::log( $found_posts . ' posts has been pushed to RMQ: ' . date_i18n( 'Y-m-d H:i:s e' ) );
	}

	public function get_grapeshot_url() {
		$this->gs_endpoint = apply_filters( 'grapeshot_url', get_option( 'grapeshot_url', 'https://trustedmediabrands.grapeshot.co.uk/main/channels.cgi?url=' ) );
	}

	public function get_meta_from_gs( $url ) {
		$gs_url = $this->gs_endpoint . $url;
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => self::API_TIMEOUT,
			CURLOPT_URL => $gs_url,
		));
		$resp = curl_exec( $curl );
		$response = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		if ( $response == 200 ) {
			if ( strpos( $resp, '["RETRY"]' ) !== false ) {
				WP_CLI::log( 'Grapeshot Retry Error' );
				return '';
			} else if ( strpos( $resp, '["ERROR"]' ) !== false ) {
				WP_CLI::log( 'Grapeshot Error pushing the record to rmq' );
				return '';
			} else if ( strpos( $resp, 'gs_channels=""' ) !== false ) {
				WP_CLI::log( 'Grapeshot Error pushing the record to rmq' );
				return '';
			} else {
				return $resp;
			}
		} else {
			WP_CLI::log( 'Error fetching Grapheshot data' . curl_error( $curl ) );
			return '';
		}
	}

	public function update_gs_meta_data( $id, $gs_meta ) {
		$gs_string = str_replace( "'",'"',rtrim( trim( str_replace( 'gs_channels=','',$gs_meta ) ),';' ) );
		$gs_string = json_encode( $gs_string );
		$site_url = get_site_url();
		$command = "wp post meta set $id gs_channels $gs_string --url='".$site_url."' --allow-root";
		exec( $command, $output, $return );
		if ( $return != 0 ) {
			WP_CLI::log( 'Error updating meta data ' . print_r( $output, true ) );
		}
	}


	// Get the queue Name based on the site
	public function get_queue_name() {
		$queue = 'grapeshot_cpt';
		return $queue;
	}


	// Send the Data to RMQ
	public function send_data_to_grapeshot_mq( $data ) {
		try {
			$this->mq_connection = $this->get_rmq_connection();
			$this->queue_name    = $this->get_queue_name();
			$this->json_data     = json_encode( $data );
			$channel             = $this->mq_connection->channel();
			$channel->queue_declare( $this->queue_name, false, true, false, false );
			$msg = new AMQPMessage( $this->json_data, array( 'delivery_mode' => 2 ) );
			$channel->basic_publish( $msg, '', $this->queue_name );
			$channel->close();
			$this->mq_connection->close();
			return true;
		} catch ( Exception $e ) {
			WP_CLI::log( 'Error pushing it to Queue - ' . $e->getMessage() );
			return false;
		}
	}

	// Get the rabbit MQ connection
	public function get_rmq_connection() {
		global $rabbitmq_server;
		if ( empty( $rabbitmq_server ) ) {
			$rabbitmq_server = array();
		}
		$rabbitmq_server = wp_parse_args( $rabbitmq_server, array(
			'host'     => $this->mq_host,
			'port'     => $this->mq_port,
			'username' => $this->mq_user,
			'password' => $this->mq_pass,
		) );

		return new AMQPStreamConnection(
			$rabbitmq_server['host'],
			$rabbitmq_server['port'],
			$rabbitmq_server['username'],
			$rabbitmq_server['password'],
			$this->mq_vhost
		);
	}

}

WP_CLI::add_command( 'grapeshot', 'GrapeShot_CLI' );

