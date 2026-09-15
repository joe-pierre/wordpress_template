<?php
/**
 * Single paroisse (parish) template — design "crème / anthracite / or"
 * (remplace entièrement le précédent habillage glassmorphism, voir
 * DECISIONS.md). Le regroupement des horaires de messes par jour reste géré
 * par dz_get_paroisse_horaires_par_jour() (inc/cpt-paroisse.php), partagé
 * avec le bandeau utilitaire de header.php ("Prochaine messe").
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_paroisse_id = get_the_ID();

	// dz_no_em_dash() : round 3, voir DECISIONS.md — ces champs texte libre
	// peuvent contenir un tiret cadratin tapé par un rédacteur ; jamais
	// appliqué à the_content() (WYSIWYG), où c'est de la prose ordinaire.
	$dz_soustitre       = dz_no_em_dash( dz_get_field( 'paroisse_soustitre' ) );
	$dz_adresse         = dz_no_em_dash( dz_get_field( 'paroisse_adresse' ) );
	$dz_secteur         = dz_no_em_dash( dz_get_field( 'paroisse_secteur' ) );
	// Téléphone/e-mail affichés dans "Nous trouver" : priorité au curé de la
	// paroisse (dz_get_paroisse_contact_phone()/_email(), avec repli sur les
	// champs paroisse_telephone/paroisse_email) — voir DECISIONS.md round 3.
	$dz_contact_phone   = dz_get_paroisse_contact_phone( $dz_paroisse_id );
	$dz_contact_email   = dz_get_paroisse_contact_email( $dz_paroisse_id );
	$dz_secretariat     = dz_no_em_dash( dz_get_field( 'paroisse_secretariat_horaire' ) );
	$dz_map             = dz_get_field( 'paroisse_localisation' );
	$dz_confessions     = dz_no_em_dash( dz_get_field( 'paroisse_confessions_note' ) );
	$dz_stat_fondation  = dz_get_field( 'paroisse_stat_fondation' );
	$dz_stat_communaute = dz_get_field( 'paroisse_stat_communautes' );
	$dz_vie_paroisse    = dz_get_field( 'paroisse_vie_paroisse', $dz_paroisse_id, array() );

	$dz_jours     = dz_get_jours_semaine();
	$dz_horaires  = dz_get_paroisse_horaires_par_jour( $dz_paroisse_id );
	$dz_today_key = array_keys( $dz_jours )[ (int) current_time( 'N' ) - 1 ];

	$dz_stats = array();
	if ( $dz_stat_fondation ) {
		$dz_stats[] = array(
			'value' => $dz_stat_fondation,
			'label' => __( 'Fondation de la paroisse', 'diocese-ziguinchor' ),
		);
	}
	if ( $dz_stat_communaute ) {
		$dz_stats[] = array(
			'value' => $dz_stat_communaute,
			'label' => __( 'Communautés de base', 'diocese-ziguinchor' ),
		);
	}
	if ( ! empty( $dz_horaires['dimanche'] ) ) {
		$dz_stats[] = array(
			'value' => count( $dz_horaires['dimanche'] ),
			'label' => __( 'Messes dominicales', 'diocese-ziguinchor' ),
		);
	}

	$dz_doyenne_terms = get_the_terms( $dz_paroisse_id, 'doyenne' );
	$dz_doyenne_label = ( $dz_doyenne_terms && ! is_wp_error( $dz_doyenne_terms ) ) ? $dz_doyenne_terms[0]->name : '';

	$dz_cure_list = dz_get_paroisse_clergy( $dz_paroisse_id, 'cure' );
	$dz_cure      = $dz_cure_list ? $dz_cure_list[0] : null;
	$dz_vicaires  = dz_get_paroisse_clergy( $dz_paroisse_id, 'vicaire' );

	$dz_cta_url = dz_get_field( 'paroisse_cta_rdv_url' );
	if ( ! $dz_cta_url ) {
		$dz_cta_url = dz_get_page_url_by_template( 'page-contact.php' );
	}
	?>

	<div class="paroisse-page">

		<!-- Paroisse Hero -->
		<section id="paroisse-hero" class="paroisse-hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="paroisse-hero-bg" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( $dz_paroisse_id, 'large' ) ); ?>');"></div>
			<?php endif; ?>
			<div class="paroisse-hero-overlay"></div>
			<div class="container" data-aos="fade-up">
				<?php if ( $dz_doyenne_label ) : ?>
					<p class="paroisse-eyebrow">
						<?php
						printf(
							/* translators: %s: deanery/zone name */
							esc_html__( 'Paroisse · %s', 'diocese-ziguinchor' ),
							esc_html( $dz_doyenne_label )
						);
						?>
					</p>
				<?php else : ?>
					<p class="paroisse-eyebrow"><?php esc_html_e( 'Paroisse', 'diocese-ziguinchor' ); ?></p>
				<?php endif; ?>
				<h1 class="paroisse-hero-title"><?php the_title(); ?></h1>
				<?php if ( $dz_soustitre ) : ?>
					<p class="paroisse-hero-subtitle"><?php echo esc_html( $dz_soustitre ); ?></p>
				<?php endif; ?>
			</div>
		</section><!-- /Paroisse Hero -->

		<!-- Fil d'Ariane -->
		<nav class="paroisse-breadcrumb">
			<div class="container">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'diocese-ziguinchor' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'paroisse' ) ); ?>"><?php esc_html_e( 'Paroisses', 'diocese-ziguinchor' ); ?></a></li>
					<li class="current"><?php the_title(); ?></li>
				</ol>
			</div>
		</nav>

		<section id="paroisse-content" class="paroisse-content section">
			<div class="container" data-aos="fade-up">
				<div class="row gy-5">

					<div class="col-lg-8">

						<?php if ( $dz_horaires ) : ?>
							<div class="paroisse-block" id="paroisse-horaires">
								<h2 class="paroisse-section-title"><?php esc_html_e( 'Horaires des messes', 'diocese-ziguinchor' ); ?></h2>
								<div class="mass-table">
									<?php foreach ( $dz_jours as $dz_jour_key => $dz_jour_label ) : ?>
										<?php if ( empty( $dz_horaires[ $dz_jour_key ] ) ) : ?>
											<?php continue; ?>
										<?php endif; ?>
										<?php
										$dz_is_sunday = ( 'dimanche' === $dz_jour_key );
										$dz_is_today  = ( $dz_jour_key === $dz_today_key );
										?>
										<div class="mass-row<?php echo $dz_is_sunday ? ' mass-row--sunday' : ''; ?>">
											<span class="mass-row-day">
												<?php echo esc_html( $dz_jour_label ); ?>
												<?php if ( $dz_is_today && ! $dz_is_sunday ) : ?>
													<span class="mass-row-today-dot"></span>
													<span class="visually-hidden"><?php esc_html_e( "Aujourd'hui", 'diocese-ziguinchor' ); ?></span>
												<?php endif; ?>
											</span>
											<span class="mass-row-times">
												<?php foreach ( $dz_horaires[ $dz_jour_key ] as $dz_horaire ) : ?>
													<span class="mass-badge"><?php echo esc_html( $dz_horaire['heure'] ); ?></span>
													<?php if ( $dz_horaire['libelle'] ) : ?>
														<span class="mass-badge mass-badge--event"><?php echo esc_html( $dz_horaire['libelle'] ); ?></span>
													<?php endif; ?>
												<?php endforeach; ?>
											</span>
										</div>
									<?php endforeach; ?>
								</div>
								<?php if ( $dz_confessions ) : ?>
									<p class="paroisse-note"><?php echo esc_html( $dz_confessions ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<div class="paroisse-block" id="paroisse-presentation">
							<h2 class="paroisse-section-title"><?php esc_html_e( 'La paroisse', 'diocese-ziguinchor' ); ?></h2>
							<div class="paroisse-presentation-text">
								<?php the_content(); ?>
							</div>

							<?php if ( $dz_stats ) : ?>
								<div class="paroisse-stats">
									<?php foreach ( $dz_stats as $dz_stat ) : ?>
										<div class="paroisse-stat">
											<span class="paroisse-stat-value"><?php echo esc_html( $dz_stat['value'] ); ?></span>
											<span class="paroisse-stat-label"><?php echo esc_html( $dz_stat['label'] ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( $dz_adresse || $dz_secteur || $dz_contact_phone || $dz_contact_email || $dz_map ) : ?>
							<div class="paroisse-block" id="paroisse-coordonnees">
								<h2 class="paroisse-section-title"><?php esc_html_e( 'Nous trouver', 'diocese-ziguinchor' ); ?></h2>

								<div class="paroisse-find-cards">
									<?php if ( $dz_adresse || $dz_secteur ) : ?>
										<div class="paroisse-find-card">
											<span class="paroisse-eyebrow"><?php esc_html_e( 'Adresse', 'diocese-ziguinchor' ); ?></span>
											<p><?php echo esc_html( trim( implode( ' - ', array_filter( array( $dz_adresse, $dz_secteur ) ) ) ) ); ?></p>
										</div>
									<?php endif; ?>

									<?php if ( $dz_contact_phone ) : ?>
										<div class="paroisse-find-card">
											<span class="paroisse-eyebrow"><?php esc_html_e( 'Téléphone', 'diocese-ziguinchor' ); ?></span>
											<p><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_contact_phone ) ); ?>"><?php echo esc_html( $dz_contact_phone ); ?></a></p>
											<?php if ( $dz_secretariat ) : ?>
												<p class="paroisse-find-note">
													<?php
													printf(
														/* translators: %s: opening hours (free text) */
														esc_html__( 'Secrétariat : %s', 'diocese-ziguinchor' ),
														esc_html( $dz_secretariat )
													);
													?>
												</p>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if ( $dz_contact_email ) : ?>
										<div class="paroisse-find-card">
											<span class="paroisse-eyebrow"><?php esc_html_e( 'E-mail', 'diocese-ziguinchor' ); ?></span>
											<p><a href="<?php echo esc_url( 'mailto:' . $dz_contact_email ); ?>"><?php echo esc_html( $dz_contact_email ); ?></a></p>
										</div>
									<?php endif; ?>
								</div>

								<?php if ( ! empty( $dz_map['lat'] ) && ! empty( $dz_map['lng'] ) ) : ?>
									<div class="map-container">
										<iframe
											src="<?php echo esc_url( sprintf( 'https://www.google.com/maps?q=%s,%s&output=embed', $dz_map['lat'], $dz_map['lng'] ) ); ?>"
											width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
											title="<?php echo esc_attr( get_the_title() ); ?>"
										></iframe>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</div>

					<div class="col-lg-4">
						<div class="paroisse-sidebar">

							<?php if ( $dz_cure ) : ?>
								<div class="paroisse-cure-card">
									<?php if ( has_post_thumbnail( $dz_cure->ID ) ) : ?>
										<div class="paroisse-cure-photo">
											<?php echo get_the_post_thumbnail( $dz_cure->ID, 'medium_large', array( 'alt' => get_the_title( $dz_cure->ID ) ) ); ?>
										</div>
									<?php endif; ?>
									<div class="paroisse-cure-body">
										<p class="paroisse-eyebrow"><?php esc_html_e( 'Curé de la paroisse', 'diocese-ziguinchor' ); ?></p>
										<h3 class="paroisse-cure-name"><a href="<?php echo esc_url( get_permalink( $dz_cure->ID ) ); ?>"><?php echo esc_html( get_the_title( $dz_cure->ID ) ); ?></a></h3>
										<?php $dz_cure_bio = dz_no_em_dash( dz_get_field( 'pretre_bio_courte', $dz_cure->ID ) ); ?>
										<?php if ( $dz_cure_bio ) : ?>
											<p class="paroisse-cure-bio"><?php echo esc_html( $dz_cure_bio ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $dz_vicaires ) : ?>
								<ul class="paroisse-vicaires-list">
									<?php foreach ( $dz_vicaires as $dz_vicaire ) : ?>
										<li>
											<a href="<?php echo esc_url( get_permalink( $dz_vicaire->ID ) ); ?>"><?php echo esc_html( get_the_title( $dz_vicaire->ID ) ); ?></a>
											<span><?php esc_html_e( 'Vicaire', 'diocese-ziguinchor' ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( $dz_vie_paroisse ) : ?>
								<div class="paroisse-vie-card">
									<h3><?php esc_html_e( 'Vie de la paroisse', 'diocese-ziguinchor' ); ?></h3>
									<ul>
										<?php foreach ( $dz_vie_paroisse as $dz_activite ) : ?>
											<li>
												<span class="paroisse-vie-frequence"><?php echo esc_html( dz_no_em_dash( $dz_activite['paroisse_vie_frequence'] ) ); ?></span>
												<span class="paroisse-vie-description"><?php echo esc_html( dz_no_em_dash( $dz_activite['paroisse_vie_description'] ) ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<a href="<?php echo esc_url( $dz_cta_url ); ?>" class="paroisse-cta-btn"><?php esc_html_e( 'Demander un rendez-vous', 'diocese-ziguinchor' ); ?></a>

						</div>
					</div>

				</div>
			</div>
		</section>

	</div><!-- /.paroisse-page -->

<?php endwhile; ?>

<?php get_footer(); ?>
