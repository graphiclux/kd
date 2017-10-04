<div <?php post_class('post') ?> id="post-<?php the_ID(); ?>">
    <?php if (has_post_thumbnail()) { ?>
        <div class="big_img_title">
            <?php the_post_thumbnail('page-thumb'); ?>
        </div>
    <?php } ?>
    <div class="container">
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
</div><!-- /post -->