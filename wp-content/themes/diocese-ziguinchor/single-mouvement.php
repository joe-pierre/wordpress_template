<?php
/**
 * Single mouvement template.
 *
 * Same socle layout as conseil/service_diocesain/commission_diocesain/
 * association (see DECISIONS.md, PROMPT 13/14), plus an "Aumônier" tile for
 * the CPT-specific `mouvement_aumonier` relation (see
 * acf-json/group_dz_cpt_mouvement.json) — reuses .contact-info-panel/.info-card
 * exactly like the shared "Responsable" tile in
 * template-parts/organisation-composition.php, since it is the same kind of
 * single-person tile, not a list (unlike service_diocesain's
 * "Structures rattachées" repeater).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_responsable  = dz_get_field( 'org_responsable' );
	$dz_aumonier_id  = dz_get_field( 'mouvement_aumonier' );
	$dz_has_aumonier = $dz_aumonier_id && get_post( $dz_aumonier_id );

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => $dz_responsable ? sprintf(
				/* translators: %s: person in charge */
				__( 'Responsable : %s', 'diocese-ziguinchor' ),
				$dz_responsable
			) : '',
		)
	);
	?>

	<!-- Mouvement Details Section -->
	<section id="mouvement-details" class="mouvement-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>
				</div>

				<div class="col-lg-4">
					<?php if ( $dz_has_aumonier ) : ?>
						<div class="contact-info-panel mb-4">
							<div class="contact-info-header">
								<h3><?php esc_html_e( 'Aumônier', 'diocese-ziguinchor' ); ?></h3>
							</div>
							<div class="contact-info-cards">
								<div class="info-card">
									<div class="icon-container"><i class="bi bi-person-badge"></i></div>
									<div class="card-content">
										<h4>
											<a href="<?php echo esc_url( get_permalink( $dz_aumonier_id ) ); ?>">
												<?php echo esc_html( get_the_title( $dz_aumonier_id ) ); ?>
											</a>
										</h4>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/organisation-composition' ); ?>
				</div>
			</div>
		</div>
	</section><!-- /Mouvement Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
