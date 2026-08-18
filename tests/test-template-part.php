<?php
/**
 * テンプレートパーツのテスト
 *
 * @package ystandard
 */

/**
 * Class TemplatePartTest
 */
class TemplatePartTest extends WP_UnitTestCase {

	/**
	 * 名前付きテンプレートへ引数を渡せることを確認
	 */
	public function test_get_template_part_loads_named_template_with_args() {
		ob_start();
		get_template_part(
			'tests/data/template-part/sample',
			'named',
			[ 'message' => 'named template' ]
		);
		$output = ob_get_clean();

		$this->assertSame( 'named template', $output );
	}

	/**
	 * 名前付きテンプレートがない場合に汎用テンプレートを読み込むことを確認
	 */
	public function test_get_template_part_falls_back_to_generic_template() {
		ob_start();
		get_template_part( 'tests/data/template-part/sample', 'missing' );
		$output = ob_get_clean();

		$this->assertSame( 'generic', $output );
	}

	/**
	 * テンプレートがない場合に出力せず失敗を返すことを確認
	 */
	public function test_get_template_part_returns_false_when_template_is_missing() {
		ob_start();
		$result = get_template_part( 'tests/data/template-part/missing' );
		$output = ob_get_clean();

		$this->assertFalse( $result );
		$this->assertSame( '', $output );
	}

	/**
	 * 廃止した独自APIが読み込まれていないことを確認
	 */
	public function test_legacy_template_part_api_is_removed() {
		$this->assertFalse( function_exists( 'ys_get_template_part' ) );
		$this->assertFalse( class_exists( '\\ystandard\\Template' ) );
	}
}
