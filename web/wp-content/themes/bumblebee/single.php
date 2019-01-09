<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bumblebee
 */

wp_enqueue_style( 'bumblebee-style-article', get_stylesheet_directory_uri() . '/article.css', [], '1.0.2' );

get_header();
?>

<div class="site-container">
	<main class="site-content">
		<div class="pure-g pre-article-ad text-center">
		<?php
		bumblebee_render_ad(
			uniqid( 'ad' ),
			[
				'slot-name'        => 'prearticle',
				'sizes'            => '970x250,970x90,728x90,3x3',
				'targeting'        => [
					'pos'      => 'prearticle',
					'location' => 'top',
				],
				'responsive-sizes' => [
					'mobile'       => [ [ 320, 50 ] ],
					'tablet'       => [ [ 728, 90 ] ],
					'desktop'      => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ] ],
					'large_screen' => [ [ 970, 250 ], [ 970, 90 ], [ 728, 90 ], [ 3, 3 ] ],
				],
			]
		);
		?>
		</div>
		<div class="pure-g opening-content">
	<?php
	if ( have_posts() ) :
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'post' );
		endwhile;
	endif;
?>
	<div class="pure-u-md-7-24 pure-u-lg-7-24 pure-u-xl-7-24 hide-on-mobile article-sidebar">
		<div class="sidebar-ad-wrapper text-center">
			<aside class="sidebar">
				<div class="article-sidebar-top-ad">
				<?php
				bumblebee_render_ad(
					uniqid( 'ad' ),
					[
						'slot-name'        => 'railtop',
						'sizes'            => '970x250,970x90,728x90,3x3',
						'targeting'        => [
							'pos'      => 'railtop',
							'location' => 'rail',
						],
						'responsive-sizes' => [
							'mobile'       => [],
							'tablet'       => [],
							'desktop'      => [ [ 300, 250 ] ],
							'large_screen' => [ [ 300, 250 ] ],
						],
					]
				);
				?>
				</div>
				<div class="article-sidebar-middle-ad">
				<?php
				bumblebee_render_ad(
					uniqid( 'ad' ),
					[
						'slot-name'        => 'railmiddle',
						'sizes'            => '970x250,970x90,728x90,3x3',
						'targeting'        => [
							'pos'      => 'railmiddle',
							'location' => 'rail',
						],
						'responsive-sizes' => [
							'mobile'       => [],
							'tablet'       => [],
							'desktop'      => [],
							'large_screen' => [ [ 160, 600 ], [ 300, 250 ], [ 300, 600 ] ],
						],
					]
				);
				?>
				</div>
				<div class="article-sidebar-scroll-ad">
				<?php
				bumblebee_render_ad(
					uniqid( 'ad' ),
					[
						'slot-name'        => 'railscroll',
						'sizes'            => '970x250,970x90,728x90,3x3',
						'targeting'        => [
							'pos'      => 'railscroll',
							'location' => 'rail',
						],
						'responsive-sizes' => [
							'mobile'       => [],
							'tablet'       => [],
							'desktop'      => [],
							'large_screen' => [ [ 160, 600 ], [ 300, 250 ], [ 300, 600 ], [ 300, 1050 ] ],
						],
					]
				);
				?>
				</div>
			</aside>
		</div>
	</div>
		</div>
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
</div>
	<?php

	get_footer();
	?>
