CKEDITOR.plugins.add( 'codefacebook', {
    icons: 'facebook_icon',
    init: function( editor ) {
        var v_label = 'Nhập code facebook cho bài viết';
        editor.addCommand( 'codefacebookDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'facebook_common/dsp_code_facebook_for_news', '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'codefacebook', {
            label: v_label,
            command: 'codefacebookDialog',
			icon: 'facebook_icon'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('facebookgroup');
            // Create a manu item
            editor.addMenuItem('code_facebook_item', {
                label: v_label,
                icon: 'facebook_icon',
                command: 'codefacebookDialog',
                group: 'facebookgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { code_facebook_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});