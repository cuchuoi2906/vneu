(function()
	{
		CKEDITOR.plugins.add( 'magazine', {
			// Register the icons. They must match command names.
			icons: 'magazine',
			lang: ['en'],
			// The plugin initialization logic goes inside this method.
			init: function( editor ) {

				// Define the editor command that inserts a timestamp.
				editor.addCommand( 'showMagazineDialog', {
		
					// Define the function that will be fired when the command is executed.
					exec: function( editor )
					{
						window.open(editor.config.baseHref+'magazine/dsp_choose_magazine', 'choose_magazine_dialog', 'width=800,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
					}
				});

				// Create the toolbar button that executes the above command.
				editor.ui.addButton( 'magazine', {
					label: editor.lang.magazine.tooltip,
					command: 'showMagazineDialog',
					icon: this.path + 'icons/magazine.png',
   		         toolbar: 'links'
				});

				if (editor.addMenuItem) {
				    editor.addMenuGroup('toolgroup');
				    // Create a manu item
				    editor.addMenuItem('magazine_item', {
				        label: 'Chọn nội dung magazine',
				        icon: 'magazine',
				        command: 'showMagazineDialog',
				        group: 'toolgroup'
				    });
				}
				if (editor.contextMenu) {
				    editor.contextMenu.addListener(function(element, selection) {
				        return { magazine_item: CKEDITOR.TRISTATE_ON };
				    });
				}
			}
		}
	)
})
();
