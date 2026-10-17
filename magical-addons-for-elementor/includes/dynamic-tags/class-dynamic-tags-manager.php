<?php
namespace MagicalAddons\DynamicTags;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Manager
 * 
 * Registers custom Dynamic Tags and Groups for Elementor Free users.
 */
class Manager {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('elementor/dynamic_tags/register', [$this, 'register_tags']);
	}

	/**
	 * Register groups and tags.
	 * 
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager
	 */
	public function register_tags($dynamic_tags_manager) {
		// 1. Register Magical Groups
		$dynamic_tags_manager->register_group('magical-post-tags', [
			'title' => esc_html__('Magical: Post & Page', 'magical-addons-for-elementor'),
		]);

		$dynamic_tags_manager->register_group('magical-meta-tags', [
			'title' => esc_html__('Magical: Custom Fields & Query', 'magical-addons-for-elementor'),
		]);

		$dynamic_tags_manager->register_group('magical-site-tags', [
			'title' => esc_html__('Magical: Site Info', 'magical-addons-for-elementor'),
		]);

		$dynamic_tags_manager->register_group('magical-user-tags', [
			'title' => esc_html__('Magical: User Info', 'magical-addons-for-elementor'),
		]);

		$dynamic_tags_manager->register_group('magical-archive-tags', [
			'title' => esc_html__('Magical: Archive & Terms', 'magical-addons-for-elementor'),
		]);

		// 2. Load tag classes
		require_once __DIR__ . '/tags/post-title.php';
		require_once __DIR__ . '/tags/post-excerpt.php';
		require_once __DIR__ . '/tags/post-date.php';
		require_once __DIR__ . '/tags/post-id.php';
		require_once __DIR__ . '/tags/post-url.php';
		require_once __DIR__ . '/tags/author-info.php';
		require_once __DIR__ . '/tags/post-featured-image.php';
		require_once __DIR__ . '/tags/post-custom-field.php';
		require_once __DIR__ . '/tags/query-arg.php';
		require_once __DIR__ . '/tags/site-title.php';
		require_once __DIR__ . '/tags/site-tagline.php';
		require_once __DIR__ . '/tags/site-url.php';
		require_once __DIR__ . '/tags/site-logo.php';
		require_once __DIR__ . '/tags/current-date-time.php';
		require_once __DIR__ . '/tags/user-info.php';
		require_once __DIR__ . '/tags/archive-title.php';

		// 3. Register tag instances
		$dynamic_tags_manager->register(new Post_Title_Tag());
		$dynamic_tags_manager->register(new Post_Excerpt_Tag());
		$dynamic_tags_manager->register(new Post_Date_Tag());
		$dynamic_tags_manager->register(new Post_ID_Tag());
		$dynamic_tags_manager->register(new Post_URL_Tag());
		$dynamic_tags_manager->register(new Author_Info_Tag());
		$dynamic_tags_manager->register(new Post_Featured_Image_Tag());
		$dynamic_tags_manager->register(new Post_Custom_Field_Tag());
		$dynamic_tags_manager->register(new Query_Arg_Tag());
		$dynamic_tags_manager->register(new Site_Title_Tag());
		$dynamic_tags_manager->register(new Site_Tagline_Tag());
		$dynamic_tags_manager->register(new Site_URL_Tag());
		$dynamic_tags_manager->register(new Site_Logo_Tag());
		$dynamic_tags_manager->register(new Current_Date_Time_Tag());
		$dynamic_tags_manager->register(new User_Info_Tag());
		$dynamic_tags_manager->register(new Archive_Title_Tag());
	}
}
