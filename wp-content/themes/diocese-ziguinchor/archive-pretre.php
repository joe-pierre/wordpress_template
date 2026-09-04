<?php
/**
 * Pretre (clergy) directory/listing template.
 *
 * Not listed in SPEC.md §5's file tree, but required by TODO.md Phase 5 and
 * SPEC.md §11 ("Prêtres — liste + détail — annuaire du clergé") — see
 * DECISIONS.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Nos prêtres', 'diocese-ziguinchor' ),
		'breadcrumb' => __( 'Prêtres', 'diocese-ziguinchor' ),
	)
);
?>

<section id="team" class="team section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( have_posts() ) : ?>
			<div class="row g-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
						<?php get_template_part( 'template-parts/card-pretre' ); ?>
					</div><!-- End Team Member -->
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
			<p><?php esc_html_e( 'Aucun prêtre pour le moment.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
