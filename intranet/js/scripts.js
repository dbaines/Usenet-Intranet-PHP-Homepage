$(function() {
	
	// Current/Recent switcher
	$(".downloadPage .go").click(function() {
		if($(this).parent(".downloadPage").hasClass("downloadPageCurrent")) {
			$(".downloadFrameSlide").animate({
				"marginLeft": "-450px"
			}, 500);
		} else if ($(this).parent(".downloadPage").hasClass("downloadPageHistory")) {
			$(".downloadFrameSlide").animate({
				"marginLeft": "0px"
			}, 500);
		}
	});

	// Fix for popups on multi-line shows
	$(".sickbeardShows li").each(function() {
		// Get half of the height of this li
		var liHeight = $(this).height() / 2,
			// 90 is half of the height of the poseter
			newHeight = 90 - liHeight;
		// Set top postion based on that
		$(this).find(".showPopup").css("top",-newHeight);
	});

});