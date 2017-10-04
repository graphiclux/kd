<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

wc_print_notices();
?>
<div class="checkout_page_box">
    <?php do_action('woocommerce_before_cart'); ?>

    <form action="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" method="post">

        <?php do_action('woocommerce_before_cart_table'); ?>

        <div class="checkout_tbl">
            <ul class="checkout_tbl_title">
                <li class="check_prod_photo_cell"></li>
                <li class="check_prod_name_cell">PRODUCT NAME</li>
                <li class="check_prod_delete_cell"></li>
                <li class="check_prod_style_cell">STYLE / QUANTITY</li>
                <li class="check_prod_subtotal_cell">SUBTOTAL</li>
            </ul>

<!--<table class="shop_table cart" cellspacing="0">
<thead>
        <tr>
                <th class="product-remove">&nbsp;</th>
                <th class="product-thumbnail">&nbsp;</th>
                <th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
                <th class="product-price"><?php _e('Price', 'woocommerce'); ?></th>
                <th class="product-quantity"><?php _e('Quantity', 'woocommerce'); ?></th>
                <th class="product-subtotal"><?php _e('Total', 'woocommerce'); ?></th>
        </tr>
</thead>
<tbody>-->
            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    ?>

                    <ul class="checkout_tbl_row <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                        <li class="check_prod_photo_cell">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                            if (!$_product->is_visible())
                                echo $thumbnail;
                            else
                                printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
                            ?>
                        </li>
                        <li class="check_prod_name_cell">
                            <h3><?php
                    if (!$_product->is_visible())
                        echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
                    else
                        echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);

                    // Meta data
                    

                    // Backorder notification
                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
                        echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
                            ?></h3>
                            <ul>
                                <?php echo WC()->cart->get_item_data($cart_item); ?>
                                <li>$<?php echo $_product->get_price(); ?></li>
                            </ul>
                        </li>
                        <li class="check_prod_delete_cell">
                            <?php
                            echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="checkout_delete_link" title="%s">DELETE</a>', esc_url(WC()->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key);
                            ?>
                        </li>
                        <li class="check_prod_style_cell">
                            <?php
                            if ($_product->is_sold_individually()) {
                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                            } else {
                                $product_quantity = woocommerce_quantity_input(array(
                                    'input_name' => "cart[{$cart_item_key}][qty]",
                                    'input_value' => $cart_item['quantity'],
                                    'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                    'min_value' => '0'
                                        ), $_product, false);
                            }
                            //echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
                            ?>
                            <select class="input-text qty text" name="cart[<?php echo $cart_item_key; ?>][qty]">
                                <?php
                                for($r=1; $r<=$_product->get_stock_quantity(); $r++){
                                    echo '<option value="'.$r.'"';
                                    if($r==$cart_item['quantity']){
                                        echo ' selected="selected"';
                                    }
                                    echo '>'.$r.'</option>';
                                }
                                ?>
                            </select>
                        </li>
                        <li class="check_prod_subtotal_cell">
                            <span class="checkout_price">
                                <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                ?></span>
                        </li>
                    </ul>


                    <?php
                }
            }

            do_action('woocommerce_cart_contents');
            /*
              ?>
              <tr>
              <td colspan="6" class="actions">

              <?php if ( WC()->cart->coupons_enabled() ) { ?>
              <div class="coupon">

              <label for="coupon_code"><?php _e( 'Coupon', 'woocommerce' ); ?>:</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />

              <?php do_action('woocommerce_cart_coupon'); ?>

              </div>
              <?php } ?>

              <input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> <input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />

              <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

              <?php wp_nonce_field( 'woocommerce-cart' ); ?>
              </td>
              </tr>

              <?php do_action( 'woocommerce_after_cart_contents' ); */
            ?>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>

    

    <a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>" class="continue_shopping_link">CONTINUE SHOPPING</a>
				<div class="check_free_shipping_note"><?php the_field('free_shipping_text','option') ?></div>
				<div class="checkout_total">SUBTOTAL : <?php wc_cart_totals_subtotal_html(); ?></div>
				<input type="submit" class="checkout-button button alt wc-forward the_button checkout_btn" name="proceed" value="<?php _e( 'CHECKOUT', 'woocommerce' ); ?>" />
<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				</form>
    <div class="cart-collaterals">

        <?php do_action('woocommerce_cart_collaterals'); ?>

        <?php //woocommerce_cart_totals(); ?>

        <?php woocommerce_shipping_calculator(); ?>

    </div>

    <?php do_action('woocommerce_after_cart'); ?>
</div>