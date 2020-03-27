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

	
			$limit_in_date = get_post_meta( $form_id, 'form-countdown-on-date', true );
			$limit_in_time = get_post_meta( $form_id, 'form-countdown-on-time', true );
			$time_list     = gfc_get_time_list();

			$formatted_date = date( 'Y-m-d H:i:s', strtotime( $limit_in_date . ' ' . $time_list[ $limit_in_time ] ) );

			// GMT timestamp.
			$limit_timestamp = get_gmt_from_date( $formatted_date, 'U' );

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
        '12:00:00' => __( '12:00 AM', 'givewp-form-countdown' ),
	    '01:00:00' => __( '1:00 AM', 'givewp-form-countdown' ),
		'02:00:00' => __( '2:00 AM', 'givewp-form-countdown' ),
		'03:00:00' => __( '3:00 AM', 'givewp-form-countdown' ),
		'04:00:00' => __( '4:00 AM', 'givewp-form-countdown' ),
		'05:00:00' => __( '5:00 AM', 'givewp-form-countdown' ),
		'06:00:00' => __( '6:00 AM', 'givewp-form-countdown' ),
		'07:00:00' => __( '7:00 AM', 'givewp-form-countdown' ),
		'08:00:00' => __( '8:00 AM', 'givewp-form-countdown' ),
		'09:00:00' => __( '9:00 AM', 'givewp-form-countdown' ),
		'10:00:00' => __( '10:00 AM', 'givewp-form-countdown' ),
		'11:00:00' => __( '11:00 AM', 'givewp-form-countdown' ),
        '24:00:00' => __( '12:00 PM', 'givewp-form-countdown' ),
        '13:00:00' => __( '1:00 PM', 'givewp-form-countdown' ),
		'14:00:00' => __( '2:00 PM', 'givewp-form-countdown' ),
		'15:00:00' => __( '3:00 PM', 'givewp-form-countdown' ),
		'16:00:00' => __( '4:00 PM', 'givewp-form-countdown' ),
		'17:00:00' => __( '5:00 PM', 'givewp-form-countdown' ),
		'18:00:00' => __( '6:00 PM', 'givewp-form-countdown' ),
		'19:00:00' => __( '7:00 PM', 'givewp-form-countdown' ),
		'20:00:00' => __( '8:00 PM', 'givewp-form-countdown' ),
		'21:00:00' => __( '9:00 PM', 'givewp-form-countdown' ),
		'22:00:00' => __( '10:00 PM', 'givewp-form-countdown' ),
		'23:00:00' => __( '11:00 PM', 'givewp-form-countdown' ),
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
	$message = $message ? $message : __( 'Thank you to all our donors, we have met our fundraising goal.', 'givewp-form-countdown' );

	/**
	 * Filter the donation duration message.
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 * @param int    $form_id
	 */

	$message = '<div class="gfc-message">' . apply_filters( 'the_content', wpautop( $message ) ) . '</div>';

	return apply_filters('give_donation_duration_message', $message);
}

function gfc_output_custom_color_scheme( $form_id ) {
	
	$color = get_post_meta( 'form-countdown-custom-theme-picker', true );
	
	ob_start();
	?>
	<style>
	/********** Theme: custom **********/
	/* Font styles */
	.flipdown.flipdown__theme-custom {
	font-family: sans-serif;
	font-weight: bold;
	}
	/* Rotor group headings */
	.flipdown.flipdown__theme-custom .rotor-group-heading:before {
	color: <?php echo gfc_adjustBrightness( esc_attr( $color), $steps = '-50');?>;
	}
	/* Delimeters */
	.flipdown.flipdown__theme-custom .rotor-group:nth-child(n+2):nth-child(-n+3):before,
	.flipdown.flipdown__theme-custom .rotor-group:nth-child(n+2):nth-child(-n+3):after {
	background-color: <?php echo esc_attr( $color );?>;
	}
	/* Rotor tops */
	.flipdown.flipdown__theme-custom .rotor,
	.flipdown.flipdown__theme-custom .rotor-top,
	.flipdown.flipdown__theme-custom .rotor-leaf-front {
	color: #FFFFFF;
	background-color: <?php echo esc_attr( $color );?>;
	}
	/* Rotor bottoms */
	.flipdown.flipdown__theme-custom .rotor-bottom,
	.flipdown.flipdown__theme-custom .rotor-leaf-rear {
	color: #EFEFEF;
	background-color: <?php echo gfc_adjustBrightness( esc_attr( $color ), $steps = '-10');?>;
	}
	/* Hinge */
	.flipdown.flipdown__theme-custom .rotor:after {
	border-top: solid 1px <?php echo esc_attr( $color );?>;
	}
	</style>
	<?php 
	
	$scheme = ob_get_clean();

	return $scheme;
}

function gfc_adjustBrightness( $hex, $steps ) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}
