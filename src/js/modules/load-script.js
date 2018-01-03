/**
 * script,cssの読み込み
 */
class YSLoadScript {
  constructor () {
    this._elementId = 'ys-main-script'
    this._scriptOnloadAttribute = 'data-ys-onload-script'
    this._scriptLazyAttribute = 'data-ys-lazy-script'
    this._cssLazyAttribute = 'data-ys-lazy-css'
    this._lazyScripts = null
    this._lazyCss = null
    this._waitLazyLoad = new Date()
  }

  /**
   * スクリプト読み込み
   */
  static loadScript (id, src, deps, onloadfnc = null) {
    let d = document
    if (deps !== '') {
      if (!d.getElementById(deps)) {
        return
      }
    }

    let js
    if (!d.getElementById(id)) {
      js = d.createElement('script')
      js.id = id
      js.src = src
      js.async = true
      if (onloadfnc != null) {
        js.onload = onloadfnc
      }
      d.body.appendChild(js)
    }
  }

  /**
   * CSS読み込み
   */
  static loadCss (id, href, onloadfnc = null) {
    let d = document
    let style
    if (!d.getElementById(id)) {
      style = d.createElement('link')
      style.id = id
      style.href = href
      style.rel = 'stylesheet'
      if (onloadfnc != null) {
        style.onload = onloadfnc
      }
      d.getElementsByTagName('head')[0].appendChild(style)
    }
  }

  /**
   * script エレメント取得
   */
  getScripts (attribute) {
    let elem = document.getElementById(this._elementId)
    if (elem == null) return null

    let scripts = elem.getAttribute(attribute)
    if (scripts == null || scripts === '') return null
    try {
      scripts = JSON.parse(scripts)
    } catch (e) {
      return false
    }
    return scripts
  }

  /**
   * 遅延読み込みスクリプト取得
   */
  getLazyScripts () {
    if (this._lazyScripts != null) {
      return this._lazyScripts
    }
    this._lazyScripts = this.getScripts(this._scriptLazyAttribute)
    return this._lazyScripts
  }

  /**
   * 遅延読み込みcss取得
   */
  getLazyCss () {
    if (this._lazyCss != null) {
      return this._lazyCss
    }
    this._lazyCss = this.getScripts(this._cssLazyAttribute)
    return this._lazyCss
  }

  /**
   * 遅延ロード中止時間セット
   */
  setWaitingTimeLazyLoad () {
    this._waitLazyLoad
      .setSeconds(this._waitLazyLoad.getSeconds() + 5)
  }

  /**
   * 読み込み中状態リセット
   */
  resetWaitingTimeLazyLoad () {
    this._waitLazyLoad = new Date()
  }

  /**
   * 遅延読み込み最中かどうか
   */
  isWaitingLazyLoad () {
    let now = new Date()
    return now < this._waitLazyLoad
  }

  /**
   * javascript ちょっと遅れて読み込み
   */
  loadScripts () {
    let scripts = this.getScripts(this._scriptOnloadAttribute)
    if (scripts === null || scripts === false) return

    for (var i = 0; i < scripts.length; i = (i + 1) | 0) {
      YSLoadScript.loadScript(scripts[i].id, scripts[i].url, '')
    }
  }

  /**
   * javascript スクロールで読み込み
   */
  lazyLoadScripts () {
    let scripts = this.getLazyScripts()
    if (scripts === null || scripts === false) return

    if (scripts.length > 0) {
      if (!this.isWaitingLazyLoad()) {
        this.setWaitingTimeLazyLoad()
        YSLoadScript.loadScript(
          scripts[0].id,
          scripts[0].url,
          '',
          this.resetWaitingTimeLazyLoad()
        )
        this._lazyScripts.shift()
      }
    }
  }

  /**
   * css スクロールで読み込み
   */
  lazyLoadCss () {
    let cssList = this.getLazyCss()
    if (cssList === null || cssList === false) return

    if (cssList.length > 0) {
      if (!this.isWaitingLazyLoad()) {
        this.setWaitingTimeLazyLoad()
        YSLoadScript.loadCss(
          cssList[0].id,
          cssList[0].url,
          '',
          this.resetWaitingTimeLazyLoad()
        )
        this._lazyCss.shift()
      }
    }
  }
}
export default YSLoadScript
