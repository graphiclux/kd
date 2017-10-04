<?php
global $wp_query;
get_header();
if (is_product_category()) {
    global $wp_query;
    $cat = $wp_query->get_queried_object();
    $thumbnail_id = get_woocommerce_term_meta($cat->term_id, 'thumbnail_id', true);
    $image = wp_get_attachment_url($thumbnail_id);
    if ($image) {
        ?>
        <div class="big_img_title">
            <img src="<?php echo $image; ?>" alt="">
        </div><!--/look_book_big_img_title-->
        <?php
    }
}

?>

<div class="container">

    <?php woocommerce_content(); ?>

</div><!--/inner_page_box-->
<?php get_footer(); ?>