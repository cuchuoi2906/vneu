<?php
// Gioi han kich thuoc anh dai dien
$image_dimension = array(200, 200); 
$thumnail_resize =  array(
	//15-12-2015 : Thangnb chinh_sua_cat_anh_su_kien
    'medium' => array(
        'width' => 180,
        'height' => 135,
        'folder' => 'medium'
    ),
	//Begin 27-10-2015 : Thangnb chinh_sua_kich_thuoc_anh
    'thumbnail' => array(
        'width' => 110,
        'height' => 83,
        'folder' => 'thumbnail'
    )
);
// Anh chu nhat
$image_dimension_chu_nhat = array(300, 225); 
$thumnail_resize_chu_nhat =  array(
	//15-12-2015 : Thangnb chinh_sua_cat_anh_su_kien
    'medium' => array(
        'width' => 180,
        'height' => 135,
        'folder' => 'medium'
    ),
	//Begin 27-10-2015 : Thangnb chinh_sua_kich_thuoc_anh
    'thumbnail' => array(
        'width' => 110,
        'height' => 83,
        'folder' => 'thumbnail'
    )
);

// Gioi hạn dung luong anh dai dien
$image_size = 16384; // 16KB
$image_size_chu_nhat = 25600; // 25KB
// chieu rong box preview
$priview_box_width = 600;
// chieu cao box preview
$priview_box_height = 520;
$v_domain_image_old = 'http://anh.24h.com.vn/';

// begin 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
$anh_event['kich_thuoc'] = array(300,225); // cau hinh kich thuoc anh su kien
// end 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
/* Begin anhpt1 29/06/2016 export_import_title_des_key_su_kien */
$v_so_items_export_seo_chi_tiet_event = 10000;
$v_hien_thi_ten_hoac_id_thiet_bi  = 1; // 1: hi?n th? id, 2: hi?n th? t?n
$seo_chi_tiet_su_kien_conf['SO_KI_TU_TOI_DA_LINK_REDIRECT'] = 2000; 
$seo_chi_tiet_su_kien_conf['SO_NGAY_CHO_PHEP_REDIRECT'] = 10000; 
$seo_chi_tiet_su_kien_conf['THU_MUC_CHUA_FILE_EXCEL'] = WEB_ROOT.'modules/exceldata/data'; // thu muc chua file excel upload
$seo_chi_tiet_su_kien_conf['SO_COT_TOI_DA'] = 6; 
$seo_chi_tiet_su_kien_conf['FILE_EXCEL_MAU'] = 'modules/exceldata/temp/seo_chi_tiet_su_kien.xls';
$v_so_max_dong_import_keyword_link  = 2000;
$v_arr_thiet_bi  = array(1,2,3);
/* End anhpt1 29/06/2016 export_import_title_des_key_su_kien */
$v_so_nam_hien_thi_keyword_update_tu_event = 20;