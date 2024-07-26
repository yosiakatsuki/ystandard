"use strict";var ysSetGlobalNavSearch=function ysSetGlobalNavSearch(){var searchButton=document.getElementById("global-nav__search-button");if(searchButton){searchButton.addEventListener("click",function(){var search=document.getElementById("global-nav__search");if(search){search.classList.toggle("is-active");setTimeout(function(){document.querySelector("#global-nav__search .search-field").focus()},50)}})}var closeButton=document.getElementById("global-nav__search-close");if(closeButton){closeButton.addEventListener("click",function(){var search=document.getElementById("global-nav__search");if(search){search.classList.remove("is-active")}})}};var ysSetGlobalNavToggle=function ysSetGlobalNavToggle(){var globalNavToggle=document.getElementById("global-nav__toggle");if(!globalNavToggle){return}var globalNavToggleButtons=document.querySelectorAll("#global-nav__toggle, .drawer-menu-toggle");if(0<globalNavToggleButtons.length){globalNavToggleButtons.forEach(function(element){element.addEventListener("click",function(e){e.preventDefault();if("none"===window.getComputedStyle(globalNavToggle).display){return false}globalNavToggle.classList.toggle("is-open");var globalMenu=document.getElementById("global-nav__menu");if(globalMenu){globalMenu.classList.toggle("is-open")}var globalSearch=document.getElementById("global-nav__search");if(globalSearch){globalSearch.classList.toggle("is-open")}ysToggleContentDisableScroll(globalNavToggle);var mobileFooter=document.getElementsByClassName("footer-mobile-nav");if(mobileFooter&&mobileFooter.length){mobileFooter[0].classList.toggle("is-hide")}})})}var globalNavLinks=document.querySelectorAll(".global-nav a[href*=\"#\"]");if(0<globalNavLinks.length){globalNavLinks.forEach(function(element){element.addEventListener("click",function(){if(globalNavToggle){globalNavToggle.classList.remove("is-open");ysToggleContentDisableScroll(globalNavToggle)}var globalMenu=document.getElementById("global-nav__menu");if(globalMenu){globalMenu.classList.toggle("is-open")}var mobileFooter=document.getElementsByClassName("footer-mobile-nav");if(mobileFooter&&mobileFooter.length){mobileFooter[0].classList.remove("is-hide")}})})}};var ysToggleContentDisableScroll=function ysToggleContentDisableScroll(target){if(target.classList.contains("is-open")){document.body.style.top="-".concat(window.scrollY,"px");document.body.style.position="fixed";document.body.style.width="100%"}else if("none"!==document.defaultView.getComputedStyle(target,null).display){var top=document.body.style.top;document.body.style.position="";document.body.style.top="";document.body.style.width="";window.scrollTo(0,parseInt(top||"0")*-1)}};var ysSetSmoothScroll=function ysSetSmoothScroll(){var links=document.querySelectorAll("a[href*=\"#\"]:not(.skip-link)");for(var i=0;i<links.length;i++){links[i].addEventListener("click",function(e){var _window,_window2;var urlSplit=e.currentTarget.getAttribute("href").split("#");var targetPageUrl=urlSplit[0];if(!((_window=window)!==null&&_window!==void 0&&(_window=_window.ystdScriptOption)!==null&&_window!==void 0&&_window.isPermalinkBasic)){targetPageUrl=targetPageUrl.split("?")[0]}targetPageUrl=targetPageUrl.split("&")[0];targetPageUrl=targetPageUrl.replace(/\/$/,"");var currentPageUrl=location.href.split("#")[0];if(!((_window2=window)!==null&&_window2!==void 0&&(_window2=_window2.ystdScriptOption)!==null&&_window2!==void 0&&_window2.isPermalinkBasic)){currentPageUrl=currentPageUrl.split("?")[0]}currentPageUrl=currentPageUrl.split("&")[0];currentPageUrl=currentPageUrl.replace(/\/$/,"");var id=urlSplit[1].split("?")[0].split("&")[0];if(""!==targetPageUrl&&targetPageUrl!==currentPageUrl){location.href=e.currentTarget.getAttribute("href");return}e.preventDefault();ysScrollToTarget(id)})}};var ysScrollToTarget=function ysScrollToTarget(id){var behavior=arguments.length>1&&arguments[1]!==undefined?arguments[1]:"smooth";var target=document.getElementById(id);if(!target&&""!==id){return}var top=0;if(target){var pos=target.getBoundingClientRect().top;top=pos+window.pageYOffset-ysGetScrollBuffer()}window.scroll({top:top,behavior:behavior})};var ysGetScrollBuffer=function ysGetScrollBuffer(){var buffer=50;var header=document.getElementById("masthead");if(header){if("fixed"===window.getComputedStyle(header,null).getPropertyValue("position")){buffer=header.getBoundingClientRect().bottom+20}}return buffer};var ysSetLoadedPosition=function ysSetLoadedPosition(){if(-1===location.href.indexOf("#")){return}var id=location.href.split("#")[1].split("?")[0];var target=document.getElementById(id);if(!target&&""!==id){return}window.scrollTo({top:target.getBoundingClientRect().top+window.pageYOffset-ysGetScrollBuffer()})};var ysSetBackToTop=function ysSetBackToTop(){var backToTop=document.getElementById("back-to-top");if(backToTop&&backToTop.classList.contains("is-square")){var width=backToTop.getBoundingClientRect().width;var height=backToTop.getBoundingClientRect().height;var size=width<height?"".concat(height,"px"):"".concat(width,"px");backToTop.style.width=size;backToTop.style.height=size;backToTop.addEventListener("click",function(e){e.preventDefault();window.scroll({top:0,behavior:"smooth"})})}};var ysSetScrollBarWidth=function ysSetScrollBarWidth(){var scrollbar=window.innerWidth-document.body.clientWidth;if(window.getComputedStyle(document.documentElement).getPropertyValue("--scrollbar-width")){document.querySelector(":root").style.setProperty("--scrollbar-width",scrollbar+"px")}};var getHeaderHeight=function getHeaderHeight(){var header=document.getElementById("masthead");if(!header){return undefined}return Math.floor(header.getBoundingClientRect().height)};var ysSetFixedHeaderPadding=function ysSetFixedHeaderPadding(){var classes=document.body.classList;if(classes.contains("has-fixed-header")&&!classes.contains("disable-auto-padding")&&!classes.contains("is-overlay")){var size=getHeaderHeight();if(size){document.body.style.paddingTop="".concat(size,"px");document.querySelector(":root").style.setProperty("--fixed-sidebar-top","".concat(size+48,"px"))}}};var ysSetDrawerNavPadding=function ysSetDrawerNavPadding(){var size=getHeaderHeight();if(size){document.querySelector(":root").style.setProperty("--mobile-nav-container-padding","".concat(size+24,"px"))}};document.addEventListener("DOMContentLoaded",function(){ysSetScrollBarWidth();ysSetFixedHeaderPadding();ysSetGlobalNavToggle();ysSetDrawerNavPadding();ysSetGlobalNavSearch();ysSetSmoothScroll();ysSetBackToTop();setTimeout(function(){ysSetLoadedPosition()},1)});window.addEventListener("resize",function(){if(window.ysResizeFixedHeader){clearTimeout(window.ysResizeFixedHeader)}window.ysResizeFixedHeader=setTimeout(function(){ysSetFixedHeaderPadding()},100)});
//# sourceMappingURL=ystandard.js.map