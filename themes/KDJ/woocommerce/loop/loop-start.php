<?php
global $wp_query, $woocommerce, $product;
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
 
 $orderby = '';
 if(isset($_GET['orderby'])){
	 $orderby = $_GET['orderby'];
 }
 
?>
<div class="category_sidebar">

    <div class="pathway_box">
        <?php breadcrumb_trail('echo=1&separator='); ?>
    </div>

    <h2><?php _e('Shop All', 'am'); ?></h2>

    <a href="#" class="shop_all_mob_switch"><?php _e('Shop All', 'am'); ?></a>

    <div class="cat_sb_widget shop_by">
        <h3><?php _e('SHOP BY', 'am'); ?></h3>

        <ul>
            <?php
            $args = array(
                'show_option_all' => '',
                'orderby' => 'name',
                'order' => 'ASC',
                'style' => 'list',
                'show_count' => 0,
                'hide_empty' => 1,
                'use_desc_for_title' => 1,
                'child_of' => 0,
                'feed' => '',
                'feed_type' => '',
                'feed_image' => '',
                'exclude' => '',
                'exclude_tree' => '',
                'include' => '',
                'hierarchical' => 1,
                'title_li' => null,
                'show_option_none' => '',
                'number' => null,
                'echo' => 1,
                'depth' => 0,
                'current_category' => 0,
                'pad_counts' => 0,
                'taxonomy' => 'product_cat',
                'walker' => null
            );
            wp_list_categories($args);
            ?> 
        </ul>
    </div>


    <a href="#" class="sort_refine_mob_siwtch"><?php _e('REFINE', 'am'); ?> &amp; </a>
    <div class="sort_refine_mob_tab">
        <div class="cat_sb_widget refine_widget">
            <h3><?php _e('REFINE', 'am'); ?></h3>

            <h4 class="sb_widget_tab_button"><?php _e('PRICE', 'am'); ?></h4>
            <div class="sb_widget_tab">
                <ul>
                    <li><a href="?orderby=price"><?php _e('LOW', 'am'); ?></a></li>
                    <li><a href="?orderby=price-desc"><?php _e('HIGH', 'am'); ?></a></li>
                </ul>
            </div>

            <h4 class="sb_widget_tab_button"><?php _e('COLOR', 'am'); ?></h4>
            <div class="sb_widget_tab">
                <ul>
                    <?php
                    $args_color = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'exclude' => array(),
                        'exclude_tree' => array(),
                        'include' => array(),
                        'number' => '',
                        'fields' => 'all',
                        'slug' => '',
                        'parent' => '',
                        'hierarchical' => true,
                        'child_of' => 0,
                        'get' => '',
                        'name__like' => '',
                        'description__like' => '',
                        'pad_counts' => false,
                        'offset' => '',
                        'search' => '',
                    );

                    $terms_color = get_terms(array('pa_color'), $args_color);
                    foreach ($terms_color as $color) {
                        $term_link = get_term_link($color);
                        echo '<li><a href="' . esc_url($term_link) . '">' . $color->name . '</a></li>';
                    }
                    ?>
                </ul>

            </div>
        </div>
    </div><!--/sort_refine_mob_tab-->
</div>

<div class="category_content">

    <div class="category_controls_box">
        <span><?php _e('Sort', 'am'); ?>:</span>
        <form method="get" class="woocommerce-ordering">
            <select class="orderby" name="orderby">
                <option<?php if($orderby=='') echo ' selected="selected"'; ?> value="menu_order"></option>
                <option<?php if($orderby=='date') echo ' selected="selected"'; ?> value="date"><?php _e('Featured', 'am'); ?></option>
                <option<?php if($orderby=='price') echo ' selected="selected"'; ?> value="price"><?php _e('Price', 'am'); ?></option>
                <option<?php if($orderby=='title_asc') echo ' selected="selected"'; ?> value="title_asc"><?php _e('Alphabet', 'am'); ?></option>
            </select>
            <?php if(isset($_GET['showall'])) : ?>
            <input type="hidden" name="showall" value="yes">
            <?php endif; ?>
        </form>
        <?php ?>
        <?php if( !isset( $_GET['showall'] ) ) : ?>
        <span><a href="?showall=yes"><i>View All</i></a></span>
        <div class="pager">
            <?php
            $next_page = get_next_posts_link('');
            $prev_pages = get_previous_posts_link('');

            if (!empty($prev_pages)) {
                echo str_replace('<a', '<a class="pager_prev" ', $prev_pages);
            }
            ?>
            <div class="pager_counter"><?php echo max(1, get_query_var('paged')); ?> <i><?php _e('of', 'am'); ?></i> <?php echo $wp_query->max_num_pages; ?></div>
            <?php
            if (!empty($next_page)) {
                echo str_replace('<a', '<a class="pager_next" ', $next_page);
            }
            ?>
        </div>
		<?php endif; ?>
    </div>

    <ul class="products_list">