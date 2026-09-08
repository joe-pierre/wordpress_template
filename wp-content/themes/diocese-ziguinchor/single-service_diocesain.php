<?php
/**
 * Single service_diocesain template.
 *
 * Same socle layout as conseil/commission_diocesain, plus a "Structures
 * rattachées" block for the CPT-specific `service_diocesain_sous_structures`
 * repeater (see SPEC.md §3 and acf-json/group_dz_cpt_service_diocesain.json)
 * — Bootstrap's own .list-group, no new CSS needed (same reasoning as the
 * sacrement documents list).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_responsable = dz_get_field( 'org_responsable' );

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

	<!-- Service Diocesain Details Section -->
	<section id="service-diocesain-details" class="service-diocesain-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>

					<?php if ( function_exists( 'have_rows' ) && have_rows( 'service_diocesain_sous_structures' ) ) : ?>
						<h3><?php esc_html_e( 'Structures rattachées', 'diocese-ziguinchor' ); ?></h3>
						<ul class="list-group mb-4">
							<?php
							while ( have_rows( 'service_diocesain_sous_structures' ) ) :
								the_row();
								$dz_sous_nom         = get_sub_field( 'service_diocesain_sous_structure_nom' );
								$dz_sous_responsable = get_sub_field( 'service_diocesain_sous_structure_responsable' );

								if ( ! $dz_sous_nom ) {
									continue;
								}
								?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span><?php echo esc_html( $dz_sous_nom ); ?></span>
									<?php if ( $dz_sous_responsable ) : ?>
										<span class="text-muted small"><?php echo esc_html( $dz_sous_responsable ); ?></span>
									<?php endif; ?>
								</li>
								<?php
							endwhile;
							?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="col-lg-4">
					<?php get_template_part( 'template-parts/organisation-composition' ); ?>
				</div>
			</div>
		</div>
	</section><!-- /Service Diocesain Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
