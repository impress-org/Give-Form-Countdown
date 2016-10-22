jQuery(document).ready(function ($) {
	/**
	 * Selectors.
	 */
	var $close_form           = $('.limit-donation-close-from_field :radio'),
		$limit_donation_radio = $('.limit-donation-by_field :radio');

	/**
	 * Datepicker field.
	 */
	$('#limit-donation-on-date').datepicker({
		minDate: 1
	});

	/**
	 * Close form.
	 */
	$close_form.change(function () {
		var field_value                = $('.limit-donation-close-from_field :radio:checked').val(),
			donation_limit_radio_value = $('.limit-donation-by_field :radio:checked').val();

		if ('enabled' === field_value) {
			$('.limit-donation-by_field').show();
			$('.limit-donation-message_field').show();

			if ('number_of_days' === donation_limit_radio_value) {

				$('.limit-donation-in-number-of-days_field').show();
				$('.limit-donation-on-date_field').hide();
				$('.limit-donation-on-time_field').hide();
			} else if ('end_on_day_and_time' === donation_limit_radio_value) {

				$('.limit-donation-in-number-of-days_field').hide();
				$('.limit-donation-on-date_field').show();
				$('.limit-donation-on-time_field').show();
			}
		} else {
			$('.limit-donation-by_field').hide();
			$('.limit-donation-in-number-of-days_field').hide();
			$('.limit-donation-on-date_field').hide();
			$('.limit-donation-on-time_field').hide();
			$('.limit-donation-message_field').hide();
		}
	}).change();

	/**
	 * Limit donation.
	 */
	$limit_donation_radio.change(function () {
		if ('disabled' === $('.limit-donation-close-from_field :radio:checked').val()) {
			return false;
		}

		var field_value = $('.limit-donation-by_field :radio:checked').val();

		if ('number_of_days' === field_value) {

			$('.limit-donation-in-number-of-days_field').show();
			$('.limit-donation-on-date_field').hide();
			$('.limit-donation-on-time_field').hide();
		} else if ('end_on_day_and_time' === field_value) {

			$('.limit-donation-in-number-of-days_field').hide();
			$('.limit-donation-on-date_field').show();
			$('.limit-donation-on-time_field').show();
		}

		$('.limit-donation-message_field').show();

	}).change();
});