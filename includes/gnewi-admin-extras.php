<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Genesis What's New Info
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2013-2018, David Decker - DECKERWEB
 * @license    https://opensource.org/licenses/GPL-2.0
 * @link       https://github.com/deckerweb/genesis-whats-new-info/
 * @link       https://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

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
 */
function ddw_gnewi_info_values() {

	$gnewi_info = array(

		'url_translate'     => 'https://translate.wordpress.org/projects/wp-plugins/genesis-whats-new-info',
		'url_donate'        => 'https://www.paypal.me/deckerweb',
		'url_plugin'        => 'https://github.com/deckerweb/genesis-whats-new-info/'

	);  // end of array

	return $gnewi_info;

}  // end function


/**
 * Add Admin link to plugin page.
 *
 * @since  1.0.0
 *
 * @param  array $gnewi_links (Default) Array of plugin action links.
 * @return string Admin page link.
 */
function ddw_gnewi_admin_page_link( $gnewi_links ) {

	/** Admin page link */
	$gnewi_admin_link = sprintf(
		'<a class="dashicons-before dashicons-info" href="%1$s" title="%2$s">%3$s</a>',
		esc_url( admin_url( 'admin.php?page=genesis-upgraded' ) ),
		sprintf(
			/* translators: %s - Label of Admin page ("Genesis Whats's New") */
			esc_html__( 'Go to the %s admin page', 'genesis-whats-new-info' ),
			esc_html__( 'Genesis What\'s New', 'genesis-whats-new-info' )
		),
		esc_attr__( 'What\'s New', 'genesis-whats-new-info' )
	);

	/** Set the order of the links */
	array_unshift( $gnewi_links, $gnewi_admin_link );

	/** Display plugin settings links */
	return apply_filters( 'gnewi_filter_admin_page_link', $gnewi_links );

}  // end function


add_filter( 'plugin_row_meta', 'ddw_gnewi_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 *
 * @uses   ddw_gnewi_info_values()
 *
 * @param  array  $gnewi_links (Default) Array of plugin meta links
 * @param  string $gnewi_file  URL of base plugin file
 *
 * @return array $gnewi_links Array of plugin link strings to build HTML markup.
 */
function ddw_gnewi_plugin_links( $gnewi_links, $gnewi_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {
		return $gnewi_links;
	}

	/** List additional links only for this plugin */
	if ( $gnewi_file === GNEWI_PLUGIN_BASEDIR . 'genesis-whats-new-info.php' ) {

		$gnewi_info = (array) ddw_gnewi_info_values();

		$gnewi_links[] = '<a href="' . esc_url( $gnewi_info[ 'url_translate' ] ) . '" target="_blank" rel="nofollow noopener noreferrer" title="' . __( 'Translations', 'genesis-whats-new-info' ) . '">' . __( 'Translations', 'genesis-whats-new-info' ) . '</a>';

		$gnewi_links[] = '<a class="button" href="' . esc_url( $gnewi_info[ 'url_donate' ] ) . '" target="_blank" rel="nofollow noopener noreferrer" title="' . __( 'Donate', 'genesis-whats-new-info' ) . '"><strong>' . __( 'Donate', 'genesis-whats-new-info' ) . '</strong></a>';

	}  // end-if plugin links

	/** Output the links */
	return apply_filters( 'gnewi_filter_plugin_links', $gnewi_links );

}  // end function


add_filter( 'plugins_api_result', 'ddw_gnewi_add_tbex_api_result', 11, 3 );
/**
 * Filter plugin fetching API results to inject plugin "Cleaner Plugin Installer".
 *
 * @since   1.2.0
 *
 * Original code by Remy Perona/ WP-Rocket.
 * @author  Remy Perona
 * @link    https://wp-rocket.me/
 * @license GPL-2.0+
 * 
 * @param   object|WP_Error $result Response object or WP_Error.
 * @param   string          $action The type of information being requested from the Plugin Install API.
 * @param   object          $args   Plugin API arguments.
 *
 * @return array Updated array of results.
 */
function ddw_gnewi_add_tbex_api_result( $result, $action, $args ) {

	if ( empty( $args->browse ) ) {
		return $result;
	}

	if ( 'featured' !== $args->browse
		&& 'recommended' !== $args->browse
		&& 'popular' !== $args->browse
	) {
		return $result;
	}

	if ( ! isset( $result->info[ 'page' ] ) || 1 < $result->info[ 'page' ] ) {
		return $result;
	}

	/** Check if plugin active */
	if ( ( is_plugin_active( 'toolbar-extras/toolbar-extras.php' ) || is_plugin_active_for_network( 'toolbar-extras/toolbar-extras.php' ) ) ) {
		return $result;
	}

	/** Grab all slugs from the api results. */
	$result_slugs = wp_list_pluck( $result->plugins, 'slug' );

	$query_fields = array(
		'icons'             => TRUE,
		'active_installs'   => TRUE,
		'short_description' => TRUE,
		'group'             => TRUE,
	);

	$tbex_query_args = array(
		'slug'   => 'toolbar-extras',	// plugin slug from wordpress.org
		'fields' => $query_fields,
	);

	$tbex_data = plugins_api( 'plugin_information', $tbex_query_args );

	if ( is_wp_error( $tbex_data ) ) {
		return $result;
	}

	array_unshift( $result->plugins, $tbex_data );

	return $result;

}  // end function
