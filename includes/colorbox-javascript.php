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
    var $iframe = false;
    var $groupId;
    var $title;

<?php
    /**
     * jQuery selector
     *
     * call colorboxImage on all "a" elements that have a nested "img"
     */
    ?>
    (function($) {
        colorboxSelector = function() {
            $("a:has(img):not(.colorbox-off)").each(function(index, obj) {
            <?php //only go on if link points to an image ?>
                if ($(obj).attr("href").match(/\.(?:jpe?g|gif|png|bmp)/i)) {
                    colorboxImage(index, obj)
                }
            });
            <?php //call colorboxLink on all elements that have CSS class called "colorbox-link" ?>
            $(".colorbox-link").each(function(index, obj) {
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
                $groupId = $linkClasses.match('colorbox-[0-9]+') || $linkClasses.match('colorbox-manual');
                if (!$groupId) {
                <?php // link does not have colorbox class. Check if image has colorbox class. ?>
                    var $imageClasses = $image.attr("class");
                    if (!$imageClasses.match('colorbox-off')) {
                    <?php //groupId is either the automatically created colorbox-123 or the manually added colorbox-manual ?>
                        $groupId = $imageClasses.match('colorbox-[0-9]+') || $imageClasses.match('colorbox-manual');
                    }
                <?php //only call Colorbox if there is a groupId for the image?>
                    if ($groupId) {
                    <?php //convert groupId to string for easier use ?>
                        $groupId = $groupId.toString();
                    <?php  //if groudId is colorbox-manual, set groupId to "nofollow" so that images are not grouped ?>
                        if ($groupId == "colorbox-manual") {
                            $groupId = "nofollow";
                        }
                    <?php //the title of the img is used as the title for the Colorbox. ?>
                        $title = $image.attr("title");

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
            $title = $(obj).attr("title");
            $iframe = true;
            $groupId = "nofollow";
            if ($(obj).attr("href").match(/\.(?:jpe?g|gif|png|bmp)/i)) {
                $iframe = false;
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
                rel:$groupId,
                title:$title,
            <?php echo $this->colorboxSettings['maxWidth'] == "false" ? '' : 'maxWidth:"' . $this->colorboxSettings['maxWidthValue'] . $this->colorboxSettings['maxWidthUnit'] . '",';
            echo $this->colorboxSettings['maxHeight'] == "false" ? '' : 'maxHeight:"' . $this->colorboxSettings['maxHeightValue'] . $this->colorboxSettings['maxHeightUnit'] . '",';
            echo $this->colorboxSettings['height'] == "false" ? '' : 'height:"' . $this->colorboxSettings['heightValue'] . $this->colorboxSettings['heightUnit'] . '",';
            echo $this->colorboxSettings['width'] == "false" ? '' : 'width:"' . $this->colorboxSettings['widthValue'] . $this->colorboxSettings['widthUnit'] . '",';
            echo !$this->colorboxSettings['slideshow'] ? '' : 'slideshow:true,';
            echo $this->colorboxSettings['slideshowAuto'] ? '' : 'slideshowAuto:false,';
            echo $this->colorboxSettings['scalePhotos'] ? '' : 'scalePhotos:false,';
            echo $this->colorboxSettings['preloading'] ? '' : 'preloading:false,';
            echo $this->colorboxSettings['overlayClose'] ? '' : 'overlayClose:false,';
            echo !$this->colorboxSettings['displayScrollbar'] || $this->colorboxSettings['draggable'] ? '' : 'scrolling:false,';?>
                opacity:"<?php echo $this->colorboxSettings['opacity']; ?>",
                transition:"<?php echo $this->colorboxSettings['transition']; ?>",
                speed:<?php echo $this->colorboxSettings['speed']; ?>,
                slideshowSpeed:<?php echo $this->colorboxSettings['slideshowSpeed']; ?>,
                close:"<?php _e('close', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                next:"<?php _e('next', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                previous:"<?php _e('previous', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                slideshowStart:"<?php _e('start slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                slideshowStop:"<?php _e('stop slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                current:"<?php _e('{current} of {total} images', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                iframe:$iframe
            });
        }
    })(jQuery);
    // ]]>
</script>

<script type="text/javascript">
    // <![CDATA[
    <?php
     /**
      * call colorbox selector function.
      */
     ?>
    jQuery(document).ready(function($) {
        colorboxSelector();
    });
    // ]]>
</script>