<?php

/**
 * @link              http://wpthemespace.com
 * @since             1.1.0
 * @package           Magical Addons For Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Magical Addons For Elementor
 * Plugin URI:        
 * Description:       Premium addons for Elementor page builder
 * Version:           1.3.7
 * Author:            Noor alam
 * Author URI:        https://profiles.wordpress.org/nalam-1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       magical-addons-for-elementor
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Main Magical Addons For Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Magical_Addons_Elementor
{

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.3.7';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.6.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Test_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct()
	{

		$this->define_main();

		// Load text domain at init hook to prevent "too early" errors in WP 6.7+
		add_action('init', [$this, 'load_plugin_textdomain']);

		$this->call_main_file();
		//	add_action('activated_plugin', [$this, 'go_welcome_page']);
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'admin_adminpro_link']);



		if (!did_action('elementor/loaded')) {
			return;
		}
		add_action('plugins_loaded', [$this, 'init']);
	}

	/**
	 * Load plugin textdomain
	 * 
	 * @since 1.3.7
	 * @access public
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain('magical-addons-for-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Constract define
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function define_main()
	{
		if (!defined('MAGICAL_ADDON_VERSION')) {
			define('MAGICAL_ADDON_VERSION', (defined('WP_DEBUG') && WP_DEBUG) ? time() : self::VERSION);
		}

		if (!defined('MAGICAL_ADDON_URL')) {
			define('MAGICAL_ADDON_URL', plugin_dir_url(__FILE__));
		}

		if (!defined('MAGICAL_ADDON_ASSETS')) {
			define('MAGICAL_ADDON_ASSETS', plugin_dir_url(__FILE__) . 'assets/'); // Removed extra slash
		}

		if (!defined('MAGICAL_ADDON_PATH')) {
			define('MAGICAL_ADDON_PATH', plugin_dir_path(__FILE__));
		}

		if (!defined('MAGICAL_ADDON_ROOT')) {
			define('MAGICAL_ADDON_ROOT', __FILE__);
		}
	}





	/**
	 * regirect welcome page
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function go_welcome_page($plugin)
	{
		if (plugin_basename(__FILE__) == $plugin) {
			wp_redirect(admin_url('admin.php?page=magical-addons'));
			die();
		}
	}


	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init()
	{
		if (!class_exists('magicalAddonsProMain')) {
			include_once MAGICAL_ADDON_PATH . '/includes/admin/helper/admin-info.php';
			require_once(MAGICAL_ADDON_PATH . '/includes/pro-widgets.php');
		}

		$mgadmin_notices = new mgAdminNotice();
		$enque_file = new mgAddonsEnqueueFile();
		$widget_init = new magicalWidgetInit();
		// Add Plugin actions
		add_action('elementor/elements/categories_registered', [$this, 'register_new_category']);
		add_action('elementor/editor/after_enqueue_styles', [$this, 'editor_widget_styles']);
		add_action('elementor/preview/enqueue_styles', [$this, 'editor_preview_widget_styles']);


		$is_plugin_activated = get_option('mg_plugin_activated');
		if ('yes' !== $is_plugin_activated) {
			update_option('mg_plugin_activated', 'yes');
		}
		$mg_install_date = get_option('mg_install_date');
		if (empty($mg_install_date)) {
			update_option('mg_install_date', current_time('mysql'));
		}
	}

	public function register_new_category($elements_manager)
	{
		$elements_manager->add_category('magical', [
			'title' => esc_html__('Magical Elements', 'magical-addons-for-elementor'),
			'icon' => 'fa fa-magic',
		]);
		$elements_manager->add_category('magical-pro', [
			'title' => esc_html__('Magical Pro Addons', 'magical-addons-for-elementor'),
			'icon' => 'fa fa-magic',
		]);

		$categories = $elements_manager->get_categories();

		// Define the desired order of the first few categories
		$first_categories = ['layout', 'basic', 'magical'];

		// Reorder the categories
		$ordered_keys = array_reduce(
			array_keys($categories),
			function ($carry, $key) use ($first_categories) {
				if (in_array($key, $first_categories)) {
					// If the category is in our $first_categories array, 
					// add it to the beginning of $carry in the order it appears in $first_categories
					$index = array_search($key, $first_categories);
					array_splice($carry, $index, 0, [$key]);
				} else {
					// For all other categories, add them to the end of $carry
					$carry[] = $key;
				}
				return $carry;
			},
			[]
		);

		// Create the reordered categories array
		$reordered_categories = [];
		foreach ($ordered_keys as $key) {
			if (isset($categories[$key])) {
				$reordered_categories[$key] = $categories[$key];
			}
		}

		// Replace the original categories with the reordered ones
		$reflection = new ReflectionClass($elements_manager);
		$property = $reflection->getProperty('categories');
		$property->setAccessible(true);
		$property->setValue($elements_manager, $reordered_categories);
	}




	function editor_widget_styles()
	{
		wp_enqueue_style('mg-editor-style',  plugins_url('/assets/css/mg-editor-style.css', __FILE__), array(), MAGICAL_ADDON_VERSION, 'all');
	}
	function editor_preview_widget_styles()
	{
		wp_enqueue_style('mg-editor-prev-style',  plugins_url('/assets/css/mg-editor-style-preview.css', __FILE__), array(), MAGICAL_ADDON_VERSION, 'all');
	}


	/**
	 * Call base file
	 *
	 * Include files 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function call_main_file()
	{
		//admin notice - load this first and unconditionally
		require_once(MAGICAL_ADDON_PATH . '/includes/basic/mg-admin-notice.php');
		new mgAdminNotice();

		if (!did_action('elementor/loaded')) {
			return;
		}

		// include file
		require_once(MAGICAL_ADDON_PATH . '/includes/magical-init-widgets.php');

		require_once(MAGICAL_ADDON_PATH . '/includes/basic/assets-managment.php');
		require_once(MAGICAL_ADDON_PATH . '/includes/functions.php');
		require_once(MAGICAL_ADDON_PATH . '/libs/class.settings-api.php');

		require_once(MAGICAL_ADDON_PATH . '/includes/admin/admin-page.php');
		require_once(MAGICAL_ADDON_PATH . '/includes/btn-icons-class.php');
		//	if (self::porcheck() == 'no') {
		//	}

		include_once MAGICAL_ADDON_PATH . '/includes/admin/helper/activation.php';
		require_once(MAGICAL_ADDON_PATH . '/libs/lib/index.php');

		require_once(MAGICAL_ADDON_PATH . '/includes/lions-icons.php');
		/* require_once(MAGICAL_ADDON_PATH . '/libs/tools/generate-icons-json.php'); */

		require_once(MAGICAL_ADDON_PATH . '/libs/tedit/header-footer/hf-main.php');
		// In your main plugin file
		require_once MAGICAL_ADDON_PATH . 'includes/extra/conditional-display/conditional-display.php';
		require_once MAGICAL_ADDON_PATH . 'includes/extra/custom-code/custom-code.php';
		require_once MAGICAL_ADDON_PATH . 'includes/extra/custom-attribute.php';
		require_once MAGICAL_ADDON_PATH . 'includes/extra/role-manager/role-manager.php';
	}
	//Admin pro link
	public function admin_adminpro_link($links)
	{
		$newlink = sprintf("<a target='_blank' href='%s'><span style='color:red;font-weight:bold'>%s</span></a>", esc_url('https://magic.wpcolors.net/pricing-plan/#mgpricing'), __('Upgrade Now', 'magical-addons-for-elementor'));
		if (!class_exists('magicalAddonsProMain')) {
			$links[] = $newlink;
		}
		return $links;
	}
}

Magical_Addons_Elementor::instance();
