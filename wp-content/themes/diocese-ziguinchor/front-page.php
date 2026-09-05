<?php
/**
 * Homepage template: hero slider + featured posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/hero-slider',
	null,
	array(
		'slides' => dz_get_front_hero_slides(),
	)
);

$dz_featured_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => -1,
		'meta_key'            => 'post_a_la_une',
		'meta_value'          => '1',
		'ignore_sticky_posts' => true,
	)
);
?>

<?php if ( $dz_featured_query->have_posts() ) : ?>
	<!-- Featured Posts Section -->
	<section id="featured-posts" class="featured-posts section">

		<!-- Section Title -->
		<div class="container section-title" data-aos="fade-up">
			<span class="description-title"><?php esc_html_e( 'À la une', 'diocese-ziguinchor' ); ?></span>
			<h2><?php esc_html_e( 'À la une', 'diocese-ziguinchor' ); ?></h2>
			<p><?php esc_html_e( 'Les actualités mises en avant par la rédaction diocésaine', 'diocese-ziguinchor' ); ?></p>
		</div><!-- End Section Title -->

		<div class="container" data-aos="fade-up" data-aos-delay="100">

			<div class="blog-posts-slider swiper init-swiper">
				<script type="application/json" class="swiper-config">
					{
						"loop": true,
						"speed": 600,
						"autoplay": {
							"delay": 4500
						},
						"slidesPerView": 1,
						"spaceBetween": 40,
						"centeredSlides": true,
						"breakpoints": {
							"768": {
								"slidesPerView": 1.5,
								"spaceBetween": 30
							},
							"1200": {
								"slidesPerView": 2.2,
								"spaceBetween": 40
							}
						},
						"pagination": {
							"el": ".swiper-pagination",
							"clickable": true
						}
					}
				</script>

				<div class="swiper-wrapper">
					<?php while ( $dz_featured_query->have_posts() ) : $dz_featured_query->the_post(); ?>
						<div class="swiper-slide">
							<article class="blog-card">
								<div class="blog-image">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
									<?php endif; ?>
									<?php $dz_categories = get_the_category(); ?>
									<?php if ( ! empty( $dz_categories ) ) : ?>
										<div class="category-badge"><?php echo esc_html( $dz_categories[0]->name ); ?></div>
									<?php endif; ?>
								</div>
								<div class="blog-content">
									<div class="author-info">
										<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', get_the_author(), array( 'class' => 'author-avatar' ) ); ?>
										<div class="author-details">
											<span class="author-name"><?php the_author(); ?></span>
											<span class="publish-date"><?php echo esc_html( get_the_date() ); ?></span>
										</div>
									</div>
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p><?php echo esc_html( get_the_excerpt() ); ?></p>
									<div class="blog-footer">
										<div class="reading-time">
											<i class="bi bi-clock"></i>
											<?php $dz_minutes = dz_get_reading_time( get_the_ID() ); ?>
											<span>
												<?php
												echo esc_html(
													sprintf(
														/* translators: %d: estimated reading time in minutes */
														_n( '%d minute de lecture', '%d minutes de lecture', $dz_minutes, 'diocese-ziguinchor' ),
														$dz_minutes
													)
												);
												?>
											</span>
										</div>
										<a href="<?php the_permalink(); ?>" class="btn-read-more">
											<span><?php esc_html_e( 'Lire la suite', 'diocese-ziguinchor' ); ?></span>
											<i class="bi bi-arrow-right"></i>
										</a>
									</div>
								</div>
							</article>
						</div><!-- End slide item -->
					<?php endwhile; ?>
				</div>

				<div class="swiper-pagination"></div>
			</div>

		</div>

	</section><!-- /Featured Posts Section -->
	<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php get_footer(); ?>
