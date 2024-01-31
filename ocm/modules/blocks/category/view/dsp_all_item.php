<div id="div_dsp_all_item">
<?php
$v_filter_box_on_top = $this->dsp_filter_box_top();
if (!$this->autoRender) {
	echo $v_filter_box_on_top;
}?>
    <form action="" method="POST" name="frm_dsp_all_item" target="frm_submit">
        <input type="hidden" name="goback" value="<?php echo $goback; ?>">
		<input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
		<?php
		if ($this->_GET['chk_menu_mobile'] == 1) { ?>
			<input type="hidden" name="hdn_type_menu" value="menu_mobile"><?php
		} elseif ($this->_GET['chk_dropdown_menu_mobile'] == 1) {?>
			<input type="hidden" name="hdn_type_menu" value="dropdown_mobile"><?php
		}
		?>
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
             <tr>
                <td class="rowTitle"><input type="checkbox" name="chk_item_all" value="" onclick ="check_all(document.frm_dsp_all_item, this);"></td>				
                <td class="rowTitle">Trọng số CM cấp 1</td>						
                <td class="rowTitle">Trọng số CM cấp 2</td>						
                <td class="rowTitle">ID</td>						
                <td class="rowTitle">Tên chuyên mục</td>		
                <td class="rowTitle">Liên kết</td>
                <?php //Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
                <td class="rowTitle">Hiển thị trên menu dọc PC,Ipad</td>
                <?php //End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
                <td class="rowTitle">Cập nhật cuối</td>			
                <td class="rowTitle">Trạng thái xuất bản</td>			
                <td class="rowTitle">Lịch sử phiên bản</td>			
            </tr>
            <?php	
            // Hien thi danh sach
            $v_current_style_name = "rowContent-w";
            for ($i=0; $i<$v_record_count; $i++) {
                $v_id = $v_arr_items[$i]['ID'];
                $v_name = $v_arr_items[$i]['Name'];
                $v_position = $v_arr_items[$i]['Position'];
                $v_parent_position = $v_arr_items[$i]['Position'];
                $v_mb_id = $v_arr_items[$i]['mb_ID'];
				$v_create_date = date_format(date_create($v_arr_items[$i]['date_edited']), 'd/m/Y H:i:s');
                $v_last_edit = get_name_of_editor($v_arr_items[$i]['last_editor_id']);			
                $v_link = $v_arr_items[$i]['Link'];   
                $v_parent_pos_style = ($v_arr_items[$i]['Parent']==0)? ' style="width:30px;"': ' style = "visibility:hidden;width:30px;"';
                $v_sub_pos_style = ($v_arr_items[$i]['Parent']==0)? ' style = "visibility:hidden;width:30px;"': ' style="width:30px;"'; 
                if ($this->_GET['chk_menu_mobile'] == 1 || $this->_GET['chk_dropdown_menu_mobile'] == 1) {
					$v_parent_pos_style = ' style = "visibility:hidden;width:30px;"';
					$v_sub_pos_style = ' style = "visibility:hidden;width:30px;"'; 
				}
                $v_name = ($v_arr_items[$i]['Parent']==0)? '<b>'.$v_name.'</b>': '-- '.$v_name.'';
                $goback = $this->_getCurrentUri();
                $v_current_style_name = ($v_current_style_name == 'rowContent-w') ? 'rowContent-g' : 'rowContent-w';
				$v_mb_position = "-100";
				if ($this->_GET['chk_menu_mobile'] == 1) {
					$v_mb_position = $v_arr_items[$i]['mb_position'];
				}
				if ($this->_GET['chk_dropdown_menu_mobile'] == 1) {
					$v_mb_position = $v_arr_items[$i]['mb_dropdown_position'];
				}
				if ($this->_GET['chk_menu_mobile'] == 1 && $this->_GET['chk_dropdown_menu_mobile'] == 1) {
					$v_mb_position = "-100";
				}
				//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
				$v_is_show_on_pc = $v_arr_items[$i]['c_is_show_on_pc'];
				//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
				
                ?>
                <tr class="<?php echo $v_current_style_name ?>">
                    <td align ="center">
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_id; ?>">
                        <input type="hidden" name="hdn_mobile_id<?php echo $i; ?>" value="<?php echo $v_mb_id; ?>">
                    </td>
                    <td align ="center"><?php 
						if ($v_mb_position >= 0) {
							?>
							<input type="text" name="txt_order_mobile<?php echo $i; ?>" value="<?php echo $v_mb_position; ?>" style="width:30px;" readonly7="readonly" onchange="document.frm_dsp_all_item.chk_item_id<?php echo $i; ?>.checked=true;"/><?php 
						}   ?>
						<input type="textbox" name="txt_order1_<?php echo $i; ?>" value="<?php echo $v_parent_position; ?>" <?php echo $v_parent_pos_style?> onchange ="document.frm_dsp_all_item.chk_item_id<?php echo $i; ?>.checked=true;"/>
                        <input type ="hidden" name ="hdn_parent<?php echo $i; ?>" value ="<?php echo $v_arr_items[$i]['Parent']?>"/>
                    </td>   
                    <td align ="center">
                        <input type="textbox" name="txt_order2_<?php echo $i; ?>" value="<?php echo $v_position; ?>" <?php echo $v_sub_pos_style?> onchange ="document.frm_dsp_all_item.chk_item_id<?php echo $i; ?>.checked=true;"/>
                    </td>
                    <td align ="center">
                        <?php echo $v_id; ?>
                    </td>
                    <td>
                        <a href="<?php html_link('category/dsp_single_category/'.$v_id.'?goback='.$goback); ?>"><?php echo $v_name; ?></a>
                    </td>
                    <td class="redText">
                        <a href ="<?php echo  $v_link; ?>" target ="_blank"><?php echo  $v_link; ?></a>
                    </td>
                    <?php //Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
                    <td align ="center">
                        <input type="checkbox" name="chk_is_show_on_pc_<?php echo $i; ?>" value="1" <?php echo ($v_is_show_on_pc==1) ? "checked" : ""; ?> onchange="document.frm_dsp_all_item.chk_item_id<?php echo $i; ?>.checked=true;" />
                    </td>
                    <?php //End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
                    <td align ="center">
                        <b><?php echo $v_last_edit; ?></b><br />
                        Thời gian cập nhật : <?php echo $v_create_date; ?> 
                    </td>
                    <td><?php echo html_select_box('sel_publish'.$i, $v_arr_trang_thai, 'c_code', 'c_name', $v_arr_items[$i]['Activate'], $extend=' onchange ="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"', $add_option = 0); ?></td>
                   	<td align="center"><a href="<?php html_link('category_common/dsp_history_change/'.$v_id.'?goback='.$goback); ?>">Xem lịch sử sửa đổi</a></td>
                </tr>
		<?php
		} // for
		?>
		</table>
		<?php
		html_no_data($v_record_count);
		?>		
	</form>
	<iframe name="frm_submit" class="iframe-form"></iframe>
	<?php
    $v_html = $this->dsp_form_button();
	if (!$this->autoRender) {
		echo $v_html;
	}?>
</div> 
<!-- goi ham xu ly an nut lenh khi ghi du lieu-->	
<script type="text/javascript">set_disable_link("button_update","tr_button");</script>
