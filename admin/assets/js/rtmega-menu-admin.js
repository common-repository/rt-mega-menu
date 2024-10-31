(function($){

   var RTMegaMenuAdmin = {

    init: function() {
         $( document )
             .on( 'click.RTMegaMenuAdmin', '.save-rtmega-menu', this.saveMenuOptions )
             .on( 'click.RTMegaMenuAdmin', '.rtmega-menu-opener', this.openMegaMenuModal )
             .on( 'click.RTMegaMenuAdmin', '.rtmega-menu-modal-closer', this.closeMegaMenuModal )
             .on( 'click.RTMegaMenuAdmin', '.save-rt-menu-item-options', this.updateRtmegaMenuItemSettings )
             .on( 'click.RTMegaMenuAdmin', '.delete-rt-menu-item-options', this.deleteRtmegaMenuItemSettings )
             .on( 'click.RTMegaMenuAdmin', '.rtmega_pro_warning_img', this.alertForLicenseActive )
             .on( 'click.RTMegaMenuAdmin', '.rtmega_set_icon_toggle_in_nav_item', this.alertForLicenseActive )
             ;
    },
    alertForLicenseActive: function () { 
        alert('Please activate plugin license to use this advanced features!');
    },
    openMegaMenuModal: function (that) { 
        $('#rtmega-menu-setting-modal').css('display', 'flex');
        $('div#rtmega-menu-setting-modal #tabs-nav li').removeClass('active');
        $('div#rtmega-menu-setting-modal #tabs-nav li:first-child').addClass('active');
        let menuItemId = $(this).attr('data-menu_item_id');
        $('.save-rt-menu-item-options').attr('data-menu_item_id', menuItemId);
        $('.delete-rt-menu-item-options').attr('data-menu_item_id', menuItemId);
        RTMegaMenuAdmin.showMegaMenuModalAjaxLoader($(this));
        RTMegaMenuAdmin.getMenuItemOptions(menuItemId);
    },
    closeMegaMenuModal: function () {
        $('#rtmega-menu-setting-modal').css('display', 'none');
    },
    showMegaMenuModalAjaxLoader: function () { 
        $('#rtmega-menu-setting-modal .ajax-loader').css('display', 'flex');
    },
    hideMegaMenuModalAjaxLoader: function () {
        $('#rtmega-menu-setting-modal .ajax-loader').css('display', 'none');
    },
    deleteRtmegaMenuItemSettings: function( that ){
        RTMegaMenuAdmin.showMegaMenuModalAjaxLoader($(this));
        let menu_id = $("#nav-menu-meta-object-id").val();
        let menu_item_id = $(this).attr('data-menu_item_id');
        let status_form = $('#rtmega-menu-setting-modal .form-status');

        $.ajax({
            type: 'POST',
            url: rtmegamenu_ajax.ajaxurl,
            data: {
                action          : "rtmega_delete_menu_options",
                menu_id         : menu_id,
                menu_item_id    : menu_item_id,
                nonce : rtmegamenu_ajax.nonce,
            },
            cache: false,
            success: function(response) {
                if(response.success == true){
                    $(status_form).html('<span class="rtmega-text-success">Settings Deleted!</span>');
                    setTimeout(() => {
                        $(status_form).html('');
                        location.reload();
                    }, 2000);
                    RTMegaMenuAdmin.hideMegaMenuModalAjaxLoader($(this));
                }
                
            }
        });
    },
    getMenuItemOptions: function (menu_item_id) { 
        $.ajax({
            type: 'POST',
            url: rtmegamenu_ajax.ajaxurl,
            data: {
                action          : "rtmega_get_menu_options",
                menu_item_id    : menu_item_id,
                nonce : rtmegamenu_ajax.nonce,
            },
            cache: false,
            success: function(response) {
                $('#rtmega-menu-setting-modal .tab-contents-wrapper').html(response);
                RTMegaMenuAdmin.hideMegaMenuModalAjaxLoader($(this));
            }
        });
    },

    saveMenuOptions: function( that ){

        var spinner = $(this).parent().parent().find('.ajax-loader');
        spinner.addClass('show');
        

        let menu_id = $("#nav-menu-meta-object-id").val();

        var settings = {
                'enable_menu': $(".rt_mega_menu_switch").is(':checked') === true  ? 'on' : 'off'
            };
        
        $.ajax({
            type: 'POST',
            url: rtmegamenu_ajax.ajaxurl,
            data: {
                action          : "rtmega_update_menu_options",
                actualAction    : 'saveMenuOptions',
                settings        : settings,
                menu_id         : menu_id,
                nonce : rtmegamenu_ajax.nonce,
            },
            cache: false,
            success: function(response) {
                $(that).parent().parent().find('.ajax-loader').removeClass('show');
                location.reload();
            }
        });

    },

    updateRtmegaMenuItemSettings: function( that ){

        RTMegaMenuAdmin.showMegaMenuModalAjaxLoader($(this));
        let menu_id = $("#nav-menu-meta-object-id").val();
        let menu_item_id = $(this).attr('data-menu_item_id');
        let settings = $('#rtmega_menu_items_settings').serialize();
        let css = $('#rtmega_menu_items_css').serialize();
        let status_form = $('#rtmega-menu-setting-modal .form-status');
       
        console.log(css);

        $.ajax({
            type: 'POST',
            url: rtmegamenu_ajax.ajaxurl,
            data: {
                action          : "rtmega_update_menu_options",
                actualAction    : 'saveMenuItemSettings',
                settings        : settings,
                css             : css,
                menu_id         : menu_id,
                menu_item_id    : menu_item_id,
                nonce : rtmegamenu_ajax.nonce,
            },
            cache: false,
            success: function(response) {

                console.log(response);
                
                if(response.success == true){
                    $(status_form).html('<span class="rtmega-text-success">Settings Saved!</span>');
                    setTimeout(() => {
                        $(status_form).html('');
                    }, 2000);
                    RTMegaMenuAdmin.hideMegaMenuModalAjaxLoader($(this));
                }
                
            }
        });

    },


   }

   RTMegaMenuAdmin.init();

    

})(jQuery);