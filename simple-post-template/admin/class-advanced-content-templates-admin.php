<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/admin
 * @author     Clifton Griffin <clif@cgd.io>
 */
class Simple_Content_Templates_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Main instance of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->plugin      = $plugin;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Content_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Content_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name , plugin_dir_url( __FILE__ ) . 'css/advanced-content-templates-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Content_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Content_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name , plugin_dir_url( __FILE__ ) . 'js/advanced-content-templates-admin.js', array( 'jquery' ), $this->version, false );
	}


	/**
	 * Register the admin menus.
	 *
	 * @access public
	 * @return void
	 */
	function admin_menus() {
		global $wp_tabbed_navigation;

		add_submenu_page( 'edit.php?post_type=' . sanitize_key( $this->plugin->post_type ), 'Simple Content Templates Settings', 'Settings', 'manage_options', 'sct-settings', array( $this, 'admin_settings_page' ) );
		add_submenu_page( 'edit.php?post_type=' . sanitize_key( $this->plugin->post_type ), 'Simple Content Templates Upgrade', 'Upgrade', 'manage_options', 'sct-upgrade', array( $this, 'admin_upgrade_page' ) );

		$wp_tabbed_navigation = new WP_Tabbed_Navigation( 'Simple Content Templates Settings' );

		$wp_tabbed_navigation->add_tab( 'Settings', menu_page_url( 'sct-settings', false ) );
		$wp_tabbed_navigation->add_tab( 'Upgrade', menu_page_url( 'sct-upgrade', false ) );
	}

	/**
	 * admin_settings_page function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_settings_page() {
		include_once 'partials/advanced-content-templates-admin-display.php';
	}

	/**
	 * admin_upgrade_page function
	 *
	 * @return void
	 */
	function admin_upgrade_page() {
		include_once 'partials/advanced-content-templates-admin-upgrade.php';
	}

	/**
	 * Add template loader metaboxes to editor of correct post types
	 *
	 * @access public
	 * @return void
	 */
	function boxes() {
		$settings = $this->plugin->get_setting( 'act_post_type_settings' );

		if ( empty( $settings ) || ! is_array( $settings ) ) {
return;
		}

		foreach ( $settings as $post_type => $s ) {
			if ( isset( $s['show_ui'] ) && $s['show_ui'] == 'true' ) {
				add_meta_box( 'act_side_car_' . sanitize_key( $post_type ), 'Simple Content Templates', array( $this, 'render_side_car' ), sanitize_key( $post_type ), 'side', 'high' );
			}
		}
	}

	/**
	 * render_side_car function.
	 *
	 * @access public
	 * @return void
	 */
	function render_side_car() {
		include_once 'partials/advanced-content-templates-admin-sidecar.php';
	}

	/**
	 * Register Page Template Metabox.
	 *
	 * @access public
	 * @return void
	 */
	function page_template_box() {
		global $post;

		if ( $post->post_type == $this->plugin->post_type ) {
			add_meta_box( 'act_template_side_car', 'Template', array( $this, 'render_template_side_car' ), sanitize_key( $this->plugin->post_type ), 'side', 'high' );
		}
	}

	/**
	 * render_template_side_car function.
	 *
	 * @access public
	 * @return void
	 */
	function render_template_side_car() {
		global $post;

		if ( 0 != count( get_page_templates() ) ) :
			$template = get_post_meta( $post->ID, '_wp_page_template', true );
			if ( empty( $template ) ) {
$template = false;
			}
			?>

			<select name="page_template" id="page_template">
				<option value="default"><?php esc_html_e( 'Default Template', 'simple-post-template' ); ?></option>
				<?php page_template_dropdown( $template ); ?>
			</select>

		<?php endif; ?>
		<?php
	}


	/**
	 * Save page template setting on ACT template save.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	function save_template( $post_id ) {
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		$template = get_post( $parent_id );

		if ( ! is_object( $template ) ) {
return;
		}

		// Check if we should process page template (use POST consistently)
		if ( $template->post_type != $this->plugin->post_type || ! isset( $_POST['page_template'] ) ) {
			return;
		}

		// Verify nonce before saving page template
		if ( ! isset( $_POST['act_page_template_nonce'] ) ||
		     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['act_page_template_nonce'] ) ), 'act_save_page_template' ) ) {
			return; // Silently fail to maintain compatibility
		}

		// Sanitize and process page template
		$page_template = sanitize_text_field( wp_unslash( $_POST['page_template'] ) );

		if ( $page_template !== 'default' ) {
			update_post_meta( $parent_id, '_wp_page_template', $page_template );
		} else {
			delete_post_meta( $parent_id, '_wp_page_template' );
		}
	}

	/**
	 * Add nonce field for page template saving
	 *
	 * @access public
	 * @return void
	 */
	public function add_template_nonce_field() {
		global $post;
		if ( $post && $post->post_type == $this->plugin->post_type ) {
			wp_nonce_field( 'act_save_page_template', 'act_page_template_nonce' );
		}
	}


	/**
	 * On first activate, redirect to settings.
	 *
	 * @access public
	 * @return void
	 */
	function redirect_on_first_activate() {
		if ( $this->plugin->get_setting( 'act_first_activate', false ) ) {
			$this->plugin->delete_setting( 'act_first_activate' );
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . sanitize_key( $this->plugin->post_type ) . '&page=sct-settings' ) );
			exit;
		}
	}


	/**
	 * Load a template
	 *
	 * @access public
	 * @param mixed $excerpt
	 * @param mixed $post
	 * @return void
	 */
	function template_load( $excerpt, $post ) {
		global $editing;
		if ( ! current_user_can( 'edit_posts' ) || $editing !== true ) {
return $excerpt;
		}

		// Verify nonce for manual template loading
		if ( isset( $_REQUEST['act_template_load'] ) &&
		    ( ! isset( $_REQUEST['act_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['act_nonce'] ) ), 'act_load_template' ) ) ) {
			wp_die( 'Security check failed. Please try again.', 'Security Error', array( 'response' => 403 ) );
		}

		$post_type = $post->post_type;
		$settings  = $this->plugin->get_setting( 'act_post_type_settings' );

		// If there are no configured post types, bail
		if ( empty( $settings ) ) {
return;
		}

		// Load the template
		$template = false;
		if ( ! empty( $settings[ $post_type ]['auto_load'] ) && is_numeric( $settings[ $post_type ]['auto_load'] ) ) {
			$template = get_post( $settings[ $post_type ]['auto_load'] );
		} elseif ( isset( $_REQUEST['act_template_load'] ) && is_numeric( $_REQUEST['act_template_load'] ) ) {
			$template = get_post( absint( wp_unslash( $_REQUEST['act_template_load'] ) ) );
		}

		// Only proceed if we have a template
		if ( $template !== false ) {
			ob_start();

			// Get and sanitize post_id
			$post_id = isset( $_REQUEST['post_id'] ) ? absint( wp_unslash( $_REQUEST['post_id'] ) ) : $post->ID;

			// Verify edit permissions if using custom post_id
			if ( isset( $_REQUEST['post_id'] ) && ! current_user_can( 'edit_post', $post_id ) ) {
				wp_die( 'You do not have permission to edit this post.', 'Permission Error', array( 'response' => 403 ) );
			}

			if ( $post_id > 0 ) {
				$target_post = get_post( $post_id );
			}

			$properties          = get_object_vars( $template );
			$excluded_properties = array(
				'ID',
				'post_author',
				'post_modified',
				'post_modified_gmt',
				'post_name',
				'guid',
				'post_status',
				'post_date',
				'post_date_gmt',
				'post_type',
				'post_status',
			);

			$temp_post                = array();
			$temp_post['ID']          = $post_id;
			$temp_post['post_status'] = 'draft';
			$empty_only_properties    = apply_filters( 'act_protected_properties', array() );

			foreach ( $properties as $property => $value ) {
				// Skip excluded properties
				if ( in_array( $property, $excluded_properties ) ) {
					continue;
				}

				// Skip if property should be empty-only and already has value
				if ( ( in_array( $property, $empty_only_properties ) && ! empty( $target_post ) && ! empty( $target_post->$property ) ) && $target_post->$property != 'Auto Draft' ) {
					continue;
				}

				/**
				 * act_template_property
				 *
				 * Filter template property before it is loaded.
				 *
				 * @since 2.0.0
				 *
				 * @param string $value The template property value
				 * @param string $property The template property being filtered
				 */
				$value = apply_filters( 'act_template_property', $value, $property );

				$temp_post[ $property ] = iconv( mb_detect_encoding( $value ), 'UTF-8//IGNORE', $value );
			}

			$temp_post = apply_filters( 'act_load_template', $temp_post );

			wp_update_post( $temp_post );

			ob_end_clean();

			do_action_ref_array( 'act_template_loaded', $post_id );

			wp_safe_redirect( get_edit_post_link( $post_id, '' ) );
			exit();
		}

		return $excerpt;
	}

	function get_templates() {
		return get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => $this->plugin->post_type,
				'orderby'        => 'post_title',
				'order'          => 'ASC',
				'post_status'    => array(
					'publish',
					'pending',
					'future',
					'private',
				),
			)
		);
	}
}
