<?php
/**
 * Aumonerie listing template, filterable by type_aumonerie
 * (see inc/cpt-aumonerie.php: dz_aumonerie_archive_query(), PROMPT 14).
 *
 * Filter tabs link back to this same archive with `?type_aumonerie=<slug>`
 * appended, rather than to a separate taxonomy archive route — so a filtered
 * listing renders through this exact template/card, never the generic blog
 * archive (see inc/cpt-aumonerie.php for why the taxonomy has no rewrite).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-title',
	null,
	array(
		'title'      => __( 'Aumôneries', 'diocese-ziguinchor' ),
		'breadcrumb' => __( 'Aumôneries', 'diocese-ziguinchor' ),
	)
);

$dz_current_type  = isset( $_GET['type_aumonerie'] ) ? sanitize_title( wp_unslash( $_GET['type_aumonerie'] ) ) : '';
$dz_archive_link  = get_post_type_archive_link( 'aumonerie' );
$dz_type_terms    = get_terms(
	array(
		'taxonomy'   => 'type_aumonerie',
		'hide_empty' => false,
	)
);
?>

<section id="aumoneries" class="aumoneries section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<?php if ( $dz_type_terms && ! is_wp_error( $dz_type_terms ) ) : ?>
			<ul class="nav nav-pills aumonerie-filters justify-content-center mb-4">
				<li class="nav-item">
					<a class="nav-link<?php echo '' === $dz_current_type ? ' active' : ''; ?>" href="<?php echo esc_url( $dz_archive_link ); ?>">
						<?php esc_html_e( 'Toutes', 'diocese-ziguinchor' ); ?>
					</a>
				</li>
				<?php foreach ( $dz_type_terms as $dz_term ) : ?>
					<li class="nav-item">
						<a class="nav-link<?php echo $dz_current_type === $dz_term->slug ? ' active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'type_aumonerie', $dz_term->slug, $dz_archive_link ) ); ?>">
							<?php echo esc_html( $dz_term->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6">
						<?php get_template_part( 'template-parts/card-organisation' ); ?>
					</div>
					<?php
				endwhile;
				?>
			</div>

			<div class="d-flex justify-content-center mt-4">
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
		<?php else : ?>
			<p><?php esc_html_e( 'Aucune aumônerie pour le moment.', 'diocese-ziguinchor' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
