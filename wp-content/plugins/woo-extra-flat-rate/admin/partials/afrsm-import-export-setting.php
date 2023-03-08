<?php
	
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
	$get_status = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
	$msg        = '';
	$style      = "display:none;";
	if ( 'success' === $get_status ) {
		$style = "display:block;";
		$msg   = esc_html__( 'Import successfully', 'advanced-flat-rate-shipping-for-woocommerce' );
	}
?>
<div class="imp_exp_msg" style="<?php echo esc_attr( $style ); ?>">
	<?php echo esc_html( $msg ); ?>
</div>
<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl">
        <h2><?php echo esc_html__( 'Step 1 - Import &amp; Export Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
        <table class="table-outer">
            <tbody>
            <tr>
                <th scope="row" class="titledesc"><label
                            for="blogname"><?php echo esc_html__( 'Export Zone Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <form method="post">
                        <div class="afrsm_main_container">
                            <p class="afrsm_button_container"><?php submit_button( esc_html__( 'Export', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false ); ?></p>
                            <p class="afrsm_content_container">
								<?php wp_nonce_field( 'afrsm_zone_export_save_action_nonce', 'afrsm_zone_export_action_nonce' ); ?>
                                <input type="hidden" name="afrsm_zone_export_action" value="zone_export_settings"/>
                                <strong><?php esc_html_e( 'Export the zone settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
                            </p>
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <th scope="row" class="titledesc"><label
                            for="blogname"><?php echo esc_html__( 'Import Zone Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <form method="post" enctype="multipart/form-data">
                        <div class="afrsm_main_container">
                            <p>
                                <input type="file" name="zone_import_file"/>
                            </p>
                            <p class="afrsm_button_container">
                                <input type="hidden" name="afrsm_zone_import_action" value="zone_import_settings"/>
								<?php wp_nonce_field( 'afrsm_zone_import_action_nonce', 'afrsm_zone_import_action_nonce' ); ?>
								<?php
									$other_attributes = array( 'id' => 'afrsm_zone_import_setting' );
								?>
								<?php submit_button( esc_html__( 'Import', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false, $other_attributes ); ?>
                                <strong><?php esc_html_e( 'Import the zone settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
                            </p>
                        </div>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
	<div class="afrsm-main-table res-cl">
		<h2><?php echo esc_html__( 'Step 2 - Import &amp; Export Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
		<table class="table-outer">
			<tbody>
			<tr>
				<th scope="row" class="titledesc"><label
						for="blogname"><?php echo esc_html__( 'Export Shipping Method Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				</th>
				<td>
					<form method="post">
						<div class="afrsm_main_container">
							<p class="afrsm_button_container"><?php submit_button( esc_html__( 'Export', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false ); ?></p>
							<p class="afrsm_content_container">
								<?php wp_nonce_field( 'afrsm_export_save_action_nonce', 'afrsm_export_action_nonce' ); ?>
								<input type="hidden" name="afrsm_export_action" value="export_settings"/>
								<strong><?php esc_html_e( 'Export the shipping method settings for this site as a .json file. This allows you to easily import the configuration into another site. Please make sure simple product and variation products slugs must be unique.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
							</p>
						</div>
					</form>
				</td>
			</tr>
			<tr>
				<th scope="row" class="titledesc"><label
						for="blogname"><?php echo esc_html__( 'Import Shipping Method Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				</th>
				<td>
					<form method="post" enctype="multipart/form-data">
						<div class="afrsm_main_container">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p class="afrsm_button_container">
								<input type="hidden" name="afrsm_import_action" value="import_settings"/>
								<?php wp_nonce_field( 'afrsm_import_action_nonce', 'afrsm_import_action_nonce' ); ?>
								<?php
								$other_attributes = array( 'id' => 'afrsm_import_setting' );
								?>
								<?php submit_button( esc_html__( 'Import', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false, $other_attributes ); ?>
								<strong><?php esc_html_e( 'Import the shipping method settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
							</p>
						</div>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>
