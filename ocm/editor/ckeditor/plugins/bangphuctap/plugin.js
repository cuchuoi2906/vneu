CKEDITOR.plugins.add( 'bangphuctap', {
    icons: 'bangphuctap',
    init: function( editor ) {
        var v_label = 'Đánh dấu bảng SCROLL - RESPONSIVE';
        editor.addCommand( 'bangphuctapDialog', {
            exec: function( editor ) {
                if(document.getElementById('txt_body')){
                    var v_body = window.CKEDITOR.instances.txt_body.getData();
                }
                if(document.getElementById('txt_dulieubongda')){
                    var v_body = window.CKEDITOR.instances.txt_dulieubongda.getData();
                }
                // kiểm tra body
                if(v_body !== ''){
                    var d = new Date();
                    var n = d.getTime();
                    // Đường dẫn ajax
                    var v_url = CONFIG.BASE_URL + 'ajax/news/dsp_chen_bang_phuc_tap/?' + n; 
                    $.ajax({
                        type: "POST",
                        url: v_url,
                        data: {
                            v_body: v_body
                        },
                        async: false,
                        success: function (data) {
                            // chuỗi trả về khác trống
                            if(data !== ''){
                                p_width = 500;
                                p_height = 300;
                                mo_cua_so_popup_overlay('',p_width, p_height);
                                $('#_box_popup').html(data);
                            }
                        }
                    });
                }
            }
        } );
        
        editor.ui.addButton( 'bangphuctap', {
            label: v_label,
            command: 'bangphuctapDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('bangphuctap', {
                label: v_label,
                icon: 'bangphuctap',
                command: 'bangphuctapDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { bangphuctap: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});