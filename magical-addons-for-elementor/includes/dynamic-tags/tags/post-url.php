<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Post_URL_Tag
 */
class Post_URL_Tag extends Tag {

	public function get_name() {
		return 'mg-post-url';
	}

	public function get_title() {
		return esc_html__('Post URL / Permalink (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-post-tags';
	}

	public function get_categories() {
		return [
			TagsModule::URL_CATEGORY,
			TagsModule::TEXT_CATEGORY,
		];
	}

	protected function register_controls() {
		// Base class automatically opens 'settings' section and appends advanced section.
	}

	public function render() {
		echo esc_url(get_permalink());
	}
}
