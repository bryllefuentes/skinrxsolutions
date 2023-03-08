<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Base Class For Common var & functions
*/
class Woo_Stock_Base {
		
	public function __construct() {
		//change stock status based on product
		add_filter( 'woocommerce_get_availability', array( $this,'woo_rename_stock_status' ) , 10 , 2);
		add_filter( 'woocommerce_get_bundled_item_availability', array( $this, 'woo_bundled_rename_stock_status' ), 5, 3 );
	}

	public function woo_bundled_rename_stock_status( $message_array, $this_obj, $product_obj ) {
		return $this->woo_rename_stock_status( $message_array ,  $product_obj );
	}

	/**
	 * Default stock status and its names (array format key as meta_key value as Label)
	 */
	public $status_array = array(
							'in_stock' 				=> 'In stock',
							'only_s_left_in_stock' 	=> 'Only %s left in stock',
							'can_be_backordered' 	=> '(can be backordered)',
							's_in_stock'			=> '%s in stock',
							'available_on_backorder'=> 'Available on backorder',
							'out_of_stock' 			=> 'Out of stock',
						);

	/**
	 * Default stock status colors
	 */
	public $status_color_array = array(
							'in_stock_color' 				=> array('default'=> '#77a464', 'label' => 'In stock color'),
							'only_s_left_in_stock_color' 	=> array('default'=> '#77a464', 'label' => 'Only %s left in stock color'),
							's_in_stock_color'				=> array('default'=> '#77a464', 'label' => '%s in stock color'),
							'available_on_backorder_color'	=> array('default'=> '#77a464', 'label' => 'Available on backorder color'),
							'can_be_backordered_color' 		=> array('default'=> '#77a464', 'label' => '(can be backordered) color'),
							'out_of_stock_color' 			=> array('default'=> '#ff0000', 'label' => 'Out of stock color'),
						);
	

	/**
	 * Default stock status font size
	 */
	public $status_font_size_array = array(
							'in_stock_font_size' 				=> array('default'=> '#77a464', 'label' => 'In stock font size'),
							'only_s_left_in_stock_font_size' 	=> array('default'=> '#77a464', 'label' => 'Only %s left in stock font size'),
							's_in_stock_font_size'				=> array('default'=> '#77a464', 'label' => '%s in stock font size'),
							'available_on_backorder_font_size'	=> array('default'=> '#77a464', 'label' => 'Available on backorder font size'),
							'can_be_backordered_font_size' 		=> array('default'=> '#77a464', 'label' => '(can be backordered) font size'),
							'out_of_stock_font_size' 			=> array('default'=> '#ff0000', 'label' => 'Out of stock font size'),
						);

	/*
	 * Rename the default stock list names based on Product
	 */
	public function woo_rename_stock_status( $message_array ,  $this_obj ) {
		
		$availability = $class = '';

		foreach($this->status_array as $status=>$label){
			$$status = $label;
		}
		/**
		 * Change Stock Status Based on Product wise. now, we used simple & variation product types only 
		 * if product doesn't have stock status we use global stock status (Settings->Custom Stock) tab
		 */
		foreach( $this->status_array as $status=>$label ){
			if( $this_obj->is_type( 'simple' ) || $this_obj->is_type( 'composite' ) || $this_obj->is_type('bundle') ) {
				$stock_status = get_post_meta( $this_obj->get_id() , $status , true );
				if( !empty( $stock_status ) ) {
					$$status = get_post_meta( $this_obj->get_id() , $status , true );
				} else {
					$$status = (get_option('wc_slr_'.$status,$$status)=='') ? $$status : get_option('wc_slr_'.$status,$$status);
				}
			} elseif ( $this_obj->is_type( 'variation' ) ) {
				$stock_status = get_post_meta( $this_obj->get_id() , '_'.$status.'_status' , true );
				if( !empty( $stock_status ) ) {
					$$status = get_post_meta( $this_obj->get_id() , '_'.$status.'_status' , true );
				} else {
					$$status = (get_option('wc_slr_'.$status,$$status)=='') ? $$status : get_option('wc_slr_'.$status,$$status);
				}	
			}
			else {
				$$status = (get_option('wc_slr_'.$status,$$status)=='') ? $$status : get_option('wc_slr_'.$status,$$status);
			}
		}
		
		if ( $this_obj->managing_stock() ) {

			if ( $this_obj->is_in_stock() && $this_obj->get_stock_quantity() > get_option( 'woocommerce_notify_no_stock_amount' ) ) {

				switch ( get_option( 'woocommerce_stock_format' ) ) {

					case 'no_amount' :
						$availability = __( $in_stock, 'woocommerce' );
						$extra_class  = 'in_stock_color';
					break;

					case 'low_amount' :
						if ( $this_obj->get_stock_quantity() <= wc_get_low_stock_amount( $this_obj ) ) {
							$availability = sprintf( __( $only_s_left_in_stock, 'woocommerce' ), $this_obj->get_stock_quantity() );

							$extra_class  = 'only_s_left_in_stock_color';

							if ( $this_obj->backorders_allowed() && $this_obj->backorders_require_notification() ) {
								$availability .= ' ' . __( $can_be_backordered, 'woocommerce' );
								$extra_class  .= ' can_be_backordered_color';
							}
						} else {
							$availability = __( $in_stock, 'woocommerce' );
							$extra_class  = 'in_stock_color';
						}
					break;

					default :
						$availability = sprintf( __( $s_in_stock, 'woocommerce' ), $this_obj->get_stock_quantity() );

						$extra_class  = 's_in_stock_color';

						if ( $this_obj->backorders_allowed() && $this_obj->backorders_require_notification() ) {
							$availability .= ' ' . __( $can_be_backordered, 'woocommerce' );
							$extra_class  .= ' can_be_backordered_color';
						}
					break;
				}

				$class        = 'in-stock ';

				$class		 .= $extra_class;

			} elseif ( $this_obj->backorders_allowed() && $this_obj->backorders_require_notification() ) {

				$availability = __( $available_on_backorder, 'woocommerce' );
				$class        = 'available-on-backorder available_on_backorder_color';

			} elseif ( $this_obj->backorders_allowed() ) {

				$availability = __( $in_stock, 'woocommerce' );
				$class        = 'in-stock in_stock_color';

			} else {

				$availability = __( $out_of_stock, 'woocommerce' );
				$class        = 'out-of-stock out_of_stock_color';
			}

		} else {
			
			$stock_status_no_inventory = $this_obj->get_stock_status();
			if($stock_status_no_inventory=='instock') {

				$availability = __( $in_stock, 'woocommerce' );
				$class        = 'in-stock in_stock_color';

			} elseif($stock_status_no_inventory=='outofstock') {

				$availability = __( $out_of_stock, 'woocommerce' );
				$class        = 'out-of-stock out_of_stock_color';
				
			} elseif($stock_status_no_inventory=='onbackorder') {

				$availability = __( $available_on_backorder, 'woocommerce' );
				$class        = 'available-on-backorder available_on_backorder_color';

			}

		}

		$message_array['availability'] = $availability;
		$message_array['class'] = $class.' woo-custom-stock-status';
		return $message_array;
	}
}

?>
