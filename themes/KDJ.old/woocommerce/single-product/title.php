<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
?>
<div class="desktop_only">
    <h2 class="product_page_sidebar_title">
        <?php
        the_title();
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail = wp_get_attachment_image_src($thumbnail_id, 'full');
        $thumbnail_link = '';
        if (isset($thumbnail[0])) :
            $thumbnail_link = $thumbnail[0];
        endif;
        ?>
        <a class="pin_it_link" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo $thumbnail_link; ?>&description=<?php echo esc_attr(get_the_title()); ?>">pinterest</a>

    </h2>