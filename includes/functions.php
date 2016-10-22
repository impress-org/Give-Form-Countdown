<?php
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

	if ( get_post_meta( $form_id, 'limit_donation_time_achieved', true ) ) {
		$is_time_achieved = true;
	}

	return $is_time_achieved;
}