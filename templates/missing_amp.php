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
<!doctype html>
<html>
<head>
<?php header(' ', true, 404); ?>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" name="viewport">
<title>Tin tuc | Web giai tri so1 | tin tuc trong ngay| bong da | thoi trang</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<link rel="stylesheet" href="/css/amp_404.css" type="text/css">
<?php 
	//Begin 25-07-2017 : Thangnb chuyen_vi_tri_dat_script_gtm_len_cao_nhat_header
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
	//End 25-07-2017 : Thangnb chuyen_vi_tri_dat_script_gtm_len_cao_nhat_header
?>
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
    $v_script_cross_domain = get_script_theo_che_do_cross_domain();
    // lấy cấu hình on/off mã GA
    $v_on_off_ma_ga_rieng = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_rieng');
    $v_on_off_ma_ga_tong = _get_module_config('cau_hinh_dung_chung', 'v_on_off_ma_ga_tong');
    $v_ma_ga_tong       = _get_module_config('cau_hinh_dung_chung', 'v_ma_ga_tong');
    if($v_on_off_ma_ga_rieng || $v_on_off_ma_ga_tong || $v_script_cross_domain != ''){
	//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
	if ($v_script_cross_domain != '') {
		$v_on_off_tracking_ga_content = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_TRACKING_GA_CONTENT');
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
					
					ga('create', 'UA-8670218-57', 'auto',{name: 'Tong24h_mobile_cu'});
					ga('Tong24h_mobile_cu.require', 'linkid', 'linkid.js');
					ga('Tong24h_mobile_cu.require', 'displayfeatures');
					ga('Tong24h_mobile_cu.send', 'pageview');
					
					ga('create', 'UA-57444020-1', 'auto',{name: 'Tong24h_mobile_moi'});
					ga('Tong24h_mobile_moi.require', 'linkid', 'linkid.js');
					ga('Tong24h_mobile_moi.require', 'displayfeatures');
					ga('Tong24h_mobile_moi.send', 'pageview');
					
					ga('create', 'UA-8670218-52', 'auto',{name: 'trangloi24h_Tong'});
					ga('trangloi24h_Tong.require', 'linkid', 'linkid.js');
					ga('trangloi24h_Tong.require', 'displayfeatures');
					ga('trangloi24h_Tong.send', 'pageview');
					
					ga('create', 'UA-57444020-23', 'auto',{name: 'trangloi24h_mobile'});
					ga('trangloi24h_mobile.require', 'linkid', 'linkid.js');
					ga('trangloi24h_mobile.require', 'displayfeatures');
					ga('trangloi24h_mobile.send', 'pageview');
                <?php } ?>
	//]]></script>
	<!-- End GA code -->
    <?php 
    } 
	?>
</head>
<body>
    <?php 
    if($v_arr_script_tag_manager['v_script_duoi_body'] != ''){
        echo $v_arr_script_tag_manager['v_script_duoi_body'];
    }
    ?>
    <div class="blk">
        <div class="txtCent pdT10"><a href="/" title=""><img src="<?php echo $v_url_image; ?>images/m2014/images/logo24h-16090.gif" width="160" height="90" alt=""></a></div>
        <div class="txtCent pdT10 pdB20 lnkBlw"><b>ĐƯỜNG DẪN BẠN VỪA GÕ SAI:</b><BR/><a href="#" title="" class="link-green lnkGrn" ><?php echo $v_wrong_url; ?></a></div>
        <div class="txtCent pdB10 lnkBlw"><b>Mời bạn bấm vào link dưới để vào trang</b></div>
        <section class="blk">
            <nav class="cateOthr">
                <ul class="cmOthr">
                    <li><a href="/trang-chu-24gio-c45.html" id="bottom_45" target="_self" title="Trang chủ 24giờ">Trang chủ 24giờ</a></li>
                    <li><a href="/tin-tuc-trong-ngay-c46.html" id="bottom_46" target="_self" title="Tin tức trong ngày">Tin tức trong ngày</a></li>
                    <li><a href="/bong-da-c48.html" id="bottom_48" target="_self" title="Bóng đá">Bóng đá</a></li>
                    <li><a href="/thoi-trang-c78.html" id="bottom_78" target="_self" title="Thời trang">Thời trang</a></li>
                    <li><a href="/an-ninh-hinh-su-c51.html" id="bottom_51" target="_self" title="Pháp luật">Pháp luật</a></li>
                    <li><a href="/thoi-trang-hi-tech-c407.html" id="bottom_407" target="_self" title="Thời trang Hi-tech">Thời trang Hi-tech</a></li>
                    <li><a href="/tai-chinh-bat-dong-san-c161.html" id="bottom_161" target="_self" title="Tài chính - Bất động sản">Tài chính - Bất động sản</a></li>
                    <li><a href="/am-thuc-c460.html" id="bottom_460" target="_self" title="Ẩm thực">Ẩm thực</a></li>
                    <li><a href="/lam-dep-c145.html" id="bottom_145" target="_self" title="Làm đẹp">Làm đẹp</a></li>
                    <li><a href="/phim-c74.html" id="bottom_74" target="_self" title="Phim">Phim</a></li>
                    <li><a href="/giao-duc-du-hoc-c216.html" id="bottom_216" target="_self" title="Giáo dục - du học">Giáo dục - du học</a></li>
                    <li><a href="/ban-tre-cuoc-song-c64.html" id="bottom_64" target="_self" title="Bạn trẻ - Cuộc sống">Bạn trẻ - Cuộc sống</a></li>
                    <li><a href="/ca-nhac-mtv-c73.html" id="bottom_73" target="_self" title="Ca nhạc - MTV">Ca nhạc - MTV</a></li>
                    <li><a href="/the-thao-c101.html" id="bottom_101" target="_self" title="Thể thao">Thể thao</a></li>
                    <li><a href="/phi-thuong-ky-quac-c159.html" id="bottom_159" target="_self" title="Phi thường - kỳ quặc">Phi thường - kỳ quặc</a></li>
                    <li><a href="/cong-nghe-thong-tin-c55.html" id="bottom_55" target="_self" title="Công nghệ thông tin">Công nghệ thông tin</a></li>
                    <li><a href="/o-to-xe-may-c77.html" id="bottom_77" target="_self" title="Ô tô - Xe máy">Ô tô - Xe máy</a></li>
                    <li><a href="/thi-truong-tieu-dung-c52.html" id="bottom_52" target="_self" title="Thị trường - Tiêu dùng">Thị trường - Tiêu dùng</a></li>
                    <li><a href="/du-lich-c76.html" id="bottom_76" target="_self" title="Du lịch">Du lịch</a></li>
                    <li><a href="/suc-khoe-doi-song-c62.html" id="bottom_62" target="_self" title="Sức khỏe đời sống">Sức khỏe đời sống</a></li>
                    <?php 
                        //Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
                        $v_domain_viec_lam = _get_module_config('cau_hinh_dung_chung','v_domain_viec_lam');		
                    /* Begin anhpt1 19/11/2015 thay_doi_link_trang_404 */
                    ?>
                    <li><a href="/tin-tuc-quoc-te-c415.html" id="bottom_84" target="_self" title="thế giới">Thế giới</a></li>
                    <?php /* Begin anhpt1 19/11/2015 thay_doi_link_trang_404 */ ?>
                    <li><a href="/cuoi-24h-c746.html" id="bottom_70" target="_self" title="Cười 24H">Cười 24H</a></li>
                    <li><a href="/guest/RSS/" id="bottom_283" target="_self" title="24H RSS">24H RSS</a></li>
                </ul>
            </nav>
        </section>			
        <div class="clear line marB20"></div>
         <p class="orOther">Ho&#7863;c tham kh&#7843;o c&#225;c trang t&#432;&#417;ng t&#7921; d&#432;&#7899;i &#273;&#226;y:</p>             
            <script type="text/javascript" src="//www.google.com/cse/brand?form=cse-search-box&lang=vi"></script>
            <div id="cse-search-results"></div>
            <script type="text/javascript">
                var googleSearchIframeName = "cse-search-results";
                var googleSearchFormName = "cse-search-box";
                var googleSearchFrameWidth = '100%';
                var googleSearchDomain = "www.google.com";
                var googleSearchPath = "/cse";
            </script>
            <script type="text/javascript" src="//www.google.com/afsonline/show_afs_search.js"></script>
            <iframe height='525' width='100%' frameborder='0' scrolling='yes' src="//www.google.com/cse?cx=005327017215456024553:nbasfeisuym&cof=FORID%3A10&ie=UTF-8&q=<?php echo $search; ?>&sa=Ti%CC%80m+ki%C3%AA%CC%81m&ad=w9&num=10&rurl=file%3A%2F%2F%2FD%3A%2Fprojects%2Fweb24h02%2Fdoc%2F404%2Fweb%2520loi%2Findex.html%3Fcx%3D005327017215456024553%253Anbasfeisuym%26cof%3DFORID%253A10%26ie%3DUTF-8%26q%3Ddungpt%26sa%3DTi%25CC%2580m%2Bki%25C3%25AA%25CC%2581m"></iframe>		
        <div style="clear:both"></div>
    </div>
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