<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.3
 * @package    Ec_Auto_Tag_Creator
 * @subpackage Ec_Auto_Tag_Creator/includes
 * @author     Ecreate Infotech <contact@ecreateinfotech.com>
 */
 
class Ec_Auto_Tag_Creator {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      Ec_Auto_Tag_Creator_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.3
	 */
	public function __construct() {
		if ( defined( 'EC_AUTO_TAG_CREATOR_VERSION' ) ) {
			$this->version = EC_AUTO_TAG_CREATOR_VERSION;
		} else {
			$this->version = '1.1.0';
		}
		$this->plugin_name = 'ec-auto-tag-creator';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ec_Auto_Tag_Creator_Loader. Orchestrates the hooks of the plugin.
	 * - Ec_Auto_Tag_Creator_i18n. Defines internationalization functionality.
	 * - Ec_Auto_Tag_Creator_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ec-auto-tag-creator-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ec-auto-tag-creator-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ec-auto-tag-creator-admin.php';

		
		$this->loader = new Ec_Auto_Tag_Creator_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ec_Auto_Tag_Creator_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ec_Auto_Tag_Creator_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Ec_Auto_Tag_Creator_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_setting_menu_page' );
		$this->loader->add_action( 'wp_ajax_tag_save_settings', $plugin_admin, 'tag_save_settings_callback' );
		$this->loader->add_action( 'wp_ajax_ec_auto_tag_bulk_create', $plugin_admin, 'bulk_create_tags_callback' );

		// WordPress 5.6+ fires wp_after_insert_post after terms are saved, which the block editor needs.
		if ( function_exists( 'wp_after_insert_post' ) ) {
			$this->loader->add_action( 'wp_after_insert_post', $plugin_admin, 'after_insert_post', 10, 2 );
		} else {
			$this->loader->add_action( 'publish_post', $plugin_admin, 'create_tags' );
			$this->loader->add_action( 'publish_product', $plugin_admin, 'create_tags' );
		}

		// WooCommerce API, imports and other plugins save product terms after the post itself.
		$this->loader->add_action( 'woocommerce_new_product', $plugin_admin, 'after_save_product' );
		$this->loader->add_action( 'woocommerce_update_product', $plugin_admin, 'after_save_product' );

		$plugin_file = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . 'ec-auto-tag-creator.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_file, $plugin_admin, 'add_settings_link' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.3
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.3
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.3
	 * @return    Ec_Auto_Tag_Creator_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.3
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
