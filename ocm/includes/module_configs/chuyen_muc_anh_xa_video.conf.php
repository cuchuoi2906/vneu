<?php
/**
* Cấu hình thông số module
* @author Tytv <Tytv@24h.com.vn>
*/
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author Tytv <Tytv@24h.com.vn>
*/
$arr_chuyen_muc_anh_xa_video['SQL_ERROR']="Lỗi câu lệnh SQL";
$arr_chuyen_muc_anh_xa_video['ERROR_DA_TON_TAI']="Chuyên mục đã được cấu hình ánh xạ";
$arr_chuyen_muc_anh_xa_video['ERROR_DELETE_DANG_XUAT_BAN']="Không thể xóa cấu hình chuyên mục video ánh xạ đang xuất bản";
$arr_chuyen_muc_anh_xa_video['ERROR_CHUYEN_MUC_DA_DUOC_ANH_XA']="Chuyên mục được ánh xạ đã được ánh xạ bởi chuyên mục video khác! Vui lòng chọn lại chuyên mục video chưa được ánh xạ.";
/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author Tytv <tytv@24h.com.vn>
*/
// cấu hình ID chuyên mục video cha để lấy các chuyên mục con dùng ánh xạ
$arr_chuyen_muc_anh_xa_video['ID_CHUYEN_MUC_VIDEO_CAP_1_ANH_XA']= 768;
// Cấu hình ghi dữ liệu tới redis
$arr_chuyen_muc_anh_xa_video['WRITE_DATA_TO_REDIS'] = false;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_chuyen_muc_anh_xa_video['KEY_VALUE_NAME'] = 'data_chuyen_muc_anh_xa_video_id_';
$arr_chuyen_muc_anh_xa_video['TABLE_NAME'] = _CACHE_TABLE; 


