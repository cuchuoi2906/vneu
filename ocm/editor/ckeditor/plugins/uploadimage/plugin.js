CKEDITOR.plugins.add( 'uploadimage', {
    icons: 'uploadimage',
    init: function( editor ) {
        var v_label = 'Upload ảnh';
        editor.addCommand( 'uploadImageDialog', {
            exec: function( editor ) {
                var news_title  = '';
                if(document.frm_dsp_single_item) {
                    if(document.frm_dsp_single_item.txt_title){
                        var news_title  = '/?news_title='+document.frm_dsp_single_item.txt_title.value;
                    }
                }
				var v_href = editor.config.baseHref;
                if(typeof v_href === 'undefined'){
                    var v_href = document.location.origin+'/ocm/';
                }
                window.open(v_href+'upload_image'+news_title, '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'uploadimage', {
            label: v_label,
            command: 'uploadImageDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('uploadimage_item', {
                label: v_label,
                icon: 'uploadimage',
                command: 'uploadImageDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { uploadimage_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});