<?php
if( preg_match( '#-a([0-9]+).html#', $fwRequestUri, $v_result)){
    $news_id = intval($v_result[1]);
}

if (intval($news_id) == 0) {
	_redirect_to_404_page();
}

$row_news = fe_bai_viet_theo_id($news_id);
if (!check_array($row_news)) {
    _redirect_to_404_page();
}

$row_cat = fe_chuyen_muc_theo_id(intval($row_news['CategoryID']));
if (!check_array($row_cat)) {
    _redirect_to_404_page();
}
if(intval($row_news['Status']) != 1 && !isset($_GET['preview']) && intval($_GET['preview']) != 1 ){
    _redirect_to_404_page();
}
$row_seo_news = fe_seo_chi_tiet_bai_viet_theo_id($news_id);
$cat_id = intval($row_news['CategoryID']);

$rs_template = get_title_desc_keyword_canonical_bai_viet($cat_id, $news_id, $row_news, array(), $row_seo_news);

$v_arr_magazine_content = array();
if ($row_news['Type'] == 2) {
    if (preg_match("/\[\[@magazine_(\d+)\#\]\]/i", $row_news['Body'], $v_mz_id)) {
        $v_magazine_id = intval($v_mz_id[1]);
        $v_arr_magazine_content = fe_magazine_theo_id($v_magazine_id);
    }
}
$template = new vTemplate();
$template->set('cat_id', $cat_id);									
$template->set('news_id', $news_id);
$template->set('row_cat', $row_cat);
$template->set('row_news', $row_news);
$template->set('row_seo_news', $row_seo_news);
$template->set('rs_template', $rs_template);
$template->set('v_arr_magazine_content', $v_arr_magazine_content);
//Begin 26-05-2021 : ducnq xu_ly_target_new_football_interface
$v_is_new_football_interface = 0;

//End 26-05-2021 : ducnq xu_ly_target_new_football_interface
if (intval($row_news['Type']) == 2) {
    $fwLayout = 'layout_magazine.php';
    if(isset($_SERVER['is_mobile']) && $_SERVER['is_mobile']){
        $__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_magazine_mobile.php');
    }else{
        $__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_magazine_pc.php');
    }
} else {
    $fwLayout = 'layout.php';
	$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index_pc.php');
}