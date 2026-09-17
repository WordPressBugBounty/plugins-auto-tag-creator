<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Fired when the plugin is uninstalled.
 * @link       http://ecreateinfotech.com/
 * @since      1.0.3
 *
 * @package    Ec_Auto_Tag_Creator
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	$ec_auto_tag_site_ids = get_sites( array( 'fields' => 'ids', 'number' => 0 ) );
	foreach ( $ec_auto_tag_site_ids as $ec_auto_tag_site_id ) {
		switch_to_blog( $ec_auto_tag_site_id );
		delete_option( 'ec_auto_tag_creator_setting' );
		restore_current_blog();
	}
} else {
	delete_option( 'ec_auto_tag_creator_setting' );
}
