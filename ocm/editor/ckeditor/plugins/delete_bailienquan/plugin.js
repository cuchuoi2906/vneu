/* 
 * Begin 17/04/2020
 * AnhTT tao plugin xoa html bai lien quan
 */


(function()
	{
            icons: 'clear_black',
		CKEDITOR.plugins.add( 'delete_bailienquan', {
			// The plugin initialization logic goes inside this method.
			init: function( editor ) {
                                // Define the editor command that inserts a timestamp.
				editor.addCommand( 'delete_bailienquan', {
		
					allowedContent: '*[id,name,class]{margin-left}',
					// Define the function that will be fired when the command is executed.
					exec: function( editor )
					{
                                                var element = new CKEDITOR.dom.element(editor.document.$.getElementsByClassName( 'bv-lq' ));
                                                    var count = element.$.length;
                                                if(count != 0){
                                                    Object.keys(element.$).forEach(key => { element.$[0].remove(); })
                                                }
					}
				});
				// Create the toolbar button that executes the above command.
				editor.ui.addButton( 'delete_bailienquan', {
					label: 'Xoá bài liên quan',
                                        icon: this.path + 'icons/clear_black.png',
                                        toolbar: 'links',
					command: 'delete_bailienquan'
				});
			}
		}
	)
})
();
