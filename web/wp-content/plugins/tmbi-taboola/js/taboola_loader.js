window._taboola = window._taboola || [];
_taboola.push({photo: 'auto'});
_taboola.push({article: 'auto'});

function loadTaboola() {
	//delay load of taboola
	setTimeout(function () {
		(function (e, f, u) {
			e.src = u;
			f.parentNode.insertBefore(e, f);
		})(
			document.createElement('script'),
			document.getElementsByTagName('script')[0],
			tmbi_taboola.script
		);
		_taboola.push({flush: true});
	}, 6000);
}

window.addEventListener('load', loadTaboola);