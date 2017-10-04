<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CodextentWooCrossSellsShortcode {
	
	static $add_script;
	var $wpvepdf_attr = false;

	static function init() {
		
		add_filter('widget_text', 'do_shortcode');
		add_shortcode('cdxwoocs', array(new CodextentWooCrossSellsShortcode(), 'cdxfreewoocross_shortcode_fun'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'register_script_style'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'print_style'));
		
	}

	public function cdxfreewoocross_shortcode_fun($attr) {
						
		$product_id = (isset($attr['pid']))?(int)$attr['pid']:0;
		$theme      = (isset($attr['theme']))?$attr['theme']:'theme-list-view';
		
		//initial value
		$products = false;
		
		//If product ID supplied
		if($product_id!='0'){ $products = cdxfreewoocross_get_cross_sell_products($product_id);}
		
		switch($theme){
		
			case 'theme-list-view':
				$html     = cdxfreewoocross_theme_list_view($products);
			break;
			case 'theme-hover':
				$html     = cdxfreewoocross_theme_hover($products);
			break;	
			default:
				$html     = cdxfreewoocross_theme_list_view($products);
			break;	
			
		}
			
		return $html;
						
	}

	static function register_script_style() {
		wp_register_style('cdx-html-lib',  CE_CDXWOOFREECROSS_PLUGIN_URL. '/assets/css/ce.css');
		wp_register_style('cdx-front',  CE_CDXWOOFREECROSS_PLUGIN_URL. '/assets/css/codextent-woo-cross-sell.css');
	}

	static function print_style(){
		
		wp_enqueue_style('cdx-html-lib');	
		wp_enqueue_style('cdx-front');	
	}
	
}

CodextentWooCrossSellsShortcode::init();
?>