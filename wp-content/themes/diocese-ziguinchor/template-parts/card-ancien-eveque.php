<?php
/**
 * Ancien évêque card, used inside the current post loop by
 * archive-ancien_eveque.php. Reuses .organisation-card /
 * .organisation-card-responsable (assets/css/main.css) for visual
 * consistency with the other organisational archives, even though
 * ancien_eveque has no `org_responsable` field (no socle, see DECISIONS.md):
 * the mandate period takes that same meta-line slot instead of duplicating
 * the CSS in a CPT-specific class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_periode = dz_get_ancien_eveque_periode_label( get_the_ID() );
?>
<div class="organisation-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="organisation-card-img">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php else : ?>
		<div class="organisation-card-icon">
			<i class="bi bi-person-vcard-fill"></i>
		</div>
	<?php endif; ?>

	<div class="organisation-card-body">
		<h3 class="organisation-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $dz_periode ) : ?>
			<p class="organisation-card-responsable">
				<i class="bi bi-calendar-range"></i>
				<?php echo esc_html( $dz_periode ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
