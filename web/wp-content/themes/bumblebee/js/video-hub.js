/* global jQuery*/
jQuery(document).ready(function ($) {
	//list of dom elements
	var DOMStrings = {
		dataPlaylist : 'data-playlist',
		scriptTemplate : 'script[type="text/x-template"]',
		dataCarousel : 'data-carousel',
		dataMediaId : 'data-media-id',
		dataPlaylistId : 'data-playlist-id',
		dataPlaylistTitle : 'data-playlist-title'
	};
	$('[' + DOMStrings.dataPlaylist + ']').each(function () {
		var $self = $(this);
		var playlist_id = $self.attr(DOMStrings.dataPlaylist);
		var template = $self.find(DOMStrings.scriptTemplate).html();
		$.getJSON('https://cdn.jwplayer.com/v2/playlists/' + playlist_id, function (data) {
			var playlist = data.playlist;
			var the_markup = '';
			playlist.forEach(function (element) {
				var the_video = template.replace('%image%', element.image).replace('%title%', element.title).replace('%media_id%', element.mediaid).replace('%playlist_id%', playlist_id);
				the_markup += the_video;
			});
			$self.html(the_markup);
			if ($self.attr(DOMStrings.dataCarousel)) {
				$self.owlCarousel({
					loop: true,
					margin: 40,
					nav: true,
					navText: ['<span class="dashicons dashicons-arrow-left-alt2"></span>', '<span class="dashicons dashicons-arrow-right-alt2"></span>'],
					responsive: {
						0: {
							items: 1
						},
						320: {
							autoWidth: false,
							items: 2,
							margin: 15,
							slideBy: 1
						},
						480: {
							autoWidth: false,
							items: 2,
							margin: 15,
							slideBy: 1
						},
						767: {
							items: 3,
							margin: 16,
							slideBy: 4
						},
						1027: {
							items: 3,
							slideBy: 2
						}
					}
				});
			}
		});
	});
	$(document).on('click', '[' + DOMStrings.dataMediaId + '],[' + DOMStrings.dataPlaylistId + ']', function () {
		var newURL = window.location.href,
			$this = $(this);
		if ($this.attr(DOMStrings.dataMediaId)) {
			newURL = replaceUrlParam(newURL, 'video_id', $this.attr(DOMStrings.dataMediaId));
		}
		if ($this.attr(DOMStrings.dataPlaylistId)) {
			newURL = replaceUrlParam(newURL, 'playlist_id', $this.attr(DOMStrings.dataPlaylistId));
		}
		if ($this.attr(DOMStrings.dataPlaylistTitle)) {
			newURL = replaceUrlParam(newURL, 'playlist_title', $this.attr(DOMStrings.dataPlaylistTitle));
		}
		window.location.href = newURL;
	});

	function replaceUrlParam(url, paramName, paramValue) {
		if (paramValue === null) {
			paramValue = '';
		}
		var pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
		if (url.search(pattern) >= 0) {
			return url.replace(pattern, '$1' + paramValue + '$2');
		}
		url = url.replace(/[?#]$/, '');

		return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
	}
});
