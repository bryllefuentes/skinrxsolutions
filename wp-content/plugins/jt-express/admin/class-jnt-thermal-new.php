<?php
 
class Jnt_Thermal_New {

	public $jnt_helper = null;

	public function __construct() {

		$this->jnt_helper = new Jnt_Helper();
		$this->define_hooks();

	}

	/**
	* Define hooks
	*/
	protected function define_hooks() {

		add_filter( 'bulk_actions-edit-shop_order', [ $this, 'bulk_actions_consignment_note_thermal_new' ], 30 );
		add_filter( 'handle_bulk_actions-edit-shop_order', [$this, 'handle_bulk_action_consignment_note_thermal_new'], 10, 3 );
		
	}

	public function bulk_actions_consignment_note_thermal_new ( $actions ) {

		$actions['jnt_consignment_note_thermal_new'] = __( 'Print J&T Consignment Note (more item)' );

		return $actions;
	}

	public function handle_bulk_action_consignment_note_thermal_new ( $redirect_to, $action, $post_ids ) {

		if ( $action !== 'jnt_consignment_note_thermal_new' ) {
			return $redirect_to;
		}

		$processed_ids = array();
		$empty_awb = array();

		foreach ( $post_ids as $post_id ) {
			if ( ! get_post_meta( $post_id, 'jtawb', true ) ) {
				$empty_awb[] = $post_id;
			}else{
				$processed_ids[] = $post_id;
			}
	    }

	    if ( ! empty( $processed_ids ) ) {
			$result = $this->jnt_helper->process_print_thermal_new($processed_ids);

		} else {
			
			$redirect_to = add_query_arg( array(
				'acti' => 'error',
				'msg' => 'Not yet Order',
			), $redirect_to );

			return $redirect_to;
		}

	}

}