<?php
/**
 * Common site footer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</main><!-- End #main -->

	<footer id="footer" class="footer position-relative light-background">

		<div class="container">
			<div class="row gy-5">

				<div class="col-lg-4">
					<div class="footer-brand">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo d-flex align-items-center mb-3">
							<span class="sitename"><?php bloginfo( 'name' ); ?></span>
						</a>

						<?php $dz_tagline = dz_get_option( 'dz_footer_tagline' ); ?>
						<?php if ( $dz_tagline ) : ?>
							<p class="tagline"><?php echo esc_html( $dz_tagline ); ?></p>
						<?php endif; ?>

						<?php get_template_part( 'template-parts/social-links', null, array( 'wrapper_class' => 'social-links mt-4' ) ); ?>
					</div>
				</div>

				<?php $dz_footer_columns = dz_get_option( 'dz_footer_columns', array() ); ?>
				<?php if ( $dz_footer_columns ) : ?>
					<div class="col-lg-6">
						<div class="footer-links-grid">
							<div class="row">
								<?php foreach ( $dz_footer_columns as $dz_column ) : ?>
									<div class="col-6 col-md-4">
										<h5><?php echo esc_html( $dz_column['dz_footer_column_title'] ); ?></h5>
										<?php if ( ! empty( $dz_column['dz_footer_column_links'] ) ) : ?>
											<ul class="list-unstyled">
												<?php foreach ( $dz_column['dz_footer_column_links'] as $dz_link ) : ?>
													<li><a href="<?php echo esc_url( $dz_link['dz_footer_link_url'] ); ?>"><?php echo esc_html( $dz_link['dz_footer_link_label'] ); ?></a></li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="col-lg-2">
					<div class="footer-cta">
						<h5><?php esc_html_e( 'Nous contacter', 'diocese-ziguinchor' ); ?></h5>
						<a href="<?php echo esc_url( dz_get_page_url_by_template( 'page-contact.php' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Nous écrire', 'diocese-ziguinchor' ); ?></a>
					</div>
				</div>

			</div>
		</div>

		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="footer-bottom-content">
							<p class="mb-0">
								<?php
								$dz_copyright = dz_get_option( 'dz_footer_copyright', get_bloginfo( 'name' ) );
								printf(
									/* translators: 1: current year, 2: copyright holder text */
									esc_html__( '© %1$s %2$s', 'diocese-ziguinchor' ),
									esc_html( gmdate( 'Y' ) ),
									esc_html( $dz_copyright )
								);
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</footer>

	<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	<div id="preloader"></div>

	<?php wp_footer(); ?>

</body>
</html>
