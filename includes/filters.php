<?php
/**
 * Tell system that donation form is close.
 *
 * @since 1.0
 *
 * @param bool $is_closed
 * @param int  $form_id
 *
 * @return bool
 */
function gdd_form_close( $is_closed, $form_id ) {
	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id )  && 'close_form' === get_post_meta( $form_id, 'donation-duration-message-achieved-position', true ) ) {
		return true;
	}

	return $is_closed;
}

add_filter( 'give_is_close_donation_form', 'gdd_form_close', 10, 2 );


/**
 * Show donation duration message when time achieved.
 *
 * @since 1.0
 *
 * @param $message
 * @param $form_id
 *
 * @return string
 */
function gdd_closed_form_message( $message, $form_id ) {
	$is_goal = give_is_setting_enabled( get_post_meta( $form_id, '_give_goal_option', true ) );
	$is_close_form = give_is_setting_enabled( get_post_meta( $form_id, '_give_close_form_when_goal_achieved', true ) );
	$is_use_donation_duration_end_message = give_is_setting_enabled( get_post_meta( $form_id, 'donation-duration-use-end-message', true ) );

	// If form is for limited duration and goal is not achieved then show limit duration message.
	if ( give_is_limit_donation_time_achieved( $form_id ) || ( $is_use_donation_duration_end_message && $is_goal && $is_close_form ) ){
		$message = gdd_get_message( $form_id );
	}

	return $message;
}

add_filter( 'give_goal_closed_output', 'gdd_closed_form_message', 9999, 2 );
