<?php
/**
 * Add setting and control : color
 */
function ys_customizer_add_setting_color( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
									$args['id'],
									array(
										'default'           => $args['default'],
										'type'              => $args['setting_type'],
										'transport'         => $args['transport'],
										'sanitize_callback' => 'sanitize_hex_color'
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
 */
function ys_customizer_add_setting_image( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
									$args['id'],
									array(
										'type'              => $args['setting_type'],
										'transport'         => $args['transport'],
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
 */
function ys_customizer_add_setting_radio( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'ys_customizer_sanitize_select'
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
 */
function ys_customizer_add_setting_image_label_radio( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'ys_customizer_sanitize_select'
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
 */
function ys_customizer_add_setting_checkbox( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'ys_customizer_sanitize_checkbox'
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
 */
function ys_customizer_add_setting_text( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'sanitize_text_field'
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
							'input_attrs' => $args['input_attrs']
						)
					);
}
/**
 * Add setting and control : number
 */
function ys_customizer_add_setting_number( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'ys_customizer_sanitize_number'
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
							'input_attrs' => $args['input_attrs']
						)
					);
}
/**
 * Add setting and control : textarea
 */
function ys_customizer_add_setting_textarea( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'esc_textarea'
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
 */
function ys_customizer_add_setting_plain_textarea( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'ys_customizer_sanitize_plain_text'
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
 */
function ys_customizer_add_setting_url( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						$args['id'],
						array(
							'default'           => $args['default'],
							'type'              => $args['setting_type'],
							'transport'         => $args['transport'],
							'sanitize_callback' => 'esc_url_raw'
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
 */
function ys_customizer_add_label( $wp_customize, $args ) {
	$args = ys_customizer_parse_args( $args );
	$wp_customize->add_setting(
						'ys_customizer_label',
						array(
							'type'              => 'option',
							'transport'         => 'postMessage',
						)
					);
	$wp_customize->add_control(
						'ys_customizer_label',
						array(
							'description' => $args['description'],
							'label'       => $args['label'],
							'section'     => $args['section'],
							'settings'    => 'ys_customizer_label',
							'type'        => 'hidden',
							'priority'    => $args['priority'],
						)
					);
}

/**
 * デフォルト値
 */
function ys_customizer_parse_args( $args ) {
	$defaults = array(
		'setting_type' => 'option',
		'description'  => '',
		'transport'    => 'refresh',
		'priority'     => 1,
		'default'      => '',
		'input_attrs'  => array()
	);
	return wp_parse_args( $args, $defaults );
}