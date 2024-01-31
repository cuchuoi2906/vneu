<?php
$fwLayout = 'layout.php';	
if( preg_match( '#-c([0-9]+).html#', $fwRequestUri, $v_result)){
    $cat_id = intval($v_result[1]);
}
if (intval($cat_id) == 0) {
	_redirect_to_404_page();
}

$row_cat = fe_chuyen_muc_theo_id(intval($cat_id));
if (!check_array($row_cat)) {
    _redirect_to_404_page();
}
$urlHelper = new UrlHelper();$urlHelper->getInstance();
$v_url_cat = substr($urlHelper->url_cate(array("ID"=>$row_cat['ID'],"slug"=>get_category_slug($row_cat))),1);

$rs_template['title'] = _fix_ki_tu_dac_biet_title_desc($row_cat['Name']);
$rs_template['desc'] = _fix_ki_tu_dac_biet_title_desc($row_cat['Description']);
$rs_template['keyword'] = _fix_ki_tu_dac_biet_title_desc($row_cat['Keyword']);
$rs_template['canonical'] = BASE_URL_FOR_PUBLIC.$v_url_cat;


$template = new vTemplate();
$template->set('cat_id', $cat_id);
$template->set('region_id', $region_id);
$template->set('v_device_global', $v_device_global);
$template->set('row_cat', $row_cat);
$template->set('rs_template', $rs_template);
$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_pc.php');