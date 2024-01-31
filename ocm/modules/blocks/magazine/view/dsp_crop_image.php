<link type="text/css" href="<?php html_css('css/jquery.Jcrop.css'); ?>?v=17092018" rel="stylesheet" />
<script type="text/javascript" src="<?php html_js('js/jquery.Jcrop.js'); ?>?v=17092018"></script>
<script type="text/javascript" src="<?php html_link('js/magazine.js'); ?>?v=170920118"></script>

<img src="" id="cropbox" />

<!-- This is the form that our event handler fills -->
<form onsubmit="return false;" class="coords" name="frm_crop_image">
	<input type="hidden" id="stt" name="stt" value="<?php echo $v_stt; ?>"/>
	<input type="hidden" id="code" name="code" value="<?php echo $v_code; ?>"/>
	<input type="hidden" id="x1" name="x1" />
	<input type="hidden" id="y1" name="y1" />
	<input type="hidden" id="x2" name="x2" />
	<input type="hidden" id="y2" name="y2" />
	<div style="margin-top:10px">
		<?php // Begin 3/4/2020 AnhTT toi_uu_crop_anh ?>
		<label>Chiều rộng: <input type="text" size="4" id="w" name="w" onkeyup="keyUpWidth()" /></label>
		<label>Chiều cao: <input type="text" size="4" id="h" name="h" onkeyup="keyUpHeight()" /></label>
		<?php // Begin 3/4/2020 AnhTT toi_uu_crop_anh ?>
	</div>
	<div style="margin:10px 0">
	    <a href="javascript:;" onclick="sendDataToOpener()" class="button_big btn_grey">Cắt ảnh</a>
	    <a href="javascript:;" onclick="window.close()" class="button_big btn_grey">Đóng cửa sổ</a>
	</div>
</form>

<canvas id="previewcanvas"></canvas>

<script>
	// @link: https://developer.mozilla.org/en-US/docs/Web/API/HTMLCanvasElement/toBlob#Polyfill
	// A low performance polyfill based on toDataURL.
	if (!HTMLCanvasElement.prototype.toBlob) {
	  Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
	    value: function (callback, type, quality) {
	      var canvas = this;
	      setTimeout(function() {

	        var binStr = atob( canvas.toDataURL(type, quality).split(',')[1] ),
	            len = binStr.length,
	            arr = new Uint8Array(len);

	        for (var i = 0; i < len; i++ ) {
	          arr[i] = binStr.charCodeAt(i);
	        }

	        callback( new Blob( [arr], {type: type || 'image/jpeg'} ) );

	      });
	    }
	  });
	}
</script>

<script type="text/javascript">
	var v_code = '<?php echo $v_code; ?>';
	var v_stt = '<?php echo $v_stt; ?>';
	var v_target = '<?php echo $v_target; ?>';
	var targetElem = window.opener.document.getElementById(v_target);
	if (targetElem) {
		var file = targetElem.files[0];
		window.CropFileMetadata = {};
		if ( targetElem.getAttribute('data-metadata') ) {
			window.CropFileMetadata = JSON.parse(targetElem.getAttribute('data-metadata'));
		}
		console.log(window.CropFileMetadata);

		window.URL = window.URL || window.webkitURL;
		var data = window.URL.createObjectURL( file );
		$(document).ready(function(){
			
			$('#cropbox').attr('src', data);

			$('#cropbox').Jcrop({
				onChange: showCoords,
				onSelect: showCoords
			});

		});
	}

<?php // Begin 3/4/2020 AnhTT toi_uu_crop_anh ?>
    function keyUpWidth(){
        
        var wi = document.getElementById("w").value;
        var he = document.getElementById("h").value;
        check_data_send_crop(wi,he);
    }
    function keyUpHeight(){    
        var wi = document.getElementById("w").value;
        var he = document.getElementById("h").value;
        check_data_send_crop(wi,he);
    }
    function check_data_send_crop(width,height){
        var v_target = '<?php echo $v_target; ?>';
        
        var targetElem = window.opener.document.getElementById(v_target);
        var width_image = targetElem.getAttribute('data-width');
        var height_image = targetElem.getAttribute('data-height');
            
        if(width && height){
            if(parseInt(width) > parseInt(width_image)){
                width = width_image;
            }else{
                width = width;
            } 
            if(parseInt(height) > parseInt(height_image)){
                height = height_image;
            }else{
                height = height;
            } 
            var data_send = {x:0, y:0, x2:width, y2:height, w:width, h:height};
            $('#cropbox').Jcrop({
                setSelect:[ width, height, 0, 0 ],
				onChange: showCoords(data_send),
				onSelect: showCoords(data_send)
			});
        }
    }
    <?php // Begin 3/4/2020 AnhTT toi_uu_crop_anh ?>
	// Simple event handler, called from onChange and onSelect
	// event handlers, as per the Jcrop invocation above
	function showCoords(c)
	{
		$('#x1').val(c.x);
		$('#y1').val(c.y);
		$('#x2').val(c.x2);
		$('#y2').val(c.y2);
		$('#w').val(c.w);
		$('#h').val(c.h);
		// canvas
		var canvas = document.getElementById('previewcanvas');
		var context = canvas.getContext('2d');
		var img = document.getElementById("cropbox");
		canvas.width = c.w;
		canvas.height = c.h;
		context.drawImage(img, c.x, c.y,c.w, c.h, 0, 0, c.w, c.h);
	};

	function sendDataToOpener() 
	{
		var obj = {};
		obj.msg_type = 'crop_image';
		obj.stt = v_stt;
		obj.code = v_code;
		obj.x1 = $('#x1').val();
		obj.y1 = $('#y1').val();
		// obj.x2 = $('#x2').val();
		// obj.y2 = $('#y2').val();
		obj.w = $('#w').val();
		obj.h = $('#h').val();
		obj.target_elem = v_target;

		if (!parseInt(obj.w) || !parseInt(obj.h)) {
			alert('Bạn chưa chọn vùng để cắt'); 
		    return false;
		}

		var metadata = window.CropFileMetadata;
		// check file dimension
		// if (metadata.width) {
		//   if (!checkByOperator(obj.w, metadata.width.operator, metadata.width.value)) {
		//     alert('Chiều rộng ảnh không hợp lệ. Chiều rộng ảnh phải ' + getTextByOperator(metadata.width.operator, metadata.width.value) ); 
		//     return false;
		//   }
		// }
		// if (metadata.height) {
		//   if (!checkByOperator(obj.h, metadata.height.operator, metadata.height.value)) {
		//     alert('Chiều cao ảnh không hợp lệ. Chiều cao ảnh phải ' + getTextByOperator(metadata.height.operator, metadata.height.value) ); 
		//     return false;
		//   }
		// }

		var canvas = document.getElementById('previewcanvas');
		obj.data = canvas.toDataURL('image/jpeg', 1.0);

		var msg = JSON.stringify(obj);
		// Make sure you are sending a string, and to stringify JSON
		window.opener.postMessage(msg, '*');
		window.close();

		// canvas.toBlob(function(blob) {
		//     obj.data = URL.createObjectURL(blob);
		//     var msg = JSON.stringify(obj);
		//     // Make sure you are sending a string, and to stringify JSON
		//     window.opener.postMessage(msg, '*');
		//     window.close();
		// });
	}
</script>