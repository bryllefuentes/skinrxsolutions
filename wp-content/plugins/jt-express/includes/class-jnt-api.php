<?php
 
class Jnt_Api {

	public function order($shipment_info){

		$sign = 'AKe62df84bJ3d8e4b1hea2R45j11klsb';
		$res = array();

		foreach ($shipment_info as $value) {
			$data = [
				'detail' => array(
					array(
						'username'	=> 'WORDPRESS',
						'api_key'	=> 'WORD12',
						'orderid'	=> $value['orderid'],
						'shipper_name' => $value['sender_name'],
						'shipper_addr' => $value['sender_addr'],
						'shipper_contact' => $value['sender_name'],
						'shipper_phone'	=> $value['sender_phone'],
						'sender_zip' => $value['sender_zip'],
						'receiver_name'	=> $value['receiver_name'],
						'receiver_addr'	=> $value['receiver_addr'],
						'receiver_phone' => $value['receiver_phone'],
						'receiver_zip' => $value['receiver_zip'],
						'qty' => $value['qty'],
						'weight' => $value['weight'],
						'item_name'	=> mb_substr($value['item'], 0, 200, 'UTF-8'),
						'payType'	=> $value['payType'],
						'goodsType'	=> $value['goodsType'],
						'cuscode' => $value['cuscode'], 
						'password' => $value['password'],
						'servicetype' => $value['servicetype'],
						'expresstype' => $value['expresstype'],
						'goodsdesc' => $value['goodsdesc']
					) 
				)
			];

			$json_data = json_encode($data);
			$signature = hash("sha256", ($json_data . $sign));
			$post = array(
				'data_param' => $json_data,
				'data_sign'	=> $signature,
			);

			$res[] = array('id'=>$value['id'], 'detail'=>self::curl($post, 'https://api.jtexpress.my/blibli/order/createOrders'));
		}

		return $res;

	}

	public function process_fresh_order($shipment_info) {
		$url = "https://api.jtexpress.my/international/api/otherservice/createOrder";
		$key = "0080fb7482c0e8f774f4ed8cb550c4ba56a5beed73595b52f62c531042f2ff27";

		$res = array();

		foreach ($shipment_info as $value) {
			$senderPostCode = $value['sender_zip'];
			$receiverPostCode = $value['receiver_zip'];
			$expressType = $this->freshDelivery($senderPostCode, $receiverPostCode);

			if ($expressType) {
				if ($value['weight'] <= 5) {
					$data = [
						"logisticId" => $value['orderid'],
						"userName" => "WORDPRESS", 
						"apiKey" => "WORD12",
						"customerCode" => $value['cuscode'],
						"sender" => [
							"address" => $value['sender_addr'],
							"postcode" => $value['sender_zip'],
							"area" => $value['sender_area'],
							"city" => $value['sender_city'],
							"prov" => $value['sender_prov'],
							"country" => $value['sender_country'],
							"name" => $value['sender_name'],
							"phone" => $value['sender_phone']
						],
						"receiver" => [
							"address" => $value['receiver_addr'],
							"postcode" => $value['receiver_zip'],
							"area" => $value['receiver_area'],
							"city" => $value['receiver_city'],
							"prov" => $value['receiver_prov'],
							"country" => $value['receiver_country'],
							"name" => $value['receiver_name'],
							"phone" => $value['receiver_phone']
						],
						"expressType" => $value['expresstype'],
						"goodsName" => mb_substr($value['item'], 0, 200, 'UTF-8'),
						"goodsType" => $value['goodsType'],
						"goodsValue" => $value['item_value'],
						"orderWeight" => $value['weight'],
						"paymentType" => $value['payType'],
						"qty" => $value['qty'],
						"serviceType" => $value['servicetype'],
						"remark" => $value['remark']
					];

					$json_data = json_encode($data);
					$signature = hash("sha256", ($json_data.$key));

					$header = array(
						'Content-Type' => 'application/json',
						'account' => 'WORDPRESS',
						'sign' => $signature
					);

					$response = wp_remote_post($url, array('sslverify' => false, 'headers' => $header, 'body' => $json_data));
					$response = wp_remote_retrieve_body($response);

					$res[] = array('id' => $value['id'], 'detail' => $response);
				} else {
					$reason = ['code' => 0, 'msg' => 'This service only supports up to 5KG'];
					$res[] = array('id' => $value['id'], 'detail' => json_encode($reason));
				}
			} else {
				$reason = ['code' => 0, 'msg' => 'This Shipment Type is not Currently Supported!'];
				$res[] = array('id' => $value['id'], 'detail' => json_encode($reason));
			}
		}
		return $res;
	}

	public function tracking($awb){
		$url = 'http://14.192.70.169:22236/jandt-app-ifd-web/router.do';
		$key = "0080fb7482c0e8f774f4ed8cb550c4ba56a5beed73595b52f62c531042f2ff27";
		$data = [
			'parameter' => array(
				'billCode'	=> $awb,
				'lang'		=> 'en',
			)
		];
		$json_data = json_encode($data);
		$signature = hash("sha256", ($json_data.$key));
		$header = array('from' => 'WORDPRESS','sign' => $signature);
		$post = array('data'=> json_encode($data),'method' => 'order.orderTrack',);

		$response = wp_remote_post($url, array('sslverify' => false, 'headers' => $header, 'body' => $post));
		return wp_remote_retrieve_body($response);
		
	}

	public function cancel ($awbs) {

		$url = 'https://api.jtexpress.my/blibli/order/cancelOrder';
		
		$key = 'AKe62df84bJ3d8e4b1hea2R45j11klsb';

		$res = array();

		foreach ($awbs as $value) {
			$data = array(
				'username' => 'WORDPRESS',
				'api_key' => 'WORD12',
				'awb_no' => $value['awb'],
				'orderid' => '',
    			'remark' => ''
			);

			$json_data = json_encode($data);
			$signature = base64_encode(md5($json_data.$key));
			$post = array(
			 	'data_param' => $json_data, 
			 	'data_sign'  => $signature 
			);
			
			$res[] = array('id'=>$value['id'], 'detail'=>self::curl($post, $url));
		}

		return $res;
	}

	public static function curl($post, $url) {
		$r = wp_remote_post($url, array('sslverify' => false, 'body' => $post));

		return wp_remote_retrieve_body($r);

	}

	public function printA4($cuscode, $awbs) {
		$url = "https://api.jtexpress.my/jandt_report_web/print/A4facelistAction!print.action";
    				
		$logistics_interface	= array(
			'account'	=> 'WORDPRESS',
			'password'	=> 'WORD12',
			'customercode'	=> $cuscode,
			'billcode'	=> $awbs,
		);

		$post = array('logistics_interface' => json_encode($logistics_interface), 'data_digest' => '123', 'msg_type' => '1');

		$result = wp_remote_post($url, array('body' => $post));
		header('Content-Type: application/pdf');
		print_r(wp_remote_retrieve_body($result));
	}

	public function print($cuscode, $awbs) {
		$url = 'https://api.jtexpress.my/jandt_report_web/print/facelistAction!print.action';

		$logistics_interface = array(
			'account'	=> 'WORDPRESS',
			'password'	=> 'WORD12',
			'customercode'	=> $cuscode,
			'billcode'	=> $awbs,
		);

		$post = array('logistics_interface' => json_encode($logistics_interface), 'data_digest' => '123', 'msg_type' => '1');

		$result = wp_remote_post($url, array('body' => $post));
		header('Content-Type: application/pdf');
		print_r(wp_remote_retrieve_body($result));
	}

	public function calculate($weight, $sender_zip, $receiver_zip, $cuscode, $pass) {

		$url = 'https://api.jtexpress.my/open/api/express/getQuotedPriceByCustomer';
		$key = '0080fb7482c0e8f774f4ed8cb550c4ba56a5beed73595b52f62c531042f2ff27';

		$data = [
			'customerCode'	=> $cuscode,
			'password'		=> $pass,
			'expressType'  => 'EZ',
         	'goodsType' => 'PARCEL',
         	'pcs' => 1,
			'receiverPostcode'	=> $receiver_zip,
			'senderPostcode'	=> $sender_zip,
			'weight'	=> $weight, 
		];

		$json_data = json_encode($data);
		$signature = hash("sha256",($json_data.$key));
		
		$header = array(
			'Content-Type' => 'application/json',
			'account' => 'WORDPRESS',
			'sign' => $signature
		);

		$response = wp_remote_post($url, array('sslverify' => false, 'headers' => $header, 'body' => $json_data));
		$res = wp_remote_retrieve_body($response);
		
		$res = json_decode($res, true);
		return $res['data']['shippingFee'] ?? 0; 

	}

	public function postcode($postcode) {
		$url = "https://sd.jtexpress.my/post.php";
		$data = ['postcode' => $postcode];
		$json_data = json_encode($data);
		$response = wp_remote_post($url, array('body' => $json_data));
		$res = wp_remote_retrieve_body($response);
		return (!empty($res)) ? json_decode($res, true) : [];
	}

	public function freshDelivery($senderPostCode, $receiverPostCode) {
		$url = 'http://14.192.70.169:22236/jandt-app-ifd-web/router.do?';

		$data = ['parameter' => ['senderCountry' => 'MYS', 'receiverCountry' => 'MYS', 'senderPostCode' => $senderPostCode, 'receiverPostCode' => $receiverPostCode]];
		$url = $url . 'method=app.listExpressType&data='. json_encode($data);
		$response = wp_remote_get($url, array('sslverify' => false));
		$res = wp_remote_retrieve_body($respone);
		$data = json_decode($res, true);
		$list = json_decode($data['data'], true);
		return (in_array("FD", $list['countryList'])) ? true : faslse;
	}

	public function generate($value){
		echo '<img alt="testing" src="https://pmp.jtexpress.my/wordpresslib/barcode.php?text='.trim($value).'&size=55&sizefactor=2" />';
	}

	public function generate2($value){
		echo '<img alt="testing" src="https://pmp.jtexpress.my/wordpresslib/barcode.php?text='.trim($value).'&size=25&sizefactor=1" />';

	}

}