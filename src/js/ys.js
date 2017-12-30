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

  if(type == 'reset'){
    document.getElementById('sidebar-wrapper').style.height = 'auto';
  }

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
