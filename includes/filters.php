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
function give_ldd_form_close( $is_closed, $form_id ) {
	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id ) ) {
		return true;
	}

	return $is_closed;
}

add_filter( 'give_is_close_donation_form', 'give_ldd_form_close', 10, 2 );

function give_ldd_closed_form_message( $message, $form_id ){
	$form = new Give_Donate_Form( $form_id );

	// If form is for limited duration and goal is not achieved then show limit duration message.
	if(
		give_is_limit_donation_time_achieved( $form_id )
		&& ( $form->get_goal() > $form->get_earnings() )
	) {
		$message = get_post_meta( $form_id, 'limit-donation-message', true );
	}

	return $message;
}
add_filter( 'give_goal_closed_output', 'give_ldd_closed_form_message', 9999, 2 );