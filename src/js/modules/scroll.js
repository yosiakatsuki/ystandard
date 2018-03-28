import YSElement from './element'

class YSScroll {
  /**
   * スクロール量の取得
   */
  static getScroll () {
    return document.documentElement.scrollTop || document.body.scrollTop
  }

  /**
   * スクロール量以上かどうか
   */
  static isOverScroll (pos) {
    return YSScroll.getScroll() >= pos
  }
  /**
   * スクロール量未満かどうか
   */
  static isUnderScroll (pos) {
    return YSScroll.getScroll() < pos
  }

  /**
   * 画面上に出現したかどうか
   */
  static isAppearedOnScreen (elem, offset = 0) {
    let top = YSElement.getElementTopPosition(elem) - window.innerHeight + offset
    return YSScroll.isOverScroll(top)
  }

  // /**
  //  * スクロール量表示の更新
  //  */
  // refresh_scroll() {
  //   let text = document.getElementById('scroll');
  //   let scroll = YSScroll.getScroll();
  //   text.innerHTML = scroll;
  // }
  // /**
  //  * スクロール量の表示
  //  */
  // show_scroll(pos='right') {
  //   let scroll = YSScroll.getScroll();
  //   let style = 'position:fixed;top:0;'+pos+':0;padding:1em;background-color:#000;color:#fff;z-index:9999;';
  //   let text = '<div id="scroll" style="'+style+'">'+scroll+'</div>';
  //   let elm = document.createElement('div');
  //   elm.innerHTML = text;
  //   document.getElementsByTagName('body')[0].appendChild(elm);
  //   window.addEventListener( 'scroll' , this.refresh_scroll, false);
  // }
}
export default YSScroll
