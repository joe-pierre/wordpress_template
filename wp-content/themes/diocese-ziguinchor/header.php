<?php
/**
 * Common site header.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<?php
	// Sitewide contact/social bar, above the header on every page (not just
	// paroisse/pretre, unlike .dz-utility-bar below) — phone/email only shown
	// when set (dz_get_option()'s own empty-string fallback), social icons
	// via the shared template-part rather than a second copy of its markup.
	$dz_topbar_phone = dz_get_option( 'dz_contact_phone' );
	$dz_topbar_email = dz_get_option( 'dz_contact_email' );
	?>
	<div class="dz-topbar">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="dz-topbar-contact">
				<?php if ( $dz_topbar_phone ) : ?>
					<a class="dz-topbar-contact-item" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_topbar_phone ) ); ?>">
						<i class="bi bi-telephone"></i> <?php echo esc_html( $dz_topbar_phone ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $dz_topbar_email ) : ?>
					<a class="dz-topbar-contact-item dz-topbar-email" href="<?php echo esc_url( 'mailto:' . $dz_topbar_email ); ?>">
						<i class="bi bi-envelope"></i> <?php echo esc_html( $dz_topbar_email ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/social-links', null, array( 'wrapper_class' => 'dz-topbar-social' ) ); ?>
		</div>
	</div>

	<?php if ( is_singular( 'paroisse' ) || is_post_type_archive( 'pretre' ) ) : ?>
		<?php
		// Bandeau utilitaire — d'abord scopé à single-paroisse.php ("Refonte
		// design crème/anthracite/or"), désormais partagé avec l'archive
		// "Nos prêtres" (même palette). Classes renommées .paroisse-utility-
		// bar* -> .dz-utility-bar* en conséquence (même précédent déjà suivi
		// pour .archive-filters, voir DECISIONS.md). Le centre reste propre à
		// chaque contexte (prochaine messe / nom de l'évêque) ; le téléphone
		// a été retiré (voir DECISIONS.md "Bandeau contact/réseaux sociaux
		// sitewide") : le nouveau bandeau sitewide ci-dessus l'affiche déjà
		// en permanence, plus besoin de le répéter ici.
		if ( is_singular( 'paroisse' ) ) {
			$dz_bar_paroisse_id = get_queried_object_id();
			$dz_bar_next_mass   = dz_get_paroisse_next_mass( $dz_bar_paroisse_id );
			$dz_bar_center      = $dz_bar_next_mass
				? sprintf(
					/* translators: 1: day label (lowercase), 2: time (H:i) */
					__( 'Prochaine messe : %1$s %2$s', 'diocese-ziguinchor' ),
					mb_strtolower( $dz_bar_next_mass['jour_label'], 'UTF-8' ),
					$dz_bar_next_mass['heure']
				)
				: '';
		} else {
			$dz_bar_eveque = dz_get_option( 'dz_eveque_nom' );
			$dz_bar_center = $dz_bar_eveque
				? sprintf(
					/* translators: %s: bishop's name */
					__( 'Évêque : %s', 'diocese-ziguinchor' ),
					$dz_bar_eveque
				)
				: '';
		}
		?>
		<div class="dz-utility-bar">
			<div class="container d-flex flex-wrap align-items-center justify-content-between">
				<span class="dz-utility-bar-brand"><?php echo esc_html( get_bloginfo( 'name' ) . ' - ' . __( 'Sénégal', 'diocese-ziguinchor' ) ); ?></span>

				<?php if ( $dz_bar_center ) : ?>
					<span class="dz-utility-bar-center"><?php echo esc_html( $dz_bar_center ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<header id="header" class="header d-flex align-items-center sticky-top dz-header-glass">
		<div class="container position-relative d-flex align-items-center justify-content-between">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo d-flex align-items-center me-auto me-xl-0">
				<?php $dz_logo = dz_get_option( 'dz_logo' ); ?>
				<?php if ( ! empty( $dz_logo['url'] ) ) : ?>
					<img src="<?php echo esc_url( $dz_logo['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $dz_logo['alt'] ) ? $dz_logo['alt'] : get_bloginfo( 'name' ) ); ?>">
				<?php else : ?>
					<h1 class="sitename"><?php bloginfo( 'name' ); ?></h1>
				<?php endif; ?>
			</a>

			<nav id="navmenu" class="navmenu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '<ul>%3$s</ul>',
						// Depth 3, not 2: méga-menu rubrics need their
						// grandchildren (depth 2) walked — DZ_Walker_Nav_Menu
						// itself still suppresses depth 2 for the light
						// dropdowns, see its class docblock and DECISIONS.md
						// "Mega-menu hybride" (PROMPT 17).
						'depth'          => 3,
						'walker'         => new DZ_Walker_Nav_Menu(),
						'fallback_cb'    => false,
					)
				);
				?>
				<i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
			</nav>

			<a href="<?php echo esc_url( dz_get_page_url_by_template( 'page-dons.php' ) ); ?>" class="dz-header-donate-btn"><?php esc_html_e( 'Faire un don', 'diocese-ziguinchor' ); ?></a>

		</div>
	</header>

	<main class="main">
