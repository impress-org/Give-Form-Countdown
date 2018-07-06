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
		$countdown_clock = $('.form-countdown-countdown-clock_field');

	// Add warning.
	if (!$donation_duration_message_wraning_wrap.length) {
		$('label', $donation_duration_msg).after('<div class="give-notice give-notice-warning" style="border-left: 4px solid #ffb900;background: white;padding: 4px 12px;box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.11);"><em>' + gdc_vars.duration_ended_message.warning + ' <a href="#" class="give-edit-goal-message">Click here</a> to edit message.</em></div>');
		$donation_duration_message_wraning_wrap = $('.give-notice-warning', $donation_duration_msg);
		$goal_edit_msg_link = $('.give-edit-goal-message', $donation_duration_msg);

	}

	/**
	 * Datepicker field.
	 */
	$('#form-countdown-on-date').datepicker({
		minDate: 1
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
	 * Limit donation.
	 */
	$limit_donation_radio.change(function () {
		if ('disabled' === $('.form-countdown-close-form_field :radio:checked').val()) {
			return false;
		}

		var field_value = $('.form-countdown-by_field :radio:checked').val();

		if ('number_of_days' === field_value) {

			$('.form-countdown-in-number-of-days_field').show();
			$('.form-countdown-on-date_field').hide();
			$('.form-countdown-on-time_field').hide();
		} else if ('end_on_day_and_time' === field_value) {

			$('.form-countdown-in-number-of-days_field').hide();
			$('.form-countdown-on-date_field').show();
			$('.form-countdown-on-time_field').show();
		}

		$('.form-countdown-message_field').show();

	}).change();

	/**
	 * Use donation end message.
	 */
	$donation_goal.on('change', function () {
		var selected_value = $('input[type="radio"]:checked', $(this)).val(),
			close_form_value = $('input[type="radio"]:checked', $close_form_donation_achieved).val(),
			donation_end_message_value = $('input[type="radio"]:checked', $donation_end_message).val();

		if ('enabled' === selected_value && 'enabled' === close_form_value && 'disabled' === donation_end_message_value) {
			$donation_end_message.show();
		} else {
			$donation_end_message.hide();
		}

		$donation_end_message.change();
	}).change();

	$close_form_donation_achieved.on('change', function () {
		var selected_value = $('input[type="radio"]:checked', $(this)).val(),
			donation_goal_value = $('input[type="radio"]:checked', $(this)).val(),
			goal_value = $('input[type="radio"]:checked', $donation_goal).val();

		if ('enabled' === selected_value && 'enabled' === donation_goal_value && 'enabled' === goal_value) {
			$donation_end_message.show();
		} else {
			$donation_end_message.hide();
		}

		$donation_end_message.change();
	}).change();

	$donation_end_message.on('change', function () {
		var selected_value = $('input[type="radio"]:checked', $(this)).val(),
			close_form_value = $('input[type="radio"]:checked', $close_form_donation_achieved).val(),
			goal_value = $('input[type="radio"]:checked', $donation_goal).val();

		if ('disabled' === selected_value && 'enabled' === close_form_value && 'enabled' === goal_value) {
			$goal_achieved_msg.show();
			$donation_duration_message_wysiwyg.hide();
			$donation_duration_message_wysiwyg.next().hide();
			$donation_duration_message_wraning_wrap.show();

		} else {
			$goal_achieved_msg.hide();
			$donation_duration_message_wysiwyg.show();
			$donation_duration_message_wysiwyg.next().show();
			$donation_duration_message_wraning_wrap.hide();
		}
	}).change();

	/**
	 * Donation duration edit link.
	 */
	$goal_edit_msg_link.on('click', function (e) {
		e.preventDefault();

		$('a[href="#donation_goal_options"]').trigger('click');
		$('html, body').animate({scrollTop: $goal_achieved_msg.position().top}, 'slow');
		return false;
	});
});
