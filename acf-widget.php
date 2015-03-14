<?php
/**
  Plugin Name: ACF Recent Posts Widget
  Plugin URI: http://wp-doin.com/portfolio/acfrpw/
  Description: Allow ACF and meta fields in the recent posts widget, giving control on the way recent posts are displayed.
  Author: Rafał Gicgier @wp-doin
  Version: 1.0
  Author URI: http://wp-doin.com
  License: GPLv2 or later
  Copyright: Rafał Gicgier
 */
DEFINE( 'ACF_RWP_CLASS_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
DEFINE( 'ACF_RWP_INC_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR );

class ACF_Recent_Posts_Widget {

	/**
	 * Instance variable to store plugin's object
	 * 
	 * @var self::object 
	 */
	public static $instance = null;

	/**
	 *  Private constructor to follow the Singleton pattern
	 */
	public function __construct() {

		// include the required function to check if plugin is active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// enqueue the plugin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );

		// require helper class
		require( ACF_RWP_CLASS_PATH . 'helpers.php');

		// require the widget files 
		require( ACF_RWP_CLASS_PATH . 'widget-base.php');
		require( ACF_RWP_CLASS_PATH . 'acf-widget-widget.php');
		require( ACF_RWP_CLASS_PATH . 'resizer.php');

		// register widget activation hook
		add_action( 'widgets_init', array( $this, 'register_the_widget' ) );

		// apply custom filtering functions to the before and after filter
		add_action( 'acp_rwp_before', array( ACF_Helper, 'af_bf_content_filter' ) );
		add_action( 'acp_rwp_after', array( ACF_Helper, 'af_bf_content_filter' ) );

		// verify if the ACF is active
		if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
			// ACF absent display admin notices
			add_action( 'admin_notices', array( $this, 'admin_notify_no_acf' ) );
		}

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	}

	/**
	 * @hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {

		if ( is_admin() ) {
			wp_register_script( 'acf-widget-admin', plugins_url( 'js/acf-widget-admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ) );
			wp_enqueue_script( 'acf-widget-admin' );

			wp_register_style( 'acf-widget-admin', plugins_url( 'css/acf-widget-admin.css', __FILE__ ) );
			wp_enqueue_style( 'acf-widget-admin' );

			wp_register_style( 'jquery-ui', plugins_url( 'css/jquery-ui.css', __FILE__ ) );
			wp_enqueue_style( 'jquery-ui' );
		}
	}

	/**
	 * @hook wp_enqueue_scripts
	 */
	public function front_enqueue_scripts() {
		wp_register_style( 'acf-rpw-main', plugins_url( 'css/acf-widget-front.css', __FILE__ ) );
	}

	/**
	 * Notify the admin on ACF dependency
	 * @hook admin_notices
	 */
	public function admin_notify_no_acf() {
		?>
		<div class="error acf-rpw-notice">
			<p><?php _e( '<strong>ACF Recent Posts Widget</strong>: You seem to have ACF disabled, some plugin functionalities are disabled.', 'acf_rpw' ); ?> <!--<span class="hide"><a href="#" alt="Click to hide.">&#10008;</a></span>--></p>
		</div>
		<?php
	}

	/**
	 * Register the widget
	 * @hook widgets_init
	 */
	public function register_the_widget() {
		register_widget( 'ACF_Rpw_Widget' );
	}

	/**
	 * @hook init
	 */
	public function load_plugin_textdomain() {
		$domain = 'acf_rpw';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
	}

}

// instantiate the plugin
$acf_rpw = new ACF_Recent_Posts_Widget();

