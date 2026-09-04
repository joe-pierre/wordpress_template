<?php
/**
 * Custom nav menu walker.
 *
 * Reproduit le balisage du menu du template "Story" (span + icône pour les
 * éléments avec sous-menu, classe "active" sur le lien courant) et limite
 * l'affichage à 2 niveaux de profondeur : le 3e niveau ("Deep Dropdown" du
 * template d'origine) n'est jamais rendu, quelle que soit la profondeur
 * réellement construite dans l'admin (voir DECISIONS.md).
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

	public function walk( $elements, $max_depth, ...$args ) {
		$this->dz_parent_ids = array();

		foreach ( $elements as $element ) {
			if ( ! empty( $element->menu_item_parent ) ) {
				$this->dz_parent_ids[ (int) $element->menu_item_parent ] = true;
			}
		}

		return parent::walk( $elements, $max_depth, ...$args );
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth >= 1 ) {
			return;
		}

		$output .= '<ul>';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth >= 1 ) {
			return;
		}

		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth >= 2 ) {
			return;
		}

		$has_children = 0 === $depth && isset( $this->dz_parent_ids[ $item->ID ] );

		$li_classes = empty( $item->classes ) ? array() : array_filter( (array) $item->classes );
		if ( $has_children ) {
			$li_classes[] = 'dropdown';
		}

		$output .= '<li' . ( $li_classes ? ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '"' : '' ) . '>';

		$is_current  = ! empty( $item->classes ) && array_intersect(
			array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent' ),
			(array) $item->classes
		);
		$link_class = $is_current ? ' class="active"' : '';
		$link_href  = ! empty( $item->url ) ? esc_url( $item->url ) : '#';

		$output .= '<a href="' . $link_href . '"' . $link_class . '>';

		if ( $has_children ) {
			$output .= '<span>' . esc_html( $item->title ) . '</span> <i class="bi bi-chevron-down toggle-dropdown"></i>';
		} else {
			$output .= esc_html( $item->title );
		}

		$output .= '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth >= 2 ) {
			return;
		}

		$output .= '</li>';
	}
}
