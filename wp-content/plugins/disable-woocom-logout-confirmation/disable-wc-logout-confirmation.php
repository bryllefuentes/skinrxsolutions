<?php
/**
 * Plugin Name: Disable woocommerce logout confirmation
 * Plugin URI: http://hemthapa.com?ref=woodisdowmenu
 * Description: This lightweight plugin disables the woocommerce logout confirmation!
 * Version: 1.2
 * Author: Hem Thapa
 * Author URI: https://hemthapa.com/
 * WC tested up to: 5.7.1
 */

function disable_wc_logout_confirmation(){

	global $wp;

    if(isset($wp->query_vars['customer-logout'])){
        wp_redirect(str_replace('&amp;','&',wp_logout_url( wc_get_page_permalink('myaccount'))));
        exit;
    }
}

add_action('template_redirect', 'disable_wc_logout_confirmation');

?>