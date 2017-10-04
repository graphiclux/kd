<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $current_user;
if (!defined('ABSPATH')) {
    exit;
}
$url_arr = explode("/", $_SERVER["REQUEST_URI"]);
wc_print_notices();

if (in_array('view-order', $url_arr)) {
    wc_get_template('myaccount/my-orders.php', array('order_count' => $order_count));
} else {
    get_currentuserinfo();
    ?>
    <h3 class="head">ACCOUNT INFO</h3>
    <div class="panel">
        <span class="p_info">Email: <?php echo $current_user->user_email; ?></span><ul class="p_nav">
            <li><a href="<?php echo $myaccount_page . get_option('woocommerce_myaccount_edit_account_endpoint'); ?>">UPDATE EMAIL</a></li>
        </ul>
    </div>

    <?php
}
?>


