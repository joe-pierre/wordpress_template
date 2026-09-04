<?php
/**
 * Single sacrement template.
 *
 * Reuses about.html's ".about .feature-item" markup/CSS for the steps
 * repeater (icon replaced with a step number) and contact.html's
 * ".contact .info-card" for the referring parish — see DECISIONS.md.
 * Documents to download use Bootstrap's own .list-group (no source-template
 * equivalent, and no need for new custom CSS).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title' => get_the_title(),
		)
	);

	$dz_paroisse_referente = dz_get_field( 'sacrement_paroisse_referente' );
	?>

	<!-- Sacrement Details Section -->
	<section id="sacrement-details" class="sacrement-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>

					<?php if ( function_exists( 'have_rows' ) && have_rows( 'sacrement_etapes' ) ) : ?>
						<div class="about">
							<h3><?php esc_html_e( 'Conditions et démarches', 'diocese-ziguinchor' ); ?></h3>
							<div class="features">
								<?php
								$dz_step = 0;
								while ( have_rows( 'sacrement_etapes' ) ) :
									the_row();
									++$dz_step;
									$dz_etape_titre       = get_sub_field( 'sacrement_etape_titre' );
									$dz_etape_description = get_sub_field( 'sacrement_etape_description' );
									?>
									<div class="feature-item mb-4">
										<div class="feature-icon">
											<span class="step-number"><?php echo esc_html( $dz_step ); ?></span>
										</div>
										<div class="feature-content">
											<?php if ( $dz_etape_titre ) : ?>
												<h4><?php echo esc_html( $dz_etape_titre ); ?></h4>
											<?php endif; ?>
											<?php if ( $dz_etape_description ) : ?>
												<p><?php echo esc_html( $dz_etape_description ); ?></p>
											<?php endif; ?>
										</div>
									</div>
									<?php
								endwhile;
								?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( function_exists( 'have_rows' ) && have_rows( 'sacrement_documents' ) ) : ?>
						<h3><?php esc_html_e( 'Documents à télécharger', 'diocese-ziguinchor' ); ?></h3>
						<ul class="list-group mb-4">
							<?php
							while ( have_rows( 'sacrement_documents' ) ) :
								the_row();
								$dz_document_titre  = get_sub_field( 'sacrement_document_titre' );
								$dz_document_fichier = get_sub_field( 'sacrement_document_fichier' );

								if ( empty( $dz_document_fichier['url'] ) ) {
									continue;
								}
								?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span><i class="bi bi-file-earmark-arrow-down"></i> <?php echo esc_html( $dz_document_titre ? $dz_document_titre : $dz_document_fichier['filename'] ); ?></span>
									<a href="<?php echo esc_url( $dz_document_fichier['url'] ); ?>" class="btn btn-sm btn-outline-primary" download>
										<?php esc_html_e( 'Télécharger', 'diocese-ziguinchor' ); ?>
									</a>
								</li>
								<?php
							endwhile;
							?>
						</ul>
					<?php endif; ?>
				</div>

				<?php if ( $dz_paroisse_referente && get_post( $dz_paroisse_referente ) ) : ?>
					<div class="col-lg-4">
						<div class="contact">
							<div class="contact-info-panel">
								<div class="contact-info-header">
									<h3><?php esc_html_e( 'Paroisse référente', 'diocese-ziguinchor' ); ?></h3>
								</div>
								<div class="contact-info-cards">
									<div class="info-card">
										<div class="icon-container"><i class="bi bi-geo-alt-fill"></i></div>
										<div class="card-content">
											<h4><a href="<?php echo esc_url( get_permalink( $dz_paroisse_referente ) ); ?>"><?php echo esc_html( get_the_title( $dz_paroisse_referente ) ); ?></a></h4>
											<?php $dz_referente_secteur = dz_get_field( 'paroisse_secteur', $dz_paroisse_referente ); ?>
											<?php if ( $dz_referente_secteur ) : ?>
												<p><?php echo esc_html( $dz_referente_secteur ); ?></p>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section><!-- /Sacrement Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
