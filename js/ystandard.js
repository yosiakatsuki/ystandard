/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/drawer-nav/index.ts":
/*!************************************!*\
  !*** ./src/js/drawer-nav/index.ts ***!
  \************************************/
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
    const globalNavToggle = document.getElementById('global-nav__toggle');
    if (!globalNavToggle) {
      return;
    }
    // ドロワーメニュー開閉ボタン取得.
    const globalNavToggleButtons = document.querySelectorAll('#global-nav__toggle, .drawer-menu-toggle');
    // ボタンがあれば開閉処理追加.
    if (0 < globalNavToggleButtons.length) {
      globalNavToggleButtons.forEach(element => {
        element.addEventListener('click', e => {
          e.preventDefault();
          const display = window.getComputedStyle(globalNavToggle).display;
          // ボタンが表示なしの場合は何もしない.
          if ('none' === display) {
            return;
          }
          globalNavToggle.classList.toggle('is-open');
          // グローバルメニューの開閉.
          const globalMenu = document.getElementById('global-nav__menu');
          if (globalMenu) {
            globalMenu.classList.toggle('is-open');
          }
          // グローバルサーチの開閉.
          const globalSearch = document.getElementById('global-nav__search');
          if (globalSearch) {
            globalSearch.classList.toggle('is-open');
          }
          // メイン要素のスクロール制御.
          disableScroll(globalNavToggle);
          // モバイルフッターの開閉.
          const mobileFooter = document.getElementsByClassName('footer-mobile-nav');
          if (mobileFooter && mobileFooter.length) {
            mobileFooter[0].classList.toggle('is-hide');
          }
        });
      });
    }
  });
}

/**
 * メニュー開閉時のメイン要素のスクロール制御
 * @param target
 */
function disableScroll(target) {
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
/*!*****************************!*\
  !*** ./src/js/ystandard.ts ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _drawer_nav__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./drawer-nav */ "./src/js/drawer-nav/index.ts");

(0,_drawer_nav__WEBPACK_IMPORTED_MODULE_0__.drawerNav)();
})();

/******/ })()
;
//# sourceMappingURL=ystandard.js.map