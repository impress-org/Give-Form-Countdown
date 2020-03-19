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
$default_date = array();
for ( $i = 1; $i <= count( $gfc_time_constraints ); $i ++ ) {
	$default_date[] = '00';
}
$default_date = implode( ':', $default_date );
?>
<div id="gfc-clock-<?php echo $form_id; ?>-wrap" class="gfc-clock-wrap">
	<div id="flipdown-<?php echo $form_id; ?>" class="flipdown"></div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {

		var twoDaysFromNow = (new Date().getTime() / 1000) + (86400 * 2) + 1;

		new FlipDown( twoDaysFromNow,'flipdown-<?php echo $form_id; ?>',{theme: 'dark'} ).start();
	});
</script>
