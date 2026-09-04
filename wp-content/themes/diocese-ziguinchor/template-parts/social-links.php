<?php
/**
 * Social links list, fed by the "Réglages du thème" ACF options page.
 * Used by both header.php and footer.php.
 *
 * @param array $args {
 *     @type string $wrapper_class Class(es) applied to the wrapping <div>.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_wrapper_class = isset( $args['wrapper_class'] ) ? $args['wrapper_class'] : 'social-links';
$dz_social_links  = dz_get_option( 'dz_social_links', array() );

if ( empty( $dz_social_links ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( $dz_wrapper_class ); ?>">
	<?php foreach ( $dz_social_links as $dz_link ) : ?>
		<?php
		$dz_platform = isset( $dz_link['dz_social_platform'] ) ? $dz_link['dz_social_platform'] : '';
		$dz_url      = isset( $dz_link['dz_social_url'] ) ? $dz_link['dz_social_url'] : '';
		$dz_icon     = dz_get_social_icon_class( $dz_platform );

		if ( empty( $dz_url ) || empty( $dz_icon ) ) {
			continue;
		}
		?>
		<a href="<?php echo esc_url( $dz_url ); ?>" aria-label="<?php echo esc_attr( dz_get_social_label( $dz_platform ) ); ?>">
			<i class="bi <?php echo esc_attr( $dz_icon ); ?>"></i>
		</a>
	<?php endforeach; ?>
</div>
