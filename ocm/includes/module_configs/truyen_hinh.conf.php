<?php
//	mảng mã kênh dùng riêng cho trang quản trị thông tin truyền hình
$arr_kenh = array(
	array('c_ma'=>1, 'c_ma_text'=>'VTV1', 'c_ten'=>'VTV1', 'c_ten_khong_dau'=>'vtv1', 'ul_id' => 0),
	array('c_ma'=>2, 'c_ma_text'=>'VTV2', 'c_ten'=>'VTV2', 'c_ten_khong_dau'=>'vtv2', 'ul_id' => 1),
	array('c_ma'=>3, 'c_ma_text'=>'VTV3', 'c_ten'=>'VTV3', 'c_ten_khong_dau'=>'vtv3', 'ul_id' => 2),
	array('c_ma'=>4, 'c_ma_text'=>'VTV4', 'c_ten'=>'VTV4', 'c_ten_khong_dau'=>'vtv4', 'ul_id' => 3),
	array('c_ma'=>5, 'c_ma_text'=>'VTV5', 'c_ten'=>'VTV5', 'c_ten_khong_dau'=>'vtv5', 'ul_id' => 4),
	array('c_ma'=>6, 'c_ma_text'=>'VTV6', 'c_ten'=>'VTV6', 'c_ten_khong_dau'=>'vtv5', 'ul_id' => 5),
	array('c_ma'=>9, 'c_ma_text'=>'VTV9', 'c_ten'=>'VTV9', 'c_ten_khong_dau'=>'vtv6', 'ul_id' => 6),
	array('c_ma'=>30, 'c_ma_text'=>'VTV Đã Nẵng', 'c_ten'=>'VTV Đà Nẵng', 'c_ten_khong_dau'=>'vtvdn', 'ul_id' => 7),
	array('c_ma'=>31, 'c_ma_text'=>'VTV Cần Thơ 1', 'c_ten'=>'VTV Cần Thơ 1', 'c_ten_khong_dau'=>'vtvct1', 'ul_id' => 8),
	array('c_ma'=>32, 'c_ma_text'=>'VTV Cần Thơ 2', 'c_ten'=>'VTV Cần Thơ 2', 'c_ten_khong_dau'=>'vtvct2', 'ul_id' => 9),
	array('c_ma'=>33, 'c_ma_text'=>'VTVCab1', 'c_ten'=>'VTV Cab 1', 'c_ten_khong_dau'=>'vtvcab1', 'ul_id' => 10),
	array('c_ma'=>34, 'c_ma_text'=>'VTVCab2', 'c_ten'=>'VTV Cab 2', 'c_ten_khong_dau'=>'vtvcab2', 'ul_id' => 11),
	array('c_ma'=>35, 'c_ma_text'=>'VTVCab3', 'c_ten'=>'VTV Cab 3', 'c_ten_khong_dau'=>'vtvcab3', 'ul_id' => 12),
	array('c_ma'=>36, 'c_ma_text'=>'VTVCab6', 'c_ten'=>'VTV Cab 6', 'c_ten_khong_dau'=>'vtvcab6', 'ul_id' => 13),
	array('c_ma'=>37, 'c_ma_text'=>'VTVCab7', 'c_ten'=>'VTV Cab 7', 'c_ten_khong_dau'=>'vtvcab7', 'ul_id' => 14),
	array('c_ma'=>38, 'c_ma_text'=>'VTVCab8', 'c_ten'=>'VTV Cab 8', 'c_ten_khong_dau'=>'vtvcab8', 'ul_id' => 15),
	array('c_ma'=>39, 'c_ma_text'=>'VTVCab17', 'c_ten'=>'VTV Cab 17', 'c_ten_khong_dau'=>'vtvcab17', 'ul_id' => 16),
);

// định dạng url lấy dữ liệu gốc
$_url_lay_du_lieu = 'http://vtv.vn/TVSchedule/136/<!--ma_kenh-->/<!--ngay-->/<!--thang-->/<!--nam-->.vtv';