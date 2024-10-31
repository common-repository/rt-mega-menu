(function($){
    $(document).ready(function () {

        $('.rtmega-templates-item').each(function () { 
            let el = $(this).find('.preview_btn');
            let src = el.attr('data-thumb_url');
            let title = el.attr('title');
            $(el).click(function () { 
                $('#rtmega-template-imoporter-form').css('display', 'none');
                $('#rtmega-menu-setting-modal').css('display', 'flex');
                $('#template-previewer img').attr('src', src);
                $('#template-previewer').css('display', 'block');
             });
         })

         // Importer Modal
         $('.rt-mega-template-actions .import_btn').click(function () {
            let template_id = $(this).attr('data-template_id');
            $('.rtmega-template-imoporter-form .importer-status').removeClass('show');
            $('.rtmega-template-imoporter-form input[name="template-id"]').val(template_id);
            $('#template-previewer').css('display', 'none');
            $('#rtmega-menu-setting-modal').css('display', 'flex');
            $('#rtmega-template-imoporter-form').css('display', 'block');
         })

        // Import data request
        $('body').on('click', 'a.import_template_btn', function(e) {
            e.preventDefault();

            var $this = $(this),
                pageTitle = ( $('.rtmega-template-imoporter-form input[name="page-title"]').val() ) ? ( $('.rtmega-template-imoporter-form input[name="page-title"]').val() ) : '',
                template_id = $('.rtmega-template-imoporter-form input[name="template-id"]').val();


            $.ajax({
                url: ajaxurl,
                data: {
                    'action': 'import_rtmega_template',
                    'templateId' : template_id,
                    'pageTitle' : pageTitle,
                    'nonce' : rtmegamenu_ajax.nonce,
                },
                //dataType: 'JSON',
                beforeSend: function(){
                    $('.rtmega-template-imoporter-form .form-groups').addClass('hide');
                    $('.rtmega-template-imoporter-form .ajax-loader').addClass('show');
                },
                success:function(data) {
                    console.log(data);
                    var template_edit_url = rtmegamenu_ajax.adminURL+"/post.php?post="+ data.id +"&action=elementor";
                    //$('.httemplate-edit').html('<a href="'+ template_edit_url +'" target="_blank">'+ data.edittxt +'</a>');
                },
                complete:function(data){
                    
                    $('.rtmega-template-imoporter-form .ajax-loader').removeClass('show');
                    $('.rtmega-template-imoporter-form .importer-status.success-status').addClass('show');
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });

        });

    });
})(jQuery);