<?php
/**
 * Check if form limited by duration
 *
 * @param $form_id
 *
 * @return bool
 */
function give_ldd_is_form_has_limited_duration( $form_id ) {
	$has_limited_duration = false;

	if (
		give_is_setting_enabled( get_post_meta( $form_id, 'limit-donation-close-from', true ) )
		&& give_ldd_get_form_close_date( $form_id )
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
	
	if ( give_ldd_is_form_has_limited_duration( $form_id ) ) {
		$is_time_achieved = ( give_ldd_get_form_close_date( $form_id ) < current_time( 'timestamp', 1 ) ? true : false );
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
function give_ldd_get_form_close_date( $form_id, $date_format = '' ) {
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

			// Local timestamp.
			$limit_timestamp = strtotime( "+ $limit_in_day days", current_time( 'timestamp' ) );

			$formatted_date = date( 'Y-m-d 00:00:00', $limit_timestamp );

			//GMT timestamp.
			$limit_timestamp = get_gmt_from_date( $formatted_date, 'U' );
			break;

		case 'end_on_day_and_time':
			$limit_in_date = get_post_meta( $form_id, 'limit-donation-on-date', true );
			$limit_in_time = get_post_meta( $form_id, 'limit-donation-on-time', true );

			// Bailout: Date and time should be non empty.
			if ( empty( $limit_in_date ) || empty( $limit_in_time ) ) {
				break;
			}

			$formatted_date = date( 'Y-m-d H:i:s', strtotime( $limit_in_date . ' ' . give_ldd_get_time_list()[ $limit_in_time ] ) );

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
function give_ldd_get_time_list() {
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