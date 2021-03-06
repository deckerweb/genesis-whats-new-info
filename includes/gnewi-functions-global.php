<?php

// includes/gnewi-functions-global

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting internal plugin helper values.
 *
 * @since 1.1.0
 * @since 1.3.0 Added more values.
 * @since 1.3.2 Added FB Group.
 * @since 1.3.3 Added license.
 * @since 1.3.4 Added Newsletter.
 *
 * @return array Array of info values.
 */
function ddw_gnewi_info_values() {

	/** Get current user */
	$user = wp_get_current_user();

	/** Build Newsletter URL */
	$url_nl = sprintf(
		'https://deckerweb.us2.list-manage.com/subscribe?u=e09bef034abf80704e5ff9809&amp;id=380976af88&amp;MERGE0=%1$s&amp;MERGE1=%2$s',
		esc_attr( $user->user_email ),
		esc_attr( $user->user_firstname )
	);

	$gnewi_info = array(

		'url_translate'     => 'https://translate.wordpress.org/projects/wp-plugins/genesis-whats-new-info',
		'url_donate'        => 'https://www.paypal.me/deckerweb',
		'url_newsletter'    => $url_nl,
		'url_plugin'        => 'https://github.com/deckerweb/genesis-whats-new-info',
		'url_fb_group'      => 'https://www.facebook.com/groups/deckerweb.wordpress.plugins/',
		'first_code'        => '2013',
		'license'           => 'GPL-2.0-or-later',
		'url_license'       => 'https://opensource.org/licenses/GPL-2.0',
		'genesis_upgraded'  => admin_url( 'admin.php?page=genesis-upgraded' ),
		'genesis_changelog' => 'https://studiopress.github.io/genesis/changelog/',
		'genesis_dev_docs'  => 'https://studiopress.github.io/genesis/',
		'studiopress_blog'  => 'https://studiopress.blog/',
		'genesiswp_fbgroup' => 'https://www.facebook.com/groups/genesiswp/',
		'genesiswp_slack'   => 'https://genesiswp.slack.com/',
		'genesiswp_tweets'  => 'https://twitter.com/hashtag/GenesisWP?src=hash',

	);  // end of array

	return $gnewi_info;

}  // end function


/**
 * Get URL of specific GNEWI info value.
 *
 * @since 1.3.0
 *
 * @uses ddw_gnewi_info_values()
 *
 * @param string $url_key String of value key from array of ddw_gnewi_info_values()
 * @param bool   $raw     If raw escaping or regular escaping of URL gets used
 * @return string URL for info value.
 */
function ddw_gnewi_get_info_url( $url_key = '', $raw = FALSE ) {

	$gnewi_info = (array) ddw_gnewi_info_values();

	$output = esc_url( $gnewi_info[ sanitize_key( $url_key ) ] );

	if ( TRUE === $raw ) {
		$output = esc_url_raw( $gnewi_info[ esc_attr( $url_key ) ] );
	}

	return $output;

}  // end function


/**
 * Get link with complete markup for a specific BTC info value.
 *
 * @since 1.3.0
 *
 * @uses ddw_gnewi_get_info_url()
 *
 * @param string $url_key String of value key
 * @param string $text    String of text and link attribute
 * @param string $class   String of CSS class
 * @return string HTML markup for linked URL.
 */
function ddw_gnewi_get_info_link( $url_key = '', $text = '', $class = '' ) {

	$link = sprintf(
		'<a class="%1$s" href="%2$s" target="_blank" rel="nofollow noopener noreferrer" title="%3$s">%3$s</a>',
		strtolower( esc_attr( $class ) ),	//sanitize_html_class( $class ),
		ddw_gnewi_get_info_url( $url_key ),
		esc_html( $text )
	);

	return $link;

}  // end function


/**
 * Get timespan of coding years for this plugin.
 *
 * @since 1.3.0
 * @since 1.3.2 Improved first year logic.
 *
 * @uses ddw_gnewi_info_values()
 *
 * @param int $first_year Integer number of first year
 * @return string Timespan of years.
 */
function ddw_gnewi_coding_years( $first_year = '' ) {

	$gnewi_info = (array) ddw_gnewi_info_values();

	$first_year = ( empty( $first_year ) ) ? absint( $gnewi_info[ 'first_code' ] ) : absint( $first_year );

	/** Set year of first released code */
	$code_first_year = ( date( 'Y' ) == $first_year || 0 === $first_year ) ? '' : $first_year . '&#x02013;';

	return $code_first_year . date( 'Y' );

}  // end function
