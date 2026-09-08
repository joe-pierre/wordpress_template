<?php
/**
 * Archive card shared by conseil/service_diocesain/commission_diocesain
 * (see DECISIONS.md). New ".organisation-card" class rather than reusing
 * .info-card: .info-card is only ever styled as a tile inside the dark
 * .contact-info-panel gradient background (paroisse/pretre/sacrement
 * sidebars) and would be an unreadable white-on-white block used bare on a
 * plain archive grid — same reasoning already applied for .paroisse-card
 * and .evenement-card.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_responsable = dz_get_field( 'org_responsable' );
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
			<i class="bi bi-diagram-3-fill"></i>
		</div>
	<?php endif; ?>

	<div class="organisation-card-body">
		<h3 class="organisation-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $dz_responsable ) : ?>
			<p class="organisation-card-responsable">
				<i class="bi bi-person-badge"></i>
				<?php echo esc_html( $dz_responsable ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
