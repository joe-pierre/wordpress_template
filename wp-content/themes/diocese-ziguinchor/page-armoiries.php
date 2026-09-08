<?php
/**
 * Template Name: Nos armoiries
 *
 * Symbol-by-symbol description of the diocese's crest (CONTENT_PROMPTS.md
 * PROMPT 5, points 2-3): the page's own WYSIWYG content is a short intro,
 * followed by one alternating image/text row per `dz_armoiries_symboles`
 * repeater entry (group_dz_page_armoiries — inc/acf-fields.php).
 *
 * Reuses about.html's ".about"/".about-img" component (already reused by
 * page-about.php) row by row, alternating column order with Bootstrap's
 * native `.flex-row-reverse` utility — zero new custom CSS. Every row shows
 * the SAME image (the page's featured image, the crest itself): there is
 * only one real photo of the armoiries, not one per symbol — see
 * DECISIONS.md.
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

	<!-- Armoiries Intro Section -->
	<section id="about" class="about section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<div class="row g-5 align-items-center">

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="col-lg-6">
						<div class="about-img" data-aos="fade-right">
							<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid', 'alt' => get_the_title() ) ); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="col-lg-<?php echo has_post_thumbnail() ? '6' : '12'; ?>" data-aos="fade-left">
					<?php the_content(); ?>
				</div>

			</div>
		</div>
	</section><!-- /Armoiries Intro Section -->

	<?php if ( function_exists( 'have_rows' ) && have_rows( 'dz_armoiries_symboles' ) ) : ?>
		<!-- Armoiries Symboles Section -->
		<section id="armoiries-symboles" class="about section">
			<div class="container">
				<?php
				$dz_row_index = 0;
				while ( have_rows( 'dz_armoiries_symboles' ) ) :
					the_row();
					$dz_symbole_titre = get_sub_field( 'dz_armoiries_symbole_titre' );
					$dz_symbole_texte = get_sub_field( 'dz_armoiries_symbole_texte' );

					$dz_row_classes = array( 'row', 'g-5', 'align-items-center' );
					if ( 0 === $dz_row_index % 2 ) {
						$dz_row_classes[] = 'flex-row-reverse';
					}
					if ( $dz_row_index > 0 ) {
						$dz_row_classes[] = 'mt-4';
					}
					++$dz_row_index;
					?>
					<div class="<?php echo esc_attr( implode( ' ', $dz_row_classes ) ); ?>" data-aos="fade-up">

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="col-lg-5">
								<div class="about-img">
									<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid', 'alt' => $dz_symbole_titre ) ); ?>
								</div>
							</div>
						<?php endif; ?>

						<div class="col-lg-<?php echo has_post_thumbnail() ? '7' : '12'; ?>">
							<?php if ( $dz_symbole_titre ) : ?>
								<h3><?php echo esc_html( $dz_symbole_titre ); ?></h3>
							<?php endif; ?>
							<?php if ( $dz_symbole_texte ) : ?>
								<p><?php echo nl2br( esc_html( $dz_symbole_texte ) ); ?></p>
							<?php endif; ?>
						</div>

					</div>
				<?php endwhile; ?>
			</div>
		</section><!-- /Armoiries Symboles Section -->
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
