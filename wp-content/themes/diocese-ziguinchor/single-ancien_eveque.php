<?php
/**
 * Single ancien_eveque template.
 *
 * Reuses single-pretre.php's "author-profile"/"author-card" markup as-is —
 * same shape (photo, name, a short designation line, biography), minus the
 * contact/paroisse blocks that don't apply here (no socle, no relations for
 * this CPT — see DECISIONS.md).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_periode = dz_get_ancien_eveque_periode_label( get_the_ID() );

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => $dz_periode,
		)
	);
	?>

	<!-- Ancien Eveque Profile Section -->
	<section id="ancien-eveque-profile" class="author-profile section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<div class="author-profile-1">
				<div class="row">

					<div class="col-lg-4 mb-4 mb-lg-0">
						<div class="author-card" data-aos="fade-up">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="author-image">
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid rounded', 'alt' => get_the_title() ) ); ?>
								</div>
							<?php endif; ?>

							<div class="author-info">
								<h2><?php the_title(); ?></h2>

								<?php if ( $dz_periode ) : ?>
									<p class="designation"><?php echo esc_html( $dz_periode ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="col-lg-8">
						<div class="author-content" data-aos="fade-up" data-aos-delay="200">
							<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) !== '' ) : ?>
								<div class="content-header">
									<h3><?php esc_html_e( 'Biographie', 'diocese-ziguinchor' ); ?></h3>
								</div>
								<div class="content-body">
									<?php the_content(); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section><!-- /Ancien Eveque Profile Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
