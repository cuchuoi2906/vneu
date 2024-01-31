<?php
$v_header = key_get_contents_header_footer("header_092018-$cat_id-$region_id-".NAME_THIET_BI_TABLET, _CACHE_TABLE_COMMON, $rs_template);
if (_is_test_domain()){
	$v_header = '';
}
// Kiem tra xem co lay header qua block hay khong
if (_check_if_get_header_by_block()){ 
	$v_header = ''; 
}
if ($v_header == '') {  // tao noi dung tu block
	$v_object = new header_block();
	$v_object->autoRender = false;
    $v_object->setParam('v_device_global', $v_device_global);
	$v_object->index($cat_id, $region_id);
	$v_header = $v_object->blockContent;
	$v_header = replace_template_header_footer($v_header,$rs_template);
    $v_header = replace_domain_static_images($v_header);
}
// Load css vào header
$v_header = html_load_header_css($v_header, CHEN_NOI_DUNG_CSS_VAO_HTML, MINIFY_JS_CSS,'home','',$v_device_global, 'common');

$v_header = replace_text_link_logo($cat_id, $v_header);
//06-07-2015 : Thangnb xu ly meta_ants
$row_cat = fe_chuyen_muc_theo_id($cat_id);
//Begin 12-06-2017 : Thangnb xoa_bo_code_ants_thua
// begin 04/11/2015 Ducnq - chinh_sua_title_logo
$v_header = str_replace('<!--@title_trang_chu@-->', $rs_template['title'], $v_header);
// end 04/11/2015 Ducnq - chinh_sua_title_logo
// begin 8/2/2018 Tytv bat_like_share_facebook
$v_header = str_replace('<!--@social_network_meta@-->', $rs_template['social_network_meta'], $v_header); 
// end 8/2/2018 Tytv bat_like_share_facebook

/* Begin: Tytv - 13/4/2017 toi_uu_seo_ban_ipad_24h */
$v_header = str_replace('itemscope itemtype="http://schema.org/NewsArticle"', '', $v_header);
/* End: Tytv - 13/4/2017 toi_uu_seo_ban_ipad_24h */
//Begin 23-05-2016 : Thangnb dinh_dang_link_rss_cho_cac_trang
$v_arr_dinh_danh_rss = _get_module_config('cau_hinh_dung_chung','v_arr_dinh_danh_rss');
if ($v_arr_dinh_danh_rss[$cat_id] != '') {
	$v_header = str_replace('<!--html_rss_feed-->','<link rel="alternate" href="'.$v_arr_dinh_danh_rss[$cat_id].'" type="application/atom+xml" title="'.$row_cat['Name'].'" />',$v_header);
}
//End 23-05-2016 : Thangnb dinh_dang_link_rss_cho_cac_trang

//Begin 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
$v_on_off_script_tag_manager = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_GOOGLE_TAG_MANAGER');
if ($v_on_off_script_tag_manager === 'TRUE') {
	$v_header = replace_content_gtm_page_dimensions($v_header, 'home', '', $v_device_global, $cat_id);
}
//End 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
// x? lý thay th? cross domain
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
gnud_add_request_services_tier('header', '/ajax/header/index/'.$cat_id.'/'.$region_id.'/'.NAME_THIET_BI_TABLET.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_header), 'filter_content');

$v_footer = key_get_contents_header_footer("footer_092018-$cat_id-".NAME_THIET_BI_TABLET, _CACHE_TABLE_COMMON, $rs_template);
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
$v_footer = _chen_js_quang_cao($v_footer,$cat_id, $region_id);
$v_footer = html_load_header_js($v_footer, CHEN_NOI_DUNG_JS_VAO_HTML, MINIFY_JS_CSS,'','',$v_device_global);
/*Begin 27-09-2018 trungcq XLCYCMHENG_32819_bo_sung_script_footer*/
$v_footer_script = lay_script_hien_thi_duoi_footer($cat_id, $region_id, 2);
if($v_footer_script!=''){
    $v_footer = str_replace('<!--@@SCRIPT_FOOTER@@-->', $v_footer_script, $v_footer);
}
/*End 27-09-2018 trungcq XLCYCMHENG_32819_bo_sung_script_footer*/
gnud_add_request_services_tier('footer', '/ajax/footer/index/1/'.NAME_THIET_BI_TABLET.'/0/0', 'gnud_replace_with_services_tier_content', 'function', array('data'=>$v_footer), 'filter_content');

?>