<?php
/**
 * Pretre card, used inside the current post loop by archive-pretre.php
 * ("Nos prêtres" — refonte design crème/anthracite/or, voir DECISIONS.md
 * "Refonte archive prêtres", remplace l'ancien habillage "team-member"
 * réutilisé d'about.html). Toute la carte est un seul lien cliquable,
 * même pattern que `.organisation-card` (`template-parts/card-organisation.php`,
 * voir DECISIONS.md) plutôt que d'imbriquer plusieurs liens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_fonction = dz_get_field( 'pretre_fonction' );
$dz_statut   = dz_get_field( 'pretre_statut' );
$dz_paroisse = dz_get_field( 'pretre_paroisse' );

// Ligne d'affectation : la paroisse d'affectation en priorité: à défaut
// (rôles non rattachés à une paroisse, ex. Économe/Service diocésain), le
// libellé du rôle lui-même ; à défaut, le statut s'il n'est pas "En
// fonction" (Retraité/En formation/Sans affectation). Vide sinon — la carte
// se dégrade proprement plutôt que d'afficher un texte inventé.
$dz_affectation = '';
if ( $dz_paroisse && get_post( $dz_paroisse ) ) {
	$dz_affectation = get_the_title( $dz_paroisse );
} elseif ( in_array( $dz_fonction, array( 'econome', 'service_diocesain' ), true ) ) {
	$dz_affectation = dz_get_pretre_fonction_label( $dz_fonction );
} elseif ( $dz_statut && 'en_fonction' !== $dz_statut ) {
	$dz_affectation = dz_get_pretre_statut_label( $dz_statut );
}
?>
<a href="<?php the_permalink(); ?>" class="pretre-card">
	<div class="pretre-card-media">
		<?php if ( $dz_fonction ) : ?>
			<span class="pretre-badge <?php echo esc_attr( dz_get_pretre_fonction_badge_class( $dz_fonction ) ); ?>"><?php echo esc_html( dz_get_pretre_fonction_label( $dz_fonction ) ); ?></span>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium', array( 'class' => 'pretre-card-photo', 'alt' => get_the_title() ) ); ?>
		<?php else : ?>
			<div class="pretre-card-placeholder" aria-hidden="true">
				<?php echo esc_html( mb_substr( get_the_title(), 0, 1, 'UTF-8' ) ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="pretre-card-body">
		<h3 class="pretre-card-name"><?php the_title(); ?></h3>
		<?php if ( $dz_affectation ) : ?>
			<p class="pretre-card-affectation"><?php echo esc_html( $dz_affectation ); ?></p>
		<?php endif; ?>
	</div>
</a>
