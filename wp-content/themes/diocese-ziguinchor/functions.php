<?php
/**
 * Theme bootstrap: constants, includes, asset enqueue.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DZ_THEME_VERSION', '0.1.0' );
define( 'DZ_THEME_DIR', get_template_directory() );
define( 'DZ_THEME_URI', get_template_directory_uri() );

require_once DZ_THEME_DIR . '/inc/theme-setup.php';
require_once DZ_THEME_DIR . '/inc/class-dz-walker-nav-menu.php';
require_once DZ_THEME_DIR . '/inc/acf-fields.php';
require_once DZ_THEME_DIR . '/inc/cpt-paroisse.php';
require_once DZ_THEME_DIR . '/inc/cpt-pretre.php';
require_once DZ_THEME_DIR . '/inc/cpt-evenement.php';
require_once DZ_THEME_DIR . '/inc/cpt-sacrement.php';
require_once DZ_THEME_DIR . '/inc/cpt-conseil.php';
require_once DZ_THEME_DIR . '/inc/cpt-service_diocesain.php';
require_once DZ_THEME_DIR . '/inc/cpt-commission_diocesain.php';
require_once DZ_THEME_DIR . '/inc/cpt-mouvement.php';
require_once DZ_THEME_DIR . '/inc/cpt-association.php';
require_once DZ_THEME_DIR . '/inc/cpt-aumonerie.php';
require_once DZ_THEME_DIR . '/inc/cpt-etablissement.php';
require_once DZ_THEME_DIR . '/inc/cpt-ancien_eveque.php';
require_once DZ_THEME_DIR . '/inc/categories-actualites.php';
require_once DZ_THEME_DIR . '/inc/import/import-tools.php';

/**
 * Enqueue vendor and theme front-end assets (Bootstrap, Bootstrap Icons, AOS,
 * Swiper, PureCounter, main.css/main.js).
 */
function dz_enqueue_assets() {
	wp_enqueue_style(
		'dz-google-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'dz-bootstrap', DZ_THEME_URI . '/assets/vendor/bootstrap/css/bootstrap.min.css', array(), '5.3.7' );
	wp_enqueue_style( 'dz-bootstrap-icons', DZ_THEME_URI . '/assets/vendor/bootstrap-icons/bootstrap-icons.css', array(), DZ_THEME_VERSION );
	wp_enqueue_style( 'dz-aos', DZ_THEME_URI . '/assets/vendor/aos/aos.css', array(), DZ_THEME_VERSION );
	wp_enqueue_style( 'dz-swiper', DZ_THEME_URI . '/assets/vendor/swiper/swiper-bundle.min.css', array(), DZ_THEME_VERSION );
	wp_enqueue_style( 'dz-main', DZ_THEME_URI . '/assets/css/main.css', array( 'dz-bootstrap' ), DZ_THEME_VERSION );

	wp_enqueue_script( 'dz-bootstrap-bundle', DZ_THEME_URI . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array(), '5.3.7', true );
	wp_enqueue_script( 'dz-aos', DZ_THEME_URI . '/assets/vendor/aos/aos.js', array(), DZ_THEME_VERSION, true );
	wp_enqueue_script( 'dz-swiper', DZ_THEME_URI . '/assets/vendor/swiper/swiper-bundle.min.js', array(), DZ_THEME_VERSION, true );
	wp_enqueue_script( 'dz-purecounter', DZ_THEME_URI . '/assets/vendor/purecounter/purecounter_vanilla.js', array(), DZ_THEME_VERSION, true );

	wp_enqueue_script(
		'dz-main',
		DZ_THEME_URI . '/assets/js/main.js',
		array( 'dz-bootstrap-bundle', 'dz-aos', 'dz-swiper', 'dz-purecounter' ),
		DZ_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'dz_enqueue_assets' );
