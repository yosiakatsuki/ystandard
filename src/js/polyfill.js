/**
 * sticky
 */
function ysSticky() {
  let sticky = document.getElementById('sidebar-fixed')
  Stickyfill.add(sticky);
}

/**
 * 処理実行
 */
function ysPolyfill() {
  /**
   * sticky
   */
  ysSticky()
}

/**
 * イベント追加
 */
window.addEventListener('load', () => {
  ysPolyfill()
})
