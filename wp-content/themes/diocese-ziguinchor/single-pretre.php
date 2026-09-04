<?php
/**
 * Single pretre (priest) template.
 *
 * Reuses author-profile.html's "author-profile" / "contact" markup as-is
 * (see CONVENTIONS.md and DECISIONS.md) — a priest profile has the same
 * shape (photo, name, role, bio, contact) as the source template's author
 * profile page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_fonction   = dz_get_field( 'pretre_fonction' );
	$dz_statut     = dz_get_field( 'pretre_statut' );
	$dz_ordination = dz_get_field( 'pretre_date_ordination' );
	$dz_telephone  = dz_get_field( 'pretre_telephone' );
	$dz_email      = dz_get_field( 'pretre_email' );
	$dz_paroisse   = dz_get_field( 'pretre_paroisse' );

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => $dz_fonction ? dz_get_pretre_fonction_label( $dz_fonction ) : '',
		)
	);
	?>

	<!-- Pretre Profile Section -->
	<section id="pretre-profile" class="author-profile section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<div class="author-profile-1">
				<div class="row">

					<div class="col-lg-4 mb-4 mb-lg-0">
						<div class="author-card" data-aos="fade-up">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="author-image">
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid rounded', 'alt' => get_the_title() ) ); ?>
								</div>
							<?php endif; ?>

							<div class="author-info">
								<h2><?php the_title(); ?></h2>

								<?php if ( $dz_fonction ) : ?>
									<p class="designation"><?php echo esc_html( dz_get_pretre_fonction_label( $dz_fonction ) ); ?></p>
								<?php endif; ?>

								<?php if ( $dz_ordination ) : ?>
									<div class="author-bio">
										<?php
										echo esc_html(
											sprintf(
												/* translators: %s: ordination date */
												__( 'Ordonné le %s', 'diocese-ziguinchor' ),
												date_i18n( get_option( 'date_format' ), strtotime( $dz_ordination ) )
											)
										);
										?>
									</div>
								<?php elseif ( $dz_statut && 'en_fonction' !== $dz_statut ) : ?>
									<div class="author-bio"><?php echo esc_html( dz_get_pretre_statut_label( $dz_statut ) ); ?></div>
								<?php endif; ?>

								<?php if ( $dz_telephone || $dz_email ) : ?>
									<div class="social-links">
										<?php if ( $dz_telephone ) : ?>
											<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_telephone ) ); ?>" aria-label="<?php esc_attr_e( 'Téléphone', 'diocese-ziguinchor' ); ?>"><i class="bi bi-telephone-fill"></i></a>
										<?php endif; ?>
										<?php if ( $dz_email ) : ?>
											<a href="<?php echo esc_url( 'mailto:' . $dz_email ); ?>" aria-label="<?php esc_attr_e( 'E-mail', 'diocese-ziguinchor' ); ?>"><i class="bi bi-envelope-fill"></i></a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="col-lg-8">
						<div class="author-content" data-aos="fade-up" data-aos-delay="200">
							<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) !== '' ) : ?>
								<div class="content-header">
									<h3><?php esc_html_e( 'Biographie', 'diocese-ziguinchor' ); ?></h3>
								</div>
								<div class="content-body">
									<?php the_content(); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section><!-- /Pretre Profile Section -->

	<!-- Pretre Paroisse Section -->
	<section id="pretre-paroisse" class="contact section">
		<div class="container">
			<div class="contact-info-panel">
				<div class="contact-info-header">
					<h3><?php esc_html_e( "Paroisse d'affectation", 'diocese-ziguinchor' ); ?></h3>
				</div>
				<div class="contact-info-cards">
					<?php if ( $dz_paroisse && get_post( $dz_paroisse ) ) : ?>
						<div class="info-card">
							<div class="icon-container"><i class="bi bi-geo-alt-fill"></i></div>
							<div class="card-content">
								<h4><a href="<?php echo esc_url( get_permalink( $dz_paroisse ) ); ?>"><?php echo esc_html( get_the_title( $dz_paroisse ) ); ?></a></h4>
								<?php $dz_paroisse_secteur = dz_get_field( 'paroisse_secteur', $dz_paroisse ); ?>
								<?php if ( $dz_paroisse_secteur ) : ?>
									<p><?php echo esc_html( $dz_paroisse_secteur ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php else : ?>
						<div class="info-card">
							<div class="icon-container"><i class="bi bi-info-circle"></i></div>
							<div class="card-content">
								<h4><?php echo esc_html( $dz_statut ? dz_get_pretre_statut_label( $dz_statut ) : __( 'Sans affectation', 'diocese-ziguinchor' ) ); ?></h4>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section><!-- /Pretre Paroisse Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
