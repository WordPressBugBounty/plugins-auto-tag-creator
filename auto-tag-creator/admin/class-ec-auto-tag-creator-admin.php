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
	 * Number of posts processed per bulk request.
	 *
	 * @since    1.1.0
	 */
	const BULK_BATCH_SIZE = 20;

	/**
	 * Post meta key used to mark a post as already auto-tagged, so
	 * later saves don't regenerate (and re-add) tags the user removed.
	 *
	 * @since    1.1.2
	 */
	const PROCESSED_META_KEY = '_ec_auto_tag_creator_processed';

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
	 * Default plugin settings.
	 *
	 * @since    1.1.0
	 */
	public static function get_default_settings() {

		return array(
			'enable_post_title'       => '1',
			'enable_post_category'    => '1',
			'enable_product_title'    => '0',
			'enable_product_category' => '0',
			'overwrite_tag'           => '0',
			'minimum_word'            => '3',
			'ignore_words'            => '',
			'use_stop_words'          => '1',
			'tag_case'                => 'original',
			'max_tags'                => '0',
		);

	}

	/**
	 * Saved settings merged with defaults, so settings added in newer
	 * versions always have a value.
	 *
	 * @since    1.1.0
	 */
	public static function get_settings() {

		$settings = get_option( 'ec_auto_tag_creator_setting', array() );
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return wp_parse_args( $settings, self::get_default_settings() );

	}

	/**
	 * Built-in list of common English words that are never turned into tags.
	 *
	 * @since    1.1.0
	 */
	public static function get_stop_words() {

		$stop_words = array(
			'a', 'about', 'above', 'after', 'again', 'against', 'all', 'am', 'an', 'and', 'any', 'are', 'as', 'at',
			'be', 'because', 'been', 'before', 'being', 'below', 'best', 'between', 'both', 'but', 'by',
			'can', 'could', 'did', 'do', 'does', 'doing', 'down', 'during',
			'each', 'every', 'few', 'for', 'from', 'further', 'get', 'got',
			'had', 'has', 'have', 'having', 'he', 'her', 'here', 'hers', 'herself', 'him', 'himself', 'his', 'how',
			'i', 'if', 'in', 'into', 'is', 'it', 'its', 'itself', 'just',
			'me', 'more', 'most', 'my', 'myself', 'new', 'no', 'nor', 'not', 'now',
			'of', 'off', 'on', 'once', 'only', 'or', 'other', 'our', 'ours', 'ourselves', 'out', 'over', 'own',
			'same', 'she', 'should', 'so', 'some', 'such',
			'than', 'that', 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'there', 'these', 'they',
			'this', 'those', 'through', 'to', 'too', 'under', 'until', 'up', 'us',
			'uncategorized', 'very', 'vs', 'was', 'we', 'were', 'what', 'when', 'where', 'which', 'while', 'who', 'whom', 'why',
			'will', 'with', 'would', 'you', 'your', 'yours', 'yourself', 'yourselves',
		);

		/**
		 * Filters the built-in stop word list.
		 *
		 * @since 1.1.0
		 * @param array $stop_words Lowercase words that are never turned into tags.
		 */
		return apply_filters( 'ec_auto_tag_creator_stop_words', $stop_words );

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
				'security'   => wp_create_nonce( 'ec_auto_tag_creator_nonce' ),
				/* translators: 1: items processed so far, 2: total items. */
				'processing' => __( 'Processing %1$s of %2$s...', 'ec-auto-tag-creator' ),
				'done_single' => __( 'Done. 1 item processed.', 'ec-auto-tag-creator' ),
				/* translators: %s: number of items processed. */
				'done'       => __( 'Done. %s items processed.', 'ec-auto-tag-creator' ),
				'error'      => __( 'Something went wrong. Please try again.', 'ec-auto-tag-creator' ),
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
	 * Add a Settings link to the plugin row on the Plugins page.
	 *
	 * @since    1.1.0
	 */
	public function add_settings_link( $links ) {

		if ( current_user_can( 'manage_options' ) ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=ec-auto-tag-setting' ) ) . '">' . esc_html__( 'Settings', 'ec-auto-tag-creator' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;

	}

	/**
	 * Create tags once a post and its terms have been saved (WordPress 5.6+).
	 *
	 * @since    1.1.0
	 */
	public function after_insert_post( $post_id, $post ) {

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( $post->post_status !== 'publish' || ! in_array( $post->post_type, array( 'post', 'product' ), true ) ) {
			return;
		}

		$this->create_tags( $post_id );

	}

	/**
	 * Create tags once WooCommerce has saved a product and its terms.
	 *
	 * @since    1.1.0
	 */
	public function after_save_product( $product_id ) {

		if ( get_post_status( $product_id ) !== 'publish' ) {
			return;
		}

		$this->create_tags( $product_id );

	}

	/**
	 * Setting page form template.
	 *
	 * @since    1.0.3
	 */
	public function auto_tag_settings_callback() {

		$settings = self::get_settings();
		$bulk_post_types = $this->get_bulk_post_types();
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

		$save_setting['use_stop_words'] = isset( $_POST['use_stop_words'] ) ? '1' : '0';

		$tag_case = isset( $_POST['tag_case'] ) ? sanitize_key( wp_unslash( $_POST['tag_case'] ) ) : 'original';
		$save_setting['tag_case'] = in_array( $tag_case, array( 'lower', 'title', 'original' ), true ) ? $tag_case : 'original';

		$save_setting['max_tags'] = isset( $_POST['max_tags'] )
			? absint( wp_unslash( $_POST['max_tags'] ) )
			: 0;

		update_option( 'ec_auto_tag_creator_setting', $save_setting );

		$response = array(
            'success' => true,
            'msg' => __('Settings has been saved successfully.','ec-auto-tag-creator')
        );
        wp_send_json($response);
		exit;

	}

	/**
	 * Post types that can be bulk tagged, keyed by post type.
	 *
	 * @since    1.1.0
	 */
	public function get_bulk_post_types() {

		$post_types = array(
			'post' => __( 'Posts', 'ec-auto-tag-creator' ),
		);

		if ( post_type_exists( 'product' ) && taxonomy_exists( 'product_tag' ) ) {
			$post_types['product'] = __( 'Products', 'ec-auto-tag-creator' );
		}

		return $post_types;

	}

	/**
	 * Tag existing published posts in batches.
	 *
	 * @since    1.1.0
	 */
	public function bulk_create_tags_callback() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Unauthorized access.', 'ec-auto-tag-creator' ),
				),
				403
			);
		}

		check_ajax_referer( 'ec_auto_tag_creator_nonce', 'security' );

		$post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : '';
		$offset    = isset( $_POST['offset'] ) ? absint( wp_unslash( $_POST['offset'] ) ) : 0;

		if ( ! array_key_exists( $post_type, $this->get_bulk_post_types() ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Invalid post type.', 'ec-auto-tag-creator' ),
				),
				400
			);
		}

		$counts = wp_count_posts( $post_type );
		$total  = isset( $counts->publish ) ? (int) $counts->publish : 0;

		$post_ids = get_posts(
			array(
				'post_type'        => $post_type,
				'post_status'      => 'publish',
				'posts_per_page'   => self::BULK_BATCH_SIZE,
				'offset'           => $offset,
				'orderby'          => 'ID',
				'order'            => 'ASC',
				'fields'           => 'ids',
				'suppress_filters' => true,
			)
		);

		wp_defer_term_counting( true );
		foreach ( $post_ids as $post_id ) {
			$this->create_tags( $post_id, true );
		}
		wp_defer_term_counting( false );

		$processed = min( $offset + count( $post_ids ), $total );

		wp_send_json_success(
			array(
				'total'     => $total,
				'processed' => $processed,
				'done'      => count( $post_ids ) < self::BULK_BATCH_SIZE || $processed >= $total,
			)
		);

	}

	/**
	 * Create tags when publishing a post.
	 *
	 * Runs only once per post (on its first publish) unless $force is
	 * true, so manually removed tags don't get regenerated and re-added
	 * on every later save/update.
	 *
	 * @since    1.0.3
	 * @param    int     $post_id    The post to tag.
	 * @param    bool    $force      Regenerate even if this post was already processed.
	 */
	public function create_tags( $post_id, $force = false ) {

		if ( ! $force && get_post_meta( $post_id, self::PROCESSED_META_KEY, true ) ) {
			return;
		}

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

		$settings = self::get_settings();

		$ignore_list = array();
		if( trim($settings['ignore_words'])!='' ) {
			$ignore_list = array_map(
				function( $word ) {
					return $this->to_lower( $this->clean_word( $word ) );
				},
				array_filter( explode( ',', $settings['ignore_words'] ) )
			);
		}

		if( $settings['use_stop_words']=='1' ) {
			$ignore_list = array_merge( $ignore_list, self::get_stop_words() );
		}

		$len = 1;
		if( trim($settings['minimum_word'])!='' && $settings['minimum_word']!='0') {
			$len = (int) trim($settings['minimum_word']);
		}

		$title_tags = array();
		$cat_tags = array();

		if( $settings[$setting_title_key]=='1' ) {

			$title = html_entity_decode( trim( get_the_title( $post_id ) ), ENT_QUOTES, 'UTF-8' );

			$title_tags = $this->prepare_title_to_tag( $title, $ignore_list, $len );

		}

		if( $settings[$setting_cat_key]=='1' ) {

			$categories = wp_get_object_terms( $post_id, $taxonomy_name );

			if ( ! is_wp_error( $categories ) ) {
				$cat_tags = $this->parepare_category_to_tag( $categories, $ignore_list, $len );
			}

		}

		$new_tags = $this->format_tags( array_merge( $title_tags, $cat_tags ), $settings['tag_case'], (int) $settings['max_tags'] );

		$this->set_tag( $post_id, $new_tags, array(), $term_taxonomy_name , $settings['overwrite_tag'] );

		update_post_meta( $post_id, self::PROCESSED_META_KEY, '1' );

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

			$words = explode( ' ', html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8' ) );

			$tags = $this->words_to_tag( $words, $ignore_list, $len );

			$cat_tag = array_merge($cat_tag,$tags);

		}

		return $cat_tag;

	}

	/**
	 * Create tags from words. Words keep their original case here,
	 * case is applied later in format_tags().
	 *
	 * @since    1.0.3
	 */
	public function words_to_tag( $words, $ignore_list, $len ) {

		$word_tag = array();
		foreach ( $words as $word ) {
			$word = $this->clean_word( $word );
			if( $word !== '' && $this->str_length( $word ) >= $len && !in_array( $this->to_lower( $word ), $ignore_list, true ) ) {
				$word_tag[] = $word;
			}
		}

		return $word_tag;

	}

	/**
	 * Remove duplicates (case-insensitive), apply the tag case setting
	 * and limit the number of generated tags.
	 *
	 * @since    1.1.0
	 */
	public function format_tags( $tags, $tag_case, $max_tags ) {

		$formatted = array();
		foreach ( $tags as $tag ) {

			$key = $this->to_lower( $tag );
			if ( isset( $formatted[ $key ] ) ) {
				continue;
			}

			if ( $tag_case === 'title' ) {
				$tag = $this->to_title( $tag );
			} elseif ( $tag_case === 'lower' ) {
				$tag = $key;
			}

			$formatted[ $key ] = $tag;

			if ( $max_tags > 0 && count( $formatted ) >= $max_tags ) {
				break;
			}
		}

		return array_values( $formatted );

	}

	/**
	 * Strip everything except letters and numbers.
	 *
	 * @since    1.1.0
	 */
	private function clean_word( $word ) {
		return preg_replace( '/[^\p{L}\p{N}]+/u', '', trim( $word ) );
	}

	/**
	 * Multibyte-safe lowercase.
	 *
	 * @since    1.1.0
	 */
	private function to_lower( $word ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $word, 'UTF-8' ) : strtolower( $word );
	}

	/**
	 * Multibyte-safe title case.
	 *
	 * @since    1.1.0
	 */
	private function to_title( $word ) {
		return function_exists( 'mb_convert_case' ) ? mb_convert_case( $word, MB_CASE_TITLE, 'UTF-8' ) : ucfirst( strtolower( $word ) );
	}

	/**
	 * Word length in characters rather than bytes.
	 *
	 * @since    1.1.0
	 */
	private function str_length( $word ) {
		return function_exists( 'mb_strlen' ) ? mb_strlen( $word, 'UTF-8' ) : strlen( $word );
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
				if ( ! is_wp_error( $existing_tags ) ) {
					foreach( $existing_tags as $tag ) {
						$ex_tag[] = $tag->name;
					}
				}
				$new_tags = array_merge( $new_tags, $ex_tag );

			}

			$new_tags = array_unique ( $new_tags );
			wp_set_object_terms( $post_id, $new_tags, $term );

		}

	}

}
