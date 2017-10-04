<?php
get_header();
$inline_css = "";
if (has_post_thumbnail(template_page_id('blog'))) {
    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(template_page_id('blog')), 'page-thumb');
    $inline_css = $image_url[0];
    ?>

    <div class="big_img_title the_blog">
        <img src="<?php echo $inline_css; ?>" alt="">
        <h2 class="the_blog_title">
            <span>The</span>
            <span>Blog</span>
        </h2>
    </div>
<?php } ?>

<div class="container">
    <div class="the_blog_container">
        <div class="blog_articles_container">
            <div class="blog_articles_box">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        ?>
                        <div class="blog_article_item" id="post-<?php the_ID(); ?>">
                            <div class="blog_article_info">
                                <h3><?php the_title(); ?></h3>

                                <div class="blog_art_userpic">
                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_avatar(get_the_author_ID(), array(45, 48)); ?></a>
                                </div>

                                <div class="blog_art_author">
                                    POSTED<br> BY
                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('display_name'); ?></a>
                                </div>

                                <div class="blog_art_date">
                                    <span><?php the_time("F") ?></span>
                                    <span><?php the_time("d") ?></span>
                                    <span><?php the_time("Y") ?></span>
                                </div>
                            </div><!--/blog_article_info-->

                            <div class="blog_art_inside_container">
                                <div class="blog_art_inside_box">

                                    <div class="blog_art_inside_title">
                                        <div class="blog_art_inside_media">
                                            <span>share</span>
                                            <a href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?>" target="_blank" class="bm_fb"></a>
                                            <a href="http://twitter.com/share?url=<?php echo get_permalink(get_the_ID()); ?>&text=<?php the_title(); ?>" target="_blank" class="bm_tw"></a>
                                            <a href="//www.pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo $thumbnail_link; ?>&description=<?php echo esc_attr(get_the_title()); ?>" target="_blank" class="bm_instagram"></a>
                                            <a href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(get_the_ID()); ?>" target="_blank" class="bm_pinterest"></a>
                                        </div>
                                    </div>

                                    <div class="blog_art_text">
                                        <div class="entry">
                                            <?php the_content(__('Read more', 'am')); ?>
                                            <div class="clear"></div>
                                            <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'am') . '</span>', 'after' => '</div>')); ?>
                                            <?php edit_post_link(__('Edit', 'am'), '<br /><p>', '</p>'); ?>
                                        </div>
                                    </div>

                                </div><!--/blog_art_inside_box-->
                                <div class="blog_comments_container">
                                    <?php comments_template(); ?>
                                </div><!--/blog_comments_box-->
                            </div>
                        </div><!--/blog_article_item-->

                        <?php
                    endwhile;

                else :
                    get_template_part('templates/content', 'none');
                endif;
                wp_reset_query();
                ?>

            </div>

            <div class="related_box">
                <h4>
                    <span>THE KATIE DEAN BLOG</span>
                </h4>
            </div>
            <a href="<?php echo get_permalink(template_page_id('blog')); ?>" class="more_blogs">MORE BLOGS</a>

        </div>
        <?php get_sidebar(); ?>
    </div><!--/the_blog_container-->

    <?php
    $frontpage_id = get_option('page_on_front');
    $show_form = get_field('show_form', $frontpage_id);
    if ($show_form) {
        $form_title = get_field('form_title', $frontpage_id);
        $form_text = get_field('form_text', $frontpage_id);
        ?>
        <div class="email_subscribe_box">
            <?php
            if ($form_title) {
                ?>
                <h3><?php echo str_replace(array('<p>', '</p>'), "", wpautop($form_title)); ?></h3>
                <?php
            }
            ?>
           <form method="post" action="//katiedeanjewelry.us13.list-manage.com/subscribe/post?u=e8ba99d857a0300f3d6a4479f&amp;id=aa18b05827" target="_blank" class="email_subscribe_form" >
		<input type="text" placeholder="First Name" name="FNAME"></label>
		<input type="text" placeholder="Your Email" name="EMAIL"></label>
		<input type="submit" value="GO">
            </form>
            <?php
            if ($form_text) {
                ?>
                <h4><?php echo str_replace(array('<p>', '</p>'), "", wpautop($form_text)); ?></h4>
                <?php
            }
            ?>
        </div><!--/email_subscribe_box-->
        <?php
    }
    ?>
</div><!--/container-->
<?php
get_footer();
?>