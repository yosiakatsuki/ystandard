import YSLoadScript from './modules/load-script'

let loadScript = new YSLoadScript()

/**
 * グローバル変数
 */
let ysSetTimeoutId = null

/**
 * スクロール処理メイン
 */
function ysScroll () {
  /**
   * css,js 遅延ロード
   */
  loadScript.lazyLoadCss()
  loadScript.lazyLoadScripts()
}

/**
 * 初期化
 */
function ysInit () {
  /**
   * スクロール処理追加
   */
  window.addEventListener('scroll', () => {
    if (ysSetTimeoutId) {
      return false
    }

    ysSetTimeoutId = setTimeout(() => {
      ysScroll()
      ysSetTimeoutId = null
    }, 100)
  })

  /**
   * スクリプト読み込み
   */
  loadScript.loadScripts()
}

/**
 * イベント追加
 */
window.addEventListener('load', () => {
  ysInit()
})
