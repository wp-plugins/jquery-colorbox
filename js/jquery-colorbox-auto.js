/*
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 3.1
 * @author Arne Franken
 * @author jrevillini
 *
 * adds colorbox-manual to ALL img tags that are found in the HTML output
 */
jQuery(document).ready(function($) {
    $("img").each( function(index,obj){
        if(!$(obj).attr("class").match('colorbox')) {
            $(obj).addClass('colorbox-manual');
        }
    });
});