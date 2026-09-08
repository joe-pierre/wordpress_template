<?php
/**
 * "Responsable" + "Composition" (repeater membres) block, shared by the
 * conseil/service_diocesain/commission_diocesain single templates — same
 * socle fields for all three (see acf-json/group_dz_cpt_organisation_socle.json
 * and DECISIONS.md). Operates on the current post in the loop, no $args.
 *
 * "Responsable" reuses .contact-info-panel/.info-card (already used for the
 * paroisse/pretre/sacrement sidebar tiles) since it is a single dark panel
 * tile here, not a freestanding grid card. "Composition" reuses
 * .about .check-list (about.html) for the same reason a bullet list with a
 * check icon already fits without new CSS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dz_responsable = dz_get_field( 'org_responsable' );
?>

<?php if ( $dz_responsable ) : ?>
	<div class="contact-info-panel mb-4">
		<div class="contact-info-header">
			<h3><?php esc_html_e( 'Responsable', 'diocese-ziguinchor' ); ?></h3>
		</div>
		<div class="contact-info-cards">
			<div class="info-card">
				<div class="icon-container"><i class="bi bi-person-badge"></i></div>
				<div class="card-content">
					<h4><?php echo esc_html( $dz_responsable ); ?></h4>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ( function_exists( 'have_rows' ) && have_rows( 'org_membres' ) ) : ?>
	<div class="about">
		<h3><?php esc_html_e( 'Composition', 'diocese-ziguinchor' ); ?></h3>
		<ul class="check-list">
			<?php
			while ( have_rows( 'org_membres' ) ) :
				the_row();
				$dz_membre_nom  = get_sub_field( 'org_membre_nom' );
				$dz_membre_role = get_sub_field( 'org_membre_role' );

				if ( ! $dz_membre_nom ) {
					continue;
				}
				?>
				<li>
					<i class="bi bi-check-circle"></i>
					<?php
					echo esc_html(
						$dz_membre_role
							? sprintf(
								/* translators: 1: member name, 2: member role */
								__( '%1$s — %2$s', 'diocese-ziguinchor' ),
								$dz_membre_nom,
								$dz_membre_role
							)
							: $dz_membre_nom
					);
					?>
				</li>
				<?php
			endwhile;
			?>
		</ul>
	</div>
<?php endif; ?>
