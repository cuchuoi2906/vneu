CKEDITOR.plugins.add( 'hoidap', {
    icons: 'hoidap',
    init: function( editor ) {
        var v_label = 'Chọn hỏi đáp';
        editor.addCommand( 'hoidapDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'quan_tri_hoi_dap_cau_hoi/dsp_choose_cau_hoi', 'dlg_hoidap', 'width=900,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'hoidap', {
            label: v_label,
            command: 'hoidapDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('hoidap', {
                label: v_label,
                icon: 'hoidap',
                command: 'hoidapDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { hoidap: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});