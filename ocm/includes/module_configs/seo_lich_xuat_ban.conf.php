<?php
/**
* Cấu hình thông số module
* @author TungVN <tungvn@24h.com.vn>
*/
// Cấu hình ghi dữ liệu tới redis
$arr_seo_lich_xuat_ban['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_seo_lich_xuat_ban['KEY_VALUE_NAME_NEWS'] = 'data_seo_lich_xuat_ban_theo_bai_viet_id_';
$arr_seo_lich_xuat_ban['KEY_VALUE_NAME_TYPE'] = 'data_seo_lich_xuat_ban_theo_loai_';
$arr_seo_lich_xuat_ban['KEY_VALUE_NAME_EVENT'] = 'data_seo_lich_xuat_ban_theo_chu_de_id_';
$arr_seo_lich_xuat_ban['KEY_VALUE_NAME_CATEGORY'] = 'data_seo_lich_xuat_ban_theo_chuyen_muc_id_';
