<?php
// Elementor Classes.
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Nav Menu.
 */
class RTMEGA_MENU_INLINE extends Widget_Base {
	/**
	 * Menu index.
	 *
	 * @access protected
	 * @var $nav_menu_index
	 */
	protected $nav_menu_index = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rt-mega-navigation-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'RTMEGA Menu', 'rt-mega-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'rtmega_category' ];
	}

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.3.0
	 * @access protected
	 *
	 * @return string nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Retrieve the list of available menus.
	 *
	 * Used to get the list of available menus.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @return array get WordPress menus list.
	 */
	private function get_available_menus() {

		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check if the Elementor is updated.
	 *
	 * @since 1.3.0
	 *
	 * @return boolean if Elementor updated.
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Register Nav Menu controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_general_content_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu', 'rt-mega-menu' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'rt-mega-menu' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'rt-mega-menu' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'rt-mega-menu' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		$this->add_control(
			'enable_menu_description',
			[
				'label'        => __( 'Show Description', 'rt-mega-menu' ),
				'type'         => Controls_Manager::SELECT,
				'options'   => [
					'none' => __( 'No', 'rt-mega-menu' ),
					'inline-block'  => __( 'Yes', 'rt-mega-menu' ),
				],
				'default'   => 'none',
				'selectors'          => [
					'{{WRAPPER}} li.menu-item .menu-desc' => 'display: {{VALUE}};',
				],
			]
		);
		


		$this->end_controls_section();
			$this->start_controls_section(
				'section_layout',
				[
					'label' => __( 'Layout', 'rt-mega-menu' ),
				]
			);

			$this->add_control(
				'menu_layout',
				[
					'label'   => __( 'Layout', 'rt-mega-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => __( 'Horizontal', 'rt-mega-menu' ),
						'vertical'   => __( 'Vertical', 'rt-mega-menu' )
					],
				]
			);
			
			$this->add_control(
				'enable_sticky_header',
				[
					'label' => esc_html__( 'Enable Sticky Header', 'rt-mega-menu' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
					'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
					'return_value' => 'yes',
					'separator' => 'before'
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
            'section_vertical_menu',
            [
                'label' => __( 'Vertical Menu', 'rt-mega-menu'),
				'condition' => [
					'menu_layout' => 'vertical'
				]
            ]
        );

		$this->add_control(
			'vertical_menu_expand_mode',
			[
				'label'   => __( 'Expand Mode', 'rt-mega-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'always_expand',
				'options' => [
					'click' => __( 'Expand on click', 'rt-mega-menu' ),
					'always_expand'   => __( 'Always expand', 'rt-mega-menu' )
				],
				'condition' => [
					'menu_layout' => 'vertical'
				]
			]
		);

		$this->add_control(
			'vertical_menu_expand_position',
			[
				'label'   => __( 'Menu Expand Position', 'rt-mega-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'left' => __( 'left', 'rt-mega-menu' ),
					'bottom'   => __( 'Bottom', 'rt-mega-menu' )
				],
				'condition' => [
					'menu_layout' => 'vertical',
					'vertical_menu_expand_mode' => 'click'
				]
			]
		);						
		$this->add_responsive_control(
			'vertical_menu_position_left',
			[
				'label'              => __( 'Position Left', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 500,
						'step' => 10,
					],
					
				],
				'condition' => [
					'vertical_menu_expand_position' => 'left',
					'vertical_menu_expand_mode' => 'click'
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded.expand-position-left' => 'left: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_responsive_control(
			'vertical_menu_position_top',
			[
				'label'              => __( 'Position Top', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 500,
						'step' => 10,
					],
					
				],
				'condition' => [
					'vertical_menu_expand_position' => 'left',
					'vertical_menu_expand_mode' => 'click'
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded.expand-position-left' => 'top: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_control(
			'vertical_menu_submenu_expad_mode',
			[
				'label'   => __( 'Submenu Expand Mode', 'rt-mega-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'click' => __( 'Clicked on expand', 'rt-mega-menu' ),
					'hover'   => __( 'Hover', 'rt-mega-menu' )
				],
				'condition' => [
					'menu_layout' => 'vertical'
				]
			]
		);			
		$this->add_responsive_control(
			'vertical_menu_width',
			[
				'label'              => __( 'Menu Width', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 3000,
						'step' => 10,
					],
					
				],
				'condition' => [
					'menu_layout' => 'vertical'
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded' => 'width: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_responsive_control(
			'sub_menu_width',
			[
				'label'              => __( 'SubMenu Width', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 3000,
						'step' => 10,
					],
					
				],
				'condition' => [
					'menu_layout' => 'vertical'
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_control(
            'vertical_menu_btn_icon',
            [
                'label'     => esc_html__('Menu Button Icon', 'rt-mega-menu'),
                'type' => Controls_Manager::ICONS,
                'separator' => 'before',
				'condition'    => [
					'enable_mobile_menu_view' => [ 'yes'],
				],
            ]
        );
		$this->add_responsive_control(
			'vertical_menu_btn_icon_size',
			[
				'label'              => __( 'Icon Size', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 100,
						'step' => 1,
					],
					
				],
				'condition' => [
					'menu_layout' => 'vertical'
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area.rtmega-menu-vertical-expand-button-wrapper a svg' => 'width: {{SIZE}}{{UNIT}};',
					],
			]
		);

		$this->add_control(
			'enable_vertical_menu_arrow',
			[
				'label' => esc_html__( 'Enable Vertical Menu Icon', 'rt-mega-menu' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
				'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
				'return_value' => 'yes',
				'condition' => [
					'menu_layout' => 'vertical'
				],
			]
		);

		$this->add_control(
			'enable_vertical_menu_arrow_right',
			[
				'label' => esc_html__( 'Enable Position Right Icon', 'rt-mega-menu' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
				'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
				'return_value' => 'yes',
				'condition' => [
					'menu_layout' => 'vertical'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'section_mobile_menu_menu',
            [
                'label' => __( 'Responsive Menu', 'rt-mega-menu'),
            ]
        );
		$this->add_control(
			'enable_mobile_menu_view',
			[
				'label' => esc_html__( 'Enable Menu Responsive view', 'rt-mega-menu' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
				'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
            'icon_settings',
            [
                'label'     => __( 'Icon Settings', 'rt-mega-menu'),
                'type'      => \Elementor\Controls_Manager::HEADING,               
                'condition'    => [
					'enable_mobile_menu_view' => [ 'yes'],
				],
            ]
        );
		$this->add_control(
            'menu_btn_icon',
            [
                'label'     => esc_html__('Menu Button Icon', 'rt-mega-menu'),
                'type' => Controls_Manager::ICONS,
                'separator' => 'before',
				'condition'    => [
					'enable_mobile_menu_view' => [ 'yes'],
				],
            ]
        );
		$this->end_controls_section();

	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'rt-mega-menu' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);
		

		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			[
				'label'              => __( 'Horizontal Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			[
				'label'              => __( 'Vertical Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'default'            => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'space_between_menu_item',
			[
				'label'              => __( 'Space Between', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'body:not(.rtl) {{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'condition'    => [
					'menu_layout' => [ 'horizontal'],
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'right_gap_menu_item',
			[
				'label'              => __( 'Menu Item Right Gap', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item:not(:last-child)' => 'margin-right:{{SIZE}}{{UNIT}}',
				],
				'condition'          => [
					'menu_layout' => 'horizontal',
				],
				'frontend_available' => true,
			]
		);			
		$this->add_responsive_control(
			'menu_item_margin',
			[
				'label'              => __( 'Item Margin', 'rt-mega-menu' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'pointer_menu_item',
			[
				'label'     => __( 'Link Hover Effect', 'rt-mega-menu' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => [
					'none'        => __( 'None', 'rt-mega-menu' ),
					'underline'   => __( 'Underline', 'rt-mega-menu' ),
				],
				'condition' => [
					'menu_layout' => [ 'horizontal' ],
				],
			]
		);

	$this->add_control(
		'style_divider_menu_item',
		[
			'type' => Controls_Manager::DIVIDER,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'      => 'typography_menu_item',
			'separator' => 'before',
			'selector'  => '{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item .menu-link, {{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item .menu-link',
		]
	);

	$this->start_controls_tabs( 'tabs_menu_item_style' );
		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'rt-mega-menu' ),
			]
		);

			$this->add_control(
				'color_menu_item',
				[
					'label'     => __( 'Text Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item .menu-link' => 'color: {{VALUE}}',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item .menu-link' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'bg_color_menu_item',
				[
					'label'     => __( 'Background Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link' => 'background-color: {{VALUE}}',
					],
					
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_menu_item_hover',
				[
					'label' => __( 'Hover', 'rt-mega-menu' ),
				]
			);

				$this->add_control(
					'color_menu_item_hover',
					[
						'label'     => __( 'Hover Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item:hover > .menu-link' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item:hover > .menu-link' => 'color: {{VALUE}}',
						],
					]
				);	
				$this->add_control(
					'bg_color_menu_item_hover',
					[
						'label'     => __( 'Background Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item:hover > .menu-link' => 'background-color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item:hover > .menu-link' => 'background-color: {{VALUE}}',
						],
						
					]
				);	
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_menu_item_active',
				[
					'label' => __( 'Active', 'rt-mega-menu' ),
				]
			);

				$this->add_control(
					'color_menu_item_active',
					[
						'label'     => __( 'Active Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item.current-menu-item > .menu-link' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item.current-menu-item > .menu-link' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'bg_color_menu_item_active',
					[
						'label'     => __( 'Background Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item.current-menu-item > .menu-link' => 'background-color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item.current-menu-item > .menu-link' => 'background-color: {{VALUE}}',
						],
						
					]
				);	
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border_menu_item',
				'label' => esc_html__( 'Border', 'rt-mega-menu' ),
				'selector' => '{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link, .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'border_radius_radius_menu_item',
			[
				'label'              => __( 'Border Radius', 'rt-mega-menu' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item > .menu-link'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item > .menu-link'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'frontend_available' => true,
			]
		);
			// Sub Menu Icon
			$this->add_control(
				'submenu_icon_options',
				[
					'label' => esc_html__( 'Sub Menu Icon', 'rt-mega-menu' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'enable_submenu_icon',
				[
					'label' => esc_html__( 'Disable Submenu Icon', 'rt-mega-menu' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
					'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
					'return_value' => 'none',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item-has-children .menu-link .menu-text .submenu-parent-icon' => 'display: {{VALUE}} !important',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item-has-children .menu-link .menu-text .submenu-parent-icon' => 'display: {{VALUE}} !important',
					],
				]
			);
			
			$this->add_control(
				'submenu_icon_style',
				[
					'label' => esc_html__( 'Select Icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'icon1',
					'options' => [
						'icon1'  => esc_html__( 'Angle', 'plugin-name' ),
						'icon2' => esc_html__( 'Plus', 'plugin-name' ),						
						'icon3' => esc_html__( 'Arrow Right', 'plugin-name' ),						
					],
					'condition' => ['enable_submenu_icon!' => 'none'],
				]
			);

			$this->add_control(
				'color_parent_icon',
				[
					'label'     => __( 'Icon Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item-has-children > .menu-link .menu-text .submenu-parent-icon svg' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item-has-children > .menu-link .menu-text .submenu-parent-icon svg' => 'fill: {{VALUE}} !important',
					],
					'condition' => ['enable_submenu_icon!' => 'none'],
				]
			);
			$this->add_responsive_control(
				'submenu_icon_size',
				[
					'label'              => __( 'Icon Size', 'rt-mega-menu' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item-has-children > .menu-link .menu-text .submenu-parent-icon svg' => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu > .menu-item-has-children > .menu-link .menu-text .submenu-parent-icon svg' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition' => ['enable_submenu_icon!' => 'none'],
					'frontend_available' => true,
				]
			);
			
			$this->add_control(
				'enable_vertical_active_menu_icon',
				[
					'label' => esc_html__( 'Disable Vertical Active Menu Icon', 'rt-mega-menu' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'rt-mega-menu' ),
					'label_off' => esc_html__( 'No', 'rt-mega-menu' ),
					'return_value' => 'none',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item.current-menu-item .vertical_menu_active_icon' => 'display: {{VALUE}} !important',
					],
					'condition'          => [
						'menu_layout' => 'vertical',
					],
				]
			);
			$this->add_control(
				'vertical_active_menu_style',
				[
					'label' => esc_html__( 'Vertical Active Menu Icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'icon3',
					'options' => [
						'icon1'  => esc_html__( 'Angle', 'plugin-name' ),
						'icon2' => esc_html__( 'Plus', 'plugin-name' ),						
						'icon3' => esc_html__( 'Arrow Right', 'plugin-name' ),						
					],
					'condition' => [
						'enable_vertical_active_menu_icon!' => 'none',
						'menu_layout' => 'vertical',
					],
				]
			);

			$this->add_control(
				'color_verticle_icon',
				[
					'label'     => __( 'Icon Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded .current-menu-item .vertical_menu_active_icon svg' => 'fill: {{VALUE}} !important',
						
					],
					'condition' => [
						'enable_vertical_active_menu_icon!' => 'none',
						'menu_layout' => 'vertical',
					],
				]
			);

			$this->add_responsive_control(
				'width_icon',
				[
					'label'              => __( 'Icon Size', 'rt-mega-menu' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'default'            => [
						'size' => '12',
						'unit' => 'px',
					],
					'selectors'          => [						
						'{{WRAPPER}} .rtmega-menu-area .vertical-expaned-menu-area .rtmega-menu-vertical-expanded .current-menu-item .vertical_menu_active_icon svg' => 'height: {{SIZE}}{{UNIT}};',
						
						
					],
					'condition' => [
						'enable_vertical_active_menu_icon!' => 'none',
						'menu_layout' => 'vertical',
					],
					'frontend_available' => true,
				]
			);

			// Sticky Menu
			$this->add_control(
				'more_options',
				[
					'label' => esc_html__( 'Sticky Menu', 'rt-mega-menu' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

		
			$this->add_control(
				'sticky_color_parent_icon',
				[
					'label'     => __( 'Sticky Icon Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'header.sticky-header .rtmega-menu-container .desktop-menu-area .rtmega-megamenu > .menu-item-has-children > .menu-link .menu-text .submenu-parent-icon svg' => 'fill: {{VALUE}} !important',
						
					],
					'condition' => ['enable_submenu_icon!' => 'none'],
				]
			);

			$this->start_controls_tabs( 'tabs_menu_item_style_stikcy' );

				$this->start_controls_tab(
					'stikcy_menu_item_normal',
					[
						'label' => __( 'Normal', 'rt-mega-menu' ),
					]
				);

					$this->add_control(
						'sticky_color_menu_item',
						[
							'label'     => __( 'Text Color', 'rt-mega-menu' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => [
								'default' => Global_Colors::COLOR_TEXT,
							],
							'default'   => '',
							'selectors' => [
								'header.sticky-header .rtmega-menu-container .desktop-menu-area > .rtmega-megamenu > .menu-item > .menu-link' => 'color: {{VALUE}} !important',
							],
						]
					);

					

				$this->end_controls_tab();

				$this->start_controls_tab(
					'sticky_menu_item_hover',
					[
						'label' => __( 'Hover', 'rt-mega-menu' ),
					]
				);

					$this->add_control(
						'sticky_color_menu_item_hover',
						[
							'label'     => __( 'Hover Text Color', 'rt-mega-menu' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => [
								'default' => Global_Colors::COLOR_ACCENT,
							],
							'selectors' => [
								'header.sticky-header .rtmega-menu-container .desktop-menu-area > .rtmega-megamenu > .menu-item:hover > .menu-link' => 'color: {{VALUE}} !important',
							],
						]
					);					


				$this->end_controls_tab();

				$this->start_controls_tab(
					'sticky_menu_item_active',
					[
						'label' => __( 'Active', 'rt-mega-menu' ),
					]
				);

					$this->add_control(
						'sticky_color_menu_item_actve',
						[
							'label'     => __('Active Text Color', 'rt-mega-menu' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'header.sticky-header .rtmega-menu-container .desktop-menu-area > .rtmega-megamenu > .menu-item.current-menu-item > .menu-link' => 'color: {{VALUE}}',
							],
						]
					);

				
					

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'sticky_color_bg',
				[
					'label'     => __( 'Sticky Area Bg Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => [
						'default' => Global_Colors::COLOR_TEXT,
					],
					'default'   => '',
					'selectors' => [
						'header.sticky-header' => 'background: {{VALUE}} !important',
					],
				]
			);	
			
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'stikcy_box_shadow',				
					'selector'  => 'header.sticky-header',
					'separator' => 'after',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_dropdown_content_controls() {

		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => __( 'Dropdown', 'rt-mega-menu' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width_dropdown_item',
			[
				'label'              => __( 'Dropdown Width (px)', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default'            => [
					'size' => '220',
					'unit' => 'px',
				],
				'selectors'          => [						
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu:not(.rtmegamenu-contents)' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu:not(.rtmegamenu-contents)' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					
				],
				'condition'          => [
					'menu_layout' => 'horizontal',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'dropdown_menu_align',
			[
				'label'        => __( 'Alignment', 'rt-mega-menu' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => __( 'Left', 'rt-mega-menu' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'rt-mega-menu' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'rt-mega-menu' ),
						'icon'  => 'eicon-h-align-right',
					],
					
				],
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link' => 'text-align: {{VALUE}}'
				],
			]
		);


		

			$this->start_controls_tabs( 'tabs_dropdown_item_style' );

				$this->start_controls_tab(
					'tab_dropdown_item_normal',
					[
						'label' => __( 'Normal', 'rt-mega-menu' ),
					]
				);

					$this->add_control(
						'color_dropdown_item',
						[
							'label'     => __( 'Text Color', 'rt-mega-menu' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link' => 'color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link' => 'color: {{VALUE}}',
							],
						]
					);
					$this->add_control(
						'bg_color_dropdown_menu_item',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu' => 'background-color: {{VALUE}}',
							],
							
						]
					);					

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_hover',
					[
						'label' => __( 'Hover', 'rt-mega-menu' ),
					]
				);

					$this->add_control(
						'color_dropdown_item_hover',
						[
							'label'     => __( 'Hover Text Color', 'rt-mega-menu' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover> a 
								' => 'color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover> a 
								' => 'color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu:after 
								' => 'border-bottom-color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu:after 
								' => 'border-bottom-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_dropdown_menu_item_hover',
						[
							'label'     => __( 'Hover Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover > .menu-link' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover > .menu-link' => 'background-color: {{VALUE}}',
							],
							
						]
					);				

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_active',
					[
						'label' => __( 'Active', 'rt-mega-menu' ),
					]
				);

				$this->add_control(
					'color_dropdown_item_active',
					[
						'label'     => __( 'Active Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-item > a' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-item > a a' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-ancestor >  a a' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-ancestor >  a a' => 'color: {{VALUE}}',

						],
					]
				);
				$this->add_control(
						'bg_color_dropdown_menu_item_active',
						[
							'label'     => __( 'Active Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-item' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item.current-menu-item' => 'background-color: {{VALUE}}',
							],
							
						]
					);		

			
				$this->end_controls_tabs();

			$this->end_controls_tabs();
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'dropdown_typography',
					'separator' => 'before',
					'selector'  => '
							{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link, 
							{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link',
				]
			);
			$this->add_responsive_control(
				'dropdown_padding',
				[
					'label'              => __( 'Padding', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => esc_html__( 'Border', 'rt-mega-menu' ),
					'selector' => '
								{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu,
								{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu',
				]
			);
			
			$this->add_responsive_control(
				'dropdown_border_radius',
				[
					'label'              => __( 'Border Radius', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'dropdown_box_shadow',
					'exclude'   => [
						'box_shadow_position',
					],
					'selector'  => '
									{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu,
									{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu',
					'separator' => 'after',
				]
			);

			$this->add_control(
				'heading_dropdown_divider',
				[
					'label'     => __( 'Dropdown Item Options', 'rt-mega-menu' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_responsive_control(
				'dropdown_item_padding',
				[
					'label'              => __( 'Item Padding', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dropdown_item_link_padding',
				[
					'label'              => __( 'Item Link Padding', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dropdown_item_margin',
				[
					'label'              => __( 'Item Margin', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dropdown_item_border_radius',
				[
					'label'              => __( 'Item Border Radius', 'rt-mega-menu' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'dropdown_divider_border',
				[
					'label'       => __( 'Border Style', 'rt-mega-menu' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'rt-mega-menu' ),
						'solid'  => __( 'Solid', 'rt-mega-menu' ),
						'double' => __( 'Double', 'rt-mega-menu' ),
						'dotted' => __( 'Dotted', 'rt-mega-menu' ),
						'dashed' => __( 'Dashed', 'rt-mega-menu' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'divider_border_color',
				[
					'label'     => __( 'Border Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width',
				[
					'label'     => __( 'Border Width', 'rt-mega-menu' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);


			$this->add_control(
				'heading_dropdown_description',
				[
					'label'     => __( 'Dropdown Description', 'rt-mega-menu' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->start_controls_tabs( 'tabs_dropdown_item_description_style' );

			$this->start_controls_tab(
				'tab_dropdown_item_description_normal',
				[
					'label' => __( 'Normal', 'rt-mega-menu' ),
				]
			);
	
				$this->add_control(
					'color_dropdown_item_desc',
					[
						'label'     => __( 'Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#497696',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link .menu-desc' => 'color: {{VALUE}}',
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link .menu-desc' => 'color: {{VALUE}}',
						],
					]
				);				
			$this->end_controls_tab();
	
			$this->start_controls_tab(
				'tab_dropdown_item_desc_hover',
				[
					'label' => __( 'Hover', 'rt-mega-menu' ),
				]
			);
	
				$this->add_control(
					'color_dropdown_item_desc_hover',
					[
						'label'     => __( 'Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover> a .menu-desc' => 'color: {{VALUE}}', 
							'{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item:hover> a .menu-desc ' => 'color: {{VALUE}}',
						],
					]
				);
	
			$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_tabs();
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'dropdown_desc_typography',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rtmega-menu-container .desktop-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link .menu-desc',
				'selector'  => '{{WRAPPER}} .rtmega-menu-container .vertical-expaned-menu-area .rtmega-megamenu .menu-item ul.sub-menu .menu-item .menu-link .menu-desc'
			]
		);

		$this->end_controls_section();


		//Responsive menu icon settings

		$this->start_controls_section(
			'resonsive_menu',
			[
				'label'     => __( 'Mobile Menu', 'rt-mega-menu' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				
			]
		);
		$this->add_control(
			'mobile_menu_overlay_bg',
			[
				'label'     => __( 'Overlay Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .overlay' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_container_bg',
			[
				'label'     => __( 'Container Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'mobile_menu_container_padding',
			[
				'label'              => __( 'Container Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'mobile_menu_padding',
			[
				'label'              => __( 'Menu Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' 
				],
				'frontend_available' => true,
				'separator' => 'after'
			]
		);
		$this->add_control(
			'mobile_menu_item_styles',
			[
				'label'     => __( 'Menu Items', 'rt-mega-menu'),
				'type'      => \Elementor\Controls_Manager::HEADING,      
			]
		);
		$this->add_responsive_control(
			'padding_horizontal_mobile_menu_item',
			[
				'label'              => __( 'Horizontal Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu > .menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'padding_vertical_mobile_menu_item',
			[
				'label'              => __( 'Vertical Padding', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu > .menu-item > .menu-link' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'space_between_mobile_menu_item',
			[
				'label'              => __( 'Space Between', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'body:not(.rtl) {{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu > .menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'condition'    => [
					'menu_layout' => [ 'horizontal'],
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'right_gap_mobile_menu_item',
			[
				'label'              => __( 'Menu Item Right Gap', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu > .menu-item:not(:last-child)' => 'margin-right:{{SIZE}}{{UNIT}}',
				],
				'condition'          => [
					'menu_layout' => 'horizontal',
				],
				'frontend_available' => true,
			]
		);			

	$this->add_control(
		'style_divider_mobile_menu_item',
		[
			'type' => Controls_Manager::DIVIDER,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'      => 'typography_mobile_menu_item',
			'separator' => 'before',
			'selector'  => '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item .menu-link',
		]
	);

	$this->start_controls_tabs( 'tabs_mobile_menu_item_style' );
		$this->start_controls_tab(
			'tab_mobile_menu_item_normal',
			[
				'label' => __( 'Normal', 'rt-mega-menu' ),
			]
		);

			$this->add_control(
				'color_mobile_menu_item',
				[
					'label'     => __( 'Text Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item .menu-link' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'bg_color_mobile_menu_item',
				[
					'label'     => __( 'Background Color', 'rt-mega-menu' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item .menu-link' => 'background-color: {{VALUE}}',
					],
					
				]
			);

			

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_mobile_menu_item_hover',
				[
					'label' => __( 'Hover', 'rt-mega-menu' ),
				]
			);

				$this->add_control(
					'color_mobile_menu_item_hover',
					[
						'label'     => __( 'Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item:hover > .menu-link' => 'color: {{VALUE}}',
						],
					]
				);					


			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_mobile_menu_item_active',
				[
					'label' => __( 'Active', 'rt-mega-menu' ),
				]
			);

				$this->add_control(
					'color_mobile_menu_item_active',
					[
						'label'     => __( 'Text Color', 'rt-mega-menu' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item.current-menu-item > .menu-link' => 'color: {{VALUE}}',
						],
					]
				);

			
				

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border_mobile_menu_item',
				'label' => esc_html__( 'Border', 'rt-mega-menu' ),
				'selector' => '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu > .menu-item > .menu-link',
			]
		);
		

		$this->add_responsive_control(
			'border_radius_radius_mobile_menu_item',
			[
				'label'              => __( 'Border Radius', 'rt-mega-menu' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item .menu-link'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mobile_submenu_icon_options',
			[
				'label' => esc_html__( 'Sub Menu Icon', 'rt-mega-menu' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_control(
			'color_parent_icon_mobile_menu',
			[
				'label'     => __( 'Icon Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-megamenu .menu-item-has-children .menu-link .menu-text .submenu-parent-icon svg' => 'fill: {{VALUE}} !important',
				],
				'separator' => 'after',
			]
		);	
					
		$this->add_control(
			'mobile_menu_toggle_styles',
			[
				'label'     => __( 'Menu Opener', 'rt-mega-menu'),
				'type'      => \Elementor\Controls_Manager::HEADING,               
				
			]
		);
		$this->add_responsive_control(
		    'mobile_menu_toggle_padding',
		    [
		        'label' => esc_html__( 'Padding', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', 'em', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-mobile-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_responsive_control(
		    'mobile_menu_toggle_margin',
		    [
		        'label' => esc_html__( 'Margin', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', 'em', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-mobile-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name' => 'mobile_menu_toggle_border',
		        'selector' => '{{WRAPPER}} .rtmega-menu-mobile-button',
		    ]
		);
		$this->add_control(
		    'mobile_menu_toggle_border_radius',
		    [
		        'label' => esc_html__( 'Border Radius', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-mobile-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'mobile_menu_toggle_style_normal',
			[
				'label' => __( 'Normal', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_color_path_fill',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_color_rect_fill',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_bg_color',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_menu_toggle_hover',
			[
				'label' => __( 'Hover', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'toggle_color_hover',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_color_path_fill_hover',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_color_rect_fill_hover',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button:hover svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'toggle_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-mobile-button:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'mobile_menu_sticky_toggle_styles',
			[
				'label'     => __( 'Menu Opener on Sticky', 'rt-mega-menu'),
				'type'      => \Elementor\Controls_Manager::HEADING,               
				
			]
		);

		$this->start_controls_tabs( 'tabs_stikcy_toggle_style' );

		$this->start_controls_tab(
			'sticky_mobile_menu_toggle_style_normal',
			[
				'label' => __( 'Normal', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'sticky_toggle_color',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_color_path_fill',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_color_rect_fill',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header {{WRAPPER}} .rtmega-menu-mobile-button svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_bg_color',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'sticky_mobile_menu_toggle_hover',
			[
				'label' => __( 'Hover', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'sticky_toggle_color_hover',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_color_path_fill_hover',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_color_rect_fill_hover',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header {{WRAPPER}} .rtmega-menu-mobile-button:hover svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'sticky_toggle_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'header.sticky-header .rtmega-menu-mobile-button:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'mobile_menu_closer_styles',
			[
				'label'     => __( 'Menu Closer', 'rt-mega-menu'),
				'type'      => \Elementor\Controls_Manager::HEADING,      
				'separator' => 'before',         
				
			]
		);
		$this->add_responsive_control(
		    'mobile_menu_closer_padding',
		    [
		        'label' => esc_html__( 'Padding', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', 'em', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_responsive_control(
		    'mobile_menu_closer_margin',
		    [
		        'label' => esc_html__( 'Margin', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', 'em', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name' => 'mobile_menu_closer_border',
		        'selector' => '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close',
		    ]
		);
		$this->add_control(
		    'mobile_menu_closer_border_radius',
		    [
		        'label' => esc_html__( 'Border Radius', 'rt-mega-menu' ),
		        'type' => Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);
		$this->start_controls_tabs( 'tabs_mobile_menu_closer_style' );

		$this->start_controls_tab(
			'mobile_menu_closer_style_normal',
			[
				'label' => __( 'Normal', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'mobile_menu_closer_color',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_color_path_fill',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_color_rect_fill',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_bg_color',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_menu_closer_hover',
			[
				'label' => __( 'Hover', 'rt-mega-menu' ),
			]
		);

		$this->add_control(
			'mobile_menu_closer_color_hover',
			[
				'label'     => __( 'Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_color_path_fill_hover',
			[
				'label'     => __( 'SVG PATH Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_color_rect_fill_hover',
			[
				'label'     => __( 'SVG RECT Fill Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close:hover svg rect' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile_menu_closer_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'rt-mega-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rtmega-menu-container .mobile-menu-area .rtmega-menu-mobile-sidebar .rtmega-menu-mobile-close:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_mega_menu',
			[
				'label' => __( 'Mega Menu', 'rt-mega-menu' ),
			]
		);

		$this->add_responsive_control(
			'mega_menu_width',
			[
				'label'              => __( 'Mega Menu Width', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px'],
				'range'              => [
					'px' => [						
						'max' => 3000,
						'step' => 10,
					],
					
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .desktop-menu-area ul.rtmega-megamenu .menu-item-has-children.rtmega_menu > .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_responsive_control(
			'mega_menu_position_left',
			[
				'label'              => __( 'Position Left', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'range'              => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .desktop-menu-area ul.rtmega-megamenu .menu-item-has-children .rtmegamenu-contents.sub-menu' => 'left: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_responsive_control(
			'mega_menu_position_right',
			[
				'label'              => __( 'Position Right', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'range'              => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .desktop-menu-area ul.rtmega-megamenu .menu-item-has-children .rtmegamenu-contents.sub-menu' => 'left: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->add_responsive_control(
			'mega_menu_position_top',
			[
				'label'              => __( 'Position Top', 'rt-mega-menu' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'range'              => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .rtmega-menu-area .desktop-menu-area ul.rtmega-megamenu .menu-item-has-children .rtmegamenu-contents.sub-menu' => 'top: {{SIZE}}{{UNIT}};',
					],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.5.2
	 * @param string $atts link attributes.
	 * @access public
	 */
	public function handle_link_attrs( $atts ) {

		$atts .= ' itemprop="url"';
		return $atts;
	}

	/**
	 * Add itemprop to the li tag of Navigation Schema.
	 *
	 * @since 1.6.0
	 * @param string $value link attributes.
	 * @access public
	 */
	public function handle_li_values( $value ) {

		$value .= ' itemprop="name"';
		return $value;
	}

	/**
	 * Get the menu and close icon HTML.
	 *
	 * @since 1.5.2
	 * @param array $settings Widget settings array.
	 * @access public
	 */
	public function get_menu_close_icon( $settings ) {
		$menu_icon     = '';
		$close_icon    = '';
		$icons         = [];
		$icon_settings = [
			$settings['dropdown_icon'],
			$settings['dropdown_close_icon'],
		];

		foreach ( $icon_settings as $icon ) {
			if ( $this->is_elementor_updated() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon(
					$icon,
					[
						'aria-hidden' => 'true',
						'tabindex'    => '0',
					]
				);
				$menu_icon = ob_get_clean();
			} else {
				$menu_icon = '<i class="' . esc_attr( $icon ) . '" aria-hidden="true" tabindex="0"></i>';
			}

			array_push( $icons, $menu_icon );
		}

		return $icons;
	}

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() { 
		

		$settings = $this->get_settings_for_display();		
		$class_responsvie =  !empty($settings['enable_mobile_menu_view']) == 'yes' ? 'enabled-mobile-menu': 'enabled-desktop-menu';
		$class_responsvie .=  !empty($settings['menu_layout']) == 'vertical' ? ' enabled-vertical-menu': '';
		$menu_layout = $settings['menu_layout'];
		$menu_arrow_verticale = ($settings['enable_vertical_menu_arrow'] == 'yes') ? 'rt-mega-arrow-add' : '';
		$menu_arrow_verticale_right = ($settings['enable_vertical_menu_arrow_right'] == 'yes') ? 'rt-mega-arrow-add-right' : '';
		$rtmega_mobile_menu_html = '';
		$menu_expand_position = $settings['vertical_menu_expand_position'];
		$menu_expand_position_class = ' expand-position-' . $settings['vertical_menu_expand_position'];
		$unique_id = uniqid();

				$menus = $this->get_available_menus();
				if ( empty( $menus ) ) {
					return false;
				}
				// Sub Menu Icon 			
				if($settings['submenu_icon_style'] == 'icon2'){
                    $submenu_parent_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>';
                }
                elseif($settings['submenu_icon_style'] == 'icon3'){
                    $submenu_parent_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg>';
                }
				else{
                    $submenu_parent_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>';
                }

				// Vertical Menu Icon 			
				if($settings['vertical_active_menu_style'] == 'icon2'){
                    $active_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>';
                }
                elseif($settings['vertical_active_menu_style'] == 'icon3'){
                    $active_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg>';
                }
				else{
                    $active_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>';
                }
				// Mobile Menu
				if($settings['enable_mobile_menu_view'] == 'yes'){
					$rtmega_mobile_menu_html = '<div class="mobile-menu-area '.$unique_id.'">
					<div class="overlay" onclick="closeRTMEGAmobile()"></div>
					<div class="rtmega-menu-mobile-sidebar">
						<a class="rtmega-menu-mobile-close" onclick="closeRTMEGAmobile()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M317.7 402.3c3.125 3.125 3.125 8.188 0 11.31c-3.127 3.127-8.186 3.127-11.31 0L160 267.3l-146.3 146.3c-3.127 3.127-8.186 3.127-11.31 0c-3.125-3.125-3.125-8.188 0-11.31L148.7 256L2.344 109.7c-3.125-3.125-3.125-8.188 0-11.31s8.188-3.125 11.31 0L160 244.7l146.3-146.3c3.125-3.125 8.188-3.125 11.31 0s3.125 8.188 0 11.31L171.3 256L317.7 402.3z"/></svg></a>
						<div class="rtmega-menu-mobile-navigation"><ul id="%1$s" class="%2$s">%3$s</ul></div>
						</div>
					</div>';
				}			
				
				// Vertical Expaned Menu
				if($settings['menu_layout'] == 'vertical' && $settings['vertical_menu_expand_mode'] == 'click'){
					$rtmega_vetical_menu_html = '<div class="vertical-expaned-menu-area '.$unique_id.' vertical-expaned-menu-area-'.$menu_expand_position.'">
						<div class="rtmega-menu-vertical-expanded '. $menu_expand_position_class .'">
							<div class="rtmega-menu-mobile-navigation"><ul id="%1$s" class="%2$s">%3$s</ul></div>
							</div>
						</div>';
				 }			
				 if($settings['menu_layout'] == 'vertical' && $settings['vertical_menu_expand_mode'] == 'always_expand'){
					$rtmega_vetical_menu_html = '<div class="vertical-expaned-menu-area '.$unique_id.' vertical-expaned-menu-area-'.$menu_expand_position.'">
						<div class="rtmega-menu-vertical-always-expanded rtmega-menu-vertical-expanded opened '. $menu_expand_position_class .'">
							<div class="rtmega-menu-mobile-navigation '. $menu_arrow_verticale .' '.$menu_arrow_verticale_right.'"><ul id="%1$s" class="%2$s">%3$s</ul></div>
							</div>
						</div>';
				 }	
				
				// Desktop Menu
				$items_wrap = '<div class="desktop-menu-area"><ul id="%1$s" class="%2$s">%3$s</ul></div>'.$rtmega_mobile_menu_html;

				if($settings['menu_layout'] == 'vertical'){
					$items_wrap = $rtmega_vetical_menu_html;
				}

				$args = [
					'echo'        => false,
					'menu'        => $settings['menu'],					
					'fallback_cb' => '__return_empty_string',
					'menu_class'      => 'menu desktop-menu rtmega-megamenu vertical-submenu-expand-mode-'.$settings['vertical_menu_submenu_expad_mode'] . ' ' .$menu_layout,
					'container_class'	=> 'rtmega-elelmentor-widget menu-wrapper rtmega-menu-container rtmega-menu-area '.$class_responsvie,
					'vertical_menu_active_icon' => $active_icon,
					'submenu_parent_icon' => $submenu_parent_icon,
					'menu_layout'		  => $settings['menu_layout'],
					'items_wrap'      => $items_wrap,
					'pointer_hover_effect' => $settings['pointer_menu_item'],
					'is_mobile_menu'	=> '',
					'walker'          	=> new RTMEGA_Nav_Walker()
				];

					echo wp_nav_menu( $args );

					
					if($settings['vertical_menu_expand_mode'] == 'click'){
						
						?>
						<div class="rtmega-menu-area rtmega-menu-vertical-expand-button-wrapper enabled-vertical-menu">
							
									<?php
									if(!empty($settings['vertical_menu_btn_icon']['value'])){
										?>
										<a href="#" class="rtmega-menu-mobile-button" widget_id='<?php echo esc_attr( $unique_id )?>'>
										<?php \Elementor\Icons_Manager::render_icon( $settings['vertical_menu_btn_icon'], [ 'aria-hidden' => 'true' ]); ?>
										</a>
										<?php
									}else{
										?>
										<a href="#" class="rtmega-menu-mobile-button" widget_id='<?php echo esc_attr( $unique_id )?>'>
											<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect y="14" width="18" height="2" fill="#000000"></rect>
												<rect y="7" width="18" height="2" fill="#000000"></rect>
												<rect width="18" height="2" fill="#000000"></rect>
											</svg>
										</a>
										<?php
									}
									?>
								
						</div>
							
						<?php
					}
					
					if($settings['enable_mobile_menu_view'] == 'yes'){
						
						?>
						<div class="rtmega-menu-area rtmega-menu-mobile-button-wrapper enabled-mobile-menu">
							
									<?php
									if(!empty($settings['menu_btn_icon']['value'])){
										?>
										<a href="#" class="rtmega-menu-mobile-button" onclick="openRTMEGAmobile()">										
										<?php \Elementor\Icons_Manager::render_icon( $settings['menu_btn_icon'], [ 'aria-hidden' => 'true' ]); ?>
										</a>
										<?php
									}else{
										?>
										<a href="#" class="rtmega-menu-mobile-button" onclick="openRTMEGAmobile()">
											<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect y="14" width="18" height="2" fill="#000000"></rect>
												<rect y="7" width="18" height="2" fill="#000000"></rect>
												<rect width="18" height="2" fill="#000000"></rect>
											</svg>
										</a>
										<?php
									}
									?>
								
						</div>
							
						<?php
					}

					if($settings['enable_sticky_header'] == 'yes'){
						?>
							<script>
								(function($) {

									// sticky menu
									  // Select header element
									var header = $('header');
                                    header.addClass("sticky-header-on");
									// Select window element
									var win = $(window);
									// Function to handle scrolling
									function handleScroll() {
										// Get the current scroll position
										var scroll = win.scrollTop();
										// Check if the scroll position is greater than 0
										if (scroll > 100) {
											// If yes, add the 'sticky-header' class to the header
											header.addClass("sticky-header");
										} else {
											// If no, remove the 'sticky-header' class from the header
											header.removeClass("sticky-header");
										}
									}
									// Bind the handleScroll function to the scroll event
									win.on('scroll', handleScroll);

								})(jQuery);
							</script>
						<?php
					}

				}		
}