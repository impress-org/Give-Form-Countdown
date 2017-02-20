<?php
/**
 * Filter the countdown clock time constraints.
 *
 * @since 1.0
 * @todo  : Refactor javascript code.
 * @todo  : Add months time constraint.
 */


/**
 * Filter the option time constraints per form
 *
 * @since 1.0
 */
$gfc_default_time_constraints = apply_filters(
	"gfc_{$form_id}_countdown_clock_1_time_constraints",
	array(
		__( 'weeks', 'give-form-countdown' ),
		__( 'days', 'give-form-countdown' ),
	)
);

/**
 * Filter the time constraints for all form.
 *
 * @since 1.0
 */
$gfc_default_time_constraints = apply_filters(
	"gfc_countdown_clock_1_time_constraints",
	$gfc_default_time_constraints,
	$form_id
);

// Time constraints supported weeks (optional), days (optional), hours, minutes, seconds.
$gfc_time_constraints = array_filter(
	array_merge(
		$gfc_default_time_constraints,
		array(
			__( 'hours', 'give-form-countdown' ),
			__( 'minutes', 'give-form-countdown' ),
			__( 'seconds', 'give-form-countdown' ),
		)
	),

	// Remove empty time constraints.
	function ( $time_constraint ) {
		return ! empty( $time_constraint );
	}
);


// Set default date.
$default_date = '';
for ( $i = 1; $i <= count( $gfc_time_constraints ); $i ++ ) {
	$default_date[] = '00';
}
$default_date = implode( ':', $default_date );
?>
<div id="gfc-clock-<?php echo $form_id; ?>-wrap" class="gfc-clock-wrap">
	<span id="gfc-clock-<?php echo $form_id; ?>"></span>
</div>
<script type="text/template" id="gfc-clock-<?php echo $form_id; ?>-template">
	<div class="time <%= label %>">
		<span class="count curr top"><%= curr %></span>
		<span class="count next top"><%= next %></span>
		<span class="count next bottom"><%= next %></span>
		<span class="count curr bottom"><%= curr %></span>
		<span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span>
	</div>
</script>
<script type="text/javascript">

	jQuery(window).on('load', function () {
		var labels   = ['<?php echo implode( '\',\'', $gfc_time_constraints ); ?>'],
			nextYear = '<?php echo get_date_from_gmt( gfc_get_form_close_date( $form_id, 'Y/m/d H:i:s' ), 'Y/m/d H:i:s' ); ?>',
			template = _.template(jQuery("#gfc-clock-<?php echo esc_js( $form_id ); ?>-template").html()),
			currDate = nextDate = '<?php echo $default_date; ?>',
			$gfc_clock = jQuery("#gfc-clock-<?php echo esc_js( $form_id ); ?>");

		// Parse countdown string to an object
		function strfobj(str) {
			var parsed = str.split(':'),
				obj    = {};

			labels.forEach(function (label, i) {
				obj[label] = parsed[i]
			});

			return obj;
		}

		// Return the time components that diffs
		function diff(obj1, obj2) {
			var diff = [];
			labels.forEach(function (key) {
				if (obj1[key] !== obj2[key]) {
					diff.push(key);
				}
			});
			return diff;
		}

		// Build the layout
		var initData = strfobj(currDate);
		labels.forEach(function (label, i) {
			$gfc_clock.append(template({
				curr : initData[label],
				next : initData[label],
				label: label
			}));
		});

		function get_time_format(event) {
			var format = '%M:%S', newDate, data;

			// Set hours.
			if (-1 === labels.indexOf('days') && -1 === labels.indexOf('weeks') && -1 === labels.indexOf('months')) {
				format = '%I:' + format;
			} else if (( -1 !== labels.indexOf('weeks') || -1 !== labels.indexOf('months') ) && -1 === labels.indexOf('days')) {
				var hours = event.offset.days * 24 + event.offset.hours;
				hours     = ( 10 > hours ? '0' + hours.toString() : hours.toString() );
				format    = hours + ':' + format;
			} else {
				format = '%H:' + format;
			}

			// Set days.
			if (-1 !== labels.indexOf('days')) {
				if (-1 !== labels.indexOf('weeks')) {
					format = '%d:' + format;
				} else {
					format = '%D:' + format;
				}
			}

			// Set weeks.
			if (-1 !== labels.indexOf('weeks')) {
				format = '%w:' + format;
			}

			return format;
		}

		// Starts the countdown
		$gfc_clock.countdown(nextYear, function (event) {
			var format = get_time_format(event);

			// New date.
			newDate = event.strftime(format);

			if (newDate !== nextDate) {
				currDate = nextDate;
				nextDate = newDate;

				// Setup the data
				data = {
					'curr': strfobj(currDate),
					'next': strfobj(nextDate)
				};

				// Apply the new values to each node that changed
				diff(data.curr, data.next).forEach(function (label) {
					var selector = '.%s'.replace(/%s/, label),
						$node    = $gfc_clock.find(selector);

					$node.removeClass('gfc-time-length-' + data.curr[label].length);
					$node.addClass('gfc-time-length-' + data.next[label].length);

					// Update the node
					$node.removeClass('flip');
					$node.find('.curr').text(data.curr[label]);
					$node.find('.next').text(data.next[label]);

					// Wait for a repaint to then flip
					_.delay(function ($node) {
						$node.addClass('flip');
					}, 50, $node);
				});
			}
		});
	});
</script>
