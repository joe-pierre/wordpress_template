<?php
/**
 * Theme supports and navigation menus registration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'diocese-ziguinchor' ),
			'footer'  => __( 'Menu pied de page', 'diocese-ziguinchor' ),
		)
	);
}
add_action( 'after_setup_theme', 'dz_theme_setup' );

/**
 * Reads a field from the "Réglages du thème" ACF options page, without
 * fataling if ACF is not active.
 *
 * @param string $selector ACF field name.
 * @param mixed  $default  Value returned when ACF is inactive or the field is empty.
 * @return mixed
 */
function dz_get_option( $selector, $default = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $selector, 'option' );

	return ( null === $value || false === $value || '' === $value ) ? $default : $value;
}

/**
 * Finds the front-end URL of the page using a given page template.
 *
 * @param string $template      Page template filename (e.g. 'page-contact.php').
 * @param bool   $fallback_home When true (default, existing callers rely on this),
 *                               returns the site's home URL if no such page exists yet.
 *                               Pass false when the caller needs to tell "no page yet"
 *                               apart from a real URL (e.g. to hide a link).
 * @return string
 */
function dz_get_page_url_by_template( $template, $fallback_home = true ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template,
			'fields'         => 'ids',
		)
	);

	if ( $pages ) {
		return get_permalink( $pages[0] );
	}

	return $fallback_home ? home_url( '/' ) : '';
}

/**
 * Reads an ACF field on any post, without fataling if ACF is not active.
 * Use this (rather than get_field() directly) for every CPT relation/field
 * read in templates, per CONVENTIONS.md — a missing/incomplete field must
 * never trigger a PHP error.
 *
 * @param string   $selector ACF field name.
 * @param int|null $post_id  Post ID, defaults to the current post.
 * @param mixed    $default  Value returned when ACF is inactive or the field is empty.
 * @return mixed
 */
function dz_get_field( $selector, $post_id = null, $default = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $selector, $post_id );

	return ( null === $value || false === $value || '' === $value ) ? $default : $value;
}

/**
 * Replaces an em dash (—) with a simple hyphen (-), for a free-text ACF
 * field an editor may have typed with one (see DECISIONS.md "Ajustements
 * typographiques round 3" — the new paroisse design's mono/serif labels
 * read better with simple hyphens than with an em dash). Deliberately not
 * applied to the_content()/WYSIWYG output, where an em dash is ordinary
 * French prose punctuation, not a typographic choice of this design.
 *
 * @param string|null $text
 * @return string
 */
function dz_no_em_dash( $text ) {
	return str_replace( '—', '-', (string) $text );
}

/**
 * Diocese-wide headline counts ("34 Prêtres", "19 Paroisses", "6 Doyennés"
 * on the "Nos prêtres" hero, see DECISIONS.md "Refonte archive prêtres") —
 * always computed from the real content, never hard-coded, so the numbers
 * stay correct as fiches are added. `doyenne` is a taxonomy on `paroisse`
 * (SPEC.md §3), not a separate CPT, hence `wp_count_terms()` rather than
 * `wp_count_posts()` for that one.
 *
 * @return array{pretres:int,paroisses:int,doyennes:int}
 */
function dz_get_diocese_stats() {
	return array(
		'pretres'   => (int) wp_count_posts( 'pretre' )->publish,
		'paroisses' => (int) wp_count_posts( 'paroisse' )->publish,
		'doyennes'  => (int) wp_count_terms(
			array(
				'taxonomy'   => 'doyenne',
				'hide_empty' => false,
			)
		),
	);
}

/**
 * Estimated reading time for a post, in whole minutes (minimum 1).
 *
 * @param int $post_id
 * @return int
 */
function dz_get_reading_time( $post_id ) {
	$word_count = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );

	return max( 1, (int) ceil( $word_count / 200 ) );
}

/**
 * Enqueues WordPress's built-in threaded-comments script on singular posts.
 */
function dz_enqueue_comment_reply() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dz_enqueue_comment_reply' );
