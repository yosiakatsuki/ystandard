<?php
//------------------------------------------------------------------------------
//
//	条件分岐(条件分岐のみys_is_~で作成)
//
//------------------------------------------------------------------------------




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
		return false;
	}
}


//-----------------------------------------------
//	AMPページにできるか判断
//-----------------------------------------------
if( ! function_exists( 'ys_is_amp_possible' ) ) {
	function ys_is_amp_possible(){
		return false;
	}
}
?>