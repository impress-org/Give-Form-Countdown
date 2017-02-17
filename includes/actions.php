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

	wp_enqueue_script( 'donation-duration-jquery-countdown-script' );
	wp_enqueue_script( 'donation-duration-underscore-script' );
	wp_enqueue_style( 'donation-duration-jquery-countdown-layout-1-style' );
	?>
	<div id="gdd-clock-<?php echo $form_id; ?>-wrap" class="gdd-clock-wrap"><span id="gdd-clock-<?php echo $form_id; ?>"></span></div>
	<script type="text/template" id="gdd-clock-<?php echo $form_id; ?>-template">
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
			var labels   = ['weeks', 'days', 'hours', 'minutes', 'seconds'],
				nextYear = '<?php echo get_date_from_gmt( gdd_get_form_close_date( $form_id, 'Y/m/d H:i:s' ), 'Y/m/d H:i:s' ); ?>',
				template = _.template(jQuery("#gdd-clock-<?php echo esc_js( $form_id ); ?>-template").html()),
				currDate = '00:00:00:00:00',
				nextDate = '00:00:00:00:00',
				parser   = /([0-9]{2})/gi,
				$gdd_clock = jQuery("#gdd-clock-<?php echo esc_js( $form_id ); ?>");

			// Parse countdown string to an object
			function strfobj(str) {
				var parsed = str.match(parser),
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
				$gdd_clock.append(template({
					curr : initData[label],
					next : initData[label],
					label: label
				}));
			});

			// Starts the countdown
			$gdd_clock.countdown(nextYear, function (event) {
				var newDate = event.strftime('%w:%d:%H:%M:%S'),
					data;
				if (newDate !== nextDate) {
					currDate = nextDate;
					nextDate = newDate;
					// Setup the data
					data     = {
						'curr': strfobj(currDate),
						'next': strfobj(nextDate)
					};
					// Apply the new values to each node that changed
					diff(data.curr, data.next).forEach(function (label) {
						var selector = '.%s'.replace(/%s/, label),
							$node    = $gdd_clock.find(selector);
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
	<?php
}
add_action( 'give_pre_form_output', 'gdd_add_pre_form_countdown_clock' );