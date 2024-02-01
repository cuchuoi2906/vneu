<div class="greyBox">
    <div>
		<div class="tr_button" style="text-align:left">
			<a id="button_luu_bai" href="javascript:;" class="button_big btn_grey button_update" onclick="btn_save_news_onclick(document.frm_dsp_single_item, '<?php html_link('news/act_update_news/SAVE/'.intval($v_news['ID'])); ?>', 'iframe_submit');">Lưu bài</a>
		</div>
	</div>
</div>
<form action="" method="post" name="frm_dsp_single_item" id="frm_dsp_single_item" enctype="multipart/form-data" target="fr_submit">
    <input type="hidden" name="goback" value="<?php echo $_GET['goback']; ?>" />
	<table width="100%" cellpadding="1" cellspacing="1" border="1" bordercolor="#bbb" style="border-collapse:collapse">
        <tr>
            <td>
            <label>
                <input value="1" type="radio" name="rad_news_type" <?php echo ($v_news_type!='album')?'checked':'';?> onclick="window.location.href='<?php html_link('news/dsp_single_item/0')?>'" />
                <img src="<?php html_image('images/logo_video.gif'); ?>" width="16" height="16" /> Bài thường và video&nbsp;&nbsp;
            </label>
            <label>
                <input value="2" type="radio" name="rad_news_type" <?php echo ($v_news_type=='magazine')?'checked':'';?> onclick="window.location.href='<?php html_link('news/dsp_single_item/0?type=magazine')?>'" />
                <img src="<?php html_image('images/magazine_icon.png'); ?>" width="16" height="16" />  Bài Magazine
            </label>
            </td>
        </tr>
        <tr>
            
            <td width="60%" valign="top" id="td_left"> <?php /* edit: Tytv - 19/07/2017 - update_editor_ocm */ ?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="90" class="tbLabel" height="30"><b>Tiêu đề bài</b> <span class="redText">(*)</span></td>
                        <td>
                            <input type="text" id="txt_title" name="txt_title" value="<?php echo html_quote_decode($v_news['Title']); ?>" style="width:80%" />
                            <span id="title_countdown"></span>
                            <script type="text/javascript">setCountdown('txt_title', 255, 'title_countdown')</script>
                        </td>
                    </tr>
                    <tr>
                        <td width="90" class="tbLabel" height="30"><b>Tiêu đề bài tiếng Đức</b> <span class="redText">(*)</span></td>
                        <td>
                            <input type="text" id="txt_title_de" name="txt_title_de" value="<?php echo html_quote_decode($v_news['Title_de']); ?>" style="width:80%" />
                            <span id="title_countdown"></span>
                            <script type="text/javascript">setCountdown('txt_title_de', 255, 'title_de_countdown')</script>
                        </td>
                    </tr>
					<tr>
						<td class="tbLabel" height="35"><b>Ảnh đại diện tỷ lệ 4:3</b> <span class="redText">(*)</span></td>
						<td>
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
										<?php $anh_dai_dien_config = _get_module_config('news','anh_dai_dien');?>
										<input type="file" id="file_summary_image_chu_nhat" name="file_summary_image_chu_nhat" onchange="if(!check_dung_luong_image_truoc_khi_upload(this,5242880,'Bạn chỉ được phép chọn ảnh gif nhỏ hơn 5MB','.gif')){return;};getImageDataURL(this, 'hdn_summary_image_chu_nhat_preview', function(){document.getElementById('icon_crop_image_chu_nhat').style.display=''})" style="max-width:250px" />
										<a href="<?php echo  strpos($v_news['SummaryImg_chu_nhat'], 'image_dai_dien_gif') !== false ? str_replace('.jpg','.gif', $v_news['SummaryImg_chu_nhat']) : $v_news['SummaryImg_chu_nhat']; ?>" target="_blank" rel="lightbox" style="<?php echo $v_news['SummaryImg_chu_nhat'] ? '' : 'visibility:hidden'; ?>">
											<img src="<?php html_image('images/imgpreview.gif');?>" align="absmiddle" width="16" height="16" />
										</a>
										<a id="icon_crop_image_chu_nhat" href="javascript:;" onclick="btn_preview_onclick(document.frm_dsp_single_item, '<?php html_link('crop_image/index/file_summary_image_chu_nhat/txt_summary_image_chu_nhat/hdn_summary_image_chu_nhat_preview/'.$anh_dai_dien_config['kich_thuoc'][0].'/'.$anh_dai_dien_config['kich_thuoc'][1]); ?>', 'new_window');" title="Cắt ảnh" style="display:none"><img src="<?php html_image('images/image-crop-icon.png');?>" align="absmiddle" width="16" height="16" /></a>
										<br />
										<span class="redText">Ảnh chữ nhật <?php echo $anh_dai_dien_config['kich_thuoc'][0].'x'. $anh_dai_dien_config['kich_thuoc'][1].' (max '.(MAX_SUMMARY_IMAGE_SIZE/1024).'Kb). Nếu ảnh gif(max 2.5M)không crop ảnh';?></span>
									</td>
									<td align="right" style="padding-right:14px">
										<textarea name="txt_summary_image_tip" title="Chú thích ảnh" class="auto-title" style="width:100%;height:30px"><?php echo $v_news['SummaryImgTip'] ? $v_news['SummaryImgTip'] : 'Chú thích ảnh'; ?></textarea>
										<input type="hidden" id="hdn_summary_image_chu_nhat_preview" name="hdn_summary_image_chu_nhat_preview" value="<?php echo $v_news['SummaryImg_chu_nhat']; ?>" />
										<input type="hidden" id="txt_summary_image_chu_nhat" name="txt_summary_image_chu_nhat" value="" />
										<input type="hidden" name="SummaryImg_chu_nhat_old" value="<?php echo $v_news['SummaryImg_chu_nhat']; ?>" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="130" class="tbLabel">Ảnh background chuyên mục:</td>
						<td>
							<input type="file" id="file_background_news" name="file_background_news" onchange="getImageDataURL(this, 'hdn_file_background_news', function(){document.getElementById('icon_crop_image_category').style.display=''})" style="max-width:250px" />
							<?php 
							if ($v_news['TopnewsImg'] != '') {
								?>
								<a href="<?php echo $v_news['TopnewsImg']; ?>" target="_blank" rel="lightbox"><img src="<?php html_image('images/imgpreview.gif');?>" align="absmiddle" width="16" height="16" /></a>
								<?php
							}
							$v_arr_anh_dai_dien = _get_module_config('news', 'v_arr_background_news');
							$v_max_size_anh_dai_dien = _get_module_config('news', 'v_max_size_arr_background_news');
							?>
							<a id="icon_crop_image_category" href="javascript:;" onclick="crop_image(document.frm_dsp_single_item, '<?php html_link('crop_image/index/file_background_news/c_background_news/hdn_file_background_news/'.$v_arr_anh_dai_dien[0].'/'.$v_arr_anh_dai_dien[1]); ?>', 'new_window');" title="Cắt ảnh" style="display:none"><img src="<?php html_image('images/image-crop-icon.png');?>" align="absmiddle" width="16" height="16" /></a>
							<input type="hidden" id="hdn_file_background_news" name="hdn_file_background_news" value="<?php echo $v_news['TopnewsImg']; ?>" />
							<input type="hidden" id="c_background_news" name="c_background_news" value="" />
							<span class="redText"><?php echo 'Ảnh chữ nhật có kích thước :'.$v_arr_anh_dai_dien[0].'x'.$v_arr_anh_dai_dien[1].', (size: 200kb)'; ?></span>
							<input type="hidden" id="hdn_background_news" name="hdn_background_news" value="<?php echo $v_news['TopnewsImg']; ?>" />
						</td>
					</tr>
					<tr>
						<td class="tbLabel" height="60"><b>Nội dung </b><span class="redText">(*)</span></td>
						<td class="padBot">
							<div class="padBot" style="text-align:right;margin-right:15px">
								&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="openWindowUploadImageTitle();"><b>&raquo; Upload ảnh</b></a>
								&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="openWindowUploadImage('gif');"><b>&raquo; Upload ảnh GIF</b></a>
							</div>
							<?php
                            if ($v_news_type != 'magazine') {
                                loadEditor('txt_body', $v_news['Body'], 700, 325, 'edit_bong_da_truc_tiep');
                            }else{
                                loadEditor('txt_body', $v_news['Body'], 700, 325, 'default',true,'magazine');
                            }
							?>
						</td>
					</tr>
                    <tr>
						<td class="tbLabel" height="60"><b>Nội dung tiếng Đức</b><span class="redText">(*)</span></td>
						<td class="padBot">
							<?php
                            if ($v_news_type != 'magazine') {
                                loadEditor('txt_body_de', $v_news['Body_de'], 700, 325, 'edit_bong_da_truc_tiep');
                            }else{
                                loadEditor('txt_body_de', $v_news['Body_de'], 700, 325, 'default',true,'magazine');
                            }
							?>
						</td>
					</tr>
                    <tr>
                        <td width="130" class="tbLabel">Trạng thái xuất bản</td>
                        <td>
                            <?php echo html_select_box('sel_publish', $v_arr_trang_thai, 'c_code', 'c_name', $v_news['Status'], $extend='', $add_option = 0); ?>
                        </td>
                    </tr>
				</table>
			</td>
			<td valign="top" style="padding: 10px;" width="40%" id="td_right">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="90" class="tbLabel" height="30"><b>Keyword</b> <span class="redText">(*)<br/>các từ khóa cách nhau bằng dấu (,)</span></td>
						<td valign="middle" class="padBot">
							<textarea name="txt_keywords" id="txt_keywords" style="width:95%"><?php echo $v_news['keywords']; ?></textarea>
						</td>        
					</tr>
					<tr>
						<td width="130" class="tbLabel">Chuyên mục xuất bản<span class="redText">*</span></td>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>&nbsp;</td>
									<td style="width: 400px;">
										<select size="5" id="sel_chuyen_muc" name="sel_chuyen_muc">
											<option <?php if(intval($v_arr_item['fk_category'])<=0){echo 'selected'; } ?> value='-100' title="Chọn chuyên mục">&nbsp;&nbsp;&nbsp;&nbsp;Chọn Chuyên mục</option>
											<?php
											for ($i = 0, $s = sizeof($rs_chuyen_muc_xb); $i < $s; $i++) {
												$selected = '';
												if ($rs_chuyen_muc_xb[$i]['ID'] == $v_news['CategoryID']) {
													$selected = 'selected';
												}
												?>
												<option value='<?php echo $rs_chuyen_muc_xb[$i]['ID']; ?>' title="<?php echo _utf8_to_ascii($rs_chuyen_muc_xb[$i]['Name']) . ' ' . $rs_chuyen_muc_xb[$i]['Name']; ?>" <?php echo $selected; ?>><?php echo $rs_chuyen_muc_xb[$i]['Name']; ?></option>
											<?php }
											?>	
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div class="greyBox">
	<div>
		<div class=" tr_button" style="text-align:left">
			<a id="button_luu_bai" href="javascript:;" class="button_big btn_grey button_update" onclick="btn_save_news_onclick(document.frm_dsp_single_item, '<?php html_link('news/act_update_news/SAVE/'.intval($v_news['ID'])); ?>', 'iframe_submit');">Lưu bài</a>
		</div>
	</div>
</div>
<iframe name="iframe_submit" class=""></iframe>
<script type="text/javascript">
    // addEventListener support for IE8
    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener){
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent('on' + eventName, eventHandler);
        }
    }
    // Listen to message from child window
    bindEvent(window, 'message', function (event) {
        try {
            if (event.origin != event.origin.replace('qlnb.24h.com.vn', '')) {
                // message tra ve tu qlnb
                qlnb_check_data_nb(event);
            } else if (event.origin != event.origin.replace(window.location.hostname, '')) {
                // message from same origin
               if (event.data) {
                   var obj = JSON.parse(event.data);
                   if (obj.msg_type == 'choose_magazine') {
                       window.CKEDITOR.instances.txt_body.setData(obj.content);
                   }
               }
            }
        } catch(err) {
            console.log(err);
        }
    });
</script>