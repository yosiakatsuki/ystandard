<?php
/**
 * Class Test_Header_Thumbnail
 *
 * @package ystandard
 */

/**
 * Class HeaderThumbnailTest
 */
class Header_Thumbnail_Test extends WP_UnitTestCase {

	private function remove_nl_tab( $text ) {
		$text_helper = new \ystandard\helper\Text();

		return $text_helper->remove_tab( $text_helper->remove_nl( $text ) );
	}

	private function create_test_image( $file, $post_id ) {

		return $this->factory->attachment->create_upload_object(
			$file,
			$post_id
		);
	}

	function test_home_thumbnail() {
		$archive = new \ystandard\Archive();
		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame( '', ob_get_clean() );

		$post_id = $this->factory->post->create(
			[ 'post_type' => 'page', ]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $post_id );
		$this->go_to( get_permalink( $post_id ) );

		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame( '', ob_get_clean() );

		$attachment_id = $this->create_test_image(
			DIR_TEST_DATA . '/images/test.png',
			$post_id
		);
		set_post_thumbnail( $post_id, $attachment_id );

		$expected = get_the_post_thumbnail(
			$post_id,
			'post-thumbnail',
			[
				'id'    => 'site-header-thumbnail__image',
				'class' => 'site-header-thumbnail__image',
				'alt'   => get_the_title( $post_id ),
			]
		);

		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame(
			"<figure class=\"site-header-thumbnail\">${expected}</figure>",
			$this->remove_nl_tab( ob_get_clean() )
		);
	}
}
