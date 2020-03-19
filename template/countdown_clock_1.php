<?php
/**
 * Filter the countdown clock time constraints.
 *
 * @since 1.0
 * @todo  : Refactor javascript code.
 * @todo  : Add months time constraint.
 */

$form_meta = get_post_meta($form_id);
$theme = $form_meta['form-countdown-theme'][0];

$date = $form_meta['form-countdown-on-date'][0] . ' ' . $form_meta['form-countdown-on-time'][0];
 
//Create a new DateTime object using the date string above.
$dateTime = new DateTime($date);
 
//Format it into a Unix timestamp.
$timestamp = $dateTime->format('U');

?>
<div id="gfc-clock-<?php echo $form_id; ?>-wrap" class="gfc-clock-wrap">
	<div id="flipdown-<?php echo $form_id; ?>" class="flipdown"></div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {

		var twoDaysFromNow = (new Date().getTime() / 1000) + (86400 * 2) + 1;
		var fiveSeconds = (new Date().getTime() / 1000) + 5;
		
		//Testing
		var flipdown = new FlipDown(fiveSeconds,'flipdown-<?php echo $form_id; ?>', {theme: '<?php echo $theme; ?>'})

		//var flipdown = new FlipDown(<?php echo $timestamp; ?>,'flipdown-<?php echo $form_id; ?>', {theme: '<?php echo $theme; ?>'})

		.start()

		// Do something when the countdown ends
		.ifEnded(() => {
			alert('The countdown has ended!');
  		});
	});
</script>
