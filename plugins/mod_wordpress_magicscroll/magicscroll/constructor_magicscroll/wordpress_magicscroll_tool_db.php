<?php
function magictoolbox_WordPress_MagicScroll_create_teble() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicscroll_store';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
          id int unsigned NOT NULL auto_increment,
          name varchar(300) DEFAULT NULL,
          shortcode varchar(50) DEFAULT NULL,
          image varchar(50) DEFAULT NULL,
          thumbnails text DEFAULT NULL,
          options text DEFAULT NULL,
          mobile_options text DEFAULT NULL,
          additional_options text DEFAULT NULL,
          UNIQUE KEY id (id));";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        magictoolbox_WordPress_MagicScroll_create_example_chortcode();
    }
}

function magictoolbox_WordPress_MagicScroll_remove_teble() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicscroll_store';

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table_name);
    }
}

function magictoolbox_WordPress_MagicScroll_remove_element($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicscroll_store';

    return $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ));
}

function magictoolbox_WordPress_MagicScroll_add_data_to_table($name, $shortcode, $image, $thumbnails, $options, $mobile_options, $additional_options) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicscroll_store';

    $r = $wpdb->insert($table_name, array('name' => $name, 'shortcode' => $shortcode, 'image' => $image, 'thumbnails' => $thumbnails, 'options' => $options, 'mobile_options' => $mobile_options, 'additional_options' => $additional_options));

    if ($r) {
        $r = $wpdb->insert_id;
    }

    return $r;
}

function magictoolbox_WordPress_MagicScroll_get_data($field=false, $value=false) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicscroll_store';

    if (!$field) {
        return $wpdb->get_results("SELECT * FROM ".$table_name);
    } else {
        return $wpdb->get_results("SELECT * FROM ".$table_name." WHERE ".$field." = ".$value);
    }
}

function magictoolbox_WordPress_MagicScroll_add_image_to_media($image_url, $name, $title=null, $description=null) {
    $image_url        = esc_url($image_url);
    $image_name       = $name;
    $upload_dir       = wp_upload_dir();
    $image_data       = file_get_contents($image_url);
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
    $filename         = basename( $unique_file_name );

    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents( $file, $image_data );
    $wp_filetype = wp_check_filetype( $filename, null );

    if (null == $title) {
        $title = sanitize_file_name( $filename );
    }
    if (null == $description) {
        $description = '';
    }
    $attachment = array(
        'guid' => $upload_dir['url'] . '/' . basename( $file ),
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => $title,
        'post_content'   => $description,
        'post_status'    => 'inherit'
    );

    if( file_exists( ABSPATH . 'wp-admin/includes/image.php') && file_exists( ABSPATH . 'wp-admin/includes/media.php') ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attach_id = wp_insert_attachment( $attachment, $file);

        if (!is_wp_error( $attach_id )) {
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            return $attach_id;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function magictoolbox_WordPress_MagicScroll_get_images_from_media_library() {
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'orderby' => 'rand'
    );
    return new WP_Query( $args );
}

function magictoolbox_WordPress_MagicScroll_get_image_name($url) {
    $name = explode("/", $url);
    $name = $name[count($name) - 1];
    return $name;
}

function magictoolbox_WordPress_MagicScroll_create_example_chortcode() {
    $ids = array();
    $images = array(
        '0264044679_2_1_1.jpg',
        '0367009811_1_1_1.jpg',
        '2052677712_2_2_1.jpg',
        '2212733710_2_2_1.jpg',
        '2284670704_1_1_1.jpg',
        '2347340251_2_1_1.jpg',
        '2566296070_2_1_1.jpg',
        '2568292251_2_2_1.jpg',
        '3859050401_2_1_1.jpg',
        '4437245623_1_1_1.jpg',
        '6771030512_1_1_1.jpg',
        '6869042050_1_1_1.jpg',
        '6873009712_2_1_1.jpg',
        '7521064300_2_3_1.jpg',
        '8203643942_2_1_1.jpg',
        '9320041533_2_3_1.jpg'
    );
    $imageUrl = 'https://magictoolbox.sirv.com/images/magicscroll/zara2/';

    $ml_images = magictoolbox_WordPress_MagicScroll_get_images_from_media_library();

    foreach ($images as $value) {
        $tmp = false;
        foreach ($ml_images->posts as $img) {
            if (magictoolbox_WordPress_MagicScroll_get_image_name($img->guid) == $value) {
                $tmp = $img->ID;
                break;
            }
        }

        if (false == $tmp) {
            $tmp = magictoolbox_WordPress_MagicScroll_add_image_to_media($imageUrl.$value, $value, 'MagicScroll-example');
        }

        if (false != $tmp) {
            $ids[] = $tmp;
        }
    }

    if (count($ids)) {
        magictoolbox_WordPress_MagicScroll_add_example_data($ids);
    }
}

function magictoolbox_WordPress_MagicScroll_add_example_data($ids) {
    $name = 'Example shortcode';
    $shortcode = '';
    $thumbnails = implode(",", $ids);
    $options = 'pagination:Yes;show-image-title:Yes;';
    $additional_options = 'image_size:undefined;image_width:undefined;image_height:undefined;thumbnail_size:thumbnail;thumbnail_width:100;thumbnail_height:100;thumbnails_position:undefined;settings:custom;';
    magictoolbox_WordPress_MagicScroll_add_data_to_table($name, $shortcode, '', $thumbnails, $options, '', $additional_options);
}
?>
