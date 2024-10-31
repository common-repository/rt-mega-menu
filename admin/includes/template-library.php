<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
define('RTMEGA_TEMPLATES_SOURCE_SITE_URL', 'https://rtmega.themewant.com');
class RTMEGA_MENU_Template_Library{ 

    public static $templateapi = '';
    public static $api_args = [];

    // Get Instance
    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if ( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'admin_menu' ], 225 );
            add_action( 'wp_ajax_import_rtmega_template', [ $this, 'import_rtmega_template' ] );
            add_action( 'wp_ajax_nopriv_import_rtmega_template', [ $this, 'import_rtmega_template' ] );
        }        

        self::$api_args = [
            'plugin_version' => RTMEGA_MENU_VERSION,
            'url'            => home_url(),
        ];

    }

    // Plugins Library Register
    function admin_menu() {
        add_submenu_page(
            'rtmega-menu', 
            esc_html__( 'Templates Library', 'rtmega-menu' ),
            esc_html__( 'Templates Library', 'rtmega-menu' ), 
            'manage_options', 
            'rtmegamenu_templates', 
            array ( $this, 'library_render_html' ) 
        );
    }

    function library_render_html(){
        require_once __DIR__ . '/templates_list.php';
    }

    public static function get_rtmega_templates() {

         $TEMPLATES_SOURCE_URL = RTMEGA_TEMPLATES_SOURCE_SITE_URL . '/wp-json/reacthemes/v1/get_rt_el_templates';
         
         $body = [];

        $response = wp_remote_post( $TEMPLATES_SOURCE_URL, array(
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => false,
            'data_format' => 'body',
            'body'        => $body
        ) );
        
        return $response['body'];
    }

    function get_rtmega_template_by_id($template_id) {

        $TEMPLATES_SOURCE_URL = RTMEGA_TEMPLATES_SOURCE_SITE_URL . '/wp-json/reacthemes/v1/get_rt_el_template_data_by_id';
        
        $body = [];
        $body['template_id'] = $template_id;

       $response = wp_remote_post( $TEMPLATES_SOURCE_URL, array(
           'headers'     => [],
           'timeout'     => 60,
           'redirection' => 5,
           'blocking'    => true,
           'httpversion' => '1.0',
           'sslverify'   => false,
           'body'        => $body
       ) );
       
       $result = json_decode( wp_remote_retrieve_body( $response ), true );
       return $result;
   }

    function import_rtmega_template(){

       check_ajax_referer('rtmega_templates_import_nonce', 'nonce');

        if ( isset( $_REQUEST ) ) {

            $template_id = $_REQUEST['templateId'];
            $page_title = $_REQUEST['pageTitle'];


            $response_data = $this->get_rtmega_template_by_id( $template_id );
           


            $default_title = !empty( $response_data['title'] ) ? $response_data['title'] : __( 'New Template RTMEGA', 'rt-mega-menu' );

            $args = [
                'post_type'    => !empty( $page_title ) ? 'page' : 'elementor_library',
                'post_status'  => !empty( $page_title ) ? 'draft' : 'publish',
                'post_title'   => !empty( $page_title ) ? $page_title : $default_title,
                'post_content' => '',
            ];

            $new_post_id = wp_insert_post( $args );

            update_post_meta( $new_post_id, '_elementor_data', $response_data['content'] );
            update_post_meta( $new_post_id, '_elementor_page_settings', $response_data['page_settings'] );
            update_post_meta( $new_post_id, '_elementor_template_type', $response_data['template_type'] );
            update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

            if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                update_post_meta( $new_post_id, '_wp_page_template', ! empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_header_footer' );
            }

            echo wp_json_encode(
                array( 
                    'id' => $new_post_id,
                    'edittxt' => esc_html__( 'Edit Template', 'rt-mega-menu' )
                )
            );

        }

        die();
    }


}

RTMEGA_MENU_Template_Library::instance();