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
	return apply_filters( 'gnewi/filter/plugins_page/admin_link', $gnewi_links );

}  // end function


add_filter( 'plugin_row_meta', 'ddw_gnewi_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 * @since  1.3.0 Improved link building.
 *
 * @uses   ddw_gnewi_info_values()
 * @uses   ddw_gnewi_get_info_link()
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

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_translate', esc_html_x( 'Translations', 'Plugins page listing', 'genesis-whats-new-info' ), 'dashicons-before dashicons-translation' );

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_fb_group', esc_html_x( 'Facebook Group', 'Plugins page listing', 'genesis-whats-new-info' ), 'dashicons-before dashicons-facebook' );

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_donate', esc_html_x( 'Donate', 'Plugins page listing', 'genesis-whats-new-info' ), 'button-primary dashicons-before dashicons-thumbs-up' );

	}  // end-if plugin links

	/** Output the links */
	return apply_filters(
		'gnewi/filter/plugins_page/more_links',
		$gnewi_links
	);

}  // end function


/**
 * Inline CSS fix for Plugins page update messages.
 *
 * @since 1.3.2
 *
 * @see   ddw_gnewi_plugin_update_message()
 * @see   ddw_gnewi_multisite_subsite_plugin_update_message()
 */
function ddw_gnewi_plugin_update_message_style_tweak() {

	?>
		<style type="text/css">
			.gnewi-update-message p:before,
			.update-message.notice p:empty {
				display: none !important;
			}
		</style>
	<?php

}  // end function


add_action( 'in_plugin_update_message-' . GNEWI_PLUGIN_BASEDIR . 'genesis-whats-new-info.php', 'ddw_gnewi_plugin_update_message', 10, 2 );
/**
 * On Plugins page add visible upgrade/update notice in the overview table.
 *   Note: This action fires for regular single site installs, and for Multisite
 *         installs where the plugin is activated Network-wide.
 *
 * @since  1.3.2
 *
 * @param  object $data
 * @param  object $response
 * @return string Echoed string and markup for the plugin's upgrade/update
 *                notice.
 */
function ddw_gnewi_plugin_update_message( $data, $response ) {

	if ( isset( $data[ 'upgrade_notice' ] ) ) {

		ddw_gnewi_plugin_update_message_style_tweak();

		printf(
			'<div class="update-message gnewi-update-message">%s</div>',
			wpautop( $data[ 'upgrade_notice' ] )
		);

	}  // end if

}  // end function


add_action( 'after_plugin_row_wp-' . GNEWI_PLUGIN_BASEDIR . 'genesis-whats-new-info.php', 'ddw_gnewi_multisite_subsite_plugin_update_message', 10, 2 );
/**
 * On Plugins page add visible upgrade/update notice in the overview table.
 *   Note: This action fires for Multisite installs where the plugin is
 *         activated on a per site basis.
 *
 * @since  1.3.2
 *
 * @param  string $file
 * @param  object $plugin
 * @return string Echoed string and markup for the plugin's upgrade/update
 *                notice.
 */
function ddw_gnewi_multisite_subsite_plugin_update_message( $file, $plugin ) {

	if ( is_multisite() && version_compare( $plugin[ 'Version' ], $plugin[ 'new_version' ], '<' ) ) {

		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

		ddw_gnewi_plugin_update_message_style_tweak();

		printf(
			'<tr class="plugin-update-tr"><td colspan="%s" class="plugin-update update-message notice inline notice-warning notice-alt"><div class="update-message gnewi-update-message"><h4 style="margin: 0; font-size: 14px;">%s</h4>%s</div></td></tr>',
			$wp_list_table->get_column_count(),
			$plugin[ 'Name' ],
			wpautop( $plugin[ 'upgrade_notice' ] )
		);

	}  // end if

}  // end function


/**
 * Optionally tweaking Plugin API results to make more useful recommendations to
 *   the user.
 *
 * @since 1.2.0
 * @since 1.3.0 Complete refactoring, using library class DDWlib Plugin
 *              Installer Recommendations
 */

add_filter( 'ddwlib_plir/filter/plugins', 'ddw_gnewi_register_plugin_recommendations' );
/**
 * Register specific plugins for the class "DDWlib Plugin Installer
 *   Recommendations".
 *   Note: The top-level array keys are plugin slugs from the WordPress.org
 *         Plugin Directory.
 *
 * @since  1.3.0
 *
 * @param  array $plugins Array holding all plugin recommendations, coming from
 *                        the class and the filter.
 * @return array Filtered and merged array of all plugin recommendations.
 */
function ddw_gnewi_register_plugin_recommendations( array $plugins ) {
  
  	/** Remove our own slug when we are already active :) */
  	if ( isset( $plugins[ 'genesis-whats-new-info' ] ) ) {
  		$plugins[ 'genesis-whats-new-info' ] = null;
  	}

  	/** Register our additional plugin recommendations */
	$gnewi_plugins = array(
		'genesis-layout-extras' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'yes',
		),
		'genesis-widgetized-footer' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'yes',
		),
		'genesis-widgetized-notfound' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'yes',
		),
		'genesis-widgetized-archive' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'no',
		),
		'blox-lite' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'no',
		),
		'genesis-title-toggle' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'yes',
		),
		'genesis-footer-builder' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'no',
		),
		'display-featured-image-genesis' => array(
			'featured'    => 'yes',
			'recommended' => 'yes',
			'popular'     => 'no',
		),
		'genesis-enews-extended' => array(
			'featured'    => 'no',
			'recommended' => 'no',
			'popular'     => 'yes',
		),
		'genesis-simple-edits' => array(
			'featured'    => 'no',
			'recommended' => 'no',
			'popular'     => 'yes',
		),
		'genesis-simple-sidebars' => array(
			'featured'    => 'no',
			'recommended' => 'yes',
			'popular'     => 'yes',
		),
		'genesis-simple-hooks' => array(
			'featured'    => 'no',
			'recommended' => 'no',
			'popular'     => 'yes',
		),
	);

  	/** Merge with the existing recommendations and return */
	return array_merge( $plugins, $gnewi_plugins );

}  // end function

/** Optionally add string translations for the library */
if ( ! function_exists( 'ddwlib_plir_strings_plugin_installer' ) ) :

	add_filter( 'ddwlib_plir/filter/strings/plugin_installer', 'ddwlib_plir_strings_plugin_installer' );
	/**
	 * Optionally, make strings translateable for included library "DDWlib Plugin
	 *   Installer Recommendations".
	 *   Strings:
	 *    - "Newest" --> tab in plugin installer toolbar
	 *    - "Version:" --> label in plugin installer plugin card
	 *
	 * @since  1.3.2
	 *
	 * @param  array $strings Holds all filterable strings of the library.
	 * @return array Array of tweaked translateable strings.
	 */
	function ddwlib_plir_strings_plugin_installer( $strings ) {

		$strings[ 'newest' ] = _x(
			'Newest',
			'Plugin installer: Tab name in installer toolbar',
			'genesis-whats-new-info'
		);

		$strings[ 'version' ] = _x(
			'Version:',
			'Plugin card: plugin version',
			'genesis-whats-new-info'
		);

		return $strings;

	}  // end function

endif;  // function check

/** Include class DDWlib Plugin Installer Recommendations */
require_once( GNEWI_PLUGIN_DIR . 'includes/ddwlib-plugin-installer-recommendations.php' );