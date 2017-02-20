<?php
/**
 * Check if form limited by duration
 *
 * @param $form_id
 *
 * @return bool
 */
function gfc_is_form_has_limited_duration( $form_id ) {
	$has_limited_duration = false;

	if (
		give_is_setting_enabled( get_post_meta( $form_id, 'form-countdown-close-form', true ) )
		&& gfc_get_form_close_date( $form_id )
	) {
		$has_limited_duration = true;
	}

	return $has_limited_duration;
}

/**
 * Get form limit donation time status.
 *
 * @since 1.0
 *
 * @param $form_id
 *
 * @return bool
 */
function give_is_limit_donation_time_achieved( $form_id ) {
	$is_time_achieved = false;

	if ( gfc_is_form_has_limited_duration( $form_id ) ) {
		$is_time_achieved = ( gfc_get_form_close_date( $form_id ) < current_time( 'timestamp', 1 ) ? true : false );
	}

	return $is_time_achieved;
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
function gfc_get_form_close_date( $form_id, $date_format = '' ) {
	$limit_timestamp = '';

	// Get donation time limit type.
	$limit_donation_by = get_post_meta( $form_id, 'form-countdown-by', true );

	switch ( $limit_donation_by ) {
		case 'number_of_days':
			$limit_in_day = absint( get_post_meta( $form_id, 'form-countdown-in-number-of-days', true ) );
			// Bailout: Day should be greater than zero.
			if ( ! $limit_in_day ) {
				break;
			}

			// Local timestamp.
			$limit_timestamp = strtotime( "+ $limit_in_day days", current_time( 'timestamp' ) );

			$formatted_date = date( 'Y-m-d 00:00:00', $limit_timestamp );

			// GMT timestamp.
			$limit_timestamp = get_gmt_from_date( $formatted_date, 'U' );
			break;

		case 'end_on_day_and_time':
			$limit_in_date = get_post_meta( $form_id, 'form-countdown-on-date', true );
			$limit_in_time = get_post_meta( $form_id, 'form-countdown-on-time', true );
			$time_list     = gfc_get_time_list();

			// Bailout: Date and time should be non empty.
			if ( empty( $limit_in_date ) || empty( $limit_in_time ) ) {
				break;
			}

			$formatted_date = date( 'Y-m-d H:i:s', strtotime( $limit_in_date . ' ' . $time_list[ $limit_in_time ] ) );

			// GMT timestamp.
			$limit_timestamp = get_gmt_from_date( $formatted_date, 'U' );
			break;
	}

	// Output.
	return ( $date_format ? date( $date_format, $limit_timestamp ) : $limit_timestamp );
}


/**
 * Set array of time.
 *
 * @since  1.0
 * @access public
 *
 * @return array
 */
function gfc_get_time_list() {
	$times = array(
		'0100' => __( '1:00 AM', 'give-form-countdown' ),
		'0200' => __( '2:00 AM', 'give-form-countdown' ),
		'0300' => __( '3:00 AM', 'give-form-countdown' ),
		'0400' => __( '4:00 AM', 'give-form-countdown' ),
		'0500' => __( '5:00 AM', 'give-form-countdown' ),
		'0600' => __( '6:00 AM', 'give-form-countdown' ),
		'0700' => __( '7:00 AM', 'give-form-countdown' ),
		'0800' => __( '8:00 AM', 'give-form-countdown' ),
		'0900' => __( '9:00 AM', 'give-form-countdown' ),
		'1000' => __( '10:00 AM', 'give-form-countdown' ),
		'1100' => __( '11:00 AM', 'give-form-countdown' ),
		'1200' => __( '12:00 AM', 'give-form-countdown' ),
		'1300' => __( '1:00 PM', 'give-form-countdown' ),
		'1400' => __( '2:00 PM', 'give-form-countdown' ),
		'1500' => __( '3:00 PM', 'give-form-countdown' ),
		'1600' => __( '4:00 PM', 'give-form-countdown' ),
		'1700' => __( '5:00 PM', 'give-form-countdown' ),
		'1800' => __( '6:00 PM', 'give-form-countdown' ),
		'1900' => __( '7:00 PM', 'give-form-countdown' ),
		'2000' => __( '8:00 PM', 'give-form-countdown' ),
		'2100' => __( '9:00 PM', 'give-form-countdown' ),
		'2200' => __( '10:00 PM', 'give-form-countdown' ),
		'2300' => __( '11:00 PM', 'give-form-countdown' ),
		'2400' => __( '12:00 PM', 'give-form-countdown' ),
	);

	// Format time  with wp time format setting.
	$wp_time_format = get_option( 'time_format' );
	foreach ( $times as $key => $value ) {
		$times[ $key ] = date( $wp_time_format, strtotime( $value ) );
	}

	return $times;
}


/**
 * Get form message when time achieved.
 *
 * @since 1.0
 *
 * @param int $form_id
 *
 * @return string
 */
function gfc_get_message( $form_id ) {
	$message = get_post_meta( $form_id, 'form-countdown-message', true );
	$message = $message ? $message : __( 'Thank you to all our donors, we have met our fundraising goal.', 'give-form-countdown' );

	/**
	 * Filter the donation duration message.
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 * @param int    $form_id
	 */
	$message = apply_filters( 'give_donation_duration_message', wpautop( $message ) );

	return $message;
}
