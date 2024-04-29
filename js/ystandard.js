/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scripts/drawer-nav/index.ts":
/*!*****************************************!*\
  !*** ./src/scripts/drawer-nav/index.ts ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   drawerNav: () => (/* binding */ drawerNav)
/* harmony export */ });
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



(0,_drawer_nav__WEBPACK_IMPORTED_MODULE_0__.drawerNav)();
(0,_search_form__WEBPACK_IMPORTED_MODULE_1__.setGlobalNavSearch)();
(0,_mobile_footer__WEBPACK_IMPORTED_MODULE_2__.setMobileFooter)();
})();

/******/ })()
;
//# sourceMappingURL=ystandard.js.map