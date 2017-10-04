<?php
/*

Copyright 2017 MagicToolbox (email : support@magictoolbox.com)

*/

$error_message = false;
$update_plugin = true;

function WordPress_MagicScroll_activate () {

    set_transient( 'WordPress_MagicScroll_welcome_license_activation_redirect', true, 30 );

    if(!function_exists('file_put_contents')) {
        function file_put_contents($filename, $data) {
            $fp = fopen($filename, 'w+');
            if ($fp) {
                fwrite($fp, $data);
                fclose($fp);
            }
        }
    }

    /* === onlyForMod start: woocommerce */

    //fix url's in css files
    $fileContents = file_get_contents(dirname(__FILE__) . '/core/magicscroll.css');
    $cssPath = preg_replace('/https?:\/\/[^\/]*/is', '', get_option("siteurl"));

    $cssPath .= '/wp-content/'.preg_replace('/^.*?\/(plugins\/.*?)$/is', '$1', str_replace("\\","/",dirname(__FILE__))).'/core';

    $pattern = '#url\(\s*(\'|")?(?!data:|mhtml:|http(?:s)?:|/)([^\)\s\'"]+?)(?(1)\1)\s*\)#is';
    $replace = 'url($1'.$cssPath.'/$2$1)';

    $fixedFileContents = preg_replace($pattern, $replace, $fileContents);
    if($fixedFileContents != $fileContents) {
        file_put_contents(dirname(__FILE__) . '/core/magicscroll.css', $fixedFileContents);
    }

    magictoolbox_WordPress_MagicScroll_create_teble();
    magictoolbox_WordPress_MagicScroll_create_db();

    magictoolbox_WordPress_MagicScroll_init();

    WordPress_MagicScroll_send_stat('install');
}

function WordPress_MagicScroll_deactivate () {}

function WordPress_MagicScroll_uninstall() {
    magictoolbox_WordPress_MagicScroll_remove_teble();

    magictoolbox_WordPress_MagicScroll_delete_row_from_db();

    if (magictoolbox_WordPress_MagicScroll_is_empty_db() && !count(WordPress_MagicScroll_get_active_modules())) {
        magictoolbox_WordPress_MagicScroll_remove_db();
    }

    delete_option("WordPressMagicScrollCoreSettings");
    WordPress_MagicScroll_send_stat('uninstall');
}

function WordPress_MagicScroll_get_active_modules() {
    $name = explode('/', plugin_basename( __FILE__ ));
    $name = $name[0];
    $mtb_ap = array();

    foreach (get_option('active_plugins') as $value) {
        $name2 = explode('/', $value);
        $name2 = $name2[0];

        if ($name2 != $name && preg_match('/magiczoom|magiczoomplus|magic360|magicslideshow|magicscroll|magicthumb/', $value)) {
            $mtb_ap[] = $value;
        }
    }

    return $mtb_ap;
}

function WordPress_MagicScroll_send_stat($action = '') {

    //NOTE: don't send from working copy
    if('working' == 'v6.7.0' || 'working' == 'v2.0.31') {
        return;
    }

    $hostname = 'www.magictoolbox.com';

    $url = preg_replace('/^https?:\/\//is', '', get_option("siteurl"));
    $url = urlencode(urldecode($url));

    global $wp_version;
    $platformVersion = isset($wp_version) ? $wp_version : '';

    $path = "api/stat/?action={$action}&tool_name=magicscroll&license=commercial&tool_version=v2.0.31&module_version=v6.7.0&platform_name=wordpress&platform_version={$platformVersion}&url={$url}";
    $handle = @fsockopen($hostname, 80, $errno, $errstr, 30);
    if($handle) {
        $headers  = "GET /{$path} HTTP/1.1\r\n";
        $headers .= "Host: {$hostname}\r\n";
        $headers .= "Connection: Close\r\n\r\n";
        fwrite($handle, $headers);
        fclose($handle);
    }

}





function showMessage_WordPress_MagicScroll($message, $errormsg = false) {
    if ($errormsg) {
        echo '<div id="message" class="error">';
    } else {
        echo '<div id="message" class="updated fade">';
    }
    echo "<p><strong>$message</strong></p></div>";
}


function showAdminMessages_WordPress_MagicScroll(){
    global $error_message;
    if (current_user_can('manage_options')) {
       showMessage_WordPress_MagicScroll($error_message,true);
    }
}


function plugin_get_version_WordPress_MagicScroll() {
    $plugin_data = get_plugin_data(dirname(plugin_dir_path(__FILE__)).'/mod_wordpress_magicscroll.php');
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

function update_plugin_message_WordPress_MagicScroll() {
    $ver = json_decode(@file_get_contents('http://www.magictoolbox.com/api/platform/wordpress/version/'));
    if (empty($ver)) return false;
    $ver = str_replace('v','',$ver->version);
    $oldVer = plugin_get_version_WordPress_MagicScroll();
    if (version_compare($oldVer, $ver, '<')) {
        echo '<div id="message" class="updated fade">
                  <p>New version available! We recommend that you download the <a href="'.WordPressMagicScroll_url('http://magictoolbox.com/magicscroll/modules/wordpress/',' plugins page update link ').'">latest version</a> of Magic Scroll for WordPress . </p>
              </div>';
    }
}

function get_tool_version_WordPress_MagicScroll($tool=null) {
    global $wp_filesystem;

    if (!$tool) {
        $tool = 'magicscroll';
    }

    WP_Filesystem();

    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $r = $wp_filesystem->get_contents(plugin_dir_path( __FILE__ ).'core/'.$tool.'.js');

    if (!preg_match('/demo/is',$r)) {
        $version = 'commercial';
    } else {
        $version = 'trial';
    }
    return $version;
}

function MagicScroll_remove_update_nag($value) {
    if (isset($value->response)) {
        unset($value->response[ str_replace('/plugin','',plugin_basename(__FILE__)) ]);
    }
    return $value;
}

function  magictoolbox_WordPress_MagicScroll_init() {

    add_action( 'admin_init', 'WordPressMagicScroll_welcome_license_do_redirect' );

    global $error_message;

    require_once(dirname(__FILE__) . '/constructor_magicscroll/wordpress_magicscroll_fns.php');
    require_once(dirname(__FILE__) . '/constructor_magicscroll/wordpress_magicscroll_tool_db.php');
    require_once(dirname(__FILE__) . '/widget/widget_wordpress_magicscroll.php');
    require_once(dirname(__FILE__) . '/core/visual_composer/vc_wordpress_magicscroll.php');

    add_action("admin_menu", "magictoolbox_WordPress_MagicScroll_config_page_menu");
    add_action('admin_enqueue_scripts', 'WordPress_MagicScroll_load_admin_scripts');
    add_action('wp_enqueue_scripts', 'WordPress_MagicScroll_load_frontend_scripts');

    add_filter('filesystem_method', create_function('$a', 'return "direct";' ));

    add_filter("the_content", "magictoolbox_WordPress_MagicScroll_check_tool", 13);

    require_once(dirname(__FILE__)."/core/autoupdate.php");
    require_once(dirname(__FILE__)."/core/view/import_export/export.php");
    add_action('wp_ajax_WordPress_MagicScroll_import', 'WordPress_MagicScroll_import');
    add_action('wp_ajax_WordPress_MagicScroll_export', 'WordPress_MagicScroll_export');

    add_action('wp_ajax_WordPress_MagicScroll_save', 'magictoolbox_ajax_WordPress_MagicScroll_save');
    add_action('wp_ajax_WordPress_MagicScroll_check_shortcode', 'magictoolbox_ajax_WordPress_MagicScroll_check_shortcode');
    add_action('wp_ajax_WordPress_MagicScroll_remove_tools', 'magictoolbox_ajax_WordPress_MagicScroll_remove_tools');
    add_action('wp_ajax_WordPress_MagicScroll_copy_tool', 'magictoolbox_ajax_WordPress_MagicScroll_copy');
    add_action('wp_ajax_WordPress_MagicScroll_get_img_urls', 'magictoolbox_ajax_WordPress_MagicScroll_get_img_urls');
    add_action('admin_head', 'magictoolbox_WordPress_MagicScroll_add_my_tc_button');
    add_action('wp_ajax_WordPress_MagicScroll_tiny_mce_data', 'magictoolbox_WordPress_MagicScroll_get_tiny_mce_data');
    add_shortcode('magicscroll', 'magictoolbox_WordPress_MagicScroll_shortcode');

    add_action('wp_ajax_magictoolbox_WordPress_MagicScroll_set_license', 'magictoolbox_WordPress_MagicScroll_set_license');



    
    
    add_filter('site_transient_update_plugins', 'MagicScroll_remove_update_nag');
    
    add_filter( 'plugin_action_links', 'magictoolbox_WordPress_MagicScroll_links', 10, 2 );
    add_filter( 'plugin_row_meta', 'magictoolbox_WordPress_MagicScroll_plugin_row_meta' , 10, 2 );

    if (!file_exists(dirname(__FILE__) . '/core/magicscroll.js')) {
        $jsContents = file_get_contents('http://www.magictoolbox.com/static/magicscroll/trial/magicscroll.js');
        if (!empty($jsContents) && preg_match('/\/\*.*?\\\*/is',$jsContents)){
            if ( !is_writable(dirname(__FILE__) . '/core/')) {
                $error_message = 'The '.substr(dirname(__FILE__),strpos(dirname(__FILE__),'wp-content')).'/core/magicscroll.js file is missing. Please re-uplaod it.';
            }
            file_put_contents(dirname(__FILE__) . '/core/magicscroll.js', $jsContents);
            chmod(dirname(__FILE__) . '/core/magicscroll.js', 0777);
        } else {
            $error_message = 'The '.substr(dirname(__FILE__),strpos(dirname(__FILE__),'wp-content')).'/core/magicscroll.js file is missing. Please re-uplaod it.';
        }
    }
    if ($error_message) add_action('admin_notices', 'showAdminMessages_WordPress_MagicScroll');

    if(!isset($GLOBALS['magictoolbox']['WordPressMagicScroll'])) {
        require_once(dirname(__FILE__) . '/core/magicscroll.module.core.class.php');
        $coreClassName = "MagicScrollModuleCoreClass";
        $GLOBALS['magictoolbox']['WordPressMagicScroll'] = new $coreClassName;
        $coreClass = &$GLOBALS['magictoolbox']['WordPressMagicScroll'];
    }
    $coreClass = &$GLOBALS['magictoolbox']['WordPressMagicScroll'];
    /* get current settings from db */
    $settings = get_option("WordPressMagicScrollCoreSettings");
    if($settings !== false && is_array($settings) && isset($settings['default']) && !isset($_GET['reset_settings'])) {
        foreach (WordPressMagicScroll_getParamsProfiles() as $profile => $name) {
        if (isset($settings[$profile])) {
        $coreClass->params->appendParams($settings[$profile],$profile);
        }
    }
    } else { //set defaults
        $allParams = array();
        $defaults = $coreClass->params->getParams('default');
        $map = WordPressMagicScroll_getParamsMap();

    foreach (WordPressMagicScroll_getParamsProfiles() as $profile => $name) {
        $params = array();
        foreach ($defaults as $id => $param) {;
                if (isset($map[$profile][$param['group']]) && is_array($map[$profile][$param['group']]) && in_array($id,$map[$profile][$param['group']])) { //set defaults only according to mapping
                    $params[$id] = $param;
                }
            }
            $coreClass->params->setParams($params,$profile);

        $allParams[$profile] = $coreClass->params->getParams($profile);
    }

    delete_option("WordPressMagicScrollCoreSettings");
        add_option("WordPressMagicScrollCoreSettings", $allParams);
    }

    add_action( 'upgrader_process_complete', 'WordPress_MagicScroll_get_packed_js', 10, 2 );
    
}

function WordPress_MagicScroll_init_wp_filesystem($form_url) {
    global $wp_filesystem;
    $creds = request_filesystem_credentials($form_url, '', false, plugin_dir_path( __FILE__ ), false);

    if (!WP_Filesystem($creds)) {
        request_filesystem_credentials($form_url, '', true, plugin_dir_path( __FILE__ ), false);
        return false;
    }
    return true;
}

function WordPress_MagicScroll_write_file ($url, $content) {
    global $wp_filesystem;
    // if (empty($wp_filesystem)) {
    //     require_once (ABSPATH . '/wp-admin/includes/file.php');
    // }
    WordPress_MagicScroll_init_wp_filesystem($url);

    $result = $wp_filesystem->put_contents($url, $content, FS_CHMOD_FILE );

    return $result ? null : "Failed to write to file";
}

function WordPress_MagicScroll_rewrite ($option, $tool) {
    $response = get_option($option);
    $result = WordPress_MagicScroll_write_file(plugin_dir_path(__FILE__).'core/'.$tool.'.js', $response);
    return $result;
}

function WordPress_MagicScroll_get_packed_js ($upgrader_object, $options) {
    if ('update' == $options['action'] && 'plugin' == $options['type']) {
        foreach ($options['plugins'] as $pl) {
            $_plugin = explode("/", $pl);
            $_plugin = $_plugin[count($_plugin) - 1];
            if ('mod_wordpress_magicscroll.php' === $_plugin) {
                $key = magictoolbox_WordPress_MagicScroll_get_data_from_db();
                if (!$key) {
                    $result = WordPress_MagicScroll_rewrite("WordPress_MagicScroll_backup", 'magicscroll');
                }
                break;
            }
        }
    }
}

function WordPress_MagicScroll_load_frontend_scripts () {
    $plugin = $GLOBALS['magictoolbox']['WordPressMagicScroll'];

    $tool_lower = 'magicscroll';
    switch ($tool_lower) {
        case 'magicthumb':      $priority = '10'; break;
        case 'magic360':        $priority = '11'; break;
        case 'magiczoom':       $priority = '12'; break;
        case 'magiczoomplus':   $priority = '13'; break;
        case 'magicscroll':     $priority = '14'; break;
        case 'magicslideshow':  $priority = '15'; break;
        default :               $priority = '11'; break;
    }

    wp_register_style( 'magictoolbox_magicscroll_style', plugin_dir_url( __FILE__ ).'core/magicscroll.css', array(), false, 'all');
    wp_register_style( 'magictoolbox_magicscroll_module_style', plugin_dir_url( __FILE__ ).'core/magicscroll.module.css', array(), false, 'all');
    wp_register_script( 'magictoolbox_magicscroll_script', plugin_dir_url( __FILE__ ).'core/magicscroll.js', array(), false, true);
    add_action("wp_footer", "magictoolbox_WordPress_MagicScroll_add_src_to_footer", $priority);
    add_action("wp_footer", "magictoolbox_WordPress_MagicScroll_add_options_script", 10001);
}

function WordPress_MagicScroll_load_admin_scripts () {
    wp_enqueue_script( 'jquery' ,includes_url('/js/jquery/jquery.js'));
    wp_enqueue_script( 'jquery-ui-core', includes_url('/js/jquery/ui/core.js') );
    wp_enqueue_script( 'jquery-ui-tabs', includes_url('/js/jquery/ui/tabs.js') );

    $ownPage = false;
    if (array_key_exists('page', $_GET)) {
        $ownPage =  "WordPressMagicScroll-config-page" ==  $_GET["page"]        ||
                    "WordPressMagicScroll-shortcodes-page" ==  $_GET["page"]    ||
                    "WordPressMagicScroll-import-export-page" ==  $_GET["page"] ||
                    "WordPressMagicScroll-license-page" ==  $_GET["page"];
    }

    if (is_admin()) {
        wp_register_script( 'wordpress_MagicScroll_admin_adminpage_script', plugin_dir_url( __FILE__ ).'core/wordpress_MagicScroll_adminpage.js', array('jquery', 'jquery-ui-core', 'jquery-ui-tabs'), null );
        if ($ownPage) {
            wp_enqueue_style( 'magictoolbox_wordpress_MagicScroll_admin_page_style', plugin_dir_url( __FILE__ ).'core/admin.css', array(), null );
        }

        if ($ownPage) {
            wp_enqueue_style( 'WordPress_MagicScroll_admin_list_style', plugin_dir_url( __FILE__ ).'constructor_magicscroll/css/magicscroll_list.css', array(), null );
            wp_enqueue_style( 'WordPress_MagicScroll_admin_spinshortcodes_style', plugin_dir_url( __FILE__ ).'constructor_magicscroll/css/magicscroll_shortcodes.css', array(), null );

            if ($_GET["page"] == "WordPressMagicScroll-shortcodes-page") {
                wp_enqueue_style( 'WordPress_MagicScroll_admin_magicscroll_style', plugin_dir_url( __FILE__ ).'core/magicscroll.module.css', array(), null );
            }
            wp_enqueue_style( 'magictoolbox_WordPress_MagicScroll_tool_style', plugin_dir_url( __FILE__ ).'core/magicscroll.css', array(), null );
        }
        wp_register_script( 'WordPress_MagicScroll_admin_list_script', plugin_dir_url( __FILE__ ).'constructor_magicscroll/js/magicscroll_list.js', array('jquery'), null );
        wp_register_script( 'WordPress_MagicScroll_admin_spinshortcodes_script', plugin_dir_url( __FILE__ ).'constructor_magicscroll/js/magicscroll_shortcodes.js', array('jquery'), null );
        wp_register_script( 'magictoolbox_WordPress_MagicScroll_tool_script', plugin_dir_url( __FILE__ ).'core/magicscroll.js', array('WordPress_MagicScroll_admin_spinshortcodes_script'), null );
        add_action('admin_print_styles', 'magictoolbox_WordPress_MagicScroll_button_css');
        if ($ownPage) {
            wp_enqueue_style( 'WordPress_MagicScroll_admin_import_export_style', plugin_dir_url( __FILE__ ).'core/view/import_export/wordpress_MagicScroll_import_export.css', array(), null );
            wp_enqueue_style( 'WordPress_MagicScroll_admin_license_style', plugin_dir_url( __FILE__ ).'core/view/license/wordpress_MagicScroll_license.css', array(), null );

        }
        wp_register_script( 'WordPress_MagicScroll_admin_import_export_script', plugin_dir_url( __FILE__ ).'core/view/import_export/wordpress_MagicScroll_import_export.js', array('jquery'), null );
        wp_register_script( 'WordPress_MagicScroll_admin_license_script', plugin_dir_url( __FILE__ ).'core/view/license/wordpress_MagicScroll_license.js', array('jquery'), null );
    }

}


/**
  * Show row meta on the plugin screen.
  *
  * @param  mixed $links Plugin Row Meta
  * @param  mixed $file  Plugin Base file
  * @return array
  */

function magictoolbox_WordPress_MagicScroll_plugin_row_meta( $links, $file ) {

    if (strpos(plugin_dir_path(__FILE__),plugin_dir_path($file))) {
        $row_meta = array($links[0],$links[1]);
        $row_meta['Settings'] = '<a href="admin.php?page=WordPressMagicScroll-config-page">'.__('Settings').'</a>';
        $row_meta['Support'] =  '<a target="_blank" href="'.WordPressMagicScroll_url('https://www.magictoolbox.com/contact/','plugins page support link').'">Support</a>';
        $row_meta['More cool plugins'] = '<a target="_blank" href="'.WordPressMagicScroll_url('https://www.magictoolbox.com/wordpress/','plugins page more cool plugins link').'">More cool plugins</a>';

        return $row_meta;
    }

    return (array) $links;
}

function WordPressMagicScroll_config_page() {
    include 'core/view/settings/wordpress_MagicScroll_settings.php';
}

function WordPress_MagicScroll_add_admin_src_to_menu_page() {
    wp_enqueue_script( 'wordpress_MagicScroll_admin_adminpage_script' );

    $arr = array(
        'ajax'   => get_site_url().'/wp-admin/admin-ajax.php',
        'nonce'  => wp_create_nonce('magic-everywhere'),
        'mtburl' => 'https://www.magictoolbox.com/site/order/'
    );

    wp_localize_script( 'wordpress_MagicScroll_admin_adminpage_script', 'magictoolbox_WordPress_MagicScroll_admin_modal_object', $arr);
}

function WordPress_MagicScroll_import() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $tool = 'wordpress_magicscroll';

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        return;
    }

    $file = $_FILES['file'];

    $arr = (array) simplexml_load_string(file_get_contents($file["tmp_name"]),'SimpleXMLElement', LIBXML_NOCDATA);

    if (array_key_exists('tool', $arr) && $tool == $arr['tool']) {
        if (array_key_exists('license', $arr) && $arr['license'] != 'trial' && strlen($arr['license']) == 7) {
            magictoolbox_WordPress_MagicScroll_update_db($arr['license']);

            $url = 'https://www.magictoolbox.com/site/order/'.$arr['license'].'/magicscroll.js';
            $response = magictoolbox_WordPress_MagicScroll_get_file($url);
            if($response['status'] == 200) {
                WordPress_MagicScroll_write_file(plugin_dir_path( __FILE__ ).'core/magicscroll.js', $response['content']);
            }
        }

        if (array_key_exists('core', $arr)) {
            $core = (array) $arr['core'];

            $settings = get_option("WordPressMagicScrollCoreSettings");

            foreach ($core as $profile => $name) {
                $name = (array) $name;
                foreach ($name as $key => $value) {
                    $value = (array) $value;
                    if ('' != $value[0]) {
                        $settings[$profile][$key]['value'] = $value[0];
                    }
                }
            }

            delete_option("WordPressMagicScrollCoreSettings");
            add_option("WordPressMagicScrollCoreSettings", $settings);
        }

        if (array_key_exists('constructor', $arr)) {
            $constructor = (array) $arr['constructor'];
            $constructor = (array) $constructor[$tool];

            if (empty($constructor['id'])) {
                foreach ($constructor as $value) {
                    $value = (array) $value;
                    magictoolbox_WordPress_MagicScroll_add_data_to_table($value['name'], $value['shortcode'], $value['image'], $value['thumbnails'], $value['options'], $value['mobile_options'], $value['additional_options']);
                }
            } else {
                magictoolbox_WordPress_MagicScroll_add_data_to_table($constructor['name'], $constructor['shortcode'], $constructor['image'], $constructor['thumbnails'], $constructor['options'], $constructor['mobile_options'], $constructor['additional_options']);
            }
        }
    }
    // exit;
}

function WordPress_MagicScroll_export() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $value = $_POST['value'];
    $secret_data = null;

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        return;
    }

    if (function_exists('magictoolbox_WordPress_MagicScroll_get_data')) {
        $secret_data = magictoolbox_WordPress_MagicScroll_get_data();
    }

    WordPress_MagicScroll_wp_export($value, get_option("WordPressMagicScrollCoreSettings"), $secret_data);
    exit;
}

function magictoolbox_WordPress_MagicScroll_add_src_to_footer() {
    global $magictoolbox_MagicScroll_page_has_shortcode,
            $magictoolbox_MagicScroll_page_has_tool,
            $magictoolbox_MagicScroll_page_added_script;

    if (!$magictoolbox_MagicScroll_page_has_tool) {
        $plugin = $GLOBALS['magictoolbox']['WordPressMagicScroll'];
        if ($plugin->params->checkValue('include-headers','yes') || isset($GLOBALS['custom_template_headers'])) {
            $magictoolbox_MagicScroll_page_has_tool = true; // add footers for all pages
            //if (isset($GLOBALS['custom_template_headers'])) unset($GLOBALS['custom_template_headers']); //prevent render on non-product pages
        }
    }

    if (!$magictoolbox_MagicScroll_page_added_script) {
        $magictoolbox_MagicScroll_page_added_script = true;

        if ($magictoolbox_MagicScroll_page_has_shortcode || $magictoolbox_MagicScroll_page_has_tool) {
            wp_enqueue_style('magictoolbox_magicscroll_style');
            wp_enqueue_style('magictoolbox_magicscroll_module_style');
            wp_enqueue_script('magictoolbox_magicscroll_script');
        }

        if ($magictoolbox_MagicScroll_page_has_tool) {
            // wp_enqueue_style('magictoolbox_magicscroll_style');
            // wp_enqueue_style('magictoolbox_magicscroll_module_style');
            // wp_enqueue_script('magictoolbox_magicscroll_script');
        }
    }
}

function magictoolbox_WordPress_MagicScroll_add_options_script () {
    global $magictoolbox_MagicScroll_page_added_options,
            $magictoolbox_MagicScroll_page_has_shortcode,
            $magictoolbox_MagicScroll_page_has_tool;
    $footers = '';

    if (!$magictoolbox_MagicScroll_page_added_options) {
        $magictoolbox_MagicScroll_page_added_options = true;


        if ($magictoolbox_MagicScroll_page_has_shortcode || $magictoolbox_MagicScroll_page_has_tool) {
            $plugin = $GLOBALS['magictoolbox']['WordPressMagicScroll'];
            $footers = $plugin->getOptionsTemplate();

            $o = get_option("WordPressMagicScrollCoreSettings");
            $o = $o['default'];
            if(isset($o['background']['value']) && '' != trim($o['background']['value'])) {
                $footers .= '<style type=\'text/css\'>';
                $footers .= '.MagicScroll {background-color: '.$o['background']['value'].';}';
                $footers .= '</style>';
            }

            if (function_exists('plugins_url')) {
                $core_url = plugins_url();
            } else {
                $core_url = get_option("siteurl").'/wp-content/plugins';
            }
            $path = preg_replace('/^.*?\/plugins\/(.*?)$/is', '$1', str_replace("\\","/",dirname(__FILE__)));

        }
        echo $footers;
    }
}

function magictoolbox_WordPress_MagicScroll_get_file($url) {
    $result = array( 'content' => '', 'status' => 0);

    if ($url && is_string($url)) {
        $url = trim($url);
        if ('' != $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $result['content'] = $response;
            $result['status'] = $code;
        }
    }

    return $result;
}

function magictoolbox_WordPress_MagicScroll_set_license() {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
    }

    WP_Filesystem();
    // ob_end_clean();

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $key = $_POST['key'];
    $extra_param = $_POST['param'];
    $result = '{"error": "error"}';

    if (!$extra_param || 'null' == $extra_param) {
        $extra_param = null;
        $tool_name = 'magicscroll';
    } else {
        $tool_name = $extra_param;
        $extra_param = 'WordPress_MagicScroll_'.$extra_param;
    }

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = '{"error": "verification failed"}';
    } else {
        if ($key && '' != $key) {
            $url = 'https://www.magictoolbox.com/site/order/'.$key.'/'.$tool_name.'.js';
            $response = magictoolbox_WordPress_MagicScroll_get_file($url);

            $code = $response['status'];
            $response = $response['content'];

            if($code == 200) {
                $result = WordPress_MagicScroll_write_file(plugin_dir_path( __FILE__ ).'core/'.$tool_name.'.js', $response);
                if (!$result) {
                    magictoolbox_WordPress_MagicScroll_update_db($key, $extra_param);
                    $result = 'null';
                }
                $result = '{"error": '.$result.'}';
            } else if($code == 403) {
                $result = '{"error": "limit"}';
                //Download limit reached
                //Your license has been downloaded 10 times already.
                //If you wish to download your license again, please contact us.
            } else if ($code == 404) {
                $result = '{"error": "license failed"}';
            } else {
                $result = '{"error": "Other errors"}';
            }
        }
    }
    ob_end_clean();
    echo $result;
    wp_die();
}

function magictoolbox_WordPress_MagicScroll_create_db() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'magictoolbox_store';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
          id int unsigned NOT NULL auto_increment,
          name varchar(50) DEFAULT NULL,
          license varchar(50) DEFAULT NULL,
          UNIQUE KEY id (id));";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

function magictoolbox_WordPress_MagicScroll_remove_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magictoolbox_store';

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table_name);
    }
}

function magictoolbox_WordPress_MagicScroll_update_db($key, $name=null) {
    global $wpdb;
    $result = false;

    if (!$name || !is_string($name)) {
        $name = 'WordPress_MagicScroll';
    }

    if ($key && is_string($key)) {
        $table_name = $wpdb->prefix . 'magictoolbox_store';

        $data = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE name = '" . $name . "'");

        if ($data && count($data) > 0) {
            $result = $wpdb->update($table_name, array('license' => $key), array('name' => $name), array( '%s' ), array( '%s' ));
            $result = !!$result;
        } else {
            $result = $wpdb->insert($table_name, array('name' => $name, 'license' => $key));
        }
    }

    return $result;
}

function magictoolbox_WordPress_MagicScroll_delete_row_from_db($name=null) {
    global $wpdb;

    if (!$name || !is_string($name)) {
        $name = 'WordPress_MagicScroll';
    }

    $table_name = $wpdb->prefix . 'magictoolbox_store';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        return $wpdb->delete( $table_name, array( 'name' => $name ) );
    } else {
        return false;
    }
}

function magictoolbox_WordPress_MagicScroll_is_empty_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magictoolbox_store';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $result = $wpdb->get_results("SELECT * FROM ".$table_name);
        return !(count($result) > 0);
    } else {
        return false;
    }
}

function magictoolbox_WordPress_MagicScroll_get_data_from_db($name=null) {
    global $wpdb;

    if (!$name || !is_string($name)) {
        $name = 'WordPress_MagicScroll';
    }

    $table_name = $wpdb->prefix . 'magictoolbox_store';
    $result = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE name = '".$name."'");
    if ($result && count($result) > 0) {
        return $result[0];
    } else {
        return null;
    }
}

function magictoolbox_WordPress_MagicScroll_links( $links, $file ) {
    $fileName = 'mod_wordpress_magicscroll_commercial/mod_wordpress_magicscroll.php';
    $fileName = preg_replace('/\_trial\//', '/', $fileName);
    if ($file == $fileName) {
        $settings_link = '<a href="admin.php?page=WordPressMagicScroll-config-page">'.__('Settings').'</a>';
        array_unshift( $links, $settings_link );
        $spins_link = '<a href="admin.php?page=WordPressMagicScroll-shortcodes-page">Shortcodes</a>';
        array_push( $links, $spins_link );
    }
    return $links;
}

function magictoolbox_WordPress_MagicScroll_config_page_menu() {
    if(function_exists("add_menu_page")) {
        //$page = add_submenu_page("admin.php", __("Magic Scroll Plugin Configuration"), __("Magic Scroll Configuration"), "manage_options", "WordPressMagicScroll-config-page", "WordPressMagicScroll_config_page");
        $page = add_menu_page( __("Magic Scroll"), __("Magic Scroll"), "manage_options", "WordPressMagicScroll-config-page", "WordPressMagicScroll_config_page", plugin_dir_url( __FILE__ )."core/admin_graphics/icon.png");
        add_submenu_page( "WordPressMagicScroll-config-page", 'Settings', 'Settings', 'manage_options', "WordPressMagicScroll-config-page" );
        add_action('admin_print_scripts-' . $page, 'WordPress_MagicScroll_add_admin_src_to_menu_page');
    }

    if(function_exists("add_submenu_page")) {
        $submenu_page = add_submenu_page("WordPressMagicScroll-config-page", "Shortcodes", "Shortcodes", "manage_options", "WordPressMagicScroll-shortcodes-page", "WordPress_MagicScroll_submenu_page");
        add_action('admin_print_scripts-' . $submenu_page, 'WordPress_MagicScroll_add_admin_src_to_submenu_page');

        $license_page = add_submenu_page("WordPressMagicScroll-config-page", "License", "License", "manage_options", "WordPressMagicScroll-license-page", "WordPress_MagicScroll_license_page");
        add_action('admin_print_scripts-' . $license_page, 'WordPress_MagicScroll_add_admin_src_to_license_page');
        $import_export_page = add_submenu_page("WordPressMagicScroll-config-page", "Backup / Restore", "Backup / Restore", "manage_options", "WordPressMagicScroll-import-export-page", "WordPress_MagicScroll_import_export_page");
        add_action('admin_print_scripts-' . $import_export_page, 'WordPress_MagicScroll_add_admin_src_to_import_export_page');

        global $submenu;
        $tmp = $submenu['WordPressMagicScroll-config-page'][0];
        $submenu['WordPressMagicScroll-config-page'][0] = $submenu['WordPressMagicScroll-config-page'][1];
        $submenu['WordPressMagicScroll-config-page'][1] = $tmp;
    }
}

function WordPress_MagicScroll_submenu_page() {
    if (array_key_exists('id', $_GET)) {
        include 'constructor_magicscroll/view/magicscroll_shortcodes.php';
    } else {
        include 'constructor_magicscroll/view/magicscroll_list.php';
    }
}

function WordPress_MagicScroll_add_admin_src_to_submenu_page() {
    if (array_key_exists('id', $_GET)) {
        wp_enqueue_script( 'WordPress_MagicScroll_admin_spinshortcodes_script' );
        wp_enqueue_script( 'magictoolbox_WordPress_MagicScroll_tool_script'    );
        wp_localize_script( 'WordPress_MagicScroll_admin_spinshortcodes_script', 'magictoolbox_WordPress_MagicScroll_admin_modal_object', array('ajax' =>  get_site_url().'/wp-admin/admin-ajax.php', 'nonce' => wp_create_nonce('magic-everywhere')) );
        wp_enqueue_media();
    } else {
        wp_enqueue_script( 'WordPress_MagicScroll_admin_list_script');
        wp_localize_script( 'WordPress_MagicScroll_admin_list_script', 'magictoolbox_WordPress_MagicScroll_admin_modal_object', array('ajax' =>  get_site_url().'/wp-admin/admin-ajax.php', 'nonce' => wp_create_nonce('magic-everywhere')) );
    }
}

function WordPress_MagicScroll_import_export_page() {
    include 'core/view/import_export/wordpress_MagicScroll_import_export.php';
}

function WordPress_MagicScroll_add_admin_src_to_import_export_page() {
    wp_enqueue_script( 'WordPress_MagicScroll_admin_import_export_script' );
    wp_localize_script( 'WordPress_MagicScroll_admin_import_export_script', 'magictoolbox_WordPress_MagicScroll_admin_modal_object', array('ajax' =>  get_site_url().'/wp-admin/admin-ajax.php', 'nonce' => wp_create_nonce('magic-everywhere')) );
}

function WordPress_MagicScroll_license_page() {
    include 'core/view/license/wordpress_MagicScroll_license.php';
}

function WordPress_MagicScroll_add_admin_src_to_license_page() {
    wp_enqueue_script( 'WordPress_MagicScroll_admin_license_script' );
    wp_localize_script( 'WordPress_MagicScroll_admin_license_script', 'magictoolbox_WordPress_MagicScroll_admin_modal_object', array('ajax' =>  get_site_url().'/wp-admin/admin-ajax.php', 'nonce' => wp_create_nonce('magic-everywhere')) );
}

function magictoolbox_WordPress_MagicScroll_styles() {
    if(!defined('MAGICTOOLBOX_MAGICSCROLL_HEADERS_LOADED')) {
        $plugin = $GLOBALS['magictoolbox']['WordPressMagicScroll'];

        
        if (function_exists('plugins_url')) {
            $core_url = plugins_url();
        } else {
            $core_url = get_option("siteurl").'/wp-content/plugins';
        }


        $path = preg_replace('/^.*?\/plugins\/(.*?)$/is', '$1', str_replace("\\","/",dirname(__FILE__)));

        $headers = $plugin->getHeadersTemplate($core_url."/{$path}/core");

        echo $headers;
        define('MAGICTOOLBOX_MAGICSCROLL_HEADERS_LOADED', true);
    }
}



function magictoolbox_WordPress_MagicScroll_check_tool($content) {
    global $magictoolbox_MagicScroll_page_has_tool;
    $toolPatern = '#(<div\b[^>]*?(?:\bclass\s*+=\s*+"[^"]*?\bMagicScroll\b[^"]*+")[^>]*+>)'.
                    '('.
                    '(?:'.
                        '[^<]++'.
                        '|'.
                        '<(?!/?div\b|!--)'.
                        '|'.
                        '<!--.*?-->'.
                        '|'.
                        '<div\b[^>]*+>'.
                            '(?2)'.
                        '</div\s*+>'.
                    ')*+'.
                    ')'.
                    '</div\s*+>#is';

    if (preg_match($toolPatern, $content)) {
        $magictoolbox_MagicScroll_page_has_tool = true;
    }
    return $content;
}


    $result = $title;



function WordPress_MagicScroll_get_post_attachments($addMain = false)  {

    $args = array(
            'post_type' => 'attachment',
            'numberposts' => '-1',
            'post_status' => null,
            'post_parent' => $post_id
        );

    $attachments = get_posts($args);
    return $attachments;
}








function WordPressMagicScroll_url ($url,$position) {

    if ('commercial' == get_tool_version_WordPress_MagicScroll()) {
    $utm_source = 'CommercialVerison';
    } else {
    if (magictoolbox_WordPress_MagicScroll_get_data_from_db()) {
        $utm_source = 'CommercialVersion';
    } else {
        $utm_source = 'TrialVersion';
    }
    }

    $utm_medium = 'WordPress';
    $utm_content = preg_replace('/\s+/is','-',trim($position));
    $utm_campaign = 'MagicScroll';

    $link = $url.'?utm_source='.$utm_source.'&utm_medium='.$utm_medium.'&utm_content='.$position.'&utm_campaign='.$utm_campaign;

    return $link;
}

function WordPressMagicScroll_params_map_check ($profile = 'default', $group, $parameter) {
    $map = WordPressMagicScroll_getParamsMap();
    if (isset($map[$profile][$group][$parameter])) return true;
    return false;
}
function WordPressMagicScroll_getParamsMap () {
    $map = array(
		'default' => array(
			'General' => array(
				'include-headers',
			),
			'Scroll' => array(
				'width',
				'height',
				'orientation',
				'mode',
				'items',
				'speed',
				'autoplay',
				'loop',
				'step',
				'arrows',
				'pagination',
				'easing',
				'scrollOnWheel',
				'lazy-load',
				'scroll-extra-styles',
				'show-image-title',
				'background',
			),
			'Watermark' => array(
				'watermark',
				'watermark-max-width',
				'watermark-max-height',
				'watermark-opacity',
				'watermark-position',
				'watermark-offset-x',
				'watermark-offset-y',
			),
		),
	);
    return $map;
}

function WordPressMagicScroll_getParamsProfiles () {

    $blocks = array(
		'default' => 'General',
	);

    return $blocks;
}

function WordPressMagicScroll_welcome_license_do_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( 'WordPress_MagicScroll_welcome_license_activation_redirect' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( 'WordPress_MagicScroll_welcome_license_activation_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'WordPressMagicScroll-license-page' ), admin_url( 'admin.php' ) ) );

}

function WordPressMagicScroll_plugin_path() {

  return untrailingslashit( plugin_dir_path( __FILE__ ) );
 
}
 
function WordPress_MagicScroll_locate_template( $template, $template_name, $template_path ) {
 
  global $woocommerce;
 
  $_template = $template;
 
  if ( ! $template_path ) $template_path = $woocommerce->template_url;
 
  $plugin_path  = WordPressMagicScroll_plugin_path() . '/core/templates/';
  
  $template = locate_template(
 
    array(
 
      $template_path . $template_name,
 
      $template_name
 
    )
 
  );
  $post_id = get_the_id();
  
    if ( file_exists( $plugin_path . $template_name ) ) {
        $template = $plugin_path . $template_name;
    } else {
        $template = $_template;
    }
  
    if ( ! $template ) {
        $template = $_template;
    }

  return $template;
 
}

function WordPress_MagicScroll_get_containers_data($thumbs = array(), $post_id = false, $useWpImages = false) {

    $mainHTML = '';
    $GLOBALS['defaultContainerId'] = 'zoom';
    $containersData = array(
        'zoom' => '',
        '360' => '',
    );
    $productImagesHTML = array();

    $plugin = $GLOBALS['magictoolbox']['WordPressMagicScroll'];
    
    $main_image = $GLOBALS['magictoolbox']['MagicScroll']['main'];
    $main_image = preg_replace('/(<a.*?class=\".*?)\"/is', "$1" . ' lightbox-added"', $main_image);
    $containersData['zoom'] = $main_image;

    if(isset($thumbs) && !empty($thumbs)){ 
        foreach ($thumbs as $index => $thumb) {
            $thumbs[$index] = str_replace('<a ', '<a data-magic-slide-id="zoom" ', $thumb);
        }
     }

    if ($useWpImages) {
        global $_wp_additional_image_sizes;
        $imageSize = $plugin->params->getValue('thumbnails-wordpress-image', 'product');
        if (in_array( $imageSize, array('thumbnail', 'medium', 'medium_large', 'large'))) {
            $sMaxWidth = (int)get_option($imageSize.'_size_w');
            $sMaxHeight = (int)get_option($imageSize.'_size_h');
        } else if (isset( $_wp_additional_image_sizes[$imageSize] ) ) {
            $sMaxWidth = (int)$_wp_additional_image_sizes[$imageSize]['width'];
            $sMaxHeight = (int)$_wp_additional_image_sizes[$imageSize]['height'];
        }
    } else {
        $sMaxWidth = (int)$plugin->params->getValue('selector-max-width', 'product');
        $sMaxHeight = (int)$plugin->params->getValue('selector-max-height', 'product');
    }

    //video data
    if (metadata_exists( 'post', $post_id, '_provide_videolinks_field' )){
        $scrollEnabled = $plugin->params->checkValue('magicscroll', 'Yes');
        $productVideos = get_post_meta( $post_id, '_provide_videolinks_field', true );
        $productVideos = empty($productVideos) ? array() : unserialize($productVideos);
        $videoIndex = 1;
         
        foreach ($productVideos as $videoUrl => $videoData) {
            if($videoData['youtube']) {
                $dataVideoType = 'youtube';
                $containersData['video-'.$videoIndex] = '<iframe src="https://www.youtube.com/embed/'.$videoData['code'].'?enablejsapi=1"';
            } else {
                $dataVideoType = 'vimeo';
                $containersData['video-'.$videoIndex] = '<iframe src="https://player.vimeo.com/video/'.$videoData['code'].'?byline=0&portrait=0"';
            }
            $containersData['video-'.$videoIndex] .=
                //' width="'.$maxWidth.'" height="'.$maxHeight.'"'.
                ' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen data-video-type="'.$dataVideoType.'"></iframe>';

            $productImagesHTML[] =
                '<a data-magic-slide-id="video-'.$videoIndex.'" data-video-type="'.$dataVideoType.'" class="video-selector" href="#" onclick="return false">'.
                '<span><b></b></span>'.
                '<img src="'.$videoData['thumb'].'" alt="video"'.($scrollEnabled ? '' : ' style="max-width: '.$sMaxWidth.'px; max-height: '.$sMaxHeight.'px;"').'/>'.
                '</a>';
            $videoIndex++;
        }
    }

    if (metadata_exists( 'post', $post_id, '_magic360_data' ) && magictoolbox_WordPress_MagicScroll_check_plugin_active('magic360') ){

        $magic360_plugin = $GLOBALS['magictoolbox']['WooCommerceMagic360'];
        $magic360_plugin->params->setProfile('product');

        if(!empty($magic360_plugin) && $magic360_plugin->params->checkValue('page-status','Yes') ){

            $magic360_data = json_decode((get_post_meta( $post_id, '_magic360_data', true )), true);
            $magic360_image_gallery = Array();
            if(!empty($magic360_data) && array_key_exists('images_ids', $magic360_data)) $magic360_image_gallery = $magic360_data['images_ids'];

            if(!empty($magic360_image_gallery)){

                $watermark = $plugin->params->getValue('watermark');
                $plugin->params->setValue('watermark', '');

                $magic360_selector_path = $magic360_plugin->params->getValue('selector-path');
                
                if (!$useWpImages) {
                    $magic360_selector = '<a data-magic-slide-id="360" style="display:inline-block;" class="m360-selector" title="360" href="#" onclick="return false;"><img src="'.WordPress_MagicScroll_get_product_image($magic360_selector_path,'selector').'" alt="360" /></a>';
                } else {
                    $magic360_selector = '<a data-magic-slide-id="360" style="display:inline-block;" class="m360-selector" title="360" href="#" onclick="return false;"><img style="max-width: '.$sMaxWidth.'px; max-height: '.$sMaxHeight.'px;" src="'.get_site_url().$magic360_selector_path.'" alt="360" /></a>';
                }     
                
                $plugin->params->setValue('watermark', $watermark);
                array_unshift($thumbs, $magic360_selector);

                foreach($magic360_image_gallery as $i => $image_id) {
                    $image_src = wp_get_attachment_image_src($image_id, 'original', $image_id);
                    $image_src = preg_replace('/.*(\/wp-content.*)/','$1', $image_src[0]);
                    $GLOBALS['magic360images'][$i] = array(
                        'medium' => WooCommerce_Magic360_get_product_image($image_src,'thumb', $image_id),
                        'img' => WooCommerce_Magic360_get_product_image($image_src,'original', $image_id)
                    );
                }

                $magic360_plugin->params->setValue('columns', $magic360_data['options']['columns']);

                usort($GLOBALS['magic360images'], 'magictoolbox_WordPress_MagicScroll_key_sort');

                $containersData['360'] = $magic360_plugin->getMainTemplate($GLOBALS['magic360images']);
                $GLOBALS['defaultContainerId'] = '360';

                global $magictoolbox_Magic360_page_has_tool;
                $magictoolbox_Magic360_page_has_tool = true;
            }
        }

    }
    
    return array('containersData'       => $containersData,
                 'productImagesHTML'    => $productImagesHTML,
                 'thumbs'               => $thumbs);
    
}


?>
