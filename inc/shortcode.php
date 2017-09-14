<?php
//------------------------------------------------------------------------------
//
//	ショートコード
//
//------------------------------------------------------------------------------



//-----------------------------------------------
//	この記事を書いた人
//-----------------------------------------------
function ys_shortcode_author($args) {
		// デフォルト値をセットして、変数に代入
		extract(shortcode_atts(array(
				'default_user_name' => false,
				'user_name' => false,
		), $args));

		$author_id = '';

		/**
		 * 個別投稿であれば投稿者を取得
		 */
		if( is_singular() ){
			$author_id = get_the_author_meta( 'ID' );
		}

		/**
		 * user_nameが設定されていればユーザー名からauthor_idを取得
		 */
		if( $user_name !== false ) {
			$user = get_user_by( 'slug', $user_name );
			if( $user ){
				$author_id = $user->ID;
			}
		}

		if( $author_id == '' && $default_user_name !== false ){
			$user = get_user_by( 'slug', $default_user_name );
			if( $user ){
				$author_id = $user->ID;
			}
		}
		if( $author_id == '' ){
			return '';
		}

		return ys_template_get_the_biography($author_id,true,'div');
}
add_shortcode('ys_author', 'ys_shortcode_author');



?>