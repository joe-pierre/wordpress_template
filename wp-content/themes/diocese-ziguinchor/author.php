<?php
/**
 * Author profile template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$dz_author        = get_queried_object();
$dz_author_id     = ( $dz_author instanceof WP_User ) ? $dz_author->ID : 0;
$dz_display_name  = get_the_author_meta( 'display_name', $dz_author_id );
$dz_bio           = get_the_author_meta( 'description', $dz_author_id );
$dz_website       = get_the_author_meta( 'user_url', $dz_author_id );
$dz_article_count = (int) count_user_posts( $dz_author_id, 'post', true );

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => $dz_display_name,
		'subtitle'   => $dz_bio ? wp_trim_words( $dz_bio, 25 ) : '',
		'breadcrumb' => $dz_display_name,
	)
);
?>

<!-- Author Profile Section -->
<section id="author-profile" class="author-profile section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<div class="author-profile-1">
			<div class="row">

				<!-- Author Info -->
				<div class="col-lg-4 mb-4 mb-lg-0">
					<div class="author-card" data-aos="fade-up">
						<div class="author-image">
							<?php echo get_avatar( $dz_author_id, 300, '', $dz_display_name, array( 'class' => 'img-fluid rounded' ) ); ?>
						</div>

						<div class="author-info">
							<h2><?php echo esc_html( $dz_display_name ); ?></h2>

							<?php if ( $dz_bio ) : ?>
								<div class="author-bio"><?php echo esc_html( wp_trim_words( $dz_bio, 20 ) ); ?></div>
							<?php endif; ?>

							<div class="author-stats d-flex justify-content-between text-center my-4">
								<div class="stat-item">
									<h4 data-purecounter-start="0" data-purecounter-end="<?php echo esc_attr( $dz_article_count ); ?>" data-purecounter-duration="1" class="purecounter"></h4>
									<p><?php esc_html_e( 'Articles', 'diocese-ziguinchor' ); ?></p>
								</div>
							</div>

							<?php if ( $dz_website ) : ?>
								<div class="social-links">
									<a href="<?php echo esc_url( $dz_website ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Site web', 'diocese-ziguinchor' ); ?>">
										<i class="bi bi-globe2"></i>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Author Content -->
				<div class="col-lg-8">
					<div class="author-content" data-aos="fade-up" data-aos-delay="200">

						<?php if ( $dz_bio ) : ?>
							<div class="content-header">
								<h3><?php esc_html_e( 'À propos', 'diocese-ziguinchor' ); ?></h3>
							</div>
							<div class="content-body">
								<p><?php echo esc_html( $dz_bio ); ?></p>
							</div>
						<?php endif; ?>

						<?php
						$dz_author_posts = get_posts(
							array(
								'author'         => $dz_author_id,
								'posts_per_page' => 4,
							)
						);
						?>
						<?php if ( $dz_author_posts ) : ?>
							<div class="featured-articles mt-5">
								<h4><?php esc_html_e( 'Derniers articles', 'diocese-ziguinchor' ); ?></h4>
								<div class="row g-4">
									<?php foreach ( $dz_author_posts as $dz_post ) : ?>
										<div class="col-md-6">
											<article class="article-card">
												<?php if ( has_post_thumbnail( $dz_post ) ) : ?>
													<div class="article-img">
														<a href="<?php echo esc_url( get_permalink( $dz_post ) ); ?>">
															<?php echo get_the_post_thumbnail( $dz_post, 'medium', array( 'class' => 'img-fluid', 'alt' => get_the_title( $dz_post ) ) ); ?>
														</a>
													</div>
												<?php endif; ?>
												<div class="article-details">
													<?php $dz_post_categories = get_the_category( $dz_post ); ?>
													<?php if ( ! empty( $dz_post_categories ) ) : ?>
														<div class="post-category"><?php echo esc_html( $dz_post_categories[0]->name ); ?></div>
													<?php endif; ?>
													<h5><a href="<?php echo esc_url( get_permalink( $dz_post ) ); ?>"><?php echo esc_html( get_the_title( $dz_post ) ); ?></a></h5>
													<div class="post-meta">
														<span><i class="bi bi-clock"></i> <?php echo esc_html( get_the_date( '', $dz_post ) ); ?></span>
														<span>
															<i class="bi bi-chat-dots"></i>
															<?php
															echo esc_html(
																sprintf(
																	/* translators: %s: number of comments */
																	_n( '%s commentaire', '%s commentaires', get_comments_number( $dz_post ), 'diocese-ziguinchor' ),
																	number_format_i18n( get_comments_number( $dz_post ) )
																)
															);
															?>
														</span>
													</div>
												</div>
											</article>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>

					</div>
				</div>

			</div>
		</div>
	</div>
</section><!-- /Author Profile Section -->

<?php get_footer(); ?>
