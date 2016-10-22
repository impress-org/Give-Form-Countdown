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

		// Add meta to form ( md5 of limit donation settings ) to check if data actually change or not.
		add_action( 'give_pre_process_give_forms_meta', array( $this, 'save_limit_donation_setting_md5' ) );

		// Setup cron after form data save.
		add_action( 'give_post_process_give_forms_meta', array( $this, 'setup_cron' ) );

		// Process cron job
		add_action( 'limit_donation_close_form', array( $this, 'close_form' ) );
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
					'options' => $this->get_time_list(),
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

	/**
	 * Setup cron to automatically close form when time met.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param  int $form_id
	 *
	 * @return void
	 */
	function setup_cron( $form_id ) {
		$do_not_setup_cron = false;
		$limit_timestamp   = '';

		// Bailout.
		switch ( true ) {
			// Check if admin want to auto close form when time met.
			case ( ! give_is_setting_enabled( get_post_meta( $form_id, 'limit-donation-close-from', true ) ) ):
				$do_not_setup_cron = true;
				break;

			// Validate time
			case ( ! ( $limit_timestamp = $this->get_form_close_date( $form_id ) ) ):
				$do_not_setup_cron = true;
				break;

			// Check time
			case ( current_time( 'timestamp', 1 ) > $limit_timestamp ):
				$do_not_setup_cron = true;
				break;

			// Do nothing if admin did not change form setting.
			case ( get_post_meta( $form_id, 'limit_donation_md5', true ) === $this->limit_donation_setting_md5( $form_id ) ):
				return;
		}

		// Remove meta if exist.
		delete_post_meta( $form_id, 'limit_donation_time_achieved' );

		// Clear previous cron.
		wp_clear_scheduled_hook( 'limit_donation_close_form', array( $form_id ) );

		// Bailout.
		if ( $do_not_setup_cron ) {
			return;
		}

		// Setup cron.
		wp_schedule_single_event( $limit_timestamp, 'limit_donation_close_form', array( $form_id ) );
	}

	/**
	 * Set array of time.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	function get_time_list() {
		$times = array(
			'0100' => __( '1:00 AM', 'give' ),
			'0200' => __( '2:00 AM', 'give' ),
			'0300' => __( '3:00 AM', 'give' ),
			'0400' => __( '4:00 AM', 'give' ),
			'0500' => __( '5:00 AM', 'give' ),
			'0600' => __( '6:00 AM', 'give' ),
			'0700' => __( '7:00 AM', 'give' ),
			'0800' => __( '8:00 AM', 'give' ),
			'0900' => __( '9:00 AM', 'give' ),
			'1000' => __( '10:00 AM', 'give' ),
			'1100' => __( '11:00 AM', 'give' ),
			'1200' => __( '12:00 AM', 'give' ),
			'1300' => __( '1:00 PM', 'give' ),
			'1400' => __( '2:00 PM', 'give' ),
			'1500' => __( '3:00 PM', 'give' ),
			'1600' => __( '4:00 PM', 'give' ),
			'1700' => __( '5:00 PM', 'give' ),
			'1800' => __( '6:00 PM', 'give' ),
			'1900' => __( '7:00 PM', 'give' ),
			'2000' => __( '8:00 PM', 'give' ),
			'2100' => __( '9:00 PM', 'give' ),
			'2200' => __( '10:00 PM', 'give' ),
			'2300' => __( '11:00 PM', 'give' ),
			'2400' => __( '12:00 PM', 'give' ),
		);

		// Format time  with wp time format setting.
		$wp_time_format = get_option( 'time_format' );
		foreach ( $times as $key => $value ) {
			$times[ $key ] = date( $wp_time_format, strtotime( $value ) );
		}

		return $times;
	}

	/**
	 * Get form close date.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param int    $form_id
	 * @param string $date_format
	 *
	 * @return string
	 */
	function get_form_close_date( $form_id, $date_format = '' ) {
		$limit_timestamp = '';

		// Get donation time limit type.
		$limit_donation_by = get_post_meta( $form_id, 'limit-donation-by', true );


		switch ( $limit_donation_by ) {
			case 'number_of_days':
				$limit_in_day = absint( get_post_meta( $form_id, 'limit-donation-in-number-of-days', true ) );
				// Bailout: Day should be greater than zero.
				if ( ! $limit_in_day ) {
					break;
				}

				// Timestamp.
				$limit_timestamp = strtotime( "+ $limit_in_day days", current_time( 'timestamp', 1 ) );
				break;

			case 'end_on_day_and_time':
				$limit_in_date = get_post_meta( $form_id, 'limit-donation-on-date', true );
				$limit_in_time = get_post_meta( $form_id, 'limit-donation-on-time', true );

				// Bailout: Date and time should be non empty.
				if ( empty( $limit_in_date ) || empty( $limit_in_time ) ) {
					break;
				}

				// Timestamp.
				$limit_timestamp = get_gmt_from_date( "$limit_in_date {$this->get_time_list()[ $limit_in_time ]}", 'U' );

				break;
		}

		// Output.
		return ( $date_format ? date( $date_format, $limit_timestamp ) : $limit_timestamp );
	}


	/**
	 * Set meta key to detect form closed or not.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param  int $form_id
	 *
	 * @return void
	 */
	function close_form( $form_id ) {
		if ( ! give_is_setting_enabled( get_post_meta( $form_id, 'limit-donation-close-from', true ) ) ) {
			return;
		}

		add_post_meta( $form_id, 'limit_donation_time_achieved', $this->get_form_close_date( $form_id ), true );
	}


	/**
	 * Get md5 donation limit setting.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param  int $form_id
	 *
	 * @return string
	 */
	function limit_donation_setting_md5( $form_id ) {
		$limit_donation_setting = array(
			'limit-donation-close-from'        => get_post_meta( $form_id, 'limit-donation-close-from', true ),
			'limit-donation-by'                => get_post_meta( $form_id, 'limit-donation-by', true ),
			'limit-donation-in-number-of-days' => get_post_meta( $form_id, 'limit-donation-in-number-of-days', true ),
			'limit-donation-on-date'           => get_post_meta( $form_id, 'limit-donation-on-date', true ),
			'limit-donation-on-time'           => get_post_meta( $form_id, 'limit-donation-on-time', true ),
		);

		return md5( serialize( $limit_donation_setting ) );
	}


	/**
	 * MD5 donation limit setting to check if setting change status.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param  int $form_id
	 *
	 * @return void
	 */
	function save_limit_donation_setting_md5( $form_id ) {
		add_post_meta( $form_id, 'limit_donation_md5', $this->limit_donation_setting_md5( $form_id ), true );
	}
}


// initialize.
Give_Limit_Donation_Duration_Metabox_Settings::get_instance()->setup_params()->setup_hooks();
