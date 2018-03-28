<?php
/**
 * サニタイズ
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Select,radio
 *
 * @param string $input input.
 * @param object $setting setting.
 */
function ys_customizer_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
/**
 * Chekbox
 *
 * @param string $value value.
 * @param object $setting setting.
 */
function ys_customizer_sanitize_checkbox( $value, $setting ) {
	if ( 'option' == $setting->manager->get_setting( $setting->id )->type ) {
		return  ( ( isset( $value ) && true == $value ) ? true : '' );
	}
	return  ( ( isset( $value ) && true == $value ) ? true : false );
}
/**
 * Number
 *
 * @param string $number number.
 * @param object $setting setting.
 */
function ys_customizer_sanitize_number( $number, $setting ) {
	if ( 1 !== preg_match( '/^\d+$/', $number ) ) {
		return $setting->default;
	}
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;
	$min  = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	$max  = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}
/**
 * Plain text
 *
 * @param string $value value.
 */
function ys_customizer_sanitize_plain_text( $value ) {
	$value = wp_strip_all_tags( $value, true );
	return $value;
}