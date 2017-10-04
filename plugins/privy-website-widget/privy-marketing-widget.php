<?php
/*
Plugin Name: Privy Website Widget
Plugin URI: http://blog.privy.com/blog/2015/5/how-to-install-the-privy-wordpress-plugin
Description: Simple website banners and exit intent popups to grow your email list.
Version: 2.0.11
Author: Privy Inc.
Author URI: http://privy.com/
License: MIT
*/

// Register WordPress hooks
add_action('admin_menu', 'privy_create_settings_page');
add_action('admin_init', 'register_privy_settings' );
add_action('admin_notices', 'display_privy_notice');
add_action('wp_footer', 'privy_widget');

function privy_create_settings_page() {
	add_options_page('Privy', 'Privy', 'manage_options', 'privy_settings_page', 'privy_settings_page');
}

function register_privy_settings() {
	register_setting( 'privy-settings-group', 'account_identifier', 'indentifier_save_callback' ); }

function privy_settings_page() {
  if (!current_user_can('manage_options'))
  {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  include(sprintf('%s/templates/settings.php', dirname(__FILE__)));
}

function indentifier_save_callback($input){
  $validated_input = privy_settings_validate($input);
  notify_privy_installation($validated_input);

  return $validated_input;
}

function privy_settings_validate($input) {
	$newinput = $input;
	if(!preg_match('/^[a-z0-9]{24}$/i', $newinput)) {
		$newinput = '';
	}
	return $newinput;
}

function notify_privy_installation($identifier){
  if(empty($identifier)){
    return;
  }

  $url = "https://api.privy.com/businesses/{$identifier}/campaigns.jsonp?s=j";
  $args = array(
    'timeout' => 120
  );

  $response = wp_remote_get($url, $args);
}

function display_privy_notice(){
  if(!get_option('account_identifier')){
    $class = 'notice notice-warning is-dismissible';
    $settings_url = admin_url('options-general.php?page=privy_settings_page');
    $message = "Please enter an account identifier in
                <a href=\"{$settings_url}\" title=\"Privy Settings\">Privy Settings</a>
                to enable e-mail collection.";

    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message);
  }
}

// inject script into footer of site
function privy_widget() {
	wp_enqueue_script('privy-marketing-widget', 'https://widget.privy.com/assets/widget.js');

	// build settings parameters
	$params = array('business_id' => get_option('account_identifier'));
	// add current user info, if available, for more accurate campaigns
	if (is_user_logged_in()) {
		$current_user = wp_get_current_user();
		$params['user'] = array('email' => $current_user->user_email);
	}

  // detect WooCommerce plugin
	if (class_exists('WooCommerce')){
		try {
			$cart = WooCommerce::instance()->cart;
			$cart->calculate_totals();
			$params['WooCommerce'] = array('cart_total' => $cart->total * 100);
		} catch (Exception $e) {
			// No action on WooCommerce exception
		}
	}

	wp_localize_script('privy-marketing-widget', 'privySettings', $params);
}

// add settings link from plugins page
function privy_plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=privy_settings_page">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'privy_plugin_settings_link' );

?>
