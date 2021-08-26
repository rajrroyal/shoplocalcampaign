/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/account.js":
/*!*********************************!*\
  !*** ./resources/js/account.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// account dashboard stores list
window.dashboardStores = function (stores) {
  return {
    stores: stores.data,
    init: function init() {
      var _this = this;

      window.Echo["private"]("user.update.".concat(user_id)).listen('.update.progress', function (e) {
        _this.updateProgress(e);
      }).listen('.update.complete', function (e) {
        _this.updateProgress(e);
      });
    },
    updateStore: function updateStore(store) {
      store.updating = true;
      axios.get(store.update_url).then(function (response) {
        console.log(response);
      });
    },
    updateProgress: function updateProgress(e) {
      var _this2 = this;

      var store = e.store;
      var storeElem = document.querySelector("[data-store-id=\"".concat(store.id, "\"]"));
      this.stores.map(function (store, index) {
        if (store.id === e.store.id) {
          _this2.stores[index] = e.store;
        }
      });
      var percent = e.percent || 0;
      var progressText = storeElem.querySelector("[data-progress-text]");
      progressText.innerHTML = percent.toFixed(0) + '%';
      var progressBar = storeElem.querySelector("[data-progress-bar]");
      progressBar.style.transform = percent ? "scaleX(".concat(percent / 100, ")") : null;
    }
  };
};

window.connectStore = function () {
  return {
    connectShopify: function connectShopify(event) {
      var form = new FormData(event.target);
      var shop = form.get('shop');
      var client_id = form.get('client_id');
      var scope = form.get('scope');
      var redirect_uri = form.get('redirect_uri');
      var state = form.get('state');
      var authUrl = "https://".concat(shop, ".myshopify.com/admin/oauth/authorize?client_id=").concat(client_id, "&scope=").concat(scope, "&redirect_uri=").concat(redirect_uri, "&state=").concat(state);
      var authWindow = window.open(authUrl, '_blank');
      this.callbackListener(authWindow);
    },
    connectPowerShop: function connectPowerShop(authUrl) {
      var authWindow = window.open(authUrl, '_blank');
      this.callbackListener(authWindow);
    },
    callbackListener: function callbackListener(authWindow) {
      window.addEventListener('message', function (e) {
        var hostname = new URL(e.origin).hostname;

        if (hostname !== window.location.hostname) {
          return;
        }

        var message = e.data;

        if (message.event == 'store_authorized') {
          authWindow.close();
          window.location.href = message.redirect_url;
        }
      });
    },
    authCallback: function authCallback(redirect_url) {
      var message = {
        event: 'store_authorized',
        redirect_url: redirect_url
      };
      window.opener.postMessage(message, '*');
    }
  };
};

/***/ }),

/***/ 3:
/*!***************************************!*\
  !*** multi ./resources/js/account.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/dave/Documents/WWWRoot/ShopCity/shop20/resources/js/account.js */"./resources/js/account.js");


/***/ })

/******/ });