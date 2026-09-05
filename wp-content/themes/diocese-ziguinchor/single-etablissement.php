<?php
/**
 * Single etablissement template.
 *
 * Same socle layout as conseil/service_diocesain/commission_diocesaine/
 * mouvement/association/aumonerie (see DECISIONS.md, PROMPT 13-15). "Type
 * d'établissement" is folded into the page-title subtitle next to
 * "Responsable" — same pattern as single-aumonerie.php's "Type" subtitle.
 * "Contact" (CPT-specific free-text field) gets its own sidebar tile,
 * reusing .contact-info-panel/.info-card like single-mouvement.php's
 * "Aumônier" tile — same kind of single-value info, not a repeater.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_responsable = dz_get_field( 'org_responsable' );
	$dz_type        = dz_get_field( 'type_etablissement' );
	$dz_type_label  = $dz_type ? dz_get_etablissement_type_label( $dz_type ) : '';
	$dz_contact     = dz_get_field( 'etablissement_contact' );

	$dz_subtitle_parts = array();
	if ( $dz_responsable ) {
		$dz_subtitle_parts[] = sprintf(
			/* translators: %s: person in charge */
			__( 'Responsable : %s', 'diocese-ziguinchor' ),
			$dz_responsable
		);
	}
	if ( $dz_type_label ) {
		$dz_subtitle_parts[] = sprintf(
			/* translators: %s: establishment type */
			__( 'Type : %s', 'diocese-ziguinchor' ),
			$dz_type_label
		);
	}

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => implode( ' · ', $dz_subtitle_parts ),
		)
	);
	?>

	<!-- Etablissement Details Section -->
	<section id="etablissement-details" class="etablissement-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>
				</div>

				<div class="col-lg-4">
					<?php if ( $dz_contact ) : ?>
						<div class="contact-info-panel mb-4">
							<div class="contact-info-header">
								<h3><?php esc_html_e( 'Contact', 'diocese-ziguinchor' ); ?></h3>
							</div>
							<div class="contact-info-cards">
								<div class="info-card">
									<div class="icon-container"><i class="bi bi-telephone-fill"></i></div>
									<div class="card-content">
										<h4><?php echo esc_html( $dz_contact ); ?></h4>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/organisation-composition' ); ?>
				</div>
			</div>
		</div>
	</section><!-- /Etablissement Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
