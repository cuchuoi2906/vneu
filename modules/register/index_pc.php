<?php
$cat_id = ID_TRANG_CHU; // trang chu
$fwLayout = 'layout.php';	

$template = new vTemplate();
/* begin 6/2/2017 TuyenNT bo_sung_text_phan_biet_khu_vuc_quang_cao */
$template->set('region_id', $region_id);
$template->set('v_device_global', $v_device_global);
/* end 6/2/2017 TuyenNT bo_sung_text_phan_biet_khu_vuc_quang_cao */
$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_pc.php');