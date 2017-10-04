<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (isset($_POST['coupon_code']))
    { apply_coupon($_POST['coupon_code']); }; 

function apply_coupon($couponcode) { 
    global $woocommerce; 
    
     $json = array();
    
    WC()->cart->remove_coupons();
    $ret = WC()->cart->add_discount( $couponcode ); 
    
    if ($ret) {
	    
	    if ( ! defined('WOOCOMMERCE_CART') ) {
		  define( 'WOOCOMMERCE_CART', true );
		}
	    

		WC()->cart->calculate_totals();
// 		echo WC()->cart->get_total();
// 		WC()->cart->calculate_shipping();
		
		$json = array(
			'coupon_code' => $couponcode,
			'coupon_amount' => WC()->cart->get_coupon_discount_amount($couponcode),
			'total_html' => WC()->cart->get_cart_total()
		);

	    
// 		woocommerce_cart_totals();
    }
    
       echo json_encode($json, JSON_FORCE_OBJECT);
       
}
exit;