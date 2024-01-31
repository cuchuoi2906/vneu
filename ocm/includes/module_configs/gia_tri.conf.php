<?php
/**
* Cấu hình thông số module
* @author cuongnx <cuongnx@24h.com.vn>
*/
/* Có sử dụng sphinx để tìm kiếm */
/* CAU HINH DANH SACH ID GIA TRI CHON ANH TU SERVER */
$arr_gia_tri['LOAI_DM_CHON_ANH_TU_SERVER']= array(
//1
/* DM THUONG HIEU */
//,2
);
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author cuongnx <cuongnx@24h.com.vn>
*/

$arr_gia_tri['SQL_ERROR']="Lỗi câu lệnh SQL";
$arr_gia_tri['TEN_GIA_TRI_DA_TON_TAI']="Giá trị bị trùng với 1 giá trị khác của cùng loại danh mục";
$arr_gia_tri['LOAI_DANH_MUC_KHONG_TON_TAI']="Loại danh mục không tồn tại";
$arr_gia_tri['GIA_TRI_KHONG_TON_TAI']="Giá trị không tồn tại";
$arr_gia_tri['THEM_MOI_THANH_CONG']="Đã lưu giá trị thành công";
$arr_gia_tri['DA_XOA_LOAI_DM_THANH_CONG']="{noi_dung} đã xóa thành công";
$arr_gia_tri['MA_GIA_TRI_TRONG']= "Vui lòng nhập mã giá trị";


/**
* Cấu hình mã thông báo dữ liệu không hợp lệ
* @author cuongnx <cuongnx@24h.com.vn>
*/

$arr_gia_tri['CHUA_NHAP_TEN']="Bạn chưa nhập giá trị";
$arr_gia_tri['TEN_KO_HOP_LE']="Giá trị tối đa 300 là ký tự.";
$arr_gia_tri['GIA_TRI_SS_KHONG_HOP_LE']="Giá trị so sánh phải là số nguyên dương.";
$arr_gia_tri['SAI_DINH_DANG']="Ảnh đại diện phải là định dạng " . IMAGE_EXTENSION_ALLOW ;
$arr_gia_tri['DUNG_LUONG_QUA_LON']="Ảnh đại diện tối đa " . round(MAX_IMAGE_SIZE/1024) . "kb";

/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author cuongnx <tytv@24h.com.vn>
*/
$arr_gia_tri['TU_KHOA']="Nhập giá trị để tìm kiếm";
$arr_gia_tri['MA_GIA_TRI']="Nhập mã giá trị để tìm kiếm";
$arr_gia_tri['ID_GIA_TRI']="Nhập ID giá trị";
$arr_gia_tri['NHAP_GIA_TRI_LIEN_QUAN']="Nhập, Chọn giá trị liên quan";


// Cấu hình ghi dữ liệu tới redis
$arr_gia_tri['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_gia_tri['TABLE_NAME'] = 't_gia_tri';
// Cấu hình template tạo key redis gía trị loại danh mục
$arr_gia_tri['REDIS_KEY_TEMPLATE'] = "{table_name}".NAME_DELIMITERS."{ma_gia_tri}".NAME_DELIMITERS."{ma_loai_danh_muc}"; //#table_name#code#