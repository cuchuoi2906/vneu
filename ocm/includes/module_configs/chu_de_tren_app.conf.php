<?php
$chu_de_conf['SO_KI_TU_TOI_DA_TEN_CHU_DE'] = 300;
$chu_de_conf['SO_KI_TU_TOI_DA_TEN_CHU_DE_TREN_APP'] = 300;
$chu_de_conf['SO_KI_TU_TOI_DA_TEN_CHU_DE_TREN_APP'] = 300;
$chu_de_conf['KICH_THUOC_ANH_DAI_DIEN'] = array(320,320);
$chu_de_conf['KICH_THUOC_ANH_COVER'] = array(1004,380);
$chu_de_conf['DUNG_LUONG_TOI_DA_ANH_DAI_DIEN'] = 51200; //50kb
$chu_de_conf['DUNG_LUONG_TOI_DA_ANH_COVER'] = 204800; //200kb
$chu_de_conf['SO_KI_TU_TOI_DA_TOM_TAT_PROFILE'] = 300;
$chu_de_conf['SO_KI_TU_TOI_DA_TIEU_SU_PROFILE'] = 3000; 
// anhpt1 thêm số kí tự được nhập textbox fanpage
$chu_de_conf['SO_KI_TU_TOI_DA_FACPAGE_PROFILE'] = 500; 

// begin 27/10/2015 tuyennt bo sung: resize_anh_dai_dien_cover_profile
// Resize thumnail cho anh dai dien, anh cover profile
$thumnail_resize_anh_dai_dien =  array(
    'thumbnail' => array(
        'width' => 84,
        'height' => 84,
        'folder' => 'profile_image_84x84'
    ),
    'medium' => array(
        'width' => 110,
        'height' => 110,
        'folder' => 'profile_image_110x110'
    ),
    'maxium' => array(
        'width' => 180,
        'height' => 180,
        'folder' => 'profile_image_180x180'
    )
);

$thumnail_resize_anh_cover =  array(
    'medium' => array(
        'width' => 768,
        'height' => 291,
        'folder' => 'profile_cover_768x291'
    )
);
// end 27/10/2015 tuyennt bo sung: resize_anh_dai_dien_cover_profile

// begin 23/11/2015 tuyennt bo_sung_nut_tao_du_lieu_cho_profile
$arr_url_key_data = array(
    0 => array(
        'c_url' => 'ajax/create_key_data_profile/gen_data_profile/'
    ),
    1 => array(
        'c_url' => 'ajax/create_key_data_profile/gen_data_danh_sach_tin_bai_profile/'
    ),
    2 => array(
        'c_url' =>'ajax/create_key_data_profile/gen_data_danh_sach_tin_bai_video_profile/'
    ),
    3 => array(
        'c_url' =>'ajax/create_key_data_profile/gen_data_danh_sach_album_anh_profile/'
    )
  );

$chu_de_conf['URL_KEY_DATA_PROFILE_THEO_CHUYEN_MUC'] = 'ajax/create_key_data_profile/gen_data_danh_sach_profile_theo_chuyen_muc/'; 
// end 23/11/2015 tuyennt bo_sung_nut_tao_du_lieu_cho_profile

// begin 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
$anh_tag_app['kich_thuoc'] = array(320,320); // cau hinh kich thuoc anh tag app
$anh_cover_tag_app['kich_thuoc'] = array(1004,380); // cau hinh kich thuoc anh cover tag app
// end 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h

// begin 04/04/2016 TuyenNT them_menu_chu_de_da_nhap_bai_viet
$url_chu_de_co_bai_viet = 'chu_de_tren_app/index?txt_category_id=Nhập+chuyên+mục&hdn_category_id=&sel_category_id=0&txt_user_id=Người+sửa+cuối&sel_user_id=0&txt_trang_thai=Trạng+thái&sel_status=-1&txt_loai_chu_de=Loại+chủ+đề&sel_loai_chu_de_filter=-1&txt_loai_profile=Loại+profile&sel_loai_profile_filter=-1&txt_chu_de_id=&txt_ten_chu_de=&txt_from_date=&txt_to_date=&chk_xem_chu_de_da_nhap_bai_viet=1&sel_column=&sel_sorttype=&number_per_page=20';
// end 04/04/2016 TuyenNT them_menu_chu_de_da_nhap_bai_viet
/* Begin anhpt1 12/07/2016 export_import_title_des_key_profile */
$v_so_items_export_seo_chi_tiet_profile = 10000;
$seo_chi_tiet_profile_conf['SO_KI_TU_TOI_DA_LINK_REDIRECT'] = 2000; 
$seo_chi_tiet_profile_conf['SO_NGAY_CHO_PHEP_REDIRECT'] = 10000; 
$seo_chi_tiet_profile_conf['THU_MUC_CHUA_FILE_EXCEL'] = WEB_ROOT.'modules/exceldata/data'; // thu muc chua file excel upload
$seo_chi_tiet_profile_conf['SO_COT_TOI_DA'] = 6; 
$seo_chi_tiet_profile_conf['FILE_EXCEL_MAU'] = 'modules/exceldata/temp/seo_chi_tiet_profile.xls';
$v_so_max_dong_import_profile  = 2000;
$v_arr_thiet_bi  = array(1,2,3);
/* End anhpt1 12/07/2016 export_import_title_des_key_profile */

// begin 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de 
$on_off_chuc_nang_tu_tao_chu_de = false;  // cau hinh on/off chuc nang tu tao chu de: true co tao tu dong, flase: khong tao tu dong
// end 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de 