<?php
/*
Plugin Name:        Cambent Gravity Forms Enhancements
Plugin URI:         https://github.com/JasonDodd511/cambent-gravity-forms-enhancements
Description:        Various snippets, updates and enhancements to Gravity Forms.
Version:            1.3.6
Author:             Jason Dodd
Author URI:
License: GPL2
GitHub Plugin URI: https://github.com/JasonDodd511/cambent-gravity-forms-enhancements
GitHub Branch:     master
*/

/**
 * Shortcode: Get Remaining Entries
 *
 * Displays the number of remaining entries for forms that have entry
 * limits set
 *
 * @param array $atts   Arguments passed to the shortcode. Accepts 'id',
 *                      'format'.  For format, use 'decimal' to change
 *                      thousands separator to a decimal, otherwise will
 *                      be a comma.
 * @return int|null     What is displayed to the user. Number of entries.
 *                      Null if 'id' isn't supplied or isn't valid.
 */
function gfe_get_remaining_entries( $atts ) {
	extract( shortcode_atts( array(
		'id' => false,
		'format' => false
	), $atts ) );
	if( ! $id ) {
		return '';
	}
	$form = RGFormsModel::get_form_meta( $id );
	if( ! rgar( $form, 'limitEntries' ) || ! rgar( $form, 'limitEntriesCount' ) ){
		return '';
	}
	$entry_count = RGFormsModel::get_lead_count( $form['id'], '', null, null, null, null, 'active' );
	$entries_left = rgar( $form, 'limitEntriesCount' ) - $entry_count;
	$output = $entries_left;
	if( $format ) {
		$format = $format == 'decimal' ? '.' : ',';
		$output = number_format( $entries_left, 0, false, $format );
	}
	return $entries_left > 0 ? $output : 0;
}
add_shortcode( 'gfe_entries_remaining', 'gfe_get_remaining_entries' );

/*
 * Changes to various settings within Gravity forms
 */
// Turns on the ability to hide labels in the GF form builder - Isn't that great?
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );