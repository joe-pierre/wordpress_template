<?php
/**
 * Paroisse card, used inside the current post loop by archive-paroisse.php.
 * No equivalent card exists in the source template — see DECISIONS.md
 * (new .paroisse-card class, following CONVENTIONS.md's naming rule for
 * genuinely new business sections).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_adresse = dz_get_field( 'paroisse_adresse' );
$dz_secteur = dz_get_field( 'paroisse_secteur' );
$dz_cure    = dz_get_paroisse_clergy( get_the_ID(), 'cure' );
?>
<div class="paroisse-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="paroisse-card-img">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="paroisse-card-body">
		<h3 class="paroisse-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $dz_adresse || $dz_secteur ) : ?>
			<p class="paroisse-card-meta">
				<i class="bi bi-geo-alt-fill"></i>
				<?php echo esc_html( trim( implode( ' — ', array_filter( array( $dz_adresse, $dz_secteur ) ) ) ) ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! empty( $dz_cure ) ) : ?>
			<p class="paroisse-card-cure">
				<i class="bi bi-person-badge"></i>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: priest's name */
						__( 'Curé : %s', 'diocese-ziguinchor' ),
						get_the_title( $dz_cure[0] )
					)
				);
				?>
			</p>
		<?php endif; ?>
	</div>
</div>
