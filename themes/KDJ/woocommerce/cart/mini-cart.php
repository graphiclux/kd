<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					$product_color = ($cart_item['variation']['attribute_pa_color']) ? strtoupper($cart_item['variation']['attribute_pa_color']) : false;
					$product_size = ($cart_item['variation']['attribute_pa_size']) ? explode('size-', $cart_item['variation']['attribute_pa_size'])[1] : false;
					
					if (strlen($product_size) > 1) {
						$firstSizeNumber = substr($product_size, 0, 1);
						$product_size = substr_replace($product_size, $firstSizeNumber . "/", 0, 1);
					}
					
					$product_color = ($product_color) ? "COLOR : " . $product_color : '';
					$product_size = ($product_size) ? "SIZE: " . $product_size : '';
					
					?>
					<li>
					<?php if ( ! $_product->is_visible() ) { ?>
						<?=str_replace(array( 'http:', 'https:' ), '', $thumbnail)?>
						<a class="mini_cart_item_title" href="<?php echo get_permalink( $product_id ); ?>"><h3 class="mini_cart_item_title"><?=trim($product_name)?></h3></a>
					<?php } else { ?>
						<a href="<?php echo get_permalink( $product_id ); ?>"><?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) ?></a>
						<a class="mini_cart_item_title" href="<?php echo get_permalink( $product_id ); ?>"><h3><?=$product_name?></h3></a>
					<?php } ?>
						<?php echo WC()->cart->get_item_data( $cart_item ); ?>

						<?php //echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
						
						<?=$product_price?>
						<?=($product_color) ? '<span class="mini_product_color">'.$product_color.'</span>' : '';?>
						<?=($product_size) ? '<span class="mini_product_size">'.$product_size.'</span>' : '';?>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

<!-- 	<p class="total"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p> -->

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="buttons">
		<a id="mini_bag_link" href="<?php echo WC()->cart->get_cart_url(); ?>" class="button wc-forward"><?php _e( 'View Bag', 'woocommerce' ); ?></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
	</p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
