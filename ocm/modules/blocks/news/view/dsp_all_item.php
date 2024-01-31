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
                <td class="rowTitle">ID bài viết</td>						
                <td class="rowTitle">tiêu đề bài viết</td>	
                <td class="rowTitle">Trạng thái xuất bản</td>	
            </tr>
            <?php	
            // Hien thi danh sach
            $v_current_style_name = "rowContent-w";
            for ($i=0; $i<$v_record_count; $i++) {
                $v_id = $v_arr_items[$i]['ID'];
                $v_name = $v_arr_items[$i]['Title'];
                $v_status = $v_arr_items[$i]['Status'];			
                $v_type = $v_arr_items[$i]['Type'];			
                $url_maz = '';
                if($v_type ==2){
                    $url_maz = '&type=magazine';
                }
                ?>
                <tr class="<?php echo $v_current_style_name ?>">
                    <td align ="center">
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" value="<?php echo $v_id; ?>">
                    </td>
                    <td align ="center"> 
                        <a href="<?php html_link('news/dsp_single_item/'.$v_id.'?goback='.$goback.$url_maz); ?>"><?php echo $v_id; ?></a>
                    </td>   
                    <td align ="center">
                        <?php echo $v_name; ?><br />
                        <a href="javascript:frm_submit(document.frm_dsp_all_item, '<?php html_link('news/dsp_news_url/'.$v_id)?>','frm_submit')" class="history">Lấy link</a>
                    </td>
                    <td><?php echo html_select_box('sel_publish'.$i, $v_arr_trang_thai, 'c_code', 'c_name', $v_status, $extend=' onchange ="document.frm_dsp_all_item.chk_item_id'.$i.'.checked=true;"', $add_option = 0); ?></td>
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
