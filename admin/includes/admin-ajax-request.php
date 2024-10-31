<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( !class_exists('RTMEGA_MENU_Admin_Ajax')) {
    class RTMEGA_MENU_Admin_Ajax {

        function __construct(){

            add_action( "wp_ajax_rtmega_update_menu_options", array ( $this, 'rtmega_update_menu_options' ) );
            add_action( "wp_ajax_nopriv_rtmega_update_menu_options", array ( $this, 'rtmega_update_menu_options' ) );

            add_action( "wp_ajax_rtmega_get_menu_options", array ( $this, 'rtmega_get_menu_options' ) );
            add_action( "wp_ajax_nopriv_rtmega_get_menu_options", array ( $this, 'rtmega_get_menu_options' ) );

            add_action( "wp_ajax_rtmega_set_menu_item_mega_button", array ( $this, 'rtmega_set_menu_item_mega_button' ) );
            add_action( "wp_ajax_nopriv_rtmega_set_menu_item_mega_button", array ( $this, 'rtmega_set_menu_item_mega_button' ) );

            add_action( "wp_ajax_rtmega_delete_menu_options", array ( $this, 'rtmega_delete_menu_options' ) );
            add_action( "wp_ajax_nopriv_rtmega_delete_menu_options", array ( $this, 'rtmega_delete_menu_options' ) );

            add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'rtmega_menu_item_icon' ), 10, 2 );
        }

        function rtmega_menu_item_icon( $item_id, $item ) {

            
            ?>
                <div class="rtmega_saved_icon_wrapper" style="clear: both;">
                    <div class="rtmega_saved_icon"><i class=""></i></div>
                    <div class="rtmega_saved_icon_actions">
                        <button type="button" class="rtmega_set_icon_toggle_in_nav_item" data-menu_item_id="<?php echo esc_attr($item_id); ?>"><?php echo 'Add Icon'; ?></button>
                    </div>
                </div>
            <?php
            
        }

        public function rtmega_update_menu_options($menu_id) {

            check_ajax_referer('rtmega_templates_import_nonce', 'nonce');
            
            if(isset($_POST['settings']) && isset($_POST['menu_id']) && isset($_POST['actualAction'])){


                $actual_action = sanitize_text_field($_POST['actualAction']);

                $menu_id = sanitize_text_field($_POST['menu_id']);
  

                $settings = isset( $_POST['settings'] ) ?  $_POST['settings'] : array();
                $menu_id = absint( $_POST['menu_id'] );

                if($actual_action == 'saveMenuOptions'){

                    update_option( 'rtmega_menu_settings_' . $menu_id, $settings );

                }else{

                    $menu_item_id = sanitize_text_field($_POST['menu_item_id']);
                    $settings = ( !empty( $_POST['settings'] ) ?  sanitize_text_field( $_POST['settings'] ) : '' );

                    if( !empty( $settings ) ) {
                        parse_str( $settings, $parsed_settings );
                    } else {
                        return;
                    }

                    $css = ( !empty( $_POST['css'] ) ?  $_POST['css']  : '' );

                    if( !empty( $css ) ) {
                        parse_str( $css, $parsed_css );
                    } else {
                        return;
                    }
                   
                    update_post_meta( $menu_item_id, 'rtmega_menu_settings', ['switch' => 'on', 'content' => $parsed_settings, 'css' => $parsed_css] );

                }

                wp_send_json_success([
                    'message' => esc_html__( 'Successfully data saved','rt-mega-menu' )
                ]);
                
                wp_die();

            }

        }

        public function rtmega_set_menu_item_mega_button() {
            check_ajax_referer('rtmega_templates_import_nonce', 'nonce');
            if(isset($_POST['menu_item_id'])){

                $menu_item_id = sanitize_text_field($_POST['menu_item_id']);
                $rtmega_menu_item_settings = get_post_meta( $menu_item_id, 'rtmega_menu_settings', true );

                echo wp_send_json_success( $rtmega_menu_item_settings ) ;


            }
            wp_die();
        }

        public function rtmega_delete_menu_options() {
            check_ajax_referer('rtmega_templates_import_nonce', 'nonce');
            if(isset($_POST['menu_item_id'])){

                $menu_item_id = sanitize_text_field($_POST['menu_item_id']);
                $rtmega_menu_item_settings = get_post_meta( $menu_item_id, 'rtmega_menu_settings', true );

                if(isset($rtmega_menu_item_settings)){
                    delete_post_meta( $menu_item_id, 'rtmega_menu_settings' );
                    echo wp_send_json_success( $rtmega_menu_item_settings, 200 );
                }else{
                    echo wp_send_json_success( $rtmega_menu_item_settings, 404 );
                }

                


            }
            wp_die();
        }

        public function rtmega_get_menu_options() {

            check_ajax_referer('rtmega_templates_import_nonce', 'nonce');

            if(isset($_POST['menu_item_id'])){

                $menu_item_id = sanitize_text_field($_POST['menu_item_id']);

                $RTMEGA_menupos_left = $RTMEGA_menupos_left = $RTMEGA_menupos_top = $RTMEGA_menuwidth = $rtmega_menu_item_css = '';

                $rtmega_menu_item_settings = get_post_meta( $menu_item_id, 'rtmega_menu_settings', true );

                

                if(isset($rtmega_menu_item_settings['css'])){
                    $rtmega_menu_item_css = $rtmega_menu_item_settings['css'];

                    if( isset( $rtmega_menu_item_css['left'] ) ){
                        $RTMEGA_menupos_left =  $rtmega_menu_item_css['left'];
                    }
                    if( isset( $rtmega_menu_item_css['right'] ) ){
                        $RTMEGA_menupos_right =  $rtmega_menu_item_css['right'];
                    }
                    if( isset( $rtmega_menu_item_css['top'] ) ){
                        $RTMEGA_menupos_top =  $rtmega_menu_item_css['top'];
                    }

                    if( isset( $rtmega_menu_item_css['width'] ) ){
                        $RTMEGA_menuwidth =  $rtmega_menu_item_css['width'];
                    }

                    if( isset( $rtmega_menu_item_css['full_width'] ) ){
                        $RTMEGA_menu_full_width =  $rtmega_menu_item_css['full_width'];
                    }
                    

                }

                

                ?>
                    <div id="tabs-content">
                        <div id="tab1" class="tab-content">
                            <h2>Select a template</h2>
                            <!-- elementor_library -->
                            <?php 

                                $activeKitId = get_option( 'elementor_active_kit' );

                                $elementor_library_query_args = array(
                                    'post_type' => 'elementor_library',
                                    'post__not_in' => array($activeKitId),
                                    'posts_per_page' => -1,
                                    'orderby' => 'id',
                                    'order' => 'DESC'
                                );

                                $elementor_library_query = new WP_Query($elementor_library_query_args);
                                

                                
                                $content_tempalte = '';
                                if(isset($rtmega_menu_item_settings['content']['rtmega_template'])){
                                    $content_tempalte = $rtmega_menu_item_settings['content']['rtmega_template'];
                                }

                            ?>
                            <form action="" onsubmit="return false" id='rtmega_menu_items_settings'>    
                                <div class="rtmega-menu-option-inputs">
                                    <ul class="rtmega-menu-option-input-list"> 
                                        <li>
                                            <?php 
                                                if($elementor_library_query->have_posts()){
                                                    ?>
                                                        <select name="rtmega_template" id="rtmega-template-select">
                                                            <option value="">Select Template</option>
                                                            <?php 
                                                                
                                                                while ($elementor_library_query->have_posts()) {
                                                                    $elementor_library_query->the_post();
                                                                        ?>
                                                                            <option value="<?php echo esc_attr(get_the_ID());?>" <?php echo esc_attr($content_tempalte == get_the_ID() ? 'selected' : ''); ?> ><?php the_title( );?></option>
                                                                        <?php
                                                                }
                                                                
                                                            ?>
                                                    </select>
                                            <?php }else{
                                                ?>
                                                <strong class="rtmega-text-danger ">Ops! Templates not found. <a href="<?php echo esc_url(admin_url('edit.php?post_type=elementor_library&tabs_group=library')) ?>" title="Click here to create a template.">Create</a> a new template.</strong>
                                                <?php
                                            } ?>
                                            <?php 
                                            if($elementor_library_query->have_posts()){ ?>
                                                <a href="<?php echo esc_url(admin_url('post.php?post='.$content_tempalte.'&action=elementor')) ?>" id="edit-remega-selected-template" class="button" target="_blank">Edit Template</a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <div class="option-label">Badge : </div>
                                            <div class="option-inputs">
                                                
                                                <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/badge_pro_condition.png'); ?>" class="rtmega_pro_warning_img" alt="badge_pro_condition">
                                                <p class="rtmega-pro-notice rtmega-text-danger">Please activate plugin license to use this advanced features</p>
                                                    
                                            </div>
                                        </li>
                                        <li>   
                                            <div class="option-label">Icon : </div>
                                            <div class="option-inputs">
                                                <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/icon_pro_condition.png'); ?>" class="rtmega_pro_warning_img" alt="icon_pro_condition">
                                                <p class="rtmega-pro-notice rtmega-text-danger">Please activate plugin license to use this advanced features</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            
                        </div>
                        <div id="tab2" class="tab-content" style="display: none;">
                            <form action="" onsubmit="return false" id='rtmega_menu_items_css'>          
                                <div class="rtmega-menu-option-inputs">
                                    <ul class="rtmega-menu-option-input-list">
                                        <li>
                                            <div class="option-label">Position : </div>
                                            <div class="option-inputs">
                                                <label>
                                                    <strong>Left (ex: 100px or 100%)</strong>
                                                    <input type="text" name="left" value="<?php echo esc_attr($RTMEGA_menupos_left); ?>">
                                                </label>
                                                <label>
                                                    <strong>Right (ex: 100px or 100%)</strong>
                                                    <input type="text" name="right" value="<?php echo esc_attr($RTMEGA_menupos_right); ?>">
                                                </label>
                                                <label>
                                                    <strong>Top (ex: 100px or 100%)</strong>
                                                    <input type="text" name="top" value="<?php echo esc_attr($RTMEGA_menupos_top); ?>">
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="option-label">Size : </div>
                                            <div class="option-inputs">
                                                <label>
                                                    <strong>Width (ex: 100px or 100%)</strong>
                                                    <input type="text" name="width" value="<?php echo esc_attr($RTMEGA_menuwidth); ?>">
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="option-label">Full Width : </div>
                                            <div class="option-inputs">
                                                <label>
                                                    <strong></strong>
                                                    <input 
                                                    type="checkbox" 
                                                    class="menu-item-checkbox rt_mega_menu_full_width_switch" 
                                                    name="full_width" 
                                                    value="<?php echo esc_attr( $RTMEGA_menu_full_width == 'on' ? 'on' : '' ) ?>" <?php echo esc_attr( $RTMEGA_menu_full_width == 'on' ? 'checked' : '' ) ?>>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="option-label">Badge: </div>
                                            <div class="option-inputs">
                                                    <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/badge_style_pro_condition.png'); ?>" class="rtmega_pro_warning_img" alt="badge_style_pro_condition">
                                                    <p class="rtmega-pro-notice rtmega-text-danger">Please activate plugin license to use this advanced features</p>
            
                                            </div>
                                        </li>
                                        <li>
                                            <div class="option-label">Icon : </div>
                                            <div class="option-inputs">
                                                <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/icon_style_pro_condition.png'); ?>" class="rtmega_pro_warning_img" alt="icon_style_pro_condition">
                                                <p class="rtmega-pro-notice rtmega-text-danger">Please activate plugin license to use this advanced features</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div> <!-- END tabs-content -->

                    <script>
                        (function($){

                            $(".rt_mega_menu_full_width_switch").change(function() {
                                if($(this).prop('checked')) {
                                   $(this).val('on');
                                } else {
                                   $(this).val('off');
                                }
                            });

                        })(jQuery);
                    </script>
                <?php

            }
            
            wp_die();
        }


    }

    $RTMEGA_MENU_Admin_Ajax = new RTMEGA_MENU_Admin_Ajax();
}