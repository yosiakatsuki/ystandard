var ys_setTimeoutId = null;

//-----------------------------------------------
//スクリプト読み込み
//-----------------------------------------------
function ys_script_load(d, id, src,deps,onloadfnc) {

	onloadfnc = onloadfnc === undefined ? null : onloadfnc;

	if(deps !== ''){
		if (!d.getElementById(deps)) {
			return;
		}
	}

	var js;
	if (!d.getElementById(id)) {
		js = d.createElement('script');
		js.id = id;
		js.src = src;
		js.async = true;
		if(onloadfnc != null){
			js.onloadfnc = onloadfnc;
		}
		d.body.appendChild(js);
	}
};

//-----------------------------------------------
//スタイルシート読み込み
//-----------------------------------------------
function ys_sylesheet_load(d, id, href) {
	var style;
	if (!d.getElementById(id)) {
		style = d.createElement('link');
		style.id = id;
		style.href = href;
		style.media = 'all';
		style.rel = 'stylesheet';
		d.getElementsByTagName('head')[0].appendChild(style);
	}
};




//-----------------------------------------------
//スクロール量取得
//-----------------------------------------------
function ys_get_scroll(d) {
	return d.documentElement.scrollTop || d.body.scrollTop;
};


//-----------------------------------------------
//SNS関連スクリプトロード
//-----------------------------------------------
function ys_load_sns_scripts(d) {

	// twitter
	ys_script_load(d,'twitter-wjs','//platform.twitter.com/widgets.js','');
	// facebook
	ys_script_load(d,'facebook-jssdk','//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8','');
};

//-----------------------------------------------
//サイドバー追従
//-----------------------------------------------
function ys_fixed_sidebar(d) {
  // 判断
  var elmprimary = d.getElementById('primary')
      ,elmsidebar = d.getElementById('secondary')
      ,elmfixside = d.getElementById('sidebar-fixed');
  // サイドバーの存在確認
  if(elmsidebar == null || elmfixside == null){
    return;
  }
  // サイドバーが下に落ちてないか確認
  if(elmprimary.offsetLeft + elmprimary.offsetWidth > elmsidebar.offsetLeft) {
    ys_set_styles_fixed_sidebar(elmfixside,'reset',0)
    return;
  }
  //メインコンテンツのほうが長いか確認
  var elmmain = d.getElementById('main')
      ,elmrightside = d.getElementById('sidebar-right')
      ,sidebarheight = elmfixside.offsetHeight
      ,siderightheight = 0;
  if(elmrightside != null){
    sidebarheight = elmrightside.offsetHeight + elmfixside.offsetHeight;
    siderightheight = elmrightside.offsetHeight;
  }
  if(elmmain.offsetHeight < sidebarheight ) {
    ys_set_styles_fixed_sidebar(elmfixside,'reset',0)
    return;
  }
  // ----
  var siderect = elmsidebar.getBoundingClientRect()
      ,sidebartop = siderect.top + window.pageYOffset
      ,scroll = ys_get_scroll(d)
      ,fixmargin = 20;
  if(scroll + elmfixside.offsetHeight + fixmargin > sidebartop + elmsidebar.offsetHeight ) {
    // absolute　下部固定
    ys_set_styles_fixed_sidebar(elmfixside,'absolute',0);
  } else if(scroll + fixmargin > sidebartop + siderightheight) {
    // fixed
    ys_set_styles_fixed_sidebar(elmfixside,'fixed',fixmargin);
  } else {
    // relative
    ys_set_styles_fixed_sidebar(elmfixside,'relative',0);
  }
}

//-----------------------------------------------
//サイドバースタイル設定
//-----------------------------------------------
function ys_set_styles_fixed_sidebar(elm,type,topmargin) {
  if(elm == null){
    return;
  }
  // reset
  var position = 'relative'
      ,top = ''
      ,left = ''
      ,bottom = ''
      ,width = ''
      ,elmbcr = null;

  if(type == 'absolute'){
    elmbcr = elm.getBoundingClientRect()
    position = 'absolute';
    left = '0px';
    bottom = '0px';
    width = elmbcr.width + 'px';
  } else if(type == 'fixed'){
    elmbcr = elm.getBoundingClientRect()
    position = 'fixed';
    top = topmargin + 'px';
    left = elmbcr.left + 'px';
    width = elmbcr.width + 'px';
  }
  elm.style.position = position;
  elm.style.top = top;
  elm.style.left = left;
  elm.style.bottom = bottom;
  elm.style.width = width;
}



//-----------------------------------------------
//スクロール時に実行する処理
//-----------------------------------------------
function ys_scroll_main(d) {

  // サイドバー追従
  ys_fixed_sidebar(d);
};

//-----------------------------------------------
//ページ読み込み完了時の処理
//-----------------------------------------------
function ys_init() {
	var d = document;
  // SNS関連スクリプト
	ys_load_sns_scripts(d);
};

//-----------------------------------------------
//スクロールイベントでの処理
//-----------------------------------------------
function ys_evt_scroll() {
	if( ys_setTimeoutId ) {
		return false ;
	}

	ys_setTimeoutId = setTimeout( function() {
		ys_scroll_main(document);

		ys_setTimeoutId = null ;
	}, 300 ) ;
};

//-----------------------------------------------
//イベント追加
//-----------------------------------------------
window.onload = ys_init;
window.addEventListener( "scroll", ys_evt_scroll);
