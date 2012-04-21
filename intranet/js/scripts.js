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

});