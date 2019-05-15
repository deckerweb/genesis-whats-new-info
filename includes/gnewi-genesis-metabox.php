<?php

// includes/gnewi-genesis-metabox

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


add_action( 'genesis_theme_settings_metaboxes', 'ddw_gnewi_add_meta_box' );
/**
 * Add new Meta Box "At a Glance" on Genesis Theme Settings admin page.
 *
 * @since 1.3.0
 *
 * @param string $_genesis_theme_settings_pagehook
 */
function ddw_gnewi_add_meta_box( $_genesis_theme_settings_pagehook ) {

	add_meta_box(
		'gnewi-ataglance',
		esc_attr__( 'At a Glance', 'genesis-whats-new-info' ),
		'ddw_gnewi_render_meta_box',
		$_genesis_theme_settings_pagehook,
		'main',
		'high'
	);

}  // end function


/**
 * Render the Meta Box' content.
 *
 * @since 1.3.0
 *
 * @uses ddw_gnewi_get_info_url()
 * @uses ddw_gnewi_get_info_link()
 */
function ddw_gnewi_render_meta_box() {

	do_action( 'gnewi/action/ataglance_before' );

	?>
		<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Genesis Framework', 'genesis-whats-new-info' ); ?></th>
				<td>
					<p><span class="dashicons-before dashicons-info"></span> <?php _e( 'Version', 'genesis-whats-new-info' ); ?>: <?php echo PARENT_THEME_VERSION; ?><?php echo defined( 'PARENT_THEME_RELEASE_DATE' ) ? ' / ' . __( 'Release Date', 'genesis-whats-new-info' ) . ': ' . PARENT_THEME_RELEASE_DATE : ''; ?></p>
					<p><span class="dashicons-before dashicons-info"></span> <a href="<?php echo ddw_gnewi_get_info_url( 'genesis_upgraded' ); ?>"><?php _e( 'What\'s New Update Page', 'genesis-whats-new-info' ); ?></a></p>
					<p><span class="dashicons-before dashicons-media-code"></span> <?php echo ddw_gnewi_get_info_link( 'genesis_changelog', __( 'Genesis Framework Changelog', 'genesis-whats-new-info' ) ); ?></p>
					<p><span class="dashicons-before dashicons-admin-generic"></span> <?php echo ddw_gnewi_get_info_link( 'genesis_dev_docs', __( 'Genesis Developer Documentation', 'genesis-whats-new-info' ) ); ?></p>
					<p><span class="dashicons-before dashicons-admin-post"></span> <?php echo ddw_gnewi_get_info_link( 'studiopress_blog', __( 'Official StudioPress Blog', 'genesis-whats-new-info' ) ); ?></p>
					<p><span class="dashicons-before dashicons-facebook"></span> <?php echo ddw_gnewi_get_info_link( 'genesiswp_fbgroup', __( '#GenesisWP Facebook Community Group', 'genesis-whats-new-info' ) ); ?></p>
					<p><span class="dashicons-before dashicons-format-chat"></span> <?php echo ddw_gnewi_get_info_link( 'genesiswp_slack', __( '#GenesisWP Community Slack Channel', 'genesis-whats-new-info' ) ); ?></p>
					<p><span class="dashicons-before dashicons-twitter"></span> <?php echo ddw_gnewi_get_info_link( 'genesiswp_tweets', __( '#GenesisWP Tweets', 'genesis-whats-new-info' ) ); ?></p>
				</td>
			</tr>

			<?php if ( defined( 'CHILD_THEME_NAME' ) ) : ?>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Active Child Theme', 'genesis-whats-new-info' ); ?></th>
					<td>
						<p><span class="dashicons-before dashicons-info"></span> <?php echo CHILD_THEME_NAME; ?><?php echo defined( 'CHILD_THEME_VERSION' ) ? ' / ' . __( 'Version', 'genesis-whats-new-info' ) . ': ' . CHILD_THEME_VERSION : ''; ?></p>
						<?php if ( 'dynamik-gen' === get_stylesheet() ) :
							echo sprintf(
								'<p><span class="dashicons-before dashicons-media-code"></span> <a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
								esc_url( get_stylesheet_directory_uri() . '/CHANGELOG.md' ),
								__( 'View Changelog (Markdown file, .md)', 'genesis-whats-new-info' )
							);
						endif; ?>
					</td>
				</tr>
			<?php endif; ?>

		</tbody>
		</table>
	<?php

	do_action( 'gnewi/action/ataglance_after' );

}  // end function
