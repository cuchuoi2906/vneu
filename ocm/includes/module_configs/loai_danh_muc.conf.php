<?php
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author cuongnx <cuongnx@24h.com.vn>
*/

$arr_loai_danh_muc['SQL_ERROR']="Lỗi câu lệnh SQL";
$arr_loai_danh_muc['TON_TAI_MA_DANH_MUC']="Mã loại danh mục bị trùng với 1 loại danh mục khác";
$arr_loai_danh_muc['LOAI_DANH_MUC_KHONG_TON_TAI']="Loại danh mục không tồn tại";
$arr_loai_danh_muc['LOAI_DANH_MUC_CO_GIA_TRI']="Loại danh mục có chứa giá trị. Bạn không xóa được";
$arr_loai_danh_muc['DA_XOA_LOAI_DM_THANH_CONG']="{noi_dung} đã xóa thành công";
$arr_loai_danh_muc['LOAI_DANH_MUC_DUNG_CHUNG']="Đây là loại danh mục dùng chung. Không cập nhật ngành hàng liên quan";

/**
* Cấu hình mã thông báo dữ liệu không hợp lệ
* @author cuongnx <cuongnx@24h.com.vn>
*/

$arr_loai_danh_muc['CHUA_NHAP_TEN']="Bạn chưa nhập tên loại danh mục";
$arr_loai_danh_muc['TEN_KO_HOP_LE']="Tên loại danh mục chưa đúng định dạng. Tên loại danh mục tối đa 300 ký tự, có thể là chữ, số và 1 số ký tự đặc biệt là dấu gạch ngang, dấu gạch dưới, dấu chấm";
$arr_loai_danh_muc['CHUA_NHAP_MA']="Bạn chưa nhập Mã loại danh mục";
$arr_loai_danh_muc['MA_KO_HOP_LE']="Mã loại danh mục chưa đúng định dạng. Mã loại danh mục tối đa 50 ký tự, có thể là chữ, số và 1 số ký tự đặc biệt là dấu gạch ngang, dấu gạch dưới, dấu chấm";

/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author cuongnx <cuongnx@24h.com.vn>
*/
$arr_loai_danh_muc['TU_KHOA']="Nhập tên loại danh mục để tìm kiếm";
$arr_loai_danh_muc['ID_LOAI_DM']="Nhập ID loại danh mục";
$arr_loai_danh_muc['ID_NGANH_HANG']="Nhập ID ngành hàng";
$arr_loai_danh_muc['TIM_NHANH_LOAI_DM']="Tìm nhanh loại danh mục";

// Cấu hình ghi dữ liệu tới redis
$arr_loai_danh_muc['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_loai_danh_muc['TABLE_NAME'] = 't_loai_danh_muc';
// Cấu hình template tạo key redis loại danh mục
$arr_loai_danh_muc['REDIS_KEY_TEMPLATE'] = '{table_name}'.NAME_DELIMITERS.'{code}';