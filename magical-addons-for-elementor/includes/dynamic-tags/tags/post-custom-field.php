<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Post_Custom_Field_Tag
 * 
 * Fetches any post meta / ACF field for the current post.
 */
class Post_Custom_Field_Tag extends Tag {

	public function get_name() {
		return 'mg-post-custom-field';
	}

	public function get_title() {
		return esc_html__('Post Custom Field / ACF (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-meta-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
			TagsModule::URL_CATEGORY,
			TagsModule::POST_META_CATEGORY,
			TagsModule::NUMBER_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'custom_key',
			[
				'label'       => esc_html__('Custom Key / Meta Key', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'my_custom_meta_key',
				'label_block' => true,
				'description' => esc_html__('Enter the custom field (meta key) name, such as an ACF field name or WordPress post meta key.', 'magical-addons-for-elementor'),
			]
		);
	}

	public function render() {
		$key = $this->get_settings('custom_key');
		if (empty($key)) {
			return;
		}

		$post_id = get_the_ID();
		if (!$post_id) {
			return;
		}

		$value = '';

		// If ACF is active, check get_field first for formatted values
		if (function_exists('get_field')) {
			$acf_value = get_field($key, $post_id);
			if (!empty($acf_value)) {
				if (is_array($acf_value)) {
					$value = implode(', ', array_map('strval', $acf_value));
				} else {
					$value = (string) $acf_value;
				}
			}
		}

		// Fallback to standard get_post_meta
		if (empty($value)) {
			$meta_val = get_post_meta($post_id, sanitize_key($key), true);
			if (!empty($meta_val)) {
				if (is_array($meta_val)) {
					$value = implode(', ', array_map('strval', $meta_val));
				} else {
					$value = (string) $meta_val;
				}
			}
		}

		echo wp_kses_post($value);
	}
}
