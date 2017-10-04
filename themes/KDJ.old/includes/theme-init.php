<?php

global $am_option;

if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'am_add_javascript');
    add_action('wp_print_styles', 'am_add_css');
}

load_theme_textdomain($am_option['textdomain'], get_template_directory() . '/languages');

add_filter('body_class', 'am_browser_body_class');
add_filter('excerpt_more', 'am_excerpt_more');
add_action('wp_footer', 'am_analytics');
add_action('widgets_init', 'am_the_widgets_init');
add_action('widgets_init', 'am_unregister_default_wp_widgets', 1);
add_filter('the_title', 'am_has_title');
add_filter('the_content', 'am_texturize_shortcode_before');
add_action('login_headerurl', 'am_login_logo_url');
add_action('login_enqueue_scripts', 'am_login_logo');

//acf plugin
add_filter('acf/options_page/settings', 'am_acf_options_page_settings');
//add_action('admin_menu', 'am_remove_acf_menu', 999);
// This theme uses wp_nav_menu() in one location.
register_nav_menus(array(
    'mainmenu1' => __('Main Navigation', 'am'),
    'mainmenu2' => __('Hamburger Navigation', 'am'),
    'footermenu0' => __('Footer Navigation My Account', 'am'),
    'footermenu1' => __('Footer Navigation Customer Care', 'am'),
    'footermenu2' => __('Footer Navigation About Us', 'am'),
));

// This theme styles the visual editor with editor-style.css to match the theme style.
add_editor_style();

// This theme uses post thumbnails
add_theme_support('post-thumbnails');

// Add default posts and comments RSS feed links to head
add_theme_support('automatic-feed-links');

add_theme_support( 'woocommerce' );

// Allow Shortcodes in Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 99);
add_filter('the_content', 'shortcode_unautop', 100);


add_action('after_setup_theme', 'woocommerce_support');
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

//Set the Full Width Image value
if (!isset($content_width))
    $content_width = 900;

add_image_size('main-slider', 1260, 650, true);
add_image_size('primary-boxes-thumb', 306, 298, true);
add_image_size('widget-box-thumb', 625, 595, true);
add_image_size('bestsellers-thumb', 290, 290, true);
add_image_size('our-story-thumb', 233, 233, true);
add_image_size('product-widget-thumb', 308, 308, true);
add_image_size('page-thumb', 1500, 374, true);
add_image_size('people-face', 280, 279, true);
add_image_size('post-thumb', 285);
add_image_size('look-book-thumb', 1500, 1000, true);
add_image_size('look-book-slider', 606, 813, true);
add_image_size('look-book-gallery', 1215, 692, true);
add_image_size('product-single-thumb', 467, 707, true);
?>