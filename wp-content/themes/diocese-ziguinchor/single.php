<?php
/**
 * Single news article template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/page-title',
		null,
		array(
			'title' => get_the_title(),
		)
	);
	?>

	<!-- Blog Details Section -->
	<section id="blog-details" class="blog-details section">
		<div class="container" data-aos="fade-up">

			<div class="article-hero">
				<div class="hero-background">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'hero-bg-image', 'alt' => get_the_title() ) ); ?>
					<?php endif; ?>
					<div class="hero-overlay"></div>
				</div>

				<div class="hero-content" data-aos="fade-up" data-aos-delay="200">
					<?php $dz_categories = get_the_category(); ?>
					<?php if ( ! empty( $dz_categories ) ) : ?>
						<div class="category-badges">
							<?php foreach ( $dz_categories as $dz_category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $dz_category ) ); ?>" class="badge"><?php echo esc_html( $dz_category->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<h1><?php the_title(); ?></h1>
					<p class="hero-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>

					<?php $dz_author_bio = get_the_author_meta( 'description' ); ?>
					<div class="author-meta">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', get_the_author(), array( 'class' => 'author-avatar' ) ); ?>
						<div class="author-details">
							<h4><?php the_author(); ?></h4>
							<?php if ( $dz_author_bio ) : ?>
								<span><?php echo esc_html( wp_trim_words( $dz_author_bio, 8 ) ); ?></span>
							<?php endif; ?>
						</div>
						<div class="article-stats">
							<span><i class="bi bi-calendar3"></i> <?php echo esc_html( get_the_date() ); ?></span>
							<span><i class="bi bi-clock-history"></i>
								<?php
								echo esc_html(
									sprintf(
										/* translators: %d: estimated reading time in minutes */
										__( '%d min', 'diocese-ziguinchor' ),
										dz_get_reading_time( get_the_ID() )
									)
								);
								?>
							</span>
							<?php if ( comments_open() || get_comments_number() ) : ?>
								<span><i class="bi bi-chat-dots"></i> <?php comments_number( '0', '1', '%' ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="article-body">
				<main class="main-content">
					<?php the_content(); ?>
				</main>
			</div>

			<?php
			$dz_share_permalink = get_permalink();
			$dz_share_title     = get_the_title();
			$dz_share_twitter   = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $dz_share_permalink ) . '&text=' . rawurlencode( $dz_share_title );
			$dz_share_facebook  = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $dz_share_permalink );
			$dz_share_linkedin  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $dz_share_permalink );
			$dz_share_email     = 'mailto:?subject=' . rawurlencode( $dz_share_title ) . '&body=' . rawurlencode( $dz_share_permalink );
			?>
			<div class="article-actions" data-aos="fade-up">
				<div class="engagement-section">
					<div class="social-sharing">
						<h3><?php esc_html_e( 'Partager cet article', 'diocese-ziguinchor' ); ?></h3>
						<div class="share-options">
							<a href="<?php echo esc_url( $dz_share_twitter ); ?>" target="_blank" rel="noopener" class="share-btn twitter">
								<i class="bi bi-twitter-x"></i><span>Twitter</span>
							</a>
							<a href="<?php echo esc_url( $dz_share_facebook ); ?>" target="_blank" rel="noopener" class="share-btn facebook">
								<i class="bi bi-facebook"></i><span>Facebook</span>
							</a>
							<a href="<?php echo esc_url( $dz_share_linkedin ); ?>" target="_blank" rel="noopener" class="share-btn linkedin">
								<i class="bi bi-linkedin"></i><span>LinkedIn</span>
							</a>
							<a href="<?php echo esc_url( $dz_share_email ); ?>" class="share-btn email">
								<i class="bi bi-envelope"></i><span>E-mail</span>
							</a>
						</div>
					</div>
				</div>

				<?php $dz_tags = get_the_tags(); ?>
				<?php if ( $dz_tags ) : ?>
					<div class="topic-tags">
						<h3><?php esc_html_e( 'Sujets liés', 'diocese-ziguinchor' ); ?></h3>
						<div class="tag-cloud">
							<?php foreach ( $dz_tags as $dz_tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $dz_tag ) ); ?>" class="topic-tag"><?php echo esc_html( $dz_tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</section><!-- /Blog Details Section -->

	<?php
	$dz_author_id    = get_the_author_meta( 'ID' );
	$dz_author_posts = get_posts(
		array(
			'author'         => $dz_author_id,
			'post__not_in'   => array( get_the_ID() ),
			'posts_per_page' => 2,
		)
	);
	?>
	<!-- Blog Author Section -->
	<section id="blog-author" class="blog-author section">
		<div class="container" data-aos="fade-up">
			<div class="author-card">
				<div class="author-header">
					<div class="author-image-container">
						<?php echo get_avatar( $dz_author_id, 96, '', get_the_author(), array( 'class' => 'author-image' ) ); ?>
					</div>

					<div class="author-intro">
						<div class="name-block">
							<h3 class="author-name"><?php the_author(); ?></h3>
						</div>
						<?php if ( $dz_author_bio ) : ?>
							<p class="author-tagline"><?php echo esc_html( wp_trim_words( $dz_author_bio, 20 ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<div class="author-content" data-aos="fade-up" data-aos-delay="200">
					<div class="content-row">
						<?php if ( $dz_author_bio ) : ?>
							<div class="bio-section">
								<p class="bio-text"><?php echo esc_html( $dz_author_bio ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( $dz_author_posts ) : ?>
							<div class="featured-posts">
								<h4><?php esc_html_e( 'Autres articles', 'diocese-ziguinchor' ); ?></h4>
								<ul class="post-list">
									<?php foreach ( $dz_author_posts as $dz_post ) : ?>
										<li>
											<i class="bi bi-arrow-right-circle"></i>
											<a href="<?php echo esc_url( get_permalink( $dz_post ) ); ?>"><span><?php echo esc_html( get_the_title( $dz_post ) ); ?></span></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>

					<div class="author-footer">
						<a href="<?php echo esc_url( get_author_posts_url( $dz_author_id ) ); ?>" class="subscribe-button">
							<?php esc_html_e( 'Voir tous les articles', 'diocese-ziguinchor' ); ?>
							<i class="bi bi-arrow-right"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section><!-- /Blog Author Section -->

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
