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
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.3
	 */
	public static function activate() {
		
		$save_setting = array();
		
		$save_setting['enable_post_title']  =  '1';
		$save_setting['enable_post_category']  =  '1';
		$save_setting['enable_product_title']  =  '0';
		$save_setting['enable_product_category']  =  '0';
		$save_setting['overwrite_tag']  =  '0';
		$save_setting['minimum_word']  =  '3';
		$save_setting['ignore_words']  =  '';
		
		update_option( 'ec_auto_tag_creator_setting', $save_setting );
		
	}

}
