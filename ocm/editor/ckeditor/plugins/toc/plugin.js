(function()
	{
		CKEDITOR.plugins.add( 'toc', {

			// Register the icons. They must match command names.
			icons: 'toc',
			lang: ['en'],
			// The plugin initialization logic goes inside this method.
			init: function( editor ) {

				// Define the editor command that inserts a timestamp.
				editor.addCommand( 'insertToc', {
		
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
						//find all headings
						var list = [],
						nodes = editor.editable().find('h3,h4,h5,');

						if ( nodes.count() == 0 )
						{
							alert( editor.lang.toc.notitles );
							return;
						}
						//iterate over headings
						var tocItems = "";
						for ( var i = 0 ; i < nodes.count() ; i++ )
						{
							var node = nodes.getItem(i),
								//level can be used for indenting. it contains a number between 0 (h1) and 5 (h6).
								level = parseInt( node.getName().substr( 1 ) ) - 2;

							var text = new CKEDITOR.dom.text( CKEDITOR.tools.trim( node.getText() ), editor.document);

							var id="";
							//check if heading has id
							if(node.hasAttribute("id")) { id = node.getAttribute("id").toString(); }
							//if no id, create an id based on the text
							else 
							{
								id = text.getText().substr(0,20).replace(/[^A-Za-z0-9\_\-]/g, "_");
								node.setAttribute( 'id', id.toString() );
							}
							//create name-attribute based on id
							node.setAttribute('class', id.toString()+' toc_heading' );
				
							//build toc entries as divs
							if (level == 1) {
								tocItemsNews = '<div id="' + id.toString() + '-toc" class="tableOfContents"><a class="toc_menu_link" href="#' + id.toString() + '">' + text.getText().toString() + '</a></div>';
							} else if (level == 2) {
								tocItemsNews = '<div class="stepMl tableOfContents" id="' + id.toString() + '-toc"><a href="#' + id.toString() + '" class="stepbystep toc_menu_link">' + text.getText().toString() + '</a></div>';
							} else if (level == 3) {
								tocItemsNews = '<div class="addInfo tableOfContents" id="' + id.toString() + '-toc"><a href="#' + id.toString() + '" class="addInfoLk toc_menu_link">' + text.getText().toString() + '</a></div>';
							}
							tocItems = tocItems + tocItemsNews;
						}

						//output toc
						var tocNode = '<div id="main-toc" class="mucluc tableOfContents"><div class="muclucCtn"><div class="muclucTit"><span>' + editor.lang.toc.ToC + '</span></div><div class="bodyCt">' + tocItems + '</div></div>';
						editor.insertHtml(tocNode);
					}
				});

				// Create the toolbar button that executes the above command.
				editor.ui.addButton( 'toc', {
					label: editor.lang.toc.tooltip,
					command: 'insertToc',
					icon: this.path + 'icons/toc.png',
   		         toolbar: 'links'
				});
			}
		}
	)
})
();
