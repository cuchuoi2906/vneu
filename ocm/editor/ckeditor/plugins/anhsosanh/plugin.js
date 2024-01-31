CKEDITOR.plugins.add( 'anhsosanh', {
    icons: 'anhsosanh_icon',
    init: function( editor ) {
        var v_label = 'Upload ảnh so sánh cho bài viết';
        editor.addCommand( 'anhsosanhDialog', {
            exec: function( editor ) {
                var news_title  = '';
                if(document.frm_dsp_single_item) {
                    if(document.frm_dsp_single_item.txt_title){
                        var news_title  = '/?news_title='+document.frm_dsp_single_item.txt_title.value;
                    }
                }
                window.open(editor.config.baseHref+'anhsosanh_common/dsp_upload_anh_so_sanh'+news_title, '', 'width=800,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'anhsosanh', {
            label: v_label,
            command: 'anhsosanhDialog',
			icon: 'anhsosanh_icon'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('anhsosanhgroup');
            // Create a manu item
            editor.addMenuItem('anhsosanh_item', {
                label: v_label,
                icon: 'anhsosanh_icon',
                command: 'anhsosanhDialog',
                group: 'anhsosanhgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { anhsosanh_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});