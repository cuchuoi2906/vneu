CKEDITOR.plugins.add('giaidau', {
    icons: 'giaidau',
    init: function( editor ) {
        var v_label = 'Bảng tổng hợp trận đấu trực tiếp';
        editor.addCommand( 'giaidauDialog', {
            exec: function( editor ) {
                // kiểm tra text body
                if(document.getElementById('txt_body')){
                    var v_body = window.CKEDITOR.instances.txt_body.getData();
                    // kiểm tra trong body đã có box tổng hợp bóng đá chưa
                    var v_check = v_body.indexOf("box_tong_hop_tran_dau_truc_tiep");
                    if(v_check > 0){
                        // thông báo bài viết đã có box trực tiếp
                        alert('Bài viết đã có box tổng hợp Trận đấu trực tiếp!');
                    }else{
                        //  gắn thêm mã box
                        var v_content = '<div class="box_tong_hop_tran_dau"><!--box_tong_hop_tran_dau_truc_tiep--></div>';
                        //CKEDITOR.instances.txt_body.setData(v_body);
                        oEditor = CKEDITOR.instances['txt_body'];
                        oEditor.insertHtml(v_content);
                    }
                }
            }
        } );
        editor.ui.addButton( 'giaidau', {
            label: v_label,
            command: 'giaidauDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('giaidau_item', {
                label: v_label,
                icon: 'giaidau',
                command: 'giaidauDialog',
                group: 'toolgroup'
            });
        }
    }
});