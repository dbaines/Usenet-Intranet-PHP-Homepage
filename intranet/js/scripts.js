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

	// Some helper scripts for the show poster
	$(".sickbeardShows li").each(function() {

		// Fix for popups on multi-line shows
		// Get half of the height of this li
		var liHeight = $(this).height() / 2,
			// 90 is half of the height of the poseter
			newHeight = 90 - liHeight;
			popup = $(this).find(".showPopup");
		// Set top postion based on that
		popup.css("top",-newHeight);

	});
	$(".sickbeardShows li, .seasonStarts li").hover(function() {

		var popup = $(this).find(".showPopup");
		// reset position
		popup.stop().css({
			"left": "-170px",
			"opacity": 0
		});
		// Animate position
		popup.stop().animate({
			"left": "-146px",
			"opacity": 1
		}, 200);

	}, function() {

		var popup = $(this).find(".showPopup");
		popup.stop().animate({
			"left": "-170px",
			"opacity": 0
		}, 200);
	});

});