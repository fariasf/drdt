jQuery(document).ready(function($) {
    $("div.toh-sharing a img").on("click",function(e){ 
        e.preventDefault();
        var social_label = $(this).attr("alt");
        if( typeof Krux != 'undefined' ){
            switch(social_label) {
                case 'facebook' :
                    Krux('ns:trustedmediabrandsinc', 'admEvent', 'MXz_PsA3', 'social', {});
                    break;
                case 'Pinterest' :
                    Krux('ns:trustedmediabrandsinc', 'admEvent', 'MX0AFyA9', 'social', {});
                    break;
                case 'twitter':
                    Krux('ns:trustedmediabrandsinc', 'admEvent', 'MX0AcG25', 'social', {});
                    break;
                case 'Linkedin':
                    Krux('ns:trustedmediabrandsinc', 'admEvent', 'MX0Anbbs', 'social', {});
                    break;
                case 'Email' :
                    Krux('ns:trustedmediabrandsinc', 'admEvent', 'MX0Kvd42', 'fd', {});
                    break;
                default:
                    break;
            }
        }
    });
});