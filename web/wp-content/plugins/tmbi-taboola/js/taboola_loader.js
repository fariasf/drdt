window._taboola = window._taboola || [];
_taboola.push({photo: 'auto'});
_taboola.push({article: 'auto'});

function loadTaboola() {
	(function (e, f, u) {
		e.src = u;
		f.parentNode.insertBefore(e, f);
	})(
		document.createElement('script'),
		document.getElementsByTagName('script')[0],
		tmbi_taboola.script
	);
	setTimeout(function () {
		_taboola.push({flush: true});
	},200);
}

var boxElement = null;
window.addEventListener('load', function () {
	boxElement = document.querySelector('[id*=taboola]');
	if (boxElement) {
		createObserver();
	}
}, false);

function createObserver() {
	//check for intersection observer for lazy load of taboola
	if (typeof IntersectionObserver !== 'undefined') {
		var options = {
				root: null,
				rootMargin: '0px',
				threshold: 0.01
			},
			observer = new IntersectionObserver(handleIntersect, options);
		observer.observe(boxElement);
	} else {
		loadTaboola();
	}
}

function handleIntersect(entries, observer) {
	entries.forEach(function (entry) {
		if (entry.isIntersecting) {
			observer.unobserve(boxElement);
			loadTaboola();
		}
	});
}
