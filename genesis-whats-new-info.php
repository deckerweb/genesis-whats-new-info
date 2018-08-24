<?php # -*- coding: utf-8 -*-
/**
 * Main plugin file.
 * Easy access of the Genesis What's New Admin overview page - not only on
 *    updates but everytime. Makes sense, heh? :)
 *
 * @package      Genesis What's New Info
 * @author       David Decker
 * @copyright    Copyright (c) 2013-2018, David Decker - DECKERWEB
 * @license      GPL-2.0+
 * @link         https://deckerweb.de/twitter
 *
 * @wordpress-plugin
 * Plugin Name:  Genesis What's New Info
 * Plugin URI:   https://github.com/deckerweb/genesis-whats-new-info/
 * Description:  Easy access of the Genesis What's New Admin overview page - not only on updates but everytime. Makes sense, heh? :)
 * Version:      1.3.1
 * Author:       David Decker - DECKERWEB
 * Author URI:   https://deckerweb.de/
 * License:      GPL-2.0+
 * License URI:  https://opensource.org/licenses/GPL-2.0
 * Text Domain:  genesis-whats-new-info
 * Domain Path:  /languages/
 * Requires WP:  4.7
 * Requires PHP: 5.6
 *
 * Copyright (c) 2013-2018 David Decker - DECKERWEB
 *
 *     This file is part of Genesis What's New Info,
 *     a plugin for WordPress.
 *
 *     Genesis What's New Info is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     Genesis What's New Info is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Exit if called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting constants
 *
 * @since 1.0.0
 */
/** Plugin directory */
define( 'GNEWI_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

/** Plugin base directory */
define( 'GNEWI_PLUGIN_BASEDIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );


add_action( 'init', 'ddw_gnewi_load_translations', 1 );
/**
 * Load the text domain for translation of the plugin.
 *
 * @since 1.2.0
 *
 * @uses  get_user_locale()
 * @uses  load_textdomain() To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 */
function ddw_gnewi_load_translations() {

	/** Load translations for Admin */
	if ( is_admin() || is_admin_bar_showing() ) {

		/** Set textdomain */
		$gnewi_textdomain = 'genesis-whats-new-info';

		/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
		$locale = esc_attr(
			apply_filters(
				'plugin_locale',
				get_user_locale(),
				$gnewi_textdomain
			)
		);

		/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
		load_textdomain(
			$gnewi_textdomain,
			trailingslashit( WP_LANG_DIR ) . trailingslashit( $gnewi_textdomain ) . $gnewi_textdomain . '-' . $locale . '.mo'
		);

		/** Translations: Secondly, look in plugin's "languages" folder = default */
		load_plugin_textdomain(
			$gnewi_textdomain,
			FALSE,
			GNEWI_PLUGIN_BASEDIR . 'languages'
		);

	}  // end if

}  // end function


register_activation_hook( __FILE__, 'ddw_gnewi_activation_check' );
/**
 * Checks for activated Genesis Framework before allowing plugin to activate.
 *
 * @since 1.0.0
 *
 * @uses  ddw_gnewi_load_translations()
 * @uses  get_template_directory()
 * @uses  deactivate_plugins()
 * @uses  wp_die()
 */
function ddw_gnewi_activation_check() {

	/** Load translations to display for the activation message. */
	ddw_gnewi_load_translations();

	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( ! class_exists( 'Genesis_Admin_Upgraded' ) ) {

		/** If no Genesis, deactivate ourself */
		deactivate_plugins( plugin_basename( __FILE__ ) );

		/** Message: no Genesis active */
		$gnewi_deactivation_message = sprintf(
			/* translators: 1 = Plugin name / 2 & 3 = Open and close anchor tags */
			__( 'Sorry, you cannot activate the %1$s plugin unless you have installed the %2$sGenesis Framework%3$s.', 'genesis-whats-new-info' ),
			__( 'Genesis What\'s New Info', 'genesis-whats-new-info' ),
			'<a href="https://deckerweb.de/go/genesis/" target="_blank" rel="nofollow noopener noreferrer"><strong><em>',
			'</em></strong></a>'
		);

		/** Deactivation message */
		wp_die(
			$gnewi_deactivation_message,
			__( 'Plugin', 'genesis-whats-new-info' ) . ': ' . __( 'Genesis What\'s New Info', 'genesis-whats-new-info' ),
			array( 'back_link' => TRUE )
		);

	}  // end if

}  // end function


add_action( 'init', 'ddw_gnewi_init', 1 );
/**
 * Load admin helper functions - only within 'wp-admin'.
 * 
 * @since 1.0.0
 * @since 1.3.0 Enhanced functionality.
 */
function ddw_gnewi_init() {

	/** Load the admin functions only when needed */
	if ( is_admin() ) {

		require_once( GNEWI_PLUGIN_DIR . 'includes/gnewi-functions-global.php' );
		require_once( GNEWI_PLUGIN_DIR . 'includes/gnewi-deprecated.php' );

		if ( apply_filters( 'gnewi/filter/genesis_ataglance', TRUE ) ) {
			require_once( GNEWI_PLUGIN_DIR . 'includes/gnewi-genesis-metabox.php' );
		}

		require_once( GNEWI_PLUGIN_DIR . 'includes/gnewi-admin-extras.php' );

	}  // end if

	/** Add "What's New" page link to plugin page */
	if ( ( is_admin() || is_network_admin() )
		&& current_user_can( 'edit_theme_options' )
	) {

		add_filter(
			'plugin_action_links_' . plugin_basename( __FILE__ ),
			'ddw_gnewi_admin_page_link'
		);

		add_filter(
			'network_admin_plugin_action_links_' . plugin_basename( __FILE__ ),
			'ddw_gnewi_admin_page_link'
		);

	}  // end if

}  // end function


/**
 * Check for active Genesis admin menu display options - per user.
 *
 * @since  1.1.0
 *
 * @return bool Returns TRUE if any of the Genesis core admin pages are active
 *              for the current user, otherwise FALSE.
 */
function ddw_gnewi_genesis_admin_menu_active() {

	/** Get current user - we need this for checking Genesis admin menu display options */
	$gnewi_user = wp_get_current_user();

	/** Check for any of the Genesis core admin pages - per user */
	if ( get_the_author_meta( 'genesis_admin_menu', $gnewi_user->ID )
		|| get_the_author_meta( 'genesis_seo_settings_menu', $gnewi_user->ID )
		|| get_the_author_meta( 'genesis_import_export_menu', $gnewi_user->ID )

	) {

		return TRUE;

	} else {

		return FALSE;

	}  // end if

}  // end function


add_action( 'admin_menu', 'ddw_gnewi_admin_init', 11 );
/**
 * Load plugin's admin settings page - only within 'wp-admin'.
 * 
 * @since 1.0.0
 * @since 1.2.0 Make it work for Genesis 2.6.0 or higher.
 * @since 1.3.0 Simplified, adhere more to WordPress standards.
 *
 * @uses  ddw_gnewi_genesis_admin_menu_active()
 * @uses  genesis_is_menu_page()
 * @uses  PARENT_THEME_BRANCH
 * @uses  ddw_gnewi_render_page_genesis_upgraded() Callback to render Genesis' page.
 */
function ddw_gnewi_admin_init() {

	/** If in 'wp-admin' include admin settings & help tabs */
	if ( ! ddw_gnewi_genesis_admin_menu_active() ) {
		return;
	}

	/** Get Genesis branch version */
	$parent_theme_branch = ( defined( 'PARENT_THEME_BRANCH' ) && PARENT_THEME_BRANCH ) ? PARENT_THEME_BRANCH : '';

	/** Display the "Genesis What's New" page content */
	add_submenu_page(
		'genesis',
		sprintf( __( 'Welcome to Genesis %s', 'genesis-whats-new-info' ), $parent_theme_branch ),
		sprintf( __( 'What\'s New', 'genesis-whats-new-info' ) . ' <small>(%s)</small>', $parent_theme_branch ),
		'edit_theme_options',
		admin_url( 'admin.php?page=genesis-upgraded' )
	);

	/** Load default Thickbox Scripts */
	if ( function_exists( 'genesis_is_menu_page' ) && genesis_is_menu_page( 'genesis-upgraded' ) ) {
		add_action( 'admin_enqueue_scripts', 'add_thickbox' );
	}

}  // end function


add_action( 'admin_notices', 'ddw_gnewi_upgrade_notice_helper', 5 );
/**
 * Helper function for replacing Genesis upgrade admin notice with our own.
 *
 * @since 1.0.0
 */
function ddw_gnewi_upgrade_notice_helper() {

	/** Remove Genesis notice */
	remove_action( 'admin_notices', 'genesis_upgraded_notice' );

	/** Load our own notice */
	add_action( 'admin_notices', 'ddw_gnewi_genesis_upgraded_notice' );

}  // end function


/**
 * Displays the notice that the theme settings were successfully updated to the latest version.
 *
 * Currently only used for pre-release update notices.
 *
 * @since  1.0.0
 *
 * @uses   genesis_get_option()   Get theme setting value.
 * @uses   genesis_is_menu_page() Check that we're targeting a specific Genesis admin page.
 *
 * @return null Returns early if not on the Theme Settings page.
 */
function ddw_gnewi_genesis_upgraded_notice() {

	/** Bail early if not on Genesis Theme Settings page */
	if ( function_exists( 'genesis_is_menu_page' ) && ! genesis_is_menu_page( 'genesis' ) ) {
		return;
	}

	/** Check for 'upgraded' request */
	if ( isset( $_REQUEST[ 'upgraded' ] ) && 'true' == $_REQUEST[ 'upgraded' ] ) {

		$gnewi_congrats = sprintf(
				__( 'Congratulations! You are now rocking Genesis %s', 'genesis-whats-new-info' ),
				genesis_get_option( 'theme_version' )
		);

		$gnewi_whats_new = sprintf(
				' &rarr; <a href="%1$s" title="%2$s">%3$s</a>',
				esc_url( admin_url( 'admin.php?page=genesis-upgraded' ) ),
				esc_html( $gnewi_congrats ),
				__( 'See what\'s new in this version/ branch', 'genesis-whats-new-info' )
		);

		/** Display the advanced notice, now with link */
		echo sprintf(
			'<div id="message" class="updated highlight" id="message"><p><strong>%1$s%2$s</strong></p></div>',
			$gnewi_congrats,
			$gnewi_whats_new
		);

	}  // end-if 'upgraded' request check

}  // end function


add_action( 'admin_bar_menu', 'ddw_gnewi_add_toolbar_items', 90 );
/**
 * Add "Genesis What's new" Toolbar item.
 *
 * @since  1.1.0
 *
 * @uses   ddw_gnewi_genesis_admin_menu_active()
 *
 * @global mixed $GLOBALS[ 'wp_admin_bar' ]
 */
function ddw_gnewi_add_toolbar_items() {

	/** Bail early if conditions are not met */
	if ( ! is_user_logged_in()
		|| ! is_admin_bar_showing()
		|| ! current_user_can( 'edit_theme_options' )
		|| ! ddw_gnewi_genesis_admin_menu_active()
	) {
		return;
	}

	/** Add our Toolbar main item */
	$GLOBALS[ 'wp_admin_bar' ]->add_node(
		array(
			'id'     => 'genesis-whats-new-info', 
			'parent' => 'wp-logo-default',
			'title'  => esc_attr__( 'Genesis: About &amp; What\'s New', 'genesis-whats-new-info' ),
			'href'   => esc_url( admin_url( 'admin.php?page=genesis-upgraded' ) ),
			/* translators: Label of link title attribute */
			'meta'   => array( 'title' => esc_attr_x( 'Genesis: About &amp; What\'s New', 'Label of link title attribute', 'genesis-whats-new-info' ) )
		)
	);

}  // end function