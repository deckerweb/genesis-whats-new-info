<?php

// includes/gnewi-admin-extras

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
 * @since 1.0.0
 *
 * @param array $gnewi_links (Default) Array of plugin action links.
 * @return string Admin page link.
 */
function ddw_gnewi_admin_page_link( $gnewi_links ) {

	/** Admin page link */
	$gnewi_admin_link = sprintf(
		'<a href="%1$s" title="%2$s"><span class="dashicons-before dashicons-info"></span> %3$s</a>',
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
 * @since 1.0.0
 * @since 1.3.0 Improved link building.
 *
 * @uses ddw_gnewi_info_values()
 * @uses ddw_gnewi_get_info_link()
 *
 * @param array  $gnewi_links (Default) Array of plugin meta links
 * @param string $gnewi_file  URL of base plugin file
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

		?>
			<style type="text/css">
				tr[data-plugin="<?php echo $gnewi_file; ?>"] .plugin-version-author-uri a.dashicons-before:before {
					font-size: 17px;
					margin-right: 2px;
					opacity: .85;
					vertical-align: sub;
				}
			</style>
		<?php

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_translate', esc_html_x( 'Translations', 'Plugins page listing', 'genesis-whats-new-info' ), 'dashicons-before dashicons-translation' );

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_fb_group', esc_html_x( 'Facebook Group', 'Plugins page listing', 'genesis-whats-new-info' ), 'dashicons-before dashicons-facebook' );

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_donate', esc_html_x( 'Donate', 'Plugins page listing', 'genesis-whats-new-info' ), 'button dashicons-before dashicons-thumbs-up' );

		/* translators: Plugins page listing */
		$gnewi_links[] = ddw_gnewi_get_info_link( 'url_newsletter', esc_html_x( 'Join our Newsletter', 'Plugins page listing', 'genesis-whats-new-info' ), 'button-primary dashicons-before dashicons-awards' );

	}  // end-if plugin links

	/** Output the links */
	return apply_filters(
		'gnewi/filter/plugins_page/more_links',
		$gnewi_links
	);

}  // end function


add_filter( 'debug_information', 'ddw_gnewi_site_health_add_debug_info', 9 );
/**
 * Add additional plugin related info to the Site Health Debug Info section.
 *   (Only relevant for WordPress 5.2 or higher)
 *
 * @link https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
 *
 * @since 1.3.4
 *
 * @param array $debug_info Array holding all Debug Info items.
 * @return array Modified array of Debug Info.
 */
function ddw_gnewi_site_health_add_debug_info( $debug_info ) {

	$string_undefined = esc_html__( 'Undefined', 'genesis-whats-new-info' );

	/** Add our Debug info */
	$debug_info[ 'genesis-whats-new-info' ] = array(
		'label'  => esc_html__( 'Genesis What\'s New Info', 'genesis-whats-new-info' ) . ' (' . esc_html__( 'Plugin', 'genesis-whats-new-info' ) . ')',
		'fields' => array(
			'gwnf_plugin_version' => array(
				'label' => __( 'Plugin version', 'genesis-whats-new-info' ),
				'value' => GNEWI_VERSION,
			),
			'PARENT_THEME_VERSION' => array(
				'label' => 'Genesis: PARENT_THEME_VERSION',
				'value' => ( ! defined( 'PARENT_THEME_VERSION' ) ? $string_undefined : PARENT_THEME_VERSION ),
			),
			'CHILD_THEME_VERSION' => array(
				'label' => 'Genesis Child: CHILD_THEME_VERSION',
				'value' => ( ! defined( 'CHILD_THEME_VERSION' ) ? $string_undefined : CHILD_THEME_VERSION ),
			),
		),
	);

	/** Return modified Debug Info array */
	return $debug_info;

}  // end function


if ( ! function_exists( 'ddw_wp_site_health_remove_percentage' ) ) :

	add_action( 'admin_head', 'ddw_wp_site_health_remove_percentage', 100 );
	/**
	 * Remove the "Percentage Progress" display in Site Health feature as this will
	 *   get users obsessed with fullfilling a 100% where there are non-problems!
	 *
	 * @link https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
	 *
	 * @since 1.3.4
	 */
	function ddw_wp_site_health_remove_percentage() {

		/** Bail early if not on WP 5.2+ */
		if ( version_compare( $GLOBALS[ 'wp_version' ], '5.2-beta', '<' ) ) {
			return;
		}

		?>
			<style type="text/css">
				.site-health-progress {
					display: none;
				}
			</style>
		<?php

	}  // end function

endif;


if ( ! function_exists( 'ddw_genesis_tweak_plugins_submenu' ) ) :

	add_action( 'admin_menu', 'ddw_genesis_tweak_plugins_submenu', 11 );
	/**
	 * Add Genesis submenu redirecting to "genesis" plugin search within the
	 *   WordPress.org Plugin Directory. For Genesis 2.10.0 or higher this
	 *   replaces the "Genesis Plugins" submenu which only lists plugins from
	 *   StudioPress - but there are many more from the community.
	 *
	 * @since 1.3.4
	 *
	 * @uses remove_submenu_page()
	 * @uses add_submenu_page()
	 */
	function ddw_genesis_tweak_plugins_submenu() {

		/** Remove the StudioPress plugins submenu */
		if ( class_exists( 'Genesis_Admin_Plugins' ) ) {
			remove_submenu_page( 'genesis', 'genesis-plugins' );
		}

		/** Add a Genesis community plugins submenu */
		add_submenu_page(
			'genesis',
			esc_html__( 'Genesis Plugins from the Plugin Directory', 'genesis-whats-new-info' ),
			esc_html__( 'Genesis Plugins', 'genesis-whats-new-info' ),
			'install_plugins',
			esc_url( network_admin_url( 'plugin-install.php?s=genesis&tab=search&type=term' ) )
		);

	}  // end function

endif;


/**
 * Inline CSS fix for Plugins page update messages.
 *
 * @since 1.3.2
 *
 * @see ddw_gnewi_plugin_update_message()
 * @see ddw_gnewi_multisite_subsite_plugin_update_message()
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
 * @since 1.3.2
 *
 * @param object $data
 * @param object $response
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
 * @since 1.3.2
 *
 * @param string $file
 * @param object $plugin
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
 * @since 1.3.0
 *
 * @param array $plugins Array holding all plugin recommendations, coming from
 *                       the class and the filter.
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
	 * @since 1.3.2
	 * @since 1.3.4 Added new strings.
	 *
	 * @param array $strings Holds all filterable strings of the library.
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

		$strings[ 'ddwplugins_tab' ] = _x(
			'deckerweb Plugins',
			'Plugin installer: Tab name in installer toolbar',
			'genesis-whats-new-info'
		);

		$strings[ 'tab_title' ] = _x(
			'deckerweb Plugins',
			'Plugin installer: Page title',
			'genesis-whats-new-info'
		);

		$strings[ 'tab_slogan' ] = __( 'Great helper tools for Site Builders to save time and get more productive', 'genesis-whats-new-info' );

		$strings[ 'tab_info' ] = sprintf(
			__( 'You can use any of our free plugins or premium plugins from %s', 'genesis-whats-new-info' ),
			'<a href="https://deckerweb-plugins.com/" target="_blank" rel="nofollow noopener noreferrer">' . $strings[ 'tab_title' ] . '</a>'
		);

		$strings[ 'tab_newsletter' ] = __( 'Join our Newsletter', 'genesis-whats-new-info' );

		$strings[ 'tab_fbgroup' ] = __( 'Facebook User Group', 'genesis-whats-new-info' );

		return $strings;

	}  // end function

endif;  // function check

/** Include class DDWlib Plugin Installer Recommendations */
require_once( GNEWI_PLUGIN_DIR . 'includes/ddwlib-plir/ddwlib-plugin-installer-recommendations.php' );
