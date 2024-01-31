CKEDITOR.plugins.add( 'templatetable', {
    icons: 'templatetable',
    init: function( editor ) {
        var v_label = 'Chọn bảng vào bài viết';
        editor.addCommand( 'templateTableDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'news_common/dsp_template_table', 'dlg_template_table', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'templatetable', {
            label: v_label,
            command: 'templateTableDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('templatetable_item', {
                label: v_label,
                icon: 'templatetable',
                command: 'templateTableDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { templatetable_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});