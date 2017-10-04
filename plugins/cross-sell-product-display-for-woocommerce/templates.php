<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cdxfreewoocross_get_cross_sell_products($product_id=false){
	
	if($product_id ===false ){
		
		if(!is_product()){return false;}
		
		$product_id = (int)get_the_ID();
		if($product_id=='0'){return false;}
		
	}
	
	$crosssells = get_post_meta( $product_id, '_crosssell_ids',true);
	if ( sizeof($crosssells ) == 0  || $crosssells =='') { return false; }
	
	return $crosssells;
	
}

/**
* Function to check if woocommerce is installed and active active
*/
function cdxfreewoocross_check_if_woocommerce_is_active(){
	
	//Don't go further if woocommerce is not active.
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return false;
	}else{
		return true;	
	}
}

/**
* Theme - List View
*/
function cdxfreewoocross_theme_list_view($products=false){

	if(cdxfreewoocross_check_if_woocommerce_is_active()===false){return 'Error! Woocommerce is not installed or not active.';}
	
	if($products === false ){
		$products = cdxfreewoocross_get_cross_sell_products();
	}
	
	if($products==false){return false;}
	
	ob_start();
	?>
    
    <div class="cdx-theme-list-view">
        <ul class="product_list_widget"> 
        
		<?php foreach($products as $pid): 
					
				$product 	   = wc_get_product( $pid );
				$product_title = $product->post->post_title;
				$product_desc  = $product->post->post_content;
				$product_short_desc = $product->post->post_excerpt;
				$product_price_regular = wc_price( $product->get_regular_price());
				$product_price_sale    = wc_price( $product->get_sale_price());
				$product_price         = $product->get_price_html();
				
				$product_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'shop_thumbnail' );
				if(isset($product_image[0])){
					$product_image = $product_image[0];	
				}else{
					$product_image = 'null';
				}
				
				$add_to_cart_url = get_permalink(get_option( 'woocommerce_cart_page_id' )).'?add-to-cart='.$pid;
				$product_link = get_permalink($pid);
				
				//Process some data
				$product_short_desc =  substr(strip_tags($product_short_desc),0,50);
				$product_desc       =  substr(strip_tags($product_desc),0,100);
		?>        
        
            <li> 
              <a href="<?php echo $product_link; ?>" title="<?php echo $product_title; ?>">
              <img src="<?php echo $product_image; ?>" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" 
              alt="<?php echo $product_title; ?>" height="180" width="180"> 
              <span class="product-title"><?php echo $product_title; ?></span> 
              </a> 
              <?php echo $product_price; ?>
            </li>
            
        <?php endforeach; ?>    
        </ul>
        
    </div>
    
    <?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}

/**
* Theme - Hover
*/
function cdxfreewoocross_theme_hover($products=false){

	if(cdxfreewoocross_check_if_woocommerce_is_active()===false){return 'Error! Woocommerce is not installed or not active.';}
	
	if($products === false ){
		$products = cdxfreewoocross_get_cross_sell_products();
	}
	
	if($products==false){return false;}
	
	ob_start();
	?>
    
    <div class="cdx cdx-theme-hover">
       
        <div class="row">
        
		<?php foreach($products as $pid): 
					
				$product 	   = wc_get_product( $pid );
				$product_title = $product->post->post_title;
				if($product_title == null){continue;}
				$product_desc  = $product->post->post_content;
				$product_short_desc = $product->post->post_excerpt;
				$product_price_regular = wc_price( $product->get_regular_price());
				$product_price_sale    = wc_price( $product->get_sale_price());
				$product_price         = $product->get_price_html();
				
				$product_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'shop_thumbnail' );
				if(isset($product_image[0])){
					$product_image = $product_image[0];	
				}else{
					$product_image = 'null';
				}
				
				$add_to_cart_url = get_permalink(get_option( 'woocommerce_cart_page_id' )).'?add-to-cart='.$pid;
				$product_link = get_permalink($pid);
				
				//Process some data
				$product_short_desc =  substr(strip_tags($product_short_desc),0,50);
				$product_desc       =  substr(strip_tags($product_desc),0,100);
		?>        
        
            <div class="col-lg-9 col-lg-offset-1">
                
                <div class="cuadro_intro_hover">
                    <p>
                        <img src="<?php echo $product_image; ?>" class="img-responsive" alt="<?php echo $product_title; ?>">
                    </p>
                    <div class="caption">
                        <div class="blur"></div>
                        <div class="caption-text">
                            <a class="product-title" href="<?php echo $product_link; ?>"><h4><?php echo $product_title; ?></h4></a>
                            <p class="product-price"><?php echo $product_price; ?></p>
                            <a class="btn btn-info buy-button" href="<?php echo $add_to_cart_url; ?>">Buy Now</a>
                        </div>
                    </div>
                </div>
                    
            </div>
        <?php endforeach; ?>    
        </div>
        
    </div>
    
    <?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}
?>