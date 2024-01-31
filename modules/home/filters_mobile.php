<?php
// Begin Lucnd 20-04-2017: DFP_24h_xu_ly_quang_cao_dfp
$v_header = key_get_contents_header_footer("header_092018-$cat_id-$region_id-".NAME_THIET_BI_MOBILE, _CACHE_TABLE_COMMON, $rs_template);
// End Lucnd 20-04-2017: DFP_24h_xu_ly_quang_cao_dfp
// Nếu là trên con test thì chạy trực tiếp block header - ko lấy qua key
if (_is_test_domain()){
	$v_header = '';
}
// Begin : 29-09-2015 : anhnt1 toi_uu_page_speed
// Kiem tra xem co lay header qua block hay khong
if (_check_if_get_header_by_block()){
	$v_header = '';
}	
// End : 29-09-2015 : anhnt1 toi_uu_page_speed

if ($v_header == '') {  // tao noi dung tu block
	$v_object = new header_block();
	$v_object->autoRender = false;
    $v_object->setParam('v_device_global', $v_device_global);
	$v_object->index($cat_id, $region_id);
	$v_header = $v_object->blockContent;
	$v_header = replace_template_header_footer($v_header,$rs_template, $cat_id);/* Tytv 26/09/2016 on_off_text_link_logo_header_footer */
    $v_header = replace_domain_static_images($v_header);
}
$start_time = microtime(true);
$start_mem = memory_get_usage();

//Begin : 29-09-2015 : Thangnb toi_uu_page_speed
$v_header = html_load_header_css($v_header, CHEN_NOI_DUNG_CSS_VAO_HTML, MINIFY_JS_CSS,'home_092018', '', $v_device_global);
// Chèn thêm css 24h player
if (PLAYER_VIDEO == '24H_PLAYER') {
    $v_css_video = 	'<style>'._read_file(WEB_ROOT.'css/24hplayer.min.css').'</style>';
    $v_header = str_replace('</head>',$v_css_video.'</head>',$v_header);
}
log_thoi_gian_thuc_thi($start_time,$start_mem,'',WEB_ROOT.'logs/log_pagespeed.log');
//End : 29-09-2015 : Thangnb toi_uu_page_speed
//27052015 Thangnb metagooglebot
//$v_header = str_replace('<!--meta_googlebot-->',META_GOOGLEBOT, $v_header);
$v_header = replace_text_link_logo($v_header, $cat_id);
//Begin 12-06-2017 : Thangnb xoa_bo_code_ants_thua
// begin 04/11/2015 Ducnq - chinh_sua_title_logo
$v_header = str_replace('<!--@title_trang_chu@-->', $rs_template_tc['title'], $v_header);
// end 04/11/2015 Ducnq - chinh_sua_title_logo

//Begin 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
$v_on_off_script_tag_manager = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_GOOGLE_TAG_MANAGER');
if ($v_on_off_script_tag_manager === 'TRUE') {
	$v_header = replace_content_gtm_page_dimensions($v_header, 'home', '', $v_device_global, $cat_id);
}
//End 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
// xử lý thay thế cross domain
$v_script_cross_domain = get_script_theo_che_do_cross_domain();
if($v_script_cross_domain != ''){
	$v_header = str_replace('/*cross_domain_ga*/', $v_script_cross_domain, $v_header);
}
$v_on_off_tracking_ga_content = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_TRACKING_GA_CONTENT');
if ($v_on_off_tracking_ga_content === 'TRUE') {
	$v_header = replace_tracking_ga_content($v_header, 'home', '', $v_device_global, $cat_id);
}
//End 09-11-2017 : Thangnb xu_ly_tracking_ga_content
//Begin 11-10-2017 : Thangnb bo_sung_meta_tag_amp
$v_header = add_meta_tag_amphtml_header($v_header, $rs_template);
//End 11-10-2017 : Thangnb bo_sung_meta_tag_amp
gnud_add_request_services_tier('header', '/ajax/header/index/'.$cat_id.'/'.$region_id.'/'.NAME_THIET_BI_MOBILE.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_header), 'filter_content');
$v_footer = key_get_contents_header_footer("footer_092018-$cat_id-".NAME_THIET_BI_MOBILE, _CACHE_TABLE_COMMON, $rs_template);
if (_is_test_domain()){
	$v_footer = '';
}
if ($v_footer == '') {  // tao noi dung tu block
    $v_object = new footer_block();
    $v_object->autoRender = false;
    $v_object->setParam('v_device_global', $v_device_global);
    $v_object->index($cat_id);
    $v_footer = $v_object->blockContent;
    $v_footer = replace_domain_static_images($v_footer);
}

$v_footer = replace_template_header_footer($v_footer,$rs_template, $cat_id);
// Load js xuống dưới footer
$v_footer = html_load_header_js($v_footer, CHEN_NOI_DUNG_JS_VAO_HTML, MINIFY_JS_CSS,'','',$v_device_global);
$v_footer = _chen_js_quang_cao($v_footer,$cat_id, $region_id);

// Chèn js theo loại player
$v_footer = html_load_video_player_js($v_footer);
/*Begin 27-09-2018 trungcq XLCYCMHENG_32819_bo_sung_script_footer*/
$v_footer_script = lay_script_hien_thi_duoi_footer($cat_id, $region_id, 4);
if($v_footer_script!=''){
    $v_footer = str_replace('<!--@@SCRIPT_FOOTER@@-->', $v_footer_script, $v_footer);
}
/*End 27-09-2018 trungcq XLCYCMHENG_32819_bo_sung_script_footer*/
gnud_add_request_services_tier('footer', '/ajax/footer/index/'.$cat_id.'/'.NAME_THIET_BI_MOBILE.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_footer), 'filter_content');
?>