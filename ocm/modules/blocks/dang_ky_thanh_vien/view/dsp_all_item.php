<div id="div_dsp_all_item">
    <form action="" method="POST" name="frm_dsp_all_item" target="frm_submit">
        <input type="hidden" name="goback" value="<?php echo $goback; ?>">
		<input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
             <tr>			
                <td class="rowTitle">Tên</td>						
                <td class="rowTitle">Số điện thoại</td>						
                <td class="rowTitle">Địa chỉ</td>						
                <td class="rowTitle">Nội dung</td>	
            </tr>
            <?php	
            // Hien thi danh sach
            $v_current_style_name = "rowContent-w";
            for ($i=0; $i<$v_record_count; $i++) {
                $v_id = $v_arr_items[$i]['re_id'];
                $re_name = $v_arr_items[$i]['re_name'];
                $re_phone = $v_arr_items[$i]['re_phone'];
                $re_address = $v_arr_items[$i]['re_address'];
                $re_comment = $v_arr_items[$i]['re_comment'];
                ?>
                <tr class="rowContent-g">
                    <td align ="center">
                        <?php echo $re_name; ?>
                    </td>
					<td align ="center">
                        <?php echo $re_phone; ?>
                    </td>
					<td align ="center">
                        <?php echo $re_address; ?>
                    </td>
					<td align ="center">
                        <?php echo $re_comment; ?>
                    </td>
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
</div> 
<!-- goi ham xu ly an nut lenh khi ghi du lieu-->	
<script type="text/javascript">set_disable_link("button_update","tr_button");</script>