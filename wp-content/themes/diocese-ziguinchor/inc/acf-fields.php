<?php
/**
 * ACF options page ("Réglages du thème") and its field group.
 *
 * Fields are registered in PHP (acf_add_local_field_group) rather than
 * exported as acf-json, per SPEC.md §5. Everything here is guarded so the
 * theme never fatals if ACF is not (yet) active.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Supported social networks: value => [ label, Bootstrap Icons class ].
 * Shared between the field group (select choices) and the front-end
 * (template-parts/social-links.php) so both stay in sync.
 *
 * @return array<string,array{label:string,icon:string}>
 */
function dz_get_social_networks() {
	return array(
		'facebook'  => array(
			'label' => __( 'Facebook', 'diocese-ziguinchor' ),
			'icon'  => 'bi-facebook',
		),
		'twitter'   => array(
			'label' => __( 'Twitter / X', 'diocese-ziguinchor' ),
			'icon'  => 'bi-twitter-x',
		),
		'instagram' => array(
			'label' => __( 'Instagram', 'diocese-ziguinchor' ),
			'icon'  => 'bi-instagram',
		),
		'linkedin'  => array(
			'label' => __( 'LinkedIn', 'diocese-ziguinchor' ),
			'icon'  => 'bi-linkedin',
		),
		'youtube'   => array(
			'label' => __( 'YouTube', 'diocese-ziguinchor' ),
			'icon'  => 'bi-youtube',
		),
		'whatsapp'  => array(
			'label' => __( 'WhatsApp', 'diocese-ziguinchor' ),
			'icon'  => 'bi-whatsapp',
		),
	);
}

function dz_get_social_icon_class( $platform ) {
	$networks = dz_get_social_networks();
	return isset( $networks[ $platform ] ) ? $networks[ $platform ]['icon'] : '';
}

function dz_get_social_label( $platform ) {
	$networks = dz_get_social_networks();
	return isset( $networks[ $platform ] ) ? $networks[ $platform ]['label'] : '';
}

function dz_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Réglages du thème', 'diocese-ziguinchor' ),
			'menu_title' => __( 'Réglages du thème', 'diocese-ziguinchor' ),
			'menu_slug'  => 'dz-theme-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'icon_url'   => 'dashicons-admin-customizer',
		)
	);
}
add_action( 'acf/init', 'dz_register_options_page' );

function dz_register_options_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$social_choices = array();
	foreach ( dz_get_social_networks() as $value => $network ) {
		$social_choices[ $value ] = $network['label'];
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_dz_theme_settings',
			'title'    => __( 'Réglages du thème', 'diocese-ziguinchor' ),
			'fields'   => array(
				array(
					'key'   => 'field_dz_tab_logo',
					'label' => __( 'Logo', 'diocese-ziguinchor' ),
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_dz_logo',
					'label'         => __( 'Logo du diocèse', 'diocese-ziguinchor' ),
					'name'          => 'dz_logo',
					'type'          => 'image',
					'instructions'  => __( 'Affiché dans l\'en-tête et le pied de page. Si aucun logo n\'est défini, le nom du site est affiché à la place.', 'diocese-ziguinchor' ),
					'return_format' => 'array',
					'preview_size'  => 'medium',
				),
				array(
					'key'   => 'field_dz_tab_home',
					'label' => __( 'Page d\'accueil', 'diocese-ziguinchor' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_dz_hero_slides',
					'label'        => __( 'Slides du hero', 'diocese-ziguinchor' ),
					'name'         => 'dz_hero_slides',
					'type'         => 'repeater',
					'instructions' => __( 'Limité à 5 slides pour préserver les performances de la page d\'accueil (voir DECISIONS.md).', 'diocese-ziguinchor' ),
					'min'          => 0,
					'max'          => 5,
					'layout'       => 'block',
					'button_label' => __( 'Ajouter un slide', 'diocese-ziguinchor' ),
					'sub_fields'   => array(
						array(
							'key'           => 'field_dz_hero_slide_image',
							'label'         => __( 'Image', 'diocese-ziguinchor' ),
							'name'          => 'dz_hero_slide_image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'url',
							'preview_size'  => 'medium',
						),
						array(
							'key'   => 'field_dz_hero_slide_badge',
							'label' => __( 'Badge (catégorie)', 'diocese-ziguinchor' ),
							'name'  => 'dz_hero_slide_badge',
							'type'  => 'text',
							'instructions' => __( 'Court libellé affiché au-dessus du titre, ex. "Actualité", "Événement".', 'diocese-ziguinchor' ),
						),
						array(
							'key'      => 'field_dz_hero_slide_title',
							'label'    => __( 'Titre', 'diocese-ziguinchor' ),
							'name'     => 'dz_hero_slide_title',
							'type'     => 'text',
							'required' => 1,
						),
						array(
							'key'   => 'field_dz_hero_slide_link_label',
							'label' => __( 'Texte du lien', 'diocese-ziguinchor' ),
							'name'  => 'dz_hero_slide_link_label',
							'type'  => 'text',
							'instructions' => __( 'Par défaut : "En savoir plus".', 'diocese-ziguinchor' ),
						),
						array(
							'key'   => 'field_dz_hero_slide_link_url',
							'label' => __( 'Lien', 'diocese-ziguinchor' ),
							'name'  => 'dz_hero_slide_link_url',
							'type'  => 'url',
						),
					),
				),
				array(
					'key'   => 'field_dz_tab_social',
					'label' => __( 'Réseaux sociaux', 'diocese-ziguinchor' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_dz_social_links',
					'label'        => __( 'Réseaux sociaux', 'diocese-ziguinchor' ),
					'name'         => 'dz_social_links',
					'type'         => 'repeater',
					'instructions' => __( 'Affichés dans l\'en-tête et le pied de page.', 'diocese-ziguinchor' ),
					'min'          => 0,
					'max'          => 6,
					'layout'       => 'table',
					'button_label' => __( 'Ajouter un réseau social', 'diocese-ziguinchor' ),
					'sub_fields'   => array(
						array(
							'key'     => 'field_dz_social_platform',
							'label'   => __( 'Réseau', 'diocese-ziguinchor' ),
							'name'    => 'dz_social_platform',
							'type'    => 'select',
							'choices' => $social_choices,
							'required' => 1,
						),
						array(
							'key'      => 'field_dz_social_url',
							'label'    => __( 'Lien', 'diocese-ziguinchor' ),
							'name'     => 'dz_social_url',
							'type'     => 'url',
							'required' => 1,
						),
					),
				),
				array(
					'key'   => 'field_dz_tab_contact',
					'label' => __( 'Coordonnées de contact', 'diocese-ziguinchor' ),
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_dz_contact_phone',
					'label' => __( 'Téléphone', 'diocese-ziguinchor' ),
					'name'  => 'dz_contact_phone',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_dz_contact_email',
					'label' => __( 'E-mail', 'diocese-ziguinchor' ),
					'name'  => 'dz_contact_email',
					'type'  => 'email',
				),
				array(
					'key'   => 'field_dz_contact_address',
					'label' => __( 'Adresse', 'diocese-ziguinchor' ),
					'name'  => 'dz_contact_address',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'   => 'field_dz_tab_footer',
					'label' => __( 'Pied de page', 'diocese-ziguinchor' ),
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_dz_footer_tagline',
					'label' => __( 'Slogan (sous le logo)', 'diocese-ziguinchor' ),
					'name'  => 'dz_footer_tagline',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'          => 'field_dz_footer_copyright',
					'label'        => __( 'Texte de copyright', 'diocese-ziguinchor' ),
					'name'         => 'dz_footer_copyright',
					'type'         => 'text',
					'instructions' => __( 'L\'année et le symbole © sont ajoutés automatiquement.', 'diocese-ziguinchor' ),
					'placeholder'  => __( 'Diocèse de Ziguinchor. Tous droits réservés.', 'diocese-ziguinchor' ),
				),
				array(
					'key'          => 'field_dz_footer_columns',
					'label'        => __( 'Colonnes de liens', 'diocese-ziguinchor' ),
					'name'         => 'dz_footer_columns',
					'type'         => 'repeater',
					'instructions' => __( 'Jusqu\'à 3 colonnes pour préserver la mise en page.', 'diocese-ziguinchor' ),
					'min'          => 0,
					'max'          => 3,
					'layout'       => 'block',
					'button_label' => __( 'Ajouter une colonne', 'diocese-ziguinchor' ),
					'sub_fields'   => array(
						array(
							'key'      => 'field_dz_footer_column_title',
							'label'    => __( 'Titre de la colonne', 'diocese-ziguinchor' ),
							'name'     => 'dz_footer_column_title',
							'type'     => 'text',
							'required' => 1,
						),
						array(
							'key'          => 'field_dz_footer_column_links',
							'label'        => __( 'Liens', 'diocese-ziguinchor' ),
							'name'         => 'dz_footer_column_links',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => __( 'Ajouter un lien', 'diocese-ziguinchor' ),
							'sub_fields'   => array(
								array(
									'key'      => 'field_dz_footer_link_label',
									'label'    => __( 'Libellé', 'diocese-ziguinchor' ),
									'name'     => 'dz_footer_link_label',
									'type'     => 'text',
									'required' => 1,
								),
								array(
									'key'      => 'field_dz_footer_link_url',
									'label'    => __( 'Lien', 'diocese-ziguinchor' ),
									'name'     => 'dz_footer_link_url',
									'type'     => 'url',
									'required' => 1,
								),
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'dz-theme-settings',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'dz_register_options_field_group' );

/**
 * "Chiffres clés" counter badges on page-about.php (see about.html's
 * .experience-badge/.projects-badge — two fixed, differently positioned
 * badges, not a repeatable list, see DECISIONS.md).
 */
function dz_register_about_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$dz_badge_sub_fields = array(
		array(
			'key'   => 'field_dz_about_badge_number',
			'label' => __( 'Nombre', 'diocese-ziguinchor' ),
			'name'  => 'dz_number',
			'type'  => 'number',
		),
		array(
			'key'          => 'field_dz_about_badge_suffix',
			'label'        => __( 'Suffixe', 'diocese-ziguinchor' ),
			'name'         => 'dz_suffix',
			'type'         => 'text',
			'instructions' => __( 'Optionnel, ex. "+".', 'diocese-ziguinchor' ),
		),
		array(
			'key'   => 'field_dz_about_badge_label',
			'label' => __( 'Légende', 'diocese-ziguinchor' ),
			'name'  => 'dz_label',
			'type'  => 'text',
		),
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_dz_page_about',
			'title'    => __( 'Page "À propos" — chiffres clés', 'diocese-ziguinchor' ),
			'fields'   => array(
				array(
					'key'        => 'field_dz_about_badge_bottom',
					'label'      => __( 'Badge bas', 'diocese-ziguinchor' ),
					'name'       => 'dz_about_badge_bottom',
					'type'       => 'group',
					'sub_fields' => $dz_badge_sub_fields,
				),
				array(
					'key'        => 'field_dz_about_badge_top',
					'label'      => __( 'Badge haut', 'diocese-ziguinchor' ),
					'name'       => 'dz_about_badge_top',
					'type'       => 'group',
					'sub_fields' => $dz_badge_sub_fields,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-about.php',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'dz_register_about_field_group' );

/**
 * Payment methods shown on page-dons.php: value => [ label, Bootstrap Icons class ].
 *
 * @return array<string,array{label:string,icon:string}>
 */
function dz_get_don_payment_methods() {
	return array(
		'banque' => array(
			'label' => __( 'Virement bancaire', 'diocese-ziguinchor' ),
			'icon'  => 'bi-bank',
		),
		'mobile' => array(
			'label' => __( 'Mobile Money', 'diocese-ziguinchor' ),
			'icon'  => 'bi-phone',
		),
		'especes' => array(
			'label' => __( 'Espèces', 'diocese-ziguinchor' ),
			'icon'  => 'bi-cash-coin',
		),
		'autre' => array(
			'label' => __( 'Autre', 'diocese-ziguinchor' ),
			'icon'  => 'bi-heart',
		),
	);
}

function dz_get_don_icon_class( $method ) {
	$methods = dz_get_don_payment_methods();
	return isset( $methods[ $method ] ) ? $methods[ $method ]['icon'] : 'bi-heart';
}

/**
 * Donation modalities repeater on page-dons.php (SPEC.md §3: "champs ACF
 * pour les modalités (RIB, Mobile Money, etc.)"). No online payment
 * integration — see BUGS_AND_ROADMAP.md.
 */
function dz_register_dons_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$dz_method_choices = array();
	foreach ( dz_get_don_payment_methods() as $value => $method ) {
		$dz_method_choices[ $value ] = $method['label'];
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_dz_page_dons',
			'title'    => __( 'Page "Dons" — modalités', 'diocese-ziguinchor' ),
			'fields'   => array(
				array(
					'key'          => 'field_dz_dons_modalites',
					'label'        => __( 'Modalités de don', 'diocese-ziguinchor' ),
					'name'         => 'dz_dons_modalites',
					'type'         => 'repeater',
					'instructions' => __( 'Ex. RIB bancaire, numéro Orange Money/Wave...', 'diocese-ziguinchor' ),
					'min'          => 0,
					'layout'       => 'block',
					'button_label' => __( 'Ajouter une modalité', 'diocese-ziguinchor' ),
					'sub_fields'   => array(
						array(
							'key'     => 'field_dz_dons_modalite_type',
							'label'   => __( 'Type', 'diocese-ziguinchor' ),
							'name'    => 'dz_dons_modalite_type',
							'type'    => 'select',
							'choices' => $dz_method_choices,
							'required' => 1,
						),
						array(
							'key'      => 'field_dz_dons_modalite_titre',
							'label'    => __( 'Titre', 'diocese-ziguinchor' ),
							'name'     => 'dz_dons_modalite_titre',
							'type'     => 'text',
							'required' => 1,
						),
						array(
							'key'      => 'field_dz_dons_modalite_details',
							'label'    => __( 'Détails', 'diocese-ziguinchor' ),
							'name'     => 'dz_dons_modalite_details',
							'type'     => 'textarea',
							'rows'     => 4,
							'required' => 1,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-dons.php',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'dz_register_dons_field_group' );
