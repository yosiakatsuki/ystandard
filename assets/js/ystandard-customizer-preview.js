/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scripts/admin/customizer/preview.ts":
/*!*************************************************!*\
  !*** ./src/scripts/admin/customizer/preview.ts ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   showHeaderHeightInfo: () => (/* binding */ showHeaderHeightInfo)
/* harmony export */ });
/**
 * 固定ヘッダー設定時にヘッダーの高さを表示する.
 */
function showHeaderHeightInfo() {
  document.addEventListener('DOMContentLoaded', () => {
    // @ts-ignore
    const $ = jQuery;
    const label = 'ヘッダー高さ:';
    const wrapClass = 'header-height-info';
    const numClass = 'header-height-num';
    // 固定ヘッダーチェック.
    const fixedHeader = $('.has-fixed-header');
    if (!fixedHeader.length) {
      return;
    }
    // ヘッダー.
    const header = $('.site-header');
    if (!header.length) {
      return;
    }
    // 表示追加.
    header.prepend(`<span class="${wrapClass}">${label}<span class="${numClass}"></span>px</span>`);
    // 表示更新
    const updateHeight = () => {
      const height = Math.floor(header.outerHeight());
      $(`.${numClass}`).text(height);
    };
    updateHeight();
    // リサイズで更新.
    $(window).on('resize', () => {
      $(`.${numClass}`).text(' ... ');
      setTimeout(() => {
        updateHeight();
      }, 800);
    });
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
/*!***********************************************************!*\
  !*** ./src/scripts/admin/ystandard-customizer-preview.ts ***!
  \***********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ystd_admin_customizer_preview__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @ystd/admin/customizer/preview */ "./src/scripts/admin/customizer/preview.ts");

(0,_ystd_admin_customizer_preview__WEBPACK_IMPORTED_MODULE_0__.showHeaderHeightInfo)();
})();

/******/ })()
;
//# sourceMappingURL=ystandard-customizer-preview.js.map