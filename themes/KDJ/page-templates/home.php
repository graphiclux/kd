<?php
/*
  Template Name: Home
 */

get_header();
?>

<img src="https://shareasale.com/sale.cfm/?amount=14.95&tracking=475&transtype=sale&merchantID=56362" width="1" height="1">
<?php
if (have_posts()) : while (have_posts()) : the_post();

        $main_slider = get_field('main_slider');
        if ($main_slider) {
            ?>
            <div class="big_slider_box">
                <ul class="big_slider">
                    <?php
                    foreach ($main_slider as $slide) {
                        ?>
                        <li>
                        	<?php if($slide['hide_button'] && $slide["url"]) : ?><a href="<?php echo $slide["url"] ?>"><?php endif; ?>
                            <img src="<?php echo $slide["image"]["sizes"]["main-slider"] ?>" alt="">
                            <div class="big_slider_text_box">
                                <?php
                                if ($slide["title"] !== "") {
                                    ?>
                                    <h3><?php echo $slide["title"] ?></h3>
                                    <?php
                                }
                                if ($slide["url"] && !$slide['hide_button']) {
                                    ?>
                                    <a href="<?php echo $slide["url"] ?>" class="big_slider_link_1">SHOP THIS LOOK</a>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php if($slide['hide_button'] && $slide["url"]) : ?></a><?php endif; ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div><!--/big_slider_box-->
            <?php
        }
        ?>
        <div class="container">
            <?php
            $primary_boxes = get_field('primary_boxes');
            if ($primary_boxes) {
                ?>
                <ul class="primary_boxes">
                    <?php
                    foreach ($primary_boxes as $box) {
                        ?>
                        <li>
                            <a href="<?php echo $box["url"] ?>">
                                <img src="<?php echo $box["image"]["sizes"]["primary-boxes-thumb"] ?>" alt="">
                                <div>
                                    <span><span><?php echo $box["title"] ?></span></span>
                                </div>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul><!--/primary_boxes-->
               
                <?php
            }

            $show_widgets = get_field('show_widgets');
            if ($show_widgets) {
                $look_book_title = get_field('look_book_title');
                $look_book_text = get_field('look_book_text');
                $look_book_button_title = get_field('look_book_button_title');
                $look_book_url = get_field('look_book_url');
                $look_book_image = get_field('look_book_image');
                $press_title = get_field('press_title');
                $press_text = get_field('press_text');
                $press_button_title = get_field('press_button_title');
                $press_url = get_field('press_url');
                $press_image = get_field('press_image');
                ?>

                <div class="index_widget_box">
                    <div class="index_widget look_book">
                        <img src="<?php echo $look_book_image["sizes"]["widget-box-thumb"]; ?>" alt="">
                        <div class="index_widget_text">
                            <?php
                            if ($look_book_title) {
                                ?>
                                <h3><?php echo $look_book_title; ?></h3>
                                <?php
                            }
                            if ($look_book_text) {
                                ?>
                                <h4><?php echo $look_book_text; ?></h4>
                                <?php
                            }
                            if ($look_book_text && $look_book_url) {
                                ?>
                                <a href="<?php echo $look_book_url; ?>" class="the_button"><?php echo $look_book_title; ?></a>
                                <?php
                            }
                            ?>
                        </div>
                    </div><!--/index_widget look_book-->

                    <div class="index_widget press">
                        <img src="<?php echo $press_image["sizes"]["widget-box-thumb"]; ?>" alt="">
                        <div class="index_widget_text">
                            <?php
                            if ($press_title) {
                                ?>
                                <h3><?php echo $press_title; ?></h3>
                                <?php
                            }
                            if ($press_text) {
                                ?>
                                <h4><?php echo $press_text; ?></h4>
                                <?php
                            }
                            if ($press_text && $press_url) {
                                ?>
                                <a href="<?php echo $press_url; ?>" class="the_button"><?php echo $press_title; ?></a>
                                <?php
                            }
                            ?>
                        </div>
                    </div><!--/index_widget press-->
                </div><!--/index_widget_box-->
                 <?php
                            }
                
                            $show_form = get_field('show_form');
                            if ($show_form) {
                                $form_title = get_field('form_title');
                                $form_text = get_field('form_text');
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

            $shop_bestsellers_title = get_field('shop_bestsellers_title');
            $shop_bestsellers_text = get_field('shop_bestsellers_text');
            $shop_bestsellers_list = get_field('shop_bestsellers_list');

            $read_our_story_title = get_field('read_our_story_title');
            $read_our_story_text = get_field('read_our_story_text');
            $read_our_story_image = get_field('read_our_story_image');
            $read_our_story_url = get_field('read_our_story_url');

            $whatsnew_title = get_field('whatsnew_title');
            $whatsnew_text = get_field('whatsnew_text');
            $whatsnew_list = get_field('whatsnew_list');
            ?>

            <div class="small_slider_container bestsellers">
                <div class="small_slider_box">
                    <ul class="small_slider">
                        <?php
                        foreach ($shop_bestsellers_list as $value) {
                            echo '<li><a href="' . get_permalink($value->ID) . '">';
                            echo get_the_post_thumbnail($value->ID, 'our-story-thumb');
                            echo '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="small_slider_text">
                    <?php if ($shop_bestsellers_title) { ?>
                        <h4><?php echo $shop_bestsellers_title; ?></h4>
                        <?php
                    }
                    if ($shop_bestsellers_text) {
                        echo wpautop($shop_bestsellers_text);
                    }
                    ?>
                </div>
            </div><!--/small_slider_container bestsellers-->


            <div class="read_our_story">
                <a href="<?php echo $read_our_story_url; ?>">
                    <img src="<?php echo $read_our_story_image["sizes"]["our-story-thumb"]; ?>" alt="">
                    <?php if ($read_our_story_title) { ?>
                        <h4><?php echo $read_our_story_title; ?></h4>
                        <?php
                    }
                    if ($read_our_story_text) {
                        echo wpautop($read_our_story_text);
                    }
                    ?>
                </a>
            </div>


            <div class="small_slider_container whats_new">
                <div class="small_slider_box">
                    <ul class="small_slider">
                        <?php
                        foreach ($whatsnew_list as $value) {
                            echo '<li><a href="' . get_permalink($value->ID) . '">';
                            echo get_the_post_thumbnail($value->ID, 'our-story-thumb');
                            echo '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="small_slider_text">
                    <?php if ($whatsnew_title) { ?>
                        <h4><?php echo $whatsnew_title; ?></h4>
                        <?php
                    }
                    if ($whatsnew_text) {
                        echo wpautop($whatsnew_text);
                    }
                    ?>
                </div>
            </div><!--/small_slider_container whats_new-->

        </div><!--/container-->
        <?php
    endwhile;
endif;
?>


<?php get_footer(); ?>