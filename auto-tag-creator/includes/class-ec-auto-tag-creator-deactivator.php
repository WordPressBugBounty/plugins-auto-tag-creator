<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.3
 * @package    Ec_Auto_Tag_Creator
 * @subpackage Ec_Auto_Tag_Creator/includes
 * @author     Ecreate Infotech <contact@ecreateinfotech.com>
 */
 
class Ec_Auto_Tag_Creator_Deactivator {

	/**
	 * Nothing to clean up on deactivation.
	 *
	 * @since    1.0.3
	 */
	public static function deactivate() {
		// Settings are kept on deactivation and removed in uninstall.php.
	}

}
