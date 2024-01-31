CKEDITOR.plugins.add( 'poll', {
    icons: 'poll',
    init: function( editor ) {
        var v_label = 'Chọn Poll';
        editor.addCommand( 'pollDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'poll_common/dsp_poll_by_select', 'dlg_poll', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'poll', {
            label: v_label,
            command: 'pollDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('poll_item', {
                label: v_label,
                icon: 'poll',
                command: 'pollDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { poll_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});