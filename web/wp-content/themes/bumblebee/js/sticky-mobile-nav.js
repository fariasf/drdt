/**
 * File sticky-mobile-nav.js.
 *
 * Helps with sticky header for desktop and mobile.
 *
 */

(function($){
	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 140) {
			$('nav').addClass('sticky');
		} else {
			$('nav').removeClass('sticky');
		}
	});
	/* global jQuery */
})(jQuery);