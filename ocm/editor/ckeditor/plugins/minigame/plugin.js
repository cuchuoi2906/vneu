CKEDITOR.plugins.add( 'minigame', {
    icons: 'minigame',
    init: function( editor ) {
        var v_label = 'Chọn minigame';
        editor.addCommand( 'minigameDialog', {
            exec: function( editor ) {
                //console.log(3231);
                window.open(editor.config.baseHref+'minigame_game/dsp_choose_minigame', 'dlg_poll', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        } );
        editor.ui.addButton( 'minigame', {
            label: v_label,
            command: 'minigameDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('minigame', {
                label: v_label,
                icon: this.path + 'icons/minigame.png',
                command: 'minigameDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { minigame: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});