<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Archive_Title_Tag
 */
class Archive_Title_Tag extends Tag {

	public function get_name() {
		return 'mg-archive-title';
	}

	public function get_title() {
		return esc_html__('Archive Title (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-archive-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label'   => esc_html__('Include Context (e.g. "Category: ")', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
	}

	public function render() {
		$include_context = 'yes' === $this->get_settings('include_context');
		if ($include_context) {
			echo wp_kses_post(get_the_archive_title());
		} else {
			// Strip prefix
			$title = get_the_archive_title();
			$parts = explode(': ', $title, 2);
			echo esc_html(isset($parts[1]) ? $parts[1] : $title);
		}
	}
}
