<div class="greyBox-light">
    <form name="frm_dsp_filter" action="" method="get">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
			<col width="300px"/><col width="310px"/><col width=""/>
            <tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">						
						<tr>
							<td class="tbLabel">Tên BTV/CTV</td>
							<td>
								<input type="text" name="txt_title" value="<?php echo $txt_title; ?>" style="width:200px"/>
							</td>
						</tr>						
					</table>
				</td>									
				<td align="right">                   
                    <input type="submit" value="Tìm kiếm" />
                    <input type="button" value="Huỷ tìm kiếm" onclick="javascript:location.href='<?php html_link($this->className().'/dsp_all_user_by_select?reset_filter=1'); ?>';" />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php
if ($v_record_count>0) {
    ?>
    <link type="text/css" href="<?php html_css('css/ui-lightness/jquery-ui-1.8.22.custom.css'); ?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php html_js('js/jquery-1.7.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php html_js('js/jquery-ui-1.8.22.custom.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php html_js('js/jquery-ui.datepicker-vi.js'); ?>"></script>
    <form action="" method="post" name="frm_dsp_all_item">
        <input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>">
        <div style="padding:10px 0">
			<input type="button" value="Chọn" class="button" onclick="return frm_submit_select_user(document.frm_dsp_all_item, 'sel_bien_tap_bai_viet', true);" />
        </div>
        <div>
            <div style="float:right;margin:5px"><?php echo _page_nav($v_paging, $this->_getCurrentUri(false)); ?></div>
        </div>
        <div class="clear"></div>
        <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
            <tr class="rowTitle">
                <td width="20"><input type="checkbox" name="chk_item_all" onclick="check_all(document.frm_dsp_all_item, this);" /></td>
                <td>Họ và tên</td>
                <td>Địa chỉ email</td>
                <td>Bút danh</td>
                <td>BTV/CTV</td>
            </tr>    
            <?php
            for ($i=0; $i<$v_record_count; $i++) {
				$v_user_id = $v_arr_user[$i]['ID'];
				$v_full_name = $v_arr_user[$i]['Fullname'];      
				$v_user_name = $v_arr_user[$i]['Username'];      
				$v_full_name = ($v_full_name =='')? $v_user_name:$v_full_name;
                $v_class_name = ($i%2==0) ? 'rowContent-w' : 'rowContent-g';
                ?>
                <tr class="<?php echo $v_class_name; ?> news_<?php echo $v_user_id; ?>">
                    <td align="center">
                        <input type="checkbox" name="chk_item_id<?php echo $i; ?>" id="chk_item_id_<?php echo $i; ?>" value="<?php echo $v_user_id; ?>" />
                        <input type="hidden" name="hdn_user_name_<?php echo $i; ?>" value="<?php echo htmlspecialchars($v_full_name); ?>" />                        
                    </td>
                    <td><label for="chk_item_id_<?php echo $i; ?>"><?php echo $v_full_name;?></label></td>
					<td><label for="chk_item_id_<?php echo $i; ?>"><?php echo $v_email=''; ?></label></td>
					<td><label for="chk_item_id_<?php echo $i; ?>"><?php echo $v_user_name;?></label></td>                   
                    <td>&nbsp;</td>
                </tr>
                <?php
            }
        ?>
        </table>
        <div>           
            <div style="float:right;margin:5px"><?php echo _page_nav($v_paging, $this->_getCurrentUri(false)); ?></div>
        </div>
        <div class="clear"></div>
        <div style="padding:10px 0">
            <input type="button" value="Chọn" class="button" onclick="return frm_submit_select_user(document.frm_dsp_all_item, 'sel_bien_tap_bai_viet', true);" />
        </div>
    </form>
    <?php
}
html_no_data($v_record_count);
?>