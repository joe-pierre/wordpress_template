<?php
/**
 * 404 error template.
 *
 * Reuses 404.html's ".error-404" markup as-is (icon, code, title, text,
 * search box, "back to home" button) — the search form uses the exact
 * Bootstrap ".input-group"/".form-control"/".search-btn" markup that
 * ".error-404 .search-box" is styled for in main.css, not the
 * ".search-widget" form in searchform.php (different component, different
 * CSS), wired to the real WordPress search (`?s=`) instead of the source
 * template's `action="#"` placeholder.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Error 404 Section -->
<section id="error-404" class="error-404 section">

	<div class="container" data-aos="fade-up" data-aos-delay="100">

		<div class="text-center">
			<div class="error-icon mb-4" data-aos="zoom-in" data-aos-delay="200">
				<i class="bi bi-exclamation-circle"></i>
			</div>

			<h1 class="error-code mb-4" data-aos="fade-up" data-aos-delay="300">404</h1>

			<h2 class="error-title mb-3" data-aos="fade-up" data-aos-delay="400"><?php esc_html_e( 'Page introuvable', 'diocese-ziguinchor' ); ?></h2>

			<p class="error-text mb-4" data-aos="fade-up" data-aos-delay="500">
				<?php esc_html_e( "La page que vous recherchez a peut-être été déplacée, renommée, ou n'existe plus.", 'diocese-ziguinchor' ); ?>
			</p>

			<div class="search-box mb-4" data-aos="fade-up" data-aos-delay="600">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form">
					<div class="input-group">
						<input type="text" name="s" class="form-control" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Rechercher une page…', 'diocese-ziguinchor' ); ?>" aria-label="<?php esc_attr_e( 'Rechercher une page', 'diocese-ziguinchor' ); ?>">
						<button class="btn search-btn" type="submit">
							<i class="bi bi-search"></i>
						</button>
					</div>
				</form>
			</div>

			<div class="error-action" data-aos="fade-up" data-aos-delay="700">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( "Retour à l'accueil", 'diocese-ziguinchor' ); ?></a>
			</div>
		</div>

	</div>

</section><!-- /Error 404 Section -->

<?php get_footer(); ?>
