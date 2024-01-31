<?php
$array_vung_mien = array();
$array_vung_mien[0] = "Hồ Chí Minh";
$array_vung_mien[1] = "Hà Nội";
$array_vung_mien[2] = "Đà Nẵng";
$array_vung_mien[3] = "Nha Trang";
$array_vung_mien[4] = "Cần Thơ";
$ma_vung_quoc_te  = 999;
//nguon cung cap du lieu
$xml_feed_file = "http://sjc.com.vn/xml/tygiavang.xml";
$site_source = "http://sjc.com.vn";		
$max_size_of_icon = 9216; //Dung luong anh toi da < 10KB
$icon_dimension = array(0,0); // kich thuoc icon
$arr_hcm_gold = array('SJC10c','SJC99.99N','SJC99.99', '24K', '18K', '14K', '10K');
$url_gia_vang_quoc_te = 'http://vip2.giavang.net/teline2/data/aj2a.php';
$video_domain = 'http://video.24h.com.vn/';
// cau hinh ma vang quoc te 
$arr_ma_vang_quoc_te = array();
$ma_vang_the_gioi = '(SPOT GOLD)';
$ma_vang_the_gioi_24h = 'Vàng TG ($)';
//Begin 31-03-2016 : Thangnb fix_cap_nhat_gia_vang
$arr_ma_vang_quoc_te = array("(SPOT GOLD)", "BẢO TÍN MINH CHÂU", "SJC Hà Nội", "SJC Đà Nẵng", "SJC TP HCM", "SJC MIỀN BẮC", "DOJI HN", "DOJI SG", "SJC PHÚ QUÝ HÀ NỘI", "TECHCOMBANK", "DONGA BANK", "VIETINBANK GOLD", "TIENPHONGBANK GOLD", "MARITIME BANK", "VPBANK", "SACOMBANK", "EXIMBANK", "OCB", "VIETNAMGOLD", "Ngọc Hải (NHJ) TP.HCM","Ngọc Hải (NHJ) Tiền Giang","Phượng Hoàng PNJ Đông Á", "SBJ Sacombank");
//End 31-03-2016 : Thangnb fix_cap_nhat_gia_vang