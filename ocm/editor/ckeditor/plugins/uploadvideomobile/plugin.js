CKEDITOR.plugins.add( 'uploadvideomobile', {
    icons: 'uploadvideomobile',
    init: function( editor ) {
        var v_label = 'Upload video mobile';
        editor.addCommand( 'uploadVideomobileDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'upload_video/dsp_upload_video_mobile_form', '', 'width=500,height=400,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'uploadvideomobile', {
            label: v_label,
            command: 'uploadVideomobileDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('uploadgroup');
            // Create a manu item
            editor.addMenuItem('uploadvideomobile_item', {
                label: v_label,
                icon: 'uploadvideomobile',
                command: 'uploadVideomobileDialog',
                group: 'uploadgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { uploadvideomobile_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});