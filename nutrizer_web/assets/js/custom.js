/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";

var defaultThemeData = {
	layout: "light",
	sideBar: "light-sidebar",
	colorTheme: "theme-green",
	sideBarMini: false,
	stickyHeader: true
};

const MSCStorage = {
	setThemeData: function (themeData) {
		if (themeData == null) {
			themeData = defaultThemeData;
		}
		var newData = JSON.stringify(themeData);
		localStorage.setItem('mscThemeData', newData);
	},
	getThemeData: function () {
		var themeData = JSON.parse(localStorage.getItem('mscThemeData'));
		if (themeData == null) {
			return defaultThemeData;
		} else {
			return themeData;
		}
	}
}

const MSCFunc = {
	setTheme: function (themeData) {
		var currentThemeData = themeData;
		if (currentThemeData == null) {
			currentThemeData = defaultThemeData;
		}
		$("body").removeClass();
		jQuery("body").addClass(currentThemeData.layout);
		jQuery("body").addClass(currentThemeData.sideBar);
		jQuery("body").addClass(currentThemeData.colorTheme);
		// set theme default color
		;
		$("body").removeClass("theme-" + $(".choose-theme li.active").attr("title"));
		$(".choose-theme li").removeClass("active");
		var colorTheme = currentThemeData.colorTheme.split("-")[1];
		$(".choose-theme li[title|='" + colorTheme + "']").addClass("active");
		$("body").addClass("theme-" + colorTheme);

		//set default dark or light layout(1=light, 2=dark)
		var layoutThemeId = currentThemeData.layout == "light" ? "1" : "2";
		$(".select-layout[value|='" + layoutThemeId + "']").prop("checked", true);
		//set default dark or light sidebar(1=light, 2=dark)
		var sideBarThemeId = currentThemeData.sideBar == "light-sidebar" ? "1" : "2";
		$(".select-sidebar[value|='" + sideBarThemeId + "']").prop("checked", true);
		// sticky header default set to true
		if($(window).outerWidth()<1024){
			currentThemeData.sideBarMini = false;
			var body = $("body");
			body.removeClass("search-show search-gone");
			if (body.hasClass("sidebar-gone")) {
			  body.removeClass("sidebar-gone");
			  body.addClass("sidebar-show");
			} else {
			  body.addClass("sidebar-gone");
			  body.removeClass("sidebar-show");
			}
		}
		// $("[data-toggle='sidebar']").click();
		$("#mini_sidebar_setting").prop("checked", currentThemeData.sideBarMini);
		toggle_sidebar_mini(currentThemeData.sideBarMini);
		if ($(".main-navbar").length) {
			if (!currentThemeData.stickyHeader) {
				$(".main-navbar")[0].classList.remove("sticky");
			} else {
				$(".main-navbar")[0].classList += " sticky";
			}
		}
		$("#sticky_header_setting").prop("checked", currentThemeData.stickyHeader);
		MSCStorage.setThemeData(currentThemeData);
	},
	setThemeLayout: function (param) {
		var currentThemeData = MSCStorage.getThemeData();
		currentThemeData.layout = param;
		MSCStorage.setThemeData(currentThemeData);
	},
	setThemeColor: function (param) {
		var currentThemeData = MSCStorage.getThemeData();
		currentThemeData.colorTheme = param;
		MSCStorage.setThemeData(currentThemeData);
	},
	setThemeSidebar: function (param) {
		var currentThemeData = MSCStorage.getThemeData();
		currentThemeData.sideBar = param;
		MSCStorage.setThemeData(currentThemeData);
	},
	setThemeSidebarMini: function (param) {
		var currentThemeData = MSCStorage.getThemeData();
		currentThemeData.sideBarMini = param;
		MSCStorage.setThemeData(currentThemeData);
	},
	setThemeStickyHeader: function (param) {
		var currentThemeData = MSCStorage.getThemeData();
		currentThemeData.stickyHeader = param;
		MSCStorage.setThemeData(currentThemeData);
	}
}
var toggle_sidebar_mini = function (mini) {
	let body = $("body");

	if (!mini) {
		body.removeClass("sidebar-mini");
		$(".main-sidebar").css({
			overflow: "hidden"
		});
		setTimeout(function () {
			// $(".main-sidebar").niceScroll(sidebar_nicescroll_opts);
			// sidebar_nicescroll = $(".main-sidebar").getNiceScroll();
		}, 500);
		$(".main-sidebar .sidebar-menu > li > ul .dropdown-title").remove();
		$(".main-sidebar .sidebar-menu > li > a").removeAttr("data-toggle");
		$(".main-sidebar .sidebar-menu > li > a").removeAttr(
			"data-original-title"
		);
		$(".main-sidebar .sidebar-menu > li > a").removeAttr("title");
	} else {
		body.addClass("sidebar-mini");
		body.removeClass("sidebar-show");
		// sidebar_nicescroll.remove();
		// sidebar_nicescroll = null;
		$(".main-sidebar .sidebar-menu > li").each(function () {
			let me = $(this);

			if (me.find("> .dropdown-menu").length) {
				me.find("> .dropdown-menu").hide();
				me.find("> .dropdown-menu").prepend(
					'<li class="dropdown-title pt-3">' + me.find("> a").text() + "</li>"
				);
			} else {
				me.find("> a").attr("data-toggle", "tooltip");
				me.find("> a").attr("data-original-title", me.find("> a").text());
				$("[data-toggle='tooltip']").tooltip({
					placement: "right"
				});
			}
		});
	}
	toogleNiceScroll();
};

var toogleNiceScroll = function () {
	setTimeout(function () {
		var sidebarOpt = {
			cursoropacitymin: 0,
			cursoropacitymax: 0.8,
			zindex: 892
		};
		var sidebar_nicescroll = $(".main-sidebar").getNiceScroll();
		if (sidebar_nicescroll != null) {
			if (sidebar_nicescroll.length == 0) {
				// console.log("Reset");
				$(".main-sidebar").getNiceScroll().remove();
				$(".main-sidebar").niceScroll(sidebarOpt);
			} else {
				sidebar_nicescroll.resize();
				// console.log("Resize");
				// console.log(sidebar_nicescroll);
			}

		} else {
			$(".main-sidebar").niceScroll(sidebarOpt);
			// console.log("Buat Baru");
		}
	}, 550);
}

function isMobile(){
	var isMobile_ = false; //initiate as false
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
			|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
				isMobile_ = true;
		}
		return isMobile_
}
$(function () {

	$("[data-toggle='sidebar']").click(function () {
		var body = $("body"),
			w = $(window);
		if (w.outerWidth() <= 1024) {} else {
			if (body.hasClass("sidebar-mini")) {
				MSCFunc.setThemeSidebarMini(true);
			} else {
				MSCFunc.setThemeSidebarMini(false);
			}
		}
		toogleNiceScroll();
		return false;
	});
	$("#mini_sidebar_setting").on("change", function () {
		var _val = $(this).is(":checked") ? "checked" : "unchecked";
		if (_val === "checked") {
			MSCFunc.setThemeSidebarMini(true);
		} else {
			MSCFunc.setThemeSidebarMini(false);
		}
		toogleNiceScroll();
	});
	$("#sticky_header_setting").on("change", function () {
		if ($(".main-navbar")[0].classList.contains("sticky")) {
			MSCFunc.setThemeStickyHeader(true);
		} else {
			MSCFunc.setThemeStickyHeader(false);
		}
	});

	$(".choose-theme li").on("click", function () {
		var colorTheme = "theme-" + $(this).attr("title");
		MSCFunc.setThemeColor(colorTheme);
	});

	// dark light sidebar button setting
	$(".sidebar-color input:radio").change(function () {
		if ($(this).val() == "1") {
			MSCFunc.setThemeSidebar("light-sidebar");
		} else {
			MSCFunc.setThemeSidebar("dark-sidebar");
		}
	});

	// dark light layout button setting
	$(".layout-color input:radio").change(function () {
		if ($(this).val() == "1") {
			MSCFunc.setThemeLayout("light");
			MSCFunc.setThemeSidebar("light-sidebar");
			MSCFunc.setThemeColor("theme-green");
		} else {
			MSCFunc.setThemeLayout("dark");
			MSCFunc.setThemeSidebar("dark-sidebar");
			MSCFunc.setThemeColor("theme-black");
		}
	});

	// restore default to dark theme
	$(".btn-restore-theme").on("click", function () {
		MSCFunc.setTheme(defaultThemeData);
	});

	//start up class add
	MSCFunc.setTheme(MSCStorage.getThemeData());

});

(function(window,undefined){

	var $ = window.jQuery;
	  
   
	  $.debounce = function (func, wait, immediate) {
		  var timeout;
		  return function() {
			  var context = this, args = arguments;
			  var later = function() {
				  timeout = null;
				  if (!immediate) func.apply(context, args);
			  };
			  var callNow = immediate && !timeout;
			  clearTimeout(timeout);
			  timeout = setTimeout(later, wait);
			  if (callNow) func.apply(context, args);
		  };
	  };


	  $.showToast = function(message, toastType = "") {
		switch (toastType) {
			case "error":
				iziToast.error({
					title: "Oops..",
					message: message,
					position: 'bottomCenter'
				});
				break;
			case "warning":
				iziToast.warning({
					title: "Warning!",
					message: message,
					position: 'bottomCenter'
				});
				break;
			case "info":
				iziToast.info({
					message: message,
					position: 'bottomCenter'
				});
			case "success":
				iziToast.success({
					title: "Success!",
					message: message,
					position: 'bottomCenter'
				});
				break;
			default:
				iziToast.show({
					message: message,
					position: 'bottomCenter'
				});
				break;
		}
	};

	$.checkAudioDuration =  function(audioSelector, durationSelector = null) {
        var audio = $(audioSelector)[0];
        if (audio != undefined && !isNaN(audio.duration) ) {
            var duration = audio.duration;
            if (durationSelector) {
                $(durationSelector).val(duration);
            } else {
                $.showToast("Audio Duration is " + duration + " Seconds", 'info');
            }
        } else {
            $.showToast("Audio file not found or Failed to get audio file!", "error");
        }
	};
	

	// $.checkVideoDuration =  function(audioSelector, durationSelector = null) {
    //     var audio = $(audioSelector)[0];
    //     if (audio != undefined && !isNaN(audio.duration) ) {
    //         var duration = audio.duration;
    //         if (durationSelector) {
    //             $(durationSelector).val(duration);
    //         } else {
    //             $.showToast("Video Duration is " + duration + " Seconds", 'info');
    //         }
    //     } else {
    //         $.showToast("Video file not found or Failed to get video file!", "error");
    //     }
    // };
	
  })(this);


  