<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Genesis What's New Info
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2013-2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/genesis-whats-new-info/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting internal plugin helper values.
 *
 * @since 1.1.0
 *
 * @uses  get_locale()
 */
function ddw_gnewi_info_values() {

	$gnewi_info = array(

		'url_translate'     => 'http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-whats-new-info',
		'url_donate'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/spenden/' : 'http://genesisthemes.de/en/donate/',
		'url_plugin'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/plugins/genesis-whats-new-info/' : 'http://genesisthemes.de/en/wp-plugins/genesis-whats-new-info/'

	);  // end of array

	return $gnewi_info;

}  // end of function ddw_gnewi_info_values


/**
 * Add Admin link to plugin page.
 *
 * @since  1.0.0
 *
 * @param  $gnewi_links
 *
 * @return strings Admin page link.
 */
function ddw_gnewi_admin_page_link( $gnewi_links ) {

	/** Admin page link */
	$gnewi_admin_link = sprintf(
		'<a href="%s" title="%s">%s</a>',
		admin_url( 'admin.php?page=genesis-upgraded' ),
		sprintf(
			esc_html__( 'Go to the %s admin page', 'genesis-whats-new-info' ),
			esc_html__( 'Genesis What\'s New', 'genesis-whats-new-info' )
		),
		esc_attr__( 'What\'s New', 'genesis-whats-new-info' )
	);

	/** Set the order of the links */
	array_unshift( $gnewi_links, $gnewi_admin_link );

	/** Display plugin settings links */
	return apply_filters( 'gnewi_filter_admin_page_link', $gnewi_links );

}  // end of function ddw_gnewi_widgets_page_link


add_filter( 'plugin_row_meta', 'ddw_gnewi_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 *
 * @param  $gnewi_links
 * @param  $gnewi_file
 *
 * @return strings plugin links
 */
function ddw_gnewi_plugin_links( $gnewi_links, $gnewi_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {

		return $gnewi_links;

	}  // end-if cap check

	/** List additional links only for this plugin */
	if ( $gnewi_file == GNEWI_PLUGIN_BASEDIR . 'genesis-whats-new-info.php' ) {

		$gnewi_info = (array) ddw_gnewi_info_values();

		$gnewi_links[] = '<a href="' . esc_url( $gnewi_info[ 'url_translate' ] ) . '" target="_new" title="' . __( 'Translations', 'genesis-whats-new-info' ) . '">' . __( 'Translations', 'genesis-whats-new-info' ) . '</a>';

		$gnewi_links[] = '<a href="' . esc_url( $gnewi_info[ 'url_donate' ] ) . '" target="_new" title="' . __( 'Donate', 'genesis-whats-new-info' ) . '"><strong>' . __( 'Donate', 'genesis-whats-new-info' ) . '</strong></a>';

	}  // end-if plugin links

	/** Output the links */
	return apply_filters( 'gnewi_filter_plugin_links', $gnewi_links );

}  // end of function ddw_gnewi_plugin_links