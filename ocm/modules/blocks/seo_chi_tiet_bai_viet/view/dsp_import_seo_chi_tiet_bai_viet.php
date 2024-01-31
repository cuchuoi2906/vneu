<div id = "div_dsp_all_item">
<div class="contentTitle">Import Title-des-keyw bài viết</div>
<div class="line-dot"></div>
<form name="frm_update_data" method="post" enctype="multipart/form-data"  action="<?php html_link($this->className().'/dsp_import_seo_chi_tiet_bai_viet'); ?>">
    <input type="hidden" name="goback" value="<?php echo $v_goback; ?>" />
    <table border="0" width="100%" cellpadding="5" cellspacing="0">
		<col width ="15%"/><col width ="85%"/>  
		<?php 
		if ($v_message_error  != '') {?>
            <tr>
                <td></td>
                <td><span class = "redText"><?php echo $v_message_error?></span></td>
            </tr>
            <?php 
		}
		?>
		<tr>
            <td width="130" class="tbLabel">File dữ liệu<span class="redText">(*)</span></td>
            <td valign="middlle">
				<input type="file" id="file_import" name="file_import" style="width:300px"/>	
				<input type = "hidden" name = "hdn_file_import" value = "<?php echo $v_file_name?>"/>				
				<div style="float:right"><a href = "<?php html_link($v_file_excel_mau);?>">Tải file excel mẫu</a></div>
            </td>
		</tr>
		<tr>
            <td class="tbLabel"></td>
            <td><?php
                if ($this->getPerm('admin,edit')) {
					?>
					<a class="button_big btn_grey" href="javascript:document.frm_update_data.submit()">Import</a>&nbsp;                    
                    <?php 
				} ?>
				<a class="button_big btn_grey" href="<?php html_link($this->className().'/index'); ?>">Thoát</a>
            </td>
        </tr>		
		<tr>
			<td colspan =2>
				<div class="line-dot"></div>
			</td>
		</tr>
    </table>   
       <!-- DỮ liệu hợp lệ -->
       <div><b>Ghi chú: Tổng số lượng dòng trong file: <?php echo $v_record_count_khong_hop_le + $v_record_count; ?> dòng; Tổng số dòng hợp lệ: <?php echo $v_record_count; ?> dòng; Tổng số dòng không hợp lệ:  <?php echo $v_record_count_khong_hop_le; ?> dòng</b></div>    
<div class="greyBox">
    <b style="font-size: 18px;">Danh sách các title-des-keyw bài viết không hợp lệ</b>
</div> 
<div style="    margin-bottom: 10px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class ="tr_button">
            <td align="right">
                <b>Tổng số số title-des-keyw bài viết :</b> <?php echo $v_arr_items_khong_hop_le[0]['c_so_dong_khong_hop_le']; ?>
            </td>
        </tr>
    </table>   
</div>
<form action="" method="POST" name="frm_dsp_all_item" target="frm_submit">
	<input type="hidden" name="goback" value="<?php echo $goback; ?>">
    <input type="hidden" id="hdn_thiet_bi" name="hdn_thiet_bi"  value=""/>
    <input type="hidden" id="hdn_trang_thai" name="hdn_trang_thai"  value=""/>
	<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
		<tr>				
			<td class="rowTitle">ID bài viết</td>						         				
			<td class="rowTitle">Title</td>						         				
			<td class="rowTitle">Description</td>	
			<td class="rowTitle">Keyword</td>	
			<td class="rowTitle">Slug</td>   
			<td class="rowTitle">Thiết bị</td> 			
			<td class="rowTitle">Kiểm hợp dữ liệu</td>          
		</tr>
		<?php
		// Hien thi danh sach
		$v_current_style_name = "rowContent-w";		
		//$goback = $this->_getCurrentUri();
		for ($i=0; $i<$v_record_count_khong_hop_le; $i++) {	
            $v_current_style_name = ($v_current_style_name == 'rowContent-w') ? 'rowContent-g' : 'rowContent-w';
            $v_id_news = $v_arr_items_khong_hop_le[$i]['c_id_bai_viet'];
            $v_title = $v_arr_items_khong_hop_le[$i]['c_title'];
            $v_desc = $v_arr_items_khong_hop_le[$i]['c_desc'];
            $v_keyword = $v_arr_items_khong_hop_le[$i]['c_keyword'];
            $v_slug = $v_arr_items_khong_hop_le[$i]['c_slug'];
            $v_thiet_bi = get_name_in_array($v_arr_device,'c_code','c_name', $v_arr_items_khong_hop_le[$i]['c_thiet_bi']);  
            $v_error = $v_arr_items_khong_hop_le[$i]['c_noi_dung_loi_du_lieu'];
            
			$v_current_style_name = ($v_current_style_name == 'rowContent-w') ? 'rowContent-g' : 'rowContent-w';
			?>
			<tr class="<?php echo $v_current_style_name ?>">				
                <td><?php echo $v_id_news; ?></td>    
                <td><?php echo $v_title; ?></td>    
				<td><?php echo $v_desc; ?></td>    
				<td><?php echo $v_keyword; ?></td>    
				<td><?php echo $v_slug;?></td>
				<td><?php echo $v_thiet_bi;?></td>						
				<td><?php echo $v_error;?></td>						
			</tr>
        <?php
        } // for ?>
	</table>
	<?php
	html_no_data($v_record_count_khong_hop_le);
	?>		
</form>
    
    <!-- DỮ liệu hợp lệ -->
<div class="greyBox" style="margin-top: 20px;">
    <b style="font-size: 18px;">Danh sách các title-des-keyw bài viết hợp lệ</b>
</div>
<div style="    margin-bottom: 10px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class ="tr_button">
            <td>
                <?php
                $v_button_box_on_top = $this->dsp_form_button_import();
                if (!$this->autoRender) {
                    echo $v_button_box_on_top;
                }?>
            </td>
            <td align="right">
                <b>Tổng số số title-des-keyw bài viết :</b> <?php echo $v_arr_items[0]['c_so_dong_hop_le']; ?>
            </td>
        </tr>
    </table>   
</div>

<form action="" method="POST" name="frm_dsp_all_item" target="frm_submit">
	<input type="hidden" name="goback" value="<?php echo $goback; ?>">
	<input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
    <input type="hidden" id="hdn_thiet_bi" name="hdn_thiet_bi"  value=""/>
    <input type="hidden" id="hdn_trang_thai" name="hdn_trang_thai"  value=""/>
	<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
		<tr>			
            <td class="rowTitle">#</td>		
			<td class="rowTitle">ID bài viết</td>						         				
			<td class="rowTitle">Title</td>						         				
			<td class="rowTitle">Description</td>	
			<td class="rowTitle">Keyword</td>	
			<td class="rowTitle">Slug</td>   
			<td class="rowTitle">Thiết bị</td>    
		</tr>
		<?php
		// Hien thi danh sach
		$v_current_style_name = "rowContent-w";		
        $v_arr_device  = get_list_pr_device();
		//$goback = $this->_getCurrentUri();
		for ($i=0; $i<$v_record_count; $i++) {			
			$v_current_style_name = ($v_current_style_name == 'rowContent-w') ? 'rowContent-g' : 'rowContent-w';
            $v_id_news = $v_arr_items[$i]['c_id_bai_viet'];
            $v_id_tmp = $v_arr_items[$i]['pk_seo_chi_tiet'];
            $v_title = $v_arr_items[$i]['c_title'];
            $v_desc = $v_arr_items[$i]['c_desc'];
            $v_keyword = $v_arr_items[$i]['c_keyword'];
            $v_slug = $v_arr_items[$i]['c_slug'];
            $v_thiet_bi = get_name_in_array($v_arr_device,'c_code','c_name', $v_arr_items[$i]['c_thiet_bi']);  
			?>
			<tr class="<?php echo $v_current_style_name ?>">
				<td align ="center">				
					<input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_id_tmp;?>">				
				</td>					
                <td><?php echo $v_id_news; ?></td>    
                <td><?php echo $v_title; ?></td>    
				<td><?php echo $v_desc; ?></td>    
				<td><?php echo $v_keyword; ?></td>    
				<td><?php echo $v_slug;?></td>
				<td><?php echo $v_thiet_bi;?></td>											
			</tr>
	<?php
	} // for
	?>
	</table>
	<?php
	html_no_data($v_record_count);
	?>		
</form>
<script type="text/javascript">
    try{
        autosubmit_when_control_changed('frm_dsp_filter','frm_dsp_filter');			
    }
    catch(err){
    }
</script>
<form action="" method="GET" name="frm_dsp_filter">
    <div style="margin-top: 10px;">   
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr class ="tr_button">
                <td>
                    <?php
                    $v_button_box_on_top = $this->dsp_form_button_import();
                    if (!$this->autoRender) {
                        echo $v_button_box_on_top;
                    }?>
                </td>
                <td align="right">
                    Hiển thị <input class="textbox" type="text" style="width:30px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="document.frm_dsp_filter.submit();"> bản ghi/trang &nbsp;&nbsp;&nbsp;
                    <?php
                    // phan trang co su dung ajax
                    echo _page_nav($phan_trang_hop_le, $this->_getCurrentUri(false), 'div_dsp_all_item');
                    ?>
                </td>
            </tr>
        </table>   
    </div>
    <iframe name="iframe_submit" class="iframe-form"></iframe>		
</form>

<iframe name="frm_submit" class="iframe-form"></iframe>		
<div style="color:red;">
    Chú ý:<br />
    • Các định dạng file được chấp nhận import: xls, xlsx, csv<br />
    • Dung lượng tổng các file cho phép: 20Mb<br />
    • Tổng số bản ghi tối đa cho phép import: 2000
</div>
</div>