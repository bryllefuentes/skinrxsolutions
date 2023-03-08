<?php
/**
 * Kaya QR Code Generator - Main Admin Functions.
 * Managing Admin features.
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

if (!is_admin())
{
	exit; // Exit if accessed outside dashboard
}

/**
 * Check for KayaStudio Plugins object and create it if not found.
 *
 * @since 1.4.1
 */
if (!isset($wp_kayastudio_dashboard_pluginsList))
{
	global $wp_kayastudio_dashboard_pluginsList;
	$wp_kayastudio_dashboard_pluginsList = new WP_KayaStudio_Plugins_List_Admin_Dashboard();
}

/**
 * Adds administration plugin menu pages.
 *
 * Adds pages to admin menu (Main page, Plugin Settings), and adds plugin infos in plugins list.
 *
 * @return bool	True if the current user has the specified capability for seeing the menu, or False if not.
 *
 * @since 1.4.1
 */
if (!function_exists('wpkqcg_admin_addMenuPages'))
{
	function wpkqcg_admin_addMenuPages()
	{
		if (!current_user_can('edit_posts'))
		{
			return false;
		}
		global $wp_kayastudio_dashboard_pluginsList;
		
		// Add Kaya Studio Main page
		WP_KayaStudio_Plugins_Admin_Dashboard::init();
		// Add Kaya QR Code Generator page
		WPKQCG_Admin_Dashboard::init();
		// Add Kaya QR Code Generator infos in plugins list
		$wp_kayastudio_dashboard_pluginsList->addPluginInList(WPKQCG_Admin_Dashboard::getPluginInfos());
			
		return true;
	}
	add_action('init', 'wpkqcg_admin_addMenuPages');
}
