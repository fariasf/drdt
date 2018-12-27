(function($){
	$(window).on('scroll',function(){
		var rdCard = $('.rd-card');
		if ( typeof listicleEffect != 'undefined') {
			listicleEffect.call(this, rdCard);
		}
	});
})(jQuery);