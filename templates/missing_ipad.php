<?php
include_once WEB_ROOT . 'includes/app_configs_common.php';
//Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
include_once WEB_ROOT . 'includes/app_common.php';
//chuoi tim kiem
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));

//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
$search = fw24h_replace_bad_char(index_xss_clean(strip_tags(urldecode($__REQUEST_URI__))));
//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest

$v_url = BASE_URL_FOR_PUBLIC;
$v_url_image = IMAGE_STATIC;
if(_is_domain_https()){
	$v_url = str_replace('http://', 'https://', $v_url);
	$v_url_image = str_replace('http://', 'https://', $v_url_image);
}
$v_url_image = _thay_the_url_theo_vung_mien($v_url_image);
// url sai
$v_wrong_url = $v_url.$search;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="//www.w3.org/1999/xhtml">
<head>
<?php header(' ', true, 404); ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Tin tuc | Web giai tri so1 | tin tuc trong ngay| bong da | thoi trang</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<link rel="stylesheet" href="/css/404_ipad.css" type="text/css">
    <?php /* Begin anhpt 27/12/2016 cau_hinh_on_off_ga */
    // lấy chế độ cross domain
    $v_script_cross_domain = get_script_theo_che_do_cross_domain();
    // lấy cấu hình on/off mã GA
    $v_on_off_ma_ga_rieng = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_rieng');
    $v_on_off_ma_ga_tong = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_tong');
    $v_ma_ga_tong       = _get_module_config('cau_hinh_dung_chung', 'v_ma_ga_tong');
    if($v_on_off_ma_ga_rieng || $v_on_off_ma_ga_tong || $v_script_cross_domain != ''){
    ?>
    <?php 
		//Begin 12-07-2017 : Thangnb xu_ly_load_jquery_tren_header
		$v_cau_hinh_su_dung_jquery = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','CAU_HINH_SU_DUNG_JQUERY');
		if ($v_cau_hinh_su_dung_jquery == 2) {
			echo '<script type="text/javascript" src="'.$v_url.'js/jquery.min.js?v='.JS_CSS_VERSION.'"></script>';
		} else if ($v_cau_hinh_su_dung_jquery == 3) {
			$html_jquery = '<script type="text/javascript">'._read_file(WEB_ROOT.'js/jquery.min.js').'</script>';
			echo $html_jquery;
		}
		//End 12-07-2017 : Thangnb xu_ly_load_jquery_tren_header
		// Lấy script manager được cấu hình
		$v_on_off_script_tag_manager = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_GOOGLE_TAG_MANAGER');
		if ($v_on_off_script_tag_manager === 'TRUE') {
		$v_arr_script_tag_manager = get_script_tag_manager_by_config();
		// Kiểm tra chuỗi script tag manager có được cấu hình
			//Begin 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
			$v_arr_script_tag_manager = replace_content_gtm_page_dimensions($v_arr_script_tag_manager, '404', '', $v_device_global, '');
			//End 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
			if ($v_arr_script_tag_manager['v_script_tren_header'] != '') {
			echo $v_arr_script_tag_manager['v_script_tren_header'];
			}
		}
		//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
		$v_on_off_tracking_ga_content = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_TRACKING_GA_CONTENT');
		if ($v_script_cross_domain != '') {
			if ($v_on_off_tracking_ga_content === 'TRUE') {
				$v_script_cross_domain = replace_tracking_ga_content($v_script_cross_domain, '404', '', $v_device_global, '');
			}
		}
		//End 09-11-2017 : Thangnb xu_ly_tracking_ga_content
	?>
	<!-- Begin GA code -->
	<script type="text/javascript">//<![CDATA[
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
				<?php // Kiểm tra cấu hình có được hiển thị mã GA tổng hay không 
				//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
                if($v_on_off_ma_ga_tong){
					$v_html_ga_tong = "ga('create', '$v_ma_ga_tong', 'auto');
					ga('require', 'linkid', 'linkid.js');
					ga('require', 'displayfeatures');
					/*TRACKING_GA_CONTENT*/
					ga('send', 'pageview'/*TRACKING_GA_CONTENT_DIMENSION*/);";
					if ($v_on_off_tracking_ga_content === 'TRUE') {
						$v_html_ga_tong = replace_tracking_ga_content($v_html_ga_tong, '404', '', $v_device_global, '');
					}
					echo $v_html_ga_tong;
                }
				//End 09-11-2017 : Thangnb xu_ly_tracking_ga_content
                // hiển thị script cross domain
                if($v_script_cross_domain != ''){
                    echo $v_script_cross_domain;
                }
                // Kiểm tra cấu hình có được hiển thị mã GA riêng hay không
                if($v_on_off_ma_ga_rieng){
                ?>		
					ga('create', 'UA-8670218-72', 'auto',{name: 'Tong24h_leader_moi'});
					ga('Tong24h_leader_moi.require', 'linkid', 'linkid.js');
					ga('Tong24h_leader_moi.require', 'displayfeatures');
					ga('Tong24h_leader_moi.send', 'pageview');
					
					ga('create', 'UA-6282504-21', 'auto',{name: 'Tong24h_ipad'});
					ga('Tong24h_ipad.require', 'linkid', 'linkid.js');
					ga('Tong24h_ipad.require', 'displayfeatures');
					ga('Tong24h_ipad.send', 'pageview');
					
					ga('create', 'UA-8670218-52', 'auto',{name: 'trangloi24h_Tong'});
					ga('trangloi24h_Tong.require', 'linkid', 'linkid.js');
					ga('trangloi24h_Tong.require', 'displayfeatures');
					ga('trangloi24h_Tong.send', 'pageview');
					
					ga('create', 'UA-6282504-50', 'auto',{name: 'trangloi24h_ipad'});
					ga('trangloi24h_ipad.require', 'linkid', 'linkid.js');
					ga('trangloi24h_ipad.require', 'displayfeatures');
					ga('trangloi24h_ipad.send', 'pageview');
                <?php } ?>
	//]]></script>
	<!-- End GA code -->
    <?php 
    } 
	?>
</head>
<!--[if lte IE 6]>
<style>
#link{
height:246px;
padding-top:38px;
}
#link a{
font-size:14px;
}
</style>
<![endif]-->
<body>
    <?php 
    if($v_arr_script_tag_manager['v_script_duoi_body'] != ''){
        echo $v_arr_script_tag_manager['v_script_duoi_body'];
    }
    ?>
	<section class="contnr brd24h">
    	<div class="brd24hIn">
            <header class="hdr24h">
                <div class="logo"><a href="<?php echo $v_url; ?>"><img src="<?php echo $v_url_image; ?>upload/html/logo.png" /></a></div>
                <div class="lgoTit"><a href="<?php echo $v_url; ?>">Website giải trí trực tuyến số 1 Việt Nam</a></div>
            </header>
            <div class="grnTit">&#272;&#431;&#7900;NG D&#7850;N B&#7840;N V&#7914;A G&#213; SAI: <span class="wrongLink"><?php echo $v_wrong_url; ?></span></div>
            <div class="blkTit"><img src="<?php echo $v_url_image; ?>upload/html/arrow_red.gif" align="asbmindle" border="0"/> M&#7901;i b&#7841;n b&#7845;m v&#224;o link b&#234;n c&#7841;nh &#273;&#7875; v&#224;o trang:</div>
            <?php 
                //Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
                $v_domain_viec_lam = _get_module_config('cau_hinh_dung_chung','v_domain_viec_lam');
            ?>
            <div class="lstPg">
                <div class="lstNumb">1.</div><a href="<?php echo $v_url; ?>">Trang chủ 24h</a>
                <div class="lstNumb">2.</div><a href="<?php echo $v_url; ?>tin-tuc-su-kien-c46.html">Tin tức sự kiện</a>
                <div class="lstNumb">3.</div><a href="<?php echo $v_url; ?>bong-da-c48.html">Bóng đá</a>
                <div class="lstNumb">4.</div><a href="<?php echo $v_url; ?>thoi-trang-c78.html">Thời trang</a>
                <div class="lstNumb">5.</div><a href="<?php echo $v_url; ?>cuoi-24h-c746.html">Cười</a>
                <div class="lstNumb">6.</div><a href="<?php echo $v_url; ?>">24h.com.vn</a>
            </div>
            <?php /* Begin anhpt1 19/11/2015 thay_doi_link_trang_404 */ ?>
            <div class="lstPg">
                <div class="lstNumb">7.</div><a href="<?php echo $v_url; ?>tin-tuc-quoc-te-c415.html">Thế giới</a>
                <div class="lstNumb">8.</div><a href="<?php echo $v_url; ?>an-ninh-hinh-su-c51.html">Pháp luật</a>
                <div class="lstNumb">9.</div><a href="<?php echo $v_url; ?>ban-tre-cuoc-song-c64.html">Bạn trẻ - Cuộc sống</a>
                <div class="lstNumb">10.</div><a href="<?php echo $v_url; ?>tai-chinh-bat-dong-san-c161.html">Tài chính - Bất động sản</a>
                <div class="lstNumb">11.</div><a href="http://ketquaxoso.24h.com.vn/">Xổ số</a>
                <div class="lstNumb">12.</div><a href="<?php echo $v_url; ?>suc-khoe-doi-song-c62.html">Sức khỏe đời sống</a>
            </div>  
        </div>
        <?php /* End anhpt1 19/11/2015 thay_doi_link_trang_404 */ ?>
		 <p class="orOther">Ho&#7863;c tham kh&#7843;o c&#225;c trang t&#432;&#417;ng t&#7921; d&#432;&#7899;i &#273;&#226;y:</p> 
			<div id="cse-search-results"></div>
			<script type="text/javascript">
				var googleSearchIframeName = "cse-search-results";
				var googleSearchFormName = "cse-search-box";
				var googleSearchFrameWidth = 600;
				var googleSearchDomain = "google.com";
				var googleSearchPath = "/cse";
			</script>
			<script type="text/javascript" src="//google.com/afsonline/show_afs_search.js"></script>
			<iframe height='525' width='100%' frameborder='0' scrolling='yes' src="//google.com/cse?cx=005327017215456024553:nbasfeisuym&cof=FORID%3A10&ie=UTF-8&q=<?php echo $search; ?>&sa=Ti%CC%80m+ki%C3%AA%CC%81m&ad=w9&num=10&rurl=file%3A%2F%2F%2FD%3A%2Fprojects%2Fweb24h02%2Fdoc%2F404%2Fweb%2520loi%2Findex.html%3Fcx%3D005327017215456024553%253Anbasfeisuym%26cof%3DFORID%253A10%26ie%3DUTF-8%26q%3Ddungpt%26sa%3DTi%25CC%2580m%2Bki%25C3%25AA%25CC%2581m"></iframe>		
        </div>
        <div style="clear:both"></div>
	</section>
</body>
</html>
<?php
if (defined('GNUD_DEBUG_MODE') && GNUD_DEBUG_MODE==true) {
    global $fwRequestUri;
    $missingMsg = date('Y-m-d H:i:s') . "; Err_File: $errfile; RequestUri:".$fwRequestUri."; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
    error_log($missingMsg, 3, WEB_ROOT . '/logs/missing.log');
}

function index_xss_clean($data){
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do {
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);
    $v_arr_item = explode('/', $data);
    if(is_array($v_arr_item)){
        foreach($v_arr_item AS $key_item => $value_item){
            if(preg_match('/<script/', base64_decode($value_item))){
                $v_arr_item[$key_item] = ' ';
            }
        }
        $data  = implode('/', $v_arr_item);
    }

	// we are done...
	return $data;
}