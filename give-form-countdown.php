<?php
/**
 * Plugin Name: Give - Form Countdown
 * Plugin URI: https://github.com/WordImpress/Give-Form-Countdown
 * Description: Accept donations which limited by duration with Give.
 * Author: WordImpress
 * Author URI: https://wordimpress.com
 * Version: 1.0
 * Text Domain: give-form-countdown
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/WordImpress/Give-Form-Countdown
 */


/**
 * Class Give_Form_Countdown
 *
 * @since 1.0
 */
final class Give_Form_Countdown {
	/**
	 * @since  1.0
	 * @access static
	 * @var Give_Form_Countdown $instance
	 */
	static private $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since  1.0
	 * @access private
	 * Give_Form_Countdown constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get instance.
	 *
	 * @since  1.0
	 * @access static
	 * @return Give_Form_Countdown|static
	 */
	static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Setup constants.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Form_Countdown
	 */
	public function set_constants() {
		// Global Params.
		define( 'GFC_PLUGIN_VERSION', 1.0 );
		define( 'GFC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'GFC_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
		define( 'GFC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'GFC_MIN_GIVE_VER', 1.8 );

		return self::$instance;
	}

	/**
	 * Load files.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Form_Countdown
	 */
	public function load_files() {
		// Bootstrap.
		require_once GFC_PLUGIN_DIR . 'includes/admin/class-form-metabox-setting.php';

		// Load helper functions.
		require_once GFC_PLUGIN_DIR . 'includes/functions.php';

		// Load filters.
		require_once GFC_PLUGIN_DIR . 'includes/filters.php';

		// Load actions.
		require_once GFC_PLUGIN_DIR . 'includes/actions.php';

		return self::$instance;
	}
}

/**
 * Initiate plugin.
 *
 * @since 1.0
 */
function gfc_initiate() {
	// Load constants.
	Give_Form_Countdown::get_instance()->set_constants();

	// Process plugin activation conditions.
	require_once GFC_PLUGIN_DIR . 'includes/admin/plugin-activation.php';

	if ( ! class_exists( 'Give' ) ) {
		return;
	}

	// Initiate plugin.
	Give_Form_Countdown::get_instance()->load_files();
}

add_action( 'plugins_loaded', 'gfc_initiate' );
