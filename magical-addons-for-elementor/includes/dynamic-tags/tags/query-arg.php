<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Query_Arg_Tag
 * 
 * Fetches dynamic values from URL parameters ($_GET['param'])
 */
class Query_Arg_Tag extends Tag {

	public function get_name() {
		return 'mg-query-arg';
	}

	public function get_title() {
		return esc_html__('Request Parameter / Query Arg (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-meta-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
			TagsModule::NUMBER_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'param_name',
			[
				'label'       => esc_html__('Parameter Name', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. ref, user, plan',
				'label_block' => true,
				'description' => esc_html__('Enter the GET parameter name from the URL query string.', 'magical-addons-for-elementor'),
			]
		);
	}

	public function render() {
		$param = $this->get_settings('param_name');
		if (empty($param)) {
			return;
		}

		$value = isset($_GET[sanitize_key($param)]) ? sanitize_text_field(wp_unslash($_GET[sanitize_key($param)])) : '';
		echo esc_html($value);
	}
}
