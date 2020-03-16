<?php
/**
 * SNS関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class SNS
 *
 * @package ystandard
 */
class SNS {

	/**
	 * サイト内設定で使用するSNSのリスト
	 *
	 * @return array
	 */
	public static function get_sns_icons() {
		return apply_filters(
			'ys_get_sns_icons',
			[
				'twitter'   => [
					'class'      => 'twitter',
					'option_key' => 'twitter',
					'icon'       => 'fab fa-twitter',
					'color'      => 'twitter',
					'title'      => 'twitter',
					'label'      => 'Twitter',
				],
				'facebook'  => [
					'class'      => 'facebook',
					'option_key' => 'facebook',
					'icon'       => 'fab fa-facebook-f',
					'color'      => 'facebook',
					'title'      => 'facebook',
					'label'      => 'Facebook',
				],
				'instagram' => [
					'class'      => 'instagram',
					'option_key' => 'instagram',
					'icon'       => 'fab fa-instagram',
					'color'      => 'instagram',
					'title'      => 'instagram',
					'label'      => 'Instagram',
				],
				'tumblr'    => [
					'class'      => 'tumblr',
					'option_key' => 'tumblr',
					'icon'       => 'fab fa-tumblr',
					'color'      => 'tumblr',
					'title'      => 'tumblr',
					'label'      => 'Tumblr',
				],
				'youtube'   => [
					'class'      => 'youtube',
					'option_key' => 'youtube',
					'icon'       => 'fab fa-youtube',
					'color'      => 'youtube-play',
					'title'      => 'youtube',
					'label'      => 'YouTube',
				],
				'github'    => [
					'class'      => 'github',
					'option_key' => 'github',
					'icon'       => 'fab fa-github',
					'color'      => 'github',
					'title'      => 'github',
					'label'      => 'GitHub',
				],
				'pinterest' => [
					'class'      => 'pinterest',
					'option_key' => 'pinterest',
					'icon'       => 'fab fa-pinterest-p',
					'color'      => 'pinterest',
					'title'      => 'pinterest',
					'label'      => 'Pinterest',
				],
				'linkedin'  => [
					'class'      => 'linkedin',
					'option_key' => 'linkedin',
					'icon'       => 'fab fa-linkedin-in',
					'color'      => 'linkedin',
					'title'      => 'linkedin',
					'label'      => 'LinkedIn',
				],
				'amazon'    => [
					'class'      => 'amazon',
					'option_key' => 'amazon',
					'icon'       => 'fab fa-amazon',
					'color'      => 'amazon',
					'title'      => 'amazon',
					'label'      => 'Amazon',
				],
			]
		);
	}

}
