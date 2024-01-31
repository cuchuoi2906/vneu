/*
 * Plugin fix loi thao tac nhap bai cua BTV
 * Author : Thangnb 19-02-2018
 */
CKEDITOR.plugins.add('fix_loi_editor', {
    // Plugin initialisiert
    init: function (editor) {
        // Nur für Webkit Browser
        if (CKEDITOR.env.webkit) {
            var getParentsToClosestBlockElement = function (node) {
                var parentsToBlockElement = new Array();
                if (node instanceof CKEDITOR.dom.element || node instanceof CKEDITOR.dom.text) {
                    // Alle Elternknoten des Knotens holen (inkl. des Knotens selbst)
                    var parents = node.getParents(true);
                    // Wenn Elternknoten vorhanden
                    if (parents != null) {
                        // Elternelementse durchschleifen
                        for (var i = 0; i < parents.length; i++) {
                            parentsToBlockElement[i] = parents[i];
                            // Wenn Elternelement ein Blockelement, dann das vorherige
                            // Elternelement wegspeichern und abbrechen
                            if (i >= 1 && parents[i] instanceof CKEDITOR.dom.element
                                    && parents[i].getComputedStyle('display') == 'block') {
                                break;
                            }
                        }
                    }
                }
                return parentsToBlockElement;
            }

            var getPrevNodeSiblingsOfSelection = function () {
                // Rückgabearray
                var siblings = new Array();
                // Selektion holen
                var selection = editor.getSelection();
                var prevNode = null;
                // Wenn Selektion vorhanden
                if (selection != null) {
                    // Ranges der Selektion holen
                    var ranges = selection.getRanges();
                    // Wenn Ranges vorhanden
                    if (ranges.length) {
                        var prevNode = ranges[0].getPreviousNode();
                        // Wenn Knoten vorhanden
                        if (prevNode != null) {
                            var prevNodeParents = getParentsToClosestBlockElement(prevNode);
                            // Wenn Element vorhanden
                            if (prevNodeParents[prevNodeParents.length - 2] != undefined) {
                                var element = prevNodeParents[prevNodeParents.length - 2];
                                // Das Element und alle seine nachfolgenden Elemente (in der gleichen Ebene)
                                // wegspeichern
                                do {
                                    siblings.push(element);
                                    element = element.getPrevious();
                                } while (element != null);
                            }
                            if (prevNodeParents[prevNodeParents.length - 1] != undefined) {
                                var element = prevNodeParents[prevNodeParents.length - 1];
                                // Das Element und alle seine nachfolgenden Elemente (in der gleichen Ebene)
                                // wegspeichern
                                do {
                                    siblings.push(element);
                                    element = element.getPrevious();
                                } while (element != null);
                            }
                        }
                    }
                }
                var redoSelection = function () {
                    if (selection != null && ranges != null && ranges.length) {
                        selection.selectRanges(ranges);
                    }
                }
                return {
                    'siblings': siblings,
                    'redoSelection': redoSelection,
                    'prevNode': prevNode
                };

            }
            // Wenn Editor im Editierungsmodus ist (WYSIWYG Modus)
            editor.on('contentDom', function () {
                editor.document.on('keyup', function (event) {
                    console.log('keyup');
                    var prevNodeSiblingsOnKeyUp = getPrevNodeSiblingsOfSelection();
                    console.log(prevNodeSiblingsOnKeyUp);
                    for (var i = 0; i < prevNodeSiblingsOnKeyUp.siblings.length; i++) {
                        if (prevNodeSiblingsOnKeyUp.siblings[i] == undefined)
                            break;
                        nodeAfterKey = prevNodeSiblingsOnKeyUp.siblings[i];
                        console.log(nodeAfterKey);
                        if (nodeAfterKey.$.parentNode.tagName == 'DIV') {
                            editor.execCommand('undo');
                            //alert('Bạn đang thao tác vào vùng DIV chưa nội dung đặc biệt')
                            break;
                        }
                    }
                });
            });
        }
    }
});