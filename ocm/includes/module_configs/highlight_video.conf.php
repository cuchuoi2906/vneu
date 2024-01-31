<?php
/**
* Cấu hình thông số module
* @author Tytv <Tytv@24h.com.vn>
*/
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author Tytv <Tytv@24h.com.vn>
*/
$arr_highlight_video['SQL_ERROR']="Lỗi câu lệnh SQL";

/**
* Cấu hình mã thông báo dữ liệu không hợp lệ
* @author Tytv <Tytv@24h.com.vn>
*/
$arr_highlight_video['CHUA_NHAP_TEN']="Bạn chưa nhập giá trị";
$arr_highlight_video['BAI_VIET_DA_GAN_HIGHLIGHT']= "Bài viết %s đã được nhập highlight video. Không thể thêm";
$arr_highlight_video['BAI_DANG_XUAT_BAN']= "Bài đang xuất bản,bạn không thể xóa";

/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author Tytv <tytv@24h.com.vn>
*/
$arr_highlight_video['ID_BAI_VIET']="Nhập id bài viết";
$arr_highlight_video['TEN_BAI_VIET']="Nhập tên bài viết";

$arr_highlight_video['SO_LUONG_HIGHLIGHT_CHO_1_VIDEO']= 10;
$arr_highlight_video['SO_LUONG_KY_TU_TOI_DA_GHI_CHU']= 300;
$arr_highlight_video['ID_LOAI_DANH_MUC_CHON_ICON']= 1;


// Cấu hình ghi dữ liệu tới redis
$arr_highlight_video['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_highlight_video['KEY_VALUE_NAME'] = 'data_bai_viet_highlight_video_theo_id_bai_viet_';
// cấu hình mặc đinh icon tình huống
$arr_highlight_video['SU_DUNG_ICON_MAC_DINH'] = true;
