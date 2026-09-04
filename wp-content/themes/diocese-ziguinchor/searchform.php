<?php
/**
 * Custom search form, matching the "Story" template's markup/CSS
 * (`.search-widget form input[type=text]`, `.search-widget form button`)
 * instead of WordPress's default markup (input[type=search] + input[type=submit]).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Rechercher…', 'diocese-ziguinchor' ); ?>">
	<button type="submit" title="<?php esc_attr_e( 'Rechercher', 'diocese-ziguinchor' ); ?>"><i class="bi bi-search"></i></button>
</form>
