<form action="" method="GET" name="frm_dsp_filter">
    <div class="widget">
        <div class="widget_title"><h5>QUẢN TRỊ CÁC CHUYÊN MỤC CỦA WEBSITE 24H</h5><?php
			$v_class_name = html_link($this->className(),false); 						
			?>				
			<div class="div-btn-search" style="float:right">
                <a class="button_big btn_grey" href="javascript:document.frm_dsp_filter.submit();">Tìm</a>
				<a class="button_big btn_grey" href="javascript:location.href='<?php html_link($this->className().'/index?reset_filter=1'); ?>'">Hủy tìm kiếm</a>
			</div>
		</div>
        <div class="widget_body">
            <table cellpadding="2" cellspacing="1" border="0">              
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
									<select size="3" onchange="document.frm_dsp_filter.submit()" style="width:215px" id="sel_category_id" name="sel_category_id">
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
									<input style="width:110px;" type="text" name="txt_user_id" id="txt_user_id" class="auto-title auto-submit ui-autocomplete-input" title="Người sửa cuối" value="Người sửa cuối" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_user_id, document.frm_dsp_filter.sel_user_id,event)" autocomplete="off">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<select size="3" onchange="document.frm_dsp_filter.submit()" style="width:120px" id="sel_user_id" name="sel_user_id">
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
									<input style="width:110px;" type="text" name="txt_status" id="txt_status" class="auto-title auto-submit ui-autocomplete-input" title="Trạng thái" value="Trạng thái" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_status, document.frm_dsp_filter.sel_status,event)" autocomplete="off">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<select size="3" onchange="document.frm_dsp_filter.submit()" style="width:120px" id="sel_status" name="sel_status">
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
						<table border = 0 width  = "100%">
							<tr>
								<td valign = "top">Tên chuyên mục</td>
								<td>
									<input type="text" id="txt_category_name" name="txt_category_name" value="<?php echo $v_cat_name?>" title="Nhập tên chuyên mục" class="auto-title auto-submit" />                        
									<label><br/>
										<input type ="checkbox" name = "chk_dropdown_menu_mobile" value = "1" class = "auto-title auto-submit" id = "chk_dropdown_menu_mobile" <?php echo ($chk_dropdown_menu_mobile==1)?'checked':'';?> /> Hiển thị trong dropdown trên trang mobile
									</label><br/>
									<label>
										<input type ="checkbox" name = "chk_menu_mobile" value = "1" class = "auto-title auto-submit" id = "chk_menu_mobile" <?php echo ($chk_menu_mobile==1)?'checked':'';?> /> Hiển thị là menu ngang mobile
									</label><br/>
									<label>
										<input type ="checkbox" name = "chk_trang_danh_ba" value = "1" class = "auto-title auto-submit" id = "chk_trang_danh_ba" <?php echo ($chk_trang_danh_ba==1)?'checked':'';?> /> Chuyên mục là trang danh bạ
									</label>	
                                    <?php //Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
                                    <label><br/>
										<input type ="checkbox" name = "chk_is_show_on_pc" value = "1" class = "auto-title auto-submit" id = "chk_is_show_on_pc" <?php echo ($chk_is_show_on_pc==1)?'checked':'';?> /> Hiển thị trên menu dọc PC,Ipad
									</label><br/>	
                                    <?php //End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>								
								</td>
							</tr>
						</table>
                    </td>        
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
		document.frm_dsp_filter.submit();
	});
</script>