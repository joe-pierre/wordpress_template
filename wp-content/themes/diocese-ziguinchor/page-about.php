<?php
/**
 * Template Name: À propos
 *
 * About the diocese page.
 *
 * Reuses about.html's ".about" section as-is: portrait image (the page's
 * featured image) with two overlaid PureCounter badges, and the page's own
 * WYSIWYG content for the title/intro text. The source template's
 * "feature-item"/"check-list" blocks and its unrelated "Team" section
 * (redundant with archive-pretre.php) are dropped — see DECISIONS.md.
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

	$dz_badge_bottom = dz_get_field( 'dz_about_badge_bottom', get_the_ID(), array() );
	$dz_badge_top    = dz_get_field( 'dz_about_badge_top', get_the_ID(), array() );
	?>

	<!-- About Section -->
	<section id="about" class="about section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<div class="row g-5 align-items-center">

				<div class="col-lg-6 position-relative">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="about-img" data-aos="fade-right">
							<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid', 'alt' => get_the_title() ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $dz_badge_bottom['dz_number'] ) ) : ?>
						<div class="experience-badge" data-aos="fade-up">
							<h2>
								<span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo esc_attr( $dz_badge_bottom['dz_number'] ); ?>" data-purecounter-duration="2"></span><?php echo esc_html( $dz_badge_bottom['dz_suffix'] ); ?>
							</h2>
							<p><?php echo esc_html( $dz_badge_bottom['dz_label'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $dz_badge_top['dz_number'] ) ) : ?>
						<div class="projects-badge" data-aos="fade-left">
							<h2>
								<span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo esc_attr( $dz_badge_top['dz_number'] ); ?>" data-purecounter-duration="2"></span><?php echo esc_html( $dz_badge_top['dz_suffix'] ); ?>
							</h2>
							<p><?php echo esc_html( $dz_badge_top['dz_label'] ); ?></p>
						</div>
					<?php endif; ?>
				</div>

				<div class="col-lg-6" data-aos="fade-left">
					<?php the_content(); ?>
				</div>

			</div>
		</div>
	</section><!-- /About Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
