<style type="text/css">
	::-webkit-scrollbar {display: none;}
    .chu_thich_anh_mg *{
        text-align: center !important;
        color: #8d8d8d !important;
        font-size: 16px !important;
        line-height: 28px !important;
    }
</style>
<?php echo $v_html; ?>
<script>window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></' + 'script>')</script>
<script type="text/javascript">
	var html_template = <?php echo json_encode($v_html_body); ?>;
	var html_map = <?php echo json_encode($v_html_map); ?>;

	function isObject(val) {
	    if (val === null) { return false;}
	    return ( (typeof val === 'function') || (typeof val === 'object') );
	}
	function replaceDataInHtmlMap(html_map, obj, html_template) {
		if (isObject(html_map)) {
			for (var map_type in html_map) { // map_type: auto/defined
                if ( map_type == 'auto' // map auto thi bo qua
                    || !html_map.hasOwnProperty(map_type) ) 
                    continue;

		    	for (var elem_type in html_map[map_type]) { // elem_type: image,css,js,...
                    // mang ko ton tai hoac rong thi bo qua
		    		if (!html_map[map_type].hasOwnProperty(elem_type) 
                        || !html_map[map_type][elem_type].length) 
                        continue;
                    // shorten the name
                    var arr_element = html_map[map_type][elem_type];

                    for(var elem_index in arr_element) {
                        for(var key in arr_element[elem_index]) {
                            if (key != 'arr_data') continue;
                            // shorten the name
                            var arr_data = arr_element[elem_index]['arr_data'];

                            for(var attr in arr_data) {
                                if (arr_data[attr].code == obj.code) {
                                    html_map[map_type][elem_type][elem_index]['arr_data'][attr].data = obj.data;
                                }
                            }
                            
                        }
                    }

		    	}
			}
		}
	}
	
	function buildHtml(html_map, html_template) {
		if (isObject(html_map)) {
			for (var map_type in html_map) {
			    if (!html_map.hasOwnProperty(map_type)) continue;

		    	for (var elem_type in html_map[map_type]) {

		    		if (!html_map[map_type].hasOwnProperty(elem_type) 
                        || !html_map[map_type][elem_type].length) 
                        continue;

                    var arr_element = html_map[map_type][elem_type];

                    for(var elem_index in arr_element) {
                        for(var key in arr_element[elem_index]) {
                            if (key == 'arr_data') {
                                var arr_data = arr_element[elem_index]['arr_data'];

                                for(var attr in arr_data) {
                                    html_template = html_template.replace(arr_data[attr].placeholder, arr_data[attr].data);
                                }
                            }
                        }
                    }
		    	}
			}
		}
		return html_template;
	}
</script>
<script type="text/javascript">
	// addEventListener support for IE8
    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent('on' + eventName, eventHandler);
        }
    }
    // Send a message to the parent
    var sendMessage = function (msg) {
        // Make sure you are sending a string, and to stringify JSON
        window.parent.postMessage(msg, '*');
    };

    function displayChanges() {
    	var html = buildHtml(window.html_map, window.html_template);
    	// document.body.innerHTML = html;
        $(document).find('body').html(html);
    }

    function processSingleObj(obj) {
    	replaceDataInHtmlMap(html_map, obj);
    	displayChanges();
    }

    function processMultiObj(arr) {
    	if (arr.length) {
    		$.each(arr, function( index, item ) {
    			replaceDataInHtmlMap(html_map, item);
    		});
    		displayChanges();
    	}
    }
    // Listen to messages from parent window
    bindEvent(window, 'message', function (e) {
        var obj = JSON.parse(e.data);
        console.log(obj);
        if(Array.isArray()) {
        	processMultiObj(obj);
        } else if (isObject(obj)) {
        	if (obj.msg_type == 'preview_iframe') {
        		console.log(obj);
        		var html = buildHtml(obj.data, window.html_template);
        		document.body.innerHTML = html;
        	} else {
        		processSingleObj(obj);
                // fix slide fullpage
                if($.fn.fullpage) {
                    $.fn.fullpage.destroy('all');
                    FullPage.init();
                    // handleFullPage();
                    handleCaptionImage();
                }
                
        	}
        	
        }
    });
    bindEvent(window, 'DOMContentLoaded', function (e) {
        var template_id = '<?php echo $v_template_id; ?>'
        sendMessage('' + template_id);
    });
</script>
