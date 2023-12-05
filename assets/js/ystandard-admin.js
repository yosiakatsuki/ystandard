/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scripts/admin/clipboard-copy/index.ts":
/*!***************************************************!*\
  !*** ./src/scripts/admin/clipboard-copy/index.ts ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   copyToClipboard: () => (/* binding */ copyToClipboard)
/* harmony export */ });
function copyToClipboard() {
  document.addEventListener('DOMContentLoaded', () => {
    const copyButtons = document.querySelectorAll('.ys-clipboard-copy__button');
    const canWriteText = () => {
      // @ts-ignore
      navigator.permissions.query({
        name: "clipboard-write"
      }).then(result => {
        return result.state === "granted" || result.state === "prompt";
      });
      return false;
    };

    // execCommand でのコピー.
    const execCommandCopy = (target, info) => {
      target.select();
      document.execCommand('copy');
      showInfo(info);
    };
    // 「コピーしました」の表示.
    const showInfo = info => {
      const CLASS_NAME = 'is-show';
      info.classList.add(CLASS_NAME);
      setTimeout(() => {
        info.classList.remove(CLASS_NAME);
      }, 3000);
    };

    // クリップボードコピーボタンのセット.
    copyButtons.forEach(copyButton => {
      copyButton.addEventListener('click', e => {
        e.preventDefault();
        const parent = copyButton.parentElement;
        const copyTarget = parent.querySelector('.ys-clipboard-copy__target');
        const info = parent.querySelector('.ys-clipboard-copy__info');
        // クリップボード書き込み.
        if (canWriteText()) {
          navigator.clipboard.writeText(copyTarget.value).then(() => {
            showInfo(info);
          }, () => {
            execCommandCopy(copyTarget, info);
          });
        } else {
          execCommandCopy(copyTarget, info);
        }
      });
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
/*!**********************************************!*\
  !*** ./src/scripts/admin/ystandard-admin.ts ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ystd_admin_clipboard_copy__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @ystd/admin/clipboard-copy */ "./src/scripts/admin/clipboard-copy/index.ts");

(0,_ystd_admin_clipboard_copy__WEBPACK_IMPORTED_MODULE_0__.copyToClipboard)();
})();

/******/ })()
;
//# sourceMappingURL=ystandard-admin.js.map