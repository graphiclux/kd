<?php
/*
  Template Name: Inner Page
 */

get_header();
$inline_css = "";
if (has_post_thumbnail()) {
    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'page-thumb');
    $inline_css = ' style="background-image: url(\'' . $image_url[0] . '\');"';
}
?>
<div class="press_big_img_title" <?php echo $inline_css; ?>>
    <h2><?php the_title(); ?></h2>
</div>
<div class="container">
    <div class="inner_page_content">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <div class="entry">
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(__('Read more', 'am')); ?>
                    <div class="clear"></div>
                    <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'am') . '</span>', 'after' => '</div>')); ?>
                    <div class="clear"></div>
                </div>
                <?php
            endwhile;
        else :endif;
        ?>
    </div>
</div><!--/container-->
<?php
get_footer();
?>