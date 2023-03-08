<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $afrsfw_fs ;
$plugin_name = AFRSM_PRO_PLUGIN_NAME;
$version_label = 'Free Version';
$afrsm_admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
?>
<div id="dotsstoremain" class="afrsm-section">
    <div class="all-pad">
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <div class="logo-image">
                            <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advance-flat-rate.png' ) ;
?>">
                        </div>
                        <div class="plugin-version">
                            <span><?php 
esc_html_e( $version_label, 'advanced-flat-rate-shipping-for-woocommerce' );
?> <?php 
echo  esc_html( AFRSM_PRO_PLUGIN_VERSION ) ;
?></span>
                        </div>
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'advanced-flat-rate-shipping-for-woocommerce' );
?></div>
                        <div class="desc"><?php 
esc_html_e( 'Full-featured and Highly Flexible Shipping plugin for creating different shipping methods with different shipping rules.', 'advanced-flat-rate-shipping-for-woocommerce' );
?></div>
                    </div>
                </div>
                <div class="dots-header-right">
                    

                    <div class="button-group">
                        <div class="button-dots">
                            <span class="support_dotstore_image">
                                <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                                    <span class="dashicons dashicons-sos"></span>
                                    <strong><?php 
esc_html_e( 'Quick Support', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                                </a>
                            </span>
                        </div>

                        <div class="button-dots">
                            <span class="support_dotstore_image">
                                <a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce' ) ;
?>">
                                    <span class="dashicons dashicons-media-text"></span>
                                    <strong><?php 
esc_html_e( 'Documentation', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                                </a>
                            </span>
                        </div>

                        <?php 
?>
                            <div class="button-dots">
                                <span class="support_dotstore_image">
                                    <a target="_blank" href="<?php 
echo  esc_url( $afrsfw_fs->get_upgrade_url() ) ;
?>">
                                        <span class="dashicons dashicons-upload"></span>
                                        <strong><?php 
esc_html_e( 'Upgrade To Pro', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                                    </a>
                                </span>
                            </div>
                        <?php 
?>
                    </div>
                </div>
            </div>
			
			<?php 
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$afrsm_admin_object->afrsm_pro_menus( $current_page );
?>
        </header>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">