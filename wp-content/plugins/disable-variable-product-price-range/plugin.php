<?php
/*
Plugin Name: Disable Variable Product Price Range Woocommerce
Description: This usually looks like $100-$999. With this snippet you will be able to hide the highest price, plus add a “From: ” in front of the minimum price.
Version: 1.8
WC tested up to: 6.7.0
Author: Geek Code Lab
Author URI: https://geekcodelab.com/
*/
if (!defined('ABSPATH')) exit;

define("DVPPR_BUILD",1.8);

if(!defined("DVPPR_PLUGIN_DIR_PATH"))
    define("DVPPR_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
if(!defined("DVPPR_PLUGIN_URL"))
    define("DVPPR_PLUGIN_URL", plugins_url().'/'.basename(dirname(__FILE__)));

if(!class_exists('dvppr_disable_price_range')) {
    $wdvppr_options = get_option('wdvppr_options');
    $label_value = (isset($wdvppr_options['wdvppr_add_label'])) ? $wdvppr_options['wdvppr_add_label'] : 'From';
    class dvppr_disable_price_range
    {
        public function __construct() {
            $plugin = plugin_basename(__FILE__);
            add_action( 'admin_print_styles', array($this,'enqueue_styles_disable_price_range'));
            add_filter( "plugin_action_links_$plugin", array($this,'wdvppr_add_plugin_settings_link'));
            add_filter( 'woocommerce_variable_sale_price_html', array($this,'bbloomer_variation_price_format'), 10, 2 );
            add_filter( 'woocommerce_variable_price_html', array($this,'bbloomer_variation_price_format'), 10, 2 );
            add_action('admin_menu', array($this,'plugin_menu_page'));
            add_action( 'admin_init', array($this,'register_settings_callback'));
        }

        public function enqueue_styles_disable_price_range() {
            if( is_admin() ) {
                $css= DVPPR_PLUGIN_URL . "/assets/css/style.css";
                wp_enqueue_style( 'dvppr-admin', $css, array(), DVPPR_BUILD );
            }
        }

        public function wdvppr_add_plugin_settings_link( $links ) {
            $support_link = '<a href="https://geekcodelab.com/contact/" target="_blank" >' . __( 'Support') . '</a>';
            array_unshift( $links, $support_link );
        
            $settings_link = '<a href="'. admin_url() .'admin.php?page=wdvppr-disable-price-range">' . __( 'Settings') . '</a>';
            array_unshift( $links, $settings_link );
        
            return $links;
        }

        public function bbloomer_variation_price_format( $price, $product ) {
            global $wdvppr_options, $label_value;
            $sign = (!empty($label_value)) ? ':' : '';
            // Main Price
            $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
            $price = $prices[0] !== $prices[1] ? sprintf( __( $label_value.$sign.' %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
 
            // Sale Price
            $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
            sort( $prices );
            $saleprice = $prices[0] !== $prices[1] ? sprintf( __( $label_value.$sign.' %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
 
            if ( $price !== $saleprice ) {
                $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
            }
            return $price;
        }

        public function plugin_menu_page() {
            add_submenu_page(
                'woocommerce',
                'Disable price range',
                'Disable price range',
                'manage_options',
                'wdvppr-disable-price-range',
                array($this,'admin_menu_disable_price_range')
            );
        }

        public function register_settings_callback() {
            register_setting('wdvppr-all-settings','wdvppr_options',array($this,'sanitize_callback'));
        }

        public function sanitize_callback($input) {
            $new_input = array();

            if( isset( $input['wdvppr_add_label'] ) )
                $new_input['wdvppr_add_label'] = sanitize_text_field($input['wdvppr_add_label']);

            return $new_input;
        }

        public function admin_menu_disable_price_range() {
            global $wdvppr_options, $label_value;            
            ?>
                <div class="wrap">
                    <h2><?php _e('Disable Variable Product Price Range Woocommerce'); ?></h2>
                    
                    <form method="post" action="options.php">
                        <?php settings_fields( 'wdvppr-all-settings' ); ?>

                        <div class="wdvppr-price-range">
                            <div class="wdvppr-title">
                                <strong><?php _e('Add Label'); ?></strong> 
                            </div>
                            <div class="wdvppr-label">
                                <input type="text" name="wdvppr_options[wdvppr_add_label]" value="<?php _e($label_value); ?>">
                                <p><i>you can replce text <strong>"From"</strong> on Product Page. Default: <strong>From</strong></i></p>
                            </div>
                        </div>
                        <div class="wdvppr-submit-btn">
                            <?php submit_button( 'Save Settings' ); ?>
                        </div>
                    </form>                    
                </div>
            <?php
        }
    }
    new dvppr_disable_price_range();
}

