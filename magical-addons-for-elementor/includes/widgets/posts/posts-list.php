<?php


class mgPostListWidget extends \Elementor\Widget_Base
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
        return 'mgposts_list';
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
        return __('MG Posts List', 'magical-addons-for-elementor');
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
        return 'eicon-post-list';
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
    function register_content_controls()
    {

        $this->start_controls_section(
            'mgpl_query',
            [
                'label' => esc_html__('Posts Query', 'magical-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'mgpl_posts_filter',
            [
                'label' => esc_html__('Filter By', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent' => esc_html__('Recent Posts', 'magical-addons-for-elementor'),
                    /*'featured' => esc_html__( 'Popular Posts', 'magical-addons-for-elementor' ),*/
                    'random_order' => esc_html__('Random Posts', 'magical-addons-for-elementor'),
                    'show_byid' => esc_html__('Show By Id', 'magical-addons-for-elementor'),
                    'show_byid_manually' => esc_html__('Add ID Manually', 'magical-addons-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'mgpl_product_id',
            [
                'label' => __('Select posts', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgaddons_post_name(),
                'condition' => [
                    'mgpl_posts_filter' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mgpl_product_ids_manually',
            [
                'label' => __('posts IDs', 'magical-addons-for-elementor'),
                'description' => __('Separate IDs with commas', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'mgpl_posts_filter' => 'show_byid_manually',
                ]
            ]
        );

        $this->add_control(
            'mgpl_posts_count',
            [
                'label'   => __('posts Limit', 'magical-addons-for-elementor'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'mgpl_grid_categories',
            [
                'label' => esc_html__('posts Categories', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgaddons_taxonomy_list(),
                'condition' => [
                    'mgpl_posts_filter!' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mgpl_custom_order',
            [
                'label' => esc_html__('Custom order', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Orderby', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'          => esc_html__('None', 'magical-addons-for-elementor'),
                    'ID'            => esc_html__('ID', 'magical-addons-for-elementor'),
                    'date'          => esc_html__('Date', 'magical-addons-for-elementor'),
                    'name'          => esc_html__('Name', 'magical-addons-for-elementor'),
                    'title'         => esc_html__('Title', 'magical-addons-for-elementor'),
                    'comment_count' => esc_html__('Comment count', 'magical-addons-for-elementor'),
                    'rand'          => esc_html__('Random', 'magical-addons-for-elementor'),
                ],
                'condition' => [
                    'mgpl_custom_order' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('order', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC'  => esc_html__('Descending', 'magical-addons-for-elementor'),
                    'ASC'   => esc_html__('Ascending', 'magical-addons-for-elementor'),
                ],
                'condition' => [
                    'mgpl_custom_order' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        // posts Content
        $this->start_controls_section(
            'mgpl_layout',
            [
                'label' => esc_html__('List Layout', 'magical-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'mgpl_post_style',
            [
                'label'   => __('List Style', 'magical-addons-for-elementor'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'   => __('Style One', 'magical-addons-for-elementor'),
                    '2'  => __('Style Two', 'magical-addons-for-elementor'),
                ]
            ]
        );
        $this->add_control(
            'mgpl_post_img_position',
            [
                'label' => __('Image position', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-addons-for-elementor'),
                        'icon' => 'fas fa-arrow-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-addons-for-elementor'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => 'left',
                'toggle' => false,
                'prefix_class' => 'mg-card-img-',
                'style_transfer' => true,
            ]

        );
        $this->end_controls_section();
        // posts Content
        $this->start_controls_section(
            'mgpl_content',
            [
                'label' => esc_html__('Content Settings', 'magical-addons-for-elementor'),
            ]
        );


        $this->add_control(
            'mgpl_post_img_show',
            [
                'label'     => __('Show Posts image', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpl_show_title',
            [
                'label'     => __('Show posts Title', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpl_crop_title',
            [
                'label'   => __('Crop Title By Word', 'magical-addons-for-elementor'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 5,
                'condition' => [
                    'mgpl_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpl_title_tag',
            [
                'label' => __('Title HTML Tag', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h4',
                'condition' => [
                    'mgpl_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpl_desc_show',
            [
                'label'     => __('Show posts Description', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'

            ]
        );
        $this->add_control(
            'mgpl_crop_desc',
            [
                'label'   => __('Crop Description By Word', 'magical-addons-for-elementor'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 20,
                'condition' => [
                    'mgpl_desc_show' => 'yes',
                ]

            ]
        );

        $this->add_responsive_control(
            'mgpl_content_align',
            [
                'label' => __('Alignment', 'magical-addons-for-elementor'),
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
                'default' => 'left',
                'classes' => 'flex-{{VALUE}}',
                'selectors' => [
                    '{{WRAPPER}} #mglp-items .mg-card-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mgpl_meta_section',
            [
                'label' => __('Posts Meta', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => '',
            ]
        );
        $this->add_control(
            'mgpl_date_show',
            [
                'label'     => __('Show Date', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpl_category_show',
            [
                'label'     => __('Show Category', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpl_cat_type',
            [
                'label' => __('Category type', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'all' => __('Show all categories', 'magical-addons-for-elementor'),
                    'one' => __('Show first category', 'magical-addons-for-elementor'),
                ],
                'default' => 'one',
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_author_show',
            [
                'label'     => __('Show Author', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpl_tag_show',
            [
                'label'     => __('Show Tags', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );
        $this->add_control(
            'mgpl_comment_show',
            [
                'label'     => __('Show Comment', 'magical-addons-for-elementor'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'mgpl_button',
            [
                'label' => __('Button', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mgpl_post_btn',
            [
                'label' => __('Use post link?', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-addons-for-elementor'),
                'label_off' => __('No', 'magical-addons-for-elementor'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpl_link_type',
            [
                'label' => __('Link type', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'link1' => 'Link style one',
                    'link2' => 'Link style two',
                    'btn' => 'Button',
                ],
                'default' => 'link2',
            ]
        );

        $this->add_control(
            'mgpl_btn_title',
            [
                'label'       => __('Link Title', 'magical-addons-for-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Read More', 'magical-addons-for-elementor'),
                'default'     => __('Read More', 'magical-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'mgpl_btn_target',
            [
                'label' => __('Link Target', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '_self' => 'self',
                    '_blank' => 'Blank',
                ],
                'default' => '_self',
            ]
        );

        $this->add_control(
            'mgpl_usebtn_icon',
            [
                'label' => __('Use icon', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-addons-for-elementor'),
                'label_off' => __('No', 'magical-addons-for-elementor'),
                'default' => '',
            ]
        );

        $this->add_control(
            'mgpl_btn_icon',
            [
                'label' => __('Choose Icon', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'solid',
                ],
                'condition' => [
                    'mgpl_usebtn_icon' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_btn_icon_position',
            [
                'label' => __('Icon Position', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-addons-for-elementor'),
                        'icon' => 'fas fa-arrow-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-addons-for-elementor'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => 'right',
                'condition' => [
                    'mgpl_usebtn_icon' => 'yes',
                ],

            ]
        );
        $this->add_responsive_control(
            'mgpl_cardbtn_iconspace',
            [
                'label' => __('Icon Spacing', 'magical-addons-for-elementor'),
                'type' => Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'condition' => [
                    'mgpl_usebtn_icon' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-card-btn i.left,{{WRAPPER}} .mg-card-btn .left i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mg-card-btn i.right, {{WRAPPER}} .mg-card-btn .right i' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mg-card-btn svg.left,{{WRAPPER}} .mg-card-btn .left svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mg-card-btn svg.right, {{WRAPPER}} .mg-card-btn .right svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
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
            'mgpl_style',
            [
                'label' => __('Layout style', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpl_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpl_bg_color',
                'label' => esc_html__('Background', 'magical-addons-for-elementor'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mg-post-list',
            ]
        );

        $this->add_control(
            'mgpl_border_radius',
            [
                'label' => __('Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_content_border',
                'selector' => '{{WRAPPER}} .mg-post-list',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpl_content_shadow',
                'selector' => '{{WRAPPER}} .mg-post-list',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgpl_img_style',
            [
                'label' => __('Image style', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpl_post_img_show' => 'yes',
                ]
            ]
        );
        $this->add_responsive_control(
            'image_width_set',
            [
                'label' => __('Width', 'magical-addons-for-elementor'),
                'type' =>  \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 80,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-card-img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.mg-card-img-right .mg-card-text, {{WRAPPER}}.mg-card-img-left .mg-card-text' => 'flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); max-width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-addons-for-elementor'),
                'label_off' => __('Off', 'magical-addons-for-elementor'),
                'default' => 'yes',
            ]
        );
        $this->add_responsive_control(
            'mgpl_img_height',
            [
                'label' => __('Image Height', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'mgpl_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-post-img figure img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_imgbg_height',
            [
                'label' => __('Image div Height', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'mgpl_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-post-img figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_img_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-post-img, {{WRAPPER}} .mg-post-list .mg-post-img figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_img_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-post-img figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mgpl_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-post-img figure img, -img.mg-post-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpl_img_bgcolor',
                'label' => esc_html__('Background', 'magical-addons-for-elementor'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mg-post-list .mg-post-img, {{WRAPPER}} .mg-post-list .mg-post-img figure img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_img_border',
                'selector' => '{{WRAPPER}} .mg-post-list .mg-post-img figure img',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpl_title_style',
            [
                'label' => __('posts Title', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpl_title_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mgp-ptitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_title_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mgp-ptitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_title_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mgp-title-link, .mg-post-list .mgp-ptitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_title_bgcolor',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mgp-ptitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_descb_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mgp-ptitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_title_typography',
                'selector' => '{{WRAPPER}} .mg-post-list .mgp-ptitle',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpl_description_style',
            [
                'label' => __('Description', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpl_description_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-card-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_description_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-card-text p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_description_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-card-text p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_description_bgcolor',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-card-text p' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpl_description_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list .mg-card-text p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_description_typography',
                'selector' => '{{WRAPPER}} .mg-post-list .mg-card-text p',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgpl_meta_style',
            [
                'label' => __('Posts Meta', 'magical-addons-for-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'mgpl_meta_cat',
            [
                'label' => __('Category style', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_meta_cat_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-post-cats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_meta_cat_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-post-cats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_cat_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-post-cats' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_cat_bgcolor',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-post-cats' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_meta_cat_typography',
                'selector' => '{{WRAPPER}} .mgp-post-cats',
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_cat_border',
                'selector' => '{{WRAPPER}} .mgp-post-cats',
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_cat_border_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-post-cats' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_category_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_author',
            [
                'label' => __('Posts Author', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_meta_author_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-meta .mgp-author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_meta_author_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-meta .mgp-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_author_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-meta .mgp-author a, {{WRAPPER}} .mgp-meta .mgp-author i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgp-meta .mgp-author svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_author_bgcolor',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-meta .mgp-author' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_meta_author_typography',
                'selector' => '{{WRAPPER}} .mgp-meta .mgp-author, {{WRAPPER}} .mgp-meta .mgp-author a',
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_author_border',
                'selector' => '{{WRAPPER}} .mgp-meta .mgp-author',
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_author_border_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-meta .mgp-author' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_author_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_meta_date',
            [
                'label' => __('Date Style', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_meta_date_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_meta_date_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_date_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-time' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgp-time i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgp-time svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_date_bgcolor',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-time' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_meta_date_typography',
                'selector' => '{{WRAPPER}} .mgp-time',
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mg_post_list_date_icon_time_size',
            [
                'label' => __('Icon Size', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgp-time i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mgp-time svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_date_border',
                'selector' => '{{WRAPPER}} .mgp-time',
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_author_date_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-time' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_date_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_tag',
            [
                'label' => __('Tags style', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mgpl_tag_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_meta_tag_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpg-tags-links a, {{WRAPPER}} .mpg-tags-links i, {{WRAPPER}} .mpg-tags-links svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_tag_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_tag_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpg-tags-links a, {{WRAPPER}} .mpg-tags-links i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mpg-tags-links svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_tag_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgpl_meta_comment',
            [
                'label' => __('Comment style', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mgpl_comment_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpl_meta_comment_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-comment a, {{WRAPPER}} .mpg-tags-links i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpl_comment_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpl_meta_comment_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-comment a, {{WRAPPER}} .mgp-comment i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgp-comment svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mgpl_comment_show' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mgpl_btn_style',
            [
                'label' => __('Button', 'magical-addons-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpl_btn_padding',
            [
                'label' => __('Padding', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpl_btn_margin',
            [
                'label' => __('Margin', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpl_btn_typography',
                'selector' => '{{WRAPPER}} .mg-post-list a.mg-card-btn',
            ]
        );

        $this->add_responsive_control(
            'mg_post_list_button_icon_size',
            [
                'label' => __('Icon Size', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpl_btn_border',
                'selector' => '{{WRAPPER}} .mg-post-list a.mg-card-btn',
            ]
        );

        $this->add_control(
            'mgpl_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpl_btn_box_shadow',
                'selector' => '{{WRAPPER}} .mg-post-list a.mg-card-btn',
            ]
        );
        $this->add_control(
            'mgpl_button_color',
            [
                'label' => __('Button color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('mgpl_btn_tabs');

        $this->start_controls_tab(
            'mgpl_btn_normal_style',
            [
                'label' => __('Normal', 'magical-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'mgpl_btn_color',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpl_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgpl_btn_hover_style',
            [
                'label' => __('Hover', 'magical-addons-for-elementor'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpl_btnhover_boxshadow',
                'selector' => '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover',
            ]
        );

        $this->add_control(
            'mgpl_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover, {{WRAPPER}} .mg-post-list a.mg-card-btn:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover i, {{WRAPPER}} .mg-post-list a.mg-card-btn:focus i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover svg, {{WRAPPER}} .mg-post-list a.mg-card-btn:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpl_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover, {{WRAPPER}} .mg-post-list a.mg-card-btn:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpl_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-addons-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mgpl_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-post-list a.mg-card-btn:hover, {{WRAPPER}} .mg-post-list a.mg-card-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();

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
        $mgpl_filter = $this->get_settings('mgpl_posts_filter');
        $mgpl_posts_count = $this->get_settings('mgpl_posts_count');
        $mgpl_custom_order = $this->get_settings('mgpl_custom_order');
        $mgpl_grid_categories = $this->get_settings('mgpl_grid_categories');
        $orderby = $this->get_settings('orderby');
        $order = $this->get_settings('order');


        // Query Argument
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $mgpl_posts_count,
        );

        switch ($mgpl_filter) {


            case 'featured':
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
                break;

            case 'random_order':
                $args['orderby']    = 'rand';
                break;

            case 'show_byid':
                $args['post__in'] = $settings['mgpl_product_id'];
                break;

            case 'show_byid_manually':
                $args['post__in'] = explode(',', $settings['mgpl_product_ids_manually']);
                break;

            default: /* Recent */
                $args['orderby']    = 'date';
                $args['order']      = 'desc';
                break;
        }

        // Custom Order
        if ($mgpl_custom_order == 'yes') {
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        if (!(($mgpl_filter == "show_byid") || ($mgpl_filter == "show_byid_manually"))) {

            $post_cats = str_replace(' ', '', $mgpl_grid_categories);
            if ("0" != $mgpl_grid_categories) {
                if (is_array($post_cats) && count($post_cats) > 0) {
                    $field_name = is_numeric($post_cats[0]) ? 'term_id' : 'slug';
                    $args['tax_query'][] = array(
                        array(
                            'taxonomy' => 'category',
                            'terms' => $post_cats,
                            'field' => $field_name,
                            'include_children' => false
                        )
                    );
                }
            }
        }



        //grid layout
        $mgpl_post_style = $this->get_settings('mgpl_post_style');
        $mgpl_rownumber = $this->get_settings('mgpl_rownumber');
        // grid content
        $mgpl_post_img_show = $this->get_settings('mgpl_post_img_show');
        $mgpl_show_title = $this->get_settings('mgpl_show_title');
        $mgpl_crop_title = $this->get_settings('mgpl_crop_title');
        $mgpl_title_tag = $this->get_settings('mgpl_title_tag');
        $mgpl_desc_show = $this->get_settings('mgpl_desc_show');
        $mgpl_crop_desc = $this->get_settings('mgpl_crop_desc');
        $mgpl_post_btn = $this->get_settings('mgpl_post_btn');
        $mgpl_category_show = $this->get_settings('mgpl_category_show');
        $mgpl_usebtn_icon = $this->get_settings('mgpl_usebtn_icon');
        $mgpl_btn_title = $this->get_settings('mgpl_btn_title');
        $mgpl_btn_target = $this->get_settings('mgpl_btn_target');
        $mgpl_btn_icon = $this->get_settings('mgpl_btn_icon');
        $mgpl_btn_icon_position = $this->get_settings('mgpl_btn_icon_position');
        if ($settings['mgpl_link_type'] == 'btn') {
            $mgp_link_class = 'mg-card-btn mg-btn';
        } else if ($settings['mgpl_link_type'] == 'link2') {
            $mgp_link_class = 'mg-card-btn mg-link2';
        } else {
            $mgp_link_class = 'mg-card-btn mg-link';
        }

        $this->add_render_attribute('mgpl_btn_title', 'class', $mgp_link_class);


        $mgpl_posts = new WP_Query($args);

        if ($mgpl_posts->have_posts()) :
?>
            <div id="mglp-items" class="mgpl-items style<?php echo esc_attr($mgpl_post_style); ?>">
                <?php while ($mgpl_posts->have_posts()) : $mgpl_posts->the_post(); ?>
                    <?php
                    $mpg_cat_list = get_the_category_list(esc_html__('/ ', 'magical-addons-for-elementor'));
                    $categories = get_the_category();

                    $mgp_tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'magical-addons-for-elementor'));

                    ?>
                    <div class="mg-card mg-shadow mg-post-list mb-4">
                        <?php if (has_post_thumbnail() && $mgpl_post_img_show == 'yes') : ?>
                            <div class="mg-card-img mg-post-img">
                                <figure>
                                    <?php the_post_thumbnail('full'); ?>
                                </figure>
                            </div>
                        <?php endif; ?>
                        <div class="mg-card-text list-post-text">

                            <?php if ($mpg_cat_list && $settings['mgpl_category_show'] && $settings['mgpl_cat_type'] == 'all') : ?>
                                <div class="mgp-cat cat-list grid-meta <?php if (!has_post_thumbnail()) : ?>empty-img<?php endif; ?>">
                                    <?php
                                    $mpg_cat_list_sanitized = sanitize_text_field($mpg_cat_list);
                                    printf('<span class="mgp-post-cats">%s</span>', esc_html($mpg_cat_list_sanitized));

                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php
                            if (!empty($categories) && $settings['mgpl_category_show'] && $settings['mgpl_cat_type'] == 'one') { ?>
                                <div class="mgp-cat cat-list grid-meta <?php if (!has_post_thumbnail()) : ?>empty-img<?php endif; ?>">
                                    <?php
                                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '"><span class="mgp-post-cats">' . esc_html($categories[0]->name) . '</span></a>';
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                            <?php if ($mgpl_show_title) : ?>
                                <a class="mgp-title-link" href="<?php the_permalink(); ?>">
                                    <?php
                                    printf(
                                        '<%1$s class="mgp-ptitle">%2$s</%1$s>',
                                        mg_validate_html_tag($mgpl_title_tag, 'h4'),
                                        esc_html(wp_trim_words(get_the_title(), $mgpl_crop_title))
                                    );
                                    ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($mgpl_post_style == '1') : ?>
                                <div class="mgp-meta mb-3">
                                    <?php if ($settings['mgpl_author_show']) : ?>
                                        <?php echo wp_kses_post(mgp_post_author()); ?>
                                    <?php endif; ?>
                                    <?php if ($settings['mgpl_date_show']) : ?>
                                        <span class="mgp-time">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo esc_html(get_the_date('d M Y')); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; // meta if end 
                            ?>
                            <?php if ($mgpl_desc_show) : ?>
                                <p><?php echo esc_html(wp_trim_words(get_the_content(), $mgpl_crop_desc, '...')); ?></p>
                            <?php endif; ?>
                            <?php if ($mgpl_post_btn) : ?>
                                <?php if ($mgpl_usebtn_icon == 'yes') : ?>
                                    <a href="<?php the_permalink(); ?>" target="<?php echo esc_attr($mgpl_btn_target); ?>" <?php echo $this->get_render_attribute_string('mgpl_btn_title'); ?>>
                                        <?php if ($mgpl_btn_icon_position == 'left') : ?>

                                            <span class="left"><?php \Elementor\Icons_Manager::render_icon($settings['mgpl_btn_icon']); ?></span>

                                        <?php endif; ?>
                                        <span><?php echo mg_kses_tags($mgpl_btn_title); ?></span>
                                        <?php if ($mgpl_btn_icon_position == 'right') : ?>
                                            <span class="right"><?php \Elementor\Icons_Manager::render_icon($settings['mgpl_btn_icon']); ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" target="<?php echo esc_attr($mgpl_btn_target); ?>" <?php echo $this->get_render_attribute_string('mgpl_btn_title'); ?>><?php echo  mg_kses_tags($mgpl_btn_title); ?></a>
                                <?php endif; ?>
                            <?php endif; // use btn condition 
                            ?>
                            <?php if ($mgp_tags_list && $settings['mgpl_tag_show'] == 'yes' || $settings['mgpl_comment_show'] == 'yes') : ?>
                                <div class="mgpl-cmeta text-right">
                                    <?php


                                    if ($mgp_tags_list && $settings['mgpl_tag_show'] == 'yes') {
                                        printf(
                                            '<span class="mpg-tags-links"><i class="fas fa-tag"></i> %s</span>',
                                            wp_kses_post($mgp_tags_list)
                                        );
                                    }
                                    if ($settings['mgpl_comment_show'] == 'yes') {
                                        mg_plugin_comment_icon();
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                        </div>

                    </div>
                <?php
                endwhile;
                wp_reset_query();
                wp_reset_postdata();
                ?>
            </div>




<?php
        endif;
    }
}
