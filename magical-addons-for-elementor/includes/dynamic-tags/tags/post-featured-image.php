<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Post_Featured_Image_Tag
 * 
 * Returns array with id and url for image/media controls.
 */
class Post_Featured_Image_Tag extends Data_Tag {

	public function get_name() {
		return 'mg-post-featured-image';
	}

	public function get_title() {
		return esc_html__('Featured Image (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-post-tags';
	}

	public function get_categories() {
		return [
			TagsModule::IMAGE_CATEGORY,
			TagsModule::MEDIA_CATEGORY,
		];
	}

	public function get_value(array $options = []) {
		$thumbnail_id = get_post_thumbnail_id();

		if ($thumbnail_id) {
			$src = wp_get_attachment_image_src($thumbnail_id, 'full');
			return [
				'id'  => $thumbnail_id,
				'url' => $src ? $src[0] : '',
			];
		}

		// Fallback placeholder image if no thumbnail set
		return [
			'id'  => '',
			'url' => \Elementor\Utils::get_placeholder_image_src(),
		];
	}
}
