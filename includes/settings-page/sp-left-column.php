<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fourmyle
 * Date: 02.05.11
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="postbox-container" style="width: 69%;">
    <form name="jquery-colorbox-settings-update" method="post" action="admin-post.php">
        <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-colorbox-settings-form'); ?>
        <div id="poststuff">
            <?php
                require_once 'sp-plugin-settings.php';
            ?>
            <?php
                require_once 'sp-colorbox-settings.php';
            ?>
        </div>
    </form>
    <?php
        require_once 'sp-delete-settings.php';
    ?>
</div>