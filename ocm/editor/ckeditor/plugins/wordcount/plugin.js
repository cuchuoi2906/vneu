/**
 * @license Copyright (c) CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add('wordcount', {
    lang: ['vi'],
    init: function (editor) {
        if (editor.elementMode === CKEDITOR.ELEMENT_MODE_INLINE) {
            return;
        }
        if(editor.name != 'txt_body') {
            return;
        }

        var intervalId,
            lastWordCount,
            lastCharCount = 0,
            limitReachedNotified = false,
            limitRestoredNotified = false;

        // Default Config
        var defaultConfig = {
            showWordCount: true,
            showCharCount: false,
            charLimit: 'unlimited',
            wordLimit: 10000,
            pageBreak1Limit: 900,
            pageBreak2Limit: 700,
            pageBreak: '<hr title="pagebreak" />',
            countHTML: false
        };

        // Get Config & Lang
        var config = CKEDITOR.tools.extend(defaultConfig, editor.config.wordcount || {}, true);

        CKEDITOR.document.appendStyleSheet(this.path + 'css/wordcount.css');

        function counterId(editorInstance) {
            return 'cke_wordcount_' + editorInstance.name;
        }

        function counterElement(editorInstance) {
            return document.getElementById(counterId(editorInstance));
        }

        function strip(html) {
            var tmp = document.createElement("div");
            tmp.innerHTML = html;

            if (tmp.textContent == '' && typeof tmp.innerText == 'undefined') {
                return '0';
            }
            return tmp.textContent || tmp.innerText;
        }

        function updateCounter(editorInstance) {
            var wordCount = 0,
                charCount = 0,
                wordpageBreak1Count = 0,
                wordpageBreak2Count = 0,
                normalizedText,
                html,
                text,
                defaultFormat = '<span class="cke_path_item">';

            if(config.showCharCount) {
                var charLabel = editor.lang.wordcount[config.countHTML ? 'CharCountWithHTML' : 'CharCount'];
                defaultFormat += charLabel + '&nbsp;%charCount%';
                if (config.charLimit != 'unlimited') {
                    defaultFormat += '&nbsp;(' + editor.lang.wordcount.limit + '&nbsp;' + config.charLimit + ')';
                }
            }
            if (config.showWordCount) {
                defaultFormat += editor.lang.wordcount.WordCount + '&nbsp;%wordCount%' + ' từ còn ' + ' &nbsp;%word%';
                if (config.wordLimit != 'unlimited') {
                    defaultFormat += '&nbsp;(' + editor.lang.wordcount.limit + '&nbsp;' + config.wordLimit + ')';
                }
            }

            if (text = editorInstance.getData()) {
                normalizedText = replaceText(text);
                normalizedText = strip(normalizedText);

                if(text.indexOf(config.pageBreak) != -1) {
                    var page = text.split(config.pageBreak);
                    wordpageBreak1Count = (page[0] == '') ? 0 : textLength(strip(replaceText(page[0])));
                    wordpageBreak2Count = (page[1] == '') ? 0 : textLength(strip(replaceText(page[1])));
                    defaultFormat = '<span class="cke_path_item">';
                    defaultFormat += editor.lang.wordcount.pageBreak1 + '&nbsp;%wordpageBreak1Count%&nbsp;' + ' còn ' + '&nbsp;%wordpageBreak1%';
                    if (config.pageBreak1Limit != 'unlimited') {
                        defaultFormat += '&nbsp;(' + 'tối đa' + '&nbsp;' + config.pageBreak1Limit + ' từ), ';
                    }
                    defaultFormat += editor.lang.wordcount.pageBreak2 + '&nbsp;%wordpageBreak2Count%&nbsp;' + ' còn ' + '&nbsp;%wordpageBreak2%';
                    if (config.pageBreak2Limit != 'unlimited') {
                        defaultFormat += '&nbsp;(' + 'tối đa' + '&nbsp;' + config.pageBreak2Limit + ' từ)';
                    }
                } else {
                    if (config.showWordCount) {
                        wordCount = textLength(normalizedText);
                    }
                }

                if (config.showCharCount) {
                    charCount = config.countHTML ? text.length : normalizedText.length;
                }
            }
            defaultFormat += '</span>';
            var html = defaultFormat
                        .replace('%wordCount%', wordCount)
                        .replace('%word%', (config.wordLimit - wordCount))
                        .replace('%charCount%', charCount)
                        .replace('%wordpageBreak1Count%', wordpageBreak1Count)
                        .replace('%wordpageBreak1%', (config.pageBreak1Limit - wordpageBreak1Count))
                        .replace('%wordpageBreak2Count%', wordpageBreak2Count)
                        .replace('%wordpageBreak2%', (config.pageBreak2Limit - wordpageBreak2Count));

            counterElement(editorInstance).innerHTML = html;

            lastWordCount = wordCount;
            lastCharCount = charCount;

            // Check for word limit
            if (config.showWordCount && (wordCount > config.wordLimit || wordpageBreak1Count > config.pageBreak1Limit || wordpageBreak2Count > config.pageBreak2Limit)) {
                limitReached(editor, limitReachedNotified);
            } else if (!limitRestoredNotified && wordCount < config.wordLimit) {
                limitRestored(editor);
            }
            return true;
        }

        function limitReached(editorInstance, notify) {
            limitReachedNotified = true;
            limitRestoredNotified = false;

            //editorInstance.execCommand('undo');
            if (!notify) {
                counterElement(editorInstance).className += " cke_wordcountLimitReached";

                editorInstance.fire('limitReached', {}, editor);
            }
            // lock editor
            //editorInstance.config.Locked = 1;
            //editorInstance.fire("change");
        }

        function limitRestored(editorInstance) {
            limitRestoredNotified = true;
            limitReachedNotified = false;
            editorInstance.config.Locked = 0;

            counterElement(editorInstance).className = "cke_wordcount";
        }

        function replaceText(text) {
            return text.replace(/(\r\n|\n|\r)/gm, ' ')
                        .replace(/^\s+|\s+$/g, '')
                        .replace('&nbsp;', '');
        }
		
		function textLength(text) {
			var words = text.split(/\s+/);
			for (var wordIndex = words.length - 1; wordIndex >= 0; wordIndex--) {
				if (words[wordIndex].match(/^([\s\t\r\n]*)$/)) {
					words.splice(wordIndex, 1);
				}
			}
			return words.length;
		}

        editor.on('uiSpace', function (event) {
            if (event.data.space == 'bottom') {
                event.data.html += '<div id="' + counterId(event.editor) + '" class="cke_wordcount" style=""' + ' title="' + editor.lang.wordcount.title + '"' + '>&nbsp;</div>';
            }
        }, editor, null, 100);
        editor.on('dataReady', function (event) {
            var count = event.editor.getData().length;
            if (count > config.wordLimit) {
                limitReached(editor);
            }
            updateCounter(event.editor);
        }, editor, null, 100);
        editor.on('key', function (event) {
            updateCounter(event.editor);
        }, editor, null, 100);
        editor.on('afterPaste', function (event) {
            updateCounter(event.editor);
        });
        /*editor.on('change', function (event) {
            updateCounter(event.editor);
        }, editor, null, 100);*/
        editor.on('focus', function (event) {
            editorHasFocus = true;
            intervalId = window.setInterval(function () {
                updateCounter(editor);
            }, 300, event.editor);
        }, editor, null, 300);
        editor.on('blur', function () {
            if (intervalId) {
                window.clearInterval(intervalId);
            }
        }, editor, null, 300);
        
        if (!String.prototype.trim) {
            String.prototype.trim = function () {
                return this.replace(/^\s+|\s+$/g, '');
            };
        }
    }
});