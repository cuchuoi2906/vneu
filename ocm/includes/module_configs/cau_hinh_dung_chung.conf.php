<?php
/**
 * Cấu hình hình dùng chung
 * @author Tytv <tytv@24h.com.vn>
 * @date    01-08-2015
 */
/* Begin: Tytv - 29/07/2016 - nang_cap_api_facebook */
$v_version_app_facebook = 'v2.6';
/* End: Tytv - 29/07/2016 - nang_cap_api_facebook */

// Khai báo tiền tố tạo mã code
$arr_cau_hinh_dung_chung['PREFIX_CODE']  = array(
    'GIA_TRI'   => 'GT', // Cấu hình giá trị loại danh mục
);
// Khai báo cấu hình nhóm loại danh mục
$arr_cau_hinh_dung_chung['NHOM_LOAI_DANH_MUC'] = array(
    // Cấu hình nhóm danh mục loại giá trị dùng chung
    array(
        'c_name'  => 'Nhóm cấu hình dùng chung',
        'c_code' => 1
    ),
    // Cấu hình nhóm danh mục loại giá trị của chức năng quản trị chuyên mục
    array(
        'c_name'  => 'Nhóm cấu hình danh mục loại giá trị',
        'c_code' => 2
    ),
);

// Khai báo các mã javascript hiển thị video
$arr_cau_hinh_dung_chung['MA_TACH_LOAI_VIDEO']  = array(
    array('c_code'=>'file','c_name'=>'Video thường'),
    array('c_code'=>'vtvWrite','c_name'=>'Video VTV'),
    array('c_code'=>'antvWrite','c_name'=>'Video An Ninh'),
    array('c_code'=>'ballballWrite7','c_name'=>'Video ballball 7 ngày'),
    array('c_code'=>'ballballWriteAll','c_name'=>'Video ballball all'),
    array('c_code'=>'euroWrite2016','c_name'=>'Video Euro'),
    /* begin 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
    array('c_code'=>'emobi_write','c_name'=>'Video Emobi'),
    /* end 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
);
// begin 18/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo
$v_cau_hinh_hien_thi_thong_bao_toi_uu_seo_bai_viet = 1; // 1: Có hiển thị , 0: Không hiển thị
$v_cau_hinh_thoi_gian_thong_bao_toi_uu_seo_bai_viet = 10; // thời gian 10 phút
$v_arr_ngay_gui_email_toi_uu_seo = array(1,2,3,4,5);// chỉ gửi từ thứ 2-6 hàng tuần
$v_thoi_gian_bat_dau_gui_mail_toi_uu_seo = date('Y-m-d').' 08:00:00';
$v_thoi_gian_ket_thuc_gui_mail_toi_uu_seo = date('Y-m-d').' 17:00:00';
// end 18/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo
/* begin 23/3/2017 TuyenNT tang_bai_viet_box_tin_tong_hop_trang_chu_24h_mobile */
$v_so_tin_box_dau_trang_chu = 18;
/* end 23/3/2017 TuyenNT tang_bai_viet_box_tin_tong_hop_trang_chu_24h_mobile */
/* begin 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */ 
$v_replace_vbscript = 'script/language(.*?)vbscript(.*?)';
/* end 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */
/* begin 28/9/2017 TuyenNT bo_sung_thong_ke_bai_video_emobi */
$v_arr_video_type = array(
    array('c_code'=>'vtvWrite','c_name'=>'Video VTV'),
    array('c_code'=>'emobi_write','c_name'=>'Video Emobi'),
    array('c_code'=>'antvWrite','c_name'=>'Video An Ninh'),
);
/* end 28/9/2017 TuyenNT bo_sung_thong_ke_bai_video_emobi */
// Begin TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header
$v_domain_redirect = 'http://click.vn/';
// End TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header