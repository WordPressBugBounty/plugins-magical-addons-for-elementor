<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Author_Info_Tag
 */
class Author_Info_Tag extends Tag {

	public function get_name() {
		return 'mg-author-info';
	}

	public function get_title() {
		return esc_html__('Author Info (Magical)', 'magical-addons-for-elementor');
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
		$this->add_control(
			'author_field',
			[
				'label'   => esc_html__('Field', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'display_name',
				'options' => [
					'display_name' => esc_html__('Display Name', 'magical-addons-for-elementor'),
					'first_name'   => esc_html__('First Name', 'magical-addons-for-elementor'),
					'last_name'    => esc_html__('Last Name', 'magical-addons-for-elementor'),
					'description'  => esc_html__('Bio / Description', 'magical-addons-for-elementor'),
					'user_email'   => esc_html__('Email', 'magical-addons-for-elementor'),
					'user_url'     => esc_html__('Website', 'magical-addons-for-elementor'),
				],
			]
		);
	}

	public function render() {
		$field = $this->get_settings('author_field');
		$value = get_the_author_meta($field);
		echo esc_html($value);
	}
}
