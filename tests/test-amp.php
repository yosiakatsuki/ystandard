<?php
/**
 * Class AmpTest
 *
 * @package ystandard
 */

/**
 * AMP用テスト
 */
class AmpTest extends WP_UnitTestCase {
	/**
	 * 投稿データをセットする
	 *
	 * @param array $arg
	 *
	 * @return int $post_id
	 */
	public function setup_postdata( $args = null ) {
		global $post;
		global $ys_amp;
		/**
		 * 記事作成
		 */
		$post_id = $this->factory->post->create( $args );
		$post    = get_post( $post_id );
		$this->go_to( add_query_arg( 'amp', '1', get_the_permalink( $post_id ) ) );
		setup_postdata( $post );
		/**
		 * AMP設定ON
		 */
		update_option( 'ys_amp_enable', true );
		/**
		 * キャッシュのクリア
		 */
		$ys_amp = null;

		return $post_id;
	}

	/**
	 * the_contentフィルターを通す
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	function apply_the_content( $content ) {
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

	/**
	 * テスト用コンテンツ
	 *
	 * @return string
	 */
	function get_test_content() {
		return <<<EOD
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

https://twitter.com/yosiakatsuki/status/969776900804694016

<blockquote class="twitter-tweet" data-lang="ja"><p lang="ja" dir="ltr">ブログ更新!! / Visual Studio Codeで複数フォルダをまとめて開く方法 <a href="https://t.co/KbjvFVadeM">https://t.co/KbjvFVadeM</a></p>&mdash; よしあかつき@よっひーと呼ばれてます (@yosiakatsuki) <a href="https://twitter.com/yosiakatsuki/status/969776900804694016?ref_src=twsrc%5Etfw">2018年3月3日</a></blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/BkWdoU_hj0b/" data-instgrm-version="8" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:8px;"> <div style=" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;"> <div style=" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"></div></div> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://www.instagram.com/p/BkWdoU_hj0b/" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">我が家には4年前の教訓という強力な武器がある</a></p> <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="https://www.instagram.com/yosiakatsuki/" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> よっひー</a>さん(@yosiakatsuki)がシェアした投稿 - <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2018-01-22T23:06:38+00:00"> 1月 22, 2018 at 3:06午後 PST</time></p></div></blockquote> <script async defer src="//www.instagram.com/embed.js"></script>

https://www.youtube.com/watch?v=c4Pi9GLni2U

<iframe width="560" height="315" src="https://www.youtube.com/embed/c4Pi9GLni2U" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
EOD;
	}

	/**
	 * 予想結果
	 *
	 * @return string
	 */
	function get_test_expected() {
		$result = <<<EOD
<p><amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" allowfullscreen></amp-iframe></p>
<p><amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" allowfullscreen></amp-iframe></p>
<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>
<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>
<amp-instagram layout="responsive" data-shortcode="BkWdoU_hj0b" width="400" height="400" ></amp-instagram>
<amp-youtube layout="responsive" data-videoid="c4Pi9GLni2U" width="480" height="270"></amp-youtube>
<amp-youtube layout="responsive" data-videoid="c4Pi9GLni2U" width="480" height="270"></amp-youtube>
EOD;

		return $result . PHP_EOL;
	}

	/**
	 * ys_is_ampテスト
	 */
	function test_is_amp() {
		$post_id = $this->setup_postdata();
		$this->assertTrue( is_single() );
		$this->assertTrue( ys_is_amp() );
	}

	/**
	 * ys_is_ampテスト(AMP以外)
	 */
	function test_is_amp_normal_page() {
		$post_id = $this->setup_postdata();
		// 通常ページ
		$this->go_to( get_the_permalink( $post_id ) );
		$this->assertFalse( ys_is_amp() );
	}

	/**
	 * ys_is_ampテスト(固定ページ)
	 */
	function test_is_amp_page() {
		$post_id = $this->setup_postdata( array( 'post_type' => 'page' ) );
		$this->assertTrue( is_page() );
		$this->assertFalse( ys_is_amp() );
	}

	/**
	 * amp-img変換テスト
	 */
	function test_amp_img_1() {
		$post_id = $this->setup_postdata();
		/**
		 * スラッシュ付き
		 */
		$content = '<img src="https://amp-test.test/image.png" alt="amp img test" />';
		$content = ys_amp_convert_image( $content );
		$this->assertSame(
			'<amp-img layout="responsive" src="https://amp-test.test/image.png" alt="amp img test" ></amp-img>',
			$content
		);
	}

	/**
	 * amp-img変換テスト
	 */
	function test_amp_img_2() {
		$post_id = $this->setup_postdata();
		/**
		 * スラッシュなし
		 */
		$content = '<img src="https://amp-test.test/image.png" alt="amp img test" >';
		$content = ys_amp_convert_image( $content );
		$this->assertSame(
			'<amp-img layout="responsive" src="https://amp-test.test/image.png" alt="amp img test" ></amp-img>',
			$content
		);
	}

	/**
	 * amp-img変換テスト
	 */
	function test_amp_img_3() {
		$post_id = $this->setup_postdata();
		/**
		 * style指定
		 */
		$content = '<img src="https://amp-test.test/image.png" alt="amp img test" style="margin:0;" >';
		$content = ys_amp_convert_image( $content );
		$this->assertSame(
			'<amp-img layout="responsive" src="https://amp-test.test/image.png" alt="amp img test" style="margin:0;" ></amp-img>',
			$content
		);
	}
	/**
	 * amp-img変換テスト
	 */
	function test_amp_img_4() {
		$post_id = $this->setup_postdata();
		/**
		 * style指定
		 */
		$content = '<img src="https://amp-test.test/image.png" alt="amp img test" style="margin:0 !important;" >';
		$content = ys_amp_convert_image( $content );
		$this->assertSame(
			'<amp-img layout="responsive" src="https://amp-test.test/image.png" alt="amp img test" style="margin:0 ;" ></amp-img>',
			$content
		);
	}
	/**
	 * amp-img変換テスト
	 */
	function test_amp_img_5() {
		$post_id = $this->setup_postdata();
		/**
		 * style指定
		 */
		$content = '<img src="https://amp-test.test/image.png" alt="amp img test" style="margin:0 !important;padding:0 !important;" >';
		$content = ys_amp_convert_image( $content );
		$this->assertSame(
			'<amp-img layout="responsive" src="https://amp-test.test/image.png" alt="amp img test" style="margin:0 ;padding:0 ;" ></amp-img>',
			$content
		);
	}

	/**
	 * iframe変換テスト
	 */
	function test_amp_iframe() {
		$content = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_iframe( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<p><amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" allowfullscreen></amp-iframe></p>
<p><amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.415846697843!2d139.76777057596865!3d35.68114599502427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1520116570877" width="600" height="450" frameborder="0" allowfullscreen></amp-iframe></p>' . PHP_EOL,
			$content
		);
	}

	/**
	 * Twitter変換テスト oembed
	 */
	function test_amp_twitter_oembed() {
		$content = 'https://twitter.com/yosiakatsuki/status/969776900804694016

https://twitter.com/yosiakatsuki/status/969776900804694016';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_twitter( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>
<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>' . PHP_EOL,
			$content
		);
	}

	/**
	 * Twitter変換テスト 直接埋め込み
	 */
	function test_amp_twitter_embedded() {
		$content = '<blockquote class="twitter-tweet" data-lang="ja"><p lang="ja" dir="ltr">ブログ更新!! / Visual Studio Codeで複数フォルダをまとめて開く方法 <a href="https://t.co/KbjvFVadeM">https://t.co/KbjvFVadeM</a></p>&mdash; よしあかつき@よっひーと呼ばれてます (@yosiakatsuki) <a href="https://twitter.com/yosiakatsuki/status/969776900804694016?ref_src=twsrc%5Etfw">2018年3月3日</a></blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<blockquote class="twitter-tweet" data-lang="ja"><p lang="ja" dir="ltr">ブログ更新!! / Visual Studio Codeで複数フォルダをまとめて開く方法 <a href="https://t.co/KbjvFVadeM">https://t.co/KbjvFVadeM</a></p>&mdash; よしあかつき@よっひーと呼ばれてます (@yosiakatsuki) <a href="https://twitter.com/yosiakatsuki/status/969776900804694016?ref_src=twsrc%5Etfw">2018年3月3日</a></blockquote>
		<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_twitter( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>
<amp-twitter width=486 height=657 layout="responsive" data-tweetid="969776900804694016"></amp-twitter>' . PHP_EOL,
			$content
		);
	}

	/**
	 * Instagram変換テスト 直接埋め込み
	 */
	function test_amp_instagram_embedded() {
		$content = '<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/BkWdoU_hj0b/" data-instgrm-version="8" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:8px;"> <div style=" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;"> <div style=" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"></div></div> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://www.instagram.com/p/BkWdoU_hj0b/" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">我が家には4年前の教訓という強力な武器がある</a></p> <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="https://www.instagram.com/yosiakatsuki/" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> よっひー</a>さん(@yosiakatsuki)がシェアした投稿 - <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2018-01-22T23:06:38+00:00"> 1月 22, 2018 at 3:06午後 PST</time></p></div></blockquote> <script async defer src="//www.instagram.com/embed.js"></script>';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_instagram( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<amp-instagram layout="responsive" data-shortcode="BkWdoU_hj0b" width="400" height="400" ></amp-instagram>' . PHP_EOL,
			$content
		);
	}

	/**
	 * youtube変換テスト oembed
	 */
	function test_amp_youtube_oembed() {
		$content = 'https://www.youtube.com/watch?v=c4Pi9GLni2U';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_youtube( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<amp-youtube layout="responsive" data-videoid="c4Pi9GLni2U" width="480" height="270"></amp-youtube>' . PHP_EOL,
			$content
		);
	}

	/**
	 * youtube変換テスト 直接埋め込み
	 */
	function test_amp_youtube_embedded() {
		$content = '<iframe width="560" height="315" src="https://www.youtube.com/embed/c4Pi9GLni2U" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
		$content = $this->apply_the_content( $content );
		$content = ys_amp_convert_youtube( $content );
		$content = ys_amp_delete_script( $content );
		$this->assertSame(
			'<amp-youtube layout="responsive" data-videoid="c4Pi9GLni2U" width="480" height="270"></amp-youtube>' . PHP_EOL,
			$content
		);
	}

	/**
	 * AMPおまとめテスト
	 */
	function test_amp_convert() {
		$content = $this->apply_the_content( $this->get_test_content() );
		$content = ys_amp_convert_all( $content );
		$this->assertSame(
			$this->get_test_expected(),
			$content
		);
	}
}