<div class="contentTitle">Thêm mới/Cập nhật Title,des,key,slug bài viết</div>
<div class="line-dot"></div>
<form name="frm_update_seo_chi_tiet" method="post" action="<?php html_link('ajax/'.$this->className().'/act_update_seo_chi_tiet/'.$v_id); ?>"  target ="frm_submit" enctype="multipart/form-data">
    <input type="hidden" name="goback" value="<?php echo $v_goback; ?>" />
    <table border="0" width="100%" cellpadding="5" cellspacing="0">
		<col width ="15%"/><col width ="20%"/><col width ="15%"/><col width ="50%"/>       
        <tr>
            <td width="130" class="tbLabel">ID bài viết<span class="redText">(*)</span></td>
            <td valign="middlle">
                <input type="text" id="txt_news_id" name="txt_news_id" value="<?php echo $v_news_id; ?>" style="width:120px" onChange = "parent.document.location.href='<?php html_link($this->className().'/dsp_single_seo_chi_tiet/'.$v_id.'/'); ?>'+this.value+'?goback=<?php echo index_xss_clean($v_goback);?>';"/>
				<?php if ($v_error_message !='') {
					echo '<br/><span class ="redText">'.$v_error_message.'</span>';
				}?>		
            </td>
			<td>&nbsp;</td>
			<td valign="middlle" class="title">
				<div style ="color: #989696;">
				<?php echo 'Tên bài viết:'.$rs_single_seo_chi_tiet['c_ten_bai_viet'].'<br/>BTV:'.$rs_single_seo_chi_tiet['c_bien_tap_vien'].', Ngày xuất bản:'.$rs_single_seo_chi_tiet['c_ngay_xuat_ban'] ;?>
				<br/>Chuyên mục:<?php echo $rs_single_seo_chi_tiet['c_ten_chuyen_muc']?>
                <?php
                /* Begin 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
                    $txt_su_kien_chinh = $txt_su_kien_phu = '';
                    if(count($v_arr_event)){
                        foreach ($v_arr_event as $event){
                            if($event['ID'] == $v_main_event){
                                $txt_su_kien_chinh .= $event['Name'];
                            } else {
                                $txt_su_kien_phu .= $event['Name'].', ';
                            }
                        }
                    }
                /* End 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
                ?>
                <br/>Sự kiện chính:<?php echo $txt_su_kien_chinh?>
                <br/>Sự kiện phụ:<?php echo trim($txt_su_kien_phu, ', ')?>
				</div>
			</td>
		</tr>
		<tr>
            <td width="130" class="tbLabel">Đóng dấu sapo</td>
            <td colspan = 3>
                <input type = "text" id="txt_sapo" name="txt_sapo" value="<?php echo htmlspecialchars($rs_single_seo_chi_tiet['c_sapo']); ?>" style="width:66%" />  
				<span id="txt_sapo_countdown"></span>
                <script type="text/javascript">setCountdown('txt_sapo', 21, 'txt_sapo_countdown')</script>
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Slug</td>
            <td colspan = 3>
                <input type="text" id="txt_slug" name="txt_slug" value="<?php echo $rs_single_seo_chi_tiet['c_slug']; ?>" style="width:66%" />
				<span id="txt_slug_countdown"></span>
                <script type="text/javascript">setCountdown('txt_slug', 300, 'txt_slug_countdown')</script>
            </td>
        </tr>        
		<tr>
            <td width="130" class="tbLabel">Title <span class="redText">(*)</span></td>
            <td colspan = 3>
                <input type = "text" id="txt_title" name="txt_title" value="<?php echo htmlspecialchars($rs_single_seo_chi_tiet['c_title']); ?>" style="width:66%" />
				<span id="txt_title_countdown"></span>
                <?php /* begin 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */ ?>
                <script type="text/javascript">setCountdown('txt_title', 200, 'txt_title_countdown')</script>
                <?php /* end 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */ ?>
                <span class="redText" id="error_txt_title" style="display: block"></span>
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Description <span class="redText">(*)</span></td>
            <td colspan = 3>
                <input type = "text" id="txt_desc" name="txt_desc" value="<?php echo htmlspecialchars($rs_single_seo_chi_tiet['c_desc']); ?>" style="width:66%" />
				<span id="txt_desc_countdown"></span>
                <?php /* begin 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */ ?>
                <script type="text/javascript">setCountdown('txt_desc', 300, 'txt_desc_countdown')</script>
                <?php /* end 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */ ?>
                <span class="redText" id="error_txt_desc" style="display: block"></span>
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Keyword</td>
            <td colspan = 3>
                <input type = "text" id="txt_keyword" name="txt_keyword" value="<?php echo htmlspecialchars($rs_single_seo_chi_tiet['c_keyword']); ?>" style="width:66%" />Các keyword cách nhau bởi dấu phẩy(,)			
            </td>
        </tr>
		<tr>
            <td width="130" class="tbLabel">Canonical</td>
            <td colspan = 3>
                <input type="text" id="txt_canonical" name="txt_canonical" value="<?php echo htmlspecialchars($rs_single_seo_chi_tiet['c_canonical']); ?>" style="width:66%" />
            </td>
        </tr>
        <?php /* Begin anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
        $v_on_off_title = _get_module_config('news','v_on_off_title_desc_mxh');
        if($v_on_off_title){
            if ($this->getPerm('admin,edit_mxh')) { ?>
                <tr>
                    <td width="130" class="tbLabel">Tiêu đề MXH</td>
                    <td colspan = 3>
                        <input type = "text" id="txt_canonical" name="txt_title_mxh" value="<?php echo $rs_single_seo_chi_tiet['c_title_mxh']; ?>" style="width:66%" />
                    </td>
                </tr>
                <tr>
                    <td width="130" class="tbLabel">Mô tả MXH</td>
                    <td colspan = 3>
                        <input type = "text" id="txt_canonical" name="txt_des_mxh" value="<?php echo $rs_single_seo_chi_tiet['c_des_mxh']; ?>" style="width:66%" />
                    </td>
                </tr> <?php 
            } ?>
            <tr>
                <td class="tbLabel">Ảnh chia sẻ do BTV nhập</td>
                <td>
                    <?php if($v_anh_chia_se_mxh != '') { ?>
                        <a href="<?php html_image_upload($v_anh_chia_se_mxh); ?>" rel="lightbox" target="_blank">
                            <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="tbLabel">Ảnh chia sẻ mạng xã hội</td>			
                <td>
                    <table>
                    <tr>
                        <td>
                        <?php                        
                            if ($rs_single_seo_chi_tiet['c_anh_chia_se_mxh']!='') {

                            ?>
                                <a href="<?php html_image_upload($rs_single_seo_chi_tiet['c_anh_chia_se_mxh']); ?>" rel="lightbox" target="_blank">
                                    <img src="<?php html_image('images/imgpreview.gif');?>" width="16" height="16" />
                                </a>
                        <?php
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                                if ($rs_single_seo_chi_tiet['c_anh_chia_se_mxh']!='') {?>
                                    <img src="<?php html_image('images/iconDelete.gif'); ?>" style="cursor:pointer" align="absmiddle" alt="[Xoá ảnh]" title="Xoá ảnh" onclick="if (confirm('Xoá ảnh?')) document.frm_update_seo_chi_tiet.hdn_anh_chia_se_mxh.value = '';document.frm_update_seo_chi_tiet.submit();" />	
                            <?php
                            }?>
                        </td>
                        <td></td>
                        <td>
                        <input type="file" name="file_chia_se_mxh" onchange="displayFileUploadInfo(this.files, 'filesInfo'); document.getElementById('btn_upload').style.display =  ''" />
                        <input type="hidden" name="hdn_anh_chia_se_mxh" value="<?php echo $rs_single_seo_chi_tiet['c_anh_chia_se_mxh']; ?>" />                    <br />
                        <i><span class="redText">(*) Kích thước tối đa 1200x628 px, dung lượng tối đa 200KB</span></i>
                        </td>
                    </tr>
                    </table>		
                </td>
                <td>
                    <input type="image" id="btn_upload" src="<?php html_image('images/btn-upload-anh.gif')?>" style="display:none" />
                </td>
            </tr>
        <?php } /* End anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */ ?>
        <tr id="box_nhap_ngay_ha_xb" style="<?php echo ($rs_single_seo_chi_tiet['c_tu_dong_ha_xuat_ban']) ? '' : 'display:none'; ?>">
            <td width="130" class="tbLabel"></td>
            <td colspan = 3>
                <table style="width: 100%;">
                    <tr>
                        <td width="106" class="tbLabel" style="text-align: left;">Ngày hạ XB</td>
                        <td>
                            <input type="text" id="txt_ngay_ha_xuat_ban" name="txt_ngay_ha_xuat_ban" class="frm_dsp_filter_date_select" style="width:80px" value="<?php echo date('d-m-Y',strtotime($rs_single_seo_chi_tiet['c_ngay_ha_xuat_ban'])); ?>">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php /* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */ ?>
		<tr>
            <td class="tbLabel">
				<?php 
				if ($this->getPerm('admin,publish')) {
				?>
					Trạng thái xuất bản <span class="redText">(*)</span>
				<?php
				}
				?>
			</td>
			<td>
				<?php 
				if ($this->getPerm('admin,publish')) {
				?>
					<div style="width:245x" class="boxSearchItem">
						<?php
						$v_arr_trang_thai = add_ascii_column($v_arr_trang_thai, 'c_name');		
						$v_div_script = 'class="form_input"  style="width:225px;padding-bottom:5px;padding-top:5px;"';
						$v_textbox_script = 'class="textbox"';
						$v_selectbox_script = 'size="3" class="dropdown"  style="width:240px;border:1px solid #CCCCCC"';
                        /*Begin 16-01-2017 trungcq bo_sung_tich_chon_chu_de_bai_viet_cap_nhat_seo*/
                        $rs_single_seo_chi_tiet['c_trang_thai_xuat_ban'] = (intval($rs_single_seo_chi_tiet['pk_seo_chi_tiet_bai_viet'])>0)?intval($rs_single_seo_chi_tiet['c_trang_thai_xuat_ban']):1;
                        /*End 16-01-2017 trungcq bo_sung_tich_chon_chu_de_bai_viet_cap_nhat_seo*/
						echo html_selectbox_loc_tim($rs_single_seo_chi_tiet['c_trang_thai_xuat_ban'], $v_arr_trang_thai,'c_code', 'c_name', 'c_name_ascii', 'loc_trang_thai_xuat_ban', 'sel_trang_thai_xuat_ban', 'Tìm nhanh trạng thái XB', $v_div_script, $v_textbox_script, $v_selectbox_script);	?>
					</div>				
				<?php
					}
				?>
			</td>	
        </tr>
        <tr>
            <td class="tbLabel">                        
                <input type="hidden" value="1" name="sel_thiet_bi" id="sel_thiet_bi" />                   
            </td>
        </tr>
		<tr class ="tr_button">
            <td class="tbLabel"></td>
            <td colspan = 3><?php
                if ($this->getPerm('admin,edit')) {
					?>
					<a class="button_big btn_grey button_update" href="javascript:void(0)" onclick="javascript:document.frm_update_seo_chi_tiet.submit()">Cập nhật</a>&nbsp;<?php 
				}?>
				<a class="button_big btn_grey" href="javascript:void(0)" onclick="javascript:btn_back_onclick(document.frm_update_seo_chi_tiet,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Thoát</a>                    
            </td>
        </tr>
		<tr>
			<td colspan =4>
				<div class="line-dot"></div>
			</td>
		</tr>
    </table>   
</form> 
<div>   
<table width = "100%">
	<col width ="33%"/><col width ="33%"/><col width ="33%"/>
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
        <td valign="top">			
			<span class="redText">Ghi chú: Mã kí tự đặc biệt</span><br/>
            •	& #34; tương đương dấu "<br/>
			•	& #39; tương đương dấu '<br/>
			•	...<br/>
			•	Khi nhập bài có thể dùng mã hoặc dùng trực tiếp dấu (", ',..) đều được<br/>
			•	Nếu có xuất hiện mã kí tự đặc biệt khi sửa bài: KHÔNG CẦN chỉnh lại thành dấu (", ',..)<br/>
		</td>
	</tr>
</table>
</div>
<iframe name="frm_submit" class="iframe-form"></iframe>			
<!-- goi ham xu ly an nut lenh khi ghi du lieu-->	
<style>
.ckbox_seo_chi_tiet_bai_viet > div {
    float: left;
    width: 50%;
}
</style>
<script>
$(document).ready(function() {
	$(function() {
		$(".frm_dsp_filter_date_select").datepicker();
	});
});
</script>