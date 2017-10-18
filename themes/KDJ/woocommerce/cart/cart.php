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
                            <h3>
	                        <?php
		                    if (!$_product->is_visible())
		                        echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
		                    else
		                        echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);

                    // $variations = [];
                    // foreach($cart_item['variation'] as $key => $variation) {
                    //     if (empty($variation)) continue;

                    //     $idx = strrpos($key, '_') + 1;

                    //     $key = ucfirst(substr($key, $idx));
                    //     $variation = ucfirst($variation);
                    //     $variations[$key] = $variation;
                    // }

                    //foreach($variations as $key => $var): <!-- ?>
                    //
                                        <!-- <span class="cart_product_color"></span> -->
                 <!--
                    // endforeach;
                    ?> -->

                    <?php

                    // Meta data
                    // Backorder notification
                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
                        echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
                            ?></h3>
                            <ul>
                                <?php echo WC()->cart->get_item_data($cart_item); ?>
                                <li>$<?php echo $_product->get_price(); ?></li>
                            </ul>




	                            <?
                            echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="checkout_delete_link" title="%s">REMOVE</a>', esc_url(WC()->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key);
                            ?>
                        </li>


                        <li class="check_prod_style_cell">
                        	<?=($product_size) ? '<span class="cart_product_size">'.$product_size.'</span>' : '';?>
                        	<span class="qty_txt">QTY</span>
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
                    </ul>


                    <?php
                }
            }

            do_action('woocommerce_cart_contents');

              ?>
              <tr>
              <td colspan="6" class="actions">

              <input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> <input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />



              <?php wp_nonce_field( 'woocommerce-cart' ); ?>
              </td>
              </tr>

              <?php do_action( 'woocommerce_after_cart_contents' );
            ?>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>

    </div>
	<a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>" class="continue_shopping_link"><span>&larr;</span> KEEP SHOPPING</a>
</div>

<div class="cart_right">
	<div class="cart_discount_code">
        <? if ( WC()->cart->coupons_enabled() ): ?>
            <div class="coupon">

                <label for="coupon_code">
                    <?php _e( 'Discount Code', 'woocommerce' ); ?>:</label>
                <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'APPLY', 'woocommerce' ); ?>" />
                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Enter Code', 'woocommerce' ); ?>" />
                <? do_action('woocommerce_cart_coupon'); ?>

            <? foreach ( WC()->cart->get_coupons() as $code => $coupon ): ?>
                <?php
                    $type = '&nbsp;';
                    $_type = $coupon->get_discount_type();

                    if ( strpos('percent', $_type) ){
                        $type = '%';
                    } elseif ( strpos('fixed', $_type) ) {
                        $type = '$';
                    }
                ?>

                <div class="cmrd-coupon">
                    <div class="cmrd-coupon-data">
                        <em><?= $code ?>:&nbsp;</em><strong><?= $type ?><?= $coupon->amount ?></strong>
                    </div>
                    <input type="submit" value="Remove Coupons" class="button remove-coupon" data-code=" <?=$coupon->get_code()?> " />
                </div>
            <? endforeach ?>

            </div>

        <? endif ?>
    </div>

	<ul id="totals">
		<li class="cart-subtotal">
			<span><?php _e( 'Subtotal', 'woocommerce' ); ?></span><span><?php wc_cart_totals_subtotal_html(); ?></span>
		</li>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<li class="fee">
				<span><?php echo esc_html( $fee->name ); ?></span><span><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</li>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<li class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<span><?php echo esc_html( $tax->label ); ?></span><span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</li>
				<?php endforeach; ?>
			<?php else : ?>
				<li class="tax-total">
					<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span><span><?php wc_cart_totals_taxes_total_html(); ?></span>
				</li>
			<?php endif; ?>
		<?php endif; ?>

<!--
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<li class="cart_review_totals_shipping">
				<span><?php _e( 'Shipping', 'woocommerce' ); ?></span><span class="shipping-amount"><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
			</li>

			<?php //wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>
-->

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<li class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<? $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ) * -1 ?>
			<span><?=$code?></span><span><?= wc_price($amount) ?></span>
			</li>
		<?php endforeach; ?>


		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<li class="order-total">
			<span><?php _e( 'Total', 'woocommerce' ); ?></span><span><?php wc_cart_totals_order_total_html(); ?></span>
		</li>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</ul>
	<?php
		$featuredProductID = get_field('checkout_and_cart_featured_product_id', $post_id);
		$preferredFeaturedImage = get_field('preferred_image', $post_id);

		if ($preferredFeaturedImage) {
			$featuredProductImageURL = $preferredFeaturedImage;
		} else {
			$woocommerceFeaturedImageID = get_post_thumbnail_id($featuredProductID);
			$featuredProductImageURL = trim(wp_get_attachment_image_src($woocommerceFeaturedImageID, 'medium' )[0]);
		}

		$featuredProduct = wc_get_product( $featuredProductID );
		$featuredProductURL = get_permalink($featuredProductID);
		$featuredText = get_field('featured_text', $post_id);
	?>

	<div class="checkout_and_cart_featured_image" style="background: url(<?=$featuredProductImageURL?>)">
		<span class="checkout_and_cart_featured_text"><?=$featuredText?></span>
			<a href="<?=$featuredProductURL?>" class="cta" id="featured_checkout_item">ADD TO CART</a>
	</div>


    <div class="check_free_shipping_note"><?php //the_field('free_shipping_text','option') ?></div>
<!-- 		<div class="checkout_total">SUBTOTAL : <?php wc_cart_totals_subtotal_html(); ?></div> -->
		<input type="submit" class="button alt wc-forward the_button checkout_btn" name="proceed" value="<?php _e( 'CHECKOUT', 'woocommerce' ); ?>" />
        <p>Taxes and shipping applied at checkout.</p>
<?php wp_nonce_field( 'woocommerce-cart' ); ?>
	</form>

	<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>



<!--
    <div class="cart-collaterals">

        <?php do_action('woocommerce_cart_collaterals'); ?>

        <?php //woocommerce_cart_totals(); ?>

        <?php // woocommerce_shipping_calculator(); ?>


		<?php do_action('woocommerce_after_cart'); ?>

    </div>
-->
