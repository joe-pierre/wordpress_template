<?php
/**
 * Pretre card, used inside the current post loop by archive-pretre.php.
 * Reuses the "team-member" markup/CSS from about.html's Team section
 * as-is (see CONVENTIONS.md — reuse a template pattern unchanged when it
 * already fits). The fake social-overlay links become real tel:/mailto:
 * links to the priest's own contact fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_fonction = dz_get_field( 'pretre_fonction' );
$dz_phone    = dz_get_field( 'pretre_telephone' );
$dz_email    = dz_get_field( 'pretre_email' );
$dz_paroisse = dz_get_field( 'pretre_paroisse' );
?>
<div class="team-member">
	<div class="member-image">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid', 'alt' => get_the_title() ) ); ?>
		<?php endif; ?>

		<?php if ( $dz_phone || $dz_email ) : ?>
			<div class="social-overlay">
				<div class="social-icons">
					<?php if ( $dz_phone ) : ?>
						<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $dz_phone ) ); ?>" aria-label="<?php esc_attr_e( 'Téléphone', 'diocese-ziguinchor' ); ?>"><i class="bi bi-telephone-fill"></i></a>
					<?php endif; ?>
					<?php if ( $dz_email ) : ?>
						<a href="<?php echo esc_url( 'mailto:' . $dz_email ); ?>" aria-label="<?php esc_attr_e( 'E-mail', 'diocese-ziguinchor' ); ?>"><i class="bi bi-envelope-fill"></i></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="member-info">
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<?php if ( $dz_fonction ) : ?>
			<span><?php echo esc_html( dz_get_pretre_fonction_label( $dz_fonction ) ); ?></span>
		<?php endif; ?>
		<?php if ( $dz_paroisse && get_post( $dz_paroisse ) ) : ?>
			<p><?php echo esc_html( get_the_title( $dz_paroisse ) ); ?></p>
		<?php endif; ?>
	</div>
</div>
