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
