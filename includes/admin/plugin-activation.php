<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Give Display Donors Activation Banner
 *
 * Includes and initializes Give activation banner class.
 *
 * @since 1.0
 */

add_action( 'admin_init', 'gdd_activation_banner' );
function gdd_activation_banner() {

	// Check for if give plugin activate or not.
	$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false;

	// Check to see if Give is activated, if it isn't deactivate and show a banner
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! $is_give_active ) {

		add_action( 'admin_notices', 'gdd_inactive_notice' );

		// Don't let this plugin activate
		deactivate_plugins( GDD_PLUGIN_BASENAME );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		return false;

	}

	// Minimum Give version required for this plugin to work.
	if ( version_compare( GIVE_VERSION, GDD_MIN_GIVE_VER, '<' ) ) {

		add_action( 'admin_notices', 'gdd_version_notice' );

		// Don't let this plugin activate.
		deactivate_plugins( GDD_PLUGIN_BASENAME );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		return false;

	}

	// Check for activation banner inclusion.
	if ( ! class_exists( 'Give_Addon_Activation_Banner' )
	     && file_exists( GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php' )
	) {

		include GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';

		// Only runs on admin.
		$args = array(
			'file'              => __FILE__,
			'name'              => esc_html__( 'Give Donation Duration', 'give-donation-duration' ),
			'version'           => GDD_PLUGIN_VERSION,
			// 'settings_url'      => '',
			'documentation_url' => 'https://github.com/WordImpress/Give-Donation-Duration/',
			'support_url'       => 'https://github.com/WordImpress/Give-Donation-Duration/issues',
			'testing'           => false,// Never leave true.
		);

		new Give_Addon_Activation_Banner( $args );

	}

	return false;

}


/**
 * Notice for No Core Activation
 *
 * @since 1.3.3
 */
function gdd_inactive_notice() {
	echo '<div class="error"><p>' . __( '<strong>Activation Error:</strong> You must have the <a href="https://givewp.com/" target="_blank">Give</a> plugin installed and activated for the Donation Duration Add-on to activate.', 'give-donation-duration' ) . '</p></div>';
}

/**
 * Notice for min. version violation.
 *
 * @since 1.3.3
 */
function gdd_version_notice() {
	echo '<div class="error"><p>' . sprintf( __( '<strong>Activation Error:</strong> You must have <a href="%1$s" target="_blank">Give</a> minimum version %2$s for the Donation Duration Add-on to activate.', 'give-donation-duration' ), 'https://givewp.com', GDD_MIN_GIVE_VER ) . '</p></div>';
}
