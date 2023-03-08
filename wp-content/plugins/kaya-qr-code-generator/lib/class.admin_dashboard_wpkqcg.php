<?php
/** 
 * Kaya QR Code Generator - Admin Dashboard Class
 * Manages Kaya QR Code Generator admin page.
 *
 * @since 1.4.1
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

if (!class_exists('WPKQCG_Admin_Dashboard'))
{
	class WPKQCG_Admin_Dashboard
	{
		/**
		 * Menu Page Slug
		 */
		 public static $_pageSlug = 'wpkqcg-kaya-qr-code-generator-admin-option-page';
		 
		/**
		 * Main Initialisation
		 * Adds admin menu and enqueue scripts.
		 */
		public static function init()
		{
			add_action('admin_menu', array(__CLASS__, 'addAdminMenuPage'));
			add_action('admin_enqueue_scripts', array(__CLASS__, 'addAdminCssJs'));
		}
		
		/**
		 * Adds admin menu
		 * Adds a submenu for Kaya QR Code Generator admin page.
		 */
		public static function addAdminMenuPage()
		{
			// add plugin features page
			add_submenu_page(
				WP_KayaStudio_Plugins_Admin_Dashboard::$_pageSlug, 
				esc_html__('QR Code Generator', WPKQCG_TEXT_DOMAIN), 
				esc_html__('QR Code Generator', WPKQCG_TEXT_DOMAIN), 
				'edit_posts', 
				self::$_pageSlug, 
				array(__CLASS__, 'doAdminPage')
			);
		}
		
		/**
		 * Return the plugin informations to be added in Plugins List
		 *
		 * @return array
		 */
		public static function getPluginInfos()
		{
			return array(
				'title'		=> esc_attr('Kaya QR Code Generator'),
				'page_name'	=> esc_attr__('QR Code Generator options', WPKQCG_TEXT_DOMAIN),
				'page_slug'	=> self::$_pageSlug,
				'page_text'	=> esc_attr__('Generate QR Code through Widgets and Shortcodes, without any dependencies.', WPKQCG_TEXT_DOMAIN),
			);
		}
		
		/**
		 * Displays admin page
		 * Includes the page and display it.
		 */
		public static function doAdminPage()
		{
			if (is_file(plugin_dir_path(__FILE__) . '../includes/wpkqcg-admin-page.php'))
			{
				include_once plugin_dir_path(__FILE__) . '../includes/wpkqcg-admin-page.php';
				wpkqcg_admin_doOptionPage();
			}
		}
		
		/**
		 * Adds admin menu styles and scripts
		 * Registers and enqueue styles and scripts for Kaya QR Code Generator admin page.
		 *
		 * @param int $hook Hook suffix for the current admin page.
		 */
		public static function addAdminCssJs($hook)
		{
			if (isset($hook) && !empty($hook) && get_plugin_page_hookname(self::$_pageSlug, WP_KayaStudio_Plugins_Admin_Dashboard::$_pageSlug) === $hook)
			{
				wp_register_style('kayastudio_wp_admin_css', plugin_dir_url(__FILE__) . '../css/kayastudio-admin-page-pkg.min.css', false, '1.0.0');
				wp_enqueue_style('kayastudio_wp_admin_css');
			}
		}
	}
}
