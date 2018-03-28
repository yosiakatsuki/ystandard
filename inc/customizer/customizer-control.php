<?php
/**
 * カスタマイザー項目追加用関数
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Add setting and control : color
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_color( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$args['id'],
			array(
				'description' => $args['description'],
				'label'       => $args['label'],
				'section'     => $args['section'],
				'settings'    => $args['id'],
				'priority'    => $args['priority'],
			)
		)
	);
}
/**
 * Add setting and control : image
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_image( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'type'      => $args['setting_type'],
			'transport' => $args['transport'],
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			$args['id'],
			array(
				'description' => $args['description'],
				'label'       => $args['label'],
				'section'     => $args['section'],
				'settings'    => $args['id'],
				'priority'    => $args['priority'],
			)
		)
	);
}
/**
 * Add setting and control : radio
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_radio( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'ys_customizer_sanitize_select',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'choices'     => $args['choices'],
			'description' => $args['description'],
			'label'       => $args['label'],
			'priority'    => $args['priority'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'radio',
		)
	);
}
/**
 * Add setting and control : radio
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_image_label_radio( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'ys_customizer_sanitize_select',
		)
	);
	$wp_customize->add_control(
		new YS_Customize_Image_Label_Radio_Control(
			$wp_customize,
			$args['id'],
			array(
				'choices'     => $args['choices'],
				'description' => $args['description'],
				'label'       => $args['label'],
				'priority'    => $args['priority'],
				'section'     => $args['section'],
				'settings'    => $args['id'],
				'type'        => 'radio',
			)
		)
	);
}
/**
 * Add setting and control : checkbox
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_checkbox( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'ys_customizer_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'checkbox',
			'priority'    => $args['priority'],
		)
	);
}
/**
 * Add setting and control : text
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_text( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'text',
			'priority'    => $args['priority'],
			'input_attrs' => $args['input_attrs'],
		)
	);
}
/**
 * Add setting and control : number
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_number( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'ys_customizer_sanitize_number',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'number',
			'priority'    => $args['priority'],
			'input_attrs' => $args['input_attrs'],
		)
	);
}
/**
 * Add setting and control : textarea
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_textarea( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => $args['sanitize_callback'],
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'textarea',
			'priority'    => $args['priority'],
		)
	);
}
/**
 * Add setting and control : textarea(no html)
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_plain_textarea( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'ys_customizer_sanitize_plain_text',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'textarea',
			'priority'    => $args['priority'],
		)
	);
}
/**
 * Add setting and control : url
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_setting_url( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'default'           => $args['default'],
			'type'              => $args['setting_type'],
			'transport'         => $args['transport'],
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'url',
			'priority'    => $args['priority'],
		)
	);
}
/**
 * Add setting and control : label
 *
 * @param WP_Customize_Manager $wp_customize wp_customize.
 * @param array                $args オプション.
 */
function ys_customizer_add_label( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
		$args['id'],
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		$args['id'],
		array(
			'description' => $args['description'],
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => $args['id'],
			'type'        => 'hidden',
			'priority'    => $args['priority'],
		)
	);
}

/**
 * デフォルト値
 *
 * @param array $args オプション.
 */
function ys_customizer_parse_args( $args ) {
	$defaults = array(
		'setting_type'      => 'option',
		'description'       => '',
		'transport'         => 'refresh',
		'priority'          => 1,
		'default'           => '',
		'input_attrs'       => array(),
		'sanitize_callback' => '',
	);
	return wp_parse_args( $args, $defaults );
}