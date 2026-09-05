<?php
/**
 * Custom nav menu walker.
 *
 * Reproduit le balisage du menu du template "Story" (span + icône pour les
 * éléments avec sous-menu, classe "active" sur le lien courant).
 *
 * Deux modes pour un élément de premier niveau ayant des enfants (voir
 * DECISIONS.md "Mega-menu hybride", PROMPT 17) :
 * - dropdown standard (comportement d'origine, inchangé) : 1 niveau, hover
 *   sur desktop, accordéon au clic sur mobile — pour les rubriques légères
 *   (Les Conseils de l'évêque, Personnel apostolique, Soutenir le diocèse).
 * - méga-menu (nouveau) : panneau large en colonnes Bootstrap, une colonne
 *   par enfant, listant les petits-enfants à plat sous chaque colonne (pas
 *   de dropdown imbriqué) ; ouvert au clic/tap à toutes les tailles d'écran
 *   — pour les 4 rubriques riches (À propos du diocèse, Services et
 *   Commissions, Apostolat des Laïques, Vie de Foi). Bascule par élément de
 *   menu via le champ ACF `dz_nav_item_megamenu` (voir
 *   acf-json/group_dz_nav_menu_item.json), pas par correspondance de slug —
 *   plus robuste si un libellé de rubrique change.
 *
 * Nécessite `wp_nav_menu( array( 'depth' => 3, ... ) )` (header.php) : les
 * petits-enfants (depth 2) ne sont walkés par WP_Walker du tout qu'à partir
 * de depth=3 ; ce walker les ignore lui-même (return anticipé) tant qu'ils
 * n'appartiennent pas à une branche méga-menu, pour que les rubriques
 * légères restent visuellement à 1 niveau malgré la profondeur autorisée.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DZ_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Menu item IDs that have at least one child, keyed by ID.
	 *
	 * @var array<int,bool>
	 */
	private $dz_parent_ids = array();

	/**
	 * Top-level menu item IDs flagged for méga-menu rendering, keyed by ID.
	 *
	 * @var array<int,bool>
	 */
	private $dz_megamenu_ids = array();

	/**
	 * Whether the item currently being rendered (any depth) descends from a
	 * méga-menu top-level item. Walker's depth-first traversal guarantees
	 * this is set (in start_el at depth 0) before any of that item's
	 * descendants are processed, and stays correct until the next top-level
	 * item overwrites it — see the class docblock.
	 *
	 * @var bool
	 */
	private $dz_in_megamenu = false;

	public function walk( $elements, $max_depth, ...$args ) {
		$this->dz_parent_ids   = array();
		$this->dz_megamenu_ids = array();

		foreach ( $elements as $element ) {
			if ( ! empty( $element->menu_item_parent ) ) {
				$this->dz_parent_ids[ (int) $element->menu_item_parent ] = true;
			}
		}

		if ( function_exists( 'get_field' ) ) {
			foreach ( $elements as $element ) {
				$dz_is_top_level = empty( $element->menu_item_parent );
				if ( $dz_is_top_level && isset( $this->dz_parent_ids[ $element->ID ] ) && get_field( 'dz_nav_item_megamenu', $element->ID ) ) {
					$this->dz_megamenu_ids[ $element->ID ] = true;
				}
			}
		}

		return parent::walk( $elements, $max_depth, ...$args );
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			if ( $this->dz_in_megamenu ) {
				$output .= '<div class="mega-menu"><div class="mega-menu-inner container"><ul class="row mega-menu-columns">';
			} else {
				$output .= '<ul>';
			}
			return;
		}

		if ( 1 === $depth && $this->dz_in_megamenu ) {
			$output .= '<ul class="mega-menu-sublist">';
			return;
		}

		// Light dropdowns stay 1 level deep: no wrapper for depth >= 1 there.
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			if ( $this->dz_in_megamenu ) {
				$output .= '</ul></div></div>';
			} else {
				$output .= '</ul>';
			}
			return;
		}

		if ( 1 === $depth && $this->dz_in_megamenu ) {
			$output .= '</ul>';
			return;
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth >= 2 && ! $this->dz_in_megamenu ) {
			return;
		}

		$has_children = isset( $this->dz_parent_ids[ $item->ID ] );

		if ( 0 === $depth ) {
			$this->dz_in_megamenu = $has_children && isset( $this->dz_megamenu_ids[ $item->ID ] );
		}

		$li_classes = empty( $item->classes ) ? array() : array_filter( (array) $item->classes );

		if ( 0 === $depth && $has_children ) {
			$li_classes[] = $this->dz_in_megamenu ? 'mega-menu-parent' : 'dropdown';
		} elseif ( 1 === $depth && $this->dz_in_megamenu ) {
			$li_classes[] = 'mega-menu-column';
		}

		$output .= '<li' . ( $li_classes ? ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '"' : '' ) . '>';

		$is_current = ! empty( $item->classes ) && array_intersect(
			array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent' ),
			(array) $item->classes
		);

		$link_classes = array();
		if ( $is_current ) {
			$link_classes[] = 'active';
		}
		if ( 1 === $depth && $this->dz_in_megamenu ) {
			$link_classes[] = 'mega-menu-column-title';
		}

		$link_href = ! empty( $item->url ) ? esc_url( $item->url ) : '#';
		$link_class = $link_classes ? ' class="' . esc_attr( implode( ' ', $link_classes ) ) . '"' : '';

		$output .= '<a href="' . $link_href . '"' . $link_class . '>';

		if ( 0 === $depth && $has_children ) {
			$output .= '<span>' . esc_html( $item->title ) . '</span> <i class="bi bi-chevron-down toggle-dropdown"></i>';
		} else {
			$output .= esc_html( $item->title );
		}

		$output .= '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth >= 2 && ! $this->dz_in_megamenu ) {
			return;
		}

		$output .= '</li>';
	}
}
