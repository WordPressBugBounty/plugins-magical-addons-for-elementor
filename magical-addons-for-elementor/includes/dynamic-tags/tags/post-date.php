<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Post_Date_Tag
 */
class Post_Date_Tag extends Tag {

	public function get_name() {
		return 'mg-post-date';
	}

	public function get_title() {
		return esc_html__('Post Date (Magical)', 'magical-addons-for-elementor');
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
			'date_type',
			[
				'label'   => esc_html__('Type', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'published',
				'options' => [
					'published' => esc_html__('Post Published', 'magical-addons-for-elementor'),
					'modified'  => esc_html__('Post Modified', 'magical-addons-for-elementor'),
				],
			]
		);

		$this->add_control(
			'format',
			[
				'label'   => esc_html__('Format', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__('Default (WordPress)', 'magical-addons-for-elementor'),
					'F j, Y'  => date('F j, Y'),
					'Y-m-d'   => date('Y-m-d'),
					'm/d/Y'   => date('m/d/Y'),
					'd/m/Y'   => date('d/m/Y'),
					'custom'  => esc_html__('Custom', 'magical-addons-for-elementor'),
				],
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label'     => esc_html__('Custom Format', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'F j, Y',
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();
		$format = $settings['format'];
		if ('custom' === $format) {
			$format = !empty($settings['custom_format']) ? $settings['custom_format'] : get_option('date_format');
		} elseif ('default' === $format) {
			$format = get_option('date_format');
		}

		if ('modified' === $settings['date_type']) {
			$date = get_the_modified_date($format);
		} else {
			$date = get_the_date($format);
		}

		echo esc_html($date);
	}
}
