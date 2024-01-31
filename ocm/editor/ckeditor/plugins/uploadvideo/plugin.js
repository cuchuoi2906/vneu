CKEDITOR.plugins.add( 'uploadvideo', {
    icons: 'uploadvideo',
    init: function( editor ) {
        var v_label = 'Upload video';
        editor.addCommand( 'uploadVideoDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'upload_video', '', 'width=500,height=400,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'uploadvideo', {
            label: v_label,
            command: 'uploadVideoDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('uploadgroup');
            // Create a manu item
            editor.addMenuItem('uploadvideo_item', {
                label: v_label,
                icon: 'uploadvideo',
                command: 'uploadVideoDialog',
                group: 'uploadgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { uploadvideo_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});