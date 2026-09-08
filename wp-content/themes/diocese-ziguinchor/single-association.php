<?php
/**
 * Single association template.
 *
 * Same layout as conseil/service_diocesain/commission_diocesain (see
 * DECISIONS.md, PROMPT 13/14): description via the_content(), responsable +
 * membres via the shared template-parts/organisation-composition.php. No
 * field specific to this CPT.
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

	<!-- Association Details Section -->
	<section id="association-details" class="association-details section">
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
	</section><!-- /Association Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
