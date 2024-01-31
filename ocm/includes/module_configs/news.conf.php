<?php
// Resize thumnail cho anh dai dien bai viet
$thumnail_resize =  array(
    'thumbnail' => array(
        'width' => 100,
        'height' => 100,
        'folder' => 'thumbnail'
    ),
    'medium' => array(
        'width' => 150,
        'height' => 150,
        'folder' => 'medium'
    )
);

$thumnail_resize_chu_nhat =  array(
    'thumbnail' => array(
        'width' => 110,
        'height' => 83,
        'folder' => 'thumbnail'
    ),
    'medium' => array(
        'width' => 180,
        'height' => 135,
        'folder' => 'medium'
    ),
    // begin 13/09/2016 TuyenNT nang_cap_trang_chu_24h_ban_pc
    '120x90' => array(
        'width' => 120,
        'height' => 90,
        'folder' => '120x90'
    )
    // end 13/09/2016 TuyenNT nang_cap_trang_chu_24h_ban_pc
);

$dilimiter_keywords = ','; // dau phan tach cac keyword

$ipad_domain = "http://ipad.24h.com.vn/";
$mobile_domain = "http://m.24h.com.vn/";

$anh_chia_se_mxh['max_size'] = '3 * 1024 * 1024;'; //200KB
$anh_chia_se_mxh['kich_thuoc'] = array(1903,330);
//phuonghv add 25/03/2015: ty le anh crop tu anh dai dien
$anh_chia_se_mxh['kich_thuoc_anh_cat'] = array(640,335);
//phuonghv add 25/03/2015
$anh_dai_dien['kich_thuoc'] = array(640,480);
$so_tin_lien_quan_duoi_sapo_toi_da = 3;
//Begin : 26-10-2015 Thangnb bo_sung_kiem_tra_code_video
/* begin 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
$arr_dau_hieu_nhan_biet_code_video_mp4 = array(">[\s]*flashWrite[\s]*\(",">[\s]*vtvWrite[\s]*\(",">[\s]*euroWrite2016[\s]*\(",'>[\s]*ballballWriteAll[\s]*\(','>[\s]*ballballWrite7[\s]*\(','>[\s]*quangcaoWrite[\s]*\(','>[\s]*heinekenWrite[\s]*\(',">[\s]*emobi_write[\s]*\(");
/* end 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
//End : 26-10-2015 Thangnb bo_sung_kiem_tra_code_video
//Begin 10-11-2015 : Thangnb dieu_chinh_anh_dai_dien_video
$anh_dai_dien_video['kich_thuoc'] = array(532,301);
$thumnail_resize_anh_dai_dien_video =  array(
    'thumbnail' => array(
        'width' => 165,
        'height' => 94,
        'folder' => 'thumbnail'
    ),
    'medium' => array(
        'width' => 328,
        'height' => 184,
        'folder' => 'medium'
    ),
    // begin 14/09/2016 TuyenNT nang_cap_trang_chu_24h_ban_pc
    '150x100' => array(
        'width' => 150,
        'height' => 100,
        'folder' => '150x100'
    )
    // end 14/09/2016 TuyenNT nang_cap_trang_chu_24h_ban_pc
);
//End 10-11-2015 : Thangnb dieu_chinh_anh_dai_dien_video

// begin 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
// begin 17/03/2016 tuyennt tinh_chinh_kich_thuoc_anh_breaking_news_ocm_24h
$anh_breaking_news['kich_thuoc'] = array(600,360); // cau hinh kich thuoc anh breaking news bai viet
$thumnail_resize_anh_breaking_new =  array(
    'medium' => array(
        'width' => 600,
        'height' => 360,
        'folder' => 'medium'
    )
);
// end 17/03/2016 tuyennt tinh_chinh_kich_thuoc_anh_breaking_news_ocm_24h
// end 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
//Begin 17-03-2016 : Thangnb dieu_chinh_thoi_gian_day_bai_sang_khampha
$v_thoi_gian_delay_day_bai_sang_khampha = '15'; //Phut
//End 17-03-2016 : Thangnb dieu_chinh_thoi_gian_day_bai_sang_khampha
/* Begin anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
$v_on_off_title_desc_mxh = false;
/* End anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
//Begin 29-04-2016 : Thangnb xu_ly_bai_pr_gia_re
$v_max_so_luong_bai_1_khung_gio = 24;
$v_arr_khung_gio_pr_gia_re = array('08:00:00,10:00:00','10:00:00,12:00:00','12:00:00,14:00:00','14:00:00,16:00:00','16:00:00,20:00:00','20:00:00,22:00:00','22:00:00,08:00:00');
//End 29-04-2016 : Thangnb xu_ly_bai_pr_gia_re
// begin 30/05/2016 TuyenNT bo_sung_chuc_nang_chon_CM_banner_layout_chuc_nang_cap_nhat_tin_bai
$v_arr_danh_sach_chuyen_muc_hien_thi_banner_layout = array(708,709,710,711,712,713,714,715,716,717,718,719,720,721,722,723,724,725,726,727,728);
// end 30/05/2016 TuyenNT bo_sung_chuc_nang_chon_CM_banner_layout_chuc_nang_cap_nhat_tin_bai
/* Begin anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
$v_so_items_export_seo_chi_tiet_bai_viet  = 10000;
$v_hien_thi_ten_hoac_id_thiet_bi  = 1; // 1: hiển thị id, 2: hiển thị tên
$seo_chi_tiet_bai_viet_conf['SO_KI_TU_TOI_DA_LINK_REDIRECT'] = 2000; 
$seo_chi_tiet_bai_viet_conf['SO_NGAY_CHO_PHEP_REDIRECT'] = 10000; 
$seo_chi_tiet_bai_viet_conf['THU_MUC_CHUA_FILE_EXCEL'] = WEB_ROOT.'modules/exceldata/data'; // thu muc chua file excel upload
$seo_chi_tiet_bai_viet_conf['SO_COT_TOI_DA'] = 6; 
$seo_chi_tiet_bai_viet_conf['FILE_EXCEL_MAU'] = 'modules/exceldata/temp/seo_chi_tiet_bai_viet.xls';
$v_so_max_dong_import_keyword_link  = 2000;
$v_arr_thiet_bi  = array(1,2,3);
/* End anhpt1 27/06/2016 export_import_title_des_key_bai_viet */

// begin 02/11/2016 TuyenNT tang_so_luong_anh_so_sanh_duoc_phep_up_load_15_cap_anh
$v_max_so_luong_anh_so_sanh = 30;
// end 02/11/2016 TuyenNT tang_so_luong_anh_so_sanh_duoc_phep_up_load_15_cap_anh
//Begin 06-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
$v_website_name_for_khampha = '24h.com.vn';
//End 06-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
// begin 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend
$v_so_luong_anh_trong_bai_infographic = 100; // so luong anh trong bai viet dang su kien infographic
$v_arr_list_domain = array('mst.24h.com.vn','http://static.24h.com.vn','http://image.24h.com.vn','http://anh.24h.com.vn','http://www.24h.com.vn/','image.24h.com.vn','http://www.24h.com.vn/','static.24h.com.vn','anh.24h.com.vn');
/* begin 20/12/2016 TuyenNT tang_dung_luong_anh_info */
$v_anh_noi_dung_infographic['max_size'] = '512000'; //500KB
/* end 20/12/2016 TuyenNT tang_dung_luong_anh_info */
$v_thong_bao_vuot_so_luong_anh = 'Cảnh báo: Ảnh trong nội dung bài vượt quá ';
// end 31/08/2016 TuyenNT trang_bai_viet_tong_hop_su_kien_infographic_backend
/* Begin anhpt1 5/09/2016 tang_dung_luong_anh_gif */
$v_max_size_anh_dai_dien_bai_dang_gif = 2560000;
/* Begin anhpt1 19/09/2016  chien_dich_nivea */
$v_id_cau_hinh_loai_danh_muc = 2;
/* End anhpt1 19/09/2016  chien_dich_nivea */
/* Begin anhpt1 29/3/2016 de_xuat_event_profile_theo_noi_dung_bai_viet */
$v_max_tu_lay_ra = 8; //số từ khóa
$v_min_tu_lay_ra = 2; // số từ khóa
$v_gioi_han_chuoi_tu_khoa = 500;
/* End anhpt1 29/3/2016 de_xuat_event_profile_theo_noi_dung_bai_viet */

//Begin 07-12-2016 : Thannb xu_ly_bai_magazine backend
$v_so_luong_anh_trong_bai_magazine = 100; // so luong anh trong bai viet
$v_anh_noi_dung_magazine['max_size'] = '10240000'; //150KB
//End 07-12-2016 : Thannb xu_ly_bai_magazine backend

/*Begin 25-08-2017 trungcq XLCYCMHENG_25109_xu_ly_loi_thieu_the_p_editor*/
$v_arr_loai_bai_fix_loi_chen_the_p_rong = array('non-gif-image-gif','twentytwenty-container');
/*End 25-08-2017 trungcq XLCYCMHENG_25109_xu_ly_loi_thieu_the_p_editor*/
/*Begin 08-11-2017 trungcq XLCYCMHENG_27473_bo_sung_anh_dai_dien_schema_article*/
$arr_anh_schema_article['kich_thuoc_anh'] = array(700,394);
$arr_anh_schema_article['prefix'] = '_schema_article';
/*End 08-11-2017 trungcq XLCYCMHENG_27473_bo_sung_anh_dai_dien_schema_article*/
$v_size_toi_da = 3 * 1024 * 1024;
$v_arr_background_news = array(1903,330);
$v_max_size_arr_background_news = 3 * 1024 * 1024;