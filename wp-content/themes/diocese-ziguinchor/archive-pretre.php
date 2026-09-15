<?php
/**
 * Pretre (clergy) directory/listing template — refonte design "crème/
 * anthracite/or" (voir DECISIONS.md "Refonte archive prêtres"), même
 * palette et mêmes composants partagés (.dz-eyebrow/.dz-breadcrumb/
 * .dz-utility-bar/.dz-hero-title) que single-paroisse.php. Remplace
 * l'ancien gabarit générique (`template-parts/page-title.php` + grille
 * "team-member" reprise d'about.html).
 *
 * Filtre par rôle (`?fonction=`) et recherche (`?q=`, sur le nom du prêtre
 * et/ou de sa paroisse) : deux query vars qui pilotent directement la
 * requête principale (voir dz_pretre_archive_query() dans
 * inc/cpt-pretre.php) — même page réellement rechargée, même pagination
 * WordPress native que les autres archives filtrées du thème
 * (`?evenement_type=`, `?type_aumonerie=`), plutôt qu'un filtrage
 * client-side qui plafonnerait la taille de l'annuaire.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$dz_archive_link     = get_post_type_archive_link( 'pretre' );
$dz_current_fonction = isset( $_GET['fonction'] ) ? sanitize_key( wp_unslash( $_GET['fonction'] ) ) : '';
$dz_search_term      = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$dz_fonction_labels  = dz_get_pretre_fonction_labels();
$dz_fonction_counts  = dz_get_pretre_fonction_counts();
$dz_stats            = dz_get_diocese_stats();
$dz_soustitre        = dz_get_option( 'dz_pretres_soustitre' );

// Préserve la recherche en cours quand on change de pastille de rôle.
$dz_pill_args = ( '' !== $dz_search_term ) ? array( 'q' => $dz_search_term ) : array();

$dz_vocations_titre = dz_get_option( 'dz_vocations_titre', __( 'Prier pour les vocations', 'diocese-ziguinchor' ) );
$dz_vocations_texte = dz_get_option(
	'dz_vocations_texte',
	__( "Chaque vocation sacerdotale naît et grandit dans la prière d'une communauté. Soutenez la formation des futurs prêtres du diocèse.", 'diocese-ziguinchor' )
);
$dz_vocations_cta   = dz_get_option( 'dz_vocations_cta_url' );
if ( ! $dz_vocations_cta ) {
	$dz_vocations_cta = dz_get_page_url_by_template( 'page-dons.php' );
}
?>

<div class="pretre-page">

	<!-- Pretre Hero -->
	<section id="pretre-hero" class="pretre-hero">
		<div class="container" data-aos="fade-up">
			<div class="row align-items-center gy-4">
				<div class="col-lg-8">
					<p class="dz-eyebrow"><?php esc_html_e( 'Le clergé diocésain', 'diocese-ziguinchor' ); ?></p>
					<h1 class="dz-hero-title"><?php esc_html_e( 'Nos prêtres', 'diocese-ziguinchor' ); ?></h1>
					<?php if ( $dz_soustitre ) : ?>
						<p class="dz-hero-subtitle"><?php echo esc_html( $dz_soustitre ); ?></p>
					<?php endif; ?>
				</div>
				<div class="col-lg-4">
					<div class="pretre-hero-stats">
						<div class="pretre-hero-stat">
							<span class="pretre-hero-stat-value"><?php echo esc_html( $dz_stats['pretres'] ); ?></span>
							<span class="pretre-hero-stat-label"><?php esc_html_e( 'Prêtres', 'diocese-ziguinchor' ); ?></span>
						</div>
						<div class="pretre-hero-stat">
							<span class="pretre-hero-stat-value"><?php echo esc_html( $dz_stats['paroisses'] ); ?></span>
							<span class="pretre-hero-stat-label"><?php esc_html_e( 'Paroisses', 'diocese-ziguinchor' ); ?></span>
						</div>
						<div class="pretre-hero-stat">
							<span class="pretre-hero-stat-value"><?php echo esc_html( $dz_stats['doyennes'] ); ?></span>
							<span class="pretre-hero-stat-label"><?php esc_html_e( 'Doyennés', 'diocese-ziguinchor' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- /Pretre Hero -->

	<!-- Fil d'Ariane -->
	<nav class="dz-breadcrumb">
		<div class="container">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'diocese-ziguinchor' ); ?></a></li>
				<?php /* "Le Diocèse" est un intitulé de rubrique du menu (SPEC.md §11), pas une page dédiée : pas de lien. */ ?>
				<li><?php esc_html_e( 'Le Diocèse', 'diocese-ziguinchor' ); ?></li>
				<li class="current"><?php esc_html_e( 'Prêtres', 'diocese-ziguinchor' ); ?></li>
			</ol>
		</div>
	</nav>

	<section id="pretre-directory" class="pretre-directory section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">

			<div class="pretre-toolbar">
				<ul class="pretre-filters">
					<li>
						<a class="pretre-filter<?php echo ( '' === $dz_current_fonction ) ? ' active' : ''; ?>" href="<?php echo esc_url( $dz_pill_args ? add_query_arg( $dz_pill_args, $dz_archive_link ) : $dz_archive_link ); ?>">
							<?php esc_html_e( 'Tous', 'diocese-ziguinchor' ); ?>
						</a>
					</li>
					<?php foreach ( $dz_fonction_labels as $dz_fonction_key => $dz_fonction_label ) : ?>
						<?php if ( empty( $dz_fonction_counts[ $dz_fonction_key ] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<li>
							<a class="pretre-filter<?php echo ( $dz_current_fonction === $dz_fonction_key ) ? ' active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array_merge( $dz_pill_args, array( 'fonction' => $dz_fonction_key ) ), $dz_archive_link ) ); ?>">
								<?php echo esc_html( $dz_fonction_label ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>

				<form class="pretre-search" method="get" action="<?php echo esc_url( $dz_archive_link ); ?>">
					<?php if ( $dz_current_fonction ) : ?>
						<input type="hidden" name="fonction" value="<?php echo esc_attr( $dz_current_fonction ); ?>">
					<?php endif; ?>
					<label class="visually-hidden" for="pretre-search-input"><?php esc_html_e( 'Rechercher un prêtre ou une paroisse', 'diocese-ziguinchor' ); ?></label>
					<input type="search" id="pretre-search-input" name="q" value="<?php echo esc_attr( $dz_search_term ); ?>" placeholder="<?php esc_attr_e( 'Rechercher un prêtre ou une paroisse', 'diocese-ziguinchor' ); ?>">
					<button type="submit" aria-label="<?php esc_attr_e( 'Rechercher', 'diocese-ziguinchor' ); ?>"><i class="bi bi-search"></i></button>
				</form>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="row g-4">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
							<?php get_template_part( 'template-parts/card-pretre' ); ?>
						</div>
						<?php
					endwhile;
					?>
				</div>

				<div class="pretre-pagination d-flex justify-content-center mt-5">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => '<i class="bi bi-chevron-left"></i>',
							'next_text' => '<i class="bi bi-chevron-right"></i>',
						)
					);
					?>
				</div>
			<?php else : ?>
				<p class="pretre-empty">
					<?php
					if ( $dz_current_fonction || '' !== $dz_search_term ) {
						esc_html_e( 'Aucun prêtre ne correspond à ces critères.', 'diocese-ziguinchor' );
					} else {
						esc_html_e( 'Aucun prêtre pour le moment.', 'diocese-ziguinchor' );
					}
					?>
				</p>
			<?php endif; ?>

		</div>
	</section><!-- /Pretre Directory -->

	<!-- Vocations CTA -->
	<section id="pretre-vocations" class="pretre-vocations">
		<div class="container">
			<div class="pretre-vocations-inner" data-aos="fade-up">
				<div class="pretre-vocations-text">
					<h2><?php echo esc_html( $dz_vocations_titre ); ?></h2>
					<?php if ( $dz_vocations_texte ) : ?>
						<p><?php echo esc_html( $dz_vocations_texte ); ?></p>
					<?php endif; ?>
				</div>
				<a href="<?php echo esc_url( $dz_vocations_cta ); ?>" class="pretre-vocations-btn"><?php esc_html_e( 'Soutenir le séminaire', 'diocese-ziguinchor' ); ?></a>
			</div>
		</div>
	</section><!-- /Vocations CTA -->

</div><!-- /.pretre-page -->

<?php get_footer(); ?>
