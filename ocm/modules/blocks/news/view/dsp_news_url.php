<div id="dialog" class="header-popup">Thông tin url bài viết</div>
<div class="popupContent">
	<div>
        <p>
            <span style="float:left;font-weight:bold;">Link bài viết:</span></br>
            <textarea id="textarea_preview" style="width:80%;background:#f9f7f7;border:#c7c7c7 solid 1px;" onclick="this.focus();this.select()"><?php echo $v_url ?></textarea>
        </p>
        <p>
            <span style="float:left;font-weight:bold;">Link preview bài viết:</span></br>
            <textarea id="textarea_preview_ocm"  style="width:80%;background:#f9f7f7;border:#c7c7c7 solid 1px;" onclick="this.focus();this.select()"><?php echo $v_url.'?preview=1' ?></textarea>
        </p>
	</div>	
	<div style="padding-top:15px;text-align:center"><a href="javascript:top.close_box_popup()"><img src="<?php html_image('images/btn-thoat.gif')?>" /></a></div>
</div>
