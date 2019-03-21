jQuery(document).ready(function ($) {
	$("[data-playlist]").each(function(){
		var $self = $(this);
		var playlist_id = $self.attr('data-playlist');
		var template = $self.find('script[type="text/x-template"]').html();
		$.getJSON('https://cdn.jwplayer.com/v2/playlists/' + playlist_id, function(data) {
			var playlist = data.playlist;
			var the_markup = '';
			playlist.forEach(function(element) {
				var the_video = template.replace('%image%', element.image).replace('%title%', element.title).replace('%media_id%', element.mediaid).replace('%playlist_id%', playlist_id);
				the_markup += the_video;
			});
			$self.html(the_markup);
			if ( $self.attr('data-carousel') ) {
				$self.owlCarousel({
					loop: true,
					margin: 40,
					nav: true,
					navText : ['<span class="dashicons dashicons-arrow-left-alt2"></span>','<span class="dashicons dashicons-arrow-right-alt2"></span>'],
					responsive:{
						0:{
							items: 1
						},
						320:{
							autoWidth: false,
							items: 2,
							margin: 15,
							slideBy: 1
						},
						480:{
							autoWidth: false,
							items: 2,
							margin: 15,
							slideBy: 1
						},
						767:{
							items: 3,
							margin: 16,
							slideBy: 4
						},
						1027:{
							items: 3,
							slideBy: 2
						}
					}
				});
			}
		});
	});
	$(document).on('click','[data-media-id],[data-playlist-id]', function(){
		var newURL = window.location.href;
		if ( $(this).attr('data-media-id') ) {
			newURL = replaceUrlParam( newURL, 'video_id', $(this).attr('data-media-id') );
		}
		if ( $(this).attr('data-playlist-id') ) {
			newURL = replaceUrlParam( newURL, 'playlist_id', $(this).attr('data-playlist-id') );
		}
		if ( $(this).attr('data-playlist-title') ) {
			newURL = replaceUrlParam( newURL, 'playlist_title', $(this).attr('data-playlist-title') );
		}
		window.location.href = newURL;
	});

	function replaceUrlParam( url, paramName, paramValue) {
		if (paramValue == null) {
			paramValue = '';
		}
		var pattern = new RegExp('\\b('+paramName+'=).*?(&|#|$)');
		if (url.search(pattern)>=0) {
			return url.replace(pattern,'$1' + paramValue + '$2');
		}
		url = url.replace(/[?#]$/,'');

		return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
	}
});
