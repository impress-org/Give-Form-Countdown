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


/**
 * Show countdown clock.
 *
 * @since 1.0
 * @param $form_id
 *
 * @return bool
 */
function gdd_add_pre_form_countdown_clock( $form_id ){
	// Check if time achieved or not.
	if ( ! gdd_is_form_has_limited_duration( $form_id ) || give_is_limit_donation_time_achieved( $form_id ) ) {
		return false;
	}

	wp_enqueue_script( 'donation-duration-jquery-countdown' );
	?>
	<div id="gdd-clock-<?php echo $form_id; ?>-wrap"><strong><?php _e( 'Time Left:', 'give-donation-duration' ); ?></strong>&nbsp;<span id="gdd-clock-<?php echo $form_id; ?>"></span></div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("#gdd-clock-<?php echo esc_js( $form_id ); ?>")
				.countdown( "<?php echo get_date_from_gmt( gdd_get_form_close_date( $form_id, 'Y/m/d H:i:s' ), 'Y/m/d H:i:s' ); ?>", function(event) {
					var totalHours = event.offset.totalDays * 24 + event.offset.hours;
					$(this).html(event.strftime(totalHours + ' hr %M min %S sec'));
				});
		});
	</script>
	<?php
}
add_action( 'give_pre_form_output', 'gdd_add_pre_form_countdown_clock' );