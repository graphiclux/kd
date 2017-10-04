<?php
	
add_action('init', 'am_create_press');
function am_create_press() {
     $labels = array(
          'name' => __('Press', 'am'),
          'singular_name' => __('Press', 'am'),
          'add_new' => __('Add Press', 'am'),
          'add_new_item' => __('Add Press', 'am'),
          'edit_item' => __('Edit Press', 'am'),
          'new_item' => __('New Press', 'am'),
          'view_item' => __('View Press', 'am'),
          'search_items' => __('Search Press', 'am'),
          'not_found' =>  __('No Press found', 'am'),
          'not_found_in_trash' => __('No Press found in Trash', 'am'),
          'parent_item_colon' => ''
     );
     $args = array(
          'labels' => $labels,
          /*'menu_icon' => get_bloginfo( 'template_directory' ).'/images/ico_download.png',*/
          'public' => true,
          'publicly_queryable' => true,
          'exclude_from_search' => false,
          'show_ui' => true,
          'query_var' => true,
          'capability_type' => 'post',
          'hierarchical' => false,
          'menu_position' => null,
          'supports' => array('title','thumbnail','editor','excerpt'),
          'rewrite' => array(
               'slug' => 'press-post'
          )
      
      
     );
   
     register_post_type('press-post',$args);
}

function template_page_id($param) {
    $args = array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/' . $param . '.php',
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $pages = get_pages($args);
    return $pages[0]->ID;
}

add_action("wp_ajax_am_load_more_post", "am_load_more_post");
add_action("wp_ajax_nopriv_am_load_more_post", "am_load_more_post");

function am_load_more_post() {
    $args = array(
        'paged' => $_POST["page"],
        'post_type' => 'press-post',
        'post_status' => 'publish'
    );
    if ($_POST["amq"] !== "") {
        $params = unserialize(base64_decode($_POST["amq"]));
        $args = array_merge($args, $params);
    }
    query_posts($args);
    if (have_posts()) :
        while (have_posts()) : the_post();
            get_template_part('templates/content', 'post-prew');
        endwhile;
    endif;
    die();
}

add_action("wp_ajax_am_load_more_blog_post", "am_load_more_blog_post");
add_action("wp_ajax_nopriv_am_load_more_blog_post", "am_load_more_blog_post");

function am_load_more_blog_post() {
    $args = array(
        'paged' => $_POST["page"],
        'post_status' => 'publish'
    );
    if ($_POST["amq"] !== "") {
        $params = unserialize(base64_decode($_POST["amq"]));
        $args = array_merge($args, $params);
    }
    query_posts($args);
    if (have_posts()) :
        while (have_posts()) : the_post();
            get_template_part('templates/content', 'post-search');
        endwhile;
    endif;
    die();
}

function override_page_title() {
    return false;
}

add_filter('woocommerce_show_page_title', 'override_page_title');

function loop_columns() {
    return 3;
}

add_filter('loop_shop_columns', 'loop_columns', 999);

function am_woocommerce_recently_viewed_products($title_prise = false) {
    global $woocommerce;
    // Get recently viewed product cookies data
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
    $viewed_products = array_filter(array_map('absint', $viewed_products));

    // If no data, quit
    if (empty($viewed_products))
        return false;

    // Create the object
    ob_start();

    // Get products per page
    if (!isset($per_page) ? $number = 5 : $number = $per_page)

    // Create query arguments array
        $query_args = array(
            'posts_per_page' => 5,
            'no_found_rows' => 1,
            'post_status' => 'publish',
            'post_type' => 'product',
            'post__in' => $viewed_products,
            'orderby' => 'rand'
        );

    // Add meta_query to query args
    $query_args['meta_query'] = array();

    // Check products stock status
    $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

    // Create a new query
    $r = new WP_Query($query_args);

    // If query return results
    if ($r->have_posts()) {

        $content = '<ul class="am_wc_rvp_product_list_widget">';

        // Start the loop
        while ($r->have_posts()) {
            $r->the_post();
            global $product;

            $content .= '<li>
				<a href="' . get_permalink() . '" title="' . get_the_title() . '">
					' . ( has_post_thumbnail() ? get_the_post_thumbnail($r->post->ID, 'shop_thumbnail') : woocommerce_placeholder_img('shop_thumbnail') );
            if ($title_prise == true) {
                $content .= '<h5>' . get_the_title() . '</h5><span>' . $product->get_price_html() . '</span>';
            }
            $content .='</a>
			</li>';
        }

        $content .= '</ul>';
    }
    wp_reset_query();

    // Get clean object
    $content .= ob_get_clean();

    return $content;
}

class am_WP_Widget_New_Products extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'blog_whats_new_container', 'description' => __('AM New Products Widget.'));
        $control_ops = array();
        parent::__construct(false, __('AM New Products'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        global $am_widget, $wpdb;
        extract($args);
        $am_widget = array();

        $whatsnew_list = get_field('whatsnew_list', get_option('page_on_front'));
        $whatsnew_title = get_field('whatsnew_title', get_option('page_on_front'));
        echo $before_widget;
        ?>
        <div class="blog_whats_new_box">
            <ul class="blog_whats_new">
                <?php
                foreach ($whatsnew_list as $value) {
                    echo '<li><a href="' . get_permalink($value->ID) . '">';
                    echo get_the_post_thumbnail($value->ID, 'product-widget-thumb');
                    echo '</a></li>';
                }
                ?>
            </ul>
        </div>
        <?php if ($whatsnew_title) { ?>
            <h5><?php echo $whatsnew_title; ?></h5>
            <?php
        }
        ?>

        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        return $instance;
    }

    function form($instance) {
        ?>
        <p></p>
        <?php
    }

}

register_widget('am_WP_Widget_New_Products');