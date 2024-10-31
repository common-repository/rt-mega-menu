<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
add_action('wp_enqueue_scripts', 'rtmega_menu_wp_enqueue_scripts');
function rtmega_menu_wp_enqueue_scripts (){

    wp_enqueue_style( 'rtmegamenu-accordion-style', RTMEGA_MENU_PL_URL . 'public/assets/css/rtmega-accordion.css' );
    wp_enqueue_style( 'rtmegamenu-style', RTMEGA_MENU_PL_URL . 'public/assets/css/rtmega.css' );
    wp_enqueue_script( 'rtmegamenu-public', RTMEGA_MENU_PL_URL . 'public/assets/js/rtmega-menu-public.js', array('jquery'), RTMEGA_MENU_VERSION, TRUE );
    wp_enqueue_script( 'rtmegamenu-accordion-script', RTMEGA_MENU_PL_URL . 'public/assets/js/rtmega-accordion.js', array('jquery'), RTMEGA_MENU_VERSION, TRUE );

}

