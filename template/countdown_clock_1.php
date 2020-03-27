<?php
/**
 * Filter the countdown clock time constraints.
 *
 * @since 1.0
 */

$form_meta = get_post_meta( $form_id );
$theme = get_post_meta( $form_id, 'form-countdown-theme', true );
$custom_styles = gfc_output_custom_color_scheme( $form_id );

$date = get_post_meta( $form_id, 'form-countdown-on-date', true ) . ' ' . get_post_meta($form_id, 'form-countdown-on-time', true );
$closeaction = get_post_meta( $form_id, 'form-countdown-achieved-action', true );

//Create a new DateTime object using the date string above.
$dateTime = new DateTime( $date );
 
//Format it into a Unix timestamp.
$timestamp = $dateTime->format('U');

if ( $theme == 'custom' ) {
	echo $custom_styles;
}

?>
<div id="gfc-clock-<?php echo esc_attr( $form_id ); ?>-wrap" class="gfc-clock-wrap">
	<div id="flipdown-<?php echo esc_attr( $form_id ); ?>" class="flipdown"></div>
</div>
<div class="gfc-closed-message" style="display:none; margin: 1em 0; overflow:hidden;"><?php echo wp_kses_post( get_post_meta( $form_id, 'form-countdown-message', true ) ); ?></div>

<script>
	document.addEventListener('DOMContentLoaded', () => {

		var twoDaysFromNow = ( new Date().getTime() / 1000 ) + (86400 * 2) + 1,
			fiveSeconds = ( new Date().getTime() / 1000 ) + 5,
			closedMessage = 
			formwrap = document.getElementById("give-form-<?php echo esc_attr( $form_id ); ?>-wrap");
		
		//5 second countdown for testing only
		var flipdown = new FlipDown(fiveSeconds,'flipdown-<?php echo esc_attr( $form_id ); ?>', {theme: '<?php echo esc_attr( $theme ); ?>'})

		//var flipdown = new FlipDown(<?php echo esc_attr( $timestamp ); ?>,'flipdown-<?php echo esc_attr( $form_id ); ?>', {theme: '<?php echo esc_attr( $theme ); ?>'})

		.start()

		<?php switch( $closeaction ) {
			case 'close_form': 
				esc_js(print_r(".ifEnded(() => {jQuery(formwrap).fadeOut(500);jQuery('.gfc-closed-message').delay(500).fadeIn(500);});"));
				break;
			case 'message_and_form': 
				esc_js(print_r(".ifEnded(() => {jQuery('.gfc-closed-message').fadeIn(1000);});"));
				break;
			case 'hide_countdown': 
				esc_js(print_r(".ifEnded(() => {jQuery('.gfc-clock-wrap').fadeOut(1000);});"));
				break;
			case 'hide_countdown_show_message': 
				esc_js(print_r(".ifEnded(() => {jQuery('.gfc-closed-message').delay(500).fadeIn(500);jQuery('.gfc-clock-wrap').fadeOut(500);});"));
				break;
			case 'hide_countdown_and_form_show_message': 
				esc_js(print_r(".ifEnded(() => {jQuery(formwrap).fadeOut(500);jQuery('.gfc-clock-wrap').fadeOut(500);jQuery('.gfc-closed-message').delay(500).fadeIn(500);});"));
				break;
		} ?>
		
	});

	function hideGiveForm() {
		var formwrap = document.getElementById("give-form-<?php echo esc_attr( $form_id ); ?>-wrap");
		if (formwrap.style.display === "block") {
			formwrap.style.display = "none";
		} else {
			formwrap.style.display = "block";
		}
	}
</script>
