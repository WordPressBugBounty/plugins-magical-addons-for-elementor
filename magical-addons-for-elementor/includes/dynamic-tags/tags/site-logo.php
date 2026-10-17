<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Site_Logo_Tag
 * 
 * Returns array with id and url of custom logo for image controls.
 */
class Site_Logo_Tag extends Data_Tag {

	public function get_name() {
		return 'mg-site-logo';
	}

	public function get_title() {
		return esc_html__('Site Logo (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-site-tags';
	}

	public function get_categories() {
		return [
			TagsModule::IMAGE_CATEGORY,
			TagsModule::MEDIA_CATEGORY,
		];
	}

	public function get_value(array $options = []) {
		$custom_logo_id = get_theme_mod('custom_logo');

		if ($custom_logo_id) {
			$src = wp_get_attachment_image_src($custom_logo_id, 'full');
			return [
				'id'  => $custom_logo_id,
				'url' => $src ? $src[0] : '',
			];
		}

		return [
			'id'  => '',
			'url' => \Elementor\Utils::get_placeholder_image_src(),
		];
	}
}
