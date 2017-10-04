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
            <span>Archive</span>
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

                            <div class="blog_article_text_box">
                                <div class="blog_article_text">
                                    <a href="<?php the_permalink(); ?>" class="bloag_art_read_more">read more</a>
                                    <div class="entry"><?php the_excerpt(); ?></div>
                                </div>
                                <?php if (has_post_thumbnail()) { ?>
                                    <div class="blog_article_image">
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('our-story-thumb'); ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div><!--/blog_article_item-->

                        <?php
                    endwhile;
                    get_template_part('templates/pagination', 'post');
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
            <form method="post" action="https://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup3766" accept-charset="UTF-8" target="_blank" class="email_subscribe_form" >
                <input type="hidden" name="redirect" value="http://www.icontact.com/www/signup/thanks.html">
                <input type="hidden" name="errorredirect" value="http://www.icontact.com/www/signup/error.html">
                <input type="text" placeholder="First Name" name="fields_fname">
                <input type="text" placeholder="Email" name="fields_email">
                <input type="hidden" name="listid" value="6698">
                <input type="hidden" name="specialid:6698" value="M77F">
                <input type="hidden" name="clientid" value="1207836">
                <input type="hidden" name="formid" value="3766">
                <input type="hidden" name="reallistid" value="1">
                <input type="hidden" name="doubleopt" value="0">
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