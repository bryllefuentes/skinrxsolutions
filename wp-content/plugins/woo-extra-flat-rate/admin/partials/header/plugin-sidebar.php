<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$review_url = '';
$plugin_at = '';
$changelog_url = '';
$tweet_url = 'https://twitter.com/share?text=' . rawurlencode( "I use the Flat rate shipping plugin for #WooCommerce by @dotstore Highly #Flexibleshipping plugin for creating different shipping methods with different shipping rules and maximize earnings from the #shipping methods on offer. Checkout this #plugin" ) . '&amp;hashtags=wordpress,woo &amp;url=https://wordpress.org/plugins/woo-extra-flat-rate';
$review_url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#reviews' );
$plugin_at = 'WP.org';
$changelog_url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#developers' );
?>
</div>
    <div class="dots-settings-right-side">
        <div class="dots-seperator">
            <button class="toggleSidebar" title="toogle sidebar">
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </button>
        </div>

        <div class="dotstore_plugin_sidebar">
            
            <?php 
?>
                    <div class="dotstore-sidebar-section dotstore-upgrade-to-pro">
                        <div class="dotstore-important-link-heading">
                            <span class="heading-text"><?php 
esc_html_e( 'Upgrade to Flat Rate Shipping Pro', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                        </div>
                        <div class="dotstore-important-link-content">
                            <ul class="dotstore-pro-list">
                                <li><?php 
esc_html_e( 'Unlimited shipping methods and costs calculation rules', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Custom flat rate shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Set Fixed, Percentage & Dynamic Parameter based shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Weight Based Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Mini & Maxi values for cart total and/or weight and more', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Conditional Payments based on Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                                <li><?php 
esc_html_e( 'Additional costs for the price, weight, item, cart line item', 'advanced-flat-rate-shipping-for-woocommerce' );
?></li>
                            </ul>
                            <div class="dotstore-pro-button">
                                <a class="button" target="_blank" href="<?php 
echo  esc_url( 'https://bit.ly/3fTnQtI' ) ;
?>"><?php 
esc_html_e( 'Get Flat Rate Shipping Premium »', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                            </div>
                        </div>
                    </div>
                    <div class="dotstore_discount_voucher">
                        <span class="dotstore_discount_title"><?php 
esc_html_e( 'EXCLUSIVE LIFETIME OFFER', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                        <span class="dotstore-upgrade"><?php 
esc_html_e( 'Upgrade To Lifetime Pro Plan & Get', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                        <strong class="dotstore-OFF"><?php 
esc_html_e( '20% OFF', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                        <span class="dotstore-with-code"><?php 
esc_html_e( 'User Coupon Code:', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                    <b><?php 
esc_html_e( 'LIFETIMEPRO', 'advanced-flat-rate-shipping-for-woocommerce' );
?></b></span>
                        <a class="dotstore-upgrade"
                        href="<?php 
echo  esc_url( 'https://bit.ly/3fTnQtI' ) ;
?>"
                        target="_blank"><?php 
esc_html_e( 'Upgrade To Lifetime Pro Plan', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                    </div>
                    <?php 
?>

            <div class="dotstore-sidebar-section">
                <div class="content_box">
                    <h3><?php 
esc_html_e( 'Like This Plugin?', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h3>
                    <div class="et-star-rating">
                        <input type="radio" id="5-stars" name="rating" value="5" />
                        <label for="5-stars" class="star"></label>
                        <input type="radio" id="4-stars" name="rating" value="4" />
                        <label for="4-stars" class="star"></label>
                        <input type="radio" id="3-stars" name="rating" value="3" />
                        <label for="3-stars" class="star"></label>
                        <input type="radio" id="2-stars" name="rating" value="2" />
                        <label for="2-stars" class="star"></label>
                        <input type="radio" id="1-star" name="rating" value="1" />
                        <label for="1-star" class="star"></label>
                        <input type="hidden" id="et-review-url" value="<?php 
echo  esc_url( $review_url ) ;
?>">
                    </div>
                    <p><?php 
esc_html_e( 'Your Review is very important to us as it helps us to grow more.', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                </div>
            </div>

            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-image-rotate-right"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Free vs Pro Feature', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'Here’s an at a glance view of the main differences between Premium and free plugin features.', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/flat-rate-shipping-plugin-for-woocommerce' ) ;
?>"><?php 
esc_html_e( 'Click here »', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </div>
            </div>
            
            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-star-filled"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Suggest A Feature', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'Let us know how we can improve the plugin experience.', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                    <p><?php 
esc_html_e( 'Do you have any feedback & feature requests?', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/suggest-a-feature' ) ;
?>"><?php 
esc_html_e( 'Submit Request »', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </div>
            </div>

            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-editor-kitchensink"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Changelog', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'We improvise our products on a regular basis to deliver the best results to customer satisfaction.', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( $changelog_url ) ;
?>"><?php 
esc_html_e( 'Visit Here »', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </div>
            </div>

            

            <!-- html for popular plugin !-->
            <div class="dotstore-important-link dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-plugins-checked"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Our Popular Plugins', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                </div>
                <div class="video-detail important-link">
                    <ul>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Conditional-Product-Fees-For-WooCommerce-Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Product Fees For WooCommerce Checkout', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-extra-fees-plugin/" ) ;
?>">
                                <?php 
esc_html_e( 'Extra Fees Plugin for WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/plugn-login-128.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Hide Shipping Method For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/hide-shipping-method-for-woocommerce/" ) ;
?>">
                                <?php 
esc_html_e( 'Hide Shipping Method For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/WooCommerce Conditional Discount Rules For Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Discount Rules For WooCommerce Checkout', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-conditional-discount-rules-for-checkout/" ) ;
?>">
                                <?php 
esc_html_e( 'Conditional Discount Rules For WooCommerce Checkout', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/WooCommerce-Blocker-Prevent-Fake-Orders.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Blocker – Prevent Fake Orders', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-anti-fraud" ) ;
?>">
                                <?php 
esc_html_e( 'WooCommerce Anti-Fraud', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Advanced-Product-Size-Charts-for-WooCommerce.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Product Size Charts Plugin For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-advanced-product-size-charts/" ) ;
?>">
                                <?php 
esc_html_e( 'Product Size Charts Plugin For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/wcbm-logo.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Category Banner Management', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-category-banner-management/" ) ;
?>">
                                <?php 
esc_html_e( 'WooCommerce Category Banner Management', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/woo-product-att-logo.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Product Attachment For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-product-attachment/" ) ;
?>">
                                <?php 
esc_html_e( 'Product Attachment For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </a>
                        </li>
                        </br>
                    </ul>
                </div>
                <div class="view-button">
                    <a class="button button-primary button-large" target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/plugins' ) ;
?>"><?php 
esc_html_e( 'VIEW ALL', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </div>
            </div>

            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-sos"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Five Star Support', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'Got a question? Get in touch with theDotstore developers. We are happy to help! ', 'advanced-flat-rate-shipping-for-woocommerce' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Submit a Ticket »', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>