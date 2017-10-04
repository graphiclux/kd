<?php
global $am_option;

if( isset( $_GET['showall'] ) ){ 
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return -1;' ) ); 
} else {
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.get_option('posts_per_page').';' ) );
}

$am_option['shortname'] = "am";
$am_option['textdomain'] = "am";
$am_option['version'] = "1.0.1";

$am_option['url']['includes_path'] = 'includes';
$am_option['url']['includes_url'] = get_template_directory_uri().'/'.$am_option['url']['includes_path'];
$am_option['url']['extensions_path'] = $am_option['url']['includes_path'].'/extensions';
$am_option['url']['extensions_url'] = get_template_directory_uri().'/'.$am_option['url']['extensions_path'];

// Functions
require_once($am_option['url']['includes_path'].'/fn-core.php');
require_once($am_option['url']['includes_path'].'/fn-custom.php');

// Extensions
require_once($am_option['url']['extensions_path'].'/breadcrumb-trail.php');
require_once($am_option['url']['extensions_path'].'/resize.php');

/* Theme Init */
require_once ($am_option['url']['includes_path'].'/theme-widgets.php');
require_once($am_option['url']['includes_path'].'/theme-init.php');

/*--------------------------------------------------------------------------------------------------
 Don't show Categories -  Add a comma after category ie; 'gifts-50-and-under', 'rings'
--------------------------------------------------------------------------------------------------*/
add_filter( 'get_terms', 'get_subcategory_terms', 10, 3 );
function get_subcategory_terms( $terms, $taxonomies, $args ) {
 $new_terms = array();
 // if a product category and on the shop page
 if ( in_array( 'product_cat', $taxonomies ) && ! is_admin() ) {
 foreach ( $terms as $key => $term ) {
 if ( ! in_array( $term->slug, array( 'gifts-50-and-under' ) ) ) {
 $new_terms[] = $term;
 }
 }
 $terms = $new_terms;
 }
 return $terms;
}

// Register Style
function custom_styles() {

	wp_register_style( 'grayson', get_stylesheet_directory_uri() . '/grayson/grayson.css', false, '1' );
	wp_register_style( 'sumner', get_stylesheet_directory_uri() . '/grayson/sumner/sumner.css', false, '1' );
//	wp_register_style('jquery-mobile', 'http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css');


	wp_register_script( 'sumner-js', get_stylesheet_directory_uri() . '/grayson/sumner/sumner.js', array('jquery'), '1', false );
	wp_register_script( 'grayson', get_stylesheet_directory_uri() . '/grayson/grayson.js', array('jquery'), '1', false );
	wp_register_script( 'plugins', get_stylesheet_directory_uri() . '/grayson/jquery.plugin.min.js', false, '1' );
	wp_register_script( 'jquery-mobile', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js', false, false, true);
	wp_register_script( 'grayson-payment', get_stylesheet_directory_uri() . '/grayson/payment.js', false, true );


	wp_enqueue_script('plugins');
	
	$data = array(
		'coupon_nonce' => wp_create_nonce('apply-coupon'),
		'update_order_nonce' => wp_create_nonce('update-order-review')
	);
	  
	wp_localize_script('grayson', 'localized_config', $data);
	wp_enqueue_script( 'grayson');

  wp_enqueue_script('grayson-payment');
	
	if (is_woocommerce()) {
	  
		if (is_category() || is_single() || is_archive()) {
			wp_enqueue_script('jquery-mobile');
			wp_enqueue_script('sumner-js');

		}
	
	}
//  wp_enqueue_style( 'jquery-mobile' );

	wp_enqueue_style( 'grayson' );
	wp_enqueue_style( 'sumner' );


	
 	wp_enqueue_script( 'app', get_stylesheet_directory_uri() . '/includes/js/app.js', false, '', true);


}
add_action( 'wp_enqueue_scripts', 'custom_styles' );






function custom_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}

add_action( 'template_redirect', 'custom_track_product_view', 20 );












//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// WOOCOMMERCE CUSTOM HOOKS ////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CUSTOM PHONE FIELD & CHANGE PLACEHOLDERS
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     
     unset($fields['billing']);
     unset($fields['shipping']['shipping_country']);
     
     $fields['billing']['billing_first_name'] = array(
	    'placeholder'   => _x('First Name', 'placeholder', 'woocommerce'),
	    'priority'		=> 10
     );
     
     $fields['billing']['billing_last_name'] = array(
	    'placeholder'   => _x('Last Name', 'placeholder', 'woocommerce'),
	    'priority'		=> 20
     );
     
     $fields['billing']['billing_email'] = array(
	    'placeholder'   => _x('Email', 'placeholder', 'woocommerce'),
	    'priority'		=> 30
     );
     
     $fields['billing']['billing_address_1'] = array(
	    'placeholder'   => _x('Street Address 1', 'placeholder', 'woocommerce'),
	    'priority'		=> 40
     );
     
     $fields['billing']['billing_address_2'] = array(
	    'placeholder'   => _x('Street Address 2', 'placeholder', 'woocommerce'),
	    'priority'		=> 50
     );
     
     $fields['billing']['billing_country'] = array(
	    'type'			=> 'country',
	    'placeholder'   => _x('Country', 'placeholder', 'woocommerce'),
	    'priority'		=> 60
     );
     
     $fields['billing']['billing_city'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('City', 'placeholder', 'woocommerce'),
	    'priority'		=> 70
     );
     
     $fields['billing']['billing_state'] = array(
	    'placeholder'   => _x('State', 'placeholder', 'woocommerce'),
	    'type'   => 'state',
	    'priority'		=> 80
     );
     
     $fields['billing']['billing_postcode'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('Zip', 'placeholder', 'woocommerce'),
	    'priority'		=> 90
     );
     
     $fields['billing']['billing_phone'] = array(
	    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
	    'required'  => false,
	    'class'     => array('form-row-wide'),
	    'clear'     => true,
	    'priority'	=> 95
     );
     
     
     $fields['shipping']['shipping_first_name'] = array(
	    'placeholder'   => _x('First Name', 'placeholder', 'woocommerce'),
	    'priority' 		=> 10
     );
     
     $fields['shipping']['shipping_last_name'] = array(
	    'placeholder'   => _x('Last Name', 'placeholder', 'woocommerce'),
	    'priority' 		=> 20
     );
     
     $fields['shipping']['shipping_address_1'] = array(
	    'placeholder'   => _x('Street Address 1', 'placeholder', 'woocommerce'),
	    'priority' 		=> 30
     );
     
     $fields['shipping']['shipping_address_2'] = array(
	    'placeholder'   => _x('Street Address 2', 'placeholder', 'woocommerce'),
	    'priority' 		=> 40
     );
     
     $fields['shipping']['shipping_country'] = array(
	     'type'			=> 'country',
	    'placeholder'   => _x('Country', 'placeholder', 'woocommerce'),
	    'priority' 		=> 60
     );
     
     $fields['shipping']['shipping_postcode'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('Zip', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_city'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('City', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_state'] = array(
	    'type'			=> 'state',
	    'placeholder'   => _x('State', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_phone'] = array(
	    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
	    'required'  => false,
	    'class'     => array('form-row-wide'),
	    'clear'     => true,
	    'priority'	=> 90
     );
     
     unset($fields['order']['order_comments']); 

     return $fields;
}

/*
remove_action('woocommerce_checkout_billing','woocommerce_checkout_billing');
add_action('woocommerce_billing_info','woocommerce_checkout_billing');
*/




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  SHIPPING OPTION

remove_action('woocommerce_after_checkout_shipping_form', 'wc_cart_totals_shipping_html', 10);

// hook into the fragments in AJAX and add our new table to the group
add_filter('woocommerce_update_order_review_fragments', 'websites_depot_order_fragments_split_shipping', 10, 1);

function websites_depot_order_fragments_split_shipping($order_fragments) {

	ob_start();
	websites_depot_woocommerce_order_review_shipping_split();
	$websites_depot_woocommerce_order_review_shipping_split = ob_get_clean();

	$order_fragments['.websites-depot-checkout-review-shipping-table'] = $websites_depot_woocommerce_order_review_shipping_split;

	return $order_fragments;

}

// We'll get the template that just has the shipping options that we need for the new table
function websites_depot_woocommerce_order_review_shipping_split( $deprecated = false ) {
	wc_get_template( 'checkout/shipping-order-review.php', array( 'checkout' => WC()->checkout() ) );
}


add_action('woocommerce_after_checkout_shipping_form', 'websites_depot_move_new_shipping_table', 5);
function websites_depot_move_new_shipping_table() {
	echo '<div class="shop_table websites-depot-checkout-review-shipping-table"></div>';
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// KEEP SHOPPING LINK
add_action( 'woocommerce_before_checkout_form', 'add_keep_shopping_link' );
function add_keep_shopping_link() {
	echo '<a class="continue_shopping_link" href="/product-category/whats-new/">';
		_e( '&larr; KEEP SHOPPING', 'woocommerce' );
	echo '</a><span class="help">Need help? Contact us now: <a href="mailto:info@katiedeanjewelry.com">info@katiedeanjewelry.com</a></span>';
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BILLING
add_action( 'woocommerce_before_checkout_billing_form', 'add_billing_header' );
function add_billing_header() {
	echo '<h3>';
		_e( '1. Customer Information', 'woocommerce' );
	echo '</h3>';
	echo '<a href="#" id="billing_edit" class="checkout_edit">EDIT</a>';
	echo '<div class="field_review" id="billing_field_review"></div>';
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SHIPPING
add_action( 'woocommerce_after_checkout_shipping_form', 'add_shipping_continue_button' );
function add_shipping_continue_button() {
	echo '<a href="#" class="continue" id="shipping_continue">Continue</a>';
}
add_action( 'woocommerce_before_checkout_shipping_form', 'add_shipping_header' );
function add_shipping_header() {
	echo '<h3>';
		_e( '2. Shipping', 'woocommerce' );
	echo '</h3>';
	echo '<a href="#" id="shipping_edit" class="checkout_edit">EDIT</a>';
	echo '<div class="field_review" id="shipping_field_review"></div>';
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CHECKOUT - COUPON CODE
//remove_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form', 5 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_coupon', 'woocommerce_checkout_coupon_form', 5 );

// rename the "Have a Coupon?" message on the checkout page
//function woocommerce_rename_coupon_message_on_checkout() {
//// 	return 'Discount Code';
//}
//add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout', 20 );

// rename the coupon field on the checkout page
function woocommerce_rename_coupon_field_on_checkout( $translated_text, $text, $text_domain ) {
	// bail if not modifying frontend woocommerce text
	if ( is_admin() || 'woocommerce' !== $text_domain ) {
		return $translated_text;
	}
	if ( 'Coupon code' === $text ) {
		$translated_text = 'Enter Code';
	
	} elseif ( 'Apply Coupon' === $text ) {
		$translated_text = 'APPLY';
	}
	return $translated_text;
}
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_checkout', 10, 3 );




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PAYMENT
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_payment' );





//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// REVIEW
add_action( 'woocommerce_checkout_before_order_review', 'review_and_purchase' );
function review_and_purchase() {
	echo '<div class="review_and_purchase">';
	
		echo '<h3>';
			_e( '4. Review & Purchase', 'woocommerce' );
		echo '</h3>';
		
		echo '<span class="review_and_purchase_instructions">';
			echo "Click the purchase button below after you have reviewed your information.";
		echo '</span>';
		
		echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="PURCHASE" data-value="PURCHASE" />' );
		
	echo '</div>';
}




// define the woocommerce_thankyou_order_received_text callback 
function filter_woocommerce_thankyou_order_received_text( $var, $order ) { 
    $var = '<h3>Your Order Has Been Placed!</h3>';
    return $var; 
}; 
         
// add the filter 
add_filter( 'woocommerce_thankyou_order_received_text', 'filter_woocommerce_thankyou_order_received_text', 10, 2 ); 