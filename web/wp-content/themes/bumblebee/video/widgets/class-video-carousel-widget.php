<?php
/**
 * Video Carousel
 *
 * Video Carousel.
 *
 * @package  Video
 */

/**
 * Class Hero Video Widget Carousel.
 */
class Video_Carousel_Widget extends WP_Widget {

	/**
	 * Video_Carousel_Widget constructor.
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'video_carousel',
			'description' => 'A carousel of videos based on a JW Player Playlist',
		);
		parent::__construct( 'video_carousel', 'Video Carousel', $widget_options );
	}

	/**
	 * Form.
	 *
	 * @param string $instance player instance.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>">Title:</label>
			<input class="widefat" type="text" id="<?php echo wp_kses_post( $this->get_field_id( 'title' ) ); ?>" name="<?php echo wp_kses_post( $this->get_field_name( 'title' ) ); ?>" value="<?php echo wp_kses_post( esc_attr( $title ) ); ?>" />
		</p>

		<?php $playlist_id = ! empty( $instance['playlist_id'] ) ? $instance['playlist_id'] : ''; ?>
		<p>
			<label for="<?php echo wp_kses_post( $this->get_field_id( 'playlist_id' ) ); ?>">Playlist ID:</label>
			<input class="widefat" type="text" id="<?php echo wp_kses_post( $this->get_field_id( 'playlist_id' ) ); ?>"
				   name="<?php echo wp_kses_post( $this->get_field_name( 'playlist_id' ) ); ?>"
				   value="<?php echo wp_kses_post( esc_attr( $playlist_id ) ); ?>"/>
		</p>

		<?php
	}

	/**
	 * Update.
	 *
	 * @param array $new_instance new instance.
	 * @param array $old_instance old instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = esc_html( $new_instance['title'] );
		$instance['playlist_id'] = esc_html( $new_instance['playlist_id'] );

		return $instance;
	}

	/**
	 *  Widget.
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$title       = sanitize_title( $instance['title'] );
		$playlist_id = sanitize_text_field( $instance['playlist_id'] );

		?>
		<div class="video-playlist">
			<div class="video-playlist-title">
				<span><?php echo wp_kses_post( $title ); ?></span>
			</div>
			<div class="video-playlist-container">
				<div class="owl-carousel owl-theme" data-playlist="<?php echo wp_kses_post( $playlist_id ); ?>" data-carousel="true">
					<script type="text/x-template">
						<div class="item" data-media-id="%media_id%" data-playlist-id="%playlist_id%"
							 data-playlist-title="<?php echo wp_kses_post($title); ?>"><img src="%image%"><h4>%title%</h4>
							<?php echo apply_filters( 'add_preview_content', '' ); ?>
						</div>
					</script>
				</div>
			</div>
		</div>
		<?php
	}
}

add_action(
	'widgets_init',
	function () {
		register_widget( 'Video_Carousel_Widget' );
	}
);

add_filter(
	'add_preview_content',
	function () {
		$html  = '<video class="video-preview" preload="metadata" crossorigin>';
		$html .= '<source src=""  id="src-attr" type="video/mp4">';
		$html .= '<track src="" id="track-attr" kind="metadata" default>';
		$html .= '</video>';
		$html .= '<div class="thumb"></div>';
		$html .= '<div class="gif"><img src=""/></div>';

		return $html;
	}
);
