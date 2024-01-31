<?php
include_once WEB_ROOT . 'includes/app_configs.php';
//Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
include_once WEB_ROOT . 'includes/app_common.php';
//chuoi tim kiem
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));

//Begin 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss
$search = fw24h_replace_bad_char(index_xss_clean(strip_tags(urldecode($__REQUEST_URI__))));
//End 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss

// url sai
$v_wrong_url = BASE_URL_FOR_PUBLIC.$search;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php header(' ', true, 404); ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Tin tuc | Web giai tri so1 | tin tuc trong ngay| bong da | thoi trang</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
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
			echo '<script type="text/javascript" src="'.BASE_URL.'js/jquery.min.js?v='.JS_CSS_VERSION.'"></script>';
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
			$v_arr_script_tag_manager = replace_content_gtm_page_dimensions($v_arr_script_tag_manager, '404', '', 'web', '');
			//End 04-07-2017 : Thangnb xu_ly_tracking_page_dimension_google_tag_manager
			if ($v_arr_script_tag_manager['v_script_tren_header'] != '') {
				echo $v_arr_script_tag_manager['v_script_tren_header'];
			}
		}
		//Begin 09-11-2017 : Thangnb xu_ly_tracking_ga_content
		$v_on_off_tracking_ga_content = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_TRACKING_GA_CONTENT');
		if ($v_script_cross_domain != '') {
			if ($v_on_off_tracking_ga_content === 'TRUE') {
				$v_script_cross_domain = replace_tracking_ga_content($v_script_cross_domain, '404', '', 'web', '');
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
						$v_html_ga_tong = replace_tracking_ga_content($v_html_ga_tong, '404', '', 'web', '');
					}
					echo $v_html_ga_tong;
                }
				//End 09-11-2017 : Thangnb xu_ly_tracking_ga_content
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
					
					ga('create', 'UA-57441023-2', 'auto',{name: 'Tong24h_PC'});
					ga('Tong24h_PC.require', 'linkid', 'linkid.js');
					ga('Tong24h_PC.require', 'displayfeatures');
					ga('Tong24h_PC.send', 'pageview');
					
					ga('create', 'UA-8670218-52', 'auto',{name: 'trangloi24h_Tong'});
					ga('trangloi24h_Tong.require', 'linkid', 'linkid.js');
					ga('trangloi24h_Tong.require', 'displayfeatures');
					ga('trangloi24h_Tong.send', 'pageview');
					
					ga('create', 'UA-57441023-24', 'auto',{name: 'trangloi24h_PC'});
					ga('trangloi24h_PC.require', 'linkid', 'linkid.js');
					ga('trangloi24h_PC.require', 'displayfeatures');
					ga('trangloi24h_PC.send', 'pageview');
                <?php } ?>
	//]]></script>
	<!-- End GA code -->
    <?php 
    } 
	?>
</head>
<style>
*{
	margin:0 auto;
	padding:0;
	font-family:Arial, Helvetica, sans-serif;		
}
img{
	border:0px;
}
.clear{
	clear:both;
}
.container{
	width:1004px;
	padding-bottom:20px;
}
.wrongLink{
	color:#00C;
}
.greenTitle{
	font-size:18px;
	color:#0a5b2f;
	font-weight:bold;
	padding-left:40px;
	padding-top:30px;
	padding-bottom:20px;
}
.blackTitle{
	padding-left:40px;
	color:#000000;
	font-size:18px;
	padding-bottom:20px;
	float:left;
	width:450px;
}
.listPage{
	float:left;
	width:255px;
	color:#3660a9;
	font-size:18px;
	line-height:27px;
	
}
.listNumber{
	width:20px;
	float:left;
	text-align:right;
	font-size:17px;
	color:#3660a9;
	padding-right:5px;
}
.listPage a{
	color:#3660a9;
	font-size:17px;
	padding-left:5px;
	display:block;
}
.listPage a:hover{
	color:#666666;
	text-decoration:none;
}
.header-24h-1{
	background:url(<?php echo IMAGE_STATIC; ?>upload/html/header404) no-repeat;
	width:1004px;
	height:125px;
	
}
.border-24h-1{
	border:#090 solid 1px;	
}
.orOther{ float:left; clear:both; width:100%;font-size:17px;
	color:#005C27;font-weight:bold; padding:0 0 0 10px}
.divSearch{ float:left; clear:both; width:100%; padding:0 0 0 10px}
.divIndex{float:left;width:30px;}

h3{
color:#FFFFFF;
text-transform:uppercase;
font-family:Verdana, Arial, Helvetica, sans-serif;
float:right;
margin-right:8px;
margin-top:45px;
font-size:21px;
}
.logo{ width:250px;float:left; text-align:center}

.logo_title{
	width:754px;
	float:left;
	color:#FFFFFF;
	text-transform:uppercase;
	font-family:Arial, Helvetica, sans-serif;
	font-size:21px;
	font-weight:bold;
	text-align:center;
	line-height:20px;
	text-decoration:none;
	padding:40px 0 0;
}
.logo_title a{
	text-decoration:none;
	color:#FFFFFF;
}

</style>
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
	<div class="container border-24h-1">
    	<div class="header-24h-1">
			<div class="logo"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>"><img src="<?php echo IMAGE_STATIC; ?>upload/html/logo" /></a></div>

			<div class="logo_title"><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">Website giải trí trực tuyến số 1 Việt Nam</a></div>
		</div>
        <div class="greenTitle">&#272;&#431;&#7900;NG D&#7850;N B&#7840;N V&#7914;A G&#213; SAI: <span class="wrongLink"><?php echo $v_wrong_url; ?></span></div>
        <div class="blackTitle"><img src="<?php echo IMAGE_STATIC; ?>upload/html/arrow_red.gif" align="asbmindle" border="0"/> M&#7901;i b&#7841;n b&#7845;m v&#224;o link b&#234;n c&#7841;nh &#273;&#7875; v&#224;o trang:</div>
		<?php 
			//Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
			$v_domain_viec_lam = _get_module_config('cau_hinh_dung_chung','v_domain_viec_lam');
        /* Begin anhpt1 19/11/2015 thay_doi_link_trang_404 */
		?>
        <div class="listPage">
            <div class="listNumber">1.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">Trang chủ 24h</a>
            <div class="listNumber">2.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>tin-tuc-su-kien-c46.html">Tin tức sự kiện</a>
            <div class="listNumber">3.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>bong-da-c48.html">Bóng đá</a>
            <div class="listNumber">4.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>thoi-trang-c78.html">Thời trang</a>
            <div class="listNumber">5.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>cuoi-suot-24gio-c70.html">Cười</a>
            <div class="listNumber">6.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>">24h.com.vn</a>
        </div>
        <div class="listPage">
            <div class="listNumber">7.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>tin-tuc-quoc-te-c415.html">Thế giới</a>
            <div class="listNumber">8.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>"an-ninh-hinh-su-c51.html>An ninh xã hội</a>
            <div class="listNumber">9.</div><a href="<?php echo BASE_URL_FOR_PUBLIC; ?>"ban-tre-cuoc-song-c64.html>Bạn trẻ - Cuộc sống</a>
            <div class="listNumber">10.</div><a href="http://game.24h.com.vn/">Game</a>
            <div class="listNumber">11.</div><a href="http://ketquaxoso.24h.com.vn/">Xổ số</a>
            <div class="listNumber">12.</div><a href="http://chungkhoan.24h.com.vn">Chứng khoán</a>
        </div>  
        <?php /* Begin anhpt1 19/11/2015 thay_doi_link_trang_404 */ ?>
		 <p class="orOther">Ho&#7863;c tham kh&#7843;o c&#225;c trang t&#432;&#417;ng t&#7921; d&#432;&#7899;i &#273;&#226;y:</p> 
			
			<script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box&lang=vi"></script>

			<form action="" id="cse-search-box" name='dungpt_search'>
				<div class="divSearch">
					<input type="hidden" name="cx" value="005327017215456024553:nbasfeisuym" />
					<input type="hidden" name="cof" value="FORID:10" />
					<input type="hidden" name="ie" value="UTF-8" />
					<input type="text" name="q" size="31" value="<?php echo $search; ?>" />
					<input type="submit" name="sa" value="T&#236;m ki&#7871;m" />
				</div>
			</form>

			<div id="cse-search-results"></div>
			<script type="text/javascript">
				var googleSearchIframeName = "cse-search-results";
				var googleSearchFormName = "cse-search-box";
				var googleSearchFrameWidth = 600;
				var googleSearchDomain = "www.google.com";
				var googleSearchPath = "/cse";
			</script>
			<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
			<iframe height='525' width='100%' frameborder='0' scrolling='yes' src="http://www.google.com/cse?cx=005327017215456024553:nbasfeisuym&cof=FORID%3A10&ie=UTF-8&q=<?php echo $search; ?>&sa=Ti%CC%80m+ki%C3%AA%CC%81m&ad=w9&num=10&rurl=file%3A%2F%2F%2FD%3A%2Fprojects%2Fweb24h02%2Fdoc%2F404%2Fweb%2520loi%2Findex.html%3Fcx%3D005327017215456024553%253Anbasfeisuym%26cof%3DFORID%253A10%26ie%3DUTF-8%26q%3Ddungpt%26sa%3DTi%25CC%2580m%2Bki%25C3%25AA%25CC%2581m"></iframe>		
        </div>
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