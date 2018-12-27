/**
 * Created by rpandit on 11/11/2016.
 */
jQuery( '#submitdiv' ).on( 'click', '#publish', function( e ) {
    var $checked = jQuery( '#category-all li input:checked' );
    if( $checked.length <= 0 ) {
        gotoScrollElement('categorydiv');
        jQuery("#categorydiv").focus();
        jQuery('[id^="categorydiv"]').after(jQuery('<p style="color:red">Please choose atleast one category name to publish post.</p>'));
        setTimeout(function () {
            jQuery('[id^="categorydiv"]').next('p').remove();
        }, 6000);
        return (false );
    } else {
        return (true);
    }
});
function gotoScrollElement(id){
    jQuery('html,body').animate({ scrollTop: jQuery("#"+id).offset().top }, 'slow');
    return (false);
}