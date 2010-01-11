<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 * 
 * Plugin Name: jQuery Colorbox
 * Plugin URI: http://www.techotronic.de/index.php/plugins/jquery-colorbox/
 * Description: Used to overlay images on the current page. Images in one post are grouped automatically.
 * Version: 1.2
 * Author: Arne Franken
 * Author URI: http://www.techotronic.de
 */
?>
<?php
class jQueryColorbox {
    var $colorboxThemes = array();
    var $colorboxSettings = array();
    var $colorboxDefaultSettings = array();

    /**
     * Plugin initialization 
     */
    function jQueryColorbox() {
        if ( !function_exists('plugins_url') )
            return;

        // it seems that there is no way to find the plugin dir relative to the WP_PLUGIN_DIR through the Wordpress API...
        load_plugin_textdomain( 'jquery-colorbox', false, '/jquery-colorbox/localization/' );

        add_action( 'wp_head',         array(&$this, 'buildWordpressHeader') );
        add_action( 'admin_menu',      array(&$this, 'registerSettingsPage') );
        add_action( 'admin_init',      array(&$this, 'registerSettings') );

        if ( !is_admin() ) {
            wp_enqueue_script( 'colorbox', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ), array( 'jquery' ), '1.3.5' );

            wp_register_style( 'colorbox-theme1', plugins_url( 'themes/theme1/colorbox.css', __FILE__ ), array(), '1.3.5', 'screen' );
            wp_register_style( 'colorbox-theme2', plugins_url( 'themes/theme2/colorbox.css', __FILE__ ), array(), '1.3.5', 'screen' );
            wp_register_style( 'colorbox-theme3', plugins_url( 'themes/theme3/colorbox.css', __FILE__ ), array(), '1.3.5', 'screen' );
            wp_register_style( 'colorbox-theme4', plugins_url( 'themes/theme4/colorbox.css', __FILE__ ), array(), '1.3.5', 'screen' );
            wp_register_style( 'colorbox-theme5', plugins_url( 'themes/theme5/colorbox.css', __FILE__ ), array(), '1.3.5', 'screen' );
        }

        // Create list of themes and their human readable names
        $this->colorboxThemes = (array) apply_filters( 'jquery-colorbox_themes', array(
            'theme1' => __( 'Theme #1', 'jquery-colorbox' ),
            'theme2' => __( 'Theme #2', 'jquery-colorbox' ),
            'theme3' => __( 'Theme #3', 'jquery-colorbox' ),
            'theme4' => __( 'Theme #4', 'jquery-colorbox' ),
            'theme5' => __( 'Theme #5', 'jquery-colorbox' ),
        ) );

        // Create array of default settings (you can use the filter to modify these)
        $colorboxDefaultTheme = key( $this->colorboxThemes );
        $this->colorboxDefaultSettings = array(
            'colorboxTheme' => $colorboxDefaultTheme,
            'maxWidth' => 'false',
            'maxHeight' => 'false',
            'height' => 'false',
            'width' => 'false'
        );

        // Create the settings array by merging the user's settings and the defaults
        $usersettings = (array) get_option('jquery-colorbox_settings');
        $this->colorboxSettings = wp_parse_args( $usersettings, $this->colorboxDefaultSettings );

        // Enqueue the theme in wordpress
        if ( empty($this->colorboxThemes[$this->colorboxSettings['colorboxTheme']]) )
            $this->colorboxSettings['colorboxTheme'] = $this->colorboxDefaultSettings['colorboxTheme'];
        wp_enqueue_style( 'colorbox-' . $this->colorboxSettings['colorboxTheme'] );
    }//jQueryColorbox()

    /**
     * Register the settings page in wordpress
     */
    function registerSettingsPage() {
        static $plugin_basename;
        if ( current_user_can('manage_options') ) {
            $plugin_basename = plugin_basename(__FILE__);
            add_filter( 'plugin_action_links_' . $plugin_basename, array(&$this, 'addPluginActionLinks') );
            add_options_page( __('jQuery Colorbox', 'jquery-colorbox'), __('jQuery Colorbox', 'jquery-colorbox'), 'manage_options', $plugin_basename, array(&$this, 'renderSettingsPage') );
        }
    }//registerSettingsPage()

    /**
     * Add settings link to plugin management page
     *
     * @param  original action_links
     * @return action_links with link to settings page
     */
    function addPluginActionLinks($action_links) {
        static $plugin_basename;
        if ( !$plugin_basename ) $plugin_basename = plugin_basename(__FILE__);
        $settings_link = '<a href="options-general.php?page='.$plugin_basename.'">' . __('Settings', 'jquery-colorbox') . '</a>';
        array_unshift( $action_links, $settings_link );

        return $action_links;
    }//addPluginActionLinks()

    /**
     * Register the plugins settings
     */
    function registerSettings() {
        register_setting( 'jquery-colorbox_settings', 'jquery-colorbox_settings', array(&$this, 'validateSettings') );
    }//registerSettings()

    /**
     * Insert JavaScript for Colorbox into WP Header
     *
     * @return rewritten content or excerpt
     */
    function buildWordpressHeader() {
?>
        <!-- jQuery Colorbox | by Arne Franken, http://www.techotronic.de/ -->
        <script type="text/javascript">
        // <![CDATA[
            jQuery(document).ready(function($){
                //gets all "a" elements that have a nested "img"
                $("a:has(img)").each(function(index, obj){
                    //in this context, the first child is always an image if fundamental Wordpress functions are used
                    var $nestedElement = $(obj).children(0);
                    if($nestedElement.is("img")){
                        var $groupId = $nestedElement.attr("class").match('colorbox-[0-9]+');
                        //only call colorbox if there is a groupId for the image.
                        if($groupId){
                            //and calls colorbox function on each img.
                            //elements with the same groupId in the class attribute are grouped
                            //the title of the img is used as the title for the colorbox.
                            $(obj).colorbox({rel:$groupId.toString(), <?php echo('maxWidth:' . '"' . $this->colorboxSettings['maxWidth'] . '"' . ','); echo('maxHeight:' . '"' . $this->colorboxSettings['maxHeight'] . '"' . ','); echo('height:' . '"' . $this->colorboxSettings['height'] . '"' . ','); echo('width:' . '"' . $this->colorboxSettings['width'] . '"' . ',') ?> title:$nestedElement.attr("title")});
                        }
                    }
                });
            });
        // ]]>
        </script>
<?php
        //write "colorbox-postID" to "img"-tags class attribute.
        //TODO: get rid of this. Slightly better than rewriting links by adding a "rel" attribute, but still ugly.
        //TODO: why doesn't Wordpress provide a filter for img or a tags during output?
        add_filter('the_content', 'addColorboxGroupIdToImages');
        add_filter('the_excerpt', 'addColorboxGroupIdToImages');
    } //buildWordpressHeader()

    /**
     * Render Settings page
     */
    function renderSettingsPage() {
?>
    <div class="wrap">
    <?php screen_icon(); ?>
        <h2><?php _e( 'jQuery Colorbox Settings', 'jquery-colorbox' ); ?></h2>

        <form method="post" action="options.php">

        <?php settings_fields('jquery-colorbox_settings'); ?>

        <p><?php _e( 'Select the theme you want to use on your blog.', 'jquery-colorbox' ); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="jquery-colorbox-theme"><?php _e('Theme', 'jquery-colorbox'); ?></label></th>
                <td>
                    <select name="jquery-colorbox_settings[colorboxTheme]" id="jquery-colorbox-theme" class="postform">
<?php
                        foreach ( $this->colorboxThemes as $theme => $name ) {
                            echo '                  <option value="' . esc_attr($theme) . '"';
                            selected( $this->colorboxSettings['colorboxTheme'], $theme );
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="jquery-colorbox-maxWidth"><?php _e('maxWidth', 'jquery-colorbox'); ?>:</label></td>
                <td><input type="text" name="jquery-colorbox_settings[maxWidth]" id="jquery-colorbox-maxWidth" value="<?php echo $this->colorboxSettings['maxWidth'] ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="setting-description">
					<?php _e('Set the maximum width of the picture in the Colorbox in relation to the browser window. The picture is resized to the appropriate size. Set to either "false" (no maximum width for the picture, picture is as wide as the Colorbox) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><label for="jquery-colorbox-maxHeight"><?php _e('maxHeight', 'jquery-colorbox'); ?>:</label></td>
                <td><input type="text" name="jquery-colorbox_settings[maxHeight]" id="jquery-colorbox-maxHeight" value="<?php echo $this->colorboxSettings['maxHeight'] ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="setting-description">
					<?php _e('Set the maximum height of the picture in the Colorbox in relation to the browser window. The picture is resized to the appropriate size. Set to either "false" (no maximum height for the picture, picture is as high as the Colorbox) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><label for="jquery-colorbox-width"><?php _e('width', 'jquery-colorbox'); ?>:</label></td>
                <td><input type="text" name="jquery-colorbox_settings[width]" id="jquery-colorbox-width" value="<?php echo $this->colorboxSettings['width'] ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="setting-description">
					<?php _e('Set the maximum width of the Colorbox itself in relation to the browser window. The picture is NOT resized, if bigger than the colorbox, scrollbars are displayed. Set to either "false" (no maximum width for Colorbox, Colorbox is as big as the picture in it) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><label for="jquery-colorbox-height"><?php _e('height', 'jquery-colorbox'); ?>:</label></td>
                <td><input type="text" name="jquery-colorbox_settings[height]" id="jquery-colorbox-height" value="<?php echo $this->colorboxSettings['height'] ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="setting-description">
					<?php _e('Set the maximum height of the Colorbox itself in relation to the browser window. The picture is NOT resized, if bigger than the colorbox, scrollbars are displayed. Set to either "false" (no maximum height for Colorbox, Colorbox is as big as the picture in it) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                    </span>
                </td>
            </tr>
        </table>

        <p><?php printf( __('If you would like to make a small (or large) contribution towards future development please consider making a <a href="%1$s" title="%2$s">%2$s</a>.', 'jquery-colorbox'), 'http://www.techotronic.de/index.php/donate/', __('donation','jquery-colorbox') ) ?></p>

        <p class="submit">
            <input type="submit" name="jquery-colorbox-submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

        </form>
    </div>

<?php
    }//renderSettingsPage()

    /**
     * Validate the settings sent from the settings page
     * 
     * @param  $colorboxSettings settings to be validated
     * @return valid settings
     */
    function validateSettings( $colorboxSettings ) {
        if ( empty($colorboxSettings['colorboxTheme']) || empty($this->colorboxThemes[$colorboxSettings['colorboxTheme']]) )
            $colorboxSettings['colorboxTheme'] = $this->colorboxDefaultSettings['colorboxTheme'];
        
        return $colorboxSettings;
    }// validateSettings()
    
}// class jQueryColorbox()
?><?php
/**
 * initialize plugin
 */
function jQueryColorbox() {
    global $jQueryColorbox;
    $jQueryColorbox = new jQueryColorbox();
}//jQueryColorbox()
add_action( 'init', 'jQueryColorbox', 7 );

/**
 * ugly way to group images for Colorbox.
 * 
 * function is called for every page or post rendering.
 *
 * @param  the_content or the_excerpt
 * @return replaced content or excerpt
 */
//TODO: get rid of this...
function addColorboxGroupIdToImages ($content) {
    global $post;
    $pattern = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
    $replacement = '<img$1class=$2$3 colorbox-'.$post->ID.'$4$5>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}//addRefToLinks()
?>