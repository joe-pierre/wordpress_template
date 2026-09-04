<?php
/**
 * News archive/category listing template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => get_the_archive_title(),
		'subtitle'   => get_the_archive_description(),
		'breadcrumb' => wp_strip_all_tags( get_the_archive_title() ),
	)
);
?>

<div class="container">
	<div class="row">

		<div class="col-lg-8">

			<!-- Category Postst Section -->
			<section id="category-postst" class="category-postst section">
				<div class="container" data-aos="fade-up" data-aos-delay="100">
					<?php if ( have_posts() ) : ?>
						<div class="row gy-5">
							<?php
							while ( have_posts() ) :
								the_post();
								?>
								<div class="col-lg-6">
									<?php get_template_part( 'template-parts/card-article' ); ?>
								</div><!-- End post list item -->
								<?php
							endwhile;
							?>
						</div><!-- End blog posts list -->
					<?php else : ?>
						<p><?php esc_html_e( 'Aucun article pour le moment.', 'diocese-ziguinchor' ); ?></p>
					<?php endif; ?>
				</div>
			</section><!-- /Category Postst Section -->

			<!-- Pagination 2 Section -->
			<section id="pagination-2" class="pagination-2 section">
				<div class="container">
					<div class="d-flex justify-content-center">
						<?php
						the_posts_pagination(
							array(
								'mid_size'  => 2,
								'prev_text' => '<i class="bi bi-chevron-left"></i>',
								'next_text' => '<i class="bi bi-chevron-right"></i>',
							)
						);
						?>
					</div>
				</div>
			</section><!-- /Pagination 2 Section -->

		</div>

		<div class="col-lg-4 sidebar">
			<?php get_template_part( 'template-parts/sidebar-blog' ); ?>
		</div>

	</div>
</div>

<?php get_footer(); ?>
