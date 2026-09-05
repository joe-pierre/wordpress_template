<?php
/**
 * Fallback template required by WordPress.
 *
 * Every real content type in this theme already has its own explicit
 * template (single-*.php/archive-*.php for each CPT, single.php/archive.php
 * for posts, page.php for pages, search.php) — this file is the generic
 * safety net WordPress's template hierarchy falls back to when none of them
 * match (e.g. a plugin registering a post type with no matching template),
 * so it stays deliberately minimal and content-type-agnostic rather than
 * assuming any particular field/taxonomy exists.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title' => is_archive() || is_search() ? get_the_archive_title() : get_bloginfo( 'name' ),
	)
);
?>

<!-- Index Content Section -->
<section id="index-content" class="index-content section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-6">
						<article>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php
							if ( is_singular() ) {
								the_content();
							} else {
								the_excerpt();
							}
							?>
						</article>
					</div>
					<?php
				endwhile;
				?>
			</div>

			<div class="d-flex justify-content-center mt-4">
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
			<p><?php esc_html_e( 'Aucun contenu à afficher.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section><!-- /Index Content Section -->

<?php get_footer(); ?>
