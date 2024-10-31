<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( !class_exists('RTMEGA_MENU_admin_settings')) {
    class RTMEGA_MENU_admin_settings {

        private $rtmega_menu_options;
    
        function __construct(){   
            
            add_action( 'admin_menu', [$this, 'rtmega_menu_register'] );
            //add_action('rest_api_init', [$this, 'rtmega_menu_settings']);
            add_action( 'admin_init', [$this, 'rtmega_menu_settings'] );   
    
        }   
    
        public function rtmega_menu_register (){
            add_menu_page( 
                __('RT Mega Menu', 'rt-mega-menu'), 
                __('RT Mega Menu', 'rt-mega-menu'), 
                'manage_options', 
                'rtmega-menu', 
                array($this, 'rtmega_menu_plugin_page'), 
                'dashicons-editor-kitchensink',
                100
             );
        }
    
    
    
        public function rtmega_menu_settings() {
    
            register_setting(    
                'rtmega_menu_options_group', // option_group    
                'rtmega_menu_options', // option_name
            );
    
    
    
            add_settings_section(    
                'rtmega_menu_setting_section', // id    
                '', // title    
                array( $this, 'rtmega_menu_settings_section' ), // callback    
                'rtmega-menu-settings' // page    
            );  
    
    
            add_settings_field(
    
                'rt_mega_menu_width', // id
    
                'Width', // title
    
                array( $this, 'rtmega_render_menu_opts' ), // callback
    
                'rtmega-menu-settings', // page
    
                'rtmega_menu_setting_section' // section
    
            );
    
        }
    
        public function rtmega_get_settings_fields() {
    
            $rtmega_settings_fields = array();
    
            $style_fields = array(
                array(
                    'id'    => 'menu_sections_start',
                    'name'  => 'menu_sections_start',
                    'type'  => 'section_start',
                    'label' => 'Main Menu:',
                ),
                array(
                    'id'    => 'menu_color',
                    'name'  => 'menu_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Color',
                ),
                array(
                    'id'    => 'menu_hover_color',
                    'name'  => 'menu_hover_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Hover Color',
                ),
                array(
                    'id'    => 'menu_active_color',
                    'name'  => 'menu_active_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Active Color',
                ),
                array(
                    'id'    => 'submenu_sections_start',
                    'name'  => 'submenu_sections_start',
                    'type'  => 'section_start',
                    'label' => 'Sub Menu:',
                ),
                array(
                    'id'    => 'submenu_color',
                    'name'  => 'submenu_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Color',
                ),
                array(
                    'id'    => 'submenu_hover_color',
                    'name'  => 'submenu_hover_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Hover Color',
                ),
                array(
                    'id'    => 'submenu_bg_color',
                    'name'  => 'submenu_bg_color',
                    'type'  => 'wpcolor',
                    'label' => 'Menu Background Color',
                ),
                array(
                    'id'    => 'submenu_width',
                    'name'  => 'submenu_width',
                    'type'  => 'text',
                    'label' => 'Menu Width',
                ),
                array(
                    'id'    => 'megamenu_sections_start',
                    'name'  => 'megamenu_sections_start',
                    'type'  => 'section_start',
                    'label' => 'Mega Menu:',
                ),
                array(
                    'id'    => 'megamenu_width',
                    'name'  => 'megamenu_width',
                    'type'  => 'text',
                    'label' => 'Menu Width',
                    'default'   => '500px'
                ),
            );
    
    
            $rtmega_settings_fields['style_fields'] = $style_fields;
    
            return $rtmega_settings_fields;
    
    
        }
    
        public function rtmega_menu_plugin_page (){
            
            $this->rtmega_menu_options = get_option( 'rtmega_menu_options' ); ?>
            <h1>RTMEGA Menu Settings</h1>
            <?php settings_errors(); ?>
            <div class="">
                <form method="POST" action="options.php">
                    <div class="tabs rtmega-menu-settings-tabs">
                        <ul id="tabs-nav">
                            <li><a href="#tab1">Mega Menu Styles</a></li>
                            <li><a href="#tab2">Pro Features</a></li>
                        </ul> <!-- END tabs-nav -->
                        <div class="tab-contents-wrapper">
                            <div id="tab1" class="tab-content" style="display: none;">
                                <?php
                                    settings_fields( 'rtmega_menu_options_group' );	
                                    do_settings_sections( 'rtmega-menu-settings' ); 
                                    submit_button();
                                ?>
                            </div>
                            <div id="tab2" class="tab-content" style="display: none;">
                                <h1>RT Menu Free Vs RT Menu Pro Features</h1>
                                <div class="rtmega-features-list-wrapper">
                                    <div class="rtmega-features-list rtmega-features-list-free">
                                        <h3>RT Menu Free</h3>
                                        <ul>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Template Option.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Individual Menu Width Control Option.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Sub Menu Position.</li>
                                            <li><span class="dashicons dashicons-no"></span>Menu Icon Picker.</li>
                                            <li><span class="dashicons dashicons-no"></span>Menu Icon Color.</li>
                                            <li><span class="dashicons dashicons-no"></span>Menu Badge.</li>
                                            <li><span class="dashicons dashicons-no"></span>Menu Badge Color.</li>
                                            <li><span class="dashicons dashicons-no"></span>Menu Badge Background Color.</li>
                                        </ul>
                                    </div>
                                    <div class="rtmega-features-list rtmega-features-list-free">
                                        <h3>RT Menu Pro</h3>
                                        <ul>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Template Option.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Individual Menu Width Control Option.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Sub Menu Position.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Icon Picker.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Icon Color.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Badge.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Badge Color.</li>
                                            <li><span class="dashicons dashicons-yes"></span>Menu Badge Background Color.</li>
                                        </ul>
                                    <div>
                                    
                                </div>
                                <a href="https://rtmega.themewant.com" target="_blank" class="button button-primary">Buy Now</a>
                            </div>
                        </div>
                    </div> <!-- END tabs -->
                    
                </form>
            </div>
            
    
    
            <script>
                (function($){
    
                    $(document).ready(function () {
                    
                        // Show the first tab and hide the rest
                        $('#tabs-nav li:first-child').addClass('active');
                        $('.tab-content').hide();
                        $('.tab-content:first').show();
    
                        // Click function
                        $('#tabs-nav li').click(function(){
                            $('#tabs-nav li').removeClass('active');
                            $(this).addClass('active');
                            $('.tab-content').hide();
                            
                            var activeTab = $(this).find('a').attr('href');
                            $(activeTab).fadeIn();
                            return false;
                        });
    
                        $('input[type="wpcolor"]').wpColorPicker();
    
    
    
                    });
    
                })(jQuery);
            </script>
    
    
            <?php
        }
    
    
        public function rtmega_menu_settings_section (){ 
            
            ?>
    
            <?php
        }
    
        public function rtmega_render_menu_opts() {
    
            ?>
        
            <?php
    
            $rtmega_settings_fields = $this->rtmega_get_settings_fields();
    
            
    
            foreach ($rtmega_settings_fields['style_fields'] as $field) {
                if($field['type'] == 'section_start'){
                    ?>
                    <h3 class="settings-section"><?php echo esc_html($field['label']) ?></h3>
                    <?php
                }else{
    
                    $val = '';
                    if( isset( $this->rtmega_menu_options[$field['name']]) ){
                        $val = $this->rtmega_menu_options[$field['name']];
                    }else if(isset($field['default'])){
                        $val = $field['default'];
                    }
    
    
                    printf(
                        '<div class="settings-item"><label>'. esc_html($field['label']) .'</label>
                        <input type="'. esc_html($field['type']).'" name="rtmega_menu_options['. esc_html($field['name']) .']" id="rtmega_render_menu_opts" value="%s"></div>',esc_html($val)
                    );
                }
                
            }  
            
        }   
    
    }
    new RTMEGA_MENU_admin_settings();
}
