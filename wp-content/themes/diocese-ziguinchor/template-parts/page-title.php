<?php
/**
 * Page title + breadcrumb banner, shared by every inner page template.
 *
 * @param array $args {
 *     @type string $title      Heading text (required).
 *     @type string $subtitle   Optional paragraph under the heading.
 *     @type string $breadcrumb Optional current-page breadcrumb label. Defaults to $title.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_title      = isset( $args['title'] ) ? $args['title'] : '';
$dz_subtitle   = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$dz_breadcrumb = isset( $args['breadcrumb'] ) ? $args['breadcrumb'] : $dz_title;
?>
<div class="page-title">
	<div class="heading">
		<div class="container">
			<div class="row d-flex justify-content-center text-center">
				<div class="col-lg-8">
					<h1 class="heading-title"><?php echo esc_html( $dz_title ); ?></h1>
					<?php if ( $dz_subtitle ) : ?>
						<p class="mb-0"><?php echo esc_html( $dz_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<nav class="breadcrumbs">
		<div class="container">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'diocese-ziguinchor' ); ?></a></li>
				<li class="current"><?php echo esc_html( $dz_breadcrumb ); ?></li>
			</ol>
		</div>
	</nav>
</div><!-- End Page Title -->
