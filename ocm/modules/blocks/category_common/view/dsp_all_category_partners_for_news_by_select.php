<link type="text/css" href="<?php html_css('css/ui-lightness/jquery-ui-1.8.22.custom.css')?>" rel="stylesheet" />
<script type="text/javascript" src="<?php html_js('js/jquery-ui-1.8.22.custom.min.js')?>"></script>
<form action="" method="GET" name="frm_dsp_filter">
    <input type="hidden" id="hdn_news_id" name="news_id" value="<?php echo $_GET['news_id']; ?>" />
    <table>
        <tr>
            <td class="tbLabel" style="font-size: 14px;font-weight: bold;">Chọn web phụ:</td>
            <td>
                <?php echo html_select_box('sel_code_partners', $v_arr_cau_hinh_web_phu, 'c_ma_gia_tri', 'c_ten', $v_code, $extend=' onchange="javascript:form_filter_before_submit(\'frm_dsp_filter\',\'txt_title\');" ', $add_option=1); ?>
            </td>
        </tr>
    </table>
</form>
<form action="<?php html_link('ajax/'.$this->className().'/act_update_category_partners_by_news'); ?>" method="post" name="frm_dsp_all_item" style="padding-top:10px" target="frm_submit">
    <input type="hidden" id="hdn_record_count" name="hdn_record_count" value="<?php echo $v_record_count; ?>" />
    <input type="hidden" id="hdn_news_id" name="hdn_news_id" value="<?php echo $_GET['news_id']; ?>" />
    <input type="hidden" id="hdn_v_his" name="hdn_v_his" value="<?php echo $_GET['v_his']; ?>" />
    <input type="hidden" id="hdn_c_code" name="hdn_c_code" value="<?php echo $v_code; ?>" />
    <input type="hidden" id="hdn_c_code_name" name="hdn_c_code_name" value="" />
    <input type="hidden" id="hdn_c_name_partners" name="hdn_c_name_partners" value="<?php echo $v_ten_doi_tac; ?>" />
    <?php /* Begin 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
    // Không hiện thị nút chọn Nếu bài viết đẩy baogiaothong mà đã được duyệt, bài pr 
    $v_check_select = check_button_select_category_partners($v_arr_single_news, $v_code, $v_arr_partners);
    if(intval($v_arr_partners['c_approve_status']) == 0 && !$v_check_select){
    //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
	?>
        <a class="button_big btn_grey" href="javascript:;" onclick="frm_submit_select_category(document.frm_dsp_all_item, 'sel_category_khampha_list');send_main_cate_id_for_source();">Chọn</a>
    <?php 
	//Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
    }
    /* end 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */ ?>
    <a class="button_big btn_grey" href="javascript:;" onclick="window.close()" />Đóng</a>
    <div class="line-dot"></div>
    <div style="margin-bottom:7px">
        <input type="text" id="txt_category_search" value="Tìm nhanh tên chuyên mục" title="Tìm nhanh tên chuyên mục" class="auto-title" style="width:98%;background-color:#ffffcc" onkeypress="return disableEnterKey(event)" />
        <input type="hidden" id="hdn_category_id" />
    </div>
    <div style="width:100%;height:325px;margin-bottom:10px;overflow-y:auto">
        <table width="100%" cellpadding="1" cellspacing="1" border="1" bordercolor="#bbb" style="border-collapse:collapse">
            <tr>
                <td class="popup-chuyenmuc" width="20"></td>
                <td class="popup-chuyenmuc">Tên chuyên mục</td>
            </tr>
            <?php
            for ($i=0; $i<$v_record_count; $i++) {
                $v_category = $v_arr_items[$i];
                $v_class_name1 = ($v_category['Parent']==0) ? 'rowTitle' : 'rowContent-w';
                $v_class_name2 = ($v_category['Parent']==0) ? 'popup-menuCap1' : 'popup-menuCap2';
                $v_isCheck = (in_array($v_category['ID'], $v_arr_category)) ? 'checked' : '';
                ?>
                <tr>
                    <td class="<?php echo $v_class_name1; ?>" align="center">
                        <input type="checkbox" name="chk_item_id_<?php echo $i; ?>" id="item_id_<?php echo $v_category['ID']; ?>" value="<?php echo $v_category['ID']; ?>" <?php echo $v_isCheck; ?> />
                        <input type="hidden" name="hdn_category_name_<?php echo $i; ?>" value="<?php echo  htmlspecialchars(str_replace('&nbsp;&nbsp;&nbsp;&nbsp;', '-- ',$v_category['Name'])); ?>" />
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
    html_no_data($v_record_count);   ?>
    <?php /* Begin 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
    // Không hiện thị nút chọn Nếu bài viết đẩy baogiaothong mà đã được duyệt, bài pr
    if(intval($v_arr_partners['c_approve_status']) == 0 && !$v_check_select){
    //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
    ?>
	<a class="button_big btn_grey" href="javascript:;" onclick="frm_submit_select_category(document.frm_dsp_all_item, 'sel_category_khampha_list');send_main_cate_id_for_source();">Chọn</a>
    <?php
    //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
    }
    /* End 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
    ?>
    <a class="button_big btn_grey" href="javascript:;" onclick="window.close()" />Đóng</a>
	<iframe name="frm_submit" class="iframe-form"></iframe>
</form>
<script type="text/javascript">
    //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
    var json_source = <?php echo $v_json_source; ?>;
    //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
json_category = <?php echo $v_json_category; ?>;
ext_script = 'v_search_id = document.getElementById("txt_category_search");v_search_id.value="";v_search_id.focus();';
setAutoComplete('txt_category_search', json_category, 'hdn_category_id', 1, 'check_checkbox_by_input("item_id_", "hdn_category_id");'+ext_script);
</script>
