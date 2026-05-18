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
