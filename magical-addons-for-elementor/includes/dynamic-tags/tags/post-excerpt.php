<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Post_Excerpt_Tag
 */
class Post_Excerpt_Tag extends Tag {

	public function get_name() {
		return 'mg-post-excerpt';
	}

	public function get_title() {
		return esc_html__('Post Excerpt (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-post-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
		];
	}

	protected function register_controls() {
		// Base class automatically opens 'settings' section and appends advanced section.
	}

	public function render() {
		$excerpt = get_the_excerpt();
		if (empty($excerpt)) {
			$post = get_post();
			if ($post) {
				$excerpt = wp_trim_words($post->post_content, 25, '...');
			}
		}
		echo wp_kses_post($excerpt);
	}
}
