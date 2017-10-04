<?php
/*

Copyright 2008 MagicToolbox (email : support@magictoolbox.com)
Plugin Name: Magic Scroll
Plugin URI: http://www.magictoolbox.com/magicscroll/?utm_source=CommercialVersion&utm_medium=WordPress&utm_content=plugins-page-plugin-url-link&utm_campaign=MagicScroll
Description: Effortlessly scroll through lots of images on your website. Activate plugin then <a href="https://www.magictoolbox.com/magicscroll/modules/wordpress/#installation">Get Started</a>.
Version: 6.7.0
Author: Magic Toolbox
Author URI: http://www.magictoolbox.com/?utm_source=CommercialVersion&utm_medium=WordPress&utm_content=plugins-page-author-url-link&utm_campaign=MagicScroll


*/

/*
    WARNING: DO NOT MODIFY THIS FILE!

    NOTE: If you want change Magic Scroll settings
            please go to plugin page
            and click 'Magic Scroll Configuration' link in top navigation sub-menu.
*/

if(!function_exists('magictoolbox_WordPress_MagicScroll_init')) {
    /* Include MagicToolbox plugins core funtions */
    require_once(dirname(__FILE__)."/magicscroll/plugin.php");
}

//MagicToolboxPluginInit_WordPress_MagicScroll ();
register_activation_hook( __FILE__, 'WordPress_MagicScroll_activate');

register_deactivation_hook( __FILE__, 'WordPress_MagicScroll_deactivate');

register_uninstall_hook(__FILE__, 'WordPress_MagicScroll_uninstall');

magictoolbox_WordPress_MagicScroll_init();
?>