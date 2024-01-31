CKEDITOR.plugins.add( 'addon_pr', {
    icons: 'addon_pr',
    init: function( editor ) {
        var v_label = 'Chọn box add-on sản phẩm PR';
        editor.addCommand( 'addon_prDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'addon_pr/dsp_choose_addon_pr', 'dlg_addon_pr', 'width=900,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'addon_pr', {
            label: v_label,
            command: 'addon_prDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('addon_pr', {
                label: v_label,
                icon: 'addon_pr',
                command: 'addon_prDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { addon_pr: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});
