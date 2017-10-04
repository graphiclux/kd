<?php
/*
  Template Name: Look Book
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="look_book_big_img_title">
            <?php
            if (has_post_thumbnail()) {
                ?>
                <div class="look_book_big_img_box">
                    <div>
                        <?php the_post_thumbnail('look-book-thumb'); ?>
                    </div>
                </div>

                <?php
            }
            $page_title = get_field('page_title');
            $page_title_prefix = get_field('page_title_prefix');

            if ($page_title || $page_title_prefix) {
                ?>
                <h2>
                    <?php
                    if ($page_title_prefix) {
                        echo '<i>' . $page_title_prefix . ' </i>';
                    }
                    if ($page_title) {
                        echo '<span>' . $page_title . '</span>';
                    }
                    ?>
                </h2>
                <?php
            }
            $left_block_title = get_field('left_block_title');
            $left_block_text = get_field('left_block_text');
            $left_block_url = get_field('left_block_url');

            if ($left_block_title || $left_block_text) {
                ?>
                <div class="look_book_title_text">
                    <?php if ($left_block_title) { ?>
                        <h3><?php echo $left_block_title; ?></h3>
                        <?php
                    }
                    if ($left_block_text) {
                        echo wpautop($left_block_text);
                    }
                    if ($left_block_url) {
                        ?>
                        <a href="<?php echo $left_block_url; ?>" class="the_button">SHOP THE LOOK</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div><!--/look_book_big_img_title-->

        <div class="container">
            <?php
            $slider = get_field('slider');
            $slider_title = get_field('slider_title');
            $slider_text = get_field('slider_text');
            if ($slider_title || $slider_text || $slider) {
                ?>

                <div class="look_book_slider_1_box">
                    <?php
                    if ($slider) {
                        ?>
                        <div class="slider-holder">
                            <ul class="look_book_slider_1">
                                <?php foreach ($slider as $slide) { ?>
                                    <li>
                                        <div class="lbs_1_image_box">
                                            <img src="<?php echo $slide["sizes"]["look-book-slider"] ?>" alt="">
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php
                    }

                    if ($slider_title || $slider_text) {
                        ?>
                        <div class="lbs_1_text_box">
                            <?php
                            if ($slider_title) {
                                echo '<h4>' . $slider_title . '</h4>';
                            }
                            if ($slider_text) {
                                echo wpautop($slider_text);
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <?php
            }

            $gallery = get_field('gallery');
            if ($gallery) {
                ?>

                <div class="look_book_slider_2_box">
                    <ul class="look_book_slider_2">
                        <?php foreach ($gallery as $item) { ?>
                            <li>
                                <img src="<?php echo $item["sizes"]["look-book-gallery"]; ?>" alt="">
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php
            }
            ?>

        </div><!--/container-->
        <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>