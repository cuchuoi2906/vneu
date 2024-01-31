<?php
$txt_template_status = 'Trạng thái xuất bản';
if ($sel_template_status > 0) {
	$txt_template_status = str_replace('&nbsp;', '', (get_name_in_array($v_arr_trang_thai, 'c_code', 'c_name', $sel_template_status)));
}
$txt_category_id = 'Nhập chuyên mục';
if ($sel_category_id > 0) {
	$txt_category_id = str_replace('&nbsp;', '', (get_name_category($sel_category_id, $v_arr_category_by_select)));
}
?>
<form action="" method="GET" name="frm_dsp_filter">
    <div class="widget">
        <div class="widget_title"><h5>Quản lý nội dung magazine</h5>
        	<?php $v_class_name = html_link($this->className(),false); ?>
			<div class="div-btn-search" style="float:right">
                <a class="button_big btn_grey" href="javascript:document.frm_dsp_filter.submit();">Tìm</a>
				<a class="button_big btn_grey" href="javascript:location.href='<?php html_link($this->className().'/index?reset_filter=1'); ?>'">Hủy tìm kiếm</a>
			</div>
		</div>
        <div class="widget_body">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="160"><col width="130">
            	</colgroup>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<input type="text" style="width:140px;" name="txt_category_id" id="txt_category_id" value="Nhập chuyên mục" title="Nhập chuyên mục" class="auto-title" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(false);}"/>
									<!-- bien hidden luu gia tri duoc chon trong hop thoai suggestion-->
									<input type="hidden" name="hdn_category_id" id="hdn_category_id" value=""/>		
								</td>
							</tr>
							<tr>
								<td>
									<select size="4" onchange="javascript:form_filter_before_submit('frm_dsp_filter','txt_title');" style="width:152px;" id="sel_category_id" name="sel_category_id">
										<option value='0' title="tất cả tat ca" <?php echo $sel_category_id==0? 'selected':''; ?> >Tất cả</option>
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
					<td>
	                    <table cellpadding="0" cellspacing="0" border="0">
	                        <tr>
	                            <td>
	                                <input style="width:108px;" type="text" name="txt_user_id" id="txt_user_id" class="auto-title ui-autocomplete-input" title="Người sửa cuối" value="<?php echo $v_user_name ?>" onkeydown="chon_nhanh(document.frm_dsp_filter.txt_user_id, document.frm_dsp_filter.sel_user_id,event)" autocomplete="off">
	                                <!-- bien hidden luu gia tri duoc chon trong hop thoai suggestion-->
									<input type="hidden" name="hdn_user_id" id="hdn_user_id" value=""/>		
	                            </td>
	                        </tr>
	                        <tr>
	                            <td>
	                                <select size="4" onchange="document.frm_dsp_filter.submit()" style="width:120px" id="sel_user_id" name="sel_user_id">
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
						    	<td class="tbLabel" width ="130px">
						    		<p style="margin: 5px 0;font-size: 12px;">Tên nội dung magazine</p></td>
						        <td>
						            <input type="text" name="txt_magazine_name" id="txt_magazine_name" value="<?php echo $txt_magazine_name; ?>" style="width:120px" title="" class="auto-title auto-submit" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(true);}"/>
						        </td>
						    </tr>
						    <tr>
					    		<td class="tbLabel" width ="130px"><p style="margin: 5px 0;font-size: 12px;">ID bài viết</p></td>
					    	    <td>
					    	        <input type="text" name="txt_news_id" id="txt_news_id" value="<?php echo $txt_news_id == 0 ? '' : $txt_news_id; ?>" style="width:120px;" title="" class="auto-title auto-submit" onKeyPress="if(event.keyCode == 13 || event.keyCode == '13'){return(true);}"/>
					    	    </td>
						    </tr>
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
<?php $v_arr_chuyen_muc = add_ascii_column($v_arr_category_by_select, 'Name');// them cot ten khong dau
	echo _tao_file_js_suggestion($v_arr_chuyen_muc,'ds_chuyen_muc','ID','Name_ascii','Name'); // tao mang 
	$v_arr_user = add_ascii_column($v_arr_user, 'Username');// them cot ten khong dau
	echo _tao_file_js_suggestion($v_arr_user,'ds_user','ID','Username_ascii','Username'); // tao mang 
?>
	// Tao suggestion cho o tim kiem chuyen muc
	setAutoComplete('txt_category_id',ds_chuyen_muc,'hdn_category_id', 1, 'set_selected_index_to_selectbox("sel_category_id","hdn_category_id","txt_category_id",1,"frm_dsp_filter")');
	setAutoComplete('txt_user_id',ds_user,'hdn_user_id', 1, 'set_selected_index_to_selectbox("sel_user_id","hdn_user_id","txt_user_id",1,"frm_dsp_filter")');
</script>
