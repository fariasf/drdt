jQuery(document).ready(function($) {
	var $form = $('.gform_wrapper').find('.newsletter-signup');
	if( $form ) {
		$form.submit(function () {
			var track = new Image();
			track.src = "http://apiservices.krxd.net/click_tracker/track?kx_event_uid=MPeninnR&clk=https://www.rd.com/newsletter-confirmation/";
		});
	}
	var $cptform = $('.newsletter-wrapper');
	if( $cptform && $cptform.length ) {
		$(document).on('click','[type="button"]',function (event) {
			Krux('ns:trustedmediabrandsinc','admEvent', 'MQgrJsb8', 'clk', {});
		});
	}
});