CKEDITOR.plugins.add('upload_chum_anh', {
    icons: 'upload_chum_anh',
    init: function (editor) {
        var v_label = 'Upload Chùm ảnh';
        editor.addCommand('upload_chum_anh_Dialog', {
            exec: function (editor) {
                window.open(editor.config.baseHref + 'upload_chum_anh', '', 'width=500,height=400,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
            }
        });
        editor.ui.addButton('upload_chum_anh', {
            label: v_label,
            command: 'upload_chum_anh_Dialog'
        });

        if (CKEDITOR.addCss) {
            CKEDITOR.addCss(
                '.cke_chum_anh {' +
                'background-image: url(' + CKEDITOR.getUrl(this.path + 'icons/upload_chum_anh.png') + ');' +
                'background-position: center center;' +
                'background-repeat: no-repeat;' +
                'border: 1px solid #ccc;' +
                'width: 100%;' +
                'height: 20px;' +
                '}'
            );
            CKEDITOR.addCss(
                '.justified-gallery img {display:none;}'
            );
        }

        if (editor.addMenuItem) {
            editor.addMenuGroup('uploadgroup');
            // Create a manu item
            editor.addMenuItem('upload_chum_anh_item', {
                label: v_label,
                icon: 'upload_chum_anh',
                command: 'upload_chum_anh_Dialog',
                group: 'uploadgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function (element, selection) {
                return { upload_chum_anh_item: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});