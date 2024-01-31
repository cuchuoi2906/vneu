<?php
/**
* Cấu hình thông số module
* @author Tytv <Tytv@24h.com.vn>
*/
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author Tytv <Tytv@24h.com.vn>
*/
$arr_menu_ngang_header['SQL_ERROR']="Lỗi câu lệnh SQL";
$arr_menu_ngang_header['ERROR_DA_TON_TAI']="Chuyên mục {ten_chuyen_muc}, thiết bị {ten_thiet_bi} đã được cấu hình menu ngang header";
$arr_menu_ngang_header['ERROR_DELETE_DANG_XUAT_BAN']="Không thể xóa cấu hình chuyên mục menu ngang header đang xuất bản";
$arr_menu_ngang_header['ERROR_CHUYEN_MUC_DA_DUOC_ANH_XA']="Chuyên mục được ánh xạ đã được ánh xạ bởi chuyên mục video khác! Vui lòng chọn lại chuyên mục video chưa được ánh xạ.";
/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author Tytv <tytv@24h.com.vn>
*/
// Cấu hình ghi dữ liệu tới redis
$arr_menu_ngang_header['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_menu_ngang_header['KEY_VALUE_NAME'] = 'data_menu_ngang_header_theo_chuyen_muc_id_';
$arr_menu_ngang_header['TABLE_NAME'] = _CACHE_TABLE; 
// Begin TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header
$arr_menu_ngang_header['v_so_luong_ky_tu_menu'] = 30; 
// End TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header

