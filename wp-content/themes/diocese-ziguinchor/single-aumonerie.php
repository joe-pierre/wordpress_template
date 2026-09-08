<?php
/**
 * Single aumonerie template.
 *
 * Same socle layout as conseil/service_diocesain/commission_diocesain/
 * mouvement/association (see DECISIONS.md, PROMPT 13/14). The
 * `type_aumonerie` term(s) are folded into the page-title subtitle, next to
 * "Responsable" when both are set — same subtitle slot already used by every
 * other organisational single template, no new markup needed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_responsable = dz_get_field( 'org_responsable' );
	$dz_types       = get_the_terms( get_the_ID(), 'type_aumonerie' );
	$dz_type_label  = ( $dz_types && ! is_wp_error( $dz_types ) )
		? implode( ', ', wp_list_pluck( $dz_types, 'name' ) )
		: '';

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
			/* translators: %s: chaplaincy type(s) */
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

	<!-- Aumonerie Details Section -->
	<section id="aumonerie-details" class="aumonerie-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>
				</div>

				<div class="col-lg-4">
					<?php get_template_part( 'template-parts/organisation-composition' ); ?>
				</div>
			</div>
		</div>
	</section><!-- /Aumonerie Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
