jQuery(document).ready(function ($) {
	/**
	 * Selectors.
	 */
	var $close_form = $('.form-countdown-close-form_field'),
		$limit_donation_radio = $('.form-countdown-by_field :radio'),
		$donation_goal = $('._give_goal_option_field'),
		$donation_end_message = $('.form-countdown-use-end-message_field'),
		$close_form_donation_achieved = $('._give_close_form_when_goal_achieved_field'),
		$goal_achieved_msg = $('._give_form_goal_achieved_message_field'),
		$donation_duration_msg = $('.form-countdown-message_field '),
		$donation_duration_message_wraning_wrap = $('.give-notice-warning', $donation_duration_msg),
		$donation_duration_message_wysiwyg = $('#wp-form-countdown-message-wrap'),
		$goal_edit_msg_link = '',
		$duration_achieved_msg_position = $('.form-countdown-message-achieved-position_field'),
		$countdown_clock = $('.form-countdown-countdown-clock_field'),
		$theme = $('.form-countdown-theme_field');

	/**
	 * Datepicker field.
	 */
	$('#form-countdown-on-date').datepicker({
		//minDate: 1,
		defaultDate: +7,
		buttonText: '<span class="dashicons dashicons-calendar-alt"></span>',
		dateFormat: 'yy-mm-dd',
		showOn: 'both'
	});

	/**
	 * Close form.
	 */
	$close_form.change(function () {
		var field_value = $('input[type="radio"]:checked', $(this)).val(),
			donation_limit_radio_value = $('.form-countdown-by_field :radio:checked').val();

		if ('enabled' === field_value) {
			$('.form-countdown-by_field').show();
			$donation_duration_msg.show();
			$duration_achieved_msg_position.show();
			$countdown_clock.show();

			if ('number_of_days' === donation_limit_radio_value) {

				$('.form-countdown-in-number-of-days_field').show();
				$('.form-countdown-on-date_field').hide();
				$('.form-countdown-on-time_field').hide();
			} else if ('end_on_day_and_time' === donation_limit_radio_value) {

				$('.form-countdown-in-number-of-days_field').hide();
				$('.form-countdown-on-date_field').show();
				$('.form-countdown-on-time_field').show();
			}
		} else {
			$('.form-countdown-by_field').hide();
			$('.form-countdown-in-number-of-days_field').hide();
			$('.form-countdown-on-date_field').hide();
			$('.form-countdown-on-time_field').hide();
			$('.form-countdown-message_field').hide();
			$duration_achieved_msg_position.hide();
			$countdown_clock.hide();
		}
	}).change();

	/**
	 *  Show Custom Color
	 */

	$theme.on( 'change', function() {

		var scheme_val = $( '.form-countdown-theme_field select' ).val(),
		$custom_color = $('.form-countdown-custom-theme-picker_field');

        if ( scheme_val === 'custom' ) {
            //set price shows
            $custom_color.show();
        } else {
            //multi-value shows
            $custom_color.hide();
        }
    } ).change();
});
