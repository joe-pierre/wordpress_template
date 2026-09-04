<?php
/**
 * Blog sidebar (search, categories, recent posts, tags), used by archive.php.
 * search.php has no sidebar in the source template ("Story") and none is
 * added here, to stay faithful to that layout.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="widgets-container">

	<!-- Search Widget -->
	<div class="search-widget widget-item">
		<h3 class="widget-title"><?php esc_html_e( 'Rechercher', 'diocese-ziguinchor' ); ?></h3>
		<?php get_search_form(); ?>
	</div><!--/Search Widget -->

	<?php $dz_categories = get_categories( array( 'hide_empty' => true ) ); ?>
	<?php if ( $dz_categories ) : ?>
		<!-- Categories Widget -->
		<div class="categories-widget widget-item">
			<h3 class="widget-title"><?php esc_html_e( 'Catégories', 'diocese-ziguinchor' ); ?></h3>
			<ul class="mt-3">
				<?php foreach ( $dz_categories as $dz_category ) : ?>
					<li>
						<a href="<?php echo esc_url( get_category_link( $dz_category ) ); ?>">
							<?php echo esc_html( $dz_category->name ); ?> <span>(<?php echo (int) $dz_category->count; ?>)</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div><!--/Categories Widget -->
	<?php endif; ?>

	<?php
	$dz_recent_posts = get_posts(
		array(
			'posts_per_page'      => 5,
			'ignore_sticky_posts' => true,
		)
	);
	?>
	<?php if ( $dz_recent_posts ) : ?>
		<!-- Recent Posts Widget -->
		<div class="recent-posts-widget widget-item">
			<h3 class="widget-title"><?php esc_html_e( 'Articles récents', 'diocese-ziguinchor' ); ?></h3>
			<?php foreach ( $dz_recent_posts as $dz_post ) : ?>
				<div class="post-item">
					<?php if ( has_post_thumbnail( $dz_post ) ) : ?>
						<?php echo get_the_post_thumbnail( $dz_post, 'thumbnail', array( 'class' => 'flex-shrink-0', 'alt' => get_the_title( $dz_post ) ) ); ?>
					<?php endif; ?>
					<div>
						<h4><a href="<?php echo esc_url( get_permalink( $dz_post ) ); ?>"><?php echo esc_html( get_the_title( $dz_post ) ); ?></a></h4>
						<time datetime="<?php echo esc_attr( get_the_date( 'c', $dz_post ) ); ?>"><?php echo esc_html( get_the_date( '', $dz_post ) ); ?></time>
					</div>
				</div><!-- End recent post item-->
			<?php endforeach; ?>
		</div><!--/Recent Posts Widget -->
	<?php endif; ?>

	<?php $dz_tags = get_tags( array( 'hide_empty' => true ) ); ?>
	<?php if ( $dz_tags ) : ?>
		<!-- Tags Widget -->
		<div class="tags-widget widget-item">
			<h3 class="widget-title"><?php esc_html_e( 'Étiquettes', 'diocese-ziguinchor' ); ?></h3>
			<ul>
				<?php foreach ( $dz_tags as $dz_tag ) : ?>
					<li><a href="<?php echo esc_url( get_tag_link( $dz_tag ) ); ?>"><?php echo esc_html( $dz_tag->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div><!--/Tags Widget -->
	<?php endif; ?>

</div>
