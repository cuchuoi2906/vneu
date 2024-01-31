<?php
$cat_id = ID_TRANG_CHU; // trang chu
$row_cat = fe_chuyen_muc_theo_id($cat_id);
$region_id = get_region_id();
$region_value = get_region_value($region_id);

$v_key_trang_chu = 'ipad_trang_chu_2018_092018-'.$region_value.'-https';
// neu link co cron_mode=1 thi khong dung key
if (!preg_match("#cron_mode=1#", $_SERVER['QUERY_STRING']) && _on_off_get_key_html_home_page()){
	$v_html_value  = Gnud_Db_read_get_key($v_key_trang_chu, _CACHE_TABLE_COMMON);
	if ($v_html_value!=""){
		$v_html_value  = _thay_the_url_theo_vung_mien($v_html_value);
        // thay thế link canonical
        $v_html_value = _thay_the_domain_us_link_canonical($v_html_value);
        
		echo $v_html_value.'<!--CACHE '.$v_key_trang_chu.'-->';
		die;
	}	
}
/* begin 18/5/2017 TuyenNT nang_cap_ipad_2017_ghep_trang_chu_ipad */
// bổ sung right column
// HTML box 4t1
$html_box_bai_pr_2015 = html_box_4t1_to_key($cat_id);
$_RIGHT_CONTENT = array(
    'banner:ADS_196_15s' // banner half1
    ,"string:".$html_box_bai_pr_2015 // box tin tức thị trường
    ,'object:box_danh_rieng_cho_phai_dep/index('.$cat_id.',1,4)' // box dành cho phái đẹp
);
/* end 18/5/2017 TuyenNT nang_cap_ipad_2017_ghep_trang_chu_ipad */
$home_index_box = fe_chuyen_muc_hien_thi_tren_trang_chu_ipad();
array_unique_key($home_index_box, 'box_number');

// Lay template (title, desc, key) cho trang
$rs_template = get_title_desc_keyword_canonical_chuyen_muc($cat_id);
$rs_template['canonical'] = BASE_URL_FOR_PUBLIC;
// begin 8/2/2018 Tytv bat_like_share_facebook
$rs_template = get_social_network_meta_chuyen_muc($rs_template, $cat_id);
// end 8/2/2018 Tytv bat_like_share_facebook

$template = new vTemplate();
$template->set('home_index_box', $home_index_box); 
$template->set('v_device_global', $v_device_global);
$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_ipad.php');

$key = 'trang-chu24h-'.$_SERVER['PR_REGION'];
$key = 'key_value@'.$key;
$__MASTER_CONTENT__ = add_comment_cache_key($__MASTER_CONTENT__, $key);