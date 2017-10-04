</div>

<footer id="footer">
    <div class="container">
        <div class="the_button footer_signup_link" >
            BECOME AN EMAIL INSIDER!
        </div>
        <form method="post" action="//katiedeanjewelry.us13.list-manage.com/subscribe/post?u=e8ba99d857a0300f3d6a4479f&amp;id=aa18b05827" target="_blank" class="footer_subscribe_form" >
            <label>sign up <input type="text" placeholder="Your Email" name="EMAIL"></label>
            <input type="submit" value="GO">
        </form>
        <ul class="footer_menu">
            <li class="mobile">
                <h5><?php _e('My Account', 'am'); ?></h5>
                <ul>
                    <?php
                    if (is_user_logged_in()) {
                        ?>
                        <li><a href="<?php echo wp_logout_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">Logout</a></li>
                        <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">My Account Info</a></li>  
                        <?php
                    } else {
                        ?>
                        <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">sign in</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <h5><?php _e('Customer Care', 'am'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'footermenu1', 'menu_class' => 'sf-footermenu1', 'menu_id' => 'sf-footermenu1', 'container' => '', 'depth' => 1)); ?>
            </li>
            <li>
                <h5><?php _e('About Us', 'am'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'footermenu2', 'menu_class' => 'sf-footermenu2', 'menu_id' => 'sf-footermenu2', 'container' => '', 'depth' => 1)); ?>
            </li>
            <li>
                <h5><?php _e('Connect With Us', 'am'); ?></h5>
                <ul class="footer_media">
                    <?php
                    $facebook = get_field('facebook', 'option');
                    $twitter = get_field('twitter', 'option');
                    $instagram = get_field('instagram', 'option');
                    $pinterest = get_field('pinterest', 'option');
                    if ($facebook) {
                        ?>
                        <li><a href="<?php echo $facebook; ?>" class="fm_fb" target="_blank"></a></li>
                        <?php
                    }
                    if ($twitter) {
                        ?>
                        <li><a href="<?php echo $twitter; ?>" class="fm_tw" target="_blank"></a></li>
                        <?php
                    }
                    if ($instagram) {
                        ?>
                        <li><a href="<?php echo $instagram; ?>" class="fm_instagram" target="_blank"></a></li>
                        <?php
                    }
                    if ($pinterest) {
                        ?>
                        <li><a href="<?php echo $pinterest; ?>" class="fm_pinterest" target="_blank"></a></li>
                        <?php
                    }
                    ?>
                </ul>

                <div class="footer_callygraphy_by">
                    <h6><?php _e('Calligraphy by', 'am'); ?></h6>
                    <a href="http://mollyjacquesillustration.com/" class="callygraphy_by_logo"><img src="<?php echo get_template_directory_uri(); ?>/images/img_calligraphy_logo.png" alt=""></a>
                </div>

            </li>
        </ul>
        <div class="copyrights">&copy;2009-<?php echo date('Y'); ?> <?php bloginfo('name'); ?>, <?php _e('ALL RIGHTS RESERVED', 'am'); ?></div>
    </div><!--/container-->
</footer><!--/footer-->
</div>
<?php wp_footer(); ?>
</body>
</html>