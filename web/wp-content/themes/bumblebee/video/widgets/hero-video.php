<?php

class Hero_Video_Widget extends WP_Widget {
	public function __construct() {
		$widget_options = array(
			'classname' => 'hero_video',
			'description' => 'A hero video player for the video hub page',
		);
		parent::__construct( 'hero_video', 'Hero Video', $widget_options );
	}

	public function form( $instance ) {
		$player_id = ! empty( $instance['player_id'] ) ? $instance['player_id'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'player_id' ); ?>">Player ID:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'player_id' ); ?>" name="<?php echo $this->get_field_name( 'player_id' ); ?>" value="<?php echo esc_attr( $player_id ); ?>" />
		</p>

		<?php $default_video_id = ! empty( $instance['default_video_id'] ) ? $instance['default_video_id'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'default_video_id' ); ?>">Default Video ID:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'default_video_id' ); ?>" name="<?php echo $this->get_field_name( 'default_video_id' ); ?>" value="<?php echo esc_attr( $default_video_id ); ?>" />
		</p>

		<?php $default_playlist_id = ! empty( $instance['default_playlist_id'] ) ? $instance['default_playlist_id'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'default_playlist_id' ); ?>">Default Playlist ID:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'default_playlist_id' ); ?>" name="<?php echo $this->get_field_name( 'default_playlist_id' ); ?>" value="<?php echo esc_attr( $default_playlist_id ); ?>" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['player_id'] = esc_html( $new_instance['player_id'] );
		$instance['default_video_id'] = esc_html( $new_instance['default_video_id'] );
		$instance['default_playlist_id'] = esc_html( $new_instance['default_playlist_id'] );

		return $instance;
	}

	public function widget( $args, $instance ) {
		$player_id = $instance['player_id'];
		$default_playlist_id = isset( $_GET['playlist_id'] ) ? sanitize_text_field( $_GET['playlist_id'] ) : sanitize_text_field( $instance['default_playlist_id'] );
		$default_video_id = isset( $_GET['video_id'] ) ? sanitize_text_field( $_GET['video_id'] ) : sanitize_text_field( $instance['default_video_id'] );
		if ( empty( $default_video_id ) ) {
			$default_video_id = $default_playlist_id;
		}
		$playlist_title = isset( $_GET['playlist_title'] ) ? sanitize_title( $_GET['playlist_title'] ) : 'Featured Playlist';
		?>
		<div id="wrapper">
			<div class="player-header">
				<div class="playlist-title">
					<h2><?php echo $playlist_title; ?></h2>
				</div>
			</div>
			<div class="player-container">
				<div class="player-stage">
					<div id="video-hub-player"></div>
					<script src="https://content.jwplatform.com/libraries/<?php echo $player_id; ?>.js"></script>
					<script type="text/javascript">
						var thehubplayer = jwplayer( document.getElementById( 'video-hub-player' ) );
						var options = {
							playlist: 'https://cdn.jwplayer.com/v2/media/<?php echo $default_video_id; ?>',
						};
						if ( window.tmbi_ad_data && window.tmbi_ad_data.jwplayer_advertising ) {
							options.advertising = window.tmbi_ad_data.jwplayer_advertising;
						}
						thehubplayer.setup( options );
					</script>
				</div>
				<div class="mobile-playlist-title">
					<h2><?php echo $playlist_title; ?></h2>
				</div>
				<div id="the-playlist" class="playlist" data-playlist="<?php echo $default_playlist_id; ?>">
					<script type="text/x-template"><div class="item" data-media-id="%media_id%" data-playlist-id="%playlist_id%" data-playlist-title="<?php echo $playlist_title; ?>"><img alt="Image Snap" src="%image%"><h4>%title%</h4></script>
				</div>
			</div> <!-- /.player-container -->
		</div>
		<?php
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'Hero_Video_Widget' );
} );
