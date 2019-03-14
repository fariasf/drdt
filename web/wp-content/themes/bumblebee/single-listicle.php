<?php
/**
 * The template for displaying all single listicles
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-listicle
 *
 * @package bumblebee
 */

/**
 * Include the functions file
 */
add_filter(
	'ad_unit_path_2',
	function () {
		return 'collection';
	}
);

add_filter(
	'ad_unit_path_3',
	function () {
		return 'listicle';
	}
);
require_once 'functions.listicle.php';

get_header();
?>
<style type="text/css">
	<?php require get_stylesheet_directory() . '/listicle.css'; ?>
</style>
<main class="listicle-page">
	<section class="advertisement">
		<?php
		bumblebee_render_ad(
			uniqid( 'ad' ),
			[
				'slot-name'        => 'prearticle',
				'sizes'            => '970x250,970x90,728x90,3x3',
				'targeting'        => [
					'pos'      => 'prearticle',
					'location' => 'top',
					'tf'       => 'atf',
				],
				'responsive-sizes' => [
					'mobile'       => [ [ 320, 50 ] ],
					'tablet'       => [ [ 320, 50 ] ],
					'desktop'      => [ [ 728, 90 ], [ 640, 360 ], [ 3, 3 ] ],
					'large_screen' => [ [ 970, 550 ], [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
				],
			]
		);

		?>
	</section>
	<section class="content-wrapper pure-g">
		<?php
		$data         = listicle_data();
		$card_data    = $data[0];
		$total_cards  = $data[1];
		$current_card = 1;
		$section_num  = 1;
		?>
		<?php for ( $j = 1; $j <= $total_cards; $j += 3 ) : ?>
			<section class="content pure-g">
				<section class="social-menu-desktop pure-u-lg-2-24">
					<?php
					if ( 1 === $j ) {
						get_template_part( 'template-parts/social-share', 'none' );
					}
					?>
				</section>
				<section class=" pure-u-1 pure-u-lg-14-24">
					<?php if ( 1 === $j ) { ?>
						<div class="contentbarheader">
							<?php
							$category = get_the_category();
							if ( ! empty( $category[0] ) ) {
								$category      = $category[0];
								$category_name = $category->name;
								$category_link = get_category_link( $category->term_id );
							}
							?>
							<a href="<?php echo esc_url( $category_link ); ?>" class="post-category-label">
								<?php echo esc_attr( $category->name ); ?>
							</a>
							<h1 class="entry-title"><?php echo esc_html( get_the_title() ); ?></h1>
							<?php bumblebee_posted_by(); ?>
							<section class="social-menu-mobile">
								<?php get_template_part( 'template-parts/social-share', 'none' ); ?>
							</section>
							<div class="dek"><?php the_excerpt(); ?></div>
						</div>
						<?php
					}
					?>
					<div class="contentbar">
						<?php
						for ( $i = 0; $i < 3; $i++ ) :
							if ( ( $current_card ) <= ( $total_cards ) ) :
								$card_content = get_the_card_data( $current_card, $total_cards, $card_data[ $current_card ] );
								$card_image   = $card_content[0];
								$current_card = $card_content[1];
								$all_cards    = $card_content[2];
								$card_heading = $card_content[3];
								$card_brief   = $card_content[4];
								?>
								<div class="listicle-card">
									<?php echo wp_kses_post( $card_content[0] ); ?>
									<div class="card-number">
										<span class="current-page-count"><?php echo wp_kses_post( $current_card ) . ' '; ?></span><span class="total-page-count"><?php echo '/ ' . wp_kses_post( $all_cards ); ?></span>
									</div>
									<div class="card-content">
										<?php echo wp_kses_post( $card_heading ); ?>
										<p class="content"><?php echo wp_kses_post( $card_brief ); ?></p>
									</div>
								</div>
								<?php
								$current_card++;
							endif;
						endfor;
						?>
					</div>
				</section>
				<section class="sidebar pure-u-1 pure-u-lg-8-24">
					<?php
					$slot_name  = 'scroll';
					$slot_sizes = [ [ 300, 1050 ], [ 300, 600 ], [ 300, 250 ], [ 160, 600 ] ];
					$tf_slot    = 'btf';
					if ( 1 === $section_num ) {
						$slot_name  = 'top';
						$slot_sizes = [ [ 300, 250 ] ];
						$tf_slot    = 'atf';
					} elseif ( 2 === $section_num ) {
						$slot_name  = 'middle';
						$slot_sizes = [ [ 300, 250 ], [ 300, 600 ] ];
						$tf_slot    = 'atf';
					}
					bumblebee_render_ad(
						uniqid( 'ad' ),
						[
							'slot-name'        => 'rail' . $slot_name,
							'responsive-sizes' => [
								'large_screen' => $slot_sizes,
							],
							'targeting'        => [
								'tf'       => $tf_slot,
								'pos'      => 'rail' . $slot_name,
								'location' => $slot_name,
							],
						]
					);
					?>
				</section>
			</section>
			<?php if ( ( $current_card ) <= ( $total_cards ) ) : ?>
				<section class="full-width-ad">
					<?php
					bumblebee_render_ad(
						uniqid( 'ad' ),
						[
							'slot-name'        => $slot_name,
							'responsive-sizes' => [
								'mobile'       => [ [ 320, 50 ], [ 300, 250 ], [ 3, 3 ] ],
								'tablet'       => [ [ 320, 50 ], [ 300, 250 ], [ 3, 3 ] ],
								'desktop'      => [ [ 728, 90 ], [ 640, 360 ], [ 3, 3 ], [ 300, 250 ] ],
								'large_screen' => [ [ 970, 550 ], [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ], [ 300, 250 ] ],
							],
							'targeting'        => [
								'tf'       => $tf_slot,
								'pos'      => $slot_name,
								'location' => $slot_name,
							],
						]
					);
					?>
				</section>
			<?php endif; ?>
			<?php $section_num++; ?>
		<?php endfor; ?>
		<section class="content pure-g" style="width: 100%;">
			<section class="social-menu-desktop pure-u-lg-2-24">&nbsp;</section>
			<section class="pure-u-1 pure-u-lg-14-24">
				<?php if ( is_active_sidebar( 'listicle-after-content' ) ) : ?>
					<?php dynamic_sidebar( 'listicle-after-content' ); ?>
				<?php endif; ?>
			</section>
			<section class="pure-u-1 pure-u-lg-8-24">
				<?php if ( is_active_sidebar( 'listicle-after-content-right-rail' ) ) : ?>
					<?php dynamic_sidebar( 'listicle-after-content-right-rail' ); ?>
				<?php endif; ?>
			</section>
		</section>
		<div class="postarticle_ad">
			<?php
			bumblebee_render_ad(
				uniqid( 'ad' ),
				[
					'slot-name'        => 'postarticle',
					'sizes'            => '970x550,970x250,970x90,728x90,3x3,300x250',
					'responsive-sizes' => [
						'mobile'       => [ [ 320, 50 ], [ 300, 250 ], [ 3, 3 ] ],
						'tablet'       => [ [ 320, 50 ], [ 300, 250 ], [ 3, 3 ] ],
						'desktop'      => [ [ 728, 90 ], [ 640, 360 ], [ 3, 3 ], [ 300, 250 ] ],
						'large_screen' => [ [ 970, 550 ], [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ], [ 300, 250 ] ],
					],
				]
			);
			?>
		</div>
</main>
<?php
get_footer();
?>
