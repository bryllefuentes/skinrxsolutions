<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'class-wc-gateway-payssion.php' );

/**
 * Payssion 
 *
 * @class 		WC_Gateway_Payssion_Banktransfereu
 * @extends		WC_Payment_Gateway
 * @author 		Payssion
 */
class WC_Gateway_Payssion_Banktransfereu extends WC_Gateway_Payssion {
	protected $pm_id = 'banktransfer_eu';
	public $title = 'Europen SEPA Bank Transfer';
}