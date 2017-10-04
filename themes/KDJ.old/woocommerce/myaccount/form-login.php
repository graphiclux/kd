<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php wc_print_notices(); ?>
<?php do_action('woocommerce_before_customer_login_form'); ?>

<div class="sign_in_page_box">
    <div class="container">
        <div class="sign_in_form_box">
            <form  action="<?php echo esc_url(home_url('/')); ?>wp-login.php?wpe-login=katiedeanjewel" method="post">
                <h2><?php _e('Sign In HERE', 'am'); ?></h2>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>"/>
                <input  placeholder="<?php _e('EMAIL', 'am'); ?>" type="text" class="input-text" name="log" id="username" value="<?php if (!empty($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
                <input  placeholder="<?php _e('PASSWORD', 'am'); ?>" class="input-text" type="password" name="pwd" id="password" />
                <input name="rememberme" type="hidden" id="rememberme" value="forever" />
		<input type="hidden" value="1" name="testcookie">
                <input type="submit" value="SIGN IN" class="the_button">
            </form>

            <div class="forgon_password"><?php _e('LOST YOUR PASSWORD?', 'am'); ?> <a href="<?php echo esc_url(wc_lostpassword_url()); ?>"><?php _e('RESET IT HERE', 'am'); ?></a></div>
        </div>
        <div class="sign_in_create_acc_box">
            <h2><?php _e("Don’t have an account?", 'am'); ?></h2>
            <a href="#" class="the_button si_create_account"><?php _e('CREATE AN ACCOUNT', 'am'); ?></a>
        </div>
    </div><!--/container-->
</div><!--/sign_in_page_box-->
<div class="create_acc_page_box" style="display:none; margin-top:-16px;">
    <div class="container">
        <div class="create_account_box">
            <form method="post" class="register">
                <?php do_action('woocommerce_register_form_start'); ?>
                <a href="#" class="new_here_link">NEW TO KATIE DEAN JEWELRY?</a>
                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <input type="text" class="input-text" name="username" id="reg_username" value="<?php if (!empty($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
                <?php endif; ?>
                <input type="text" class="input-text" name="email" id="reg_email" placeholder="EMAIL" value="<?php if (!empty($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                    <input type="password" class="input-text" name="password" id="reg_password" placeholder="PASSWORD" />
                <?php endif; ?>
                <!-- Spam Trap -->
                <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e('Anti-spam', 'woocommerce'); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>
                <?php do_action('woocommerce_register_form'); ?>
                <?php do_action('register_form'); ?>
                <?php wp_nonce_field('woocommerce-register'); ?>
                <input type="submit" class="the_button create_account" name="register" value="<?php _e('CREATE ACCOUNT', 'woocommerce'); ?>" />

                <div class="terms_of_use">By clicking "Create Account", you agree to our <a href="<?php echo get_permalink(13); ?>">Terms</a> and <a href="<?php echo get_permalink(14); ?>">Privacy Policy</a>.</div>

                <a href="#" class="have_an_account">Already have an account? Click here!</a>

                <?php do_action('woocommerce_register_form_end'); ?>
            </form>

        </div>

    </div><!--/container-->
</div><!--/sign_in_page_box-->

