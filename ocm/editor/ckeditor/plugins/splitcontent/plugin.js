CKEDITOR.plugins.add( 'splitcontent', {
    icons: 'splitcontent',
    init: function( editor ) {
        var v_label = 'Tách bài';
        editor.addCommand( 'doSplitContent', {
            exec: function( editor ) {
                pageBreakValue = '<hr title="pagebreak" />';
                if (editor.getData().indexOf(pageBreakValue)!=-1) {
                    alert('Bài viết đã tách!');
                    return false;
                }
                editor.insertHtml(pageBreakValue);
            }
        } );
        editor.ui.addButton( 'splitcontent', {
            label: v_label,
            command: 'doSplitContent'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('splitcontent_item', {
                label: v_label,
                icon: 'splitcontent',
                command: 'doSplitContent',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { splitcontent_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});