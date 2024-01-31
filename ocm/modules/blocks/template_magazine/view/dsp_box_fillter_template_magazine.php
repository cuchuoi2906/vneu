<?php
$txt_template_status = 'Trạng thái xuất bản';
if ($sel_template_status > 0) {
	$txt_template_status = str_replace('&nbsp;', '', (get_name_in_array($v_arr_trang_thai, 'c_code', 'c_name', $sel_template_status)));
}
$txt_template_use_status = 'Trạng thái sử dụng';
if ($sel_template_use_status != '') {
	$txt_template_use_status = str_replace('&nbsp;', '', get_name_in_array($v_arr_trang_thai_sd, 'c_code', 'c_name', $sel_template_use_status));
}
?>
<form action="" method="GET" name="frm_dsp_filter">
    <div class="widget">
        <div class="widget_title"><h5>Quản lý template magazine</h5>
        	<?php $v_class_name = html_link($this->className(),false); ?>
			<div class="div-btn-search" style="float:right">
                <a class="button_big btn_grey" href="javascript:document.frm_dsp_filter.submit();">Tìm</a>
				<a class="button_big btn_grey" href="javascript:location.href='<?php html_link($this->className().'/index?reset_filter=1'); ?>'">Hủy tìm kiếm</a>
			</div>
		</div>
        <div class="widget_body">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="145"><col width="145"><col width="145">
            	</colgroup>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<input type="text" style="width:125px;" name="txt_template_status" id="txt_template_status" value="Trạng thái xuất bản" title="Trạng thái xuất bản" class="auto-title" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_template_status, document.frm_dsp_filter.sel_template_status,event)"/>
								</td>
							</tr>
							<tr>
								<td>
									<select size="3" onchange="document.frm_dsp_filter.submit()" style="width:137px" id="sel_template_status" name="sel_template_status">
										<option value='-1' title="tất cả tat ca" <?php echo $sel_template_status==-1? 'selected':''; ?> >Tất cả</option>
										<?php
										for($i=0, $s= sizeof($v_arr_trang_thai); $i<$s; $i++) {
											$selected ='';
											if($v_arr_trang_thai[$i]['c_code'] == $sel_template_status) {
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
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<input style="width:125px;" type="text" name="txt_template_use_status" id="txt_template_use_status" class="auto-title ui-autocomplete-input" title="Trạng thái sử dụng" value="Trạng thái sử dụng" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_template_use_status, document.frm_dsp_filter.sel_template_use_status,event)" autocomplete="off">
								</td>
							</tr>
							<tr>
								<td>
									<select size="3" onchange="document.frm_dsp_filter.submit()" style="width:137px" id="sel_template_use_status" name="sel_template_use_status">
										<option value='-1' title="tất cả tat ca" <?php echo $sel_template_use_status==-1? 'selected':''; ?> >Tất cả</option>
										<?php
										for($i=0, $s= sizeof($v_arr_trang_thai_sd); $i<$s; $i++) {
											$selected ='';
											if($v_arr_trang_thai_sd[$i]['c_code'] == $sel_template_use_status) {
												$selected ='selected';
											}	?>
											<option value='<?php echo $v_arr_trang_thai_sd[$i]['c_code'];?>' title="<?php echo _utf8_to_ascii($v_arr_trang_thai_sd[$i]['c_name']).' '.$v_arr_trang_thai_sd[$i]['c_name'];?>" <?php echo $selected; ?>><?php echo $v_arr_trang_thai_sd[$i]['c_name'];?></option>
											<?php
										} ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td>
	                    <table cellpadding="0" cellspacing="0" border="0">
	                        <tr>
	                            <td>
	                                <input style="width:108px;" type="text" name="txt_user_id" id="txt_user_id" class="auto-title ui-autocomplete-input" title="Người sửa cuối" value="<?php echo $v_user_name ?>" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_user_id, document.frm_dsp_filter.sel_user_id,event)" autocomplete="off">
	                            </td>
	                        </tr>
	                        <tr>
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
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
						    <tr>
						    	<td class="tbLabel" width ="100px">Tên template</td>
						        <td>
						            <input type="text" name="txt_template_name" id="txt_template_name" value="<?php echo $txt_template_name; ?>" style="width:120px;" title="" class="auto-title auto-submit" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(true);}"/>
						        </td>
						    </tr>
						    <!-- <tr>
					    		<td class="tbLabel" width ="100px">ID template</td>
					    	    <td>
					    	        <input type="text" name="txt_template_id" id="txt_template_id" value="<?php echo $txt_template_id; ?>" style="width:120px;" title="" class="auto-title auto-submit" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(true);}"/>
					    	    </td>
						    </tr> -->
						    <tr></tr>
						</table>
					</td>
				</tr>
			</table>
        </div>
	</div>
	<?php $v_html = $this->dsp_form_button();
		if (!$this->autoRender) {
			echo $v_html;
		}
	?>
</form>
<script type="text/javascript">
<?php $v_arr_template_status = add_ascii_column($v_arr_trang_thai, 'c_name');// them cot ten khong dau
	echo _tao_file_js_suggestion($v_arr_template_status,'ds_tempate_status','pk_magazine_template','c_name_ascii','c_name');
	$v_arr_template_use_status = add_ascii_column($v_arr_trang_thai_sd, 'c_name');// them cot ten khong dau
		echo _tao_file_js_suggestion($v_arr_template_use_status,'ds_tempate_use_status','pk_magazine_template','c_name_ascii','c_name');
?>
	// Tao suggestion cho o tim kiem chuyen muc
	setAutoComplete('txt_template_status',ds_tempate_status,'template_status', 1, 'set_selected_index_to_selectbox("sel_template_status","template_status","txt_template_status",1,"frm_dsp_filter")');
	setAutoComplete('txt_template_use_status',ds_tempate_use_status,'template_use_status', 1, 'set_selected_index_to_selectbox("txt_template_use_status","template_use_status","txt_template_use_status",1,"frm_dsp_filter")');
</script>
