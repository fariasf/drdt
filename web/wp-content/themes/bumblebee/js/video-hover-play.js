/* global jQuery WebVTT*/
(function ($, win) {
	win.addEventListener('load', function () {
		var i = 0, setInt,
			DOMStrings = {
				playlistContainer: 'video-playlist-container',
				gifLabel: 'gif'
			};

		function changeImage(thumb, cues, item) {
			if (cues[i]) {
				var xywhStr = cues[i].text.substr(cues[i].text.indexOf('=') + 1);
				var xywh = xywhStr.split(',');
				var imageSrc = 'https://assets-jpcust.jwpsrv.com/strips/' + cues[i].text.split('#')[0] + '#xywh=' + xywhStr;
				var img = new Image();
				img.src = imageSrc;
				img.onload = function () {
					var c = xywh[0] / (img.width - xywh[2]) * 100,
						d = xywh[1] / (img.height - xywh[3]) * 100,
						e = img.width / xywh[2] * 100;
					item.find('.' + DOMStrings.gifLabel).remove();
					item.find('img').css({visibility: 'hidden'});
					$(thumb).css({
						background: 'url(' + imageSrc + ') no-repeat',
						backgroundSize: e + '%',
						backgroundPosition: c + '% ' + d + '%',
						visibility: 'visible'
					});
					i++;
				};
			}
			if (i == cues.length) {
				i = 0;
			}
			if (i == cues.length * 2) {
				clearTimeout(setInt);
			}
			if (i < cues.length) {
				setInt = setTimeout(changeImage, 1000, thumb, cues, item);
			}
		}

		function cueCallback(cues, item, thumb) {
			if (!cues.length) {
				item.find('.gif').remove();
				return;
			}
			item.addClass('mouseover-done');
			if (setInt) {
				clearTimeout(setInt);
				setInt = 0;
			}
			changeImage(thumb, cues, item);
		}

		$('.' + DOMStrings.playlistContainer).on('mouseenter', '.owl-item.active', function (event) {

			var target = $(event.currentTarget);
			var video = target.find('.item video')[0]; //video element//
			var item = target.find('.item').data('media-id');
			$(video).attr('src', 'https://cdn.jwplayer.com/videos/' + item + '.mp4');
			$(video).find('#track-attr').attr('src', 'https://assets-jpcust.jwpsrv.com/strips/' + item + '-320.vtt');

			if (navigator.userAgent.indexOf('Trident') === -1 && navigator.userAgent.indexOf('MSIE') === -1) {
				$(target).find('.gif').show();
				video.addEventListener('loadedmetadata', function (e) {
					if (e.target.HAVE_METADATA == 1) {
						var item = $(e.target).closest('.item');
						var video = item.find('video')[0]; //video element//
						var thumb = item.find('.thumb')[0]; //thumb element//
						var cues = video.textTracks[0].cues; // cures - array of snapshots with start and end time

						if (navigator.userAgent.indexOf('Firefox') > -1) {
							if (!item.data('vtt')) {
								$.ajax({
									url: $(video).find('track').attr('src'),
									method: 'GET',
									success: function (response) {
										var vtt = response,
											parser = new WebVTT.Parser(win, WebVTT.StringDecoder()),
											cues = [],
											regions = [];
										parser.oncue = function (cue) {
											cues.push(cue);
										};
										parser.onregion = function (region) {
											regions.push(region);
										};
										parser.onflush = function () {
											item.attr('data-vtt', JSON.stringify(cues));
											cueCallback(cues, item, thumb);
										};
										parser.parse(vtt);
										parser.flush();
									}
								});
							} else {
								cues = JSON.parse(item.attr('data-vtt'));
								cueCallback(cues, item, thumb);
							}
						} else {
							cueCallback(cues, item, thumb);
						}
					}
				});
			}
		});

		$('.' + DOMStrings.playlistContainer).on('mouseleave', '.owl-item.active', function (event) {
			var target = $(event.currentTarget);
			var thumb = target.find('.thumb')[0]; //thumb element//
			target.find('img').css({visibility: 'visible'});
			thumb.style.visibility = 'hidden';
			clearTimeout(setInt);
			setInt = 0;
			i = 0;
		});
	});
}(jQuery, window));