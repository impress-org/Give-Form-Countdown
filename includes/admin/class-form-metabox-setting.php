<?php

/**
 * Class Give_Form_Countdown_Metabox_Settings
 *
 * @since 1.0
 */
class Give_Form_Countdown_Metabox_Settings {

	/**
	 * Instance.
	 *
	 * @since  1.0
	 * @access static
	 * @var Give_Form_Countdown_Metabox_Settings
	 */
	private static $instance;

	/**
	 * Setting id.
	 *
	 * @since  1.0
	 * @access private
	 *
	 * @var string
	 */
	private $id = '';

	/**
	 * Setting label.
	 *
	 * @since  1.0
	 * @access private
	 * @var string
	 */
	private $label = '';


	/**
	 * Singleton pattern.
	 *
	 * @since  1.0
	 * @access private
	 * Give_Form_Countdown_Metabox_Settings constructor.
	 */
	private function __construct() {
		
		$this->id     = 'gfc_form_settings';
		$this->prefix = '_gfc_';
		add_filter( 'give_metabox_form_data_settings', array( $this, 'setup_setting' ), 999 );
	
	}


	/**
	 * Get single instance.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Form_Countdown_Metabox_Settings
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Setup params.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Form_Countdown_Metabox_Settings
	 */
	public function setup_params() {
		$this->id    = 'givewp-form-countdown';
		$this->label = __( 'Form Countdown', 'givewp-form-countdown' );

		return static::get_instance();

	}

	/**
	 * Give_Form_Countdown_Metabox_Settings constructor.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function setup_hooks() {

		// Enqueue scripts.
		add_filter( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 999 );
		add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ), 999 );

		// Validate setting.
		add_action( 'give_post_process_give_forms_meta', array( $this, 'validate_settings' ) );

	}


	/**
	 * Plugin setting.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function setup_setting( $settings ) {
        
        // Custom metabox settings.
		$settings["{$this->id}_tab"] = array(
			'id'        => $this->id,
			'title'     => $this->label,
			'icon-html' => '<span class="dashicons dashicons-clock"></span>',
			'fields'    => array(
				// Close Form.
				array(
					'id'          => 'form-countdown-close-form',
					'name'        => __( 'Show Countdown', 'givewp-form-countdown' ),
					'type'        => 'radio_inline',
					'default'     => 'disabled',
					'options'     => array(
						'enabled'  => __( 'Enabled', 'givewp-form-countdown' ),
						'disabled' => __( 'Disabled', 'givewp-form-countdown' ),
					),
					'description' => __( 'Enable a duration for this form and display a message when the duration ends.', 'givewp-form-countdown' ),
				),

				// Date
				array(
					'id'          => 'form-countdown-on-date',
					'name'        => __( 'End Date', 'givewp-form-countdown' ),
					'type'        => 'text-medium',
					'description' => __( 'Set the date when the duration ends.', 'givewp-form-countdown' ),
				),

				// Time
				array(
					'id'          => 'form-countdown-on-time',
					'name'        => __( 'End Time', 'givewp-form-countdown' ),
					'type'        => 'select',
					'options'     => gfc_get_time_list(),
					'default'     => '1800',
					'description' => __( 'Set the time of day when the duration ends.', 'givewp-form-countdown' ),
				),

				//Color
				array(
					'id'		  => 'form-countdown-theme',
					'name'		  => __( 'Clock Color Scheme', 'givewp-form-countdown'),
					'type'		  => 'select',
					'options'	  => array(
						'light'	  => __('Light', 'givewp-form-countdown'),
						'dark'	  => __('Dark', 'givewp-form-countdown'),
						'custom'  => __('Custom', 'givewp-form-countdown'),
					),
					'default'	  => 'light'
				),

				//Custom Color Picker
				array(
					'id'		  => 'form-countdown-custom-theme-picker',
					'name'		  => __('Pick Your Custom Color'),
					'type'		  => 'colorpicker',
				),

				// Countdown achieved action.
				array(
					'id'          => 'form-countdown-achieved-action',
					'name'        => __( 'Countdown Achieved Action', 'givewp-form-countdown' ),
					'type'        => 'radio',
					'default'     => 'close_form',
					'options'     => array(
						'close_form' => __( 'Close the form and replace it with a message', 'givewp-form-countdown' ),
						'message_and_form' => __( 'Keep the form open and show this message above the form', 'givewp-form-countdown' ),
						'dont_close' => __( 'Don\'t take any action', 'givewp-form-countdown' ),
					),
					'description' => __( 'Choose what action, if any, you want to see when the countdown reaches zero.', 'givewp-form-countdown' ),
				),

				// Countdown achieved message.
				array(
					'id'          => 'form-countdown-message',
					'name'        => __( 'End Message', 'givewp-form-countdown' ),
					'type'        => 'wysiwyg',
					'default'  	  => __( 'Thank you to all our donors, 		this campaign has ended.', 'givewp-form-countdown' ),
					'description' => __( 'Enter content that appears in your form when the duration ends.', 'givewp-form-countdown' ),
					'attributes'  => array(
						'textarea_rows' => 10,
					),
				),
			),
		);

		return $settings;
	}


	/**
	 * Load admin scripts.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $hook
	 */
	function enqueue_admin_scripts( $hook ) {
		// Bailout.
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		global $post;

		// Bailout.
		if ( 'give_forms' !== $post->post_type ) {
			return;
		}

		wp_enqueue_style( 'form-countdown-admin-styles', GFC_PLUGIN_URL . 'assets/css/gfc-admin.css', array(), GFC_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'form-countdown-admin-script', GFC_PLUGIN_URL . 'assets/js/admin/admin-script.js', array( 'jquery' ), GFC_PLUGIN_VERSION );

	}

	/**
	 * Load scripts.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $hook
	 */
	function enqueue_frontend_scripts( $hook ) {
		wp_register_script( 'form-countdown-flipdown-script', GFC_PLUGIN_URL . 'assets/js/plugin/flipdown.min.js', array( 'jquery' ), GFC_PLUGIN_VERSION );
		wp_register_style( 'form-countdown-flipdown-style', GFC_PLUGIN_URL . 'assets/css/plugin/flipdown.min.css', array(), GFC_PLUGIN_VERSION );
	}


	/**
	 * Validate setting.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param $form_id
	 */
	public function validate_settings( $form_id ) {
		if ( ! gfc_get_form_close_date( $form_id ) ) {
			update_post_meta( $form_id, 'form-countdown-close-form', 'disabled' );
		}
	}
}


// initialize.
Give_Form_Countdown_Metabox_Settings::get_instance()->setup_params()->setup_hooks();
