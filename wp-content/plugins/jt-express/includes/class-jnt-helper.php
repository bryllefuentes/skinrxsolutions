<?php
 
class Jnt_Helper {

	public $jnt_api = null;

	public function __construct() {
		$this->jnt_api = new Jnt_Api();
	}

	public function process_order($ids) {
		$merge = array();
		$setting = get_option('woocommerce_jnt_settings');
		foreach ($ids as $id) {
			$order = wc_get_order($id);

			$sender = array(
				'sender_name' => $setting['name'],
				'sender_phone'=> $setting['phone'],
				'sender_addr' => implode(" ", array(
									get_option('woocommerce_store_address'),
									get_option('woocommerce_store_address_2'),
									get_option('woocommerce_store_city'),
									get_option('woocommerce_store_postcode')
								)),
				'sender_zip'  => get_option('woocommerce_store_postcode'),
				'cuscode'	  => $setting['vipcode'],
				'password'	  => $setting['apikey'],
			);

			$shipping_phone = (!empty($order->get_shipping_phone())) ? $order->get_shipping_phone() : $order->get_billing_phone();

			if(strpos($shipping_phone, '/') !== false) {
				$receiverphone = explode("/", $shipping_phone);
				$receiverphone = $receiverphone[0];
			} else {
				$receiverphone = $shipping_phone;
			}

			$receiver = array(
				'receiver_name' => $order->get_formatted_shipping_full_name(),
				'receiver_phone'=> $receiverphone,
				'receiver_addr'	=> implode(" ",array(
									$order->shipping_address_1, 
									$order->shipping_address_2, 
									$order->shipping_city, 
									$order->shipping_postcode
								)),
				'receiver_zip' 	=> $order->get_shipping_postcode(),
			);

			$weight_unit = get_option('woocommerce_weight_unit');
			$kg = 1000;
			$weight = 0;
			$item_name = '';

			if ( sizeof( $order->get_items() ) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					if ( $item['product_id'] > 0 ) {
						$_product = $order->get_product_from_item( $item );
						if ( ! $_product->is_virtual() ) {
							if ( is_numeric($_product->get_weight()) && is_numeric($item['qty'])){
								$weight += ($_product->get_weight() * $item['qty']);
							}
							$item_name .= $item['qty'].' X '.$item['name'].', ';
						}
					}
				}
			}

			if ($weight == '0'){
				$weight = 0.1;
			} else {
				if ($weight_unit == 'kg') {
					$weight = $weight;
				} else if ($weight_unit == 'g') {
					$weight = $weight / $kg;
					if ( $weight <= 0.01 ) {
						$weight = 0.01;
					}
				}
			}

			$items = array(
				'id'	=> $id,
				'orderid'	=> date('ymdHi').str_pad($id, 6, 0, STR_PAD_LEFT),
				'weight' => $weight,
				'item' 	 => substr($item_name, 0, -2),
				'qty'	 => $order->get_item_count(),
				'payType'=> 'PP_PM',
				'goodsType'	=> 'PARCEL',
				'servicetype' => $setting['service'],
				'expresstype' => 'EZ',
				'goodsdesc' => $order->customer_message
			);

			array_push($merge, array_merge($sender, $receiver, $items));
		}

		return $this->jnt_api->order($merge);
	}

	public function process_fresh_order($ids) {
		$merge = array();
		$setting = get_option('woocommerce_jnt_settings');

		$sender_postcode = get_option('woocommerce_store_postcode');
		$sender_area = $this->jnt_api->postcode($sender_postcode);

		$sender = array(
			'sender_name' => $setting['name'],
			'sender_phone' => $setting['phone'],
			'sender_addr' => implode(" ", array(
								get_option('woocommerce_store_address'),
								get_option('woocommerce_store_address_2'),
								get_option('woocommerce_store_city'),
								$sender_postcode
							)),
			'sender_zip' => $sender_postcode,
			'cuscode' => $setting['vipcode'],
			'password' => $setting['apikey'],
			'sender_country' => 'MYS',
			'sender_prov' => $sender_area['state'] ?? '',
			'sender_city' => $sender_area['county'] ?? '',
			'sender_area' => $sender_area['districtName'] ?? '',
		);

		foreach ($ids as $id) {
			$order = wc_get_order($id);

			$shipping_phone = (!empty($order->get_shipping_phone())) ? $order->get_shipping_phone() : $order->get_billing_phone();

			if (strpos($shipping_phone, '/') !== false) {
				$receiverphone = explode("/", $shipping_phone);
				$receiverphone = $receiverphone[0];
			} else {
				$receiverphone = $shipping_phone;
			}

			$receiver_area = $this->jnt_api->postcode($order->shipping_postcode);

			$receiver = array(
				'receiver_name' => $order->get_formatted_shipping_full_name(),
				'receiver_phone' => $receiverphone,
				'receiver_addr' => implode(" ", array(
									$order->shipping_address_1,
									$order->shipping_address_2,
									$order->shipping_city,
									$order->shipping_postcode
								)),
				'receiver_zip' => $order->get_shipping_postcode(),
				'receiver_country' => 'MYS',
				'receiver_prov' => $receiver_area['state'] ?? '',
				'receiver_city' => $receiver_area['county'] ?? '',
				'receiver_area' => $receiver_area['districtName'] ?? '',
			);

			$weight_unit = get_option('woocommerce_weight_unit');
			$kg = 1000;
			$weight = 0;
			$item_name = "";

			if ( sizeof( $order->get_items() ) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					if ( $item['product_id'] > 0 ) {
						$_product = $order->get_product_from_item( $item );
						if ( ! $_product->is_virtual() ) {
							if ( is_numeric($_product->get_weight()) && is_numeric($item['qty'])){
								$weight += ($_product->get_weight() * $item['qty']);
							}
							$item_name .= $item['qty'].' X '.$item['name'].', ';
						}
					}
				}
			}

			if ($weight == '0'){
				$weight = 0.1;
			} else {
				if ($weight_unit == 'kg') {
					$weight = $weight;
				} else if ($weight_unit == 'g') {
					$weight = $weight / $kg;
					if ( $weight <= 0.01 ) {
						$weight = 0.01;
					}
				}
			}

			$items = array(
				'id'	=> $id,
				'orderid' => date('ymdHi').str_pad($id, 6, 0, STR_PAD_LEFT),
				'weight'  => $weight,
				'item' 	  => substr($item_name, 0, -2),
				'item_value' => $order->get_total(),
				'qty'	  => $order->get_item_count(),
				'payType' => 'PP_PM',
				'goodsType' => 'PARCEL',
				'servicetype' => $setting['service'],
				'expresstype' => 'FD',
				'remark' => $order->customer_message
			);

			array_push($merge, array_merge($sender, $receiver, $items));
		}
		return $this->jnt_api->process_fresh_order($merge);
	}

	public function process_print_thermal_new ( $ids ) {
		$upOne = realpath(dirname(__FILE__) . '/..');
		include $upOne . '/admin/view/thermal-new.php';	
	}

	public function process_print_thermal ( $awbs ) {

		$setting = get_option('woocommerce_jnt_settings');
		$cuscode = $setting['vipcode'];
		$awbs = implode(",", $awbs);

		$this->jnt_api->print($cuscode, $awbs);
	}

	public function phone_format ($phone) {
		$length = strlen($phone);
		$visible = (int) round($length/4);
		$hide = $length - ($visible*2);
		return substr($phone, 0, $visible) . str_repeat('*', $hide) . substr($phone, ($visible * - 1), $visible);
	}

	public function process_print ( $awbs ) {
		
		$setting = get_option('woocommerce_jnt_settings');
		$cuscode = $setting['vipcode'];
		$awbs = implode(",", $awbs);

		$this->jnt_api->printA4($cuscode, $awbs);
	}

	public function tracking ( $awb ) {
		
		$awb = trim($awb);

		if ( substr( $awb, 0, 1 ) === "6" && preg_match( '/^[0-9]+$/', $awb ) && strlen( $awb ) == '12' ){

			return $this->jnt_api->tracking($awb);
		}else{

			return "Invalid Tracking Number";
		}
		
	}

	public function cancel_order ( $ids ) {

		$awbs = array();

		foreach ($ids as $key => $id) {

			$infos = array(
				'id'	=> $id,
				'awb'	=> get_post_meta($id, 'jtawb', true),
			);

			array_push( $awbs, $infos );
		}

		return $this->jnt_api->cancel($awbs);
	}

	public function shipping_rate ( $weight, $postcode ) {

		$receiver_zip = $postcode;
		$sender_zip = get_option('woocommerce_store_postcode');

		$shipping = get_option('woocommerce_jnt_settings');
		$cuscode = $shipping['vipcode'];
		$pass = $shipping['apikey'];
		
		return $this->jnt_api->calculate($weight, $sender_zip, $receiver_zip, $cuscode, $pass);

	}
}
