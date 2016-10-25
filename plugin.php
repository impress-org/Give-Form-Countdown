<?php
/**
 * Plugin Name: Give Donation Duration
 * Plugin URI:
 * Description: The most robust, flexible, and intuitive way to accept donations which limited by duration.
 * Author: WordImpress
 * Author URI: https://wordimpress.com
 * Version:
 * Text Domain: give-duration-donation
 * Domain Path: /languages
 * GitHub Plugin URI:
 *
 * Give Donation Duration is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Give Donation Duration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Give Donation Duration. If not, see <https://www.gnu.org/licenses/>.
 *
 * A Tribute to Open Source:
 *
 * "Open source software is software that can be freely used, changed, and shared (in modified or unmodified form) by anyone. Open
 * source software is made by many people, and distributed under licenses that comply with the Open Source Definition."
 *
 * -- The Open Source Initiative
 *
 * Give Donation Duration is a tribute to the spirit and philosophy of Open Source. We at WordImpress gladly embrace the Open Source philosophy both
 * in how Give Donation Duration itself was developed, and how we hope to see others build more from our code base.
 *
 * Give Donation Duration would not have been possible without the tireless efforts of WordPress and the surrounding Open Source projects and their talented developers. Thank you all for your contribution to WordPress.
 *
 * - The WordImpress Team
 *
 */


/**
 * Class Give_Donation_Duration
 *
 * @since 1.0
 */
final class Give_Donation_Duration {
	/**
	 * @since  1.0
	 * @access static
	 * @var Give_Donation_Duration $instance
	 */
	static private $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since  1.0
	 * @access private
	 * Give_Donation_Duration constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get instance.
	 *
	 * @since  1.0
	 * @access static
	 * @return Give_Donation_Duration|static
	 */
	static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Setup constants.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Donation_Duration
	 */
	public function set_constants() {
		// Global Params.
		define( 'LIMIT_DONATION_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

		return self::$instance;
	}

	/**
	 * Load files.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Donation_Duration
	 */
	public function load_files() {
		// Bootstrap.
		require_once dirname( __FILE__ ) . '/includes/admin/class-form-metabox-setting.php';

		// Load helper functions.
		require_once dirname( __FILE__ ) . '/includes/functions.php';

		// Load filters.
		require_once dirname( __FILE__ ) . '/includes/filters.php';

		return self::$instance;
	}
}


// Initiate plugin.
Give_Donation_Duration::get_instance()
                      ->set_constants()
                      ->load_files();