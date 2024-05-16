/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scripts/anchor-link/index.ts":
/*!******************************************!*\
  !*** ./src/scripts/anchor-link/index.ts ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAnchorLink: () => (/* binding */ initAnchorLink)
/* harmony export */ });
function initAnchorLink() {
  document.addEventListener('DOMContentLoaded', () => {
    // ページ内リンククリック処理.
    setAnchorLinkClick();
    setTimeout(function () {
      // ページ読み込み時の位置調整.
      setLoadedPosition();
    }, 1);
  });
}
function setAnchorLinkClick() {
  // アンカーリンク付きのリンク取得.
  const links = document.querySelectorAll('a[href*="#"]');
  links.forEach(link => {
    link.addEventListener('click', e => {
      const targetId = getTargetId(e.currentTarget);
      // 対象IDがない場合はスクロールしない.
      if (null === targetId) {
        return;
      }
      e.preventDefault();
      doScroll(targetId);
    });
  });
}
function getTargetId(currentTarget) {
  const urlSplit = currentTarget?.getAttribute('href')?.split('#');
  // 対象URLなし.
  if (!urlSplit) {
    return '';
  }

  // ターゲットID抽出.
  const targetId = urlSplit[1].split('?')[0].split('&')[0];
  let targetUrl = urlSplit[0];
  // ページURL.
  let currentUrl = window.location.href.split('#')[0];

  // URL構造がベーシックの場合は「?」以降をURLとする.
  // @ts-expect-error
  if (!window?.ystdScriptOption?.isPermalinkBasic) {
    targetUrl = targetUrl.split('?')[0];
    currentUrl = currentUrl.split('?')[0];
  }
  targetUrl = targetUrl.split('&')[0].replace(/\/$/, '');
  currentUrl = currentUrl.split('&')[0].replace(/\/$/, '');

  // ターゲットURLがページURLと一致しない場合はリンク先へ移動.
  if ('' !== targetUrl && targetUrl !== currentUrl) {
    return null;
  }
  return targetId;
}

/**
 * スクロール処理.
 *
 * @param {string} id       スクロール先の要素ID
 * @param {string} behavior スクロール動作
 */
function doScroll(id, behavior = 'smooth') {
  const target = document.getElementById(id);
  let top = 0;
  // 対象チェック..
  if (!target) {
    // 対象IDが存在しない場合はスクロールしない.
    // IDが空の場合はページ先頭へ移動.
    if ('' !== id) {
      return;
    }
  } else {
    const pos = target.getBoundingClientRect().top;
    top = pos + window.scrollY - getScrollBuffer();
  }
  // 履歴追加.
  history.pushState(null, '', `#${id}`);
  window.scroll({
    top,
    behavior
  });
}
function setLoadedPosition() {
  const url = location.href;
  if (-1 === url.indexOf('#')) {
    return;
  }
  const id = url.split('#')[1].split('?')[0];
  // 対象の要素取得.
  const target = document.getElementById(id);
  if (!target) {
    return;
  }
  const position = target.getBoundingClientRect().top + window.scrollY - getScrollBuffer();
  // 内部リンク付きURLの場合、位置調整.
  window.scrollTo({
    top: position
  });
}

/**
 * スクロール位置調整.
 */
function getScrollBuffer() {
  let buffer = 50;
  // ヘッダーチェック.
  const header = document.getElementById('masthead');
  // ヘッダーがなければ50px.
  if (!header) {
    return buffer;
  }
  // ヘッダーの固定表示チェック.
  const headerPosition = window.getComputedStyle(header, null).getPropertyValue('position');
  // ヘッダーが固定表示の場合はヘッダーの高さを追加.
  if ('fixed' === headerPosition) {
    buffer = header.getBoundingClientRect().bottom + 20;
  }
  return buffer;
}

/***/ }),

/***/ "./src/scripts/drawer-nav/index.ts":
/*!*****************************************!*\
  !*** ./src/scripts/drawer-nav/index.ts ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   drawerNav: () => (/* binding */ drawerNav)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./src/scripts/utils/index.ts");


/**
 * ドロワーメニューの開閉処理.
 */
function drawerNav() {
  document.addEventListener('DOMContentLoaded', () => {
    // ドロワーメニューの開閉処理セット.
    if (!toggleDrawerNav()) {
      return;
    }
    // ドロワーメニューを閉じる処理セット.
    closeDrawerNav();
    // ドロワーメニュー閉じるボタンエリアの高さ設定.
    setCloseContainerHeight();
  });
  let resizeWindow;
  window.addEventListener('resize', () => {
    if (resizeWindow) {
      clearTimeout(resizeWindow);
    }
    resizeWindow = setTimeout(function () {
      setCloseContainerHeight();
    }, 100);
  });
}

/**
 * ドロワーメニューの開閉処理.
 */
function toggleDrawerNav() {
  const globalNavToggle = document.getElementById('global-nav__toggle');
  if (!globalNavToggle) {
    return false;
  }
  // ドロワーメニュー開閉ボタン取得.
  const globalNavToggleButtons = document.querySelectorAll('.global-nav__toggle');
  // ボタンが無ければ処理終了
  if (0 >= globalNavToggleButtons.length) {
    return false;
  }
  // ボタンクリック時の処理追加.
  globalNavToggleButtons.forEach(element => {
    element.addEventListener('click', e => {
      e.preventDefault();
      const display = window.getComputedStyle(globalNavToggle).display;
      // ボタンが表示なしの場合は何もしない.
      if ('none' === display) {
        return;
      }
      // ドロワーメニュー開閉に関するクラス設定.
      setDrawerNavClass();
      // メイン要素のスクロール制御.
      toggleBodyScroll(globalNavToggle);
    });
  });
  return true;
}

/**
 * ドロワーメニューを閉じる処理.
 */
function closeDrawerNav() {
  // ドロワーメニュー閉じる.
  const drawerNavCloseLinks = document.querySelectorAll('.global-nav a[href*="#"],#drawer-nav__close');
  // ドロワーメニュー閉じるリンク・ボタンのチェック.
  if (0 >= drawerNavCloseLinks.length) {
    return false;
  }
  // ドロワーメニュー閉じるリンククリック時の処理追加.
  drawerNavCloseLinks.forEach(element => {
    element.addEventListener('click', () => {
      const globalNavToggle = document.getElementById('global-nav__toggle');

      // ドロワーメニュー開閉に関するクラス設定.
      setDrawerNavClass('remove');
      if (globalNavToggle) {
        // メイン要素のスクロール制御.
        toggleBodyScroll(globalNavToggle);
      }
    });
  });
  return true;
}

/**
 * ドロワーメニュー開閉に関するクラス設定
 * @param type
 */
function setDrawerNavClass(type = 'toggle') {
  // クラスの設定
  const setClass = (target, className) => {
    if (!target) return;
    if ('toggle' === type) {
      target.classList.toggle(className);
    } else {
      target.classList.remove(className);
    }
  };
  // グローバルメニュー開閉ボタン.
  setClass(document.getElementById('global-nav__toggle'), 'is-open');
  // ドロワーメニュー開閉ボタン.
  setClass(document.getElementById('drawer-nav__toggle'), 'is-open');
  // グローバルメニュー.
  setClass(document.getElementById('global-nav__menu'), 'is-open');
  // ドロワーメニュー.
  setClass(document.getElementById('drawer-nav'), 'is-open');
  // グローバルサーチ.
  setClass(document.getElementById('global-nav__search'), 'is-drawer-nav-open');
  // メイン要素.
  setClass(document.documentElement, 'is-drawer-open');
  // モバイルフッター.
  setClass(document.getElementById('footer-mobile-nav'), 'is-open');
}

/**
 * メニュー開閉時のメイン要素のスクロール制御
 * @param target
 */
function toggleBodyScroll(target) {
  if (target.classList.contains('is-open')) {
    document.body.style.top = `-${window.scrollY}px`;
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
  } else if ('none' !== window.getComputedStyle(target).display) {
    const top = document.body.style.top;
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    window.scrollTo(0, parseInt(top || '0') * -1);
  }
}

/**
 * ドロワーメニュー閉じるボタンエリアの高さ設定.
 */
function setCloseContainerHeight() {
  const headerHeight = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.getHeaderHeight)();
  const container = document.querySelector('.drawer-nav__close-container');
  // 高さセット.
  if (container && headerHeight) {
    container.style.height = `${headerHeight}px`;
  }
}

/***/ }),

/***/ "./src/scripts/fixed-header/index.ts":
/*!*******************************************!*\
  !*** ./src/scripts/fixed-header/index.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initFixedHeader: () => (/* binding */ initFixedHeader)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./src/scripts/utils/index.ts");

function initFixedHeader() {
  let resizeFixedHeader;
  document.addEventListener('DOMContentLoaded', () => {
    setFixedHeaderPadding();
  });
  window.addEventListener('resize', () => {
    if (resizeFixedHeader) {
      clearTimeout(resizeFixedHeader);
    }
    resizeFixedHeader = setTimeout(function () {
      // 固定ヘッダー高さセット.
      setFixedHeaderPadding();
    }, 100);
  });
}

/**
 * 固定ヘッダー高さセット.
 */
function setFixedHeaderPadding() {
  const bodyClass = document.body.classList;
  // 各条件.
  const hasFixedHeader = bodyClass.contains('has-fixed-header');
  const isDisableAutoPadding = bodyClass.contains('disable-auto-padding');
  const isOverlay = bodyClass.contains('is-overlay');
  // チェック.
  if (hasFixedHeader && !isDisableAutoPadding && !isOverlay) {
    const headerHeight = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.getHeaderHeight)();
    if (headerHeight) {
      document.body.style.paddingTop = `${headerHeight}px`;
      document.body.style.setProperty('--ystd--sidebar--custom-fixed-position--top', `calc(${headerHeight}px + var(--ystd--sidebar--fixed-position--top) * 2)`);
    }
  }
}

/***/ }),

/***/ "./src/scripts/mobile-footer/index.ts":
/*!********************************************!*\
  !*** ./src/scripts/mobile-footer/index.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setMobileFooter: () => (/* binding */ setMobileFooter)
/* harmony export */ });
function setMobileFooter() {
  let resizeTimer;
  document.addEventListener('DOMContentLoaded', setMobileFooterPadding);
  // リサイズイベントは間引く.
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(setMobileFooterPadding, 300);
  });
}
function setMobileFooterPadding() {
  const mobileFooter = document.querySelector('.footer-mobile-nav');
  let mobileFooterHeight = '0';
  if (!mobileFooter) {
    return;
  }
  // モバイルフッターが表示されている場合、高さをセットする.
  if ('none' !== window.getComputedStyle(mobileFooter).display) {
    mobileFooterHeight = mobileFooter.clientHeight + 'px';
  }
  // モバイルフッターの高さ分をセット.
  document.body.style.setProperty('--ystd--mobile-footer-nav--footer-padding-bottom', mobileFooterHeight);
}

/***/ }),

/***/ "./src/scripts/search-form/index.ts":
/*!******************************************!*\
  !*** ./src/scripts/search-form/index.ts ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setGlobalNavSearch: () => (/* binding */ setGlobalNavSearch)
/* harmony export */ });
function setGlobalNavSearch() {
  document.addEventListener('DOMContentLoaded', () => {
    // 検索フォームを開く処理セット.
    setOpenSearch();
    // 検索フォームを閉じる処理セット.
    setCloseSearch();
  });
}

/**
 * 検索フォームを開く処理セット.
 */
function setOpenSearch() {
  const searchButton = document.getElementById('global-nav__search-button');
  if (!searchButton) {
    return;
  }
  searchButton.addEventListener('click', e => {
    e.preventDefault();
    const search = document.getElementById('global-nav__search');
    if (search) {
      search.classList.toggle('is-active');
      // 検索フォームが表示されたらフォーカスをセット
      setTimeout(function () {
        const field = document.querySelector('#global-nav__search .search-field');
        // フォーカスをセット
        if (field) {
          field.focus();
        }
      }, 50);
    }
  });
}

/**
 * 検索フォームを閉じる処理セット.
 */
function setCloseSearch() {
  const closeButton = document.getElementById('global-nav__search-close');
  if (!closeButton) {
    return;
  }
  closeButton.addEventListener('click', () => {
    const search = document.getElementById('global-nav__search');
    if (search) {
      search.classList.remove('is-active');
    }
  });
}

/***/ }),

/***/ "./src/scripts/utils/index.ts":
/*!************************************!*\
  !*** ./src/scripts/utils/index.ts ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getHeaderHeight: () => (/* binding */ getHeaderHeight)
/* harmony export */ });
/**
 * ヘッダー高さ取得.
 */
function getHeaderHeight() {
  const header = document.getElementById('masthead');
  if (!header) {
    return undefined;
  }
  return Math.floor(header.getBoundingClientRect().height);
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************************!*\
  !*** ./src/scripts/ystandard.ts ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _drawer_nav__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./drawer-nav */ "./src/scripts/drawer-nav/index.ts");
/* harmony import */ var _search_form__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./search-form */ "./src/scripts/search-form/index.ts");
/* harmony import */ var _mobile_footer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mobile-footer */ "./src/scripts/mobile-footer/index.ts");
/* harmony import */ var _anchor_link__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./anchor-link */ "./src/scripts/anchor-link/index.ts");
/* harmony import */ var _fixed_header__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./fixed-header */ "./src/scripts/fixed-header/index.ts");





(0,_drawer_nav__WEBPACK_IMPORTED_MODULE_0__.drawerNav)();
(0,_search_form__WEBPACK_IMPORTED_MODULE_1__.setGlobalNavSearch)();
(0,_mobile_footer__WEBPACK_IMPORTED_MODULE_2__.setMobileFooter)();
(0,_anchor_link__WEBPACK_IMPORTED_MODULE_3__.initAnchorLink)();
(0,_fixed_header__WEBPACK_IMPORTED_MODULE_4__.initFixedHeader)();
})();

/******/ })()
;
//# sourceMappingURL=ystandard.js.map