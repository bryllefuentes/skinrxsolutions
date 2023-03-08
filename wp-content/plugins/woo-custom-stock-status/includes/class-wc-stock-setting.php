<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* WC Stock Status Setting Tab functions
*/

class Woo_Stock_Setting extends Woo_Stock_Base {
	
	public function __construct() {
		
		// add stock status tab in woocommerce setting page
		add_filter( 'woocommerce_settings_tabs_array', array( $this , 'add_settings_tab' ) , 50 );

		// stock status fields
		add_action( 'woocommerce_settings_tabs_wc_stock_list_rename',array( $this ,'settings_tab' ));

		// save stock status fields value
		add_action( 'woocommerce_update_options_wc_stock_list_rename',array( $this ,'update_settings' ));

		// stock status color css
		add_action( 'wp_head',array( $this,'woo_custom_stock_status_color' ) );
	}
	
	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs['wc_stock_list_rename'] = __( 'Custom Stock', 'woo-custom-stock-status' );
		return $settings_tabs;
	}
	
	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses $this->get_settings()
	 */
	public function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}
	
	
	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses $this->get_settings()
	 */
	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}
	
	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public function get_settings() {
		
		$settings['section_title'] = array(
				'name'     => __( 'Custom Stock Status', 'woo-custom-stock-status' ),
				'type'     => 'title',
				'desc'     => '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;"><a href="https://softound.com/products/woo-custom-stock-status-pro/" target="_blank" style="font-weight: bold;">Get Woo Custom Stock Status Pro</a> to edit stock status using <strong>bulk edit option</strong> with <strong>WPML</strong> compatibility.</div>',
				'id'       => 'wc_wc_stock_list_rename_section_title'
			);

		if( is_plugin_active( 'woo-custom-stock-status-pro/woo-custom-stock-status-pro.php' ) ){
			$settings['section_title']['desc'] = '';
		}
		
		foreach($this->status_array as $status=>$label){
			$settings[$status] =  array(
				'name' => __( $label, 'woo-custom-stock-status' ),
				'type' => 'text',
				'desc'     => '',
				'id'   => 'wc_slr_'.$status,
				'class' => 'large-text'
			);
		}

		foreach($this->status_color_array as $status => $options ){
			$settings[$status] =  array(
				'name' 		=> __( $options['label'], 'woo-custom-stock-status' ),
				'desc'     	=> '',
				'id'   		=> 'wc_slr_'.$status,
				'type'     	=> 'color',
				'css'      	=> 'width:6em;',
				'default'  	=> $options['default'],
				'autoload' 	=> false,
				'desc_tip' 	=> true
			);
		}

		foreach($this->status_font_size_array as $status => $options ){
			$settings[$status] =  array(
				'name' 		=> __( $options['label'], 'woo-custom-stock-status' ),
				'desc'     	=> '',
				'suffix'	=> ' px',
				'id'   		=> 'wc_slr_'.$status,
				'type'     	=> 'number',
				'css'      	=> 'width:6em;',
				'default'  	=> $options['default'],
				'autoload' 	=> false,
				'desc_tip' 	=> true
			);
		}


		/**
		 * Option to move the stock status below add to cart button
		 */
		$settings['stock_status_after_addtocart'] = array( 
												'name'		=>	__( 'Move stock status below add to cart button in single product page' , 'woo-custom-stock-status' ),
												'id'		=>	'wc_slr_stock_status_after_addtocart',
												'type'		=>	'checkbox',
												'default'	=>	'no',
												'desc_tip'	=> false,
												'autoload'	=> false
											);


		/**
		 * Option for show/hide sad face for out of stock status
		 */
		$settings['hide_sad_face'] = array( 
												'name'		=>	__( 'Hide sad face in out of stock' , 'woo-custom-stock-status' ),
												'id'		=>	'wc_slr_hide_sad_face',
												'type'		=>	'checkbox',
												'default'	=>	'no',
												'desc_tip'	=> false,
												'autoload'	=> false
											);
		

		/**
		 * Option for show/hide stock status in shop page
		 * @since 1.1.1
		 */
		$settings['show_in_shop_page'] = array( 
												'name'		=>	__( 'Show Stock Status in Shop Page' , 'woo-custom-stock-status' ),
												'id'		=>	'wc_slr_show_in_shop_page',
												'type'		=>	'checkbox',
												'default'	=>	'yes',
												'desc_tip'	=> false,
												'autoload'	=> false
											);



		/**
		 * Option for show/hide stock status in order email
		 * @since 1.2.5
		 */
		$settings['show_in_order_email'] = array( 
												'name'		=>	__( 'Show Stock Status in Order Email' , 'woo-custom-stock-status' ),
												'id'		=>	'wc_slr_show_in_order_email',
												'type'		=>	'checkbox',
												'default'	=>	'no',
												'desc_tip'	=> '<i>'.__( 'Note: The custom stock status message in email may be differ based on availability of product at the time of order, it is not always same as customers see in shop page ' , 'woo-custom-stock-status' ).'</i>',
												'autoload'	=> false
											);
		
		


		/**
		 * Option for show/hide stock status in wordpress blocks
		 * @since 1.3.2
		 */
		$settings['show_in_wordpress_blocks'] = array( 
			'name'		=>	__( 'Show Stock Status in Wordpress Blocks' , 'woo-custom-stock-status' ),
			'id'		=>	'wc_slr_show_in_wordpress_blocks',
			'type'		=>	'checkbox',
			'default'	=>	'no',
			'desc_tip'	=> '<i>'.__( 'Note: Show the stock status in "New In", "Fan Favorites", "On Sale", and "Best Sellers" blocks ' , 'woo-custom-stock-status' ).'</i>',
			'autoload'	=> false
		);

		$settings['section_end'] = array(
			'type' => 'sectionend',
			'id' => 'wc_wc_stock_list_rename_section_end'
	   );


		return apply_filters( 'wc_wc_stock_list_rename_settings', $settings );
	}

	/**
	 * load custom stock color css in head
	 */
	public function woo_custom_stock_status_color() {
		$css = '<style>';
		foreach ($this->status_array as $key => $label) {
			$color_options_default = $this->status_color_array[$key.'_color']['default'];
			$status_color = $key.'_color';
			$status_color_code = (get_option('wc_slr_'.$status_color,$color_options_default)=='') ? $color_options_default : get_option('wc_slr_'.$status_color,$color_options_default);


			$font_size_options_default = $this->status_font_size_array[$key.'_font_size']['default'];
			$status_font_size = $key.'_font_size';
			$status_font_size_code = (get_option('wc_slr_'.$status_font_size,$font_size_options_default)=='') ? $font_size_options_default : get_option('wc_slr_'.$status_font_size,$font_size_options_default);
			if(!empty($status_font_size_code)){
				$status_font_size_code = 'font-size: '.$status_font_size_code.'px;';
			}

			$css .= sprintf('.woo-custom-stock-status.%s { color: %s; %s }',$status_color,$status_color_code, $status_font_size_code);//For details page
			$css .= sprintf('ul.products .%s { color: %s; %s }',$status_color,$status_color_code, $status_font_size_code);//For listing page
			$css .= sprintf('.woocommerce-table__product-name .%s { color: %s; %s }',$status_color,$status_color_code, $status_font_size_code);
		}

		$wc_slr_hide_sad_face = get_option( 'wc_slr_hide_sad_face', 'no' );
		if($wc_slr_hide_sad_face=='yes'){
			$css .= '.woo-custom-stock-status.stock.out-of-stock::before { display: none; }';
		}
		

		$css .= '</style><!-- woo-custom-stock-status-color-css -->';
		echo $css;

		$js = '<script>';
		$wc_slr_stock_status_after_addtocart = get_option( 'wc_slr_stock_status_after_addtocart', 'no' );
		if($wc_slr_stock_status_after_addtocart=='yes'){
			$js .= "jQuery(function(){ var stock_html = jQuery('.product .summary .stock').clone();jQuery('.product .summary .stock').remove();jQuery(stock_html).insertAfter('form.cart'); });";
		}
		$js .= '</script><!-- woo-custom-stock-status-js -->';
		echo $js;
		
	}
}
