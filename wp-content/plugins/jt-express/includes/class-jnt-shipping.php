<?php

class Jnt_Shipping extends WC_Shipping_Method {

	public $jnt_helper = null;

	public function __construct() {

		$this->jnt_helper = new Jnt_Helper();

		$this->id                 = 'jnt';
		$this->method_title       = __( 'J&T Express', 'jnt' );
		$this->method_description = __( 'To start order to J&T, please fill in your info.', 'jnt' );

		$this->availability = 'including';
        $this->countries = array('MY');

		$this->init();

		$this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
 		$this->title = isset($this->settings['title']) ? $this->settings['title'] : __('cloudways Shipping', 'cloudways');

	}

	public function init() {

		$this->init_form_fields();
		$this->init_settings();

 		add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));

	}

	public function init_form_fields() {

		$this->form_fields = array(

			'enabled' => array(
				'title' => __( 'Enable', 'jnt' ),
				'type' => 'checkbox',
				'description' => __( 'Enable this shipping.', 'jnt' ),
				'default' => 'yes'
			),

			'title' => array(
				'title' => __( 'Title', 'jnt' ),
			  	'type' => 'text',
				'default' => 'J&T Express',
				'custom_attributes' => array('readonly' => 'readonly'),
			),

            'vipcode' => array(
                'title' => __( 'VIP Code', 'jnt' ),
                'type' => 'text',
                'description' => __( 'Go to J&T Express get your VIP Code.' ),
            ),

            'apikey' => array(
                'title' => __( 'API Key', 'jnt' ),
                'type' => 'password',
                'description' => __( 'Provided by J&T Express' ),
            ),

            'name' => array(
                'title' => __( 'Sender Name', 'jnt' ),
                'type' => 'text',
                'custom_attributes' => array( 'required' => 'required' ),
            ),

            'phone' => array(
                'title' => __( 'Sender Phone Number', 'jnt' ),
                'type' => 'tel',
                'custom_attributes' => array( 'required' => 'required' ),
            ),

            'service' => array(
                'title' => __( 'Service Type' ),
                'type' => 'select',
                'options' => array(
                    '1' => __( 'PICKUP' ),
                    '6' => __( 'DROPOFF' )
                )
            ),

            'goods' => array(
                'title' => __( 'Goods Name', 'jnt' ),
                'type' => 'checkbox',
                'description' => __( 'Tick this to show Goods Name in Consignment Note (more item).', 'jnt' ),
            ),

            'orderid' => array(
                'title' => __( 'Order ID', 'jnt' ),
                'type' => 'checkbox',
                'description' => __( 'Tick this to show Order ID in Consignment Note (more item).', 'jnt'),
            ),

        );

	}

	public function calculate_shipping( $package = array() ) {
                   
        $weight = 0;
        $cost = 0;
        $country = $package["destination"]["country"];
        $postcode = $package["destination"]["postcode"];

        foreach ( $package['contents'] as $item_id => $values ) 
        { 
            $_product = $values['data']; 
            $weight = $weight + $_product->get_weight() * $values['quantity']; 
        }

        $weight = wc_get_weight( $weight, 'kg' );

        $cost = $this->jnt_helper->shipping_rate($weight, $postcode);
        
        $rate = array(
            'id' => $this->id,
            'label' => $this->title,
            'cost' => $cost
        );

        $this->add_rate( $rate );
       
    }

}