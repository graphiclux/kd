<?php global $am_option, $woocommerce, $product; ?><!DOCTYPE html>
<!--[if IE 7]> <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]> <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]> <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="format-detection" content="telephone=no">
        <title><?php
            if (is_category()) {
                echo __('Category: ', 'am');
                wp_title('');
                ;
            } elseif (function_exists('is_tag') && is_tag()) {
                single_tag_title(__('Tag Archive for &quot;', 'am'));
                echo '&quot;';
            } elseif (is_archive()) {
                wp_title('');
                echo __(' Archive', 'am');
            } elseif (is_page() && !(is_home()) && !(is_front_page())) {
                echo wp_title('');
            } elseif (is_search()) {
                echo __('Search for &quot;', 'am') . esc_html($s) . '&quot;';
            } elseif (!(is_404()) && (is_single()) || (is_page()) && !(is_home()) && !(is_front_page())) {
                wp_title('');
            } elseif (is_404()) {
                echo __('Not Found', 'am');
            } else {
                bloginfo('name');
            }
            ?></title>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <!--[if lt IE 9]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-34375854-1', 'auto');
	  ga('send', 'pageview');

	</script>
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1709434962680252'); // Insert your pixel ID here.
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=1709434962680252&ev=PageView&noscript=1"
	/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- End Facebook Pixel Code -->
	<script type="text/javascript" language="javascript">
		var ajax_url = "<?php bloginfo('url'); ?>/wp-admin/admin-ajax.php";
	</script>
    </head>
    <body <?php body_class(); ?>>


        <div class="wrapper">
            <header id="header">
                <div class="container">
                	<div class="logo-holder">
                    <a href="<?php echo esc_url(home_url('/')); ?>" id="logo" title="<?php bloginfo('name'); ?>">
                        <?php bloginfo('name'); ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="">
                    </a>
                    </div>
                    <div class="cart-holder no-mobile">
                    	<div class="hamburger_menu_box">
                    	    <a href="#" class="hamburger_menu_icon"></a>
                    	    <div class="hamburger_menu">
                    	        <?php wp_nav_menu(array('theme_location' => 'mainmenu2', 'menu_class' => 'sf-menu2', 'menu_id' => 'sf-menu2', 'container' => '', 'depth' => 1)); ?>
                    	    </div>
                    	</div>
	                    <?php if ( is_active_sidebar( 'shopping-cart-dropdown' ) ) : ?>
	                    <div class="the_cart_box">
	                    	<?php dynamic_sidebar( 'shopping-cart-dropdown' ); ?>
	                    </div><!-- #primary-sidebar -->
	                    <?php endif; ?>
	                    
                    </div>
                    <nav id="menu">
                    	<div class="menu-holder">
	                        <?php wp_nav_menu(array('theme_location' => 'mainmenu1', 'menu_class' => 'main_menu', 'menu_id' => 'main_menu', 'container' => '', 'depth' => 2)); ?>
	
	                        <div class="search_container">
	                            <div class="search_icon"></div>
	                            <div class="search_box">
	                                <?php get_search_form(); ?> 
	                            </div>
	                        </div><!--/search_container-->
	                        <div class="cart-holder2 mobile-only">
	                        
	                        	<div class="the_cart_box">
	                        	    <a href="https://katiedeanjewelry.com/cart/">
	                        	        <span><?php _e('BAG', 'am'); ?></span>
	                        	        <div class="the_cart"><?php echo $woocommerce->cart->cart_contents_count; ?></div>
	                        	    </a>
	                        	</div>
	                        	<div class="hamburger_menu_box">
	                        	    <a href="#" class="hamburger_menu_icon"></a>
	                        	    <div class="hamburger_menu">
	                        	        <?php wp_nav_menu(array('theme_location' => 'mainmenu2', 'menu_class' => 'sf-menu2', 'menu_id' => 'sf-menu2', 'container' => '', 'depth' => 1)); ?>
	                        	    </div>
	                        	</div>
	                        
	                        </div>
	                	</div>
                    </nav><!--/navigation-->
                </div><!--/container-->
                <?php if (get_field('free_shipping_text', 'option')) { ?>
                    <div class="header_shipping_ad"><a href="<?php the_field('category_link', 'option'); ?>"><?php the_field('free_shipping_text', 'option'); ?></a></div>
                <?php } ?>
            </header><!--/header-->
            
            <? 
/*
	            $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

				if (preg_match('/product-category/', $url)) {
					?>
					<div class="category_banner" style="background-image: url('/wp-content/uploads/2016/10/Stacked-rings-Claw-cuff-and-Cactus-bracelet-1.jpg')"></div>
					<?
				}
*/
			?>
            <div id="body">

