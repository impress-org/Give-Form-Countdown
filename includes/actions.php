<?php
/**
 * Show donation duration end message.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gfc_add_pre_form_end_message( $form_id ){
	$is_goal = give_is_setting_enabled( get_post_meta( $form_id, '_give_goal_option', true ) );
	$is_close_form = give_is_setting_enabled( get_post_meta( $form_id, '_give_close_form_when_goal_achieved', true ) );

	// Bailout
	if( $is_goal && $is_close_form ) {
		return false;
	}

	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id )  && 'above_form' === get_post_meta( $form_id, 'form-countdown-message-achieved-position', true ) ) {
		echo gfc_get_message( $form_id );
	}

}
add_action( 'give_pre_form_output', 'gfc_add_pre_form_end_message' );

/**
 * Show donation duration end message.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gfc_add_post_form_end_message( $form_id ){
	$is_goal = give_is_setting_enabled( get_post_meta( $form_id, '_give_goal_option', true ) );
	$is_close_form = give_is_setting_enabled( get_post_meta( $form_id, '_give_close_form_when_goal_achieved', true ) );

	// Bailout
	if( $is_goal && $is_close_form ) {
		return false;
	}

	// Check if time achieved or not.
	if ( give_is_limit_donation_time_achieved( $form_id )  && 'below_form' === get_post_meta( $form_id, 'form-countdown-message-achieved-position', true ) ) {
		echo gfc_get_message( $form_id );
	}

}
add_action( 'give_post_form_output', 'gfc_add_post_form_end_message' );


/**
 * Show countdown clock.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gfc_add_pre_form_countdown_clock( $form_id ){
	// Check if time achieved or not.
	if (
		! gfc_is_form_has_limited_duration( $form_id )
		|| give_is_limit_donation_time_achieved( $form_id )
		|| ! give_is_setting_enabled( get_post_meta( $form_id, 'form-countdown-countdown-clock', true ) )
	) {
		return false;
	}

	/**
	 * Filter the countdown clock template per form.
	 *
	 * @since 1.0
	 */
	$countdown_clock = apply_filters( "gfc_{$form_id}_countdown_clock_template", GFC_PLUGIN_DIR. 'template/countdown_clock_1.php', $form_id );


	/**
	 * Filter the countdown clock template for all form.
	 *
	 * @since 1.0
	 */
	$countdown_clock = apply_filters( "gfc_countdown_clock_template", $countdown_clock, $form_id );


	if( 'countdown_clock_1.php' === wp_basename( $countdown_clock ) ) {
		wp_enqueue_script( 'form-countdown-jquery-countdown-script' );
		wp_enqueue_script( 'form-countdown-underscore-script' );
		wp_enqueue_style( 'form-countdown-jquery-countdown-layout-1-style' );

		include $countdown_clock;
	}

	/**
	 *  Fire the action to renderr countdown clock.
	 *
	 * @since 1.0
	 */
	do_action( 'gdf_render_countdown_clock', $countdown_clock, $form_id );
}
add_action( 'give_pre_form_output', 'gfc_add_pre_form_countdown_clock' );