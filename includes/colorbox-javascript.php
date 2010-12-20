<?php
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
?>

<script type="text/javascript">
    // <![CDATA[
<?php
    /**
    * declare variables that are used in more than one function
    */
    ?>
        var COLORBOX_IMG_PATTERN = /\.(?:jpe?g|gif|png|bmp)/i;
        var COLORBOX_MANUAL = "colorbox-manual";
        var COLORBOX_OFF_CLASS = ".colorbox-off";
        var COLORBOX_LINK_CLASS = ".colorbox-link";
        var COLORBOX_OFF = "colorbox-off";
        var COLORBOX_CLASS_MATCH = "colorbox-[0-9]+";

        var colorboxIframe = false;
        var colorboxGroupId;
        var colorboxTitle;
        var colorboxWidth = false;
        var colorboxHeight = false;
        var colorboxMaxWidth = false;
        var colorboxMaxHeight = false;
        var colorboxSlideshow = <?php echo !$this->colorboxSettings['slideshow'] ? 'false' : 'true'; ?>;
        var colorboxSlideshowAuto = <?php echo $this->colorboxSettings['slideshowAuto'] ? 'true' : 'false';?>;
        var colorboxScalePhotos = <?php echo $this->colorboxSettings['scalePhotos'] ? 'true' : 'false';?>;
        var colorboxPreloading = <?php echo $this->colorboxSettings['preloading'] ? 'true' : 'false';?>;
        var colorboxOverlayClose = <?php echo $this->colorboxSettings['overlayClose'] ? 'true' : 'false';?>;
        var colorboxLoop = <?php echo !$this->colorboxSettings['disableLoop'] ? 'true' : 'false';?>;
        var colorboxEscKey = <?php echo !$this->colorboxSettings['disableKeys'] ? 'true' : 'false';?>;
        var colorboxArrowKey = <?php echo !$this->colorboxSettings['disableKeys'] ? 'true' : 'false';?>;
        var colorboxScrolling = <?php echo !$this->colorboxSettings['displayScrollbar'] || $this->colorboxSettings['draggable'] ? 'true' : 'false';?>;
        var colorboxOpacity = "<?php echo $this->colorboxSettings['opacity']; ?>";
        var colorboxTransition = "<?php echo $this->colorboxSettings['transition']; ?>";
        var colorboxSpeed = <?php echo $this->colorboxSettings['speed']; ?>;
        var colorboxSlideshowSpeed = <?php echo $this->colorboxSettings['slideshowSpeed']; ?>;
        var colorboxClose = "<?php _e('close', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
        var colorboxNext = "<?php _e('next', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
        var colorboxPrevious = "<?php _e('previous', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
        var colorboxSlideshowStart = "<?php _e('start slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
        var colorboxSlideshowStop = "<?php _e('stop slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
        var colorboxCurrent = "<?php _e('{current} of {total} images', JQUERYCOLORBOX_TEXTDOMAIN); ?>";
<?php
    /**
     * jQuery selector
     *
     * call colorboxImage on all "a" elements that have a nested "img"
     */
    ?>
    (function($) {
        colorboxSelector = function() {

            <?php //set variables for images ?>
            colorboxMaxWidth = <?php echo $this->colorboxSettings['maxWidth'] == "false" ? 'false' : '"' . $this->colorboxSettings['maxWidthValue'] . $this->colorboxSettings['maxWidthUnit'] . '"'; ?>;
            colorboxMaxHeight = <?php echo $this->colorboxSettings['maxHeight'] == "false" ? 'false' : '"' . $this->colorboxSettings['maxHeightValue'] . $this->colorboxSettings['maxHeightUnit'] . '"'; ?>;
            colorboxHeight = <?php echo $this->colorboxSettings['height'] == "false" ? 'false' : '"' . $this->colorboxSettings['heightValue'] . $this->colorboxSettings['heightUnit'] . '"'; ?>;
            colorboxWidth = <?php echo $this->colorboxSettings['width'] == "false" ? 'false' : '"' . $this->colorboxSettings['widthValue'] . $this->colorboxSettings['widthUnit'] . '"'; ?>;
            $("a:has(img):not(.colorbox-off)").each(function(index, obj) {
            <?php //only go on if link points to an image ?>
                if ($(obj).attr("href").match(COLORBOX_IMG_PATTERN)) {
                    colorboxImage(index, obj)
                }
            });
            <?php //set variables for links ?>
            colorboxMaxWidth = false;
            colorboxMaxHeight = false;
            colorboxHeight = <?php echo $this->colorboxSettings['height'] == "false" ? 'false' : '"' . $this->colorboxSettings['heightValue'] . $this->colorboxSettings['heightUnit'] . '"'; ?>;
            //colorboxHeight = <?php //echo $this->colorboxSettings['linkHeight'] == "false" ? 'false' : '"' . $this->colorboxSettings['linkHeightValue'] . $this->colorboxSettings['linkHeightUnit'] . '"'; ?>;
            colorboxWidth = <?php echo $this->colorboxSettings['width'] == "false" ? 'false' : '"' . $this->colorboxSettings['widthValue'] . $this->colorboxSettings['widthUnit'] . '"'; ?>;
            //colorboxWidth = <?php //echo $this->colorboxSettings['linkWidth'] == "false" ? 'false' : '"' . $this->colorboxSettings['linkWidthValue'] . $this->colorboxSettings['linkWidthUnit'] . '"'; ?>;
            <?php //call colorboxLink on all elements that have CSS class called "colorbox-link" ?>
            $(COLORBOX_LINK_CLASS).each(function(index, obj) {
                colorboxLink(index, obj)
            });
        }
    })(jQuery);
<?php
    /**
     * colorboxImage
     *
     * selects only links that point to images and sets necessary variables
     */
    ?>
    (function($) {
        colorboxImage = function(index, obj) {
        <?php //in this context, the first child is always an image if fundamental Wordpress functions are used ?>
            var $image = $(obj).children(0);
            if ($image.is("img")) {
            <?php //check if the link has a colorbox class ?>
                var $linkClasses = $(obj).attr("class");
            <?php //groupId is either the automatically created colorbox-123 or the manually added colorbox-manual ?>
                colorboxGroupId = $linkClasses.match(COLORBOX_CLASS_MATCH) || $linkClasses.match(COLORBOX_MANUAL);
                if (!colorboxGroupId) {
                <?php // link does not have colorbox class. Check if image has colorbox class. ?>
                    var $imageClasses = $image.attr("class");
                    if (!$imageClasses.match(COLORBOX_OFF)) {
                    <?php //groupId is either the automatically created colorbox-123 or the manually added colorbox-manual ?>
                        colorboxGroupId = $imageClasses.match(COLORBOX_CLASS_MATCH) || $imageClasses.match(COLORBOX_MANUAL);
                    }
                <?php //only call Colorbox if there is a groupId for the image?>
                    if (colorboxGroupId) {
                    <?php //convert groupId to string and lose "colorbox-" for easier use ?>
                        colorboxGroupId = colorboxGroupId.toString().split('-')[1];
                    <?php  //if groudId is colorbox-manual, set groupId to "nofollow" so that images are not grouped ?>
                        if (colorboxGroupId == "manual") {
                            colorboxGroupId = "nofollow";
                        }
                    <?php //the title of the img is used as the title for the Colorbox. ?>
                        colorboxTitle = $image.attr("title");

                        colorboxWrapper(obj);
                    }
                }
            }
        }
    })(jQuery);
<?php
    /**
     * colorboxLink
     *
     * sets necessary variables
     */
    ?>
    (function($) {
        colorboxLink = function(index, obj) {
            colorboxTitle = $(obj).attr("title");
            colorboxIframe = true;
            colorboxGroupId = "nofollow";
            if ($(obj).attr("href").match(COLORBOX_IMG_PATTERN)) {
                colorboxIframe = false;
            }
            colorboxWrapper(obj);
        }
    })(jQuery);

<?php
    /**
     * colorboxWrapper
     *
     * actually calls the colorbox function on the links
     * elements with the same groupId in the class attribute are grouped
     */
    ?>
    (function($) {
        colorboxWrapper = function(obj) {
            $(obj).colorbox({
                rel:colorboxGroupId,
                title:colorboxTitle,
                maxHeight:colorboxMaxHeight,
                maxWidth:colorboxMaxWidth,
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
                iframe:colorboxIframe
            });
        }
    })(jQuery);
    // ]]>
</script>