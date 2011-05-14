/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 3.2
 * @author Arne Franken
 * @author Fabian Wolf (http://usability-idealist.de/)
 * @author Jason Stapels (jstapels@realmprojects.com)
 *
 * Colorbox Javascript
 */
var COLORBOX_INTERNAL_LINK_PATTERN = /^#.*/;
var COLORBOX_SUFFIX_PATTERN = /\.(?:jpe?g|gif|png|bmp)/i;
var COLORBOX_MANUAL = "colorbox-manual";
var COLORBOX_OFF_CLASS = ".colorbox-off";
var COLORBOX_LINK_CLASS = ".colorbox-link";
var COLORBOX_OFF = "colorbox-off";
var COLORBOX_CLASS_MATCH = "colorbox-[0-9]+";

/**
 * This block calls all functions on page load.
 */
jQuery(document).ready(function() {
    if(Colorbox.autoColorboxJavaScript == "true") {
        colorboxAddManualClass();
    }
    if(Colorbox.colorboxAddClassToLinks == "true") {
        colorboxAddClassToLinks();
    }
    if(Colorbox.autoHideFlash == "true") {
        colorboxHideFlash();
        colorboxShowFlash();
    }
    colorboxSelector();
});

/**
 * colorboxShowFlash
 *
 * show embedded flash objects when Colorbox closes
 */
(function(jQuery) {
    colorboxShowFlash = function() {
        jQuery(document).bind('cbox_closed', function(){
            var flashObjects = document.getElementsByTagName("object");
            for (i = 0; i < flashObjects.length; i++) {
                flashObjects[i].style.visibility = "visible";
            }
            var flashEmbeds = document.getElementsByTagName("embed");
            for (i = 0; i < flashEmbeds.length; i++) {
                flashEmbeds[i].style.visibility = "visible";
            }
        });
    }
})(jQuery);

// colorboxShowFlash()

/**
 * colorboxHideFlash
 *
 * hide embedded flash objects when Colorbox opens
 */
(function(jQuery) {
    colorboxHideFlash = function() {
        jQuery(document).bind('cbox_open', function(){
        var flashObjects = document.getElementsByTagName("object");
        for (i = 0; i < flashObjects.length; i++) {
            flashObjects[i].style.visibility = "hidden";
        }
        var flashEmbeds = document.getElementsByTagName("embed");
        for (i = 0; i < flashEmbeds.length; i++) {
            flashEmbeds[i].style.visibility = "hidden";
        }
    });
    }
})(jQuery);

// colorboxHideFlash()

/**
 * colorboxAddClassToLinks
 *
 * add colorbox-link to anchor tags
 */
(function(jQuery) {
    colorboxAddClassToLinks = function() {
        jQuery("a:not(:contains(img))").each( function(index,obj){
            if(!jQuery(obj).attr("class").match('colorbox')) {
                if (jQuery(obj).attr("href") && jQuery(obj).attr("href").match(COLORBOX_SUFFIX_PATTERN)) {
                    jQuery(obj).addClass('colorbox-link');
                }
            }
        });
    }
})(jQuery);

// colorboxAddClassToLinks()

/**
 * colorboxAddManualClass
 *
 * add colorbox-manual to ALL img tags
 */
(function(jQuery) {
    colorboxAddManualClass = function() {
        jQuery("img").each( function(index,obj){
            if(!jQuery(obj).attr("class").match('colorbox')) {
                jQuery(obj).addClass('colorbox-manual');
            }
        });
    }
})(jQuery);

// colorboxAddManualClass()

/**
 * colorboxSelector
 *
 * call colorboxImage on all "a" elements that have a nested "img"
 */
(function(jQuery) {
    colorboxSelector = function() {
        //set variables for images
        Colorbox.colorboxMaxWidth = Colorbox.colorboxImageMaxWidth;
        Colorbox.colorboxMaxHeight = Colorbox.colorboxImageMaxHeight;
        Colorbox.colorboxHeight = Colorbox.colorboxImageHeight;
        Colorbox.colorboxWidth = Colorbox.colorboxImageWidth;

        jQuery("a:has(img):not(.colorbox-off)").each(function(index, obj) {
            if (jQuery(obj).attr("href") && jQuery(obj).attr("href").match(COLORBOX_SUFFIX_PATTERN)) {
                colorboxImage(index, obj)
            } else {
                colorboxLink(index, obj)
            }
        });

        jQuery(COLORBOX_LINK_CLASS).each(function(index, obj) {
            colorboxLink(index, obj)
        });
    }
})(jQuery);

// colorboxSelector()

/**
 * colorboxImage
 *
 * selects only links that point to images and sets necessary variables
 */
(function(jQuery) {
    colorboxImage = function(index, obj) {
        var $image = jQuery(obj).find("img:first");
        //check if the link has a colorbox class
        var $linkClasses = jQuery(obj).attr("class");
        Colorbox.colorboxGroupId = $linkClasses.match(COLORBOX_CLASS_MATCH) || $linkClasses.match(COLORBOX_MANUAL);
        if (!Colorbox.colorboxGroupId) {
            // link does not have colorbox class. Check if image has colorbox class.
            var $imageClasses = $image.attr("class");
            if (!$imageClasses.match(COLORBOX_OFF)) {
                //groupId is either the automatically created colorbox-123 or the manually added colorbox-manual
                Colorbox.colorboxGroupId = $imageClasses.match(COLORBOX_CLASS_MATCH) || $imageClasses.match(COLORBOX_MANUAL);
            }
            //only call Colorbox if there is a groupId for the image
            if (Colorbox.colorboxGroupId) {
                //convert groupId to string and lose "colorbox-" for easier use
                Colorbox.colorboxGroupId = Colorbox.colorboxGroupId.toString().split('-')[1];

                //if groudId is colorbox-manual, set groupId to "nofollow" so that images are not grouped
                if (Colorbox.colorboxGroupId === "manual") {
                    Colorbox.colorboxGroupId = "nofollow";
                }
                //the title of the img is used as the title for the Colorbox.
                Colorbox.colorboxTitle = $image.attr("title");

                colorboxWrapper(obj);
            }
        }
    }
})(jQuery);

// colorboxImage()

/**
 * colorboxLink
 *
 * sets necessary variables
 */
(function(jQuery) {
    colorboxLink = function(index, obj) {
        Colorbox.colorboxTitle = jQuery(obj).attr("title");
        if (jQuery(obj).attr("href") && jQuery(obj).attr("href").match(COLORBOX_INTERNAL_LINK_PATTERN)) {
            Colorbox.colorboxInline = true;
        } else {
            Colorbox.colorboxIframe = true;
        }
        Colorbox.colorboxGroupId = "nofollow";
        Colorbox.colorboxMaxWidth = false;
        Colorbox.colorboxMaxHeight = false;
        Colorbox.colorboxHeight = Colorbox.colorboxLinkHeight;
        Colorbox.colorboxWidth = Colorbox.colorboxLinkWidth;
        if (jQuery(obj).attr("href").match(COLORBOX_SUFFIX_PATTERN)) {
            Colorbox.colorboxIframe = false;
            Colorbox.colorboxMaxWidth = Colorbox.colorboxImageMaxWidth;
            Colorbox.colorboxMaxHeight = Colorbox.colorboxImageMaxHeight;
            Colorbox.colorboxHeight = Colorbox.colorboxImageHeight;
            Colorbox.colorboxWidth = Colorbox.colorboxImageWidth;
        }
        colorboxWrapper(obj);
    }
})(jQuery);

// colorboxLink()

/**
 * colorboxWrapper
 *
 * actually calls the colorbox function on the links
 * elements with the same groupId in the class attribute are grouped
 */
(function(jQuery) {
    colorboxWrapper = function(obj) {
        //workaround for wp_localize_script behavior:
        //the function puts booleans as strings into the "Colorbox" array...
        jQuery.each(Colorbox, function(key, value) {
            if(value === "false") {
                Colorbox[key] = false;
            } else if (value === "true") {
                Colorbox[key] = true;
            }
        });

        jQuery(obj).colorbox({
            rel:Colorbox.colorboxGroupId,
            title:Colorbox.colorboxTitle,
            maxHeight:Colorbox.colorboxMaxHeight,
            maxWidth:Colorbox.colorboxMaxWidth,
            initialHeight:Colorbox.colorboxInitialHeight,
            initialWidth:Colorbox.colorboxInitialWidth,
            height:Colorbox.colorboxHeight,
            width:Colorbox.colorboxWidth,
            slideshow:Colorbox.colorboxSlideshow,
            slideshowAuto:Colorbox.colorboxSlideshowAuto,
            scalePhotos:Colorbox.colorboxScalePhotos,
            preloading:Colorbox.colorboxPreloading,
            overlayClose:Colorbox.colorboxOverlayClose,
            loop:Colorbox.colorboxLoop,
            escKey:Colorbox.colorboxEscKey,
            arrowKey:Colorbox.colorboxArrowKey,
            scrolling:Colorbox.colorboxScrolling,
            opacity:Colorbox.colorboxOpacity,
            transition:Colorbox.colorboxTransition,
            speed:parseInt(Colorbox.colorboxSpeed),
            slideshowSpeed:parseInt(Colorbox.colorboxSlideshowSpeed),
            close:Colorbox.colorboxClose,
            next:Colorbox.colorboxNext,
            previous:Colorbox.colorboxPrevious,
            slideshowStart:Colorbox.colorboxSlideshowStart,
            slideshowStop:Colorbox.colorboxSlideshowStop,
            current:Colorbox.colorboxCurrent,
            inline:Colorbox.colorboxInline,
            iframe:Colorbox.colorboxIframe
        });
    }
})(jQuery);

// colorboxWrapper()