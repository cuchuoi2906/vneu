CKEDITOR.plugins.add( 'quiz', {
    icons: 'quiz',
    init: function( editor ) {
        var v_label = 'Chọn Quiz';
        editor.addCommand( 'quizDialog', {
            exec: function( editor ) {
                window.open(editor.config.baseHref+'quiz/dsp_all_quiz_for_selecting', 'dlg_quiz', 'width=800,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'quiz', {
            label: v_label,
            command: 'quizDialog'
        });
        
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('quiz_item', {
                label: v_label,
                icon: 'quiz',
                command: 'quizDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { quiz_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});