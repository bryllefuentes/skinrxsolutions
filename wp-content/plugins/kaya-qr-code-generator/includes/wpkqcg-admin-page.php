<?php
/**
 * Kaya QR Code Generator - Admin Page.
 * Displays the admin plugin page.
 *
 * @since 1.4.1
 */

/**
 * Display QR Code Generator Page.
 */
if (!function_exists('wpkqcg_admin_doOptionPage'))
{
	function wpkqcg_admin_doOptionPage()
	{
		if (!current_user_can('edit_posts'))
		{
			wp_die('<p>' . __('You do not have sufficient permissions.') . '</p>');
		}
		
		$wpkqcg_footer = sprintf(/* translators: 1: Plugin Name 2: Plugin Version */__('%1$s - Version %2$s', WPKQCG_TEXT_DOMAIN), 'Kaya QR Code Generator', WPKQCG_VERSION);
	?>
	
	<div class="ks-wp-dashboard-page-container">
		<div class="ks-wp-dashboard-page-row">
	
			<div class="ks-wp-dashboard-page-header">
				<div class="ks-wp-dashboard-page-header-title">
					<h1>Kaya QR Code Generator</h1>
				</div>
			</div>
	
			<div class="ks-wp-dashboard-page-content">
				
				<div class="ks-wp-dashboard-page-content-card">
					<h6 class="ks-wp-dashboard-page-content-card-title"><?php echo esc_html__('QR Code Generator', WPKQCG_TEXT_DOMAIN); ?></h6>
					<p>
						<?php echo esc_html__('Generate QR Code through Widgets and Shortcodes, without any dependencies.', WPKQCG_TEXT_DOMAIN); ?>
					</p>
					<p>
						<?php echo esc_html__('Use built-in Widgets in "Appearance > Widgets".', WPKQCG_TEXT_DOMAIN); ?>
					</p>
					<p>
						<?php echo esc_html__('Use shortcodes like this:', WPKQCG_TEXT_DOMAIN); ?>
					</p>
					<table class="ks-wp-dashboard-page-content-table">
						<tbody>
							<tr>
								<td><?php echo esc_html__('Basic shortcode for a static content:', WPKQCG_TEXT_DOMAIN); ?></td>
								<td><code>[kaya_qrcode content="<?php echo esc_attr__('My encoded content', WPKQCG_TEXT_DOMAIN); ?>"]</code></td>
							</tr>
							<tr>
								<td><?php echo esc_html__('Basic shortcode for a dynamic content:', WPKQCG_TEXT_DOMAIN); ?></td>
								<td><code>[kaya_qrcode_dynamic][example_shortcode][/kaya_qrcode_dynamic]</code></td>
							</tr>
						</tbody>
					</table>
					<p>
						<?php echo esc_html__('Or use the following shortcode generator assistant to assist you on the shortcode structure construction. The generated shortcode must be pasted in a “shortcode block” or directly in the page content.', WPKQCG_TEXT_DOMAIN); ?><br /><br />
						<?php echo esc_html__('The shortcode generator assistant is also available in pages, posts, WooCommerce products or in any public custom post type, the generator is under the administration primary content of your page / post / product.', WPKQCG_TEXT_DOMAIN); ?>
					</p>
				</div>
				
				<div class="ks-wp-dashboard-page-content-card">
					<h6 class="ks-wp-dashboard-page-content-card-title"><?php echo esc_html__('Shortcode generator assistant', WPKQCG_TEXT_DOMAIN); ?></h6>
					<?php
						// get form fields and default values
						$formFieldsHTML = WPKQCG_Forms_QRCode::display_form_fields_options();
						$formFieldsDefaultValues = WPKQCG_Forms_QRCode::get_fields_default_value();
						
						// shortcode preparation
						$shortcodeGenerated = '[kaya_qrcode';
						foreach ($formFieldsDefaultValues as $i_attr => $i_val)
						{
							if ('' != $i_val)
							{
								$shortcodeGenerated .= ' ' . $i_attr . '="' . $i_val . '"';
							}
						}
						$shortcodeGenerated .= ']';
						
						// img preview preparation
						$currentPostID			= false;
						$currentPostPermalink	= '';
						$currentAdminURL		= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						$formFieldsValidated	= WPKQCG_Forms_QRCode::validate_fields($formFieldsDefaultValues);
						
						// shortcode html
						$qrcodeShortcodeHTML = '<input type="hidden" id="wpkqcg_shortcode_generator_sc_name" value="kaya_qrcode" />';
						$qrcodeShortcodeHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_sc_name_dynamic" value="kaya_qrcode_dynamic" />';
						$qrcodeShortcodeHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_fields_default" value="' . esc_attr(json_encode($formFieldsDefaultValues)) . '" />';
						$qrcodeShortcodeHTML .= '<div><code id="wpkqcg_shortcode_generator_display" style="display:block;border:1px solid #ccc;padding:16px 32px;" >' . esc_html($shortcodeGenerated) . '</code></div>';
						
						// img html
						$qrcodeImgHTML = '<input type="hidden" id="wpkqcg_shortcode_generator_content_post_id" value="' . esc_attr($currentPostID) . '" />';
						$qrcodeImgHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_content_url_admin" value="' . esc_attr(esc_url($currentAdminURL)) . '" />';
						$qrcodeImgHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_content_url_default" value="' . esc_attr(esc_url($currentPostPermalink)) . '" />';
						$qrcodeImgHTML .= '<div id="wpkqcg_shortcode_generator_preview_img">';
						$qrcodeImgHTML .= '<div>' . wpkqcg_doDisplayQRCode($formFieldsValidated) . '</div>';
						$qrcodeImgHTML .= '<div>';
						$qrcodeImgHTML .= '<button type="button" onclick="wpkqcg_qrcode_preview_download();" id="wpkqcg_shortcode_generator_preview_open" class="button button-secondary is-button is-default is-large">' . esc_html__('Download QR Code', WPKQCG_TEXT_DOMAIN) . '</button>';
						$qrcodeImgHTML .= '</div></div>';
						
						// alert permalink html
						$qrcodeAlertHTML = '<div id="wpkqcg_shortcode_generator_preview_permalink_alert" style="display:none;color:#000;background-color:#ddffff;border:1px solid #ccc;padding:16px 32px;">';
						$qrcodeAlertHTML .= esc_html__('The QR Code preview is not available in plugin options page with the permalink as content.', WPKQCG_TEXT_DOMAIN);
						$qrcodeAlertHTML .= '</div>';
						
						// alert dynamic html
						$qrcodeAlertHTML .= '<div id="wpkqcg_shortcode_generator_preview_dynamic_alert" style="display:none;color:#000;background-color:#ddffff;border:1px solid #ccc;padding:16px 32px;">';
						$qrcodeAlertHTML .= esc_html__('The QR Code preview is not available with a dynamic content.', WPKQCG_TEXT_DOMAIN);
						$qrcodeAlertHTML .= '</div>';
						
						// set metabox HTML content
						$output = '<table class="form-table"><tbody>';
						$output .= '<tr><th>' . esc_html__('Shortcode Generated:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
						$output .= '<tr><td>' . $qrcodeShortcodeHTML . '</td></tr>';
						$output .= '<tr><th>' . esc_html__('QR Code Preview:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
						$output .= '<tr><td>' . $qrcodeImgHTML . $qrcodeAlertHTML . '</td></tr>';
						$output .= '<tr><th>' . esc_html__('QR Code settings:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
						$output .= '<tr><td>' . $formFieldsHTML . '</td></tr>';
						$output .= '</tbody></table>';
						
						// display metabox HTML content
						echo $output;
					?>
				</div>
				
			</div>
	
			<div class="ks-wp-dashboard-page-sidebar">
			<?php
				if (is_file(plugin_dir_path(__FILE__) . 'wpkqcg-admin-page-sidebar.php'))
				{
					include_once plugin_dir_path(__FILE__) . 'wpkqcg-admin-page-sidebar.php';
					wpkqcg_admin_doPageSidebar();
				}
				if (is_file(plugin_dir_path(__FILE__) . 'kayastudio-admin-page-sidebar.php'))
				{
					include_once plugin_dir_path(__FILE__) . 'kayastudio-admin-page-sidebar.php';
					kayastudio_plugins_admin_doMainPageSidebar();
				}
			?>
			</div>
			
			<div class="ks-wp-dashboard-page-footer">
				<div class="ks-wp-dashboard-page-footer-version">
					<p><?php echo esc_html($wpkqcg_footer); ?></p>
				</div>
			</div>
	
		</div>
	</div>
	
	<?php
	}
}