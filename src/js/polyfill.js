/**
 * sticky
 */
function ysSticky() {
  let sticky = document.getElementById('sidebar-fixed')
  Stickyfill.add(sticky);
}
/**
 * object fit
 */
function ysObjectfit() {
  var someImages = document.querySelectorAll('img.subscribe__image,.ratio__image img')
  objectFitImages(someImages)
}

/**
 * 処理実行
 */
function ysPolyfill() {
  /**
   * sticky
   */
  ysSticky()
  ysObjectfit()
}

/**
 * イベント追加
 */
window.addEventListener('load', () => {
  ysPolyfill()
})
