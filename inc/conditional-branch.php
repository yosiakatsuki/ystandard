<?php
//------------------------------------------------------------------------------
//
//	条件分岐(条件分岐のみys_is_~で作成)
//
//------------------------------------------------------------------------------
$amp = null;



//-----------------------------------------------
//	TOPページ判断（HOMEの1ページ目 or front-page）
//-----------------------------------------------
if (!function_exists( 'ys_is_toppage')) {
	function ys_is_toppage() {
		if((is_home() && !is_paged()) || is_front_page()){
			return true;
		} else {
			return false;
		}
	}
}



//-----------------------------------------------
//	モバイル判定（タブレットはPCとして判断）
//-----------------------------------------------
if( ! function_exists( 'ys_is_mobile' ) ) {
	function ys_is_mobile(){
		$ua = array(
						'^(?!.*iPad).*iPhone',	//iPadとiPhoneが混ざるUAがあるらしい
						'iPod',
						'Android.*Mobile',
						'Mobile.*Firefox',
						'Windows.*Phone',
						'blackberry',
						'dream',
						'CUPCAKE',
						'webOS',
						'incognito',
						'webmate'
						);
		$pattern = '/'.implode('|', $ua).'/i';
		return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
	}
}

//-----------------------------------------------
//	AMP判断
//-----------------------------------------------
if( ! function_exists( 'ys_is_amp' ) ) {
	function ys_is_amp(){
		global $amp;

		if($amp !== null){
			return $amp;
		}
		$param_amp = '';
		if(isset($_GET['amp'])){
			$param_amp = $_GET['amp'];
		}

		if($param_amp === '1' && ys_is_amp_enable()){
			$amp = true;
		} else {
			$amp = false;
		}

		return $amp;
	}
}


//-----------------------------------------------
//	AMPページにできるか判断
//-----------------------------------------------
if( ! function_exists( 'ys_is_amp_enable' ) ) {
	function ys_is_amp_enable(){

		global $post;
		$result = true;

		if(get_option('ys_amp_enable',0) == 0){
			return false;
		}

		if(is_single()) {
			$content = $post->post_content;

			// scriptタグの判断
			if(strpos($content,'<script>') !== false && get_option('ys_amp_del_script',0) != 1) {
				$result = false;
			}
			// style属性の判断
			if(preg_match('/style=".+?"/i',$content,$matches) === 1 && get_option('ys_amp_del_style',0) != 1) {
				$result = false;
			}

		} else {
			$result = false;
		}
		return $result;
	}
}




//-----------------------------------------------
//	OGP設定が揃っているか
//-----------------------------------------------
if( ! function_exists( 'ys_is_ogp_enable' ) ) {
	function ys_is_ogp_enable(){

		$ogp = ys_option_get_ogp();
		if($ogp['app_id'] != '' && $ogp['admins'] != '' && $ogp['image'] != ''){
			return true;
		}
		return false;
	}
}

?>