<?php
function magictoolbox_WordPress_MagicScroll_set_global_variable() {
    global $magictoolbox_MagicScroll_page_has_shortcode;
    $magictoolbox_MagicScroll_page_has_shortcode = true;
}
function magictoolbox_WordPress_MagicScroll_get_wordpress_image_sizes( $unset_disabled = true ) {
    $wais = & $GLOBALS['_wp_additional_image_sizes'];

    $sizes = array();

    foreach ( get_intermediate_image_sizes() as $_size ) {
        if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
            $sizes[ $_size ] = array(
                'width'  => get_option( "{$_size}_size_w" ),
                'height' => get_option( "{$_size}_size_h" ),
                'crop'   => (bool) get_option( "{$_size}_crop" ),
            );
        }
        elseif ( isset( $wais[$_size] ) ) {
            $sizes[ $_size ] = array(
                'width'  => $wais[ $_size ]['width'],
                'height' => $wais[ $_size ]['height'],
                'crop'   => $wais[ $_size ]['crop'],
            );
        }

        // size registered, but has 0 width and height
        if( $unset_disabled && ($sizes[ $_size ]['width'] == 0) && ($sizes[ $_size ]['height'] == 0) )
            unset( $sizes[ $_size ] );
    }

    return $sizes;
}

function magictoolbox_WordPress_MagicScroll_get_option($arr) {
    return empty($arr['value']) ? $arr['default'] : $arr['value'];
}

// "key: value; key: value; ..."
function magictoolbox_WordPress_MagicScroll_parse_option_from_string($options) {
    $opt = array();

    $options = explode(";", $options);
    array_pop($options);

    foreach ($options as $value) {
        $value = trim($value);
        if (!empty($value)) {
            $value = explode(":", $value);
            // $opt[$value[0]] = trim($value[1]);
            $k = array_shift($value);
            $v = join(":",$value);
            $opt[$k] = trim($v);
        }
    }
    return $opt;
}

function magictoolbox_WordPress_MagicScroll_correct_options_value($options) {
    $opt = array();
    foreach ($options as $key => $value) {
        if ('Yes' === $value) {
            $value = 'true';
        } else if ('No' === $value) {
            $value = 'false';
        }

        $opt[$key] = $value;
    }
    return $opt;
}

function magictoolbox_WordPress_MagicScroll_options_to_string($options) {
    $str = '';
    foreach ($options as $key => $value) {
        if ('show-image-title' === $key || 'scroll-extra-styles' === $key) { continue; }
        if ('background' === $key) { continue; }
        if ('Yes' == $value || 'yes' == $value) {
            $value = true;
        } else if ('No' == $value || 'no' == $value) {
            $value = false;
        }
        $str .= ($key.': '.$value.'; ');
    }
    return $str;
}

function magictoolbox_WordPress_MagicScroll_convert_size($size, $width, $height) {
    if ('custom' == $size) {
        return array($width, $height);
    } else {
        return $size;
    }
}

function magictoolbox_WordPress_MagicScroll_get_sizes($ids, $mediumSize, $thumbSize) {
    $arr = array();
    foreach ($ids as $value) {
        $sizes = array();
        if ($mediumSize) { $sizes['medium'] = $mediumSize; }
        if ($thumbSize) { $sizes['thumbnail'] = $thumbSize; }
        $arr[] = array('id' => (int)$value, 'sizes' => $sizes);
    }
    return $arr;
}

function magictoolbox_WordPress_MagicScroll_get_style_html($nameOfStyle, $value, $ids) {
    $result = null;

    if($value || is_string($value) && '' != trim($value)) {
        $result = '';
        $count = count($ids);
        $result .= '<style type=\'text/css\'> ';
        for ($i = 0; $i < $count; $i++) {
            $result .= ('.MagicScroll[id="' . $ids[$i] . '"]');
            if ($i == $count - 1) {
                $result .= (' { ' . $nameOfStyle . ': ' . $value . ' !important; }');
            } else {
                $result .= ', ';
            }
        }
        $result .= '</style>';
    }
    return $result;
}



function magictoolbox_WordPress_MagicScroll_shortcode( $attrs ) {
    magictoolbox_WordPress_MagicScroll_set_global_variable();

    $additional_id = '';
    if (isset($attrs['additional_id'])) {
        $additional_id = $attrs['additional_id'];
    }

    if (is_numeric($attrs['id'])) {
        $m_tool = magictoolbox_WordPress_MagicScroll_get_data("id", $attrs['id']);
    } else {
        $m_tool = magictoolbox_WordPress_MagicScroll_get_data("shortcode", '"'.$attrs['id'].'"');
    }

    if (!$m_tool || 0 == count($m_tool)) { return ''; }

    $html = '';
    $extraClass = '';
    $images = array();
    $tool_options = magictoolbox_WordPress_MagicScroll_parse_option_from_string($m_tool[0]->options);
    $tool_options = magictoolbox_WordPress_MagicScroll_correct_options_value($tool_options);
    $tool_additional_options = magictoolbox_WordPress_MagicScroll_parse_option_from_string($m_tool[0]->additional_options);
    $def_options = get_option("WordPressMagicScrollCoreSettings");
    $def_options = $def_options['default'];

    if ($m_tool[0]->thumbnails) {
        $images = explode(",", $m_tool[0]->thumbnails);
    }

    $images = magictoolbox_WordPress_MagicScroll_get_img_url_with_size(
        magictoolbox_WordPress_MagicScroll_get_sizes(
            $images,
            null,
            magictoolbox_WordPress_MagicScroll_convert_size($tool_additional_options['thumbnail_size'], $tool_additional_options['thumbnail_width'], $tool_additional_options['thumbnail_height'])
        )
    );


    if ('custom' == $tool_additional_options['settings']) {
        if (array_key_exists('scroll-extra-styles', $tool_options) && trim($tool_options['scroll-extra-styles']) != '') {
            $extraClass = ' '.$tool_options['scroll-extra-styles'];
        }
    } else {
        if(array_key_exists('scroll-extra-styles', $def_options)) {
            if(array_key_exists('value', $def_options['scroll-extra-styles']) && '' == trim($def_options['scroll-extra-styles']['value'])) {
                $extraClass = ' '.$def_options['scroll-extra-styles']['value'];
            } else if (array_key_exists('default', $def_options['scroll-extra-styles']) && '' == trim($def_options['scroll-extra-styles']['default'])) {
                $extraClass = ' '.$def_options['scroll-extra-styles']['default'];
            }
        }
    }

    if ('custom' != $tool_additional_options['settings'] || !array_key_exists('show-image-title', $tool_options)) {
        if (array_key_exists('show-image-title', $def_options)) {
            if(array_key_exists('value', $def_options['show-image-title']) && 'Yes' == $def_options['show-image-title']['value'] ||
                array_key_exists('default', $def_options['show-image-title']) && 'Yes' == $def_options['show-image-title']['default']
            ) {
                $tool_options['show-image-title'] = true;
            }
        }
    }

    $html .= '<div class="MagicScroll'.$extraClass.'" id="MagicScroll-shortcode-'.$attrs['id'].$additional_id.'"';
    if ('custom' == $tool_additional_options['settings'] && count($tool_options)) {
        $html .= ' data-options="'.magictoolbox_WordPress_MagicScroll_options_to_string($tool_options).'"';
    }
    $html .= '>';

    foreach ($images as $value) {
        $html .= '<a href="#">';
        $html .= ('<img src="'.$value['thumbnail'].'"');
        if(array_key_exists('show-image-title', $tool_options) && 'false' !== $tool_options['show-image-title']) {
            $t = get_the_title($value['id']);
            $html .= (' title="'.$t.'"');
        } else {
            $html .= (' title="'.get_the_title($value['id']).'"');
        }
        $html .= (' alt="'.get_post_meta($value['id'], '_wp_attachment_image_alt', true).'"');
        $html .= ' />';
        if(array_key_exists('show-image-title', $tool_options) && 'false' !== $tool_options['show-image-title']) {
            $html .= ('<span>'.$t.'</span>');
        }
        $html .= '</a>';
    }
    $html .= '</div>';

    $styleValue = null;
    if (array_key_exists('background', $tool_options)) {
        $styleValue = $tool_options['background'];
    }
    $style = magictoolbox_WordPress_MagicScroll_get_style_html('background-color', $styleValue, array('MagicScroll-shortcode-'.$attrs['id'].$additional_id));
    if($style) {
        $html = $style.$html;
    }

    return $html;
}

function magictoolbox_WordPress_MagicScroll_get_tiny_mce_data() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $result = '{"error": "error"}';

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = '{"error": "verification failed"}';
    } else {
        $table_data = magictoolbox_WordPress_MagicScroll_get_data();
        if ($table_data && count($table_data) > 0) {
            $result = '[';
            foreach($table_data as $value) {
                $sc = $value->shortcode;
                if (empty($sc)) {
                    $sc = "null";
                }
                $result .= '{';
                $result .= '"id":"'.$value->id.'",';
                $result .= '"name":"'.$value->name.'",';
                $result .= '"shortcode":"'.$sc.'"';
                $result .= '},';
            }
            $result = preg_replace('/,$/is', '', $result);
            $result .= ']';
        } else {
            $result = '{"error": "empty"}';
        }
    }
    echo $result;
    wp_die();
}

function magictoolbox_WordPress_MagicScroll_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
        return;
    }

    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page', 'product' , 'wpsc-product', 'tcp_product' ) ) ) {
        return;
    }

    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "magictoolbox_WordPress_MagicScroll_add_tinymce_plugin");
        add_filter('mce_buttons', 'magictoolbox_WordPress_MagicScroll_register_tinymce_button');
        echo '<script>'.
            'var magictoolbox_WordPress_MagicScroll_admin_modal_object = {'.
                'ajax: "'.(get_site_url().'/wp-admin/admin-ajax.php').'",'.
                'nonce: "'.wp_create_nonce('magic-everywhere'). '"'.
            '};'.
        '</script>';
    }
}

function magictoolbox_WordPress_MagicScroll_add_tinymce_plugin($plugin_array) {
    $plugin_array["magictoolbox_WordPress_MagicScroll_shortcode"] = plugins_url( 'js/magicscroll_tiny_mce_button.js', __FILE__ );
    return $plugin_array;
}

function magictoolbox_WordPress_MagicScroll_register_tinymce_button($buttons) {
   array_push($buttons, "magictoolbox_WordPress_MagicScroll_shortcode");
   return $buttons;
}

function magictoolbox_WordPress_MagicScroll_button_css() {
    $screen = get_current_screen();

    if ( $screen->id == 'page' || $screen->id == 'post' || $screen->id == 'product' || $screen->id == 'wpsc-product' || $screen->id == 'tcp_product') {
        wp_register_style('magictoolbox_WordPress_MagicScroll_tinymce_button_css', plugin_dir_url( __FILE__ ).('css/magicscroll_tiny_mce_button.css'), array());
        wp_enqueue_style('magictoolbox_WordPress_MagicScroll_tinymce_button_css');
    }
}

function magictoolbox_ajax_WordPress_MagicScroll_copy() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $id = (int)$_POST['id'];
    $result = "null";
    $tableId = "null";

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = "\"verification_failed\"";
    } else {
        $res = magictoolbox_WordPress_MagicScroll_get_data("id", $id);
        if (!$res) {
            $result = "\"error\"";
        } else {
            $tableId = magictoolbox_WordPress_MagicScroll_add_data_to_table($res[0]->name.' (copy)', '', $res[0]->image, $res[0]->thumbnails, $res[0]->options, $res[0]->mobile_options, $res[0]->additional_options);
        }
    }
    ob_end_clean();
    echo "{\"error\":".$result.",\"id\":".$tableId."}";
    wp_die();
}

function magictoolbox_WordPress_MagicScroll_get_img_url_with_size($images) {
    require_once(preg_replace('/\/constructor_MagicScroll/is', '', dirname(__FILE__)) . '/core/magictoolbox.imagehelper.class.php');
    $result = array();

    $url = site_url();
    $shop_dir = ABSPATH;
    $image_dir = 'wp-content/uploads/';

    $imagehelper = new MagicToolboxImageHelperClass($shop_dir, $image_dir.'magictoolbox_cache', new MagicToolboxParamsClass(), null, $url);

    foreach ($images as $value) {
        $img = array('id' => $value['id']);
        foreach ($value['sizes'] as $key => $size) {
            if (is_array($size)) {
                $url = wp_get_attachment_metadata($value['id']);
                $url = '/'.$image_dir.$url['file'];
                $tmp = $imagehelper->create($url, $size);
            } else {
                $tmp = wp_get_attachment_image_src($value['id'], $size);
                $tmp = $tmp[0];
            }
            $img[$key] = $tmp;
        }

        $result[] = $img;
    }

    return $result;
}

function magictoolbox_ajax_WordPress_MagicScroll_get_img_urls() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $result = "null";
    $nonce = $_POST['nonce'];
    $ids = $_POST['ids'];
    $urls = array();

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = "\"verification_failed\"";
    } else {
        $urls = magictoolbox_WordPress_MagicScroll_get_img_url_with_size($ids);
    }

    $urls = json_encode($urls);

    ob_end_clean();
    echo "{\"error\":".$result.",\"urls\":".$urls."}";
    wp_die();
}

function magictoolbox_ajax_WordPress_MagicScroll_remove_tools() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $nonce = $_POST['nonce'];
    $ids = $_POST['ids'];
    $result = 'null';

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = "\"verification_failed\"";
    } else {
        foreach ($ids as $value) {
            magictoolbox_WordPress_MagicScroll_remove_element($value);
        }
    }

    ob_end_clean();
    echo "{\"error\":".$result."}";
    wp_die();
}

function magictoolbox_ajax_WordPress_MagicScroll_check_shortcode() {
    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) { return; }

    $result = "null";
    $nonce = $_POST['nonce'];
    $shortcode = $_POST['shortcode'];

    $id = $_POST['id'];
    if ('null' != $id) {
        $id = (int)$id;
    }

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = "\"verification_failed\"";
    } else {
        $data = magictoolbox_WordPress_MagicScroll_get_data();

        foreach ($data as $spin) {
            if ('null' == $id || $id != $spin->id) {
                if ($spin->shortcode == $shortcode) {
                    $result = "\"not_unique\"";
                    break;
                }
            }
        }
    }

    ob_end_clean();
    echo "{\"error\":".$result."}";
    wp_die();
}

function magictoolbox_ajax_WordPress_MagicScroll_save() {
    global $wpdb;

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) { return; }

    $result = "null";
    $tableId = "null";
    $nonce = $_POST['nonce'];
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $shortcode = $_POST['shortcode'];
    $image = $_POST['image'];
    $thumbnails = $_POST['thumbnails'];
    $options = $_POST['options'];
    $mobile_options = $_POST['mobile_options'];
    $additional_options = $_POST['additional_options'];

    if ( !wp_verify_nonce( $nonce, 'magic-everywhere' ) ) {
        $result = "\"verification_failed\"";
    } else {
        $oldData = magictoolbox_WordPress_MagicScroll_get_data("id", $id);

        if (!count($oldData)) {
            $tableId = magictoolbox_WordPress_MagicScroll_add_data_to_table($name, $shortcode, $image, $thumbnails, $options, $mobile_options, $additional_options);
            if (false == $tableId) {
                $result = "\"db_insert_failed\"";
                $tableId = "null";
            }
        } else {
            $table_name = $wpdb->prefix . 'magicscroll_store';

            $res = $wpdb->update($table_name, array(
                    'name' => $name,
                    'shortcode' => $shortcode,
                    'image' => $image,
                    'thumbnails' => $thumbnails,
                    'options' => $options,
                    'mobile_options' => $mobile_options,
                    'additional_options' => $additional_options
                ),
                array('id' => $id),
                array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
                array( '%d' )
            );

            if (false == $res) {
                $result = "\"db_update_failed\"";
            }
        }
    }

    ob_end_clean();
    echo "{\"error\":".$result.",\"id\":".$tableId."}";
    wp_die();
}

?>
