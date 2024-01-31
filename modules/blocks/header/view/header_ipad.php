<!DOCTYPE html>
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset = "UTF-8">
<meta http-equiv="content-language" itemprop="inLanguage" content="vi"/>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<link rel="icon" href="/favicon.gif" type="image/gif" />
<!--@title@-->
<!--@description@-->
<!--@keywords@-->
<!--@canonical@-->
<!--script_breadcrum-->
<!--@robots_index@-->
<!--@property_social@-->
<!--meta_googlebot-->
<!--Schema_video-->				   
<?php // begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh ?>
<!--@og_title_mxh@-->
<!--@og_des_mxh@-->
<!--Snippets Video Google-->
<?php // end 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh ?>
<?php // begin 24/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h ?>
<?php /*Begin 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<meta name="author" content="Tin Tức 24h" />
<?php /*End 23-11-2017 trungcq XLCYCMHENG_27919_bo_sung_author_lang_header*/?>
<!--@social_network_meta@-->
<?php // end 24/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h ?>
<?php /* Begin 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<!--@meta_alternate@-->
<?php /* End 10-6-2019 TuyenNT bo_sung_1_so_thong_tin_ho_tro_seo */ ?>
<meta name="generator" content="HTML Tidy for Windows (vers 14 February 2006), see www.w3.org" /> 
<meta http-equiv="refresh" content="1200" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<meta name="RATING" content="GENERAL" />
<?php 
//Begin 04-10-2017 : Thangnb xu_ly_dns_prefetch
$v_on_off_dns_prefetch = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN', 'ON_OFF_DNS_PREFETCH');
if ($v_on_off_dns_prefetch == 'TRUE') {
	echo html_dns_prefetch();
}
//End 04-10-2017 : Thangnb xu_ly_dns_prefetch
?>
<?php
//Begin 25-07-2017 : Thangnb chuyen_vi_tri_dat_script_gtm_len_cao_nhat_header
// Lấy script manager được cấu hình
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
<?php /* begin 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<!--meta_ants-->
<?php /* end 4/1/2016 TuyenNT bo_sung_code_du_lieu_24h_audience_insight_trang_oto_xe_may chuyen the meta ants xuong duoi */ ?>
<?php 
//Begin : 29-09-2015 : Thangnb toi_uu_page_speed
/* Begin Tytv - 03/03/2017 : xu_ly_loi_quizz_3_bai_quizz_hien_thi_loi_lan_dau_xem */
?>
<!--@css@-->
<!--@css_quizz3_news@-->
<!--@css_twentytwenty@-->
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@css_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<?php // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<!--@js_magazine@-->
<?php // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine ?>
<script type="text/javascript">
    window.addEventListener('load', function(){
        _setStorageJson24h('pageCookie', ++pageCookie, 720);
    });
</script>
<?php 
/* End Tytv - 03/03/2017 : xu_ly_loi_quizz_3_bai_quizz_hien_thi_loi_lan_dau_xem */
//30-06-2016 : Thangnb upload_anh_so_sanh ?>
<?php 
//End : 29-09-2015 : Thangnb toi_uu_page_speed
// begin 06/05/2016 TuyenNT bo_sung_truong_chinh_sua_cac_the_bai_viet_video ?>
<!--@meta_data_add_06_05_2015@-->    
<?php
if (check_array($row_header_script[1])) {
	foreach ($row_header_script[1] as $v_script) {
		echo $v_script['c_noi_dung']."\n";
	}
}
//Begin 23-05-2016 : Thangnb dinh_dang_link_rss_cho_cac_trang

/* begin 8/5/2017 TuyenNT toi_uu_script_key_value_cho_criteo_ban_ipad */
$v_arr_cm_oto = _get_module_config('cau_hinh_dung_chung', 'v_arr_cm_oto');
// riêng chuyên mục oto ko hiện thị code criteo(CM oto có code criteo riêng)
if(!in_array($cat_id,$v_arr_cm_oto)){
    //echo $v_code_script_criteo;
}
/* end 8/5/2017 TuyenNT toi_uu_script_key_value_cho_criteo_ban_ipad */
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
    
<!-- //Begin Lucnd: snowplow_ads_tracker Xử lý Ads tracker -->
<script type='text/javascript'>
    //setParamGaContentVideoNews
	v_cat_id = <?php echo $row_cat['ID']; ?>;
    adsData     = [];
</script>
<!-- //End Lucnd: snowplow_ads_tracker Xử lý Ads tracker -->
<script type='text/javascript'>
var _comscore = _comscore || [];
_comscore.push({ c1: "2", c2: "9634358" });
(function() {
var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
el.parentNode.insertBefore(s, el);
})();
</script>
<!-- End comScore Tag -->
<?php 
/* Begin anhpt 27/12/2016 cau_hinh_on_off_ga */
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
        if($v_on_off_ma_ga_tong){	  
		//Begin 12-10-2017 : Thangnb bo_sung_ga_thong_ke_99_amp
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
            # th�m c�c m? g?n c? �?nh
            $v_ds_ga = 'UA-8670218-72(Tong24h_leader_moi),UA-6282504-21(Tong24h_ipad),'.$v_ds_ga;		

            $arr_gacode = explode(',', trim($v_ds_ga));
            $arr_gacode_da_hien_thi = array('UA-2286909-2');
            echo html_ga($arr_gacode, $arr_gacode_da_hien_thi);
        }
	?>
//]]></script>
<!-- End GA code -->
<?php 
} 

?>
<!--html_rss_feed-->
</head>
<body >
<!--@@SCRIPT_GTM_BODY@@-->
<?php
if (check_array($row_seo_cat) && $row_seo_cat['c_script_marketing'] != '') {
	echo $row_seo_cat['c_script_marketing'];
}
echo html_header_ipad($rs_header,$row_seo_cat,$cat_id, $rs_anh_nen_logo_menu_ngang_header);