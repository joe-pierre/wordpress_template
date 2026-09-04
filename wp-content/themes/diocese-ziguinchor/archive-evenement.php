<?php
/**
 * Evenement agenda/listing template — filtered to upcoming/ongoing events
 * (see inc/cpt-evenement.php: dz_evenement_archive_query(), SPEC.md §4).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Agenda diocésain', 'diocese-ziguinchor' ),
		'breadcrumb' => __( 'Événements', 'diocese-ziguinchor' ),
	)
);
?>

<section id="evenements" class="evenements section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6">
						<?php get_template_part( 'template-parts/card-evenement' ); ?>
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
			<p><?php esc_html_e( 'Aucun événement à venir pour le moment.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
