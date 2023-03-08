<?php

use PHPMailer\PHPMailer\PHPMailer;

class Jnt_Shipment_Order
{

	public $jnt_helper = null;

	public function __construct()
	{

		$this->jnt_helper = new Jnt_Helper();
		$this->define_hooks();
	}

	/**
	 * Define hooks
	 */
	protected function define_hooks()
	{

		add_filter('bulk_actions-edit-shop_order', [$this, 'bulk_actions_create_order'], 30);
		add_filter('handle_bulk_actions-edit-shop_order', [$this, 'handle_bulk_action_create_order'], 10, 3);

		add_filter('manage_edit-shop_order_columns', [$this, 'table_order_number_column_header']);
		add_action('manage_shop_order_posts_custom_column', [$this, 'table_order_number_column_content'], 10, 2);

		add_filter('woocommerce_shop_order_search_fields', [$this, 'waybill_searchable_field'], 10, 1);

		add_action('admin_notices', [$this, 'admin_notices']);
	}

	public function bulk_actions_create_order($actions)
	{

		$actions['jnt_create_order'] = __('Order to J&T');
		$actions['jnt_fresh_delivery'] = __('J&T Fresh Delivery');

		return $actions;
	}

	public function handle_bulk_action_create_order($redirect_to, $action, $post_ids)
	{
		if ($action !== 'jnt_create_order' && $action !== 'jnt_fresh_delivery') {
			return $redirect_to;
		}

		$processed_ids = array();
		$empty_awb = array();
		$reasons = array();
		$stt = array();
		$result = array();

		foreach ($post_ids as $post_id) {
			if (!get_post_meta($post_id, 'jtawb', true)) {
				$processed_ids[] = $post_id;
			} else {
				$empty_awb[] = $post_id;
			}
		}

		if (!empty($processed_ids)) {
			if ($action == 'jnt_create_order') {
				$result = $this->jnt_helper->process_order($processed_ids);

				foreach ($result as $details) {

					$id = $details['id'];
					$awb = "";
					$orderid = "";
					$status = "";
					$code = "";
					$reason = "";

					$detail = json_decode($details['detail']);
					foreach ($detail as $d) {

						$awb = $d[0]->awb_no;
						$orderid = $d[0]->orderid;
						$status = $d[0]->status;
						$code = $d[0]->data->code;
						$reason = $d[0]->reason;
					}

					if ($awb) {
						$order = wc_get_order(  $id );
						$order->add_order_note( "Tracking number: ". $awb );

						add_post_meta($id, 'jtawb', $awb);
						add_post_meta($id, 'jtorder', $orderid);
						add_post_meta($id, 'jtcode', $code);
					} else {
						array_push($reasons, array('id' => $id, 'reason' => $reason));
					}
					array_push($stt, $status);
				}
			}

			if ($action == 'jnt_fresh_delivery') {
		 		$result = $this->jnt_helper->process_fresh_order($processed_ids);

		 		foreach($result as $details) {
		 			$id = $details['id'];

		 			$detail = json_decode($details['detail'],true);
					$awb = $detail['data']['billcode'];
					$orderid = $detail['data']['logisticId'];
					$status = $detail['msg'];
					$code = $detail['data']['code'];
					$reason = $detail['msg'];

					if ($awb) {
						$order = wc_get_order(  $id );
						$order->add_order_note( "Tracking number: ". $awb );

						add_post_meta($id, 'jtawb', $awb);
						add_post_meta($id, 'jtorder', $orderid);
						add_post_meta($id, 'jtcode', $code);
					} else {
						array_push($reasons, array('id' => $id, 'reason' => $reason));
					}
					array_push($stt, $status);
			 	}
            }

			$redirect_to = add_query_arg(array(
				'acti'	=> 'order',
				'status' => $stt,
				'reasons' => $reasons,
			), $redirect_to);

			return $redirect_to;
		} else {
			$redirect_to = add_query_arg(array(
				'acti'	=> 'error',
				'msg'	=> 'Already Order'
			), $redirect_to);

			return $redirect_to;
		}
	}

	public function table_order_number_column_header($columns)
	{
		$columns['waybill'] = 'J&T Waybill';
		$columns['cancel'] = 'J&T Cancelled Order';
		return $columns;
	}

	public function table_order_number_column_content($columns, $post_id)
	{

		switch ($columns) {
			case 'waybill':
				$waybill = get_post_meta($post_id, 'jtawb', true);
				echo $waybill;
				break;

			case 'order':
				$order = get_post_meta($post_id, 'jtorder', true);
				echo $order;
				break;

			case 'cancel':
				$cancel = get_post_meta($post_id, 'cancel', true);
				if ($cancel) {
					foreach ($cancel as $key => $value) {
						echo $value . "<br/>";
					}
				}
				break;
		}
	}

	public function waybill_searchable_field($meta_keys)
	{
		$meta_keys[] = 'jtawb';
		return $meta_keys;
	}

	public function admin_notices()
	{
		if (!isset($_REQUEST['acti'])) {
			return;
		}

		if ($_REQUEST['acti'] == 'order') {

			if (in_array("success", $_GET['status'])) {
?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo esc_html('Order Success'); ?></p>
				</div>
				<?php
			}
			if ($_GET['reasons']) {
				foreach ($_GET['reasons'] as $key => $value) {
					if ($value['reason'] == 'S10') {
						$res = "Duplicate Order Number";
					} else if ($value['reason'] == 'S11') {
						$res = "Duplicate Waybill Number";
					} else if ($value['reason'] == 'S12') {
						$res = "Order Already Pick Up Can't Cancel";
					} else if ($value['reason'] == 'S13') {
						$res = "API Key Wrong";
					} else if ($value['reason'] == 'S14') {
						$res = "Order Number can't Empty";
					} else if ($value['reason'] == 'S15') {
						$res = "Waybill Number can't Empty";
					} else if ($value['reason'] == 'S17') {
						$res = "Number does not meet our rules";
					} else if ($value['reason'] == 'S18') {
						$res = "Sender Address can't Empty";
					} else if ($value['reason'] == 'S19') {
						$res = "Receiver Address can't Empty";
					} else if ($value['reason'] == 'S29') {
						$res = "Sender Postcode can't Empty";
					} else if ($value['reason'] == 'S30') {
						$res = "Receiver Postcode can't Empty";
					} else if ($value['reason'] == 'S31') {
						$res = "Sender Postcode not Exist";
					} else if ($value['reason'] == 'S32') {
						$res = "Receiver Postcode not Exist";
					} else if ($value['reason'] == 'S34') {
						$res = "Customer/Vip Code not Exist";
					} else if ($value['reason'] == 'S35') {
						$res = "Sender Name can't Empty";
					} else if ($value['reason'] == 'S36') {
						$res = "Sender Phone can't Empty";
					} else if ($value['reason'] == 'S37') {
						$res = "Receiver Name can't Empty";
					} else if ($value['reason'] == 'S38') {
						$res = "Receiver Phone can't Empty";
					} else if ($value['reason'] == 'S40') {
						$res = "Weight can't Empty";
					} else if ($value['reason'] == 'S41') {
						$res = "Payment Type can't Empty";
					} else if ($value['reason'] == 'S42') {
						$res = "Wrong Payment Type";
					} else if ($value['reason'] == 'S43') {
						$res = "Service Type can't Empty";
					} else {
						$res = sanitize_text_field($value['reason']);
					}
				?>
					<div class="notice notice-warning is-dismissible">
						<p><?php echo esc_html('#' . $value['id'] . ' ' . $res); ?></p>
					</div>
<?php
				}
			}
		} else if ($_REQUEST['acti'] == 'thermal-new') {
			$url = plugin_dir_url(__FILE__) . 'view/thermal-new.php';
			echo "<div id='message' class='updated fade'>";
			echo "<p>";
			if ($_REQUEST['empty'] != "0") {
				echo $_REQUEST['empty'] . " Orders not yet \"Order to J&T\".<br/>";
			}
			echo "Total " . $_REQUEST['count'] . " Orders are Selected to Print Thermal(NEW).<br/>";
			echo "Click <a href='" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "' target='_blank'>Here</a> to Print";
			echo "</p>";
			echo "</div>";

			echo "<script>window.open('" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "', '_blank')</script>";
		} else if ($_REQUEST['acti'] == 'thermal') {
			$url = plugin_dir_url(__FILE__) . 'view/thermal.php';
			echo "<div id='message' class='updated fade'>";
			echo "<p>";
			if ($_REQUEST['empty'] != "0") {
				echo $_REQUEST['empty'] . " Orders not yet \"Order to J&T\".<br/>";
			}
			echo "Total " . $_REQUEST['count'] . " Orders are Selected to Print Thermal.<br/>";
			echo "Click <a href='" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "' target='_blank'>Here</a> to Print";
			echo "</p>";
			echo "</div>";

			echo "<script>window.open('" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "', '_blank')</script>";
		} else if ($_REQUEST['acti'] == 'consignment-note') {
			$url = plugin_dir_url(__FILE__) . 'view/consignment-note.php';
			echo "<div id='message' class='updated fade'>";
			echo "<p>";
			if ($_REQUEST['empty'] != "0") {
				echo $_REQUEST['empty'] . " Orders not yet \"Order to J&T\".<br/>";
			}
			echo "Total " . $_REQUEST['count'] . " Orders are Selected to Print A4.<br/>";
			echo "Click <a href='" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "' target='_blank'>Here</a> to Print";
			echo "</p>";
			echo "</div>";

			echo "<script>window.open('" . $url . "?" . http_build_query(array('ids' => $_REQUEST['ids'])) . "', '_blank')</script>";
		} else if ($_REQUEST['acti'] == 'cancel') {
			echo "<div id='message' class='updated fade'>";
			echo "<p>";
			echo $_REQUEST['status'];
			echo "</p>";
			echo "</div>";
		} else if ($_REQUEST['acti'] == 'error') {
			echo "<div id='message' class='updated fade'>";
			echo "<p>";
			echo $_REQUEST['msg'];
			echo "</p>";
			echo "</div>";
		}
	}
}
