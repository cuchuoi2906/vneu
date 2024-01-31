CKEDITOR.plugins.add( 'textlink_box', {
    icons: 'textlink_box',
    init: function( editor ) {
        var v_label = 'Chọn box textlink';
        editor.addCommand( 'textlinkDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'textlink_box/dsp_choose_textlink_box', 'dlg_poll', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'textlink_box', {
            label: v_label,
            command: 'textlinkDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('textlink_box', {
                label: v_label,
                icon: this.path + 'icons/textlink_box.png',
                command: 'textlinkDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { textlink_box: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});