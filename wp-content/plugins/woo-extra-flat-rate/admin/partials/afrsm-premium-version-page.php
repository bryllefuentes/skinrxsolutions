<?php
// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">

            <div class="afrsm-premium-features">
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Upgrade to Flat Rate Shipping Pro', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><strong><?php esc_html_e( 'Full-featured and Highly Flexible Shipping plugin for creating different shipping methods with different shipping rules and maximize earnings from the shipping methods on offer.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong></p>
                            <ul>
                                <li><?php esc_html_e( 'Advanced flexibility for configuring shipping costs', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Create multiple flat rate shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Offers diverse shipping options to customers', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Optimize Shipping – Maximize Revenue', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li> 
                                <li><?php esc_html_e( 'Deploy shipping in a more strategic manner', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Add specific Product, Category, User role, Country, and more', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Optimize shipping by molding shipping rates', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Most flexible Table Rate Shipping plugin for WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Create shipping zones for easy order management', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Create fixed shipping methods for geographical locations', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                            </ul>
                            <div class="dotstore-pro-button">
                                <a class="button" target="_blank" href="<?php echo esc_url('https://bit.ly/3fTnQtI'); ?>"><?php esc_html_e( 'START 14-DAY FREE TRIAL »', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
                            </div>
                            <div class="dotstore-pro-reviews">
                                <span class="dotstore-pro-reviews__stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>  
                                </span>
                                <div class="dotstore-pro-reviews__rating">
                                    <span class="sui-reviews-rating"><?php esc_html_e( '4.9', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span><?php esc_html_e( ' / 5.0 rating from', 'advanced-flat-rate-shipping-for-woocommerce' ); ?> <span class="sui-reviews-customer-count"><?php esc_html_e( '64', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span> <?php esc_html_e( ' customers', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </div>
                                <a class="dotstore-pro-reviews__link" href="<?php echo esc_url('https://www.thedotstore.com/flat-rate-shipping-plugin-for-woocommerce/#tab-reviews'); ?>" target="_blank">
                                    <?php esc_html_e( 'thedotstore.com »', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </a>
                            </div>  
                        </div>
                        <div class="col-2">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/4gaX7IlG1Do" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <h1><?php esc_html_e( 'Premium Features', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h1>
                    <div class="landing-container pro-master-settings">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'When multiple shipping methods are visible on cart page', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <ul>
                                <li>
                                    <b><?php esc_html_e( 'Allow customer to choose:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Let\'s customer choose one shipping method from available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Apply Highest:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Shipping method with the highest cost would be displayed from the available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Apply smallest:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Shipping method with the lowest cost would be displayed from the available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Force all:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'All the shipping methods are forcefully invoked with shipping charge as summed up of all shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_13.png' ); ?>"
                                 alt="<?php esc_attr_e( 'When multiple shipping methods are visible on cart page', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_04.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method based on Tag', 'advanced-flat-rate-shipping-for-woocommerce'
                                 ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific tag\'s products.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you can create "Tag-based shipping" for $10. This method should be visible when the cart has any product having "Tag1" tag.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on SKU', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific SKU\'s products.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you can create "SKU based shipping" for $12. This method should be visible when the cart has any product having "woo-single1" SKU.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_05.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method based on SKU', 'advanced-flat-rate-shipping-for-woocommerce'
                                 ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_06.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method for specific users', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method for specific users', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific users.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you have created shipping method for "John" user with $18 charge. When John is logged in and place some order then for all the orders shipping method would be displayed.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature, shipping method based is visible for specific user role/group.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you have created shipping method for "Editor" role. Now, when any user with role "Editor" is logged in and place an order then this shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_07.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method based on User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_09.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method based on total cart quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on total cart quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This shipping method allows you to create shipping method based on total quantity of cart. There are multiple conditions (like =, !=, <, <=, >, >=) available for this parameter.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, if you have created shipping method like quantity >= 5. When total quantity of cart is greater than 5 then shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on total cart\'s weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This shipping method allows you to create shipping method based on total weight of cart. There are multiple conditions (like =, !=, <, <=, >, >=) available for this parameter.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, if you have created shipping method like weight != 5. When total weight of cart is not equal to 5 then shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_10.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Shipping method based on total cart\'s weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_11.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Additional shipping charges based on shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Additional shipping charges based on shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This option allows a user to add extra cost based on shipping classes. It provides all shipping classes which are already used for the product. It displays all shipping classes list with a text box to add cost.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'The shipping class cost will be added to the shipping charge. For example, if you set $49 as a shipping charge and "Poster class" shipping cost would be $10. Now when cart having a product that has poster class then total shipping charge would be $59(49 + 10).', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Addition to product-based shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'You can also set shipping methods based on product variations. If there are two variants of the same product A and B, you can set separate shipping charges for A and B.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_15.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Addition to product-based shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_16.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Cover All Handling Charges', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Cover All Handling Charges – Don’t Lose Money on Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'What if, irrespective of the cost of the product, the cart subtotal, the quantity of product, the product category, or shipping class, you want to set a minimum/maximum shipping amount for shipping methods? This advanced flat rate shipping for WooCommerce plugin helps you set minimum and/or maximum amount based on product cost, category cost, total cart quantity, product weight, category weight and total category weight.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                 <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Calculate Drill-Down Shipping Costs Easily and Quickly to Improve Business Profitability', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This advanced flat rate for WooCommerce plugin allows you to create complex table rate shipping rules for your products worldwide. You now have the option of creating range bound shipping costs. E.g. 10-20 products = $10. You have the benefit of creating multiple product range costs with just one flat rate shipping method. Given below are a list of advanced shipping price rules (you can set min or max quantity and a unique shipping cost)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p>
                                <b><?php esc_html_e( 'Advanced Shipping Price Rules:', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></b>
                            </p>
                            <ul>
                                <li><?php esc_html_e( 'Shipping Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Shipping Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Shipping Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Shipping Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Shipping Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Shipping Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_17.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Calculate Drill-Down Shipping Costs Easily and Quickly to Improve Business Profitability', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_18.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Deploy Minute Detailing in Your Shipping Costs', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Deploy Minute Detailing in Your Shipping Costs', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Adjust shipping charges in line with different products in shopping cart, product quantity or weight per product, with this advanced flat rate WooCommerce shipping plugin. Make the most of your shipping fees.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p>
                                <b><?php esc_html_e( 'E.g.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></b>
                            </p>
                            <ul>
                                <li><?php esc_html_e( 'If customer has 1 T-Shirt in cart = Shipping charge $5', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'If customer has 2 T-Shits in cart = Shipping charge $ 10 ($5 basic shipping fee + $5 for extra quantity)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'If customer has 3 T-Shirts in cart = Shipping charge $ 15 ($10 basic shipping fee + $5 for extra quantity)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                            <ul>    
                        </div>
                    </div>
                </div> 
                <div class="section section-odd clear">
                    <div class="landing-container">
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container afsrm_upgrade_to_pro">
                        <div class="afsrm_happy_member_to_pro">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Join 40,000 Happy Customer', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( '97% of customers are happy with theDotstore Plugins, and it’s a great time to join them: as a Flat rate shipping Plugin user you’ll get a free trial period, so you can see what all the fuss is about.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <div class="dotstore-pro-button">
                                <a class="button" target="_blank" href="<?php echo esc_url('https://bit.ly/3bAoOeA'); ?>"><?php esc_html_e( 'GET FLAT RATE SHIPPING PLUGIN PRO', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
                            </div>
                            <a class="free_trial_text" href="<?php echo esc_url('https://bit.ly/3bAoOeA'); ?>" target="_blank">
                                    <?php esc_html_e( 'Try Pro to Start 14-Day Free Trial.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>