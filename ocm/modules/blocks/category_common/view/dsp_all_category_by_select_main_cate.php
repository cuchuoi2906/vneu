<link type="text/css" href="<?php html_css('css/ui-lightness/jquery-ui-1.8.22.custom.css')?>" rel="stylesheet" />
<script type="text/javascript" src="<?php html_js('js/jquery-ui-1.8.22.custom.min.js')?>"></script>
<form action="<?php echo $v_action; ?>" method="post" name="frm_dsp_all_item" style="padding-top:10px" target="frm_submit">
    <input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>" />
    <input type="hidden" id="hdn_news_id" name="hdn_news_id" value="<?php echo $_GET['news_id']; ?>" />
	<a class="button_big btn_grey" href="javascript:;" onclick="return frm_submit_select_category(document.frm_dsp_all_item, 'sel_category_list', true);">Chọn</a>
    <a class="button_big btn_grey" href="javascript:;" onclick="window.close()">Đóng</a>
	<span>
		<label>
			<input type="radio" name="rad_option"  id = "rad_option1" <?php echo ($v_option!=1)? 'checked':'';?> onclick="window.location.href='<?php html_link($this->className().'/dsp_all_category_by_select/?'.htmlentities($v_url_reload_default)); ?>';" />
			Tất cả
		</label>
		<label>
			<input type="radio" name="rad_option"  id = "rad_option2" <?php echo ($v_option!=1)? '':'checked';?> onclick="window.location.href='<?php html_link($this->className().'/dsp_all_category_by_select/?'.htmlentities($v_url_reload)); ?>';" />
			Không hiển thị mục ẩn/có link 
		</label>
	</span>
    <div class="line-dot"></div>
    <div style="margin-bottom:7px">
        <input type="text" id="txt_category_search" value="Tìm nhanh tên chuyên mục" title="Tìm nhanh tên chuyên mục" class="auto-title" style="width:98%;background-color:#ffffcc" onkeypress="return disableEnterKey(event)" />
        <input type="hidden" id="hdn_category_id" />
    </div>
    <div style="width:100%;height:325px;margin-bottom:10px;overflow-y:auto">
        <table width="100%" cellpadding="1" cellspacing="0" border="1" bordercolor="#bbbbbb" style="border-collapse:collapse">
            <tr>
                <td class="popup-chuyenmuc" width="20" align="center"><input type="checkbox" name="chk_item_all" value="" onclick ="check_all_category_by_select(document.frm_dsp_all_item, this);"></td>
                <td class="popup-chuyenmuc">CM XB gốc</td>
                <td class="popup-chuyenmuc">Tên chuyên mục</td>
            </tr>
            <?php
            for ($i=0; $i<$v_record_count; $i++) {
                $v_category = $v_arr_items[$i];
                $v_class_name1 = ($v_category['Parent']==0) ? 'rowTitle' : 'rowContent-w';
                $v_class_name2 = ($v_category['Parent']==0) ? 'popup-menuCap1' : 'popup-menuCap2';
				//Thangnb xu ly box_tin_tong_hop_trang_chu
				$v_isCheck = '';
				if (check_array($v_arr_category)) {
                	$v_isCheck = (in_array($v_category['ID'], $v_arr_category)) ? 'checked' : '';
				}
                $v_html_radio_cate_main = '';
                if($v_isCheck == 'checked'){
                    $v_checked = '';
                    if($v_arr_url_news['fk_main_category'] == $v_category['ID'] || $v_category['ID'] == $v_main_cate_id){
                        $v_checked = 'checked';
                    }
                    $v_html_radio_cate_main = '<input '.$v_checked.' type="radio" name="chk_item_main_cate_id" id="chk_item_main_cate_id'.$v_category['ID'].'" value="'.$v_category['ID'].'">';
                }
				//End Thangnb xu ly box_tin_tong_hop_trang_chu
                ?>
                <tr>
                    <td class="<?php echo $v_class_name1; ?>" align="center">
                        <input onchange="create_html_main_cate_by_sub_cate(this.value)" type="checkbox" name="chk_item_id_<?php echo $i; ?>" id="item_id_<?php echo $v_category['ID']; ?>" value="<?php echo $v_category['ID']; ?>" <?php echo $v_isCheck; ?> />
                        <input type="hidden" name="hdn_category_name_<?php echo $i; ?>" value="<?php echo  htmlspecialchars(str_replace('&nbsp;&nbsp;&nbsp;&nbsp;', '-- ',$v_category['Name'])); ?>" />
                    </td>
                    <td align="center">
                        <div id="div_main_cate_id<?php echo $v_category['ID']; ?>"><?php echo $v_html_radio_cate_main; ?></div>
                    </td>
                    <td class="<?php echo $v_class_name2; ?>">
                        <label for="item_id_<?php echo $v_category['ID']; ?>"><?php echo str_replace('&nbsp;&nbsp;&nbsp;&nbsp;', '', $v_category['Name']); ?></label>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
    html_no_data($v_record_count);        ?>
	<a class="button_big btn_grey" href="javascript:;" onclick="return frm_submit_select_category(document.frm_dsp_all_item, 'sel_category_list',true);">Chọn</a>
    <a class="button_big btn_grey" href="javascript:;" onclick="window.close()">Đóng</a>
	<iframe name="frm_submit" class="iframe-form"></iframe>
</form>
<script type="text/javascript">
var v_select_main_cate = 1;
json_category = <?php echo $v_json_category; ?>;
ext_script = 'v_search_id = document.getElementById("txt_category_search");v_search_id.value="";v_search_id.focus();';
setAutoComplete('txt_category_search', json_category, 'hdn_category_id', 1, 'check_checkbox_by_input("item_id_", "hdn_category_id");'+ext_script);
</script>
<br />
<span class="redText">*Bắt buốc chọn chuyên mục gốc</span>