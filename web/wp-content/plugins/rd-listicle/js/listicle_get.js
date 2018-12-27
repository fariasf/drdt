jQuery( document ).ready(function($) {
    //From localized variables
    //number_of_pages, pages_set, post_id, method, url

    var page_set = LIST.restapi.pages_set;
    var number_of_pages = LIST.restapi.number_of_pages;
    var post_id  = LIST.restapi.post_id;
    var rest_access_next_url = LIST.restapi.nexturl;
    //var rest_access_prev_url = LIST.restapi.prevurl;
    var method   = LIST.restapi.method;
    var next_slideshow = LIST.next_slideshow;


    var next_btn_class = $( '.js--listicle-nav.js--next');
    if ( typeof next_slideshow !== 'undefined' ) {
		next_btn_class.find( '.slideout_next' ).each( function() {
			$(this).find('.title').html( next_slideshow.title );
			$(this).find('.image img').attr( 'src', next_slideshow.thumb );
		});
		//next_btn_class.attr( 'href', next_slideshow.url );
	}


    $(".js--listicle-nav.js--next").click(function(e){

        var $current_card = $('.rd-card-index');
		var $next_button  = $('.js--next');

        if ( number_of_pages <= parseInt( $current_card[0].innerHTML ) ) {
            if ( typeof satellite_track === 'function' ) {
                satellite_track( 'slideshownext' );
            }
            window.location = LIST.next_slideshow.url;
            e.preventDefault();
            return;
        }

        if ( ! $(this).attr('href') ) {
            return;
        }

        var $current = $('.rd-listicle .rd-listicle-page:not(.rd-card-hidden)');
        var $prev_button = $('.js--prev');
        var recall = false;
        var $next = $current['next']('.rd-listicle-page');

        requestingPageNo = parseInt( $(this).attr('href').match(/\d+/) ); // page number from next button url
        currentIdx = $current.attr('class').match(/\blisticle-page-(\d+)\b/);

        if ( typeof set_digitalData_image_credits === 'function' ) {
			set_digitalData_image_credits( $current );
		}

        // If $next set
        //remove type undefined error
        if( typeof $next.attr('class') != 'undefined' ) {
            nextIdx = $next.attr('class').match(/\blisticle-page-(\d+)\b/)
            nextIdx = nextIdx && parseInt(nextIdx[1]);
        } else {
            if (requestingPageNo < number_of_pages ) {
                nextIdx = requestingPageNo;
                recall = true;
            }
        }

        currentIdx = currentIdx && parseInt(currentIdx[1]);

        //( current page / set of pages ) + ( set of pages - 2) =====> load another set of slides
        var page_floor = Math.floor( currentIdx / page_set );
        var reload_on  = page_floor * page_set + ( page_set - 2 ); // Reload on page set less than 2
        var previous   = page_floor * page_set - ( page_set - 2 );

        if ( currentIdx == reload_on || recall == true ) { //if $('.rd_listicle .rd-listicle-page:not(.listicle-page-'reload_on + 3')')
            //load another set of slides
            rest_url = rest_access_next_url + post_id + '/' + currentIdx;

            var posting = $.get( rest_url )
                .success(function(response) {
                    for ( var i = 0, len = response.length; i < len; i++ ) {
                        if(parseInt(response[i].page) == requestingPageNo ){
                            $( ".rd-listicle" ).append('<div class="rd-listicle-page listicle-page-' + response[i].page +'">' + response[i].data + '</div>');
                        } else {
                            $(".rd-listicle").append('<div class="rd-listicle-page listicle-page-' + response[i].page + ' rd-card-hidden">' + response[i].data + '</div>');
                        }
                    }
                });
        }
        //reload next set of pages

        $current_card.html(currentIdx+1);

        history.replaceState(
            // No data
            null,
            // Replace title
            document.title,
            // Use given link
            $next_button.attr('href')
        );
        if( nextIdx === number_of_pages ) {
            $next_button.data('href', $next_button.attr('href'));
            $next_button.attr( 'href', LIST.next_slideshow.url );
            $(next_btn_class).addClass( 'last_slide' );
            $(next_btn_class).find('.listicle-article--page-link--label').html( 'Next Slideshow' );
        }else {
            $next_button.attr('href', ($next_button.attr('href') || $next_button.data('href')).replace(/\/\d+\//, '/' + (nextIdx + 1) + '/'));
        }

        $prev_button.attr('href', ($prev_button.attr('href') || $prev_button.data('href')).replace(/\/\d+\//, '/' + (nextIdx - 1) + '/'));
        $current.addClass('rd-card-hidden'); // hide current page
        $next.removeClass('rd-card-hidden'); // show next page
		if ( typeof comscore_track === 'function' ) {
			comscore_track();
		}
        e.preventDefault();
    });

    $(".js--listicle-nav.js--prev").click(function(e){
        if ( ! $(this).attr('href') ) {
            return;
        }

        var $current = $('.rd-listicle .rd-listicle-page:not(.rd-card-hidden)');
        var $prev_button = $('.js--prev');
        var $next_button = $('.js--next');

        var $current_card = $('.rd-card-index');

        var $next = $current['prev']('.rd-listicle-page');


        currentIdx = $current.attr('class').match(/\blisticle-page-(\d+)\b/);


        if( typeof $next.attr('class') != 'undefined') {
            nextIdx = $next.attr('class').match(/\blisticle-page-(\d+)\b/)
        } else {
            return;
        }

        nextIdx = $next.attr('class').match(/\blisticle-page-(\d+)\b/);

        currentIdx = currentIdx && parseInt(currentIdx[1]);
        nextIdx = nextIdx && parseInt(nextIdx[1]);

		if ( typeof set_digitalData_image_credits === 'function' ) {
			set_digitalData_image_credits( $current );
		}

        //( current page / set of pages ) + ( set of pages - 2) =====> load another set of slides
        var page_floor = Math.floor( currentIdx / page_set );
        var reload_on  = page_floor + ( page_set - 2 ); // Reload on page set less than 2

        $current_card.html(currentIdx-1);

        history.replaceState(
            // No data
            null,
            // Replace title
            document.title,
            // Use given link
            $prev_button.attr('href')
        );
        if ( nextIdx == 1 ) {
            $prev_button.data('href', ($prev_button.attr('href') || $prev_button.data('href')).replace(/\/\d+\//, '/' + (nextIdx + 1) + '/'));
            $prev_button.removeAttr('href');
        }else{
            $prev_button.attr('href', ($prev_button.attr('href') || $prev_button.data('href')).replace(/\/\d+\//, '/' + (nextIdx - 1) + '/'));
        }

        $next_button.attr('href', ($prev_button.attr('href') || $prev_button.data('href')).replace(/\/\d+\//, '/' + (nextIdx + 1) + '/'));
        $current.addClass('rd-card-hidden');
        $next.removeClass('rd-card-hidden');
		next_btn_class.removeClass( 'last_slide' );
		next_btn_class.find('.listicle-article--page-link--label').html( 'Next' );
		if ( typeof comscore_track === 'function' ) {
			comscore_track();
		}
		e.preventDefault();
    });

	if( $('#taboola-placeholder').html() == "" ) {
	    $('#taboola-placeholder').closest('.listicle-page-group-container').hide();
	}
});
