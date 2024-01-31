<?php
$id_box_breaking_news = 1;
$id_box_daily_news = 2;
$id_box_sport_news = 3;
$id_box_tin_tuc_giai_tri = 4;
$id_box_video = 5;
//05-08-2015 :Thangnb bo sung box_tin_tong_hop_dau_trang
$id_box_tin_tong_hop_dau_trang = 6;
//end
$specialbox_conf['NORMAL_BOX_WIDTH_VIEW'] = 290;
$specialbox_conf['BREAKING_NEWS_BOX_WIDTH_VIEW'] = 695;
$specialbox_conf['VIDEO_BOX_WIDTH_VIEW'] = 340;
$specialbox_conf['FUNNY_NEWS_BOX_WIDTH_VIEW'] = 220;
$specialbox_conf['NORMAL_BOX_HEIGHT_VIEW'] = 290;
$specialbox_conf['FUNNY_NEWS_HEIGHT_VIEW'] = 410;
$specialbox_conf['VIDEO_BOX_HEIGHT_VIEW'] = 290;
$images_position = WEB_ROOT.'images/images_upload/';
$images_temp = WEB_ROOT.'images/images_temp/';
$array_order = array();

// mang luu thu tu tu 1-6 cho box tin tuc trong ngay
$array_order[] = array("box_id"=>$id_box_daily_news,"total_order"=>6);
$array_order[] = array("box_id"=>$id_box_sport_news,"total_order"=>6);
$array_order[] = array("box_id"=>$id_box_tin_tuc_giai_tri,"total_order"=>8);
$array_order[] = array("box_id"=>$id_box_video,"total_order"=>10);
//05-08-2015 :Thangnb bo sung box_tin_tong_hop_dau_trang
/* begin 23/3/2017 TuyenNT tang_bai_viet_box_tin_tong_hop_trang_chu_24h_mobile*/
$array_order[] = array("box_id"=>$id_box_tin_tong_hop_dau_trang,"total_order"=>18);
/* end 23/3/2017 TuyenNT tang_bai_viet_box_tin_tong_hop_trang_chu_24h_mobile */
//end
//phuonghv add 16/08/2015 cau hinh mac dinh he so pageview, like-share
$specialbox_conf['HE_SO_PAGE_VIEW_MAC_DINH'] = 1;
$specialbox_conf['HE_SO_LIKE_SHARE_MAC_DINH'] = 20;
$specialbox_conf['GIO_NHO_NHAT'] = 0;
$specialbox_conf['GIO_LON_NHAT'] = 24;
$specialbox_conf['PHUT_NHO_NHAT'] = 0;
$specialbox_conf['PHUT_LON_NHAT'] = 60;
$specialbox_conf['GIO_LON_NHAT_MAC_DINH'] = 48;
$specialbox_conf['DS_ID_CHUYEN_MUC_TONG_HOP'] = '46,48,51,78,159,73,64';
$specialbox_conf['SO_BAI_CO_DIEM_CAO_1_CHUYEN_MUC'] = 3;
$specialbox_conf['TONG_SO_BAI_THUOC_CHUYEN_MUC_TONG_HOP'] = 21;
