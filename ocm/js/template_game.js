/*
 * dmUploader - jQuery Ajax File Uploader Widget
 * https://github.com/danielm/uploader
 *
 * Copyright Daniel Morales <daniel85mg@gmail.com>
 * Released under the MIT license.
 * https://github.com/danielm/uploader/blob/master/LICENSE.txt
 *
 * @preserve
 */
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):"undefined"!=typeof exports?module.exports=e(require("jquery")):e(window.jQuery)}(function(e){"use strict";var t="dmUploader",n=0,i=1,s=2,o=3,r=4,u={auto:!0,queue:!0,dnd:!0,hookDocument:!0,multiple:!0,url:document.URL,method:"POST",extraData:{},headers:{},dataType:null,fieldName:"file",maxFileSize:0,allowedTypes:"*",extFilter:null,onInit:function(){},onComplete:function(){},onFallbackMode:function(){},onNewFile:function(){},onBeforeUpload:function(){},onUploadProgress:function(){},onUploadSuccess:function(){},onUploadCanceled:function(){},onUploadError:function(){},onUploadComplete:function(){},onFileTypeError:function(){},onFileSizeError:function(){},onFileExtError:function(){},onDragEnter:function(){},onDragLeave:function(){},onDocumentDragEnter:function(){},onDocumentDragLeave:function(){}},a=function(e,t){this.data=e,this.widget=t,this.jqXHR=null,this.status=n,this.id=Math.random().toString(36).substr(2)};a.prototype.upload=function(){var t=this;if(!t.canUpload())return t.widget.queueRunning&&t.status!==i&&t.widget.processQueue(),!1;var n=new FormData;n.append(t.widget.settings.fieldName,t.data);var s=t.widget.settings.extraData;return"function"==typeof s&&(s=s.call(t.widget.element,t.id)),e.each(s,function(e,t){n.append(e,t)}),t.status=i,t.widget.activeFiles++,t.widget.settings.onBeforeUpload.call(t.widget.element,t.id),t.jqXHR=e.ajax({url:t.widget.settings.url,type:t.widget.settings.method,dataType:t.widget.settings.dataType,data:n,headers:t.widget.settings.headers,cache:!1,contentType:!1,processData:!1,forceSync:!1,xhr:function(){return t.getXhr()},success:function(e){t.onSuccess(e)},error:function(e,n,i){t.onError(e,n,i)},complete:function(){t.onComplete()}}),!0},a.prototype.onSuccess=function(e){this.status=s,this.widget.settings.onUploadSuccess.call(this.widget.element,this.id,e)},a.prototype.onError=function(e,t,n){this.status!==r&&(this.status=o,this.widget.settings.onUploadError.call(this.widget.element,this.id,e,t,n))},a.prototype.onComplete=function(){this.widget.activeFiles--,this.status!==r&&this.widget.settings.onUploadComplete.call(this.widget.element,this.id),this.widget.queueRunning?this.widget.processQueue():this.widget.settings.queue&&0===this.widget.activeFiles&&this.widget.settings.onComplete.call(this.element)},a.prototype.getXhr=function(){var t=this,n=e.ajaxSettings.xhr();return n.upload&&n.upload.addEventListener("progress",function(e){var n=0,i=e.loaded||e.position,s=e.total||e.totalSize;e.lengthComputable&&(n=Math.ceil(i/s*100)),t.widget.settings.onUploadProgress.call(t.widget.element,t.id,n)},!1),n},a.prototype.cancel=function(e){e=void 0!==e&&e;var t=this.status;return!!(t===i||e&&t===n)&&(this.status=r,this.widget.settings.onUploadCanceled.call(this.widget.element,this.id),t===i&&this.jqXHR.abort(),!0)},a.prototype.canUpload=function(){return this.status===n||this.status===o};var l=function(t,n){return this.element=e(t),this.settings=e.extend({},u,n),this.checkSupport()?(this.init(),this):(e.error("Browser not supported by jQuery.dmUploader"),this.settings.onFallbackMode.call(this.element),!1)};l.prototype.checkSupport=function(){if(void 0===window.FormData)return!1;return!new RegExp("/(Android (1.0|1.1|1.5|1.6|2.0|2.1))|(Windows Phone (OS 7|8.0))|(XBLWP)|(ZuneWP)|(w(eb)?OSBrowser)|(webOS)|(Kindle/(1.0|2.0|2.5|3.0))/").test(window.navigator.userAgent)&&!e('<input type="file" />').prop("disabled")},l.prototype.init=function(){var n=this;this.queue=[],this.queuePos=-1,this.queueRunning=!1,this.activeFiles=0,this.draggingOver=0,this.draggingOverDoc=0;var i=n.element.is("input[type=file]")?n.element:n.element.find("input[type=file]");return i.length>0&&(i.prop("multiple",this.settings.multiple),i.on("change."+t,function(t){var i=t.target&&t.target.files;i&&i.length&&(n.addFiles(i),e(this).val(""))})),this.settings.dnd&&this.initDnD(),0!==i.length||this.settings.dnd?(this.settings.onInit.call(this.element),this):(e.error("Markup error found by jQuery.dmUploader"),null)},l.prototype.initDnD=function(){var n=this;n.element.on("drop."+t,function(e){e.preventDefault(),n.draggingOver>0&&(n.draggingOver=0,n.settings.onDragLeave.call(n.element));var t=e.originalEvent&&e.originalEvent.dataTransfer;if(t&&t.files&&t.files.length){var i=[];n.settings.multiple?i=t.files:i.push(t.files[0]),n.addFiles(i)}}),n.element.on("dragenter."+t,function(e){e.preventDefault(),0===n.draggingOver&&n.settings.onDragEnter.call(n.element),n.draggingOver++}),n.element.on("dragleave."+t,function(e){e.preventDefault(),n.draggingOver--,0===n.draggingOver&&n.settings.onDragLeave.call(n.element)}),n.settings.hookDocument&&(e(document).off("drop."+t).on("drop."+t,function(e){e.preventDefault(),n.draggingOverDoc>0&&(n.draggingOverDoc=0,n.settings.onDocumentDragLeave.call(n.element))}),e(document).off("dragenter."+t).on("dragenter."+t,function(e){e.preventDefault(),0===n.draggingOverDoc&&n.settings.onDocumentDragEnter.call(n.element),n.draggingOverDoc++}),e(document).off("dragleave."+t).on("dragleave."+t,function(e){e.preventDefault(),n.draggingOverDoc--,0===n.draggingOverDoc&&n.settings.onDocumentDragLeave.call(n.element)}),e(document).off("dragover."+t).on("dragover."+t,function(e){e.preventDefault()}))},l.prototype.releaseEvents=function(){this.element.off("."+t),this.element.find("input[type=file]").off("."+t),this.settings.hookDocument&&e(document).off("."+t)},l.prototype.validateFile=function(t){if(this.settings.maxFileSize>0&&t.size>this.settings.maxFileSize)return this.settings.onFileSizeError.call(this.element,t),!1;if("*"!==this.settings.allowedTypes&&!t.type.match(this.settings.allowedTypes))return this.settings.onFileTypeError.call(this.element,t),!1;if(null!==this.settings.extFilter){var n=t.name.toLowerCase().split(".").pop();if(e.inArray(n,this.settings.extFilter)<0)return this.settings.onFileExtError.call(this.element,t),!1}return new a(t,this)},l.prototype.addFiles=function(e){for(var t=0,n=0;n<e.length;n++){var i=this.validateFile(e[n]);if(i){!1!==this.settings.onNewFile.call(this.element,i.id,i.data)&&(this.settings.auto&&!this.settings.queue&&i.upload(),this.queue.push(i),t++)}}return 0===t?this:(this.settings.auto&&this.settings.queue&&!this.queueRunning&&this.processQueue(),this)},l.prototype.processQueue=function(){return this.queuePos++,this.queuePos>=this.queue.length?(0===this.activeFiles&&this.settings.onComplete.call(this.element),this.queuePos=this.queue.length-1,this.queueRunning=!1,!1):(this.queueRunning=!0,this.queue[this.queuePos].upload())},l.prototype.restartQueue=function(){this.queuePos=-1,this.queueRunning=!1,this.processQueue()},l.prototype.findById=function(e){for(var t=!1,n=0;n<this.queue.length;n++)if(this.queue[n].id===e){t=this.queue[n];break}return t},l.prototype.cancelAll=function(){var e=this.queueRunning;this.queueRunning=!1;for(var t=0;t<this.queue.length;t++)this.queue[t].cancel();e&&this.settings.onComplete.call(this.element)},l.prototype.startAll=function(){if(this.settings.queue)this.restartQueue();else for(var e=0;e<this.queue.length;e++)this.queue[e].upload()},l.prototype.methods={start:function(t){if(this.queueRunning)return!1;var i=!1;return void 0===t||(i=this.findById(t))?i?(i.status===r&&(i.status=n),i.upload()):(this.startAll(),!0):(e.error("File not found in jQuery.dmUploader"),!1)},cancel:function(t){var n=!1;return void 0===t||(n=this.findById(t))?n?n.cancel(!0):(this.cancelAll(),!0):(e.error("File not found in jQuery.dmUploader"),!1)},reset:function(){return this.cancelAll(),this.queue=[],this.queuePos=-1,this.activeFiles=0,!0},destroy:function(){this.cancelAll(),this.releaseEvents(),this.element.removeData(t)}},e.fn.dmUploader=function(n){var i=arguments;if("string"!=typeof n)return this.each(function(){e.data(this,t)||e.data(this,t,new l(this,n))});this.each(function(){var s=e.data(this,t);s instanceof l?"function"==typeof s.methods[n]?s.methods[n].apply(s,Array.prototype.slice.call(i,1)):e.error("Method "+n+" does not exist in jQuery.dmUploader"):e.error("Unknown plugin data found by jQuery.dmUploader")})}});

function add_file(id, file_name, has_input) {
	var html = '';
	html += '<li class="item" id="fileUpload'+ id +'" data-fileupload-id="'+ id +'">';
	html +=		'<div class="file-item">';
	html +=			'<a href="javascript:void(0)">'+ file_name +'</a>';
  html +=         '&nbsp;&nbsp;<span class="progress">';
  if(has_input) {
    html += '[tải lên thành công]';
  } else {
    html += '[chờ tải lên]';
  }
  html +=         '</span>';
	html +=			'<a href="javascript:void(0)" class="close">Xóa</a>';
	html +=		'</div>';
  if(has_input) {
    html +=      '<input type="hidden" name="arr_fileupload[]" value="'+id+'">';
  }
	html +=	'</li>';
	$('#listUploadFiles').prepend(html);
}

function checkDuplicateFile(file_name) {
  var list = $('#listUploadFiles');
  
}

function update_progress(id, percent) {
	var bar = $('#fileUpload' + id).find('.progress');
	bar.html(percent + '%');
}

/**
 * @param  string type upload/paste
 */
function extract_html(type) {
	$.ajax({
		url: magCnf.extractHtmlUrl,
		type: 'POST',
		data: {
			type: type,
			template_id: $('#template_id').val()
		},
		success: function(response) {
			var obj = JSON.parse(response);
			if(!obj.error) {
				console.log('Trích xuất dữ liệu: END');
        var result = obj.result;
        $.each(result, function(i, value) {
          if(value.file_type == 'html') {
            var editor = window.CKEDITOR.instances.c_html;
            editor.setData(value.file_content);

            console.log('Set processed html to editor');

          }
        });

			}
		}
	});
}

function saveTemplate() {
  var $elem =  $('#btnSubmitTemplate');
  if($elem.length) {
    $elem.on('click', function (e) {
      var form = $('#frm_dsp_single_item');
        window.CKEDITOR.instances.c_html.updateElement();
        $.ajax({
          type: 'POST',
          url: form.attr('action'),
          data: form.serialize(),
          success: function(res) {
            var obj = JSON.parse(res);
            alert(obj.msg);
            if(!obj.error && obj.redirect_link) {
              window.location.href = obj.redirect_link;
            }
          }
        });
    });
  }
}

function actionAfterFileUploaded(id, data) {
    var file = $('#fileUpload' + id);
    if(file.length) {
      file.append('<input type="hidden" name="arr_fileupload[]" value="'+ data.info.fileupload_id +'"/>');

      if(data.info.file_type == 'html') {
        var editor = window.CKEDITOR.instances.c_html;
        editor.setData(data.info.file_content);
      }
      // if(data.download_result && data.download_result.length) {
      //   $.each(data.download_result, function (i, value) {
      //     if(!value.error) {
      //       add_file(value.info.fileupload_id, value.info.file_name, true);
      //     }
      //   });
      // }
    }
}

function update_file_status(id, status, msg, data) {
	var file = $('#fileUpload' + id);
	
	if(status === 'success') {
		file.attr('data-fileupload-id', data.info.fileupload_id);
		file.removeClass('file-uploading').addClass('file-uploaded');
		file.find('.progress').html(msg);

    
	} else if (status === 'error') {
		file.removeClass('file-uploading').addClass('file-error');
		file.find('.progress').html(msg);
	} else if (status === 'uploading') {
		file.addClass('file-uploading');
	}
}




$(function(){
  
  initUploader();
  // triggerExtractHTML();
	removeAFileUpload();
  triggerUpload();
  saveTemplate();
  removeAllTemplateFileUpload();
  loadViewDefinedParamatersFromHTML();
  // watchElementChange();
});

function initUploader() {
  var uploader = $('#uploadZone');
  if(uploader.length) {
    window.DMUploader = uploader.dmUploader({ //
      url: magCnf.uploadUrl,
      maxFileSize: magCnf.maxFileSize, // 3 Megs 
      auto: false,
      queue: true,
      fieldName: "template_file",
      // allowedTypes: magCnf.allowedMimeTypeRegex,
      extFilter: magCnf.extFilter,
      extraData: {
        template_id: $('#template_id').val()
      },
      onDragEnter: function(){
        this.addClass('active');
      },
      onDragLeave: function(){
        this.removeClass('active');
      },
      onInit: function(){
        // Plugin is ready to use
        console.log('Uploader is ready.');
      },
      onComplete: function(){
        console.log('Trích xuất dữ liệu: START');
        // extract_html('upload');
      },
      onNewFile: function(id, file){
        add_file(id, file.name);
        console.log(file);
      },
      onBeforeUpload: function(id){
        console.log('Bắt đầu upload file: #' + id);
        update_progress(id, 0);
        update_file_status(id, 'uploading', '', {});
      },
      onUploadProgress: function(id, percent){
        // Updating file progress
        update_progress(id, percent);
      },
      onUploadSuccess: function(id, data){
        console.log('Tải lên thành công: #' + id + ' COMPLETED');
        var obj = JSON.parse(data);
        if(!obj.error) {
          update_file_status(id, 'success', obj.msg, obj);
          actionAfterFileUploaded(id,obj);
        } else {
          update_file_status(id, 'error', obj.msg, {});
        }
      },
      onUploadError: function(id, xhr, status, message){
        // Happens when an upload error happens
        console.log(message);
        update_file_status(id, 'error', '[Có lỗi xảy ra trong quá trình upload]', {});
      },
      onFallbackMode: function(){
        // When the browser doesn't support this plugin :(
        console.log('Trình duyệt không hỗ trợ plugin này');
      },
      onFileSizeError: function(file){
        alert('File \'' + file.name + '\' vượt quá dung lượng cho phép');
      },
      onFileTypeError: function(file){
        alert('File \'' + file.name + '\' không đúng định dạng file cho phép');
      },  //params: file
      onFileExtError: function(file){
        alert('File \'' + file.name + '\' không đúng phần mở rộng cho phép');
      }   //params: file
    });
  }
}

function triggerUpload() {
//  var btn = $('#btnStartUpload');
//  if(btn.length) {
//    btn.on('click', function(evt){
//      evt.preventDefault();
        $('#uploadZone').dmUploader('start');
//    });
//  }
}


function removeAFileUpload() {
    var list = $('#listUploadFiles');
    if(list.length) {
      // remove file upload from list
      list.delegate('.close', 'click', function (e) {
        if(confirm("Bạn có chắc muốn xóa đối tượng đã chọn?") == true) {
          var $item = $(this).closest('.item');
          var file_id = $item.attr('data-fileupload-id');

          if($item.hasClass('file-uploading')) {
            $uploader.dmUploader('cancel', file_id);
          } else if($item.hasClass('file-uploaded')) {
            $item.remove();
          }
        }
      });
    }
}

function removeAllTemplateFileUpload() {
  var btn = $('#btnRemoveAllFileUpload');
  if(btn.length) {
    btn.on('click', function() {
        if(confirm("Bạn có chắc muốn xóa tất cả các file đã tải lên?") == true) {
          $('#listUploadFiles').empty();
        } 
    });
  }
  
}
function loadViewDefinedParamatersFromHTML() {
  var zone = $( "#templateInfoZone" );
  if(zone.length) {
    var href = zone.attr('data-request-url');
    var editor = window.CKEDITOR.instances.c_html;
    editor.updateElement();
    // editor.setData(data.info.file_content);
      $.ajax({
        type: 'POST',
        url: href,
        data: {
          html : $('#c_html').val()
        },
        success: function(res) {
           $( "#templateInfoZone" ).html(res);
        }
      });
  }
}
function onMagazineTemplateChange(e, template_id) {
  var that = $(e);
  if(typeof template_id === 'undefined') {
    var template_id = that.val();
  }

  var base_url = that.attr('data-base-url');
  var stt = that.attr('data-stt');
  var old_value = parseInt(that.attr('data-seleted-value'));
  if (old_value > 0) {
    if(confirm("Các thông tin đã nhập sẽ mất, bạn có chắc muốn thực hiện thao tác?") == true) {
      getMagazineContentHtml(that, base_url, stt, template_id);
    }
  } else {
    getMagazineContentHtml(that, base_url, stt, template_id);
  }
}

function getMagazineContentHtml(that, base_url, stt, template_id) {
  $.ajax({
    type: 'GET',
    url: base_url + stt + '/' +template_id ,
    success: function(html) {
      var loadzone = that.closest('.magazine-content-detail');
      if(loadzone.length) {
        loadzone.replaceWith(html);
      }
    }
  });
}

function removeMagazineContentDetail(e) {
  if(confirm("Bạn có chắc muốn xóa đối tượng đã chọn?") == true) {
    $(e).closest('.magazine-content-detail').remove();
  }
}

function addNewContent(e) {
  var that = $(e);
  var next_stt = that.attr('data-next-stt');
  var base_url = that.attr('data-base-url');
  $.ajax({
    type: 'GET',
    url: base_url + next_stt,
    success: function(html) {
      var loadzone = $('#MagazineContentList');
      if (loadzone.length) {
        loadzone.append(html);
        that.attr('data-next-stt', parseInt(next_stt) + 1);

      }
    }
  });
}

function addIframeContent(iframe_id, html_content) {
  var iframe = document.getElementById(iframe_id);
  iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);

  iframe.document.open();
  iframe.document.write(html_content);
  iframe.document.close();

  disableIframeVerticalScrollBar(iframe_id);
}

function disableIframeVerticalScrollBar(iframe_id) {
  var iframe_head = $('#' + iframe_id).contents().find("head");
  // Chrome only
  var css = '<style type="text/css">';
      css +=    '::-webkit-scrollbar {display: none;}';
      css += '</style>';
  $(iframe_head).append(css);
}

function setIframeHeight(iframe_id, target_id) {
  var target = $('#' + target_id);
  var iframe = $('#' + iframe_id);

  if(target.length && iframe.length) {
    var height = target.height();
    iframe.parent().css('max-height', parseInt(height) - 40);
  }
}

function scalePreviewIframe(iframe_id) {
  var iframe = $('#' + iframe_id);

  if (iframe.length) {
    iframe.on('mouseover', function (e) {
      $(this).addClass('preview-fluid');
      $(this).closest('.preview-iframe-wrapper').find('.close-preview-iframe').show();
      $('body').css('margin-top', '400px');
    });
  }
}

function closePreviewIframe() {
  var btn = $('.close-preview-iframe');
  if (btn.length) {
    btn.on('click', function (e) {
       var that = $(this);
       that.closest('.preview-iframe-wrapper').find('.preview-iframe').removeClass('preview-fluid');
       that.hide();
       $('body').css('margin-top', '0');
    });
  }
}

// addEventListener support for IE8
function bindEvent(element, eventName, eventHandler) {
    if (element.addEventListener){
        element.addEventListener(eventName, eventHandler, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + eventName, eventHandler);
    }
}

// Send a message to the child iframe
function sendMessageToIframe(iframe_id, obj) {
  var iframeEl = document.getElementById(iframe_id);
  msg = JSON.stringify(obj);
  // Make sure you are sending a string, and to stringify JSON
  iframeEl.contentWindow.postMessage(msg, '*');
}

function getFirstWords(s, wordsAmount) {
    wordsAmount = parseInt(wordsAmount);
    s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    var arr = s.split(' ');
    if (s.length > wordsAmount) {
      arr = arr.slice(0, wordsAmount);
      return arr.join(' ');
    } else {
      return s;
    }    
}

function checkUrl(elem) {
  data = $(elem).val();
  if (!isUrl(data)) {
    var msg = 'Link không đúng định dang';
    that.parent().find('.error-map-element').html(msg);
    return false;
  }
  // reset error message if success
  $(elem).parent().find('.error-map-element').html('');
  return true;
}

function onTitleOrLinkChange(e) {
  var that = $(e);
  var elem_wrap = that.closest('.magazine-content-detail');
  var iframe_id = elem_wrap.find('.preview-iframe').attr('id');
  var type = that.attr('data-type');
  var data = $(e).val();
  var word_count = countWords(data);
  that.parent().find('.word_count').html(word_count);

  if (type == 'title') {
    if (that.attr('data-metadata')) {
      var metadata = JSON.parse(that.attr('data-metadata'));
      if (metadata.word_count) {
        if (!checkByOperator(word_count, '<=', parseInt(metadata.word_count.value))) {
          that.parent().find('.error-map-element').html('Số lượng từ không hợp lệ. Tối đa ' + metadata.word_count.value + ' từ');        
          return false;
        } else {
          that.parent().find('.error-map-element').html('');
        }
      }
    }
  }

  if (type == 'link' || type == 'iframe') {
    if (!isUrl(data)) {
      that.parent().find('.error-map-element').html('Link không đúng định dang'); return false;
    }
  }

  // if (data) {
    var obj = {};

    obj.type = that.attr('data-type');
    obj.code = that.attr('data-code');
    obj.data = that.val();

    sendMessageToIframe(iframe_id, obj);
  // }
  
}

function onParagraphChange(editor) {
  var that = $('#' + editor.name);
  editor.updateElement();
  var elem_wrap = that.closest('.magazine-content-detail');
  var iframe_id = elem_wrap.find('.preview-iframe').attr('id');
  var html_tag = that.attr('data-html-tag');

  var data = '';

  if (html_tag == 'div') {
    data = editor.getData(); // lay toan bo
  } else {
    data = editor.getData().replace(/(<p[^>]+?>|<p>|<\/p>)/img, ""); // remove p tag
  }

  var text = $('<div>').html(data).text(); // get only text
  var word_count = countWords(text);
  that.parent().find('.word_count').html(word_count);

  if (that.attr('data-metadata')) {
    var metadata = JSON.parse(that.attr('data-metadata'));
    if (metadata.word_count) {
      if (!checkByOperator(word_count, '<=', parseInt(metadata.word_count.value))) {
        that.parent().find('.error-map-element').html('Số lượng từ không hợp lệ. Tối đa ' + metadata.word_count.value + ' từ');
      } else {
        that.parent().find('.error-map-element').html('');
      }
    }
  }

  // if(data) {
    var obj = {};

    obj.type = that.attr('data-type');
    obj.code = that.attr('data-code');
    obj.data = data;

    sendMessageToIframe(iframe_id, obj);
  // }
  
}

// @link: https://gist.github.com/dperini/729294
// @link: https://mathiasbynens.be/demo/url-regex
function isUrl(str) {
  var pattern = new RegExp(
    "^" +
      // protocol identifier
      "(?:(?:https?|ftp)://)" +
      // user:pass authentication
      "(?:\\S+(?::\\S*)?@)?" +
      "(?:" +
        // IP address exclusion
        // private & local networks
        "(?!(?:10|127)(?:\\.\\d{1,3}){3})" +
        "(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})" +
        "(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})" +
        // IP address dotted notation octets
        // excludes loopback network 0.0.0.0
        // excludes reserved space >= 224.0.0.0
        // excludes network & broacast addresses
        // (first & last IP address of each class)
        "(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])" +
        "(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}" +
        "(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))" +
      "|" +
        // host name
        "(?:(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)" +
        // domain name
        "(?:\\.(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)*" +
        // TLD identifier
        "(?:\\.(?:[a-z\\u00a1-\\uffff]{2,}))" +
        // TLD may end with dot
        "\\.?" +
      ")" +
      // port number
      "(?::\\d{2,5})?" +
      // resource path
      "(?:[/?#]\\S*)?" +
    "$", "i"
  );

  return pattern.test(str);
}

function onIframeChange(e) {
  var that = $(e);
  var data = that.val();
  var elem_wrap = that.closest('.magazine-content-detail');
  var iframe_id = elem_wrap.find('.preview-iframe').attr('id');
  
  if (!isUrl(data)) {
    alert('Link không đúng định dang'); return false;
  }

  var obj = {};

  obj.type = that.attr('data-type');
  obj.code = that.attr('data-code');
  obj.data = data;

  sendMessageToIframe(iframe_id, obj);

}

function resetCropInfo(e) {
  var that = $(e);
  var code = that.attr('data-code');
  var stt = parseInt(that.attr('data-stt'));

  $('#crop_image__'+ code +'__' + stt).val('');
  $('#crop_w__'+ code +'__' + stt).val('');
  $('#crop_h__'+ code +'__' + stt).val('');
  $('#crop_x__'+ code +'__' + stt).val('');
  $('#crop_y__'+ code +'__' + stt).val('');
}

function checkWordCount(elem) {
  var that = $(elem);
  var type = that.attr('data-type');
  var metadata = {};
  if (that.attr('data-metadata').length > 0) {
    metadata = JSON.parse(that.attr('data-metadata'));
  }

  var text = '';
  if (type == 'title') {
    text = that.val();
  } else if (type == 'paragraph') {
    data = CKEDITOR.instances[that.attr('name')].getData();
    var text = $('<div>').html(data).text();
  }

  var word_count = countWords(text);

  if (metadata.word_count) {
    if (!checkByOperator(word_count, '<=', parseInt(metadata.word_count.value))) {
      that.parent().find('.error-map-element').html('Số lượng từ không hợp lệ. Tối đa ' + metadata.word_count.value + ' từ');
      return false;
    }
  }
  
  that.parent().find('.error-map-element').html('');
  return true;
}

function checkFile(elem) {
  var that = $(elem);
  var code = that.attr('data-code');
  var type = that.attr('data-type');
  var data_type = that.attr('data-dtype');
  var attr = that.attr('data-attr');
  var is_required = parseInt(that.attr('data-is-required'));
  var stt = parseInt(that.attr('data-stt'));
  var metadata = {};
  if (that.attr('data-metadata').length > 0) {
    metadata = JSON.parse(that.attr('data-metadata'));
  }
  var extension_str = that.attr('data-extension');
  console.log(elem.files);
  var file = elem.files[0];
  window.URL = window.URL || window.webkitURL;
  console.log(file);
  if(file) {
    var width = parseInt(that.attr('data-width'));
    var height = parseInt(that.attr('data-height'));
    var file_size = file.size;

    if (type == 'image') {
      var crop_image = $('#crop_image__'+ code +'__' + stt);
      var crop_width = $('#crop_w__'+ code +'__' + stt);
      var crop_height = $('#crop_h__'+ code +'__' + stt);
      // neu anh duoc crop thi ko check vi ko co cach nao de check
      if (crop_image.length && crop_image.val()) {
        width = parseInt(crop_width.val());
        height = parseInt(crop_height.val());
        file_size = atob(crop_image.val().split(',')[1]).length;
        console.log(file_size);
      }

      if (!crop_image.length || !crop_image.val()) {
        // validate file extension
        if (!validateFileExtension(that, file)) return false;
      }
    }

    if (type == 'image' || type == 'video') {
      if (metadata.width) {
        if (!checkByOperator(width, metadata.width.operator, metadata.width.value)) {
          var msg = 'Chiều rộng ' + (type == 'image' ? 'ảnh' : 'video') +' \'' + file.name + '\' không hợp lệ. Chiều rộng ảnh phải ' + getTextByOperator(metadata.width.operator, metadata.width.value);
          that.parent().parent().find('.error-map-element').html(msg);
          return false;
        }
      }
      if (metadata.height) {
        if (!checkByOperator(height, metadata.height.operator, metadata.height.value)) {
          var msg = 'Chiều cao ' + (type == 'image' ? 'ảnh' : 'video') +' \'' + file.name + '\' không hợp lệ. Chiều cao ảnh phải ' + getTextByOperator(metadata.height.operator, metadata.height.value);
          that.parent().parent().find('.error-map-element').html(msg);
          return false;
        }
      }
    }
    // validate file size
    if (metadata.file_size) {
      if (!checkByOperator(file_size, metadata.file_size.operator, human2byte(metadata.file_size.value))) {
        var msg = 'File \'' + file.name + '\' vượt quá dung lượng cho phép. Dung lượng tối đa ' + metadata.file_size.value ;
        that.parent().parent().find('.error-map-element').html(msg);
        return false;
      }
    }

  } else {
    var old_file = $('input[name="old_map__'+ code +'__' + stt+ '"]');
    if (is_required) {
      if (!old_file.length) {
        var msg = 'Bạn chưa chọn file';
        that.parent().parent().find('.error-map-element').html(msg);
        return false;
      }
    }
  }
  // reset error message if success
  that.parent().parent().find('.error-map-element').html('');
  return true;
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    if (i == 0) return bytes + ' ' + sizes[i];
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

function onChangeFile(e) {
    var that = $(e);
    resetCropInfo(e);
    var type = that.attr('data-type');
    var data_attr = that.attr('data-attr'); 
    var elem_wrap = that.closest('.magazine-content-detail');
    var iframe_id = elem_wrap.find('.preview-iframe').attr('id');
    var data = '';
    var metadata = {};
    if (that.attr('data-metadata').length > 0) {
      metadata = JSON.parse(that.attr('data-metadata'));
    }
    var file = e.files[0];
    window.URL = window.URL || window.webkitURL;

    if(file) {

      that.parent().parent().find('.image-info').html('<span>Ảnh bạn chọn có dung lượng <strong>'+ bytesToSize(file.size) +'</strong></span>');
      
      var data = window.URL.createObjectURL( file );

      if(data) {
        var obj = {};

        // show crop icon when file change
        if (type == 'image') {
          that.removeClass('fluid').addClass('w560');
          that.parent().find('.crop_image').show();
        }

        obj.type = type;
        obj.code = that.attr('data-code');
        obj.data = data;

        sendMessageToIframe(iframe_id, obj);

        // get file width & height
        if (type == 'image' || (type == 'video' && data_attr == 'poster')) {
          validateImageDimension(that, file, data, metadata);
        } else if (type == 'video') {
          validateVideoDimension(that, file, data, metadata);
        }
        // window.URL.revokeObjectURL( data );
      } else {
        alert('Can not create object URL'); return false;
      }
    }
}

function validateFileMimeType(that, file) {
  var strMimeTypes = that.attr('data-mime-type');
  var arrAllowMimeTypes = strMimeTypes.split(',');

  if ($.inArray(file.type, arrAllowMimeTypes) < 0) {
    var msg = 'Kiểu file \'' + file.type + '\' không hợp lệ. Chỉ chấp nhập các kiểu: ' + strMimeTypes;
    that.parent().parent().find('.error-map-element').html(msg);
    return false;
  }
  return true;
}

function validateFileExtension(that, file) {
  var fileExtension = file.name.split('.').pop();
  var strExtensions = that.attr('data-extension');
  var arrAllowExtensions = strExtensions.split(',');

  if ($.inArray(fileExtension, arrAllowExtensions) < 0) {
    var msg = 'Phần mở rộng của file \'' + file.name + '\' không hợp lệ. Chỉ chấp nhập các phần mở rộng sau: ' + strExtensions;
    that.parent().parent().find('.error-map-element').html(msg);
    return false;
  }

  return true;
}

function human2byte(text) { 
    var powers = {'k': 1, 'm': 2, 'g': 3, 't': 4};
    var regex = /(\d+(?:\.\d+)?)\s?(k|m|g|t)?b?/i;

    var res = regex.exec(text);

    return res[1] * Math.pow(1024, powers[res[2].toLowerCase()]);
}

function getTextByOperator(operator, value) {

  if (operator == '>') {
    return 'lớn hơn ' + value;
  } else if (operator == '>=') {
    return 'tối thiểu ' + value;
  } else if (operator == '<') {
    return 'nhỏ hơn ' + value;
  } else if (operator == '<=') {
    return 'tối đa ' + value;
  } else if (operator == '=') {
    return 'bằng ' + value;
  } else {
    return '';
  }
}

function checkByOperator(left_number, operator, right_number){
  left_number = parseInt(left_number);
  right_number = parseInt(right_number);
  if (operator == '>') {
    return left_number > right_number;
  } else if (operator == '>=') {
    return left_number >= right_number;
  } else if (operator == '<') {
    return left_number < right_number;
  } else if (operator == '<=') {
    return left_number <= right_number;
  } else if (operator == '=') {
    return left_number == right_number;
  } else {
    return false;
  }
}

function validateVideoDimension(that, file, data, metadata, callback) {
  var video = document.createElement('video');
  video.addEventListener("loadedmetadata", function () {
    that.attr('data-width', this.videoWidth);
    that.attr('data-height', this.videoHeight);
    that.attr('data-duration', this.duration);
    console.log(this.videoWidth, this.videoHeight, this.duration);

    // Make sure the callback is a function
    if (typeof callback === "function") {
      callback();
    }
  });
  video.src = data;
}

function validateImageDimension(that, file, data, metadata, callback) {
  var img = new Image();
  img.addEventListener("load", function () {
    console.log(this.width, this.height);

    that.attr('data-width', this.width);
    that.attr('data-height', this.height);

    that.parent().parent().find('.image-info').append('<span>, chiều rộng: <strong>'+ this.width +'</strong>, chiều cao: <strong>'+ this.height +'</strong></span>');
    // Make sure the callback is a function
    if (typeof callback === "function") {
      callback();
    }
    
  });

  img.src = data;
}

function selectTemplate(select_id, hdn_id, txt_input){
  var template_id = document.getElementById(hdn_id).value;
  var target = '#'+select_id;
  $(target).val(template_id);
  $('#'+txt_input).val('');
  onMagazineTemplateChange(target, template_id);
  return false;
}

function countWords(s){
    s = $("<div>").html(s).text(); // strip html tag from string
    s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
    s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
    s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
    return s.split(/\s+/).filter(function(str){return str!="";}).length;
}

function PreviewIframe(that) {
  var iframe_id = $(that).attr('id');
  var data = $(that).data('json');
  var obj = {};
  obj.msg_type = 'preview_iframe';
  obj.data = data;
  sendMessageToIframe(iframe_id, obj);
  console.log(iframe_id, data);
}

function toggleHiddenZone(that) {
  var target = $(that).attr('data-target');
  $(target).toggle();
  if ($(target).css('display') == 'none') {
    $(that).text('Xem thêm');
  } else {
    $(that).text('Rút gọn');
  }
}

function resizeIframe(that,target_id) {
  
  var target = $('#' + target_id);

  if(target.length) {
    var height = target.height();
    $(that).parent().css('max-height', parseInt(height) - 40);
  }
  that.style.height = that.contentWindow.document.body.scrollHeight + "px";
}

function checkBeforeSubmit() {
    var is_ok = true;
    mzScrollToElem = null;
    $('.map-element').each(function(index, elem) {
        var data_type = $(elem).attr('data-dtype');
        var type = $(elem).attr('data-type');
        var result = true;
        if(data_type == 'file') {
            result = checkFile(elem);
        } else if (data_type == 'url') {
            result = checkUrl(elem);
        } else if (type == 'title' || type == 'paragraph') {
            result = checkWordCount(elem);
        }
        if (!result) {
          is_ok = false;
          if (!mzScrollToElem) {
            if (type == 'paragraph') {
              mzScrollToElem = $(elem).parent();
            } else {
              mzScrollToElem = elem;
            }
            
          }
        }
    });

    if (!is_ok) {
      $('html, body').animate({
        scrollTop: $(mzScrollToElem).offset().top
      }, 500);
    }

    return is_ok;
}

function updateMagazine(e) {
  // $('#updateMagazine').on('click', function() {
    var that = $(e);
    var form = document.getElementById('frmUpdateMagazine');
    var url = form.getAttribute('action');
    var formData = new FormData(form);

    if (!checkBeforeSubmit()) {      
      alert('Có lỗi xảy ra. Bạn vui lòng kiểm tra lại các dữ liệu đã nhập'); return;
    } else {
      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
          var obj = JSON.parse(response);
          
          if(!obj.error) {
            if (obj.goback) {
              window.location.href = obj.goback;
            }
          }
          if (obj.errors) {
            var tmp = 0;
            $.each(obj.errors, function( index, value ) {
              var input = $('#' + index);
               if (input.attr('data-dtype') == 'file') {
                var parent = input.parent().parent();
               } else {
                var parent = input.parent();
               }
               parent.find('.error-map-element').html(value);
                // scroll to first error
                if (tmp == 0) {
                  $('html, body').animate({
                    scrollTop: input.offset().top
                  }, 500);
                }
                tmp++;
            });
          }
          alert(obj.msg);
        }
      });
    }
}
// BEGIN 23/7/2019 tuannt so sng chuc nang di chuyen len xuong khoi noi dung magazine
function updatePostion(id,pos,stt){
    if(id <= 0 || pos < 0 || stt < 0){
        alert("Kiểm tra lại thông tin đầu vào");
        return false;
    }
        if(stt == 0){
            pos1 = pos+1;
            pos = pos + 2;
            pos_get_class = pos1;
        }
        else{
            pos1 = pos + 1;
            pos_get_class = pos - 1;
        }
        var clss = $("."+pos_get_class).attr('class');
        id1 = clss.substring(2);
        $.ajax({
                    url : v_url_modul_ajax+"/act_update_positon_magazine/"+id+"/"+pos,
                    type : "GET",
                    dataType:"JSON",
                    success : function (result){
                        console.log(result);
                        if(typeof(result) !== "undefined"){
                            $.ajax({
                                url : v_url_modul_ajax+"/act_update_positon_magazine/"+id1+"/"+pos1,
                                type : "GET",
                                dataType:"JSON",
                                success : function (result){
                                    location.reload();
                                }
                            });       
                        }
                        else{
                            alert(result.msg);                                                 
                        }
                    }
                });
    }
// BEGIN 23/7/2019 tuannt so sng chuc nang di chuyen len xuong khoi noi dung magazine