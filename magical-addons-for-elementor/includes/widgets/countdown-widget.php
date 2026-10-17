<?php


class MgCountdown extends \Elementor\Widget_Base
{
	use mgProHelpLink;

	/**
	 * Get widget name.
	 *
	 * Retrieve Blank widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name()
	{
		return 'mgcountdown_widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Blank widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title()
	{
		return __('MG Countdown', 'magical-addons-for-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Blank widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon()
	{
		return 'eicon-countdown';
	}

	public function get_keywords()
	{
		return ['countdown', 'time', 'clock', 'mg'];
	}
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Blank widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories()
	{
		return ['magical'];
	}
	public function get_script_depends()
	{
		return [
			'mg-flipclock',
			'mg-flipclock-active',
		];
	}
	public function get_style_depends()
	{
		return [
			'mg-flipclock',
		];
	}
	/**
	 * Register Blank widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register Blank widget content ontrols.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_content_controls()
	{
		$this->start_controls_section(
			'mg_countdown',
			[
				'label' => __('Countdown options', 'magical-addons-for-elementor'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'display_type',
			[
				'label' => __('Display Type', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'timec' => __('Time countdown', 'magical-addons-for-elementor'),
					'genericc' => __('Number countdown', 'magical-addons-for-elementor'),
					'clock' => __('Clock', 'magical-addons-for-elementor'),
				],
				'default' => 'timec',
			]
		);
		$this->add_control(
			'clock_format',
			[
				'label' => __('Clock format', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'12' => __('12 Hour format', 'magical-addons-for-elementor'),
					'24' => __('24 Hour format', 'magical-addons-for-elementor'),
				],
				'default' => '12',
				'condition' => [
					'display_type' => 'clock',
				],
			]
		);
		$default_target_time = gmdate('Y-m-d H:i', strtotime('+1 year'));

		$this->add_control(
			'target_clock_time',
			[
				'label' => __('Set time', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'dynamic' => [
					'active' => true,
				],
				'default' => $default_target_time,
				'condition' => [
					'display_type' => 'timec',
				],
			]
		);
		$this->add_control(
			'genericc_countdown',
			[
				'label' => __('Countdown Form', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'display_type' => 'genericc',
				],
				'default' => '500',

			]
		);
		$this->add_control(
			'countdown_timing',
			[
				'label' => __('Countdown timing', 'magical-addons-for-elementor'),
				'description' => __('Set countdown timing by millisecond. default set 1 second (1000)', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'display_type' => 'genericc',
				],
				'default' => '1000',

			]
		);
		$this->add_control(
			'mg_countdown_label_show',
			[
				'label' => __('Show Label?', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block'  => __('Show', 'magical-addons-for-elementor'),
					'none' => __('Hide', 'magical-addons-for-elementor'),
				],
				'condition' => [
					'display_type' => 'timec',
				],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper .flip-clock-label' => 'display: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'mgcountdown_align',
			[
				'label' => __('Countdown Alignment', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'magical-addons-for-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'magical-addons-for-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'magical-addons-for-elementor'),
						'icon' => 'eicon-text-align-right',
					],

				],
				'default' => 'center',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'mg_countdown_label',
			[
				'label' => __('Countdown Label', 'magical-addons-for-elementor'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'display_type' => 'timec',
					'mg_countdown_label_show' => 'block',
				],
			]
		);
		$this->add_control(
			'mg_days_label',
			[
				'label'       => __('Days Label', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'input_type'  => 'text',
				'placeholder' => __('Days', 'magical-addons-for-elementor'),
				'default'     => __('Days', 'magical-addons-for-elementor'),
				'label_block'     => true,

			]
		);
		$this->add_control(
			'mg_hours_label',
			[
				'label'       => __('Hours Label', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'input_type'  => 'text',
				'placeholder' => __('Hours', 'magical-addons-for-elementor'),
				'default'     => __('Hours', 'magical-addons-for-elementor'),
				'label_block'     => true,

			]
		);
		$this->add_control(
			'mg_minutes_label',
			[
				'label'       => __('Minutes Label', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'input_type'  => 'text',
				'placeholder' => __('Minutes', 'magical-addons-for-elementor'),
				'default'     => __('Minutes', 'magical-addons-for-elementor'),
				'label_block'     => true,

			]
		);
		$this->add_control(
			'mg_seconds_label',
			[
				'label'       => __('Seconds Label', 'magical-addons-for-elementor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'input_type'  => 'text',
				'placeholder' => __('Seconds', 'magical-addons-for-elementor'),
				'default'     => __('Seconds', 'magical-addons-for-elementor'),
				'label_block'     => true,

			]
		);
		$this->end_controls_section();
		$this->link_pro_added();
	}

	/**
	 * Register Blank widget style ontrols.
	 *
	 * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_style_controls()
	{

		$this->start_controls_section(
			'mg_countdown_style',
			[
				'label' => __('Countdown Number Style', 'magical-addons-for-elementor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'num_bgcolor',
			[
				'label'     => __('Number background color', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#222',
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul li a div div.inn' => 'background-color: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'num_color',
			[
				'label'     => __('Number color', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ccc',
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul li a div div.inn' => 'color: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'numdiv_color',
			[
				'label'     => __('Number divider color', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul li a div.up:after' => 'background-color: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'numdot_color',
			[
				'label'     => __('Number dot color', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .flip-clock-dot' => 'background-color: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'mg_num_height',
			[
				'label' => __('Number Height', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul.flip' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'mg_num_width',
			[
				'label' => __('Number Width', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul.flip' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mg_num__shadow',
				'selector' => '{{WRAPPER}} .flip-clock-wrapper ul.flip'
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .mga-clock ul.flip li a .inn',
			]
		);
		$this->add_responsive_control(
			'mg_flip_num_padding',
			[
				'label' => __('Padding', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul.flip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'mg_flip_num_margin',
			[
				'label' => __('Margin', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-wrapper ul.flip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'mg_clabel_style',
			[
				'label' => __('Countdown Label Style', 'magical-addons-for-elementor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_type!' => 'genericc',
				],
			]
		);
		$this->add_control(
			'mg_label_color',
			[
				'label'     => __('Label Text Color', 'magical-addons-for-elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .flip-clock-meridium a, {{WRAPPER}} .flip-clock-divider .flip-clock-label' => 'color: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'mg_flip_label_margin',
			[
				'label' => __('Margin', 'magical-addons-for-elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .flip-clock-meridium a, {{WRAPPER}} .flip-clock-divider .flip-clock-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .flip-clock-wrapper,.flip-clock-wrapper ul,.flip-clock-wrapper ul li a div,.flip-clock-wrapper ul li a div',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Blank widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$display_type = !empty($settings['display_type']) ? $settings['display_type'] : 'timec';
		$clock_format = !empty($settings['clock_format']) ? $settings['clock_format'] : '12';
		$target_clock_time = !empty($settings['target_clock_time']) ? $settings['target_clock_time'] : '';

		// If empty or set to the old hardcoded 2024 date, automatically default to 1 year in the future
		if (empty($target_clock_time) || strpos($target_clock_time, '2024-') === 0) {
			$target_clock_time = gmdate('Y-m-d H:i', strtotime('+1 year'));
		}

		$genericc_countdown = isset($settings['genericc_countdown']) ? $settings['genericc_countdown'] : '500';
		$countdown_timing = !empty($settings['countdown_timing']) ? $settings['countdown_timing'] : '1000';

		$days_label = !empty($settings['mg_days_label']) ? $settings['mg_days_label'] : esc_html__('Days', 'magical-addons-for-elementor');
		$hours_label = !empty($settings['mg_hours_label']) ? $settings['mg_hours_label'] : esc_html__('Hours', 'magical-addons-for-elementor');
		$minutes_label = !empty($settings['mg_minutes_label']) ? $settings['mg_minutes_label'] : esc_html__('Minutes', 'magical-addons-for-elementor');
		$seconds_label = !empty($settings['mg_seconds_label']) ? $settings['mg_seconds_label'] : esc_html__('Seconds', 'magical-addons-for-elementor');
?>
		<div class="mga-clock" data-display-type="<?php echo esc_attr($display_type); ?>" data-clock-format="<?php echo esc_attr($clock_format); ?>" data-target-time="<?php echo esc_attr($target_clock_time); ?>" data-countdown="<?php echo esc_attr($genericc_countdown); ?>" data-timing="<?php echo esc_attr($countdown_timing); ?>" data-days-label="<?php echo esc_attr($days_label); ?>" data-hours-label="<?php echo esc_attr($hours_label); ?>" data-minutes-label="<?php echo esc_attr($minutes_label); ?>" data-seconds-label="<?php echo esc_attr($seconds_label); ?>">
		</div>
	<?php
	}

	/**
	 * Render Blank widget output on the frontend.
	 *
	 * Written in JS and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	/*protected function content_template() {}*/

	protected function content_template()
	{
		$default_target = gmdate('Y-m-d H:i', strtotime('+1 year'));
	?>
		<#
		var targetTime = settings.target_clock_time;
		if ( ! targetTime || targetTime.indexOf('2024-') === 0 ) {
			targetTime = '<?php echo esc_js($default_target); ?>';
		}
		var daysLabel = settings.mg_days_label || '<?php echo esc_js(__('Days', 'magical-addons-for-elementor')); ?>';
		var hoursLabel = settings.mg_hours_label || '<?php echo esc_js(__('Hours', 'magical-addons-for-elementor')); ?>';
		var minutesLabel = settings.mg_minutes_label || '<?php echo esc_js(__('Minutes', 'magical-addons-for-elementor')); ?>';
		var secondsLabel = settings.mg_seconds_label || '<?php echo esc_js(__('Seconds', 'magical-addons-for-elementor')); ?>';
		#>
		<div class="mga-clock" data-display-type="{{ settings.display_type }}" data-clock-format="{{ settings.clock_format }}" data-target-time="{{ targetTime }}" data-countdown="{{ settings.genericc_countdown }}" data-timing="{{ settings.countdown_timing }}" data-days-label="{{{ daysLabel }}}" data-hours-label="{{{ hoursLabel }}}" data-minutes-label="{{{ minutesLabel }}}" data-seconds-label="{{{ secondsLabel }}}"></div>
<?php
	}
}
