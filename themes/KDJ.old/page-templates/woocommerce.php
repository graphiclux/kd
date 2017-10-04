<?php
/*
  Template Name: Woocommerce
 */
global $am_option, $woocommerce, $product;
get_header();

if (!is_account_page() && is_user_logged_in() || is_cart()) {
    echo '<div class="container">';
}
/* echo '<pre>';
  var_dump();
  echo '</pre>'; */
$url_arr = explode("/", $_SERVER["REQUEST_URI"]);
?>

<?php
if (have_posts()) : while (have_posts()) : the_post();

        if (is_cart()) {
            ?>
            <div class="checkout_page_box">
                <h2><?php the_title() ?></h2>
                <?php the_content() ?>
            </div>
            <?php
        } else if (is_account_page() && !is_user_logged_in()) {
            the_content();
        } else if (is_account_page() && is_user_logged_in() || in_array('wishlist', $url_arr)) {
            $myaccount_page = get_permalink(get_option('woocommerce_myaccount_page_id'));
            if(in_array('view-order', $url_arr)){
                $br_title='<li>Order History</li>';
            }else if(in_array('edit-address', $url_arr)){
                $br_title='<li>Addresses</li>';
            }else if(in_array('wishlist', $url_arr)){
                $br_title='<li>Wish List</li>';
            }else if(in_array('edit-account', $url_arr)){
                $br_title='<li>Change Password</li>';
            }
            ?>
            <div class="container">
                <div class="pathway_box">
                    <ul>  
                        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="trail-begin">Home</a></li>
                        <li><a href="<?php echo $myaccount_page; ?>">MY ACCOUNT</a></li>
                        <?php echo $br_title; ?>
                    </ul>
                </div>
                <div class="main-frame">
                    <div class="aside">
                        <div class="widget widget-menu">
                            <h3 class="title">My Account</h3>
                            <ul>
                                <?php
                                if (is_user_logged_in()) {
                                    ?>
                                    <li><a href="<?php echo $myaccount_page . get_option('woocommerce_myaccount_view_order_endpoint'); ?>">Order History</a></li>
                                    <li><a href="<?php echo $myaccount_page . get_option('woocommerce_myaccount_edit_address_endpoint'); ?>">Addresses</a></li>
                                    <li><a href="<?php echo get_permalink(124); ?>">Wish List</a></li>
                                    <li><a href="<?php echo $myaccount_page . get_option('woocommerce_myaccount_edit_account_endpoint'); ?>">Change Password</a></li>
                                    <li><a href="<?php echo $myaccount_page . get_option('woocommerce_logout_endpoint'); ?>">Sign Out</a></li>
                                    <?php
                                } else {
                                    $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
                                    if ($myaccount_page_id) {
                                        ?>
                                        <li><a href="<?php echo get_permalink($myaccount_page_id); ?>">Sign Out</a></li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="content-frame right">
            <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <?php
        } else if (in_array('order-received', $url_arr)) {
            $new_url = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
            $order_id = $new_url[3];
            $order = new WC_Order( $order_id );
            $order_subtotal = $order->get_total();
           ?>
            
            <div class="main-holder">
                <div class="content-holder">
                    <h2 class="thank">thank you!</h2>
                    <?php if(!($order->status == 'failed')) { ?>
                    <img src="https://shareasale.com/sale.cfm?amount=<?php echo $order_subtotal; ?>&tracking=<?php echo $order_id; ?>&transtype=sale&merchantID=56362" width="1" height="1" />
                    <?php } ?>
                </div>
                <div class="aside right">
                    <div class="widget widget-info">
                        <div class="image">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/ico_cart_big.png" height="112" width="112" alt="" />
                        </div>
                        <div class="description">
                            <h3>YOUR BAG IS EMPTY! FILL IT UP!</h3>
                        </div>
                    </div>
                    <div class="widget">
                        <a class="btn normal" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>">SHOP NEW ARRIVALS</a>
                    </div>
                    <div class="widget">
                        <?php the_content(); ?>
                    </div>
                    <div class="widget">
                        <p>A confirmation email has been sent to you. Please print out this page and keep the order number for your records. </p>
                    </div>
                </div>
            </div><?php
        } else {
            get_template_part('templates/content', 'page');
        }

    endwhile;
endif;
if (!is_account_page() && is_user_logged_in() || is_cart()) {
    echo '</div>';
}
?>

<?php get_footer(); ?>

