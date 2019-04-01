<?php

/*
 * Video Ad Widget
 */
class Video_Ad_Widget extends WP_Widget {

	/*
	 * Constructor.
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'dfp_ad_widget',
			'description' => 'An Ad for the video hub page',
		);
		parent::__construct( 'dfp_ad_widget', 'VIDEO DFP AD', $widget_options );
	}

	/*
	 * form.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
				   name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<?php
		$slot_name = ! empty( $instance['slot_name'] ) ? $instance['slot_name'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'slot_name' ); ?>">Slot Name: ( Ex. top, middle, scroll,
				...)</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'slot_name' ); ?>"
				   name="<?php echo $this->get_field_name( 'slot_name' ); ?>"
				   value="<?php echo esc_attr( $slot_name ); ?>"/>
		</p>

		<?php
	}

	/*
	 * update.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = esc_html( $new_instance['title'] );
		$instance['slot_name'] = esc_html( $new_instance['slot_name'] );
		return $instance;
	}

	/*
	 * widget.
	 */
	public function widget( $args, $instance ) {
		$slot_name = ! empty( $instance['slot_name'] ) ? sanitize_text_field( $instance['slot_name'] ) : '';
		$map       = [
			'top'    => [
				'mobile'       => [ [ 320, 50 ] ],
				'tablet'       => [],
				'desktop'      => [ [ 728, 90 ] ],
				'large_screen' => [ [ 728, 90 ] ],
			],
			'middle' => [
				'mobile'       => [ [ 300, 250 ], [ 320, 50 ], [ 3, 3 ] ],
				'tablet'       => [],
				'desktop'      => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
				'large_screen' => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
			],
			'scroll' => [
				'mobile'       => [ [ 300, 250 ], [ 320, 50 ], [ 3, 3 ] ],
				'tablet'       => [],
				'desktop'      => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
				'large_screen' => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
			],
		];
		$tf_slot   = 'btf';
		if ( $slot_name === 'top' ) {
			$tf_slot = 'atf';
		}
		Ad_Stack::bumblebee_render_ad(
			uniqid( 'ad' ),
			[
				'slot-name'        => $slot_name,
				'responsive-sizes' => $map[ $slot_name ],
				'targeting'        => [
					'tf'       => $tf_slot,
					'pos'      => $slot_name,
					'location' => $slot_name,
				],
			]
		);
	}

}

add_action(
	'widgets_init',
	function () {
		register_widget( 'Video_Ad_Widget' );
	}
);
