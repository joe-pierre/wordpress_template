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
?>
<!-- Blog Hero Section -->
<section id="blog-hero" class="blog-hero section">

	<div class="container-fluid p-0" data-aos="fade">

		<div class="blog-hero-slider swiper init-swiper">
			<script type="application/json" class="swiper-config">
				{
					"loop": true,
					"speed": 1000,
					"effect": "fade",
					"autoplay": {
						"delay": 5000
					},
					"slidesPerView": 1,
					"navigation": {
						"nextEl": ".swiper-button-next",
						"prevEl": ".swiper-button-prev"
					}
				}
			</script>

			<div class="swiper-wrapper">
				<?php foreach ( $dz_hero_slides as $dz_slide ) : ?>
					<div class="swiper-slide">
						<div class="blog-hero-item">
							<?php if ( ! empty( $dz_slide['dz_front_hero_slide_image'] ) ) : ?>
								<img src="<?php echo esc_url( $dz_slide['dz_front_hero_slide_image'] ); ?>" alt="<?php echo esc_attr( $dz_slide['dz_front_hero_slide_titre'] ); ?>" class="img-fluid">
							<?php endif; ?>
							<div class="blog-hero-content">
								<h1><?php echo esc_html( $dz_slide['dz_front_hero_slide_titre'] ); ?></h1>
								<?php if ( ! empty( $dz_slide['dz_front_hero_slide_texte'] ) ) : ?>
									<p><?php echo esc_html( $dz_slide['dz_front_hero_slide_texte'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $dz_slide['dz_front_hero_slide_lien'] ) ) : ?>
									<a href="<?php echo esc_url( $dz_slide['dz_front_hero_slide_lien'] ); ?>" class="read-more">
										<?php esc_html_e( 'En savoir plus', 'diocese-ziguinchor' ); ?> <i class="bi bi-arrow-right"></i>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div><!-- End slide item -->
				<?php endforeach; ?>
			</div>

			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>

		</div>

	</div>

</section><!-- /Blog Hero Section -->
