<?php
/**
 * Homepage hero slider, called from front-page.php.
 *
 * @param array $args {
 *     @type array $slides The "dz_front_hero_slides" repeater value (see inc/acf-fields.php).
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_hero_slides = isset( $args['slides'] ) ? $args['slides'] : array();

if ( ! $dz_hero_slides ) {
	return;
}

// Fixed second button, resolved dynamically (never a hardcoded slug/ID) so
// it keeps working if the editorial team renames or re-slugs the page.
$dz_hero_bishop_word_url = dz_get_page_url_by_title( "Mot de l'évêque" );
?>
<!-- Front Hero Section -->
<section id="dz-front-hero" class="dz-front-hero section">

	<div class="container-fluid p-0" data-aos="fade">

		<div class="dz-front-hero-slider swiper init-swiper">
			<script type="application/json" class="swiper-config">
				{
					"loop": true,
					"speed": 1000,
					"effect": "fade",
					"autoplay": {
						"delay": 5000
					},
					"slidesPerView": 1,
					"pagination": {
						"el": ".swiper-pagination",
						"clickable": true
					}
				}
			</script>

			<div class="swiper-wrapper">
				<?php foreach ( $dz_hero_slides as $dz_slide ) : ?>
					<div class="swiper-slide">
						<div class="dz-front-hero-item"
							<?php if ( ! empty( $dz_slide['dz_front_hero_slide_image'] ) ) : ?>
								style="background-image: url('<?php echo esc_url( $dz_slide['dz_front_hero_slide_image'] ); ?>');"
							<?php endif; ?>
						>
							<div class="container">
								<div class="dz-front-hero-content">
									<span class="dz-front-hero-eyebrow"><?php esc_html_e( 'Église de Casamance', 'diocese-ziguinchor' ); ?></span>

									<h1 class="dz-front-hero-title"><?php echo esc_html( $dz_slide['dz_front_hero_slide_titre'] ); ?></h1>

									<?php if ( ! empty( $dz_slide['dz_front_hero_slide_texte'] ) ) : ?>
										<p class="dz-front-hero-text"><?php echo esc_html( $dz_slide['dz_front_hero_slide_texte'] ); ?></p>
									<?php endif; ?>

									<div class="dz-front-hero-actions">
										<?php if ( ! empty( $dz_slide['dz_front_hero_slide_lien'] ) ) : ?>
											<a href="<?php echo esc_url( $dz_slide['dz_front_hero_slide_lien'] ); ?>" class="dz-front-hero-btn dz-front-hero-btn-primary">
												<?php esc_html_e( 'En savoir plus', 'diocese-ziguinchor' ); ?> <i class="bi bi-arrow-right"></i>
											</a>
										<?php endif; ?>

										<a href="<?php echo esc_url( $dz_hero_bishop_word_url ); ?>" class="dz-front-hero-btn dz-front-hero-btn-secondary">
											<?php esc_html_e( "Mot de l'évêque", 'diocese-ziguinchor' ); ?>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div><!-- End slide item -->
				<?php endforeach; ?>
			</div>

			<?php
			/*
			 * A single pagination instance shared by all slides (Swiper grabs
			 * the first ".swiper-pagination" match for the whole slider) —
			 * it must stay a sibling of ".swiper-wrapper", never duplicated
			 * inside the loop above, or Swiper would render/bind it once per
			 * slide. Wrapped in its own ".container" purely so its left edge
			 * lines up with the site grid, same as ".dz-front-hero-content"'s
			 * own ".container" above — not for per-slide alignment.
			 */
			?>
			<div class="dz-front-hero-pagination-row">
				<div class="container">
					<div class="dz-front-hero-pagination-inner">
						<div class="swiper-pagination"></div>

						<span class="dz-front-hero-counter">
							<span class="dz-front-hero-counter-current">01</span> / <span class="dz-front-hero-counter-total"><?php echo esc_html( sprintf( '%02d', count( $dz_hero_slides ) ) ); ?></span>
						</span>
					</div>
				</div>
			</div>

		</div>

	</div>

</section><!-- /Front Hero Section -->
