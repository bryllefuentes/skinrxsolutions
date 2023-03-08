<?php
/** 
 * Kaya Studio - Admin Dashboard Class
 * Manages Kaya Studio admin main page for plugins.
 *
 * @version 1.0.0
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

if (!class_exists('WP_KayaStudio_Plugins_Admin_Dashboard'))
{
	class WP_KayaStudio_Plugins_Admin_Dashboard
	{
		/**
		 * Menu Page Slug
		 */
		 public static $_pageSlug = 'kayastudio-plugins-admin-main-page';
		 
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
		 * Adds admin main menu
		 * Adds a top-level menu and a submenu for Kaya Studio admin main page.
		 */
		public static function addAdminMenuPage()
		{
			if (!self::checkAdminMenuItem(self::$_pageSlug))
			{
				// add main page
				add_menu_page(
					'Kaya Studio', 
					'Kaya Studio', 
					'edit_posts', 
					self::$_pageSlug, 
					array(__CLASS__, 'doAdminMainPage'),
					'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAbAAAAGwBXBv9LgAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAEiSURBVDiN7ZM9SgNxFMTHzZd4Cwu1EK8RUKy1s7AVtEiZxiZnyAm8gAiKjRoPEFAMiCCIlYUEBYnIws/C+ZvnGlEwZQaWN2929n3BShP8G3xgB6ia71qvOW8GXzvwK/McYFRBgIegd4CXZAbW7KnGAgwxH4X+ly5DfdaxHLRn4MJ5y9op8FjsALBvbcn5huNRnLLQ9BBofOph5fU0DfAEDIAucBvWXgy8Yn/XD8BmOup2OPCr43KYJAfmXDC3dpy487uYjw/AjcddAQ7CPbeAEyALa775XSOdy3HP+n26YSk0aAJ18w5QCh/mhULfYiZpQVIPaI1j40zStaSBpPKI92eSLgsnOpfUK/j6+A9LppmfOgLTgU8BlZBHvvqnFSb4Fe+tEts/LjeouAAAAABJRU5ErkJggg==', 
					'150'
				);
				add_submenu_page(
					self::$_pageSlug, 
					'Kaya Studio', 
					'Kaya Studio', 
					'edit_posts', 
					self::$_pageSlug, 
					array(__CLASS__, 'doAdminMainPage')
				);
			}
		}
		
		/**
		 * Displays admin main page
		 * Includes the main page and display it.
		 */
		public static function doAdminMainPage()
		{
			if (is_file(plugin_dir_path(__FILE__) . '../includes/kayastudio-admin-page.php'))
			{
				include_once plugin_dir_path(__FILE__) . '../includes/kayastudio-admin-page.php';
				kayastudio_plugins_admin_doMainPage();
			}
		}
		
		/**
		 * Adds admin main menu styles and scripts
		 * Registers and enqueue styles and scripts for Kaya Studio admin main page.
		 *
		 * @param int $hook Hook suffix for the current admin page.
		 */
		public static function addAdminCssJs($hook)
		{
			if (isset($hook) && !empty($hook) && get_plugin_page_hookname(self::$_pageSlug, self::$_pageSlug) === $hook)
			{
				wp_register_style('kayastudio_wp_admin_css', plugin_dir_url(__FILE__) . '../css/kayastudio-admin-page-pkg.min.css', false, '1.0.0');
				wp_enqueue_style('kayastudio_wp_admin_css');
			}
		}
		
		/**
		 * Checks menu items.
		 * Finds if the slug is present as item in administration menu.
		 *
		 * @return bool
		 */
		public static function checkAdminMenuItem($handle, $sub = false)
		{
			if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX))
				return false;
			
			global $menu, $submenu;
			$check_menu = $sub ? $submenu : $menu;
			
			if (empty($check_menu))
				return false;
		
			foreach ($check_menu as $k => $item)
			{
				if($sub)
				{
					foreach ($item as $sm)
					{
						if ($handle == $sm[2])
							return true;
					}
				}
				else
				{
					if ($handle == $item[2])
						return true;
				}
			}
			
			return false;
		}
	}
}
