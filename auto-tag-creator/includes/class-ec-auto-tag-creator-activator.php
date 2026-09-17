<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.3
 * @package    Ec_Auto_Tag_Creator
 * @subpackage Ec_Auto_Tag_Creator/includes
 * @author     Ecreate Infotech <contact@ecreateinfotech.com>
 */
 
class Ec_Auto_Tag_Creator_Activator {

	/**
	 * Save default settings on first activation.
	 *
	 * @since    1.0.3
	 */
	public static function activate() {
		
		// Keep existing settings when the plugin is reactivated.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ec-auto-tag-creator-admin.php';

		add_option( 'ec_auto_tag_creator_setting', Ec_Auto_Tag_Creator_Admin::get_default_settings() );
		
	}

}
