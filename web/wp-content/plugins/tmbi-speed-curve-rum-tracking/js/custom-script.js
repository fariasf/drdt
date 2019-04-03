jQuery(document).ready(function ($) {
    LUX = window.LUX || {};
    LUX.label = LUX_label;

    if (!LUX_label) {
        LUX.label = $('title').text();
    }
});