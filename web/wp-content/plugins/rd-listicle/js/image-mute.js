/**
 * Created by rpandit on 10/13/2016.
 */
jQuery(document).ready(function($) {
    $('*[id*=listicle-image-attr-list-version]:visible').each(function() {
        $(this).css('display', 'none');
        if ( $('*[id*=listicle-image-attr-list-version]').next().hasClass('credits-overlay' ) ) {
            $('*[class*=credits-overlay]').css('display', 'none');
        }
    });
});