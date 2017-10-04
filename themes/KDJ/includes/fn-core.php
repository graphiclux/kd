<?php

/**
 * Custom comments for single or page templates
 */
function am_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
        </div>
        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'am') ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf(__('%1$s at %2$s', 'am'), get_comment_date(), get_comment_time())
                ?></a><?php edit_comment_link(__('(Edit)', 'am'), '  ', '');
                ?>
        </div>

        <div class="entry-comment"><?php comment_text() ?></div>

        <div class="reply">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

/**
 * Browser detection body_class() output
 */
function am_browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone, $_GET;

    if ($is_lynx)
        $classes[] = 'lynx';
    elseif ($is_gecko)
        $classes[] = 'gecko';
    elseif ($is_opera)
        $classes[] = 'opera';
    elseif ($is_NS4)
        $classes[] = 'ns4';
    elseif ($is_safari)
        $classes[] = 'safari';
    elseif ($is_chrome)
        $classes[] = 'chrome';
    elseif ($is_IE)
        $classes[] = 'ie';
    else
        $classes[] = 'unknown';

    if (wp_is_mobile())
        $classes[] = 'mobile';
    if ($is_iphone)
        $classes[] = 'iphone';
        
    if(isset($_GET['showall']) && $_GET['showall']=='yes'){
        $classes[] = 'woo-showall-yes';
    }
    
    return $classes;
}

/**
 * Show analytics code in footer
 */
function am_analytics() {

    $output = get_field('general_google_analytics_code', 'option');

    if (!empty($output))
        echo stripslashes($output) . "\n";
}

/**
 * Filter for get_the_excerpt
 */
function am_excerpt_more($more) {
    return '...';
}

/**
 * Resize the image
 */
function am_image_resize($thumbnail_id = NULL, $img_url = '', $width = 100, $height = 100, $default_img = '') {

    if ($thumbnail_id > 0) {
        $thumbnail = wp_get_attachment_image_src($thumbnail_id, 'full');
        if (isset($thumbnail[0]))
            $img_url = $thumbnail[0];
    }

    $return_img = $img_url;
    if (!empty($default_img))
        $return_img = $default_img;

    if ($img_url != "") {
        $image = matthewruddy_image_resize($img_url, $width, $height, true, false);
        if (is_wp_error($image)) {
            return $return_img;
        } else {
            return $image['url'];
        }
    }

    return $return_img;
}

function am_has_title($title) {
    global $post;
    if ($title == '') {
        return get_the_time(get_option('date_format'));
    } else {
        return $title;
    }
}

function am_texturize_shortcode_before($content) {
    $content = preg_replace('/\]\[/im', "]\n[", $content);
    return $content;
}

function am_remove_wpautop($content) {
    $content = do_shortcode(shortcode_unautop($content));
    $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
    return $content;
}

// unregister all default WP Widgets
function am_unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    //unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    //unregister_widget('WP_Widget_Recent_Posts');
    //unregister_widget('WP_Widget_Recent_Comments');
    //unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
    //unregister_widget('WP_Nav_Menu_Widget');
}

/**
 * Add JS scripts
 */
function am_add_javascript() {

    global $am_option;

    if (is_singular() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');

    wp_enqueue_script('jquery');
    if (!is_admin()) {
        wp_enqueue_script('am_bxslider', get_template_directory_uri() . '/includes/js/jquery.bxslider.min.js', array('jquery'), $am_option['version'], true);
        wp_enqueue_script('am_pkgd', get_template_directory_uri() . '/includes/js/masonry.pkgd.min.js', array('jquery'), $am_option['version'], true);
        wp_enqueue_script('am_visible', get_template_directory_uri() . '/includes/js/jquery.visible.js', array('jquery'), $am_option['version'], true);
        wp_enqueue_script('am_general', get_template_directory_uri() . '/includes/js/general.js', array('jquery'), $am_option['version'], true);
        wp_localize_script('am_general', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}

/**
 * Add CSS scripts
 */
function am_add_css() {

    global $am_option;
    wp_enqueue_style('am_style_css', get_template_directory_uri() . '/style.css', array(), $am_option['version']);
    wp_enqueue_style('am_queries_css', get_template_directory_uri() . '/queries.css', array(), $am_option['version']);
}

/**
 * Register widgetized areas
 */
function am_the_widgets_init() {

    if (!function_exists('register_sidebars'))
        return;

    $before_widget = '<div id="%1$s" class="widget %2$s"><div class="widget_inner">';
    $after_widget = '</div></div>';
    $before_title = '<h4 class="widgettitle">';
    $after_title = '</h4>';

    register_sidebar(array('name' => __('Default', 'am'), 'id' => 'sidebar-default', 'before_widget' => $before_widget, 'after_widget' => $after_widget, 'before_title' => $before_title, 'after_title' => $after_title));
}



/**
 * Register Shopping Cart Dropdown Widget
 *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Shopping Cart Dropdown',
		'id'            => 'shopping-cart-dropdown',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );


/**
 * Add theme Option pages
 */
function am_acf_options_page_settings($settings) {
    $settings['title'] = 'Theme Options';
    $settings['pages'] = array('General', 'Social');

    return $settings;
}

/**
 * Hide ACF menu item from the admin menu
 */
function am_remove_acf_menu() {
    remove_menu_page('edit.php?post_type=acf');
}

/**
 * Change admin logo url
 */
function am_login_logo_url() {
    return home_url('/');
}

/**
 * Chnage admin logo image
 */
function am_login_logo() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            width: 208px;
            height: 67px;
            display: block;
            cursor: pointer;
            text-indent: -9999em;
            background: url(<?php echo get_bloginfo('template_directory') ?>/images/logo_wp.png) no-repeat;
            margin: 0 auto 35px;
        }
    </style>
    <?php
}

function woocommerce_support() {
    add_theme_support('woocommerce');
}
?>