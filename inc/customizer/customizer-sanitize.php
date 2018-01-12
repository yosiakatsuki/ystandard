<?php
/**
 * select,radio
 */
function ys_customizer_sanitize_select( $input, $setting ) {
	$input = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
/**
 * chekbox
 */
function ys_customizer_sanitize_checkbox( $value, $setting ) {
	if( 'option' == $setting->manager->get_setting( $setting->id )->type ) {
		return  ( ( isset( $value ) && true == $value ) ? true : '' );
	}
	return  ( ( isset( $value ) && true == $value ) ? true : false );
}
/**
 * number
 */
function ys_customizer_sanitize_number( $number, $setting ) {
	if ( 1 !== preg_match( '/^\d+$/', $number ) ) {
		return $setting->default;
	}
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}
/**
 * plain text
 */
function ys_customizer_sanitize_plain_text( $value ) {
	$value = wp_filter_nohtml_kses( $value );
	$value = preg_replace('/(?:\n|\r|\r\n)/', '', $value );
	return $value;
}