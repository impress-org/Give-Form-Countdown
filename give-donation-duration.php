<?php
/**
 * Plugin Name: Give - Donation Duration
 * Plugin URI: https://github.com/WordImpress/Give-Donation-Duration
 * Description: Accept donations which limited by duration with Give.
 * Author: WordImpress
 * Author URI: https://wordimpress.com
 * Version: 1.0
 * Text Domain: give-donation-duration
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/WordImpress/Give-Donation-Duration
 */


/**
 * Class Give_Donation_Duration
 *
 * @since 1.0
 */
final class Give_Donation_Duration {
	/**
	 * @since  1.0
	 * @access static
	 * @var Give_Donation_Duration $instance
	 */
	static private $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since  1.0
	 * @access private
	 * Give_Donation_Duration constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get instance.
	 *
	 * @since  1.0
	 * @access static
	 * @return Give_Donation_Duration|static
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
	 * @return Give_Donation_Duration
	 */
	public function set_constants() {
		// Global Params.
		define( 'GDD_PLUGIN_VERSION', 1.0 );
		define( 'GDD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'GDD_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
		define( 'GDD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'GDD_MIN_GIVE_VER', 1.8 );

		return self::$instance;
	}

	/**
	 * Load files.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Donation_Duration
	 */
	public function load_files() {
		// Bootstrap.
		require_once GDD_PLUGIN_DIR . 'includes/admin/class-form-metabox-setting.php';

		// Load helper functions.
		require_once GDD_PLUGIN_DIR . 'includes/functions.php';

		// Load filters.
		require_once GDD_PLUGIN_DIR . 'includes/filters.php';

		return self::$instance;
	}
}

/**
 * Initiate plugin.
 *
 * @since 1.0
 */
function gdd_initiate() {
	// Load constants.
	Give_Donation_Duration::get_instance()->set_constants();

	// Process plugin activation conditions.
	require_once GDD_PLUGIN_DIR . 'includes/admin/plugin-activation.php';

	if ( ! class_exists( 'Give' ) ) {
		return;
	}

	// Initiate plugin.
	Give_Donation_Duration::get_instance()->load_files();
}

add_action( 'plugins_loaded', 'gdd_initiate' );
