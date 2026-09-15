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

	<?php if ( is_singular( 'paroisse' ) || is_post_type_archive( 'pretre' ) ) : ?>
		<?php
		// Bandeau utilitaire — d'abord scopé à single-paroisse.php ("Refonte
		// design crème/anthracite/or"), désormais partagé avec l'archive
		// "Nos prêtres" (même palette). Classes renommées .paroisse-utility-
		// bar* -> .dz-utility-bar* en conséquence (même précédent déjà suivi
		// pour .archive-filters, voir DECISIONS.md). Le centre/la droite du
		// bandeau restent propres à chaque contexte : prochaine messe/
		// téléphone du curé sur une fiche paroisse, nom de l'évêque/
		// téléphone général du diocèse sur "Nos prêtres".
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
			$dz_bar_phone = dz_get_paroisse_contact_phone( $dz_bar_paroisse_id );
		} else {
			$dz_bar_eveque = dz_get_option( 'dz_eveque_nom' );
			$dz_bar_center = $dz_bar_eveque
				? sprintf(
					/* translators: %s: bishop's name */
					__( 'Évêque : %s', 'diocese-ziguinchor' ),
					$dz_bar_eveque
				)
				: '';
			$dz_bar_phone = dz_get_option( 'dz_contact_phone' );
		}
		?>
		<div class="dz-utility-bar">
			<div class="container d-flex flex-wrap align-items-center justify-content-between">
				<span class="dz-utility-bar-brand"><?php echo esc_html( get_bloginfo( 'name' ) . ' - ' . __( 'Sénégal', 'diocese-ziguinchor' ) ); ?></span>

				<?php if ( $dz_bar_center ) : ?>
					<span class="dz-utility-bar-center"><?php echo esc_html( $dz_bar_center ); ?></span>
				<?php endif; ?>

				<?php if ( $dz_bar_phone ) : ?>
					<a class="dz-utility-bar-phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_bar_phone ) ); ?>"><?php echo esc_html( $dz_bar_phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<header id="header" class="header d-flex align-items-center sticky-top">
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

			<?php get_template_part( 'template-parts/social-links', null, array( 'wrapper_class' => 'header-social-links' ) ); ?>

		</div>
	</header>

	<main class="main">
