<?php
/**
 * Article card for a native `post`, used inside the current post loop by
 * both archive.php and search.php (see CONVENTIONS.md — no duplication
 * between listing templates).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-img">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid', 'alt' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php $dz_categories = get_the_category(); ?>
	<?php if ( ! empty( $dz_categories ) ) : ?>
		<p class="post-category">
			<a href="<?php echo esc_url( get_category_link( $dz_categories[0] ) ); ?>"><?php echo esc_html( $dz_categories[0]->name ); ?></a>
		</p>
	<?php endif; ?>

	<h2 class="title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>

	<div class="d-flex align-items-center">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', get_the_author(), array( 'class' => 'img-fluid post-author-img flex-shrink-0' ) ); ?>
		<div class="post-meta">
			<p class="post-author"><?php the_author(); ?></p>
			<p class="post-date">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</p>
		</div>
	</div>

</article>
