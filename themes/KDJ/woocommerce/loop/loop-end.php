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

<div class="category_controls_box end">
    <div class="pager end">
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

</div><!--/category_content-->

<div class="related_box">
    <div class="recently_viewed recently_viewed_category_page">
	    <h4><span><?php _e('RECENTLY VIEWED PIECES', 'am'); ?></span></h4>
		<?= do_shortcode('[woocommerce_recently_viewed_products per_page=”9″] ')?>
		<div class="slider_bullets">
			<a class="slider_bullet" id="bullet-1" href="#slide-0">&bull;</a><a class="slider_bullet" id="bullet-2" href="#slide-3">&bull;</a><a class="slider_bullet" id="bullet-3" href="#slide-6">&bull;</a>
		</div>
	</div>
</div>