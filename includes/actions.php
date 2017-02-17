<?php
/**
 * Show donation duration end message.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gdd_add_pre_form_end_message( $form_id ){
	$is_goal = give_is_setting_enabled( get_post_meta( $form_id, '_give_goal_option', true ) );
	$is_close_form = give_is_setting_enabled( get_post_meta( $form_id, '_give_close_form_when_goal_achieved', true ) );

	// Bailout
	if( $is_goal && $is_close_form ) {
		return false;
	}

	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id )  && 'above_form' === get_post_meta( $form_id, 'donation-duration-message-achieved-position', true ) ) {
		echo gdd_get_message( $form_id );
	}

}
add_action( 'give_pre_form_output', 'gdd_add_pre_form_end_message' );

/**
 * Show donation duration end message.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gdd_add_post_form_end_message( $form_id ){
	$is_goal = give_is_setting_enabled( get_post_meta( $form_id, '_give_goal_option', true ) );
	$is_close_form = give_is_setting_enabled( get_post_meta( $form_id, '_give_close_form_when_goal_achieved', true ) );

	// Bailout
	if( $is_goal && $is_close_form ) {
		return false;
	}

	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id )  && 'below_form' === get_post_meta( $form_id, 'donation-duration-message-achieved-position', true ) ) {
		echo gdd_get_message( $form_id );
	}

}
add_action( 'give_post_form_output', 'gdd_add_post_form_end_message' );