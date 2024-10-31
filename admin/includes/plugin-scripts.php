<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
add_action('admin_enqueue_scripts', 'rtmega_menu_admin_enqueue_scripts');
function rtmega_menu_admin_enqueue_scripts (){

    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_style( 'rtmegamenu-admin-style', RTMEGA_MENU_PL_URL . 'admin/assets/css/rtmega-menu-admin.css' );
    wp_enqueue_script( 'rtmegamenu-admin', RTMEGA_MENU_PL_URL . 'admin/assets/js/rtmega-menu-admin.js', array('jquery'), RTMEGA_MENU_VERSION, TRUE );
    wp_enqueue_script( 'rtmegamenu-template', RTMEGA_MENU_PL_URL . 'admin/assets/js/rtmega-template.js', array('jquery'), RTMEGA_MENU_VERSION, TRUE );



    $current_user = wp_get_current_user();

    wp_localize_script(
            'rtmegamenu-admin', 
            'rtmegamenu_ajax',
                [
                    'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                    'adminURL'         => admin_url(),
                    'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
                    'nonce'            => wp_create_nonce('rtmega_templates_import_nonce'),
                    'version'          => RTMEGA_MENU_VERSION,
                    'pluginURL'        => plugin_dir_url( __FILE__ ),
                    'packagedesc'      => __( 'Templates in this package', 'htmega-menu' ),
                    'user'             => [
                    'email' => $current_user->user_email,
                    ]
                ]
        );

}

