<?php
/**
 * Homepage "quick links" tiles, called from front-page.php.
 * Reuses the .organisation-card markup/classes (see DECISIONS.md) rather
 * than a new component; the caller resolves each URL and drops any item
 * that isn't available yet.
 *
 * @param array $args {
 *     @type array $items List of tiles, each: 'url', 'icon' (Bootstrap Icons
 *                          class), 'title', 'text'.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_quick_links = isset( $args['items'] ) ? $args['items'] : array();

if ( ! $dz_quick_links ) {
	return;
}
?>
<!-- Quick Links Section -->
<section id="quick-links" class="quick-links section">

	<div class="container section-title" data-aos="fade-up">
		<span class="description-title"><?php esc_html_e( 'Accès rapides', 'diocese-ziguinchor' ); ?></span>
		<h2><?php esc_html_e( 'Accès rapides', 'diocese-ziguinchor' ); ?></h2>
		<p><?php esc_html_e( 'Les rubriques les plus consultées du site du diocèse', 'diocese-ziguinchor' ); ?></p>
	</div><!-- End Section Title -->

	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<div class="row gy-4">
			<?php foreach ( $dz_quick_links as $dz_link ) : ?>
				<div class="col-lg-3 col-md-6">
					<a href="<?php echo esc_url( $dz_link['url'] ); ?>" class="organisation-card quick-link-card">
						<div class="organisation-card-icon">
							<i class="bi <?php echo esc_attr( $dz_link['icon'] ); ?>"></i>
						</div>
						<div class="organisation-card-body">
							<h3 class="organisation-card-title"><?php echo esc_html( $dz_link['title'] ); ?></h3>
							<p class="organisation-card-responsable"><?php echo esc_html( $dz_link['text'] ); ?></p>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</section><!-- /Quick Links Section -->
