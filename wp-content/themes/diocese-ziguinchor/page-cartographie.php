<?php
/**
 * Template Name: Cartographie du diocèse
 *
 * Simple map embed (Google Maps), as a placeholder "en attendant des
 * données géographiques plus précises" (SPEC.md §3, PROMPT 16) — not the
 * richer multi-marker map of every paroisse that a real geodata set would
 * allow later (see BUGS_AND_ROADMAP.md).
 *
 * Reuses the diocese's general address already configured on "Réglages du
 * thème" (`dz_contact_address`, same field page-contact.php's own map embed
 * already reads) instead of adding a second ACF field asking editors to
 * re-enter the same address a second time.
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

	$dz_address = dz_get_option( 'dz_contact_address' );
	?>

	<!-- Cartographie Section -->
	<section id="cartographie" class="cartographie section">
		<div class="container" data-aos="fade-up">
			<?php the_content(); ?>

			<?php if ( $dz_address ) : ?>
				<div class="map-container">
					<iframe
						src="<?php echo esc_url( sprintf( 'https://www.google.com/maps?q=%s&output=embed', rawurlencode( $dz_address ) ) ); ?>"
						width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
						title="<?php echo esc_attr( get_the_title() ); ?>"
					></iframe>
				</div>
			<?php endif; ?>
		</div>
	</section><!-- /Cartographie Section -->

<?php endwhile; ?>

<?php get_footer(); ?>
