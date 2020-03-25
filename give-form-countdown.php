<?php
/**
 * Plugin Name: Form Countdown for GiveWP
 * Plugin URI: https://github.com/impress-org/give-form-countdown
 * Description: Accept donations which limited by duration with Give.
 * Author: WordImpress
 * Author URI: https://wordimpress.com
 * Version: 1.0.1
 * Text Domain: give-form-countdown
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/WordImpress/Give-Form-Countdown
 */


if ( ! class_exists( 'Give_Form_Countdown' ) ) {
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
		 * Notices (array)
		 *
		 * @since 1.0.2
		 *
		 * @var array
		 */
		public $notices = array();

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
				self::$instance = new self();
				self::$instance->setup();
			}

			return self::$instance;
		}

		/**
		 * Setup Give Form Countdown.
		 *
		 * @since 1.0.2
		 * @access private
		 */
		private function setup() {

			// Setup constants.
			$this->set_constants();

			// Give init hook.
			add_action( 'give_init', array( $this, 'init' ), 10 );
			add_action( 'admin_init', array( $this, 'check_environment' ), 999 );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
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
			define( 'GFC_PLUGIN_VERSION', '1.0.1' );
			define( 'GFC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'GFC_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
			define( 'GFC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'GFC_MIN_GIVE_VER', '2.1.0' );

			return self::$instance;
		}

		/**
		 * Load files.
		 *
		 * @since 1.0.2
		 * @access public
		 * @return Give_Form_Countdown
		 */
		public function init() {

			if ( ! $this->get_environment_warning() ) {
				return;
			}

			$this->activation_banner();

			// Bootstrap.
			require_once GFC_PLUGIN_DIR . 'includes/admin/class-form-metabox-setting.php';

			// Load helper functions.
			require_once GFC_PLUGIN_DIR . 'includes/functions.php';

			// Load actions.
			require_once GFC_PLUGIN_DIR . 'includes/actions.php';

			return self::$instance;
		}

		/**
		 * Check plugin environment.
		 *
		 * @since 1.0.20
		 * @access public
		 *
		 * @return bool
		 */
		public function check_environment() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Load plugin helper functions.
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			/* Check to see if Give is activated, if it isn't deactivate and show a banner. */
			// Check for if give plugin activate or not.
			$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false;

			if ( empty( $is_give_active ) ) {
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_activate', 'error', sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> plugin installed and activated for Give - Form Countdown to activate.', 'give-form-countdown' ), 'https://givewp.com' ) );
				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Check plugin for Give environment.
		 *
		 * @since 1.0.2
		 * @access public
		 *
		 * @return bool
		 */
		public function get_environment_warning() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Verify dependency cases.
			if (
				defined( 'GIVE_VERSION' )
				&& version_compare( GIVE_VERSION, GFC_MIN_GIVE_VER, '<' )
			) {

				/* Min. Give. plugin version. */
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_incompatible', 'error', sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> core version %s for the Give - Form Countdown add-on to activate.', 'give-form-countdown' ), 'https://givewp.com', GFC_MIN_GIVE_VER ) );

				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Show activation banner for this add-on.
		 *
		 * @since 1.0.2
		 *
		 * @return bool
		 */
		public function activation_banner() {

			// Check for activation banner inclusion.
			if (
				! class_exists( 'Give_Addon_Activation_Banner' )
				&& file_exists( GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php' )
			) {
				include GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';
			}

			// Initialize activation welcome banner.
			if ( class_exists( 'Give_Addon_Activation_Banner' ) ) {

				// Only runs on admin.
				$args = array(
					'file'              => __FILE__,
					'name'              => esc_html__( 'Give Form Countdown', 'give-form-countdown' ),
					'version'           => GFC_PLUGIN_VERSION,
					// 'settings_url'      => '',
					'documentation_url' => 'https://github.com/WordImpress/Give-Form-Countdown/',
					'support_url'       => 'https://github.com/WordImpress/Give-Form-Countdown/issues',
					'testing'           => false,// Never leave true.
				);

				new Give_Addon_Activation_Banner( $args );
			}

			return true;
		}

		/**
		 * Allow this class and other classes to add notices.
		 *
		 * @since 1.0.2
		 *
		 * @param $slug
		 * @param $class
		 * @param $message
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}

		/**
		 * Display admin notices.
		 *
		 * @since 1.0.2
		 */
		public function admin_notices() {

			$allowed_tags = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
					'class' => array(),
					'id'    => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'span'   => array(
					'class' => array(),
				),
				'strong' => array(),
			);

			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], $allowed_tags );
				echo '</p></div>';
			}

		}
	}

	/**
	 * Return Give_Form_Countdown class instance
	 *
	 * @since 1.0.2
	 *
	 * @return Give_Form_Countdown
	 */
	function Give_Form_Countdown() {
		return Give_Form_Countdown::get_instance();
	}

	Give_Form_Countdown();
}
