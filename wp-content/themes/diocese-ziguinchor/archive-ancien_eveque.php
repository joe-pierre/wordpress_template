<?php
/**
 * Ancien évêque listing template — "Archives > Les différents évêques".
 * Sorted chronologically (mandate start date, oldest first) by
 * dz_ancien_eveque_archive_query() in inc/cpt-ancien_eveque.php, not by
 * WordPress publication date.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Les différents évêques', 'diocese-ziguinchor' ),
		'breadcrumb' => __( 'Anciens évêques', 'diocese-ziguinchor' ),
	)
);
?>

<section id="anciens-eveques" class="anciens-eveques section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6">
						<?php get_template_part( 'template-parts/card-ancien-eveque' ); ?>
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
			<p><?php esc_html_e( 'Aucun ancien évêque référencé pour le moment.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
