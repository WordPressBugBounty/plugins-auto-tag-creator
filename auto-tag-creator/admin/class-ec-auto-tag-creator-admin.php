<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ec_Auto_Tag_Creator
 * @subpackage Ec_Auto_Tag_Creator/admin
 * @author     Ecreate Infotech <contact@ecreateinfotech.com>
 */
 
class Ec_Auto_Tag_Creator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.3
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ec-auto-tag-creator-admin.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ec-auto-tag-creator-admin.js', array( 'jquery' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name,
			'ec_auto_tag_creator',
			array(
				'security' => wp_create_nonce( 'ec_auto_tag_creator_nonce' ),
			)
		);
		
	}
	
	/**
	 * Setting menu page for plugin.
	 *
	 * @since    1.0.3
	 */
	public function add_setting_menu_page() {
		add_menu_page(__('Tag Setting', 'ec-auto-tag-creator'),__('Tag Setting', 'ec-auto-tag-creator'),'manage_options','ec-auto-tag-setting',array($this, 'auto_tag_settings_callback'),'dashicons-tag',99);
	}

	/**
	 * Setting page form template.
	 *
	 * @since    1.0.3
	 */
	public function auto_tag_settings_callback() {
		
		$settings = get_option( 'ec_auto_tag_creator_setting' );
		$tempath = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/settings.php';
		require_once($tempath);
		
	}

	/**
	 * Save setting.
	 *
	 * @since    1.0.3
	 */
	public function tag_save_settings_callback() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Unauthorized access.', 'ec-auto-tag-creator' ),
				),
				403
			);
		}

		check_ajax_referer( 'ec_auto_tag_creator_nonce', 'security' );
		
		$save_setting = array();

		$save_setting['enable_post_title'] = isset( $_POST['enable_post_title'] ) ? '1' : '0';

		$save_setting['enable_post_category'] = isset( $_POST['enable_post_category'] ) ? '1' : '0';

		$save_setting['enable_product_title'] = isset( $_POST['enable_product_title'] ) ? '1' : '0';

		$save_setting['enable_product_category'] = isset( $_POST['enable_product_category'] ) ? '1' : '0';

		$save_setting['overwrite_tag'] = isset( $_POST['overwrite_tag'] ) ? '1' : '0';

		$save_setting['minimum_word'] = isset( $_POST['minimum_word'] )
			? absint( wp_unslash( $_POST['minimum_word'] ) )
			: 0;

		$save_setting['ignore_words'] = isset( $_POST['ignore_words'] )
			? sanitize_textarea_field(
				wp_unslash( $_POST['ignore_words'] )
			)
			: '';

		update_option( 'ec_auto_tag_creator_setting', $save_setting );
		
		$response = array(
            'success' => true,
            'msg' => __('Settings has been saved successfully.','ec-auto-tag-creator')
        );
        wp_send_json($response);
		exit;
		
	}
	
	/**
	 * Create tags when publishing or updating post.
	 *
	 * @since    1.0.3
	 */
	public function create_tags( $post_id ) {
		
		$post_type = get_post_type($post_id);
		
		$setting_title_key = 'enable_post_title';
		$setting_cat_key = 'enable_post_category';
		$taxonomy_name = 'category';
		$term_taxonomy_name = 'post_tag';
		
		if( $post_type == 'product' ) {
			
			$setting_title_key = 'enable_product_title';
			$setting_cat_key = 'enable_product_category';
			$taxonomy_name = 'product_cat';
			$term_taxonomy_name = 'product_tag';
			
		}
		
		$settings = get_option( 'ec_auto_tag_creator_setting' );
		
		$ignore_list = array();
		if( trim($settings['ignore_words'])!='' ) {
			$ignore_words = strtolower(trim($settings['ignore_words']));
			$ignore_list = array_map(
				function( $word ) {
					$word = strtolower( trim( $word ) );
					return preg_replace( '/[^\p{L}\p{N}]+/u', '', $word );
				},
				array_filter( explode( ',', $ignore_words ) )
			);
		}
		
		$len = 1;
		if( trim($settings['minimum_word'])!='' && $settings['minimum_word']!='0') {
			$len = trim($settings['minimum_word']);
		}
		
		$title_tags = array();
		$cat_tags = array();
		
		if( $settings[$setting_title_key]=='1' ) {
			
			$title = htmlspecialchars_decode(strtolower(trim( get_the_title( $post_id ) )));
			
			$title_tags = $this->prepare_title_to_tag( $title, $ignore_list, $len );
			
		}
		
		if( $settings[$setting_cat_key]=='1' ) {
			
			$categories = wp_get_object_terms( $post_id, $taxonomy_name );
			
			$cat_tags = $this->parepare_category_to_tag( $categories, $ignore_list, $len );
			
		}
		
		$this->set_tag( $post_id, $title_tags, $cat_tags, $term_taxonomy_name , $settings['overwrite_tag'] );
		
	}
	
	/**
	 * Create tags from title.
	 *
	 * @since    1.0.3
	 */
	public function prepare_title_to_tag( $title, $ignore_list, $len ) {
		
		$tags = array();
		
		if( $title != '' ) {
			
			$words = explode( ' ', $title );
			
			$tags = $this->words_to_tag( $words, $ignore_list, $len );
			
		}
		
		return $tags;
		
	}
	
	/**
	 * Create tags from category.
	 *
	 * @since    1.0.3
	 */
	public function parepare_category_to_tag( $categories, $ignore_list, $len ) {
		
		$tags = array();
		$cat_tag = array();
		
		foreach ( $categories as $category ) {
			
			$words = explode( ' ', strtolower( $category->name ));
			
			$tags = $this->words_to_tag( $words, $ignore_list, $len );
			
			$cat_tag = array_merge($cat_tag,$tags);
			
		}
		
		return $cat_tag;
		
	}
	
	/**
	 * Create tags from words.
	 *
	 * @since    1.0.3
	 */
	public function words_to_tag( $words, $ignore_list, $len ) {
		
		$word_tag = array();
		foreach ( $words as $word ) {
			$word = strtolower( trim( $word ) );
			$word = preg_replace( '/[^\p{L}\p{N}]+/u', '', $word );
			if( strlen($word) >= $len && !in_array( $word, $ignore_list, true ) ) {
				$word_tag[] = $word;						
			}
		}
		
		return $word_tag;
		
	}
	
	/**
	 * Set created tag by id.
	 *
	 * @since    1.0.3
	 */
	public function set_tag( $post_id, $title_tags, $cat_tags, $term, $is_overwrite_tag ) {
		
		$new_tags = array();
		$new_tags = array_merge( $title_tags,$cat_tags );
				
		if( !empty( $new_tags )) {
			
			if( $is_overwrite_tag=='1' ) {
				
				wp_set_object_terms ( $post_id, array(), $term );
				
			} else {
				
				$existing_tags = wp_get_object_terms( $post_id, $term );
				
				$ex_tag = array();
				foreach( $existing_tags as $tag ) {
					$ex_tag[] = $tag->name;
				}
				$new_tags = array_merge( $new_tags, $ex_tag );
				
			}
			
			$new_tags = array_unique ( $new_tags );
			shuffle($new_tags);
			wp_set_object_terms( $post_id, $new_tags, $term );
			
		}
			
	}

}