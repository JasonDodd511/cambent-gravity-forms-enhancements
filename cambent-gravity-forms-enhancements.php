<?php
/*
Plugin Name:        JD Gravity Forms Enhancements
Plugin URI:         https://github.com/JasonDodd511/gravity-forms-enhancements.git
Description:        Various snippets, updates and enhancements to Gravity Forms.
Version:            1.3.1
Author:             Jason Dodd
Author URI:
License: GPL2
GitHub Plugin URI: https://github.com/JasonDodd511/gravity-forms-enhancements.git
GitHub Branch:     master
*/

/*
// Opt out of the WordPress repo update functionality
add_filter( 'http_request_args', 'gfe_disable_wp_repo_update', 10, 2 );
function gfe_disable_wp_repo_update( $r, $url ) {
	if ( 0 === strpos( $url, 'https://api.wordpress.org/plugins/update-check/' ) ) {
		$my_plugin = plugin_basename( __FILE__ );
		$plugins = json_decode( $r['body']['plugins'], true );
		unset( $plugins['plugins'][$my_plugin] );
		unset( $plugins['active'][array_search( $my_plugin, $plugins['active'] )] );
		$r['body']['plugins'] = json_encode( $plugins );
	}
	return $r;
}
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
// Turns on the ability to hide labels in the GF form builder
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );