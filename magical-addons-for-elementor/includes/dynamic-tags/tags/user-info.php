<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class User_Info_Tag
 */
class User_Info_Tag extends Tag {

	public function get_name() {
		return 'mg-user-info';
	}

	public function get_title() {
		return esc_html__('User Info (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-user-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'user_field',
			[
				'label'   => esc_html__('Field', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'display_name',
				'options' => [
					'display_name' => esc_html__('Display Name', 'magical-addons-for-elementor'),
					'user_login'   => esc_html__('Username', 'magical-addons-for-elementor'),
					'first_name'   => esc_html__('First Name', 'magical-addons-for-elementor'),
					'last_name'    => esc_html__('Last Name', 'magical-addons-for-elementor'),
					'user_email'   => esc_html__('Email', 'magical-addons-for-elementor'),
					'description'  => esc_html__('Bio / Description', 'magical-addons-for-elementor'),
					'ID'           => esc_html__('User ID', 'magical-addons-for-elementor'),
				],
			]
		);
	}

	public function render() {
		if (!is_user_logged_in()) {
			return;
		}

		$current_user = wp_get_current_user();
		$field = $this->get_settings('user_field');

		$value = '';
		if ('first_name' === $field) {
			$value = get_user_meta($current_user->ID, 'first_name', true);
		} elseif ('last_name' === $field) {
			$value = get_user_meta($current_user->ID, 'last_name', true);
		} elseif ('description' === $field) {
			$value = get_user_meta($current_user->ID, 'description', true);
		} elseif (isset($current_user->$field)) {
			$value = $current_user->$field;
		}

		echo esc_html($value);
	}
}
