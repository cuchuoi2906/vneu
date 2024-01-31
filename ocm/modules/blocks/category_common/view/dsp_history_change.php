<div class="contentTitle">LỊCH SỬ SỬA ĐỔI CHUYÊN MỤC</div>
<form name="frm_history_data" method="post" action="<?php echo fw24h_base64_url_decode($_GET['goback'])?>">
<?php
if(count($rs_data)>0){
    $i = 0;
    $v_count = count($rs_data) -1;
    for($j= $v_count; $j>=0; $j--) {       
        $rs_single_cat = json_decode($rs_data[$j]['du_lieu'], true);
        $v_nguoi_tao =  $rs_data[$j]['nguoi_tao'];
        $v_ngay_tao = date_format(date_create($rs_data[$j]['ngay_tao']), 'd/m/Y H:i:s');       
        $i++
?>
        <div class="line-dot"></div>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="130" class="tbLabel" align ="left"><b>Lần sửa: <?php echo $i;?></b></td>
                    <td align ="right">
                        <a class="button_big btn_grey" href="javascript:frm_submit(document.frm_history_data,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Quay lại</a>&nbsp;                  
                    </td>
                </tr>
                <tr>
                    <td width="130" class="tbLabel">Người sửa: </td>
                    <td>                        
                        <?php echo $v_nguoi_tao ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>Thời gian sửa:<?php echo $v_ngay_tao;?></span>
                    </td>
                </tr>
                <tr>
                    <td width="130" class="tbLabel">Tên chuyên mục  <span class="redText">(*)</span></td>
                    <td>
                       <?php echo $rs_single_cat['Name']; ?>
                    </td>
                </tr>
                <tr>
                    <td width="130" class="tbLabel">Chuyên mục cấp 1</td>
                    <td>
                       <?php echo ($rs_single_cat['Parent']==0)? '': get_category_name_by_id($rs_single_cat['Parent']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tbLabel">Mô tả chi tiết</td>
                    <td>
                        <?php echo $rs_single_cat['Description']; ?>
                    </td>
                </tr>
                <tr>
                    <td width="130" class="tbLabel">Link gắn vào chuyên mục</td>
                    <td>
                       <?php echo $rs_single_cat['Link']; ?>
                    </td>
                </tr>
				<?php /* Begin 26-07-2018 trungcq XLCYCMHENG_32226_bo_sung_anh_chia_se_mxh */ ?>
				<tr>
                    <td width="130" class="tbLabel">Ảnh đại diện chuyên mục</td>
                    <td>
                       <?php echo ($rs_single_cat['c_anh_dai_dien']!='')?'<img src="'.html_image_upload($rs_single_cat['c_anh_dai_dien'],false).'" height ="200px" width ="200px"/>':''; ?>
                    </td>
                </tr>
				<tr>
                    <td width="130" class="tbLabel">Ảnh chia sẻ MXH</td>
                    <td>
						<?php echo ($rs_single_cat['c_anh_chia_se_mxh']!='')?'<img src="'.html_image_upload($rs_single_cat['c_anh_chia_se_mxh'],false).'" height ="200px" width ="200px"/>':''; ?>
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
                    <td width="130" class="tbLabel">Hiển thị menu chuyên mục footer</td>
                    <td>
                        <input type = "radio" id ="rad_footer_option1" name = "rad_footer_option" value ="1" <?php echo ($rs_single_cat['footerOption']==1)? 'checked':''?>/>&nbsp;&nbsp;Hiển thị chuyên mục cấp 1
                        <input type = "radio" id ="rad_footer_option2" name = "rad_footer_option" value ="2" <?php echo ($rs_single_cat['footerOption']==2)? 'checked':''?>/>&nbsp;&nbsp;Hiển thị chuyên mục cấp 2
                    </td>
                </tr>
				<tr>
                    <td width="130" class="tbLabel">Hiển thị trên trang danh bạ</td>
                    <td>
                        <input type = "checkbox" id ="chk_nha_hang" name = "chk_nha_hang" <?php echo ($rs_single_cat['nhahang']==1)? 'checked':''?>/>&nbsp;&nbsp;<i>Chuyên mục là trang danh bạ</i>
                    </td>
                </tr>
				<?php 
				if ($rs_single_cat['nhahang_image']!='') {?>
				<tr>
                    <td width="130" class="tbLabel">Ảnh trang danh bạ</td>
                    <td>
						<image src ="<?php echo $rs_single_cat['nhahang_image'];?>" width = "564" height = "95"/>
                    </td>
                </tr><?php
				}
				?>
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
                <?php /* begin 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */ ?>
                <tr>
                    <td width="130" class="tbLabel">Tad màu chuyên mục cấp 2</td>
                    <td>
                        <?php 
                        $v_background_tad = $rs_single_cat['nhahang_image'];
                        $v_style = '';
                        $v_texxt = 'màu mặc định';
                        if($v_background_tad != ''){
                            $v_style = 'style="background-color: #'.$v_background_tad.';"';
                            $v_texxt = 'mã màu: '.$v_background_tad;
                        }?>
                        <input <?php echo $v_style; ?>  value="<?php echo $v_texxt; ?>">
                        &nbsp;&nbsp;<i>chỉ chọn màu cho chuyên mục cấp 2</i>
                    </td>
                </tr>
                <?php /* end 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */ ?>
            </table>       
        </div><?php
    }
 } else {?>    
 <p>Chưa có trong dữ liệu lịch sử</p>
 <a class="button_big btn_grey" href="javascript:frm_submit(document.frm_history_data,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Quay lại</a>
 <?php
 }
 ?>
 </form>