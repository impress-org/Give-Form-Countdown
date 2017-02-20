<?php
/**
 * Uninstall Give Form Countdown
 *
 * @package     Give
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Delete all meta data created by plugin.
$wpdb->query(
	$wpdb->prepare(
		"
			DELETE FROM $wpdb->postmeta
			WHERE meta_key='%s'
			OR meta_key='%s'
			OR meta_key='%s'
			OR meta_key='%s'
			OR meta_key='%s'
			OR meta_key='%s'
			",
		'form-countdown-close-form',
		'form-countdown-by',
		'form-countdown-in-number-of-days',
		'form-countdown-on-date',
		'form-countdown-on-time',
		'form-countdown-message'
	)
);
