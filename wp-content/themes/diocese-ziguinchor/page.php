<?php
/**
 * Generic page template — every static page listed in SPEC.md §3 that
 * doesn't need its own structured ACF fields (Mot de l'évêque, Contacts,
 * Évêché, Chancellerie, Historique, L'évêque, Vie Consacrée, Prières,
 * Pèlerinages Nationaux, Pèlerinages Diocésains, Devenir bénévole,
 * Secrétariat diocésain — see bin/seed-static-pages.php), plus any other
 * plain content page created later.
 *
 * Reuses starter-page.html's minimal shape as-is: page-title banner, then a
 * single `.section`/`.container` wrapping the_content() — the source
 * template's own "use this page as a starter for your own custom pages"
 * placeholder, with no CPT-specific markup to strip.
 *
 * "Devenir bénévole" needs a dedicated Contact Form 7 form: no special
 * template logic for that here, same as page-contact.php — once the plugin
 * is installed (TODO.md Phase 6), its `[contact-form-7 ...]` shortcode is
 * pasted directly into this page's content in wp-admin, right there with
 * the rest of the editorial text (see bin/seed-static-pages.php for the
 * placeholder note already seeded into that page's content).
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
	?>

	<!-- Page Content Section -->
	<section id="page-content" class="page-content section">
		<div class="container" data-aos="fade-up">
			<?php the_content(); ?>
		</div>
	</section><!-- /Page Content Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
