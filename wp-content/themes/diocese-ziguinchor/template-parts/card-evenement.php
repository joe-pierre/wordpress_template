<?php
/**
 * Evenement card, used inside the current post loop by archive-evenement.php.
 * No equivalent card exists in the source template — see DECISIONS.md
 * (new .evenement-card class, following CONVENTIONS.md's naming rule for
 * genuinely new business sections).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_debut = dz_get_field( 'evenement_date_debut' );
$dz_lieu  = dz_get_evenement_lieu( get_the_ID() );
?>
<div class="evenement-card">
	<?php if ( has_post_thumbnail() || $dz_debut ) : ?>
		<div class="evenement-card-img">
			<a href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
				<?php endif; ?>
			</a>
			<?php if ( $dz_debut ) : ?>
				<div class="evenement-card-date-badge">
					<span class="day"><?php echo esc_html( date_i18n( 'j', strtotime( $dz_debut ) ) ); ?></span>
					<span class="month"><?php echo esc_html( date_i18n( 'M', strtotime( $dz_debut ) ) ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="evenement-card-body">
		<h3 class="evenement-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $dz_debut ) : ?>
			<p class="evenement-card-meta">
				<i class="bi bi-calendar-event"></i>
				<?php echo esc_html( dz_get_evenement_dates_label( get_the_ID() ) ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $dz_lieu['label'] ) : ?>
			<p class="evenement-card-meta">
				<i class="bi bi-geo-alt-fill"></i>
				<?php echo esc_html( $dz_lieu['label'] ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
