jQuery(document).ready(function ($) {
    $.fn.rdListicle = function () {

        var $items = $(this);

        $items.each(function () {
            var $t = $(this);
            var $carousel = $t.find('.owl-carousel');
            var $counter = $t.find('.listicle-nav span');
            var $prev_link = $t.find('.listicle-nav a:first-child');
            var $next_link = $t.find('.listicle-nav a:last-child');

            var image = new Image();
            image.onload = function () {
                $carousel.owlCarousel({items: 1, autoHeight: true, dots: true, loop: true, nav: true, lazyLoad: true, mouseDrag: false,navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>']});
            }
            image.src =  $carousel.children().eq(0).find('img').attr("src");

            $prev_link.click(function (e) {
                e.preventDefault();
                $carousel.trigger('prev.owl.carousel');
            });

            $next_link.click(function (e) {
                e.preventDefault();
                $carousel.trigger('next.owl.carousel');
            });

            $carousel.on('changed.owl.carousel', function (e) {
                if(e.item.index != null && e.item.index != 0){
                    var exp = (e.item.index+1) - Math.ceil(e.item.count/2);
                    $counter.text((exp == 0 ? e.item.count : exp) + ' / ' + e.item.count);

					if ( exp !== 0 && ( exp % 4 ) === 0 && typeof satellite_track === 'function' ) {
						satellite_track('slideshowadevent');
					}
					if ( typeof do_embedded_slide_click === 'function' ) {
						do_embedded_slide_click();
					}
                }

            });

            $carousel.on('loaded.owl.lazy', function(event) {
                var totitem = event.item.count + 2;
                var nextitem = event.item.index + 1;
                if (nextitem <= totitem) {
                    var imgsrc = $(event.target).find('.owl-item').eq(nextitem).find('img').data('src');
                    $(event.target).find('.item').eq(nextitem).find('img').attr("src", imgsrc);
                }
            });

        });

        return $items;
    };
    $('.listicle-wrap').rdListicle();

});