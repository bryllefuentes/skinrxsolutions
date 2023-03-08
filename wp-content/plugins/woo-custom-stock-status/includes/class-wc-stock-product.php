<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* WC stock status for Products if product stock status are empty they get global stock status ( Setting tab Status )
*/

class Woo_Stock_Product extends Woo_Stock_Base {
	
	public function __construct() {
		
		// add stock status tab to product tab
		add_filter( 'woocommerce_product_data_tabs', array( $this , 'woo_add_simple_product_stock_status' ) );

		// display stock status fields for ( Simple,Grouped,External ) Products
		add_action( 'woocommerce_product_data_panels' , array( $this , 'woo_stock_status_fields' ) );

		// save stock fields value for ( Simple ) Product
		add_action( 'woocommerce_process_product_meta_simple' , array( $this , 'save_stock_status_message' ) );
		
		// save stock fields value for ( Bundle ) Product
		add_action( 'woocommerce_process_product_meta_bundle' , array( $this , 'save_stock_status_message' ) );

		// save stock fields value for ( Composite ) Product
		add_action( 'woocommerce_process_product_meta_composite' , array( $this , 'save_stock_status_message' ) );

		// add stock status message in content-product template page
		add_action( 'woocommerce_after_shop_loop_item_title' , array( $this , 'add_stack_status_in_summary' ) , 15 ); // after price woocommerce\templates\content-product.php line:60

		/**
		 * Hide save stock fields value for Grouped,External Products
		 */
		
		// add_action( 'woocommerce_process_product_meta_grouped' , array( $this , 'save_stock_status_message' ) );
		// add_action( 'woocommerce_process_product_meta_external' , array( $this , 'save_stock_status_message' ) );

		// variration stock status field
		add_action( 'woocommerce_variation_options_inventory' , array( $this , 'woo_variation_stock_status_field' ) , 10 , 3 ); 

		//save variation stock status
		add_action( 'woocommerce_save_product_variation' , array( $this , 'save_variation_stock_status' ) , 10 , 2 );

		//backorder woo custom stock status in order confirmation
		add_action('woocommerce_order_item_meta_start',array($this,'add_stock_status_in_order_confirmation'),10,3);

		add_filter( 'woocommerce_order_item_get_formatted_meta_data', array($this, 'format_backend_stock_status_label'), 10, 2 );

		//change cart item name
		add_filter( 'woocommerce_cart_item_name', array($this, 'cart_page_stock_status'), 10, 3);
		add_filter( 'woocommerce_checkout_cart_item_quantity', array($this, 'checkout_page_stock_status'), 10, 3);

		//add_order_item_meta
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'update_stock_status_to_order_item_meta' ), 15, 2 );

		add_shortcode( 'woo_custom_stock_status', array( $this, 'woo_custom_stock_status_func' ) );

		add_filter( 'woocommerce_cart_item_backorder_notification', array( $this, 'hide_cart_default_backorder_notification' ), 10);

		add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'woocommerce_blocks_product_grid_stock_status' ), 10, 3 );
	}

	//Function to hide the default "Available on backorder" message in /woocommerce/templates/cart/cart.php 
	public function hide_cart_default_backorder_notification($backorder_notification){
		return '';
	}

	//[woo_custom_stock_status product_id="1234"] short code call back function
	public function woo_custom_stock_status_func( $atts ) {
		global $product;
		
		$has_product_obj = false;
		$availability_html = '';

		if ( ( isset($atts['product_id'] ) ) && !empty( $atts['product_id'] ) ) {
			$product = 	new WC_Product( $atts['product_id'] );
			$has_product_obj = true;
		} else if( isset( $product ) ){
			$has_product_obj = true;
		}

		if( $has_product_obj == true ){
			$availability      = $product->get_availability();
			$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . ' woocss_shortcode">' . __(esc_html( $availability['availability'] ),'woo-custom-stock-status') . '</p>';
		}

		return $availability_html;
	}

	/**
	 * Stock Status add in order_item_meta table
	 * @param POST $posted
	 * 
	 */
	public function update_stock_status_to_order_item_meta( $item_id, $posted ) {
		$woo_custom_stock_status_email_txt = wc_get_order_item_meta($item_id, $posted['woo_custom_status']);
		if(empty($woo_custom_stock_status_email_txt)) {
			wc_add_order_item_meta( $item_id, '_woo_custom_stock_status_email_txt', $posted['woo_custom_status'] );
		}
		

	}

	public function cart_page_stock_status($item_name, $cart_item, $cart_item_key){
		if(is_checkout()){
			return $item_name;
		} else {
			return $this->cart_stock_status($item_name, $cart_item, $cart_item_key);
		}
	}

	public function checkout_page_stock_status($item_name, $cart_item, $cart_item_key){
		return $this->cart_stock_status($item_name, $cart_item, $cart_item_key);
	}


	/**
	 * Stock Status name add in cart page and checkout page
	 * @param POST values
	 * @return html
	 */

	public function cart_stock_status($item_name, $cart_item, $cart_item_key) {
		$show_status = get_option( 'wc_slr_show_in_shop_page' , 'yes' );
		$product_id  =  $cart_item['product_id'];
		$variation_id = $cart_item['variation_id'];
		global $woocommerce;
		
		$availability_html = '';
		if( $variation_id>0 ) {
		
			$variation 				= 	new WC_Product_Variation( $variation_id );
			$product_availabilty 	= 	$variation->get_availability();
			$woocommerce->cart->cart_contents[$cart_item_key]['woo_custom_status'] = $product_availabilty['availability'];
			$woocommerce->cart->set_session();
			
		} elseif( $variation_id==0 ) {
			$product 				= 	new WC_Product( $product_id );
			$product_availabilty 	= 	$product->get_availability();
			$woocommerce->cart->cart_contents[$cart_item_key]['woo_custom_status'] = $product_availabilty['availability'];
			$woocommerce->cart->set_session();
			
			
		}

		if( $show_status === 'yes' ) {
			$availability_html      =   empty( $product_availabilty['availability'] ) ? '' : '<p class="stock ' . esc_attr( $product_availabilty['class'] ) . '">' . __(esc_html( $product_availabilty['availability'] ),'woo-custom-stock-status') . '</p>';
		}
		return $item_name.' <br>'.$availability_html;
	}
	

	//Renames "_woo_custom_stock_status_email_txt" to "Stock Status" in admin order details page
	public function format_backend_stock_status_label($formatted_meta, $this_obj ){
		foreach($formatted_meta as $key => $value){
			if(isset($value->display_key) && !empty($value->display_key) && ($value->display_key=='_woo_custom_stock_status_email_txt')){
				$value->display_key = __( 'Stock Status', 'woo-custom-stock-status' );
			}
			$formatted_meta[$key] = $value;
		}
		return $formatted_meta;
	}

	public function woo_add_simple_product_stock_status( $tabs ) {
		$tabs['stockstatus'] = array(
										'label'  => __( 'Stock Status', 'woo-custom-stock-status' ),
										'target' => 'custom_stock_status_data',
										'class'  => array( 'show_if_simple' ), // depend upon product type to show & hide
									);

		return $tabs;
	}

	public function woo_stock_status_fields() {
		echo '<div id="custom_stock_status_data" class="panel woocommerce_options_panel" style="display: flex;">';
		echo '<div style="width: 75%;">';
		foreach ($this->status_array as $key => $value) {
			woocommerce_wp_text_input(
										array( 
												'id' => $key, 
												'label' => __( $value , 'woo-custom-stock-status' ),
												'placeholder' => $value,
												'style' => 'width: 100%;',
											)
									);
		}
		echo '</div>';
		if( ! is_plugin_active( 'woo-custom-stock-status-pro/woo-custom-stock-status-pro.php' ) ){
			echo '<div style="background: #e4efff;border: #bdd9fe solid 4px;width: 25%;font-size: 17px;padding: 23px;">';
			echo '<a href="https://softound.com/products/woo-custom-stock-status-pro/" target="_blank" style="font-weight: bold;">Get Woo Custom Stock Status Pro</a> to edit stock status using <strong>bulk edit option</strong> with <strong>WPML</strong> compatibility.';
			echo '</div>';
		}
		echo '</div>';
	}

	public function save_stock_status_message( $post_id ) {
		foreach ($this->status_array as $meta_key => $val) {
			if(isset( $_POST[$meta_key] ) && !empty( $_POST[$meta_key] ) ) {
				update_post_meta( $post_id , $meta_key , sanitize_text_field( $_POST[$meta_key] ) );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		}
	}

	public function woo_variation_stock_status_field( $loop, $variation_data, $variation ) {
		$right_side = array('in_stock','can_be_backordered','available_on_backorder');
		echo '<div style="clear:both"></div><p style="font-size:14px;"><b>'.__( 'Custom Stock Status' , 'woo-custom-stock-status' ).'</b></p>';

		if( ! is_plugin_active( 'woo-custom-stock-status-pro/woo-custom-stock-status-pro.php' ) ){
			echo '<div style="background: #e4efff;border: #bdd9fe solid 4px;font-size: 17px;padding: 23px;"><a href="https://softound.com/products/woo-custom-stock-status-pro/" target="_blank" style="font-weight: bold;">Get Woo Custom Stock Status Pro</a> to edit stock status using <strong>bulk edit option</strong> with <strong>WPML</strong> compatibility.</div>';
		}

		foreach ($this->status_array as $key => $name) { ?>
			<p class="form-row <?php echo in_array( $key,$right_side ) ? 'form-row-first' : 'form-row-last' ?>">
				<label><?php _e( $name , 'woo-custom-stock-status' ); ?></label>
				<input type="text" placeholder="<?php echo $name; ?>" name="variable_<?php echo $key; ?>_status[<?php echo $loop; ?>]" value="<?php echo get_post_meta( $variation->ID , '_'.$key.'_status' , true ); ?>" />
			</p>
		<?php
		}
	}

	public function save_variation_stock_status( $post_id , $variation_key ) {
		foreach ($this->status_array as $meta_key => $val) {
			if(isset( $_POST['variable_'.$meta_key.'_status'][$variation_key] ) && !empty( $_POST['variable_'.$meta_key.'_status'][$variation_key] ) ) {
				update_post_meta( $post_id , '_'.$meta_key.'_status' , sanitize_text_field( $_POST['variable_'.$meta_key.'_status'][$variation_key] ) );
			} else {
				delete_post_meta( $post_id, '_'.$meta_key.'_status' );
			}
		}
	}
	
	/**
	 * Show stock status in product listing page
	 */
	public function add_stack_status_in_summary(){
		$show_status = get_option( 'wc_slr_show_in_shop_page' , 'yes' );
		if( $show_status === 'yes' ) {
			global $product;
			$availability      = $product->get_availability();
			$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . __(esc_html( $availability['availability'] ),'woo-custom-stock-status') . '</p>';
			echo $availability_html;
		}
	}

	/**
	 *	Show the stock status in "New In", "Fan Favorites", "On Sale", and "Best Sellers" blocks 
	 */
	public function woocommerce_blocks_product_grid_stock_status($html, $data, $product){
		$show_status = get_option( 'wc_slr_show_in_wordpress_blocks' , 'no' );
		if($show_status === "no") {
			return $html;
		} 
		$availability      = $product->get_availability();
		$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . ' woocss_shortcode">' . __(esc_html( $availability['availability'] ),'woo-custom-stock-status') . '</p>';

		return "<li class=\"wc-block-grid__product\">
				<a href=\"{$data->permalink}\" class=\"wc-block-grid__product-link\">
					{$data->image}
					{$data->title}
				</a>
				{$data->badge}
				{$data->price}
				{$data->rating}
				{$availability_html}
				{$data->button}
			</li>";
	}

	/**
	* Woo custom stock status in order confirmation (for backorders) (Improved)
	*/
	public function add_stock_status_in_order_confirmation( $item_id , $item , $order  ) {	
		$variation_id 				= 	$item->get_variation_id();
		$product_id 				= 	$item->get_product_id();

		if($variation_id>0){
			$variation 				= 	new WC_Product_Variation( $variation_id );
			$product_availabilty 	= 	$variation->get_availability();
		} else {
			$product 				= 	new WC_Product( $product_id );
			$product_availabilty 	= 	$product->get_availability();
		}

		$order_items     		=	$order->get_items();
		$on_backorder 			= 	false;
		$order_id 				=	$order->get_id();
		$show_status_in_email	= 	get_option( 'wc_slr_show_in_order_email' , 'no' );
		foreach(  $order_items as $items_ ) {
			if(	$items_['Backordered']	) {
				$on_backorder = true;
			}
		}		
		if( ( ( $on_backorder === true ) || ( $show_status_in_email == 'yes' ) ) && ( $product_id > 0 ) ) {
			if( $on_backorder === true ){
				$woo_custom_stock_status = $product_availabilty['availability'];
				$custom_message		 =  serialize(array(
					'class'   => esc_html($product_availabilty['class']),
					'status'  => $woo_custom_stock_status
				));

				$backorder_message       = get_post_meta($order_id,'woo_custom_stock_status_backorder_status_'.$item_id,true);
				if( ($backorder_message == '') || ( is_null($backorder_message) ) || (empty($backorder_message)) ) {
					update_post_meta($order_id, 'woo_custom_stock_status_backorder_status_'.$item_id, $custom_message);
					wc_update_order_item_meta($item_id, '_woo_custom_stock_status_email_txt', $woo_custom_stock_status);
				}
				$custom_message       = unserialize(get_post_meta($order_id,'woo_custom_stock_status_backorder_status_'.$item_id,true));

				echo wp_kses_post( '<p class="stock '.esc_html( $custom_message['class'] ) .'">'.__($custom_message['status'],'woo-custom-stock-status').'</p>' );
			} else if( $show_status_in_email == 'yes' ){
				//Include stock status in order email
				if( isset( $product_availabilty['availability'] ) ) {
					$woo_custom_stock_status_email_txt = wc_get_order_item_meta($item_id, '_woo_custom_stock_status_email_txt');
					if(empty($woo_custom_stock_status_email_txt)){
						wc_update_order_item_meta($item_id, '_woo_custom_stock_status_email_txt', $product_availabilty['availability']);
						$woo_custom_stock_status_email_txt = $product_availabilty['availability'];
					}
					echo "<br /><strong>".__( 'Stock Status', 'woo-custom-stock-status' ).':</strong> '.$woo_custom_stock_status_email_txt;
				}
			}
		}
	}


}
