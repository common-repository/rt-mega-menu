<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( !class_exists('RTMEGA_MENU_Nav')) {
    class RTMEGA_MENU_Nav {

        function __construct(){

            add_action( 'admin_footer', array($this, 'rtmega_menu_nav_contents') );
            add_action( 'admin_footer', array( $this, 'rtmega_menu_pop_up_content' ) );

            add_action( "wp_ajax_rtmega_get_menu_switch", array ( $this, 'rtmega_get_menu_switch' ) );
            add_action( "wp_ajax_nopriv_rtmega_get_menu_switch", array ( $this, 'rtmega_get_menu_switch' ) );
        
        }

  
        public function rtmega_get_menu_switch (){

            check_ajax_referer('rtmega_templates_import_nonce', 'nonce');

            $selected_menu_id = isset( $_REQUEST['menu_id'] ) ? absint( $_REQUEST['menu_id'] ) : 0;

            $rtmega_menu_options = get_option( 'rtmega_menu_settings_'.$selected_menu_id);

            $rtmega_menu_options_switch = isset($rtmega_menu_options['enable_menu']) ? $rtmega_menu_options['enable_menu'] : '';

            ?>
                <div class="rtmega-menu-switch-wrapper">
                    <div class="ajax-loader">
                        <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/ajax-loader.gif'); ?>" alt="Ajax Loader">
                    </div>
                    <label class="menu-item-title">
                
                        <input 
                        type="checkbox" 
                        class="menu-item-checkbox rt_mega_menu_switch" 
                        name="rt_mega_menu_switch" 
                        value="<?php echo esc_attr( $rtmega_menu_options_switch == 'on' ? 'on' : '' ) ?>" <?php echo esc_attr( $rtmega_menu_options_switch == 'on' ? 'checked' : '' ) ?>>
                            Eenable RT Mega Menu
                    </label>
                    <p><input type="submit" class="button button-primary button-large save-rtmega-menu" value="Save"></p>
                    </div>
            <?php

            wp_die();

        }
    
        public function rtmega_menu_nav_contents() {

            
            ?>
            <script>
                (function($){

                    $(document).ready(function () {

                        let seleceted_menu = $('input[name="menu"]').val();

                   
                        $.ajax({
                            type: 'POST',
                            url: rtmegamenu_ajax.ajaxurl,
                            data: {
                                action          : "rtmega_get_menu_switch",
                                menu_id         : seleceted_menu,
                                nonce : rtmegamenu_ajax.nonce,
                            },
                            cache: false,
                            success: function(response) {
                                $('#nav-menus-frame').prepend(response);
                            }
                        });


                    });
                    
                })(jQuery);
            
            </script>
            
        			
        <?php }

        public function rtmega_menu_pop_up_content(){
            ob_start();
            $contents = ob_get_clean();

            ?>
                <div id="rtmega-menu-setting-modal" style="display: none;">
                    <div class="rtmega-menu-overlay"></div>
                    <div class="rtmega-modal-body">
                        <div class="ajax-loader">
                            <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/ajax-loader.gif'); ?>" alt="Ajax Loader">
                        </div>
                        <button type="button" class="rtmega-menu-modal-closer"><span class="dashicons dashicons-no"></span></button>
                        <div class="rtmega-modal-content">
                            
                                <div class="tabs">
                                    <ul id="tabs-nav">
                                        <li><a href="#tab1">Content Template</a></li>
                                        <li><a href="#tab2">Style</a></li>
                                    </ul> <!-- END tabs-nav -->
                                    <div class="tab-contents-wrapper">

                                    </div>
                                    <p class="form-status"></p>
                                    <div class="tab-footer">
                                        <button type="button" class="button delete-rt-menu-item-options">Disable Mega Menu</button>
                                        <button type="button" class="button save-rt-menu-item-options">Save</button>
                                    </div>
                                </div> <!-- END tabs -->
                            
                        </div>
                    </div>
                </div>


            <script>
                (function($){

                    $(document).ready(function () {
                        setTimeout(() => {
                            let checkRTMegaMneu = $('input.rt_mega_menu_switch').val();

                            if(checkRTMegaMneu == 'on'){

                                $('#menu-to-edit li').each(function () {
                                    let menuItemId = $(this).find('.menu-item-checkbox').attr('data-menu-item-id');
                                    $(this).addClass('has-rt-mega-menu');
                                    $(this).find('label.item-title').append('<span class="rtmega-menu-opener" data-menu_item_id="'+menuItemId+'"><span class="dashicons dashicons-welcome-widgets-menus"></span>RT Mega Menu</span>')
                               
                               
                                    let rtMegaMenuOpener =  $(this).find('.rtmega-menu-opener');
                                    // Set Menu Item Mega Buttons
                                    $.ajax({
                                        type: 'POST',
                                        url: rtmegamenu_ajax.ajaxurl,
                                        data: {
                                            action          : "rtmega_set_menu_item_mega_button",
                                            menu_item_id    : menuItemId,
                                            nonce : rtmegamenu_ajax.nonce,
                                        },
                                        cache: false,
                                        success: function(response) {
                                            if(response.data !='' && response.data.content.rtmega_template){
                                                $(rtMegaMenuOpener).addClass('has-mega-menu');
                                            }
                                        }
                                    });
                               
                                })

                                



                            }
                        }, 1500);

                        // Show the first tab and hide the rest
                        $('div#rtmega-menu-setting-modal #tabs-nav li:first-child').addClass('active');
                        $('div#rtmega-menu-setting-modal .tab-content').hide();
                        $('div#rtmega-menu-setting-modal .tab-content:first').show();

                        // Click function
                        $('div#rtmega-menu-setting-modal #tabs-nav li').click(function(){
                            $('div#rtmega-menu-setting-modal #tabs-nav li').removeClass('active');
                            $(this).addClass('active');
                            $('div#rtmega-menu-setting-modal .tab-content').hide();
                            
                            var activeTab = $(this).find('a').attr('href');
                            $(activeTab).fadeIn();
                            return false;
                        });

                        //Change Edit url when change template
                        $(document).on('change', 'select#rtmega-template-select', function () {
                            let templateId = $(this).val();
                            let newEditLink = rtmegamenu_ajax.adminURL+'post.php?post='+templateId+'&action=elementor';
                            $('a#edit-remega-selected-template').attr('href', newEditLink);
                        })

                    });

                })(jQuery);
            </script>

            <?php

            
            echo esc_html($contents);

            
        }

       
    }
    $RTMEGA_MENU_Nav = new RTMEGA_MENU_Nav();
}







