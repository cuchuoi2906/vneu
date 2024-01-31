<form action="" method="GET" name="frm_dsp_filter">
    <div class="widget">
        <div class="widget_title"><h5>Quản trị Title,des,key,slug cho bài viết</h5>
			<div class="div-btn-search" style="float:right">
                <a class="button_big btn_grey" href="javascript:form_filter_before_submit('frm_dsp_filter','txt_news_name');">Tìm</a>
				<a class="button_big btn_grey" href="javascript:location.href='<?php html_link($this->className().'/index?reset_filter=1'); ?>'">Hủy tìm kiếm</a>
			</div>
		</div>
        <div class="widget_body">
            <table cellpadding="1" cellspacing="1" border="0" width ="100%">
                <tr>
                  	<td width ="210px">
						<table cellpadding="0" cellspacing="0" border="0">						
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="text" style="width:204px;" name="txt_category_id" id="txt_category_id" value="Nhập chuyên mục" title="Nhập chuyên mục" class="auto-title" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(false);}"/>
									<!-- bien hidden luu gia tri duoc chon trong hop thoai suggestion-->
									<input type="hidden" name="hdn_category_id" id="hdn_category_id" value=""/>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<select size="3" onchange="javascript:form_filter_before_submit('frm_dsp_filter','txt_news_name');" style="width:215px" id="sel_category_id" name="sel_category_id">
										<option value='0' title="tất cả tat ca" <?php echo $sel_category_id == 0? 'selected':''; ?> >Tất cả</option>
										<?php 
										for($i=0, $s= sizeof($v_arr_category_by_select); $i<$s; $i++) { 
											$selected ='';
											if($v_arr_category_by_select[$i]['ID'] == $sel_category_id) {
												$selected ='selected';
											}	?>
											<option value='<?php echo $v_arr_category_by_select[$i]['ID'];?>' title="<?php echo _utf8_to_ascii($v_arr_category_by_select[$i]['Name']).' '.$v_arr_category_by_select[$i]['Name'];?>" <?php echo $selected; ?>><?php echo $v_arr_category_by_select[$i]['Name'];?></option>
											<?php 
										} ?>	
									</select>
								</td>
							</tr>
						</table>
					</td>    
					<td width ="120px">
						<table cellpadding="0" cellspacing="0" border="0">						
							<tr>
								<td>&nbsp;</td>
								<td>
									<input style="width:110px;" type="text" name="txt_user_id" id="txt_user_id" class="auto-title ui-autocomplete-input" title="Người sửa cuối" value="<?php echo $v_user_name ?>" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_user_id, document.frm_dsp_filter.sel_user_id,event)" autocomplete="off">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<select size="3" onchange="javascript:form_filter_before_submit('frm_dsp_filter','txt_news_name');" style="width:120px" id="sel_user_id" name="sel_user_id">
										<option value='0' title="tất cả tat ca" <?php echo $sel_user_id == 0? 'selected':''; ?> >Tất cả</option>
										<?php 
										$v_arr_user = get_sub_array_in_array($v_arr_user, 'Activate', 1, false);
										for($i=0, $s= sizeof($v_arr_user); $i<$s; $i++) { 
											$selected ='';										
											if($v_arr_user[$i]['ID'] == $sel_user_id) {
												$selected ='selected';
											}	?>
											<option value='<?php echo $v_arr_user[$i]['ID'];?>' title="<?php echo _utf8_to_ascii($v_arr_user[$i]['Username']).' '.$v_arr_user[$i]['Username'];?>" <?php echo $selected; ?>><?php echo $v_arr_user[$i]['Username'];?></option>
											<?php 
										} ?>	
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td width ="120px">
						<table cellpadding="0" cellspacing="0" border="0">						
							<tr>
								<td>&nbsp;</td>
								<td>
									<input style="width:110px;" type="text" name="txt_status" id="txt_status" class="auto-title ui-autocomplete-input" title="Trạng thái" value="<?php echo $v_status_name ?>" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_status, document.frm_dsp_filter.sel_status,event)" autocomplete="off">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<select size="3" onchange="javascript:form_filter_before_submit('frm_dsp_filter','txt_news_name');" style="width:120px" id="sel_status" name="sel_status">
										<option value='-1' title="tất cả tất cả" <?php echo $sel_status == -1? 'selected':''; ?> >Tất cả</option>
										<?php 
										for($i=0, $s= sizeof($v_arr_trang_thai); $i<$s; $i++) { 
											$selected ='';										
											if($v_arr_trang_thai[$i]['c_code'] == $sel_status) {
												$selected ='selected';
											}	?>
											<option value='<?php echo $v_arr_trang_thai[$i]['c_code'];?>' title="<?php echo _utf8_to_ascii($v_arr_trang_thai[$i]['c_name']).' '.$v_arr_trang_thai[$i]['c_name'];?>" <?php echo $selected; ?>><?php echo $v_arr_trang_thai[$i]['c_name'];?></option>
											<?php 
										} ?>	
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table>
							<tr>
								<td>
									Tiêu đề SEO
								</td>
								<td>
									<div style="width:180px" class="form_input">
									<input type="text" name="txt_news_name_seo" id="txt_news_name_seo" value="<?php echo $v_tieu_de_seo;?>" title="Nhập tiêu đề SEO" class="auto-title auto-submit"/>
									</div>
								</td>
							</tr>
                            <?php // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet ?>
							<tr>
								<td>
							<tr>
								<td>
									ID bài viết
								</td>
								<td>
									<div style="width:180px" class="form_input">
									<input type="text" name="txt_news_id" id="txt_news_id" value="<?php echo $v_id_bai_viet;?>" title="Nhập ID bài viết" class="auto-title auto-submit"/>
									</div>
								</td>
							</tr>
						</table>						
					</td>
					<?php /* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>
                    <td width="300px" valign="top">
                        <table>
                            <tbody><tr>
                                <td>Ngày sửa</td>
                                <td>
                                   <input class="frm_dsp_filter_date_select" type="text" style="width:80px" name="txt_edit_date_start" id="txt_edit_date_start" value="<?php echo date('d-m-Y',strtotime($v_edit_date_start)); ?>"> đến 
								<input class="frm_dsp_filter_date_select" type="text" style="width:80px" name="txt_edit_date_end" id="txt_edit_date_end" value="<?php echo date('d-m-Y',strtotime($v_edit_date_end)); ?>">
                                </td>
                            </tr>
                            <?php //Begin 12/5/2020 AnhTT bo_sung_tich_amp ?>
                            <tr> 
                                <td colspan="2">
									<label>
										<input type="checkbox" id="chk_add_amp" name="chk_add_amp" onchange="javascript:form_filter_before_submit('frm_dsp_filter','txt_news_name');" value="1" <?php echo ($v_is_off_amp) ? 'checked' : '' ?>  /> 
										Không áp dụng bản AMP
									</label>
                                </td>
                            </tr>
                            <?php //Begin 12/5/2020 AnhTT bo_sung_tich_amp ?>
                        </tbody></table>
                    </td>
                    <?php /* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>
                </tr>			
            </table>
        </div>    
	</div><?php
    $v_html = $this->dsp_form_button();
	if (!$this->autoRender) {
		echo $v_html;
	}?>
</form>
<script type="text/javascript">
    <?php
    $v_arr_chuyen_muc = add_ascii_column($v_arr_category_by_select, 'Name');// them cot ten khong dau
    echo _tao_file_js_suggestion($v_arr_chuyen_muc,'ds_chuyen_muc','ID','Name_ascii','Name'); // tao mang json
    ?>
	// Tao suggestion cho o tim kiem chuyen muc
	setAutoComplete('txt_category_id',ds_chuyen_muc,'hdn_category_id', 1, 'set_selected_index_to_selectbox("sel_category_id","hdn_category_id","txt_category_id",1,"frm_dsp_filter")');
    $(".auto-submit").change(function(event) {
		form_filter_before_submit('frm_dsp_filter','txt_news_name');
	});
</script>
<?php /* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>
<script>
$(document).ready(function() {
	$(function() {
		$(".frm_dsp_filter_date_select").datepicker();
	});
});
</script>
<?php /* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>