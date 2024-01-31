(function()
	{
		CKEDITOR.plugins.add( 'remove_toc', {

			// Register the icons. They must match command names.
			icons: 'remove_toc',
			// The plugin initialization logic goes inside this method.
			init: function( editor ) {

				// Define the editor command that inserts a timestamp.
				editor.addCommand( 'removeToc', {
		
					allowedContent: '*[id,name,class]{margin-left}',
					// Define the function that will be fired when the command is executed.
					exec: function( editor )
					{
						//remove already exisiting tocs...
						var tocElements = editor.document.$.getElementsByClassName("tableOfContents");
						for (var j = tocElements.length; j > 0; j--) 
						{
							var oldid = tocElements[j-1].getAttribute("id").toString();
							editor.document.getById(oldid).remove();
						}
						if (editor.document.getById('main-toc')) {
							editor.document.getById('main-toc').remove();
						}
						if (editor.document.getById('hr-toc')) {
							editor.document.getById('hr-toc').remove();
						}
						//find all headings
						var list = [],
						nodes = editor.editable().find('h3,h4,h5,');
						if ( nodes.count() == 0 )
						{
							return;
						}
						//iterate over headings
						var tocItems = "";
						for ( var i = 0 ; i < nodes.count() ; i++ )
						{
							var node = nodes.getItem(i),
								//level can be used for indenting. it contains a number between 0 (h1) and 5 (h6).
								level = parseInt( node.getName().substr( 1 ) ) - 2;
							node.removeAttribute('id');
							node.removeAttribute('class');
						}
					}
				});

				// Create the toolbar button that executes the above command.
				editor.ui.addButton( 'remove_toc', {
					label: 'Xoá mục lục',
					command: 'removeToc',
					icon: this.path + 'icons/remove_toc.png',
   		         toolbar: 'links'
				});
			}
		}
	)
})
();
