<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $product;

if (!$product->is_purchasable())
    return;
?>

<?php
// Availability
$availability = $product->get_availability();
$availability_html = empty($availability['availability']) ? '' : '<p class="stock ' . esc_attr($availability['class']) . '">' . esc_html($availability['availability']) . '</p>';

echo apply_filters('woocommerce_stock_html', $availability_html, $availability['availability'], $product);
?>

<?php if ($product->is_in_stock()) : ?>

    <?php do_action('woocommerce_before_add_to_cart_form'); ?>
    <form class="cart" method="post" enctype='multipart/form-data'>
        <ul class="product_properties_list">
            <li>
                <div class="prod_sb_qty">
                    <h3>QTY</h3>
                    <select class="input-text qty text" title="Qty" name="quantity" id="qdrop">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </li>
        </ul>
        <?php do_action('woocommerce_before_add_to_cart_button'); ?>
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>" />

        <a href="#" class="the_button add_to_bag am-add-cart"><?php _e( 'ADD TO BAG', 'am' ); ?></a>
        <a class="the_button save_to_wishlist" href="<?php echo esc_url(home_url('/')); ?>wp-content/plugins/yith-woocommerce-wishlist/yith-wcwl-ajax.php?action=add_to_wishlist&add_to_wishlist=<?php echo $product->id; ?>">SAVE TO WISHLIST</a>

        <?php //do_action('woocommerce_after_add_to_cart_button'); ?>


    </form>
    <?php //do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>