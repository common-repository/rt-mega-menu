<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

add_action('wp_head', 'rtmega_menu_dynamic_css');
function rtmega_menu_dynamic_css(){
    
    $rtmega_menu_options = get_option( 'rtmega_menu_options' ); 

    $main_menu_color = !empty($rtmega_menu_options['menu_color']) ? $rtmega_menu_options['menu_color'] : '';
    $main_menu_hover_color = !empty($rtmega_menu_options['menu_hover_color']) ? $rtmega_menu_options['menu_hover_color'] : '';
    $main_menu_active_color = !empty($rtmega_menu_options['menu_active_color']) ? $rtmega_menu_options['menu_active_color'] : '';
    
    
    $submenu_color = !empty($rtmega_menu_options['submenu_color']) ? $rtmega_menu_options['submenu_color'] : '';
    $submenu_hover_color = !empty($rtmega_menu_options['submenu_hover_color']) ? $rtmega_menu_options['submenu_hover_color'] : '';
    $submenu_bg_color = !empty($rtmega_menu_options['submenu_bg_color']) ? $rtmega_menu_options['submenu_bg_color'] : '';
    $submenu_width = !empty($rtmega_menu_options['submenu_width']) ? $rtmega_menu_options['submenu_width'] : '';
    
    
    $megamenu_width = !empty($rtmega_menu_options['megamenu_width']) ? $rtmega_menu_options['megamenu_width'] : '';
    
    ?>
    
    <style> 
        <?php
        if(!empty($main_menu_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu > .menu-item > .menu-link{
                color: <?php echo sanitize_hex_color( $main_menu_color )?>;
            }
            <?php
        }
        if(!empty($main_menu_hover_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item:hover > .menu-link{
                color: <?php echo sanitize_hex_color( $main_menu_hover_color )?>
            }
            <?php
        }
        if(!empty($main_menu_active_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item.current-menu-item > .menu-link,
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item ul.sub-menu .menu-item.current-menu-item> .menu-link{
                color: <?php echo sanitize_hex_color( $main_menu_active_color )?>
            }
            <?php
        }
        if(!empty($submenu_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item ul.sub-menu .menu-item > .menu-link {
                color: <?php echo sanitize_hex_color( $submenu_color )?>;
            }
            <?php
        }
        if(!empty($submenu_hover_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item ul.sub-menu > .menu-item:hover > .menu-link {
                color: <?php echo sanitize_hex_color( $submenu_hover_color )?>
            }
            <?php
        }
        if(!empty($submenu_bg_color)){
            ?>
            .rtmega-menu-container .rtmega-megamenu.default-nav .menu-item ul.sub-menu > .menu-item > .menu-link {
                background: <?php echo sanitize_hex_color( $submenu_bg_color )?>
            }
            <?php
        }
        if(!empty($submenu_width)){
            ?>
            .rtmega-menu-container .rtmega-megamenu .menu-item ul.sub-menu{
                width: <?php echo esc_html( $submenu_width )?>
            }
            <?php
        }

        if(!empty($megamenu_width)){
            ?>
            .rtmega-menu-area .desktop-menu-area ul.rtmega-megamenu .menu-item-has-children ul.rtmegamenu-contents.sub-menu.submenu {
                width: <?php echo esc_html( $megamenu_width )?>
            }
            <?php
        }
        ?>
    </style>
    <?php
}


