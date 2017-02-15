jQuery(document).ready(function ($) {
	/**
	 * Selectors.
	 */
	var $close_form           = $('.donation-duration-close-form_field :radio'),
		$limit_donation_radio = $('.donation-duration-by_field :radio');

	/**
	 * Datepicker field.
	 */
	$('#donation-duration-on-date').datepicker({
		minDate: 1
	});

	/**
	 * Close form.
	 */
	$close_form.change(function () {
		var field_value                = $('.donation-duration-close-form_field :radio:checked').val(),
			donation_limit_radio_value = $('.donation-duration-by_field :radio:checked').val();

		if ('enabled' === field_value) {
			$('.donation-duration-by_field').show();
			$('.donation-duration-message_field').show();

			if ('number_of_days' === donation_limit_radio_value) {

				$('.donation-duration-in-number-of-days_field').show();
				$('.donation-duration-on-date_field').hide();
				$('.donation-duration-on-time_field').hide();
			} else if ('end_on_day_and_time' === donation_limit_radio_value) {

				$('.donation-duration-in-number-of-days_field').hide();
				$('.donation-duration-on-date_field').show();
				$('.donation-duration-on-time_field').show();
			}
		} else {
			$('.donation-duration-by_field').hide();
			$('.donation-duration-in-number-of-days_field').hide();
			$('.donation-duration-on-date_field').hide();
			$('.donation-duration-on-time_field').hide();
			$('.donation-duration-message_field').hide();
		}
	}).change();

	/**
	 * Limit donation.
	 */
	$limit_donation_radio.change(function () {
		if ('disabled' === $('.donation-duration-close-form_field :radio:checked').val()) {
			return false;
		}

		var field_value = $('.donation-duration-by_field :radio:checked').val();

		if ('number_of_days' === field_value) {

			$('.donation-duration-in-number-of-days_field').show();
			$('.donation-duration-on-date_field').hide();
			$('.donation-duration-on-time_field').hide();
		} else if ('end_on_day_and_time' === field_value) {

			$('.donation-duration-in-number-of-days_field').hide();
			$('.donation-duration-on-date_field').show();
			$('.donation-duration-on-time_field').show();
		}

		$('.donation-duration-message_field').show();

	}).change();
});