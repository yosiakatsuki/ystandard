class YSElement {
  /**
   * 要素の位置を取得
   */
  static getElementTopPosition (elem = document) {
    let rect = elem.getBoundingClientRect()
    let top = rect.top + window.pageYOffset
    return top
  }
}
export default YSElement
