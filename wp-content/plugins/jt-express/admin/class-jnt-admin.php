<?php

class Jnt_Admin {

	public function __construct() {

		$this->jnt_helper = new Jnt_Helper();
		$this->define_hooks();

	}

	public function define_hooks() {

		add_action( 'plugins_loaded', [ $this, 'check_woocommerce_activated' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );

	}

	/**
	 * Check if Woocommerce installed
	 */
	public function check_woocommerce_activated() {
		if ( defined( 'WC_VERSION' ) ) {
			return;
		}

		add_action( 'admin_notices', [ $this, 'notice_woocommerce_required' ] );
	}

	/**
	 * Admin error notifying user that Woocommerce is required
	 */
	public function notice_woocommerce_required() {
		?>
		<div class="notice notice-error">
			<p><?= 'Jnt requires WooCommerce to be installed and activated!' ?></p>
		</div>
		<?php
	}

	/**
	 * Add menu
	 */
	public function add_menu() {

		add_menu_page(
			'',
			'J&T Express',
			'manage_options', 
			'main_page', 
			array($this, 'parcel_admin_page'), 
			'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAUCAMAAACtdX32AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAFKUExURQAAAK2lpdbW1p6enqqkqra2tsjIztXV29vV1f///5CVmpqfn6ikqKiorbK3t8rKytTU1NTZ2d3U2f///6ikqMvLy5ueocjLy9XY2MnJzNfU1P///36Dhaapq6mkqZKWmpSWmsjKzP///6mjqaikqLS1uMjKzOfn6f/9/f///5KVmbS2ucnLzNfX2t3X1+Tm5v///8nJzP///////3d6f6mkp7S1uNbU1dbW2fT09P/8/P/+/v///7S2uMnKzOfn6PPz8//+/v///ycrM2pucqmkqPj4+ScsMygtNDU5QEJHTUdKT09UWVFQVVFVWl1eZF1hZmpucmxvdHd6f3+ChpGKjZKVmZOVmZudoKaoq6mkqLGytLOytLS2uMnKzMvMztbU1dbX2d3W1+Tl5ubn5+fn6Orb3Ozs7e3t7vPz8/j4+f/8/f/+/v///3AiPy0AAABHdFJOUwAfHyoqKioqKio1NTU1NTU1NTU1SUlUVFRfX19zc3N+fn6Ai5+fn5+fn6qqqqqqqqq1tb7JycnJycnJycnU1NTU1NTf39/fHlXVhgAAAAlwSFlzAAAOnAAADpwBB5RT3QAAATJJREFUKFO10VdTAjEUBeBrW1kpUVZcrFhRFBFQQCUKxrL23kvsnfP/X80WRccXXzwPyU2+uZNMQn+LxjR7DATqifSmkIrfX+eSip7gcTUlc7lBah3lnOdVhhpcJDI4RK+ESimWtSfg6jTv99RmLL3gbfnx/NhVVCpj37tP1u9x2DUQK33yXJuHioE9S670UA0NFwo3wEKxOPLVTAzYtY4w4SyaOwQ6Q6Gqkql49RIzqqxVztHi7rvRknjf2X5FWdX9OkXF8w9ms5DWEx6ucYFJg9K4/c3A5pq6cVonjukqa4y1C8hFYMPaB0xSPN7oIUWklPNApk/gbuvAZlMg6iGxpPMIwqSUUyDbnUH14iyutjhP+CiSUl8xBZxJJHRPnaMNw7DXvnDYMIJBxpzP/e8QfQANOUzlP5IqPwAAAABJRU5ErkJggg==',
			'56'
		);
	}

	public function parcel_admin_page(){

		if ( $_POST ) {
			$awb = $_POST['tracking'];

			$res = $this->jnt_helper->tracking ( $awb );

			if ( json_decode($res, true) ){
				
				$res = json_decode( $res, true );

			}else{

				
			}
		}

		include 'view/tracking.php';
	}

}