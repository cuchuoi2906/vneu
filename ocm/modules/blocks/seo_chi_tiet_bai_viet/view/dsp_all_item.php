<div id="div_dsp_all_item">
<?php
$v_filter_box_on_top = $this->dsp_filter_box_top();
if (!$this->autoRender) {
	echo $v_filter_box_on_top;
}?>
    <form action="" method="POST" name="frm_dsp_all_item" target="frm_submit">
        <input type="hidden" name="goback" value="<?php echo $goback; ?>">
		<input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">            
            <tr>
                <td class="rowTitle"><input type="checkbox" name="chk_item_all" value="" onclick ="check_all(document.frm_dsp_all_item, this);"></td>				
				<td class="rowTitle">ID bài viết</td>                
                <td class="rowTitle">Tên bài viết</td>						
                <td class="rowTitle">Nội dung</td>		
                <td class="rowTitle">Thiết bị hiển thị</td>		
                <td class="rowTitle">Cập nhật</td>		
				<td class="rowTitle">Trạng thái xuất bản</td>						
                <td class="rowTitle">Lịch sử phiên bản</td>			
            </tr>
            <?php	
			
            // Hien thi danh sach
            $v_current_style_name = "rowContent-w";			
			//$goback = $this->_getCurrentUri();
            for ($i=0; $i<$v_record_count; $i++) {
                $v_id = $v_arr_items[$i]['pk_seo_chi_tiet_bai_viet'];
              	$v_news_id = $v_arr_items[$i]['pk_news'];
				$v_ten_bai_viet = $v_arr_items[$i]['c_ten_bai_viet'];
				$v_ngay_xuat_ban = $v_arr_items[$i]['c_ngay_xuat_ban'];
				$v_bien_tap_vien = $v_arr_items[$i]['c_bien_tap_vien'];
				$v_category_name = $v_arr_items[$i]['c_ten_chuyen_muc'];				
				$v_title = $v_arr_items[$i]['c_title'];                
				$v_desc = $v_arr_items[$i]['c_desc'];                
				$v_keyword = $v_arr_items[$i]['c_keyword'];  
			    $v_slug  = $v_arr_items[$i]['c_slug'];  
                $v_create_date = date_format(date_create($v_arr_items[$i]['c_thoi_gian_sua']), 'd/m/Y H:i:s');
                $v_current_style_name = ($v_current_style_name == 'rowContent-w') ? 'rowContent-g' : 'rowContent-w';
                ?>
                <tr class="<?php echo $v_current_style_name ?>">
                    <td align ="center">
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_id; ?>">
                    </td>
					<td align ="center">
                        <?php echo $v_news_id;?>
                    </td>
                    <td class="title">
						<a href="<?php html_link('seo_chi_tiet_bai_viet/dsp_single_seo_chi_tiet/'.$v_id.'/'.$v_news_id.'?goback='.$goback); ?>"><b><?php echo $v_ten_bai_viet ;?></b></a>
						<br/><?php echo 'BTV:'.$v_bien_tap_vien.', Ngày xuất bản:'.$v_ngay_xuat_ban.'<br/>Chuyên mục:'.$v_category_name;?>
					</td>   
                    <td>
						<b>Title:</b><?php echo $v_title;?> <br/>		
						<b>Des:</b><?php  echo $v_desc;?>	<br/>	
						<b>Key:</b><?php  echo $v_keyword;?>	<br/>	
						<b>Slug:</b><?php echo $v_slug;?>	<br/>							
                    </td>             
                    <td>
                       <?php echo $v_arr_items[$i]['c_ten_thiet_bi']; ?>  
                    </td>                   
					<td>
                        <b><?php echo $v_arr_items[$i]['c_nguoi_sua']; ?></b><br />
                        Thời gian cập nhật : <?php echo $v_create_date; ?>  
                    </td>                   
                    <td><?php 
						$v_disabled = $this->getPerm('admin,publish')? '' : 'disabled';
						echo html_select_box('sel_publish'.$i, $v_arr_trang_thai, 'c_code', 'c_name', $v_arr_items[$i]['c_trang_thai_xuat_ban'], $extend = $v_disabled.' onchange ="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"', $add_option = 0); 
						?>
					</td>
                   	<td align="center"><a href="<?php html_link('seo_common/dsp_all_seo_chi_tiet_bai_viet_change/'.$v_id.'?title='.htmlspecialchars($v_ten_bai_viet).'&goback='.$goback); ?>">Xem lịch sử sửa đổi</a></td>
                </tr>
		<?php
		} // for
		?>
		</table>
		<?php
		html_no_data($v_record_count);
		?>		
	</form>
	<iframe name="frm_submit" class="iframe-form"></iframe><?php
    $v_html = $this->dsp_form_button();
	if (!$this->autoRender) {
		echo $v_html;
	}?>
	<div>   
	<table width = "100%">
		<col width ="50%"/><col width ="50%"/>
		<tr>		
			<td valign="top">
				<span class="redText">Ghi chú: Thứ tự ưu tiên đối với trang Bài viết</span><br/>
				•	Ưu tiên 1: Title,des,key,slug,alt ảnh cập nhật riêng cho bài viết<br/>
				•	Ưu tiên 2: Title,des,key,slug,alt ảnh cập nhật cho bài viêt theo teamplate<br/>
				•	Ưu tiên 3: Title,des,key,slug,alt ảnh mặc định ban đầu của bài viết<br/>	
				<span class="redText">Chú ý trường Canonical: nhập cho Bài viết nào thì chỉ trang Bài viết đấy hiển thị	</span>		
			</td>
			<td valign="top">			
				<span class="redText">Ghi chú: Thứ tự ưu tiên đối với phần đóng dấu sapo</span><br/>
				•	Ưu tiên 1: Đóng dấu sapo cho bài viết chi tiết<br/>
				•	Ưu tiên 2: Đóng dấu sapo cho sự kiện chứa bài viết<br/>
				•	Ưu tiên 3: Đóng dấu sapo cho chuyên mục chứa bài viết<br/>
			</td>
		</tr>
	</table>
	</div>
</div> 
<?php /* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>
<form name="frm_dsp_pageview_export" action="<?php html_link($this->className().'/export_excel_pageview_tin_bai?reset_filter=1'); ?>" target="fr_submit_export" method="get">
    <input type="hidden" name="goback" value="<?php echo $goback; ?>">
    <input type="hidden" name="sel_category_id" value="<?php echo intval($v_category_id); ?>">
    <input type="hidden" name="sel_user_id" value="<?php echo intval($v_selected_user); ?>">
    <input type="hidden" name="sel_status" value="<?php echo intval($v_selected_status); ?>">
    <input type="hidden" name="txt_news_id" value="<?php echo intval($v_id_bai_viet); ?>">
    <input type="hidden" name="txt_news_name" id="txt_news_name" value="<?php echo $v_ten_bai_viet_seo;?>" />
    <input type="hidden" name="sel_sorttype" value="<?php echo intval($v_sort_type); ?>">
    <input type="hidden" name="sel_thiet_bi_filter" value="<?php echo intval($v_thiet_bi_id); ?>">
    <input type="hidden" name="txt_edit_date_start" value="<?php echo fw24h_replace_bad_char($v_edit_date_start); ?>">
    <input type="hidden" name="txt_edit_date_end" value="<?php echo fw24h_replace_bad_char($v_edit_date_end); ?>">
</form>
<iframe name="fr_submit_export" class="iframe-form"></iframe>
<?php /* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */ ?>