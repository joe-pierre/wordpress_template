<?php
/**
 * Single paroisse (parish) template.
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

	$dz_adresse    = dz_get_field( 'paroisse_adresse' );
	$dz_secteur    = dz_get_field( 'paroisse_secteur' );
	$dz_telephone  = dz_get_field( 'paroisse_telephone' );
	$dz_email      = dz_get_field( 'paroisse_email' );
	$dz_map        = dz_get_field( 'paroisse_localisation' );
	$dz_jours      = array(
		'lundi'    => __( 'Lundi', 'diocese-ziguinchor' ),
		'mardi'    => __( 'Mardi', 'diocese-ziguinchor' ),
		'mercredi' => __( 'Mercredi', 'diocese-ziguinchor' ),
		'jeudi'    => __( 'Jeudi', 'diocese-ziguinchor' ),
		'vendredi' => __( 'Vendredi', 'diocese-ziguinchor' ),
		'samedi'   => __( 'Samedi', 'diocese-ziguinchor' ),
		'dimanche' => __( 'Dimanche', 'diocese-ziguinchor' ),
	);
	?>

	<!-- Paroisse Details Section -->
	<section id="paroisse-details" class="paroisse-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-7">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>

					<?php if ( function_exists( 'have_rows' ) && have_rows( 'paroisse_horaires_messes' ) ) : ?>
						<h3><?php esc_html_e( 'Horaires des messes', 'diocese-ziguinchor' ); ?></h3>
						<ul class="mass-schedule-list">
							<?php
							while ( have_rows( 'paroisse_horaires_messes' ) ) :
								the_row();
								$dz_jour  = get_sub_field( 'paroisse_horaire_jour' );
								$dz_heure = get_sub_field( 'paroisse_horaire_heure' );
								?>
								<li>
									<strong><?php echo esc_html( isset( $dz_jours[ $dz_jour ] ) ? $dz_jours[ $dz_jour ] : $dz_jour ); ?></strong>
									— <?php echo esc_html( $dz_heure ); ?>
								</li>
								<?php
							endwhile;
							?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="col-lg-5">
					<?php
					$dz_clergy_ids = wp_list_pluck(
						array_merge(
							dz_get_paroisse_clergy( get_the_ID(), 'cure' ),
							dz_get_paroisse_clergy( get_the_ID(), 'vicaire' )
						),
						'ID'
					);
					?>
					<?php if ( $dz_clergy_ids ) : ?>
						<?php
						$dz_clergy_query = new WP_Query(
							array(
								'post_type'      => 'pretre',
								'post__in'       => $dz_clergy_ids,
								'orderby'        => 'post__in',
								'posts_per_page' => -1,
							)
						);
						?>
						<?php if ( $dz_clergy_query->have_posts() ) : ?>
							<div id="paroisse-clergy" class="team section">
								<h3><?php esc_html_e( 'Clergé', 'diocese-ziguinchor' ); ?></h3>
								<div class="row g-4">
									<?php
									while ( $dz_clergy_query->have_posts() ) :
										$dz_clergy_query->the_post();
										?>
										<div class="col-md-6">
											<?php get_template_part( 'template-parts/card-pretre' ); ?>
										</div>
										<?php
									endwhile;
									?>
								</div>
							</div>
							<?php wp_reset_postdata(); ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section><!-- /Paroisse Details Section -->

	<?php if ( $dz_adresse || $dz_secteur || $dz_telephone || $dz_email || $dz_map ) : ?>
		<!-- Paroisse Coordonnées Section -->
		<section id="paroisse-coordonnees" class="contact section">
			<div class="container">
				<div class="row gy-4">
					<div class="col-lg-5">
						<div class="contact-info-panel">
							<div class="contact-info-header">
								<h3><?php esc_html_e( 'Coordonnées', 'diocese-ziguinchor' ); ?></h3>
							</div>
							<div class="contact-info-cards">
								<?php if ( $dz_adresse || $dz_secteur ) : ?>
									<div class="info-card">
										<div class="icon-container"><i class="bi bi-pin-map-fill"></i></div>
										<div class="card-content">
											<h4><?php esc_html_e( 'Adresse', 'diocese-ziguinchor' ); ?></h4>
											<p><?php echo esc_html( trim( implode( ' — ', array_filter( array( $dz_adresse, $dz_secteur ) ) ) ) ); ?></p>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $dz_telephone ) : ?>
									<div class="info-card">
										<div class="icon-container"><i class="bi bi-telephone-fill"></i></div>
										<div class="card-content">
											<h4><?php esc_html_e( 'Téléphone', 'diocese-ziguinchor' ); ?></h4>
											<p><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $dz_telephone ) ); ?>"><?php echo esc_html( $dz_telephone ); ?></a></p>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $dz_email ) : ?>
									<div class="info-card">
										<div class="icon-container"><i class="bi bi-envelope-open"></i></div>
										<div class="card-content">
											<h4><?php esc_html_e( 'E-mail', 'diocese-ziguinchor' ); ?></h4>
											<p><a href="mailto:<?php echo esc_attr( $dz_email ); ?>"><?php echo esc_html( $dz_email ); ?></a></p>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if ( ! empty( $dz_map['lat'] ) && ! empty( $dz_map['lng'] ) ) : ?>
						<div class="col-lg-7">
							<div class="map-container">
								<iframe
									src="<?php echo esc_url( sprintf( 'https://www.google.com/maps?q=%s,%s&output=embed', $dz_map['lat'], $dz_map['lng'] ) ); ?>"
									width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
									title="<?php echo esc_attr( get_the_title() ); ?>"
								></iframe>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section><!-- /Paroisse Coordonnées Section -->
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
