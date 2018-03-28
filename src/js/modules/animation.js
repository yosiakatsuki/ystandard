import YSScroll from './scroll'

class YSAnimation {
  constructor () {
    this._dataAnimation = 'data-ys-animation'
    this._dataAnimationOffset = 'data-ys-animation-offset'
  }

  /**
   * アニメーション実行判断
   */
  runAnimation (offset = 0) {
    let list = document.querySelectorAll(`[${this._dataAnimation}]`)

    if (list == null) {
      return
    }
    for (var i = 0; i < list.length; i++) {
      let dataOffset = list[i].getAttribute(this._dataAnimationOffset)
      if (dataOffset != null && !isNaN(dataOffset)) {
        offset = dataOffset
      }

      if (YSScroll.isAppearedOnScreen(list[i], offset)) {
        let data = list[i].getAttribute(this._dataAnimation)
        if (data !== '') {
          list[i].classList.add(data)
          list[i].setAttribute(this._dataAnimation, '')
        }
      }
    }
  }
}
export default YSAnimation
