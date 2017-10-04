<?php
/*
  Template Name: Inner Sidebar
 */

get_header();
?>
<div class="inner_page_box">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) { ?>
                <div class="big_img_title">
                    <?php the_post_thumbnail('page-thumb'); ?>
                </div>
            <?php } ?>
            <div class="container">
                <?php get_sidebar(); ?>
                <div class="inner_page_content">
                    <div class="entry">
                        <h1><?php the_title(); ?></h1>
                        <?php the_content(__('Read more', 'am')); ?>
                        <div class="clear"></div>
                        <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'am') . '</span>', 'after' => '</div>')); ?>
                        <?php edit_post_link(__('Edit', 'am'), '<br /><p>', '</p>'); ?>
                    </div>
                </div>
            </div><!--/container-->
        <?php endwhile;
    endif;
    ?>
</div><!--/inner_page_box-->
<?php get_footer(); ?>