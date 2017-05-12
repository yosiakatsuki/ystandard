var ys_setTimeoutId = null
    ,script_append_wait = new Date()
    ,css_append_wait = new Date();

var script_append_wait_clear = function(){
  script_append_wait = new Date();
};
var css_append_wait_clear = function(){
  css_append_wait = new Date();
};


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
			js.onload = onloadfnc;
		}
		d.body.appendChild(js);
	}
};

//-----------------------------------------------
//スタイルシート読み込み
//-----------------------------------------------
function ys_sylesheet_load(d, id, href,onloadfnc) {

  onloadfnc = onloadfnc === undefined ? null : onloadfnc;

	var style;
	if (!d.getElementById(id)) {
		style = d.createElement('link');
		style.id = id;
		style.href = href;
		style.rel = 'stylesheet';
		if(onloadfnc != null){
			style.onload = onloadfnc;
		}
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
//追加スクリプトロード
//-----------------------------------------------
function ys_load_scripts_onload(d) {

  if (typeof(js_onload) != 'undefined' && js_onload != null){
    for(var i=0;i < js_onload.length;i=(i+1)|0) {
      ys_script_load(d,js_onload[i].id,js_onload[i].url,'');
    }
  }
};

//-----------------------------------------------
//追加スクリプトロード(スクロール発火)
//-----------------------------------------------
function ys_load_scripts_scroll() {

  if (typeof(js_lazyload) != 'undefined' && js_lazyload != null){
    // 一気に読み込むとカクつく恐れもあるので1つづつ読み込み
    if(js_lazyload.length > 0) {
      if(new Date() > script_append_wait){
        script_append_wait.setSeconds( script_append_wait.getSeconds() + 1 );
        ys_script_load(document,js_lazyload[0].id,js_lazyload[0].url,'',script_append_wait_clear);
        js_lazyload.shift();
      }
    } else {
      // 読み込めるものがなくなったらイベント削除
      window.removeEventListener( 'scroll' , ys_load_scripts_scroll, false);
    }
  }
};

//-----------------------------------------------
//追加CSSロード(スクロール発火)
//-----------------------------------------------
function ys_load_css_scroll() {

  if (typeof(css_lazyload) != 'undefined' && css_lazyload != null){
    // 一気に読み込むとカクつく恐れもあるので1つづつ読み込み
    if(css_lazyload.length > 0) {
      date = new Date();
      if(date> css_append_wait){
        css_append_wait.setSeconds( css_append_wait.getSeconds() + 1 );
        ys_sylesheet_load(document,css_lazyload[0].id,css_lazyload[0].url,css_append_wait_clear);
        css_lazyload.shift();
      }
    } else {
      // 読み込めるものがなくなったらイベント削除
      window.removeEventListener( 'scroll' , ys_load_css_scroll, false);
    }
  }
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
    d.getElementById('sidebar-wrapper').style.height = 'auto';
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
  // サイドバー高さ同期（safari対策）
  d.getElementById('sidebar-wrapper').style.height = d.getElementById('secondary').offsetHeight + 'px';
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
  // 追加スクリプトロード
  ys_load_scripts_onload(d);
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
	}, 100 ) ;
};


//-----------------------------------------------
//初期化処理
//-----------------------------------------------
ys_init();

//-----------------------------------------------
//イベント追加
//-----------------------------------------------
window.addEventListener( 'scroll' , ys_evt_scroll, false);
window.addEventListener( 'scroll' , ys_load_scripts_scroll, false);
window.addEventListener( 'scroll' , ys_load_css_scroll, false);
