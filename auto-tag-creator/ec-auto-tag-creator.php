<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @link              http://ecreateinfotech.com/
 * @since             1.0.3
 * @package           Ec_Auto_Tag_Creator
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Tag Creator
 * Plugin URI:        https://www.ecreateinfotech.com/services/web-development
 * Description:       Automatically creates tags from post and product titles and categories, on publish or update.
 * Version: 1.1.2
 * Author:            Ecreate Infotech
 * Author URI:        http://ecreateinfotech.com/
 * Requires at least: 4.7
 * Requires PHP:      5.6
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ec-auto-tag-creator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version. 
 */
define( 'EC_AUTO_TAG_CREATOR_VERSION', '1.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function ectc_activate_ec_auto_tag_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ec-auto-tag-creator-activator.php';
	Ec_Auto_Tag_Creator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function ectc_deactivate_ec_auto_tag_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ec-auto-tag-creator-deactivator.php';
	Ec_Auto_Tag_Creator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ectc_activate_ec_auto_tag_creator' );
register_deactivation_hook( __FILE__, 'ectc_deactivate_ec_auto_tag_creator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ec-auto-tag-creator-more-plugins.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ec-auto-tag-creator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.3
 */
function ec_run_auto_tag_creator() {

	$plugin = new Ec_Auto_Tag_Creator();
	$plugin->run();

}
ec_run_auto_tag_creator();
