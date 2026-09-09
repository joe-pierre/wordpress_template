<?php
/**
 * Template Name: Contact
 *
 * Contact page. Contact Form 7 mounts via the_content(): once the plugin
 * is installed (TODO.md Phase 6), the form's [contact-form-7 ...]
 * shortcode is pasted into this page's content in wp-admin — see
 * DECISIONS.md. Also renders a Google Maps embed built from the diocese's
 * address, and the coordinates/social links already configured on the
 * "Réglages du thème" options page (Tâche 2).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title' => get_the_title(),
	)
);

while ( have_posts() ) :
	the_post();

	$dz_phone   = dz_get_option( 'dz_contact_phone' );
	$dz_email   = dz_get_option( 'dz_contact_email' );
	$dz_address = dz_get_option( 'dz_contact_address' );
	?>

	<!-- Contact Section -->
	<section id="contact" class="contact section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<div class="contact-wrapper">

				<?php if ( $dz_address ) : ?>
					<div class="map-container">
						<iframe
							src="<?php echo esc_url( sprintf( 'https://www.google.com/maps?q=%s&output=embed', rawurlencode( $dz_address ) ) ); ?>"
							width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
							title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
						></iframe>
					</div>
				<?php endif; ?>

				<div class="contact-columns">

					<div class="contact-info-panel">
						<div class="contact-info-header">
							<h3><?php esc_html_e( 'Nos coordonnées', 'diocese-ziguinchor' ); ?></h3>
						</div>

						<div class="contact-info-cards">
							<?php if ( $dz_address ) : ?>
								<div class="info-card">
									<div class="icon-container"><i class="bi bi-pin-map-fill"></i></div>
									<div class="card-content">
										<h4><?php esc_html_e( 'Adresse', 'diocese-ziguinchor' ); ?></h4>
										<p><?php echo nl2br( esc_html( $dz_address ) ); ?></p>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $dz_email ) : ?>
								<div class="info-card">
									<div class="icon-container"><i class="bi bi-envelope-open"></i></div>
									<div class="card-content">
										<h4><?php esc_html_e( 'E-mail', 'diocese-ziguinchor' ); ?></h4>
										<p><a href="<?php echo esc_url( 'mailto:' . $dz_email ); ?>"><?php echo esc_html( $dz_email ); ?></a></p>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $dz_phone ) : ?>
								<div class="info-card">
									<div class="icon-container"><i class="bi bi-telephone-fill"></i></div>
									<div class="card-content">
										<h4><?php esc_html_e( 'Téléphone', 'diocese-ziguinchor' ); ?></h4>
										<p><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_phone ) ); ?>"><?php echo esc_html( $dz_phone ); ?></a></p>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<div class="social-links-panel">
							<h5><?php esc_html_e( 'Suivez-nous', 'diocese-ziguinchor' ); ?></h5>
							<?php get_template_part( 'template-parts/social-links', null, array( 'wrapper_class' => 'social-icons' ) ); ?>
						</div>
					</div>

					<div class="form-container">
						<h3><?php esc_html_e( 'Envoyez-nous un message', 'diocese-ziguinchor' ); ?></h3>
						<p><?php esc_html_e( "Une question, une demande de rendez-vous ? Écrivez-nous, nous vous répondrons dans les meilleurs délais.", 'diocese-ziguinchor' ); ?></p>

						<?php
						/*
						 * Contact Form 7 mount point: once the plugin is installed
						 * (TODO.md Phase 6), paste the form's [contact-form-7 ...]
						 * shortcode into this page's content. For a true Bootstrap
						 * floating-label look, wrap each field tag in a
						 * <div class="form-floating">...<label>...</label></div> —
						 * the matching CSS already exists in main.css. Baseline
						 * styling for CF7's own default markup is provided either way.
						 */
						the_content();
						?>
					</div>

				</div>

			</div>
		</div>
	</section><!-- /Contact Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
