CKEDITOR.plugins.add('uploadaudio', {
    icons: 'uploadaudio',
    init: function (editor) {
        var v_label = 'Upload audio';
        editor.addCommand('uploadAudioDialog', {
            exec: function (editor) {
                window.open(editor.config.baseHref + 'upload_audio', '', 'width=500,height=400,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        });
        editor.ui.addButton('uploadaudio', {
            label: v_label,
            command: 'uploadAudioDialog'
        });

        if (CKEDITOR.addCss) {
            CKEDITOR.addCss(
                '.news-audio img.cke_script {' +
                'background-image: url(' + CKEDITOR.getUrl(this.path + 'icons/audio.png') + ');' +
                'background-position: center center;' +
                'background-repeat: no-repeat;' +
                'border: 1px solid #ccc;' +
                'width: 100%;' +
                'height: 20px;' +
                '}'
            );
        }

        if (editor.addMenuItem) {
            editor.addMenuGroup('uploadgroup');
            // Create a manu item
            editor.addMenuItem('uploadaudio_item', {
                label: v_label,
                icon: 'uploadaudio',
                command: 'uploadAudioDialog',
                group: 'uploadgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function (element, selection) {
                return { uploadaudio_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});