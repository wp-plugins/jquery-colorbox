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

/**
 * call colorbox selector function.
 */
jQuery(document).ready(function() {
    colorboxSelector();
});

/**
 * jQuery selector
 *
 * call colorboxImage on all "a" elements that have a nested "img"
 */
(function($) {
    colorboxSelector = function() {
        //set variables for images
        colorboxMaxWidth = colorboxImageMaxWidth;
        colorboxMaxHeight = colorboxImageMaxHeight;
        colorboxHeight = colorboxImageHeight;
        colorboxWidth = colorboxImageWidth;
        $("a:has(img):not(.colorbox-off)").each(function(index, obj) {
            //only go on if link points to an image
            if ($(obj).attr("href").match(COLORBOX_IMG_PATTERN)) {
                colorboxImage(index, obj)
            }
        });

        //call colorboxLink on all elements that have CSS class called "colorbox-link"
        $(COLORBOX_LINK_CLASS).each(function(index, obj) {
            colorboxLink(index, obj)
        });
    }
})(jQuery);

/**
 * colorboxImage
 *
 * selects only links that point to images and sets necessary variables
 */
(function($) {
    colorboxImage = function(index, obj) {
        var $image = $(obj).find("img:first");
        //check if the link has a colorbox class
        var $linkClasses = $(obj).attr("class");
        colorboxGroupId = $linkClasses.match(COLORBOX_CLASS_MATCH) || $linkClasses.match(COLORBOX_MANUAL);
        if (!colorboxGroupId) {
            // link does not have colorbox class. Check if image has colorbox class.
            var $imageClasses = $image.attr("class");
            if (!$imageClasses.match(COLORBOX_OFF)) {
                //groupId is either the automatically created colorbox-123 or the manually added colorbox-manual
                colorboxGroupId = $imageClasses.match(COLORBOX_CLASS_MATCH) || $imageClasses.match(COLORBOX_MANUAL);
            }
            //only call Colorbox if there is a groupId for the image
            if (colorboxGroupId) {
                //convert groupId to string and lose "colorbox-" for easier use
                colorboxGroupId = colorboxGroupId.toString().split('-')[1];

                //if groudId is colorbox-manual, set groupId to "nofollow" so that images are not grouped
                if (colorboxGroupId == "manual") {
                    colorboxGroupId = "nofollow";
                }
                //the title of the img is used as the title for the Colorbox.
                colorboxTitle = $image.attr("title");

                colorboxWrapper(obj);
            }
        }
    }
})(jQuery);

/**
 * colorboxLink
 *
 * sets necessary variables
 */
(function($) {
    colorboxLink = function(index, obj) {
        colorboxTitle = $(obj).attr("title");
        if ($(obj).attr("href").match(COLORBOX_INTERNAL_LINK_PATTERN)) {
            colorboxInline = true;
        } else {
            colorboxIframe = true;
        }
        colorboxGroupId = "nofollow";
        colorboxMaxWidth = false;
        colorboxMaxHeight = false;
        colorboxHeight = colorboxLinkHeight;
        colorboxWidth = colorboxLinkWidth;
        if ($(obj).attr("href").match(COLORBOX_IMG_PATTERN)) {
            colorboxIframe = false;
            colorboxMaxWidth = colorboxImageMaxWidth;
            colorboxMaxHeight = colorboxImageMaxHeight;
            colorboxHeight = colorboxImageHeight;
            colorboxWidth = colorboxImageWidth;
        }
        colorboxWrapper(obj);
    }
})(jQuery);

/**
 * colorboxWrapper
 *
 * actually calls the colorbox function on the links
 * elements with the same groupId in the class attribute are grouped
 */
(function($) {
    colorboxWrapper = function(obj) {
        $(obj).colorbox({
            rel:colorboxGroupId,
            title:colorboxTitle,
            maxHeight:colorboxMaxHeight,
            maxWidth:colorboxMaxWidth,
            initialHeight:colorboxInitialHeight,
            initialWidth:colorboxInitialWidth,
            height:colorboxHeight,
            width:colorboxWidth,
            slideshow:colorboxSlideshow,
            slideshowAuto:colorboxSlideshowAuto,
            scalePhotos:colorboxScalePhotos,
            preloading:colorboxPreloading,
            overlayClose:colorboxOverlayClose,
            loop:colorboxLoop,
            escKey:colorboxEscKey,
            arrowKey:colorboxArrowKey,
            scrolling:colorboxScrolling,
            opacity:colorboxOpacity,
            transition:colorboxTransition,
            speed:colorboxSpeed,
            slideshowSpeed:colorboxSlideshowSpeed,
            close:colorboxClose,
            next:colorboxNext,
            previous:colorboxPrevious,
            slideshowStart:colorboxSlideshowStart,
            slideshowStop:colorboxSlideshowStop,
            current:colorboxCurrent,
            inline:colorboxInline,
            iframe:colorboxIframe
        });
    }
})(jQuery);
