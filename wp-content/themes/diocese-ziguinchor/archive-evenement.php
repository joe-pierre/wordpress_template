<?php
/**
 * Evenement agenda/listing template — filtered to upcoming/ongoing events
 * (see inc/cpt-evenement.php: dz_evenement_archive_query(), SPEC.md §4),
 * plus filter tabs by evenement_type (Agenda Diocésain / Agenda de l'évêque
 * — SPEC.md §3/§8, PROMPT 16), same `?evenement_type=<slug>` + no-dedicated-
 * archive-route pattern already used for type_aumonerie (see DECISIONS.md).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Agenda', 'diocese-ziguinchor' ),
		'breadcrumb' => __( 'Agenda', 'diocese-ziguinchor' ),
	)
);

$dz_current_type = isset( $_GET['evenement_type'] ) ? sanitize_title( wp_unslash( $_GET['evenement_type'] ) ) : '';
$dz_archive_link = get_post_type_archive_link( 'evenement' );
$dz_type_terms   = get_terms(
	array(
		'taxonomy'   => 'evenement_type',
		'hide_empty' => false,
	)
);
?>

<section id="evenements" class="evenements section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( $dz_type_terms && ! is_wp_error( $dz_type_terms ) ) : ?>
			<ul class="nav nav-pills archive-filters justify-content-center mb-4">
				<li class="nav-item">
					<a class="nav-link<?php echo '' === $dz_current_type ? ' active' : ''; ?>" href="<?php echo esc_url( $dz_archive_link ); ?>">
						<?php esc_html_e( 'Tous', 'diocese-ziguinchor' ); ?>
					</a>
				</li>
				<?php foreach ( $dz_type_terms as $dz_term ) : ?>
					<li class="nav-item">
						<a class="nav-link<?php echo $dz_current_type === $dz_term->slug ? ' active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'evenement_type', $dz_term->slug, $dz_archive_link ) ); ?>">
							<?php echo esc_html( dz_get_evenement_type_archive_label( $dz_term->slug ) ?: $dz_term->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

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
