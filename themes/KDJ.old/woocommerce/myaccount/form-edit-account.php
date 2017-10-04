<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.7
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php wc_print_notices(); ?>
<h3 class="head">change your details</h3>
<form action="" method="post" class="account-form">

    <?php do_action('woocommerce_edit_account_form_start'); ?>
    <div class="panel">
        <div class="account-field">
            <input type="text" class="input-text input-add" name="account_first_name" placeholder="<?php _e('First name', 'woocommerce'); ?>" id="account_first_name" value="<?php echo esc_attr($user->first_name); ?>" />
            <input type="text" class="input-text input-add" name="account_last_name" placeholder="<?php _e('Last name', 'woocommerce'); ?>" id="account_last_name" value="<?php echo esc_attr($user->last_name); ?>" />
            <input type="email" class="input-text input-add" name="account_email" placeholder="<?php _e('Email address', 'woocommerce'); ?>" id="account_email" value="<?php echo esc_attr($user->user_email); ?>" />

            <h3 class="head">change your password</h3>
            <input type="password" class="input-text input-add" placeholder="<?php _e('Current Password', 'woocommerce'); ?>" name="password_current" id="password_current" />
            <input type="password" class="input-text input-add" placeholder="<?php _e('New Password', 'woocommerce'); ?>" name="password_1" id="password_1" />
            <input type="password" class="input-text input-add" placeholder="<?php _e('Confirm New Password', 'woocommerce'); ?>" name="password_2" id="password_2" />
        </div>
    </div>

    <?php do_action('woocommerce_edit_account_form'); ?>


    <?php wp_nonce_field('save_account_details'); ?>
    <input type="submit" class="button" name="save_account_details" value="<?php _e('Save changes', 'woocommerce'); ?>" />
    <input type="hidden" name="action" value="save_account_details" />


    <?php do_action('woocommerce_edit_account_form_end'); ?>

</form>

