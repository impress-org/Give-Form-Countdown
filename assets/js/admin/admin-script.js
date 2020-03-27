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
			theme_val = $( 'select#form-countdown-theme').val()
			action_val = $('input[name="form-countdown-achieved-action"]:checked').val();

		if ('enabled' === field_value) {
			$end_date.show(),
			$end_time.show(),
			$theme.show(),
			$countdown_achieved.show();

			if ( theme_val === 'custom' ) {
				$color_picker.show();
			}
			if ( action_val === 'dont_close' || action_val === 'hide_countdown'  ) {
				$countdown_achieved_msg.hide();
			} else {
				$countdown_achieved_msg.show();
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
	
	/**
	 *  Conditionally show the countdown achieved message field
	 */

	$countdown_achieved.on( 'change', function() {
		var action_val = $('input[name="form-countdown-achieved-action"]:checked', $(this)).val(),
			countdown_achieved_msg = $('.form-countdown-message_field');

        if ( action_val === 'close_form' || action_val === 'message_and_form' || action_val === 'hide_countdown_show_message' || action_val === 'hide_countdown_and_form_show_message' ) {
            countdown_achieved_msg.fadeIn(300);
        } else {
            countdown_achieved_msg.fadeOut(300);
        }
    } ).change();
});
