<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * Plugin Name: jQuery Colorbox
 * Plugin URI: http://www.techotronic.de/plugins/jquery-colorbox/
 * Description: Used to overlay images on the current page. Images in one post are grouped automatically.
 * Version: 4.0.1
 * Author: Arne Franken
 * Author URI: http://www.techotronic.de/
 * License: GPL
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
?>
<?php
//define constants
define('JQUERYCOLORBOX_VERSION', '4.0.1');
define('COLORBOXLIBRARY_VERSION', '1.3.16');

if (!defined('JQUERYCOLORBOX_PLUGIN_BASENAME')) {
    define('JQUERYCOLORBOX_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('JQUERYCOLORBOX_PLUGIN_NAME')) {
    define('JQUERYCOLORBOX_PLUGIN_NAME', trim(dirname(JQUERYCOLORBOX_PLUGIN_BASENAME), '/'));
}
if (!defined('JQUERYCOLORBOX_NAME')) {
    define('JQUERYCOLORBOX_NAME', 'jQuery Colorbox');
}
if (!defined('JQUERYCOLORBOX_TEXTDOMAIN')) {
    define('JQUERYCOLORBOX_TEXTDOMAIN', 'jquery-colorbox');
}
if (!defined('JQUERYCOLORBOX_PLUGIN_DIR')) {
    define('JQUERYCOLORBOX_PLUGIN_DIR', dirname(__FILE__));
}
if (!defined('JQUERYCOLORBOX_PLUGIN_URL')) {
    define('JQUERYCOLORBOX_PLUGIN_URL', WP_PLUGIN_URL . '/' . JQUERYCOLORBOX_PLUGIN_NAME);
}
if (!defined('JQUERYCOLORBOX_PLUGIN_LOCALIZATION_DIR')) {
    define('JQUERYCOLORBOX_PLUGIN_LOCALIZATION_DIR', JQUERYCOLORBOX_PLUGIN_DIR . '/localization');
}
if (!defined('JQUERYCOLORBOX_SETTINGSNAME')) {
    define('JQUERYCOLORBOX_SETTINGSNAME', 'jquery-colorbox_settings');
}
if (!defined('JQUERYCOLORBOX_LATESTDONATEURL')) {
    define('JQUERYCOLORBOX_LATESTDONATEURL', 'http://colorbox.techotronic.de/latest-donations.php');
}
if (!defined('JQUERYCOLORBOX_TOPDONATEURL')) {
    define('JQUERYCOLORBOX_TOPDONATEURL', 'http://colorbox.techotronic.de/top-donations.php');
}

/**
 * Main plugin class
 *
 * @since 1.0
 * @author Arne Franken
 */
class JQueryColorbox {

    /**
     * Constructor
     * Plugin initialization
     *
     * @since 1.0
     * @access public
     * @access static
     * @author Arne Franken
     */
    //public static function JQueryColorbox() {
    function JQueryColorbox() {
        if (!function_exists('plugins_url')) {
            return;
        }

        load_plugin_textdomain(JQUERYCOLORBOX_TEXTDOMAIN, false, '/jquery-colorbox/localization/');

        // Create the settings array by merging the user's settings and the defaults
        $usersettings = (array) get_option(JQUERYCOLORBOX_SETTINGSNAME);
        $defaultArray = $this->jQueryColorboxDefaultSettings();
        $this->colorboxSettings = wp_parse_args($usersettings, $defaultArray);

        // Create list of themes and their human readable names
        $this->colorboxThemes = array(
            'theme1' => __('Theme #1', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme2' => __('Theme #2', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme3' => __('Theme #3', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme4' => __('Theme #4', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme5' => __('Theme #5', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme6' => __('Theme #6', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme7' => __('Theme #7', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme8' => __('Theme #8', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme9' => __('Theme #9', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme10' => __('Theme #10', JQUERYCOLORBOX_TEXTDOMAIN),
            'theme11' => __('Theme #11', JQUERYCOLORBOX_TEXTDOMAIN)
        );

//        $this->colorboxThemes = array_merge($this->getThemeDirs(),$this->colorboxThemes);

        $dummyThemeNumberArray = array(
            __('Theme #12', JQUERYCOLORBOX_TEXTDOMAIN),
            __('Theme #13', JQUERYCOLORBOX_TEXTDOMAIN),
            __('Theme #14', JQUERYCOLORBOX_TEXTDOMAIN),
            __('Theme #15', JQUERYCOLORBOX_TEXTDOMAIN)
        );

        // create list of units
        $this->colorboxUnits = array(
            '%' => __('percent', JQUERYCOLORBOX_TEXTDOMAIN),
            'px' => __('pixels', JQUERYCOLORBOX_TEXTDOMAIN)
        );

        // create list of units
        $this->colorboxTransitions = array(
            'elastic' => __('elastic', JQUERYCOLORBOX_TEXTDOMAIN),
            'fade' => __('fade', JQUERYCOLORBOX_TEXTDOMAIN),
            'none' => __('none', JQUERYCOLORBOX_TEXTDOMAIN)
        );


        if (is_admin()) {
            require_once 'includes/jquery-colorbox-backend.php';
            new JQueryColorboxBackend($this->colorboxSettings, $this->colorboxThemes, $this->colorboxUnits, $this->colorboxTransitions, $this->jQueryColorboxDefaultSettings());
        } else {
            require_once 'includes/jquery-colorbox-frontend.php';
            new JQueryColorboxFrontend($this->colorboxSettings);
        }
        
    }

    // JQueryColorbox()

    /**
     * Default array of jQuery Colorbox settings
     *
     * @since 2.0
     * @access private
     * @author Arne Franken
     *
     * @return array of default settings
     */
    //private function jQueryColorboxDefaultSettings() {
    function jQueryColorboxDefaultSettings() {

        // Create and return array of default settings
        return array(
            'jQueryColorboxVersion' => JQUERYCOLORBOX_VERSION,
            'colorboxTheme' => 'theme1',
            'maxWidth' => 'false',
            'maxWidthValue' => '',
            'maxWidthUnit' => '%',
            'maxHeight' => 'false',
            'maxHeightValue' => '',
            'maxHeightUnit' => '%',
            'height' => 'false',
            'heightValue' => '',
            'heightUnit' => '%',
            'width' => 'false',
            'widthValue' => '',
            'widthUnit' => '%',
            'linkHeight' => 'false',
            'linkHeightValue' => '',
            'linkHeightUnit' => '%',
            'linkWidth' => 'false',
            'linkWidthValue' => '',
            'linkWidthUnit' => '%',
            'initialWidth' => '300',
            'initialHeight' => '100',
            'autoColorbox' => false,
            'autoColorboxGalleries' => false,
            'slideshow' => false,
            'slideshowAuto' => false,
            'scalePhotos' => false,
            'displayScrollbar' => false,
            'draggable' => false,
            'slideshowSpeed' => '2500',
            'opacity' => '0.85',
            'preloading' => false,
            'transition' => 'elastic',
            'speed' => '350',
            'overlayClose' => false,
            'disableLoop' => false,
            'disableKeys' => false,
            'autoHideFlash' => false,
            'colorboxWarningOff' => false,
            'colorboxMetaLinkOff' => false,
            'javascriptInFooter' => false,
            'debugMode' => false,
            'autoColorboxJavaScript' => false,
            'removeLinkFromMetaBox' => false
        );
    }

    // jQueryColorboxDefaultSettings()


    /**
     *
     *
     */
//    function getThemeDirs() {
//        $themesDirPath = JQUERYCOLORBOX_PLUGIN_DIR.'/themes/';
//        if ($themesDir = opendir($themesDirPath)) {
//            while (false !== ($dir = readdir($themesDir))) {
//                if (substr("$dir", 0, 1) != "."){
//                    $themeDirs[$dir] = $dir;
//                }
//            }
//            closedir($themesDir);
//        }
//        asort($themeDirs);
//        return $themeDirs;
//    }

}

// class JQueryColorbox()
?><?php
/**
 * Workaround for PHP4
 * initialize plugin, call constructor
 *
 * @since 1.0
 * @access public
 * @author Arne Franken
 */
function initJQueryColorbox() {
        global $jQueryColorbox;
        $jQueryColorbox = new JQueryColorbox();
    }

// initJQueryColorbox()

// add jQueryColorbox to WordPress initialization
add_action('init', 'initJQueryColorbox', 7);

//static call to constructor is only possible if constructor is 'public static', therefore not PHP4 compatible:
//add_action('init', array('JQueryColorbox','JQueryColorbox'), 7);

// register method for activation
register_activation_hook(__FILE__, array('JQueryColorbox', 'activateJqueryColorbox'));
?>