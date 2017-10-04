<?php
global $wp_query, $woocommerce, $product;
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>
</ul>

<div class="category_controls_box">
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
</div>

<div class="related_box">
    <h4><span><?php _e('RECENTLY VIEWED PIECES', 'am'); ?></span></h4>
    <?php echo am_woocommerce_recently_viewed_products(); ?>
</div>

</div><!--/category_content-->