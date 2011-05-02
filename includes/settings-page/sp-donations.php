<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 4.1
 * @author Arne Franken
 *
 * Donations for settings page
 */
?>
<div id="poststuff">
    <div id="jquery-colorbox-topdonations" class="postbox">
        <h3 id="topdonations"><?php _e('Top donations', JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

        <div class="inside">
            <?php echo $this->getRemoteContent(JQUERYCOLORBOX_TOPDONATEURL); ?>
        </div>
    </div>
</div>
<div id="poststuff">
    <div id="jquery-colorbox-latestdonations" class="postbox">
        <h3 id="latestdonations"><?php _e('Latest donations', JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

        <div class="inside">
            <?php echo $this->getRemoteContent(JQUERYCOLORBOX_LATESTDONATEURL); ?>
        </div>
    </div>
</div>