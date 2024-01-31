<?php
$arr_menu_box = array();
$arr_menu_box[] = array('MENU_BOX_ID' => "1",
    'MENU_NAME' => "Chức năng cho nội dung"
);
$arr_menu_box[] = array('MENU_BOX_ID' => "2",
    'MENU_NAME' => "Quản trị seo"
);
$arr_menu_item = array();

$arr_menu_item[] = array('MENU_BOX_ID' => "1"
    , 'MENU_ITEMT_NAME' => "Nhập tin bài"
    , 'MENU_ITEMT_LINK' => BASE_URL . "news/index"
    , 'MENU_ITEMT_PERMISSION' => ""
);
$arr_menu_item[] = array('MENU_BOX_ID' => "1"
    , 'MENU_ITEMT_NAME' => "Chuyên mục"
    , 'MENU_ITEMT_LINK' => BASE_URL . "category/index"
    , 'MENU_ITEMT_PERMISSION' => ""
);

$arr_menu_item[] = array('MENU_BOX_ID' => "1"
    , 'MENU_ITEMT_NAME' => "Đăng ký thành viên"
    , 'MENU_ITEMT_LINK' => BASE_URL . "dang_ky_thanh_vien/index"
    , 'MENU_ITEMT_PERMISSION' => ""
);

$arr_menu_item[] = array('MENU_BOX_ID' => "2"
    , 'MENU_ITEMT_NAME' => "Title-des-keyw bài viết"
    , 'MENU_ITEMT_LINK' => BASE_URL . "seo_chi_tiet_bai_viet/index"
    , 'MENU_ITEMT_PERMISSION' => "VIEW_SEO_CHI_TIET_BAI_VIET"
);

$arr_menu_item[] = array('MENU_BOX_ID' => "1"
    , 'MENU_ITEMT_NAME' => "Magazine Content"
    , 'MENU_ITEMT_LINK' => BASE_URL . "magazine/index"
    , 'MENU_ITEMT_PERMISSION' => ""
);
// end 23-07-2018 bangnd XLCYCMHENG_28355_xay_dung_chuc_nang_quan_tri_noi_dung_bai_magazine
// begin 03-07-2018 bangnd XLCYCMHENG_28354_xay_dung_chuc_nang_quan_tri_template_bai_magazine
$arr_menu_item[] = array('MENU_BOX_ID' => "1"
    , 'MENU_ITEMT_NAME' => "Magazine template"
    , 'MENU_ITEMT_LINK' => BASE_URL . "template_magazine/index"
    , 'MENU_ITEMT_PERMISSION' => ""
);