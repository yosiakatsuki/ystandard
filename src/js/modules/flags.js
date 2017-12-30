class flags {
  constructor() {
    this._setTimeoutId = null;
    this._wait_append_script = null;
    this._wait_appendcss = null;
  }
  /**
   * setTimeout ID
   */
  get setTimeoutId(){
    return this._setTimeoutId;
  }
  set setTimeoutId(id) {
    this._setTimeoutId = id;
  }

  /**
   * スクリプトの読み込みを一定時間ブロック
   */
  WaitAppendScript() {
    this._wait_append_script.setSeconds( this._wait_append_script.getSeconds() + 1 );
  }

}
