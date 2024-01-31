<?php
$cat_id = ID_TRANG_CHU; // trang chu
$fwLayout = 'layout.php';	
$region_id = get_region_id();
// anhnt1 31/10/2014: bo sung dung key-html
$region_value = get_region_value($region_id);
$v_is_smart_phone = _get_mobile_type();
$v_key_trang_chu = 'mobile_trang_chu_092018-'.$region_value.'-'.$v_is_smart_phone.'-https';
// neu link co cron_mode=1 thi khong dung key
if (!preg_match("#cron_mode=1#", $_SERVER['QUERY_STRING']) && _on_off_get_key_html_home_page() && !_is_test_domain()){
	$v_html_value  = Gnud_Db_read_get_key($v_key_trang_chu, _CACHE_TABLE_COMMON);
	if ($v_html_value!=""){	
		$v_html_value  = _thay_the_url_theo_vung_mien($v_html_value);
		
        // thay thế link canonical
        $v_html_value = _thay_the_domain_us_link_canonical($v_html_value);
        
		echo $v_html_value.'<!--CACHE '.$v_key_trang_chu.'-->';
		die;
	}	
}		
if ($v_html_value == ''){
	$rs_template = get_title_desc_keyword_canonical_chuyen_muc($cat_id);
	$rs_template = get_social_network_meta_chuyen_muc($rs_template, $cat_id);
    
    $rs_template_tc = $rs_template;
	$template = new vTemplate();
    $template->set('v_device_global', $v_device_global);
    $template->set('cat_id', $cat_id);
	$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_mobile.php');
}
