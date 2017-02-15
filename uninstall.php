<?php
/**
 * Uninstall Give Donation Duration
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
		'donation-duration-close-form',
		'donation-duration-by',
		'donation-duration-in-number-of-days',
		'donation-duration-on-date',
		'donation-duration-on-time',
		'donation-duration-message'
	)
);