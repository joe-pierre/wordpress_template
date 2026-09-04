<?php
/**
 * Single evenement (event) template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$dz_dates_label = dz_get_evenement_dates_label( get_the_ID() );
	$dz_lieu        = dz_get_evenement_lieu( get_the_ID() );
	$dz_inscription = dz_get_field( 'evenement_lien_inscription' );
	$dz_status      = dz_get_evenement_status( get_the_ID() );

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => $dz_dates_label,
		)
	);
	?>

	<!-- Evenement Details Section -->
	<section id="evenement-details" class="evenement-details section">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">

				<div class="col-lg-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded mb-4', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>

					<?php if ( $dz_status ) : ?>
						<?php
						$dz_badge_class = array(
							'a_venir'  => 'text-bg-primary',
							'en_cours' => 'text-bg-success',
							'termine'  => 'text-bg-secondary',
						);
						?>
						<span class="badge <?php echo esc_attr( isset( $dz_badge_class[ $dz_status ] ) ? $dz_badge_class[ $dz_status ] : 'text-bg-primary' ); ?> mb-3">
							<?php echo esc_html( dz_get_evenement_status_label( $dz_status ) ); ?>
						</span>
					<?php endif; ?>

					<?php the_content(); ?>
				</div>

				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							<ul class="list-unstyled mb-3">
								<?php if ( $dz_dates_label ) : ?>
									<li class="mb-2"><i class="bi bi-calendar-event"></i> <?php echo esc_html( $dz_dates_label ); ?></li>
								<?php endif; ?>
								<?php if ( $dz_lieu['label'] ) : ?>
									<li class="mb-2">
										<i class="bi bi-geo-alt-fill"></i>
										<?php if ( $dz_lieu['url'] ) : ?>
											<a href="<?php echo esc_url( $dz_lieu['url'] ); ?>"><?php echo esc_html( $dz_lieu['label'] ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $dz_lieu['label'] ); ?>
										<?php endif; ?>
									</li>
								<?php endif; ?>
							</ul>

							<?php if ( $dz_inscription ) : ?>
								<a href="<?php echo esc_url( $dz_inscription ); ?>" class="btn btn-primary w-100" target="_blank" rel="noopener">
									<?php esc_html_e( "S'inscrire", 'diocese-ziguinchor' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section><!-- /Evenement Details Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
