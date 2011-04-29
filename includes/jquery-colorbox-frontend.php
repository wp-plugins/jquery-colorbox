<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 4.1
 * @author Arne Franken
 *
 * Object that handles all actions in the WordPress frontend
 */
class JQueryColorboxFrontend {

    /**
     * Constructor
     *
     * @since 4.1
     * @access public
     * @access static
     * @author Arne Franken
     *
     * @param array $colorboxSettings user settings
     */
    //public static function JQueryColorboxFrontend($colorboxSettings) {
    function JQueryColorboxFrontend($colorboxSettings) {

        $this->colorboxSettings = $colorboxSettings;

        add_action('wp_head', array(& $this, 'buildWordpressHeader'), 9);

        //only add link to meta box if 
        if(isset($this->colorboxSettings['removeLinkFromMetaBox']) && !$this->colorboxSettings['removeLinkFromMetaBox']){
            add_action('wp_meta',array(& $this, 'renderMetaLink'));
        }

        if(isset($this->colorboxSettings['autoColorbox']) && $this->colorboxSettings['autoColorbox']){
            //write "colorbox-postID" to "img"-tags class attribute.
            //Priority = 100, hopefully the preg_replace is then executed after other plugins messed with the_content
            add_filter('the_content', array(& $this, 'addColorboxGroupIdToImages'), 100);
            add_filter('the_excerpt', array(& $this, 'addColorboxGroupIdToImages'), 100);
        }
        if(isset($this->colorboxSettings['autoColorboxGalleries']) && $this->colorboxSettings['autoColorboxGalleries']) {
            add_filter('wp_get_attachment_image_attributes', array(& $this, 'wpPostThumbnailClassFilter'));
        }

        // enqueue JavaScript and CSS files in wordpress
        wp_enqueue_script('jquery');
        wp_register_style('colorbox-' . $this->colorboxSettings['colorboxTheme'],  JQUERYCOLORBOX_PLUGIN_URL . '/' . 'themes/' . $this->colorboxSettings['colorboxTheme'] . '/colorbox.css', array(), JQUERYCOLORBOX_VERSION, 'screen');
        wp_enqueue_style('colorbox-' . $this->colorboxSettings['colorboxTheme']);
        if($this->colorboxSettings['debugMode']) {
            $jqueryColorboxJavaScriptPath = "js/jquery.colorbox.js";
        }
        else {
            $jqueryColorboxJavaScriptPath = "js/jquery.colorbox-min.js";
        }
        wp_enqueue_script('colorbox', JQUERYCOLORBOX_PLUGIN_URL . '/' . $jqueryColorboxJavaScriptPath, array('jquery'), COLORBOXLIBRARY_VERSION, $this->colorboxSettings['javascriptInFooter']);

        if($this->colorboxSettings['debugMode']) {
            $jqueryColorboxWrapperJavaScriptPath = "js/jquery-colorbox-wrapper.js";
        }
        else {
            $jqueryColorboxWrapperJavaScriptPath = "js/jquery-colorbox-wrapper-min.js";
        }
        wp_enqueue_script('colorbox-wrapper', JQUERYCOLORBOX_PLUGIN_URL . '/' . $jqueryColorboxWrapperJavaScriptPath, array('jquery'), COLORBOXLIBRARY_VERSION, $this->colorboxSettings['javascriptInFooter']);
//            wp_enqueue_script('colorbox-wrapper', plugins_url($jqueryColorboxWrapperJavaScriptName, __FILE__), array('jquery'), COLORBOXLIBRARY_VERSION, $this->colorboxSettings['javascriptInFooter']);

//            if($this->colorboxSettings['draggable']) {
//                ?!?wp_enqueue_script('jquery-ui-draggable');
//                wp_enqueue_script('colorbox-draggable', plugins_url('js/jquery-colorbox-draggable.js', __FILE__), array('jquery-ui-draggable'), JQUERYCOLORBOX_VERSION, $this->colorboxSettings['javascriptInFooter']);
//            }
        if ($this->colorboxSettings['autoColorbox']) {
            if ($this->colorboxSettings['autoColorboxJavaScript']) {
                if($this->colorboxSettings['debugMode']) {
                    $jqueryColorboxAutoJavaScriptPath = "js/jquery-colorbox-auto.js";
                }
                else {
                    $jqueryColorboxAutoJavaScriptPath = "js/jquery-colorbox-auto-min.js";
                }
                wp_enqueue_script('colorbox-auto', JQUERYCOLORBOX_PLUGIN_URL . '/' . $jqueryColorboxAutoJavaScriptPath, array('colorbox'), JQUERYCOLORBOX_VERSION, $this->colorboxSettings['javascriptInFooter']);
            }
        }
        if ($this->colorboxSettings['autoHideFlash']) {
            if($this->colorboxSettings['debugMode']) {
                $jqueryColorboxFlashJavaScriptPath = "js/jquery-colorbox-hideFlash.js";
            }
            else {
                $jqueryColorboxFlashJavaScriptPath = "js/jquery-colorbox-hideFlash-min.js";
            }
            wp_enqueue_script('colorbox-hideflash', JQUERYCOLORBOX_PLUGIN_URL . '/' . $jqueryColorboxFlashJavaScriptPath, array('colorbox'), JQUERYCOLORBOX_VERSION, $this->colorboxSettings['javascriptInFooter']);
        }
    }

    // JQueryColorboxFrontend()


    /**
     * Renders plugin link in Meta widget
     *
     * @since 3.3
     * @access public
     * @author Arne Franken
     */
    //public function renderMetaLink() {
    function renderMetaLink() { ?>
        <li id="colorboxLink"><?php _e('Using',JQUERYCOLORBOX_TEXTDOMAIN);?> <a href="http://www.techotronic.de/plugins/jquery-colorbox/" target="_blank" title="<?php echo JQUERYCOLORBOX_NAME ?>"><?php echo JQUERYCOLORBOX_NAME ?></a></li>
    <?php }

    // renderMetaLink()

    /**
     * Add Colorbox group Id to images.
     * function is called for every page or post rendering.
     *
     * ugly way to make the images Colorbox-ready by adding the necessary CSS class.
     * unfortunately, Wordpress does not offer a convenient way to get certain elements from the_content,
     * so I had to do this by regexp replacement...
     *
     * @since 1.0
     * @access public
     * @author Arne Franken
     *
     * @param  XML $content post or page content
     * @return XML replaced content or excerpt
     */
    //public function addColorboxGroupIdToImages($content) {
    function addColorboxGroupIdToImages($content) {
        global $post;
        // match all img tags with this pattern
        $imgPattern = "/<img([^\>]*?)>/i";
        if (preg_match_all($imgPattern, $content, $imgTags)) {
            foreach ($imgTags[0] as $imgTag) {
                // only work on imgTags that do not already contain the String "colorbox-"
                if(!preg_match('/colorbox-/i', $imgTag)){
                    if (!preg_match('/class/i', $imgTag)) {
                        // imgTag does not contain class-attribute
                        $pattern = $imgPattern;
                        $replacement = '<img class="colorbox-' . $post->ID . '" $1>';
                    }
                    else {
                        // imgTag already contains class-attribute
                        $pattern = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
                        $replacement = '<img$1class=$2$3 colorbox-' . $post->ID . '$4$5>';
                    }
                    $replacedImgTag = preg_replace($pattern, $replacement, $imgTag);
                    $content = str_replace($imgTag, $replacedImgTag, $content);
                }
            }
        }
        return $content;
    }

    // addColorboxGroupIdToImages()

    /**
     * Add colorbox-CSS-Class to WP Galleries
     *
     * If wp_get_attachment_image() is called, filters registered for the_content are not applied on the img-tag.
     * So we'll need to manipulate the class attribute separately.
     *
     * @since 2.0
     * @access public
     * @author Arne Franken
     *
     * @param  $attribute class attribute of the attachment link
     * @return replaced attributes
     */
    //public function wpPostThumbnailClassFilter($attribute) {
    function wpPostThumbnailClassFilter($attribute) {
        global $post;
        $attribute['class'] .= ' colorbox-' . $post->ID . ' ';
        return $attribute;
    }

    // wpPostThumbnailClassFilter()

    /**
     * Insert JavaScript and CSS for Colorbox into WP Header
     *
     * @since 1.0
     * @access public
     * @author Arne Franken
     *
     * @return wordpress header insert
     */
    //public function buildWordpressHeader() {
    function buildWordpressHeader() {
        ?>
<!-- <?php echo JQUERYCOLORBOX_NAME ?> <?php echo JQUERYCOLORBOX_VERSION ?> | by Arne Franken, http://www.techotronic.de/ -->
<?php
        // include Colorbox Javascript
        require_once 'colorbox-javascript-loader.php';
        ?>
<!-- <?php echo JQUERYCOLORBOX_NAME ?> <?php echo JQUERYCOLORBOX_VERSION ?> | by Arne Franken, http://www.techotronic.de/ -->
<?php
    }

    //buildWordpressHeader()

}

// class JQueryColorboxFrontend()
?>