<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class RTMEGA_MENU {
	
    private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'rt-mega-menu' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
		add_filter( 'plugin_action_links_' . RTMEGA_MENU_PLUGIN_BASE, [ $this, 'rtmega_plugin_action_links' ], 10, 4 );

	}

	

	function rtmega_plugin_action_links( $plugin_actions, $plugin_file, $plugin_data, $context ) {

		$new_actions = array();
		$new_actions['rtmega_plugin_actions_setting'] = sprintf( __( '<a href="%s" target="_self">Settings</a>', 'rt-mega-menu' ), esc_url( admin_url( 'options-general.php?page=rtmega-menu' ) ) );
		$new_actions['rtmega_plugin_actions_upgrade'] = sprintf( __( '<a href="%s" style="color: #39b54a; font-weight: bold;"  target="_blank">Upgrade to Pro</a>', 'rt-mega-menu' ), esc_url( 'https://rtmega.themewant.com' ) );
		return array_merge( $new_actions, $plugin_actions );

	}
	

    // Add elementor widget category
	public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'rtmega_category',
            [
                'title' => esc_html__('RTMEGA Elementor Addons', 'rt-mega-menu' ),
                'icon' => 'fa fa-smile-o',
            ]
        );
    }



    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		$message = __( 'RTMEGA MENU Addon Custom Elementor Addon requires Elementor to be installed and activated', 'rt-mega-menu' );

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html($message) );

	}


    public function init_widgets() {


		//Nav Widget
        require_once ( RTMEGA_MENU_PL_PATH.'/public/widgets/rtmega-widget.php' );
		\Elementor\Plugin::instance()->widgets_manager->register( new \RTMEGA_MENU_INLINE() );	



		// Register widget				
		add_action( 'elementor/elements/categories_registered', [$this, 'add_category'] );
		

	}

}