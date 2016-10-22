<?php

/**
 * Class Give_Limit_Donation_Duration_Metabox_Settings
 *
 * @since 1.0
 */
class Give_Limit_Donation_Duration_Metabox_Settings {

	/**
	 * Instance.
	 *
	 * @since  1.0
	 * @access static
	 * @var Give_Limit_Donation_Duration_Metabox_Settings
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
	 * Give_Limit_Donation_Duration_Metabox_Settings constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get single instance.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Limit_Donation_Duration_Metabox_Settings
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
	 * @return Give_Limit_Donation_Duration_Metabox_Settings
	 */
	public function setup_params() {
		$this->id    = 'give-limit-donation-duration';
		$this->label = __( 'Donation Duration', 'give' );

		return static::get_instance();

	}

	/**
	 * Give_Limit_Donation_Duration_Metabox_Settings constructor.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function setup_hooks() {
		// Add settings.
		add_filter( 'give_metabox_form_data_settings', array( $this, 'setup_setting' ), 999 );

		// Enqueue scripts.
		add_filter( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 999 );
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

		// Setup settings.
		$settings[ $this->id ] = array(
			'id'     => $this->id,
			'title'  => $this->label,
			'fields' => array(
				// Close Form.
				array(
					'id'          => 'limit-donation-close-from',
					'name'        => __( 'Close Form', 'give' ),
					'type'        => 'radio_inline',
					'default'     => 'disabled',
					'options'     => array(
						'enabled'  => __( 'Enabled', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
					'description' => __( 'Would you like to close the donation forms and stop accepting donations once time limit met?', 'give' ),
				),

				// Donation duration type.
				array(
					'id'      => 'limit-donation-by',
					'name'    => __( 'Donation limit', 'give' ),
					'type'    => 'radio_inline',
					'default' => 'number_of_days',
					'options' => array(
						'number_of_days'      => __( 'Number of days', 'give' ),
						'end_on_day_and_time' => __( 'End on day & time', 'give' ),
					),
				),

				// Days.
				array(
					'id'      => 'limit-donation-in-number-of-days',
					'name'    => __( 'Days', 'give' ),
					'type'    => 'text-small',
					'default' => '30',
				),

				// Date
				array(
					'id'   => 'limit-donation-on-date',
					'name' => __( 'Date', 'give' ),
					'type' => 'text-medium',
				),

				// Time
				array(
					'id'      => 'limit-donation-on-time',
					'name'    => __( 'Time', 'give' ),
					'type'    => 'select',
					'options' => give_ldd_get_time_list(),
				),

				// Duration achieved message.
				array(
					'id'         => 'limit-donation-message',
					'name'       => __( 'Duration achieved message', 'give' ),
					'type'       => 'textarea',
					'attributes' => array(
						'placeholder' => __( 'Thank you to all our donors, we have met our fundraising goal.', 'give' ),
					),
				),
			),
		);

		return $settings;
	}


	/**
	 * Load scripts.
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

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'limit-donation-admin-script', LIMIT_DONATION_PLUGIN_URL . 'assets/js/admin-script.js' );
	}
}


// initialize.
Give_Limit_Donation_Duration_Metabox_Settings::get_instance()->setup_params()->setup_hooks();
