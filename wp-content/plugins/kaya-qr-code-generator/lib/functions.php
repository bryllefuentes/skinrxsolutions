<?php
/**
 * Kaya QR Code Generator - Main Functions.
 * Functions for displaying QR Code image.
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

/**
 * Global boolean $wpkqcg_qrcode_isDisplayed, true if a qrcode is about to be displayed.
 * Used to a better management and lighter loading of scripts and resources.
 *
 * @since 1.1.1
 */
global $wpkqcg_qrcode_isDisplayed;

/**
 * Displays QR Code structure.
 *
 * @param array	$p_qrcodeValues QR Code form fields values.
 * @param array	$p_widgetArgs Arguments from the widget.
 *
 * @return string
 */
if (!function_exists('wpkqcg_doDisplayQRCode'))
{
	function wpkqcg_doDisplayQRCode($p_qrcodeValues, $p_widgetArgs = array())
	{
		global $wpkqcg_qrcode_isDisplayed;
		$wpkqcg_qrcode_isDisplayed = true;
		
		// get QR Code values
		foreach ($p_qrcodeValues as $i_attr => $i_val)
		{
			${'qrcodeMeta_' . $i_attr} = $i_val;
		}
		
		// set QR Code img ID
		$qrcodeUniqueID	= rand(0, 99) . uniqid() . rand(0, 99);
		$qrcodeImgID	= esc_attr('wpkqcg_qrcode_outputimg_' . $qrcodeUniqueID);
		
		// prepare QR Code values
		$qrcodeTitle		= (!empty($qrcodeMeta_title)) ? esc_html($qrcodeMeta_title) : '';
		$qrcodeTitleAlign	= (!empty($qrcodeMeta_title_align)) ? esc_attr($qrcodeMeta_title_align) : '';
		$qrcodeContent		= (!empty($qrcodeMeta_content)) ? esc_attr($qrcodeMeta_content) : '';
		$qrcodeAnchor		= (!empty($qrcodeMeta_anchor)) ? esc_attr($qrcodeMeta_anchor) : '';
		$qrcodeEccLevel		= (!empty($qrcodeMeta_ecclevel)) ? esc_attr($qrcodeMeta_ecclevel) : '';
		$qrcodeNewWindow	= (!empty($qrcodeMeta_new_window)) ? esc_attr($qrcodeMeta_new_window) : '';
		$qrcodeContentURL	= (!empty($qrcodeMeta_content_url)) ? esc_attr($qrcodeMeta_content_url) : '';
		$qrcodeCssShadow	= (!empty($qrcodeMeta_css_shadow)) ? esc_attr($qrcodeMeta_css_shadow) : '';
		$qrcodeAlign		= (!empty($qrcodeMeta_align)) ? esc_attr($qrcodeMeta_align) : '';
		$qrcodeSize			= (!empty($qrcodeMeta_size)) ? esc_attr($qrcodeMeta_size) : '';
		$qrcodeColor		= (!empty($qrcodeMeta_color)) ? esc_attr($qrcodeMeta_color) : '';
		$qrcodeBgColor		= (!empty($qrcodeMeta_bgcolor)) ? esc_attr($qrcodeMeta_bgcolor) : '';
		$qrcodeAlt			= (!empty($qrcodeMeta_alt)) ? esc_attr($qrcodeMeta_alt) : 'QR Code';
		
		// set content as current url
		$qrcodeContentAsCurrentURL = false;
		if (empty($qrcodeContent))
		{
			$qrcodeContent = esc_attr(esc_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
			$qrcodeContent .= (!empty($qrcodeAnchor)) ? esc_attr('#' . $qrcodeAnchor) : '';
			$qrcodeContentAsCurrentURL = true;
		}
		
		// set QR Code URL
		if (!empty($qrcodeMeta_url) && strpos($qrcodeMeta_url, 'bitcoin:') === 0) // allow "bitcoin:" as Bitcoin URI scheme
		{
			$qrcodeURL = (!empty($qrcodeMeta_url)) ? esc_attr($qrcodeMeta_url) : '';
		}
		elseif (!empty($qrcodeMeta_url)) // escape regular URL
		{
			$qrcodeURL = (!empty($qrcodeMeta_url)) ? esc_attr(esc_url($qrcodeMeta_url)) : '';
		}
		elseif ($qrcodeContentAsCurrentURL && empty($qrcodeMeta_url) && !empty($qrcodeContentURL)) // take current url
		{
			$qrcodeURL = (!empty($qrcodeContent)) ? esc_attr(esc_url($qrcodeContent)) : '';
		}
		
		// set the title alignment
		$qrcodeTitleInlineAlign = '';
		if ($qrcodeTitleAlign == 'alignleft')
		{
			$qrcodeTitleInlineAlign = 'text-align: left;';
		}
		else if ($qrcodeTitleAlign == 'alignright')
		{
			$qrcodeTitleInlineAlign = 'text-align: right;';
		}
		else if ($qrcodeTitleAlign == 'aligncenter')
		{
			$qrcodeTitleInlineAlign = 'text-align: center;';
		}
		
		// set QR Code image style
		$qrcodeCssInlineBasic = 'width: auto; height: auto; max-width: 100%;';
		$qrcodeCssInlineShadow = (!empty($qrcodeCssShadow)) ? ' box-shadow: 2px 2px 10px #4A4242;' : '';
		
		// set QR Code image alignment
		$qrcodeCssInlineAlign = '';
		$qrcodeLinkCssInlineAlign = 'display: table; width: auto; height: auto; max-width: 100%;';
		$qrcodeClearBlock = '<div style="clear: none;"></div>';
		if ($qrcodeAlign == 'alignleft')
		{
			$qrcodeCssInlineAlign		= ' display: block; float: left; margin-right: 1.5em;';
			$qrcodeLinkCssInlineAlign	= 'display: block; float: left; width: auto; height: auto; max-width: 100%;';
			$qrcodeClearBlock			= '<div style="clear: left;"></div>';
		}
		else if ($qrcodeAlign == 'alignright')
		{
			$qrcodeCssInlineAlign		= ' display: block; float: right; margin-left: 1.5em;';
			$qrcodeLinkCssInlineAlign	= 'display: block; float: right; width: auto; height: auto; max-width: 100%;';
			$qrcodeClearBlock			= '<div style="clear: right;"></div>';
		}
		else if ($qrcodeAlign == 'aligncenter')
		{
			$qrcodeCssInlineAlign		= ' clear: both; display: block; margin-left: auto; margin-right: auto;';
			$qrcodeLinkCssInlineAlign	= 'display: table; width: auto; height: auto; max-width: 100%; margin-left: auto; margin-right: auto;';
			$qrcodeClearBlock			= '<div style="clear: both;"></div>';
		}
		
		// QR Code structure to display
		$output = '<!-- START Kaya QR Code Generator -->';
		$output .= '<div class="wpkqcg_qrcode_wrapper">';
		$output .= '<input type="hidden" id="' . $qrcodeImgID . '_ecclevel" value="' . $qrcodeEccLevel . '" />';
		$output .= '<input type="hidden" id="' . $qrcodeImgID . '_size" value="' . $qrcodeSize . '" />';
		$output .= '<input type="hidden" id="' . $qrcodeImgID . '_color" value="' . $qrcodeColor . '" />';
		$output .= '<input type="hidden" id="' . $qrcodeImgID . '_bgcolor" value="' . $qrcodeBgColor . '" />';
		$output .= '<input type="hidden" id="' . $qrcodeImgID . '_content" value="' . $qrcodeContent . '" />';
		
		// set the title
		if (!empty($qrcodeTitle) && empty($p_widgetArgs))
		{
			$output .= '<h2 style="' . $qrcodeTitleInlineAlign . '">' . $qrcodeTitle . '</h2>'; // shortcode title
		}
		elseif (!empty($qrcodeTitle) && !empty($p_widgetArgs) && !empty($p_widgetArgs['before_title']) && !empty($p_widgetArgs['after_title']))
		{
			if (strpos($p_widgetArgs['before_title'], '>') !== false)
			{
				$p_widgetArgs['before_title'] = str_replace('>', ' style="' . $qrcodeTitleInlineAlign . '">', $p_widgetArgs['before_title']);
			}
			$output .= $p_widgetArgs['before_title'] . $qrcodeTitle . $p_widgetArgs['after_title']; // widget title
		}
		
		// surround with a link to the URL
		if (!empty($qrcodeURL))
		{
			$output .= '<a href="' . $qrcodeURL . '"';
			$output .= ' style="' . $qrcodeLinkCssInlineAlign . '" ';
			if (!empty($qrcodeNewWindow))
			{
				$output .= ' target="_blank" rel="noopener noreferrer"'; // open in new window, rel="noopener noreferrer" improves security.
			}
			$output .= '>';
		}
		
		// set QR Code image structure
		$output .= '<img src="" id="' . $qrcodeImgID . '" alt="' . $qrcodeAlt . '" class="wpkqcg_qrcode"';
		$output .= ' style="' . $qrcodeCssInlineBasic . $qrcodeCssInlineShadow . $qrcodeCssInlineAlign . '" >';
		
		// close the link
		if (!empty($qrcodeURL))
		{
			$output .= '</a>';
		}
		
		$output .= $qrcodeClearBlock;
		$output .= '</div>';
		$output .= '<!-- END Kaya QR Code Generator -->';
		
		return $output;
	}
}

/**
 * Displays Public scripts in footer.
 * Required for qrcode generation and display.
 *
 * @since 1.1.1
 */
if (!function_exists('wpkqcg_displayInlineScripts'))
{
	function wpkqcg_displayInlineScripts()
	{
		global $wpkqcg_qrcode_isDisplayed;
		if ($wpkqcg_qrcode_isDisplayed)
		{
			$output = '<script type="text/javascript" src="' . WPKQCG_PLUGIN_URL . 'assets/qrcode-v2.min.js?ver=' . WPKQCG_VERSION . '"></script>';
			$output .= '<script type="text/javascript" src="' . WPKQCG_PLUGIN_URL . 'js/wpkqcg-pkg.min.js?ver=' . WPKQCG_VERSION . '"></script>';
			$output .= '<script type="text/javascript">window.addEventListener("DOMContentLoaded", (event) => {wpkqcg_qrcode_display();});</script>';
			
			echo $output;
		}
	}
	add_action('wp_footer', 'wpkqcg_displayInlineScripts');
}

/**
 * Enqueue Admin scripts.
 * Required for the shortcode generator assistant, available on pages, posts and custom post types.
 */
if (!function_exists('wpkqcg_enqueueAdminScripts'))
{
	function wpkqcg_enqueueAdminScripts()
	{
		$currentScreen = get_current_screen();
		$currentScreenID = $currentScreen ? $currentScreen->id : '';
		// Get all public post types
		$postTypes = wpkqcg_getAllPostTypesAsList();
			
		if (is_admin() && in_array($currentScreenID, $postTypes))
		{
			wp_enqueue_script('wpkqcg-admin-pkg', WPKQCG_PLUGIN_URL . 'js/wpkqcg-admin-pkg.min.js', array(), WPKQCG_VERSION);
		}
	}
	add_action('admin_enqueue_scripts', 'wpkqcg_enqueueAdminScripts');
}

/**
 * Displays Admin scripts in footer.
 * Required for qrcode preview on the shortcode generator assistant.
 *
 * @since 1.2.0
 */
if (!function_exists('wpkqcg_displayInlineAdminScripts'))
{
	function wpkqcg_displayInlineAdminScripts()
	{
		global $wpkqcg_qrcode_isDisplayed;
		if ($wpkqcg_qrcode_isDisplayed)
		{
			$output = '<script type="text/javascript" src="' . WPKQCG_PLUGIN_URL . 'assets/qrcode-v2.min.js?ver=' . WPKQCG_VERSION . '"></script>';
			$output .= '<script type="text/javascript" src="' . WPKQCG_PLUGIN_URL . 'js/wpkqcg-pkg.min.js?ver=' . WPKQCG_VERSION . '"></script>';
			$output .= '<script type="text/javascript">window.addEventListener("DOMContentLoaded", (event) => {wpkqcg_qrcode_preview_display();});</script>';
			
			echo $output;
		}
	}
	add_action('admin_footer', 'wpkqcg_displayInlineAdminScripts');
}

/**
 * Get all public post types.
 * Return public basics post types and custom post types as list.
 *
 * @return array
 *
 * @since 1.3.0
 */
if (!function_exists('wpkqcg_getAllPostTypesAsList'))
{
	function wpkqcg_getAllPostTypesAsList()
	{
		// Get all post types as list
		$postTypesArgs = array(
		   'public' => true,
		);
		$postTypesOutput	= 'names';
		$postTypesOperator	= 'and';
		$postTypes			= get_post_types($postTypesArgs, $postTypesOutput, $postTypesOperator);
		
		// Remove some built ins or others
		$postTypesRemove	= array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block');
		$postTypesCleaned	= array();
		
		foreach ($postTypes as $i_postType)
		{
			if (in_array($i_postType, $postTypesRemove)) continue;
			$postTypesCleaned[esc_attr($i_postType)] = esc_attr($i_postType);
		}
		
		// Add admin options page
		$postTypesCleaned['wpkqcg_admin_dashboard'] = esc_attr(get_plugin_page_hookname(WPKQCG_Admin_Dashboard::$_pageSlug, WP_KayaStudio_Plugins_Admin_Dashboard::$_pageSlug));
		
		return $postTypesCleaned;
	}
}
