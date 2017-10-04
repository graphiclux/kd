<?php
/*
  Template Name: Meet Designer
 */

get_header();
?>

<?php
if (have_posts()) : while (have_posts()) : the_post();
        $designer_name = get_field('designer_name');
        $peoples = get_field('peoples');
        ?>

        <div class="press_big_img_title meet_designer" <?php if (has_post_thumbnail()) { ?>style="background: url('<?php echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>') center top no-repeat;" <?php } ?>>
            <?php if ($designer_name) { ?>
                <h2><?php echo $designer_name; ?></h2>
            <?php } ?>
        </div>
        <div class="inner_page_box">
            <div class="container">
                <div class="inner_page_content">
                    <div class="centered">
                        <div class="entry">
                            <h1><?php the_title(); ?></h1>
                            <?php the_content(__('Read more', 'am')); ?>
                            <div class="clear"></div>
                            <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'am') . '</span>', 'after' => '</div>')); ?>
                            <?php edit_post_link(__('Edit', 'am'), '<br /><p>', '</p>'); ?>
                        </div>
                    </div>
                </div>
                <?php if ($peoples) { ?>
                    <ul class="people_list">
                        <?php
                        foreach ($peoples as $item) {
                            ?>
                            <li>
                                <?php
                                if (!empty($item["photo"])) {
                                    echo '<img src="' . $item["photo"]["sizes"]["people-face"] . '" alt="">';
                                }
                                ?>
                                <div class="people_list_text">
                                    <div class="entry">
                                        <?php
                                        if ($item["title"] !== "") {
                                            echo '<h2>' . $item["title"] . '</h2>';
                                        }
                                        echo wpautop($item["text"]);
                                        ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                <?php } ?>
            </div><!--/container-->

        </div><!--/inner_page_box-->


        <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>