<?php

/**
 * Custom Attributes class for Elementor widgets
 * 
 * Adds custom attributes functionality to the Advanced tab of Elementor widgets,
 * similar to how Elementor Pro handles this feature.
 */
class Magical_Elementor_Custom_Attributes
{

    /**
     * Initialize the class
     */
    public function __construct()
    {
        // Add custom attributes section to Advanced tab
        add_action('elementor/element/after_section_end', [$this, 'add_custom_attributes_section'], 10, 3);

        // Render the custom attributes on the frontend
        add_action('elementor/frontend/before_render', [$this, 'before_render_attributes'], 10);
        add_action('elementor/frontend/widget/before_render', [$this, 'render_custom_attributes'], 10);
    }

    /**
     * Adds custom attributes section to widgets Advanced tab
     *
     * @param \Elementor\Element_Base $element
     * @param string $section_id
     * @param array $args
     */
    public function add_custom_attributes_section($element, $section_id, $args)
    {
        if ($section_id === 'section_custom_css' || $section_id === 'section_custom_css_pro') {
            $element->start_controls_section(
                'section_magical_custom_attributes',
                [
                    'label' => esc_html__('Magical Custom Attributes', 'magical-addons-for-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

            $element->add_control(
                'magical_custom_attributes_description',
                [
                    'raw' => esc_html__('Add custom attributes to the wrapper element.', 'magical-addons-for-elementor'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                ]
            );
            // Add notice that attributes only work in frontend
            $element->add_control(
                'magical_custom_attributes_notice',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info">' .
                        esc_html__('Note: Custom attributes will only be visible in the frontend, not in the editor.', 'magical-addons-for-elementor') .
                        '</div>',
                    'separator' => 'before',
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'key',
                [
                    'label' => esc_html__('Key', 'magical-addons-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => 'data-custom',
                    'label_block' => true,
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $repeater->add_control(
                'value',
                [
                    'label' => esc_html__('Value', 'magical-addons-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => 'value',
                    'label_block' => true,
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $element->add_control(
                'magical_custom_attributes',
                [
                    'label' => esc_html__('Attributes', 'magical-addons-for-elementor'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ key }}}',
                    'prevent_empty' => false,
                ]
            );

            $element->end_controls_section();
        }
    }

    /**
     * Handle attributes for all elements
     *
     * @param \Elementor\Element_Base $element
     */
    public function before_render_attributes($element)
    {
        $settings = $element->get_settings_for_display();

        if (empty($settings['magical_custom_attributes'])) {
            return;
        }

        $this->add_attributes_to_element($element, $settings);
    }

    /**
     * Render the custom attributes on the frontend for widgets
     *
     * @param \Elementor\Widget_Base $widget
     */
    public function render_custom_attributes($widget)
    {
        $settings = $widget->get_settings_for_display();

        if (empty($settings['magical_custom_attributes'])) {
            return;
        }

        $this->add_attributes_to_element($widget, $settings);
    }

    /**
     * Add attributes to element
     *
     * @param \Elementor\Element_Base $element
     * @param array $settings
     */
    private function add_attributes_to_element($element, $settings)
    {
        if (empty($settings['magical_custom_attributes'])) {
            return;
        }

        $custom_attributes = [];

        foreach ($settings['magical_custom_attributes'] as $attribute) {
            if (empty($attribute['key'])) {
                continue;
            }

            // Do not sanitize the key with sanitize_key() as it removes dashes and non-alphanumeric chars
            // Instead, just use esc_attr to maintain original attribute format (like data-* attributes)
            $key = esc_attr($attribute['key']);
            $value = esc_attr($attribute['value']);

            $custom_attributes[$key] = $value;
        }

        if (!empty($custom_attributes)) {
            // Add the custom attributes to the element's wrapper
            foreach ($custom_attributes as $attribute_key => $attribute_value) {
                $element->add_render_attribute('_wrapper', $attribute_key, $attribute_value);
            }
        }
    }
}

// Initialize the class
function magical_init_custom_attributes()
{
    new Magical_Elementor_Custom_Attributes();
}
add_action('elementor/init', 'magical_init_custom_attributes');
