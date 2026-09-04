<?php
/**
 * Donations page — informative only in v1 (SPEC.md §3): a repeater of
 * payment modalities (bank transfer, Mobile Money...). No online payment
 * integration is implemented here — see BUGS_AND_ROADMAP.md.
 *
 * No source template page to convert; reuses about.html's ".feature-item"
 * markup/CSS for the modalities, consistent with single-sacrement.php's
 * steps (see DECISIONS.md).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title' => get_the_title(),
	)
);

while ( have_posts() ) :
	the_post();
	?>

	<!-- Dons Section -->
	<section id="dons" class="dons section">
		<div class="container" data-aos="fade-up">
			<?php the_content(); ?>

			<?php if ( function_exists( 'have_rows' ) && have_rows( 'dz_dons_modalites' ) ) : ?>
				<div class="about">
					<div class="features">
						<?php
						while ( have_rows( 'dz_dons_modalites' ) ) :
							the_row();
							$dz_type    = get_sub_field( 'dz_dons_modalite_type' );
							$dz_titre   = get_sub_field( 'dz_dons_modalite_titre' );
							$dz_details = get_sub_field( 'dz_dons_modalite_details' );
							?>
							<div class="feature-item mb-4">
								<div class="feature-icon">
									<i class="bi <?php echo esc_attr( dz_get_don_icon_class( $dz_type ) ); ?>"></i>
								</div>
								<div class="feature-content">
									<?php if ( $dz_titre ) : ?>
										<h4><?php echo esc_html( $dz_titre ); ?></h4>
									<?php endif; ?>
									<?php if ( $dz_details ) : ?>
										<p><?php echo nl2br( esc_html( $dz_details ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section><!-- /Dons Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
