"use strict";document.addEventListener("DOMContentLoaded",function(){var search=document.getElementById("icon-search");search.addEventListener("keyup",function(e){var $=jQuery;var inputValue=$(e.target).val();$(".ys-icon-search__item").each(function(index,element){if(!inputValue){$(element).css("display","block")}else{var iconName=$(element).data("icon-name");var display=-1===iconName.indexOf(inputValue)?"none":"block";$(element).css("display",display)}})})});