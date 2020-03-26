jQuery(document).ready(function ($) {
	/**
	 * Countdown field selectors.
	 */
	var $close_form = $('.form-countdown-close-form_field'),
		$end_date = $('.form-countdown-on-date_field'),
		$end_time = $('.form-countdown-on-time_field'),
		$theme = $('.form-countdown-theme_field'),
		$color_picker = $('.form-countdown-custom-theme-picker_field'),
		$countdown_achieved = $('.form-countdown-achieved-action_field'),
		$countdown_achieved_msg = $('.form-countdown-message_field');

	/**
	 * Apply the Datepicker field.
	 */
	$('#form-countdown-on-date').datepicker({
		defaultDate: +7,
		buttonText: '<span class="dashicons dashicons-calendar-alt"></span>',
		dateFormat: 'yy-mm-dd',
		showOn: 'both'
	});

	/**
	 * Enable/Disable the Countdown settings.
	 */
	$close_form.change(function () {
		var field_value = $('input[type="radio"]:checked', $(this)).val(),
			theme_val = $( 'select#form-countdown-theme').val();

		if ('enabled' === field_value) {
			$end_date.show(),
			$end_time.show(),
			$theme.show(),
			$countdown_achieved.show(),
			$countdown_achieved_msg.show();

			if ( theme_val === 'custom' ) {
				$color_picker.show();
			}
		} else {
			$end_date.hide(),
			$end_time.hide(),
			$theme.hide(),
			$color_picker.hide(),
			$countdown_achieved.hide(),
			$countdown_achieved_msg.hide();
		}
	}).change();

	/**
	 *  Conditionally show the custom color field
	 */

	$theme.on( 'change', function() {

		var scheme_val = $('select', $(this)).val(),
			enabled = $('.form-countdown-close-form_field input[type="radio"]:checked').val(),
			$custom_color = $('.form-countdown-custom-theme-picker_field');

        if ( scheme_val === 'custom' && enabled === 'enabled' ) {
            $custom_color.show();
        } else {
            $custom_color.hide();
        }
    } ).change();
});
