<?php
/**
 * Search results template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Résultats de recherche', 'diocese-ziguinchor' ),
		'subtitle'   => sprintf(
			/* translators: 1: number of results, 2: search query */
			_n( 'Nous avons trouvé %1$s résultat pour « %2$s »', 'Nous avons trouvé %1$s résultats pour « %2$s »', $wp_query->found_posts, 'diocese-ziguinchor' ),
			number_format_i18n( $wp_query->found_posts ),
			get_search_query()
		),
		'breadcrumb' => __( 'Résultats de recherche', 'diocese-ziguinchor' ),
	)
);
?>

<!-- Search Results Posts Section -->
<section id="search-results-posts" class="search-results-posts section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4">
						<?php get_template_part( 'template-parts/card-article' ); ?>
					</div><!-- End post list item -->
					<?php
				endwhile;
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Aucun résultat ne correspond à votre recherche.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section><!-- /Search Results Posts Section -->

<!-- Pagination 3 Section -->
<section id="pagination-3" class="pagination-3 section">
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
</section><!-- /Pagination 3 Section -->

<?php get_footer(); ?>
