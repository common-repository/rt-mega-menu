
<div class="rtmega-templates-library">
    <div class="section-header">
        <h1>Templates Library</h1>
    </div>    
    <div class="rtmega-templates-row">
        <?php 
        $templates = RTMEGA_MENU_Template_Library::instance()->get_rtmega_templates();        
        if(!empty($templates)){
            $templates = json_decode($templates, true);
        }        
        if(is_array($templates)){

            foreach ($templates as $template) {
                $thumbnail_url = 'https://rtmega.themewant.com/wp-content/uploads/2024/05/b-img-1-1.jpg';
                $title =  $template['title'];
                $template_id = $template['template_id'];
                if(isset($template['thumbnail_url']) && !empty($template['thumbnail_url'])) {
                    $thumbnail_url = $template['thumbnail_url'];
                }
                ?>
                    <div class="rtmega-templates-item">
                        <div class="rtmega-template-thumbnail-wrapper">
                            <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="" class="rtmega-template-thumbnail thumbnail-1">
                        </div>
                         <div class="rt-mega-template-actions">
                                <h4 class="template-title"><?php echo esc_html($title); ?></h4>
                                <div class="buttons">
                                    <a href="#" class="button preview_btn" data-thumb_url="<?php echo esc_attr($thumbnail_url);?>" title="<?php echo esc_html($title); ?>">Preview</a>
                                    <a href="#rtmega-template-imoporter-form" class="button import_btn popup-with-form" data-template_id="<?php echo esc_attr($template_id);?>">Import</a>
                                </div>
                         </div>
                     </div>
                 <?php
            }
        }       
        ?>
    </div>     
    <div id="rtmega-menu-setting-modal" style="display: none;">
        <div class="rtmega-menu-overlay"></div>
        <div class="rtmega-modal-body">
            <button type="button" class="rtmega-menu-modal-closer"><span class="dashicons dashicons-no"></span></button>
            <div class="rtmega-modal-content">

                <div id="template-previewer">
                    <?php 
                        $thumbnail_url = 'https://rtmega.themewant.com/wp-content/uploads/2024/05/b-img-1-1.jpg';
                    ?>
                    <img src="<?php echo $thumbnail_url; ?>" alt="">
                </div>

                <form id="rtmega-template-imoporter-form" class="rtmega-template-imoporter-form">
                    <div class="ajax-loader">
                        <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/ajax-loader.gif'); ?>" alt="Ajax Loader">
                    </div>
                
                    <div class="importer-status success-status">
                        <img src="<?php echo esc_url(RTMEGA_MENU_PL_URL.'admin/assets/img/success.gif'); ?>" alt="Ajax Loader">
                        <h2 class="rtmega-text-success">Successfully imported the template!</h2>
                    </div>
                    
                    <div class="form-groups">
                        <div class="form-group">
                            <h2>Import to template library</h2>
                            <input type="hidden" name="template-id" placeholder="Enter your page name">
                            <a href="#" class="button button-primary import_template_btn">Import</a>
                        </div>
                        <hr>
                        <div class="form-group">
                            <h2>Import to a page</h2>
                            <div>
                                <input type="text" name="page-title" placeholder="Enter your page name">
                                <a href="#" class="button button-primary import_template_btn">Import</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>