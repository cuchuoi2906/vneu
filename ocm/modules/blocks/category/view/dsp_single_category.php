<div class="contentTitle">TẠO MỚI MỘT CHUYÊN MỤC</div>
<div class="line-dot"></div>
<form name="frm_update_category" method="post" enctype="multipart/form-data"  action="<?php html_link('ajax/'.$this->className().'/act_update_category/'.$v_id.'/'.$v_parent_id); ?>"  target ="frm_submit">
    <input type="hidden" name="goback" value="<?php echo $_GET['goback']; ?>" />
    <table border="0" width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="130" class="tbLabel">Tên chuyên mục  <span class="redText">(*)</span></td>
            <td>
                <input type="text" id="txt_cat_name" name="txt_cat_name" value="<?php echo $rs_single_cat['Name']; ?>" style="width:50%" />
                <span id="cat_name_countdown"></span>
                <script type="text/javascript">setCountdown('txt_cat_name', 67, 'cat_name_countdown')</script>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Chuyên mục cấp 1</td>
            <td>
                <?php echo html_select_box('sel_category', $v_ds_chuyen_muc_cap1, 'ID', 'Name', $rs_single_cat['Parent'], $extend='', $add_option = 1); ?>&nbsp;&nbsp;<i>Chỉ chọn nếu chuyên mục được tạo là chuyên mục cấp 2</i>
                
            </td>
        </tr>
        <tr>
            <td class="tbLabel">Mô tả chi tiết</td>
            <td>
                <textarea name="txt_note" id="txt_note" style="width:50%"><?php echo $rs_single_cat['Description']; ?></textarea>
                <span id="cat_des_countdown"></span>
                <script type="text/javascript">setCountdown('txt_note', 300, 'cat_des_countdown')</script>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Link gắn vào chuyên mục</td>
            <td>
                <input type = "text" id="txt_link" name="txt_link" value="<?php echo $rs_single_cat['Link']; ?>" style="width:50%" />
                <input type = "checkbox" id ="chk_link_type" name = "chk_link_type" <?php echo ($rs_single_cat['LinkType']==1)? 'checked':''?>/>&nbsp;&nbsp;<i>Mở trên 1 trang khác</i>
            </td>
        </tr>
		<?php /* Begin 26-07-2018 trungcq XLCYCMHENG_32226_bo_sung_anh_chia_se_mxh */ ?>
        <tr>
            <td width="130" class="tbLabel">Ảnh background chuyên mục:</td>
            <td>
                <input type="file" id="file_anh_dai_dien_cm" name="file_anh_dai_dien_cm" onchange="getImageDataURL(this, 'hdn_file_anh_dai_dien_cm', function(){document.getElementById('icon_crop_image_category').style.display=''})" style="max-width:250px" />
                <?php 
                if ($rs_single_cat['c_anh_dai_dien'] != '') {
                    ?>
                    <a href="<?php echo $rs_single_cat['c_anh_dai_dien']; ?>" target="_blank" rel="lightbox"><img src="<?php html_image('images/imgpreview.gif');?>" align="absmiddle" width="16" height="16" /></a>
                    <?php
                }
                $v_arr_anh_dai_dien = _get_module_config('category', 'v_arr_anh_dai_dien');
                $v_max_size_anh_dai_dien = _get_module_config('category', 'v_max_size_anh_dai_dien');
                ?>
                <a id="icon_crop_image_category" href="javascript:;" onclick="crop_image(document.frm_update_category, '<?php html_link('crop_image/index/file_anh_dai_dien_cm/c_anh_dai_dien_cm/hdn_file_anh_dai_dien_cm/'.$v_arr_anh_dai_dien[0].'/'.$v_arr_anh_dai_dien[1]); ?>', 'new_window');" title="Cắt ảnh" style="display:none"><img src="<?php html_image('images/image-crop-icon.png');?>" align="absmiddle" width="16" height="16" /></a>
                <input type="hidden" id="hdn_file_anh_dai_dien_cm" name="hdn_file_anh_dai_dien_cm" value="<?php echo $rs_single_cat['category_image']; ?>" />
                <input type="hidden" id="c_anh_dai_dien_cm" name="c_anh_dai_dien_cm" value="" />
                <span class="redText"><?php echo 'Ảnh chữ nhật có kích thước :'.$v_arr_anh_dai_dien[0].'x'.$v_arr_anh_dai_dien[1].', (size: 200kb)'; ?></span>
                <input type="hidden" id="hdn_anh_dai_dien" name="hdn_anh_dai_dien" value="<?php echo $rs_single_cat['c_anh_dai_dien']; ?>" />
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Ảnh chia sẻ MXH:</td>
            <td>
                <input type="file" id="file_anh_mxh" name="file_anh_mxh" onchange="getImageDataURL(this, 'hdn_file_anh_mxh', function(){document.getElementById('icon_crop_image_event').style.display=''})" style="max-width:250px" />
                <?php 
                if ($rs_single_cat['c_anh_chia_se_mxh'] != '') {
                    ?>
                    <a href="<?php echo $rs_single_cat['c_anh_chia_se_mxh']; ?>" target="_blank" rel="lightbox"><img src="<?php html_image('images/imgpreview.gif');?>" align="absmiddle" width="16" height="16" /></a>
                    <?php
                }
                $v_arr_anh_mxh_lon_nhat = _get_module_config('category', 'v_arr_anh_mxh_lon_nhat');
                $v_arr_anh_mxh_nho_nhat = _get_module_config('category', 'v_arr_anh_mxh_nho_nhat');
                ?>
                <a id="icon_crop_image_event" href="javascript:;" onclick="crop_image(document.frm_update_category, '<?php html_link('crop_image/index/file_anh_mxh/c_anh_chia_se_mxh/hdn_file_anh_mxh/'.$v_arr_anh_mxh_lon_nhat[0].'/'.$v_arr_anh_mxh_lon_nhat[1]); ?>', 'new_window');" title="Cắt ảnh" style="display:none"><img src="<?php html_image('images/image-crop-icon.png');?>" align="absmiddle" width="16" height="16" /></a>
                <input type="hidden" id="hdn_file_anh_mxh" name="hdn_file_anh_mxh" value="<?php echo $rs_single_cat['c_anh_chia_se_mxh']; ?>" />
                <input type="hidden" id="c_anh_chia_se_mxh" name="c_anh_chia_se_mxh" value="" />
                <span class="redText"><?php echo 'Ảnh chia sẻ có kích thước lớn nhất:'.$v_arr_anh_mxh_lon_nhat[0].'x'.$v_arr_anh_mxh_lon_nhat[1].', nhỏ nhất: '.$v_arr_anh_mxh_nho_nhat[0].'x'.$v_arr_anh_mxh_nho_nhat[1].', size: 200kb'; ?></span>
            </td>
        </tr>
		<?php /* End 26-07-2018 trungcq XLCYCMHENG_32226_bo_sung_anh_chia_se_mxh */ ?>
    </table>
    <div class="greyBox">
        <table border="0" width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="130" class="tbLabel">Hiển thị trên trang mobile</td>
            <td>
                <input type = "checkbox" id ="chk_mobile_dropdown" name = "chk_mobile_dropdown" <?php echo ($rs_single_cat['onmobile']==1)? 'checked':''?>/>&nbsp;&nbsp;<i>Hiển thị trong dropdown trên trang mobile</i>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Hiển thị trên mobile menu</td>
            <td>
                <input type = "checkbox" id ="chk_mobile_menu" name = "chk_mobile_menu" <?php echo ($rs_single_cat['onmobilemenu']==1)? 'checked':''?>/>&nbsp;&nbsp;<i>Hiển thị là menu của trang mobile</i>
            </td>
        </tr>
        <?php //Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
        <tr>
            <td width="130" class="tbLabel">Hiển thị trên menu dọc PC,Ipad</td>
            <td>
                <input type = "checkbox" id ="chk_is_show_on_pc" name = "chk_is_show_on_pc" <?php echo ($rs_single_cat['c_is_show_on_pc']==1)? 'checked':''?> value="1" />&nbsp;&nbsp;
            </td>
        </tr>
        <?php //End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc ?>
        <tr>
            <td width="130" class="tbLabel">Menu text trên mobile</td>
            <td>
                <input type = "textbox" id ="txt_mobile_text" name = "txt_mobile_text" value ="<?php echo $rs_single_cat['menutext']?>" style="width:30%"/>&nbsp;&nbsp;<i>Hiển thị là menu của trang mobile</i>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Thứ tự hiển thị trên dropdown mobile</td>
            <td>
                <input type = "textbox" id ="txt_dropdown_position" name = "txt_dropdown_position" value ="<?php echo $rs_single_cat['mb_dropdown_position']?>" style="width:10%"/>&nbsp;&nbsp;<i>Thứ tự nhập vào phải là số nguyên dương</i>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Thứ tự hiển thị trên menu ngang mobile</td>
            <td>
                <input type = "textbox" id ="txt_position" name = "txt_position" value ="<?php echo $rs_single_cat['mb_position']?>" style="width:10%"/>&nbsp;&nbsp;<i>Thứ tự nhập vào phải là số nguyên dương</i>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Hiển thị menu chuyên mục footer</td>
            <td>
                <input type = "radio" id ="rad_footer_option1" name = "rad_footer_option" value ="1" <?php echo ($rs_single_cat['footerOption']==1)? 'checked':''?>/>&nbsp;&nbsp;Hiển thị chuyên mục cấp 1
                <input type = "radio" id ="rad_footer_option2" name = "rad_footer_option" value ="2" <?php echo ($rs_single_cat['footerOption']==2)? 'checked':''?>/>&nbsp;&nbsp;Hiển thị chuyên mục cấp 2
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Menu ngang footer</td>
            <td>
               <label>
                <input type = "checkbox" id ="chk_menu_ngang_footer" name = "chk_menu_ngang_footer" value="1" <?php echo (intval($rs_single_cat['c_menu_ngang_footer']) == 1)? 'checked' : ''?>/>&nbsp; Hiển thị trên menu ngang footer
				</label>
            </td>
        </tr>
        <?php // begin 10-04-2017 : trungcq xu_ly_footer_ipad ?>
        <tr>
            <td width="130" class="tbLabel">Menu ngang footer Ipad</td>
            <td>
               <label><input type = "checkbox" id ="chk_menu_ngang_footer_ipad" name = "chk_menu_ngang_footer_ipad" value="1" <?php echo (intval($rs_single_cat['c_menu_ngang_footer_ipad']) == 1)? 'checked' : ''?>/>&nbsp; Hiển thị trên menu ngang footer ipad</label>
            </td>
        </tr>
        <?php // end 10-04-2017 : trungcq xu_ly_footer_ipad ?>
		<tr>
            <?php /* begin 28/4/2017 TuyenNT tinh_chinh_co_che_lay_du_lieu_hien_thi_tren_menu_ngang_header */ ?>
            <td width="130" class="tbLabel">Tên Chuyên mục rút gọn</td>
            <?php /* end 28/4/2017 TuyenNT tinh_chinh_co_che_lay_du_lieu_hien_thi_tren_menu_ngang_header */ ?>
            <td>
               <input type = "textbox" id ="txt_menu_ngang_footer" name = "txt_menu_ngang_footer" value ="<?php echo $rs_single_cat['c_ten_menu_ngang_footer']?>" style="width:30%"/>
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Hiển thị trên trang danh bạ</td>
            <td>
				<label>
                <input type = "checkbox" id ="chk_nha_hang" name = "chk_nha_hang" <?php echo ($rs_single_cat['nhahang']==1)? 'checked':''?>/>&nbsp; Chuyên mục là trang danh bạ
				</label>
            </td>
        </tr>
		<!--tr>
            <td width="130" class="tbLabel">Ảnh trang danh bạ</td>
            <td>
				<input type = "hidden" id ="hdn_anh_trang_danh_ba" name = "hdn_anh_trang_danh_ba" value ="<?php echo $rs_single_cat['nhahang_image']?>"/>
				<input type="file" name="file_anh_trang_danh_ba" onchange="getImageDataURL(this, 'hdn_anh_trang_danh_ba')" style="max-width:564px" />
                <a href="<?php echo $rs_single_cat['nhahang_image']; ?>" target="_blank" rel="lightbox" style="<?php echo $rs_single_cat['nhahang_image'] ? '' : 'visibility:hidden'; ?>"><img src="<?php html_image('images/imgpreview.gif');?>" align="absmiddle" width="16" height="16" /></a>
				<span class="redText"><i>Kích thước <?php echo $v_image_dimenssion[0]?> x <?php echo $v_image_dimenssion[1]?>px</i></span>
  			</td>
        </tr-->
        <tr>
            <td width="130" class="tbLabel">Thứ tự hiển thị  <span class="redText">(*)</span></td>
            <td>
                <input type = "text" id="txt_order" name="txt_order" value="<?php echo $rs_single_cat['Position']; ?>" style="width:10%" />&nbsp;&nbsp;<i>Thứ tự nhập vào phải là số nguyên dương</i>
            </td>
        </tr>
        <tr>
            <td width="130" class="tbLabel">Trạng thái xuất bản</td>
            <td>
                <?php echo html_select_box('sel_publish', $v_arr_trang_thai, 'c_code', 'c_name', $rs_single_cat['Activate'], $extend='', $add_option = 0); ?>
            </td>
        </tr>	
        <?php /* begin 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */ 
        ?>
        <tr>
            <td width="130" class="tbLabel">Chọn màu tab cho chuyên mục cấp 2  </td>
            <td>
                <?php 
                $v_ma_mau = $rs_single_cat['nhahang_image'];
                echo html_select_box_ma_mau('sel_ma_mau', $v_arr_ma_mau, 'c_code', 'c_name', $v_ma_mau, $extend='', $add_option = 1);
                ?>
                &nbsp;&nbsp;<i>Chỉ chọn màu cho chuyên mục cấp 2.</i>
            </td>
        </tr>
        <?php /* end 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */ ?>
       	<tr class ="tr_button">
            <td class="tbLabel"></td>
            <td><?php
                if ($this->getPerm('admin,edit')) {
					?>
					<a class="button_big btn_grey button_update" href="javascript:document.frm_update_category.action = '<?php html_link('ajax/'.$this->className().'/act_update_category/'.$v_id.'/'.$v_parent_id); ?>';document.frm_update_category.target='frm_submit'; document.frm_update_category.submit();">Cập nhật</a>&nbsp;<?php 
				}?>
				<a class="button_big btn_grey" href="javascript:btn_back_onclick(document.frm_update_category,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Thoát</a>                    
            </td>
        </tr>
        </table>       
    </div>      
</form>  
<iframe name="frm_submit" class="iframe-form"></iframe>