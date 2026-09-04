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
 * Finds the front-end URL of the page using a given page template, falling
 * back to the site's home URL when no such page exists yet.
 *
 * @param string $template Page template filename (e.g. 'page-contact.php').
 * @return string
 */
function dz_get_page_url_by_template( $template ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template,
			'fields'         => 'ids',
		)
	);

	return $pages ? get_permalink( $pages[0] ) : home_url( '/' );
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
