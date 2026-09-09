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
