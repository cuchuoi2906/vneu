<!DOCTYPE html>
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset = "UTF-8">
<meta http-equiv="Content-Language" content="vi"/>
<link rel="icon" href="/favicon.gif" type="image/gif" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-startup-image" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>" />
<link rel="apple-touch-icon" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>"/>
<link rel="apple-touch-icon-precomposed" sizes="160x90" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>" />
<meta name="mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" sizes="160x90" href="<?php html_image('images/m2014/images/logo-24h_bookmarks.png'); ?>" />
<!--@title@-->
<!--@description@-->
<!--@keywords@-->
<!--@social_network_meta@-->
<!--@canonical@-->
<!--script_breadcrum-->
<!--@robots_index@-->
<!--Snippets Video Google-->
<!--meta_googlebot-->		   
<!--@css@-->
<meta http-equiv="cleartype" content="on" />
<meta name="MobileOptimized" content="width" />
<meta name="HandheldFriendly" content="true" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="copyright" content="Copyright © 2013 by 24H.COM.VN" />
<meta name="abstract" content="24H.COM.VN Website tin tức số 1 Việt Nam" />
<meta name="distribution" content="Global" />
<?php 
//Begin 04-10-2017 : Thangnb xu_ly_dns_prefetch
$v_on_off_dns_prefetch = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN', 'ON_OFF_DNS_PREFETCH');
if ($v_on_off_dns_prefetch == 'TRUE') {
	echo html_dns_prefetch();
}
$v_arr_script_tag_manager = get_script_tag_manager_by_config();
// Kiểm tra chuỗi script tag manager có được cấu hình
if($v_arr_script_tag_manager['v_script_tren_header'] != ''){
    echo $v_arr_script_tag_manager['v_script_tren_header'];
} 
//End 25-07-2017 : Thangnb chuyen_vi_tri_dat_script_gtm_len_cao_nhat_header
?>
<?php 
/*Begin 24-05-2017 trungcq XLCYCMHENG_21890_bo_sung_schema_organization_schema_newsArticle*/
$v_organization_schema_header = _get_module_config('cau_hinh_dung_chung', 'v_organization_schema_header');
if($v_organization_schema_header!=''){
    echo $v_organization_schema_header;
}
/*End 24-05-2017 trungcq XLCYCMHENG_21890_bo_sung_schema_organization_schema_newsArticle*/
?>
<?php /*Begin 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<meta name="author" content="Tin Tức 24h" />
<?php /*End 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<?php // End 07/06/2017 Tytv bo_schema_newsArticle_mobile ?>
<meta http-equiv="refresh" content="1200" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<meta name="RATING" content="GENERAL" />
<?php /* begin 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<!--meta_ants-->
<?php /* end 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<?php 
//Begin : 29-09-2015 : Thangnb toi_uu_page_speed
?>
<!--@css@-->
<!--@css_quizz3_news@-->
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@css_magazine@-->
<!--@js_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<?php 
//End : 29-09-2015 : Thangnb toi_uu_page_speed
// begin 06/05/2016 TuyenNT bo_sung_truong_chinh_sua_cac_the_bai_viet_video
?>
<!--@meta_data_add_06_05_2015@-->
<?php
// end 06/05/2016 TuyenNT bo_sung_truong_chinh_sua_cac_the_bai_viet_video
//Begin 03-01-2019 : Trungcq XLCYCMHENG_33758_xu_ly_prebid_criteo_chuyen_muc_layout
?>
<!--@@start_code_prebid-->
<?php
if (check_array($row_header_script[1])) {
	foreach ($row_header_script[1] as $v_script) {
		echo $v_script['c_noi_dung']."\n";
	}
}
?>
<!--@@end_code_prebid-->
<?php
echo $v_html_cau_hinh_thu_tu_hien_thi_quang_cao_video; 
?>
<script type="text/javascript">
    window.addEventListener('load', function(){
        _setStorageJson24h('pageCookie', ++pageCookie, 720);
    });
</script>
<!--@@start_code_criteo-->
<?php
/* begin 8/5/2017 TuyenNT toi_uu_script_key_value_cho_criteo_ban_mobile */
$v_arr_cm_oto = _get_module_config('cau_hinh_dung_chung', 'v_arr_cm_oto');
$v_arr_cm_khong_hien_thi_criteo = _get_module_config('cau_hinh_dung_chung', 'v_arr_cm_khong_hien_thi_criteo');
// riêng chuyên mục oto ko hiện thị code criteo(CM oto có code criteo riêng)
if(!in_array($cat_id,$v_arr_cm_oto) && !in_array($cat_id,$v_arr_cm_khong_hien_thi_criteo)){
    echo $v_code_script_criteo;
}
/* end 8/5/2017 TuyenNT toi_uu_script_key_value_cho_criteo_ban_mobile */
?>
<!--@@end_code_criteo-->
<?php
//End 03-01-2019 : Trungcq XLCYCMHENG_33758_xu_ly_prebid_criteo_chuyen_muc_layout
// Begin lucnd 22-02-2017: DFP_24h_xu_ly_quang_cao_dfp
if ($v_script_slot_dfp != '') {
    if (strtolower($v_region_value) == 'us') {
        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp_us());
    } else {
        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp());
    }
}
// End lucnd 22-02-2017: DFP_24h_xu_ly_quang_cao_dfp
if (check_array($row_header_script[2])) {
	foreach ($row_header_script[2] as $v_script) {
		echo $v_script['c_noi_dung']."\n";
	}
}
?>

<script type='text/javascript'>
    //setParamGaContentVideoNews
	v_cat_id = <?php echo $row_cat['ID']; ?>;
</script>

<!-- Begin comScore Tag -->
<script type="text/javascript">
//<![CDATA[
	  var _comscore = _comscore || [];
	  _comscore.push({ c1: "2", c2: "9634358" });
	  (function() {
		var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
		s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
		el.parentNode.insertBefore(s, el);
	  })();
//]]>
</script>
<!-- End comScore Tag -->
<?php /* Begin anhpt 27/12/2016 cau_hinh_on_off_ga */
// lấy chế độ cross domain
$v_cross_domain = is_xu_ly_cross_domain_ga();
// lấy cấu hình on/off mã GA
$v_on_off_ma_ga_rieng = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_rieng');
$v_on_off_ma_ga_tong = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_tong');
$v_ma_ga_tong       = _get_module_config('cau_hinh_dung_chung', 'v_ma_ga_tong');
if($v_on_off_ma_ga_rieng || $v_on_off_ma_ga_tong || $v_cross_domain){
?>
<!-- Begin GA code -->
<script type="text/javascript">//<![CDATA[
	<?php     
        $v_link = _get_link_analytics_google();
		echo "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','".$v_link."','ga');";
        // Kiểm tra cấu hình có được hiển thị mã GA tổng hay không
		//Begin 12-10-2017 : Thangnb bo_sung_ga_thong_ke_99_amp
        if($v_on_off_ma_ga_tong){	
            echo "ga('create', '".$v_ma_ga_tong."', 'auto', {'useAmpClientId': true});
            ga('require', 'linkid', 'linkid.js');
            ga('require', 'displayfeatures');
			/*TRACKING_GA_CONTENT*/
            ga('send', 'pageview'/*TRACKING_GA_CONTENT_DIMENSION*/);
            ";
        }
		//End 12-10-2017 : Thangnb bo_sung_ga_thong_ke_99_amp
        // hiển thị script cross domain
        if($v_cross_domain){ ?>
            /*cross_domain_ga*/
        <?php }
        // Kiểm tra cấu hình có được hiển thị mã GA riêng hay không
        if($v_on_off_ma_ga_rieng){   
            # thêm các mã gắn cố định
            $v_ds_ga = 'UA-8670218-72(Tong24h_leader_moi),UA-8670218-57(Tong24h_mobile_cu),UA-57444020-1(Tong24h_mobile_moi),'.$v_ds_ga;

            $arr_gacode = explode(',', trim($v_ds_ga));
            $arr_gacode_da_hien_thi = array('UA-2286909-2');
            echo html_ga($arr_gacode, $arr_gacode_da_hien_thi);
        }
	?>
//]]></script>
<!-- End GA code -->
<?php 
} 
/* Begin anhpt1 07/07/2016 fix_loi_bao_mat_clickjaking_front_end */ 
header('X-Frame-Options: DENY'); 
/* End anhpt1 07/07/2016 fix_loi_bao_mat_clickjaking_front_end */ ?>
</head>
<body class="cat-<?php echo $row_cat['ID']; ?>">
<!--@@SCRIPT_GTM_BODY@@-->
	<?php
	if (check_array($row_seo_cat) && $row_seo_cat['c_script_marketing'] != '') {
		echo $row_seo_cat['c_script_marketing'];
	}
    echo html_header_trang_chu($rs_header, $row_seo_cat, $row_cat, $rs_anh_nen_logo_menu_ngang_header);
?>