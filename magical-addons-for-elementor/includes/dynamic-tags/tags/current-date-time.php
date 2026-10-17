<?php
namespace MagicalAddons\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class Current_Date_Time_Tag
 */
class Current_Date_Time_Tag extends Tag {

	public function get_name() {
		return 'mg-current-date-time';
	}

	public function get_title() {
		return esc_html__('Current Date / Time (Magical)', 'magical-addons-for-elementor');
	}

	public function get_group() {
		return 'magical-site-tags';
	}

	public function get_categories() {
		return [
			TagsModule::TEXT_CATEGORY,
			TagsModule::DATETIME_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'date_format',
			[
				'label'   => esc_html__('Format', 'magical-addons-for-elementor'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'          => esc_html__('Default (WordPress)', 'magical-addons-for-elementor'),
					'F j, Y'           => date('F j, Y'),
					'Y-m-d'            => date('Y-m-d'),
					'Y-m-d H:i'        => date('Y-m-d H:i'),
					'g:i a'            => date('g:i a'),
					'custom'           => esc_html__('Custom', 'magical-addons-for-elementor'),
				],
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label'     => esc_html__('Custom Format', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'Y-m-d H:i',
				'condition' => [
					'date_format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();
		$format = $settings['date_format'];
		if ('custom' === $format) {
			$format = !empty($settings['custom_format']) ? $settings['custom_format'] : 'Y-m-d H:i';
		} elseif ('default' === $format) {
			$format = get_option('date_format') . ' ' . get_option('time_format');
		}

		echo esc_html(wp_date($format));
	}
}
