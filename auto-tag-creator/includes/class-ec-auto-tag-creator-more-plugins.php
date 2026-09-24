<?php
/**
 * "More free plugins by Ecreate" panel.
 *
 * Asks WordPress.org for the author's plugins once every 12 hours and caches
 * the answer, so the list stays current without a plugin update. Falls back to
 * a plain link when the request fails.
 *
 * @package Ec_Auto_Tag_Creator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shows the author's other plugins on the plugin's own admin page.
 */
class Ec_Auto_Tag_Creator_More_Plugins {

	/**
	 * WordPress.org author account.
	 */
	const AUTHOR = 'ecreateinfotech';

	/**
	 * Transient holding the cached list.
	 */
	const CACHE = 'ec_auto_tag_creator_more_plugins';

	/**
	 * How long the list is cached.
	 */
	const CACHE_TIME = 12 * HOUR_IN_SECONDS;

	/**
	 * The author's other plugins, without this one and without any that are
	 * already installed.
	 *
	 * @param string $exclude_slug Slug of the current plugin.
	 * @return array List of arrays with slug, name and description. Empty when unavailable.
	 */
	public static function get( $exclude_slug ) {

		$plugins = get_transient( self::CACHE );

		if ( false === $plugins ) {

			$plugins = array();

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			$response = plugins_api(
				'query_plugins',
				array(
					'author'   => self::AUTHOR,
					'per_page' => 12,
					'fields'   => array(
						'short_description' => true,
						'icons'             => false,
						'sections'          => false,
						'ratings'           => false,
						'banners'           => false,
					),
				)
			);

			if ( ! is_wp_error( $response ) && ! empty( $response->plugins ) ) {
				foreach ( $response->plugins as $plugin ) {
					$plugin = (array) $plugin;
					if ( empty( $plugin['slug'] ) ) {
						continue;
					}
					$plugins[] = array(
						'slug'        => $plugin['slug'],
						'name'        => isset( $plugin['name'] ) ? wp_strip_all_tags( html_entity_decode( $plugin['name'], ENT_QUOTES, 'UTF-8' ) ) : $plugin['slug'],
						'description' => isset( $plugin['short_description'] ) ? wp_strip_all_tags( html_entity_decode( $plugin['short_description'], ENT_QUOTES, 'UTF-8' ) ) : '',
					);
				}
			}

			// Cached even when empty, so a failed request is not retried on every page load.
			set_transient( self::CACHE, $plugins, $plugins ? self::CACHE_TIME : HOUR_IN_SECONDS );
		}

		$list = array();

		foreach ( (array) $plugins as $plugin ) {
			if ( $plugin['slug'] === $exclude_slug ) {
				continue;
			}
			// Skip plugins the site already has.
			if ( is_dir( WP_PLUGIN_DIR . '/' . $plugin['slug'] ) ) {
				continue;
			}
			$list[] = $plugin;
		}

		return $list;
	}

	/**
	 * Link to the author's plugins inside this site's admin, so a plugin can
	 * be installed without leaving WordPress.
	 *
	 * @return string
	 */
	public static function all_plugins_url() {
		return self_admin_url( 'plugin-install.php?tab=search&type=author&s=' . self::AUTHOR );
	}

	/**
	 * Link to one plugin's details inside this site's admin.
	 *
	 * @param string $slug Plugin slug.
	 * @return string
	 */
	public static function plugin_url( $slug ) {
		return self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . rawurlencode( $slug ) );
	}
}
