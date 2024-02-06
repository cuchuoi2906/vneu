<?php
/**
 * SVN FILE: $Id: index.php 2056 2011-11-24 01:52:46Z dungpt $
 *
 * $Author: dungpt
 * $Revision: 2056 $
 * $Date: 2011-11-24 08:52:46 +0700 (Thu, 24 Nov 2011) $
 * $LastChangedBy: dungpt $
 * $LastChangedDate: 2011-11-24 08:52:46 +0700 (Thu, 24 Nov 2011) $
 * $URL: http://svn.24h.com.vn/svn_24h/services-tier/index.php $
 *
 */
error_reporting(E_ALL ^ E_NOTICE);
define('START_TIME', microtime(true));
define('START_MEMORY_USAGE', memory_get_usage());
define('WEB_ROOT', realpath(dirname(__FILE__)) . '/');

////////////////////////////////////////////// XỬ LÝ VÙNG MIỀN ////////////////////////////////////////////////////	
global $v_device_global;
$v_device_global = 'pc';
// Kiểm tra truyền thiết bị trên đường dẫn 
if (isset($_GET['v_device_global']) && $_GET['v_device_global']) {
	$v_device_global = index_fw24h_replace_bad_char($_GET['v_device_global']);
}
// Nếu là phiên bản amp thì set region = HN
if($v_device_global=='amp'){
    $_SERVER['SERVER_REGION'] = 'HN';
}

global $v_langue_code;
$v_langue_code = 'vi';
// Kiểm tra truyền thiết bị trên đường dẫn 
if (isset($_GET['lang']) && $_GET['lang'] != '') {
    $v_langue_code = index_fw24h_replace_bad_char($_GET['lang']);
}

$_GET['SERVER_REGION'] = $_SERVER['SERVER_REGION']; // Luu cache theo domain

////////////////////////////////////////////// HẾT XỬ LÝ VÙNG MIỀN ////////////////////////////////////////////////////	

$fwRequestUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$var = array('/index.php', '/index.php/', 'index.php/', $fwRequestUri, '//',
'?' . $_SERVER['QUERY_STRING'], '/index.php/');
$fwRequestUri = str_replace($var, '/', $_SERVER['REQUEST_URI']);
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));
$fwRequestUri = preg_replace(array('#^\/#', '#\/$#'), '', $fwRequestUri);
$_GET['web_url'] = $fwRequestUri;

$fwModuleName = '';

require 'route.php';

if ($fwRequestUri != '') {
    foreach ($fwRoute as $key => $value) {
        $url = $value['url'];
		/* Begin 13/12/2017 Tytv xu_ly_toi_uu_fwRoute_cung_module */
		if (is_array($url)) {
            foreach( $url as $u) {
                if (preg_match('#' . $u . '#', $fwRequestUri, $fwUrlResult)) {
                    $fwModuleName = $key;
                    break;
                }
            }
            if ($fwModuleName !== '') break;
        } else {
            if (preg_match('#' . $url . '#', $fwRequestUri, $fwUrlResult)) {
                $fwModuleName = $key;
                break;
            }
        }
		/* End 13/12/2017 Tytv xu_ly_toi_uu_fwRoute_cung_module */
    }
}

if ($fwModuleName == '') {
    $fwModuleName = preg_replace('#\.html$#', '', $fwRequestUri);
}

$fwAppPathInfo = array();
$fwAppPathInfo = explode('/', $fwModuleName);
$fwModuleName = ! empty($fwAppPathInfo[0]) ? $fwAppPathInfo[0] : 'home';
$fwModuleName = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwModuleName);
$fwAction = ! empty($fwAppPathInfo[1]) ? $fwAppPathInfo[1] : 'index';
$fwAction = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwAction);

//đối với trang video mà device là amp thì đưa về mobile
if($v_device_global=='amp' && $fwModuleName == 'trang_video'){
    $v_device_global = 'mobile';
}
if ($v_device_global == '' || $v_device_global == false) {
	$v_device_global = 'pc';
}
//Tạo cache toàn trang theo phiên bản
$_GET['v_device_global']= $v_device_global;
if ($v_device_global == 'mobile' || $v_device_global == 'botmobi') {
	$module = WEB_ROOT . 'modules/' . $fwModuleName . '/' . $fwAction . '_mobile.php';
} else if ($v_device_global == 'pc' || $v_device_global == 'botpc'){
	$module = WEB_ROOT . 'modules/' . $fwModuleName . '/' . $fwAction . '_pc.php';
} else if ($v_device_global == 'tablet' || $v_device_global == 'bottablet') {
	$module = WEB_ROOT . 'modules/' . $fwModuleName . '/' . $fwAction . '_ipad.php';
}else if ($v_device_global == 'amp' || $v_device_global == 'botamp') {
	$module = WEB_ROOT . 'modules/' . $fwModuleName . '/' . $fwAction . '_amp.php';
}
#---------------------------------------------------------------
/*include WEB_ROOT.'includes/block_cache.inc.php';
foreach ($qcache_list as $value) {
    if ($value['module'] == $fwModuleName) {
        $QUICKCACHE_ON = 1;
        $_QC_CACHE_DIR = $value['cache_dir'];
        $cachetimeout = $value['cache_time'];
        if ($value['get_val'] != '') {
            $_GET['get_val']=$value['get_val']; // dung cho qcache : vi du cache theo USER
        }
        break;
    }
}
if ($QUICKCACHE_ON) {
    include WEB_ROOT.'includes/quickcache/quickcache.php';
}*/
#---------------------------------------------------------------
if (!file_exists($module)) {	
	if ($v_device_global == 'mobile' || $v_device_global == 'botmobi') {
		include WEB_ROOT . 'templates/missing_mobile.php';
	} else if ($v_device_global == 'tablet' || $v_device_global == 'bottablet') {
		include WEB_ROOT . 'templates/missing_ipad.php';
	}else if ($v_device_global == 'amp' || $v_device_global == 'botamp') {
		include WEB_ROOT . 'templates/missing_amp.php';
    }else{
        include WEB_ROOT . 'templates/missing_pc.php';
    }
    die();
}

ob_start();
require WEB_ROOT . 'includes/app_common.php';
require WEB_ROOT . 'modules/before.php';

//End : 19-11-2015 : Thangnb xu_ly_redirect_301
require $module;

// Đặt log trang bài viết
if(strpos($fwModuleName,'news') !== false){
    $start_time = microtime(true);
    $start_mem = memory_get_usage();
}
if ($v_device_global == 'mobile' || $v_device_global == 'botmobi') {
	$filter_file = WEB_ROOT.'modules/'.$fwModuleName.'/filters_mobile.php';
} else if ($v_device_global == 'pc' || $v_device_global == 'botpc') {
	$filter_file = WEB_ROOT.'modules/'.$fwModuleName.'/filters_pc.php';
} else if ($v_device_global == 'tablet' || $v_device_global == 'bottablet') {
	$filter_file = WEB_ROOT.'modules/'.$fwModuleName.'/filters_ipad.php';
} else if ($v_device_global == 'amp' || $v_device_global == 'botamp') {
	$filter_file = WEB_ROOT.'modules/'.$fwModuleName.'/filters_amp.php';
}

if (file_exists($filter_file) && strpos($fwModuleName,'home') === false) {
    include $filter_file;
}

// Đặt log trang bài viết
if(strpos($fwModuleName,'news') !== false){
    log_thoi_gian_thuc_thi($start_time, $start_mem,'Doan 3 filters',WEB_ROOT.'logs/trang_bai_viet_slow.log');
    $start_time = microtime(true);
    $start_mem = memory_get_usage();
}
// Start output buffering
require WEB_ROOT . 'templates/' . $fwTheme . '/' . $fwLayout;

require WEB_ROOT . 'modules/after.php';
$GNUD_HTML = ob_get_contents(); // Get the contents of the buffer
ob_end_clean();
include WEB_ROOT . 'includes/services-tier_do_end.php';
$the_main_content = gnud_apply_filters('the_main_content', $GNUD_HTML);

echo $the_main_content;
// Ghi log thoi gian thuc thi 1 url
$time = round((microtime(true) - START_TIME), 5);
if (GNUD_DEBUG_MODE){	
	$memory = round((memory_get_usage() - START_MEMORY_USAGE) / 1024);
	$errorMsg = date('Y-m-d H:i:s '). "time: $time giay ; Trang: $fwRequestUri ; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
	@error_log($errorMsg, 3, WEB_ROOT.'logs/url.log');				
}
if ($time >= 0.2){
	$errorMsg = date('Y-m-d H:i:s '). "time: $time giay ; Trang: $fwRequestUri ; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
	@error_log($errorMsg, 3, WEB_ROOT.'logs/url_slow_'.$v_device_global.'.log');	
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

// 09/09/2014 HaiLT: function lấy vùng miền dùng GeoIP
function _get_user_region_by_geoip(){
	include WEB_ROOT.'includes/module_configs/vung_mien_geoip.conf.php';
	$v_geoip_country = getenv('GEOIP_COUNTRY_NAME');
	$v_geoip_city = getenv('GEOIP_CITY');
	$v_geoip_region = getenv('GEOIP_REGION');
	// Neu rong thi tra lai HN
	if ($v_geoip_country==''){
		return 'HN';
	}
	
	$v_user_region = 'HN'; // mặc định để là HN. Có thể set lại sau
	if ($v_geoip_country == 'Vietnam') {
		if (in_array($v_geoip_city, $v_arr_geoip_vung_mien_theo_ten_tinh['hn']) || in_array($v_geoip_region, $v_arr_geoip_vung_mien_theo_ma_region_tinh['hn'])) {
			$v_user_region = 'HN';
		} else if (in_array($v_geoip_city, $v_arr_geoip_vung_mien_theo_ten_tinh['hcm']) || in_array($v_geoip_region, $v_arr_geoip_vung_mien_theo_ma_region_tinh['hcm'])) {
			$v_user_region = 'HCM';
		}
	} else {
		$v_user_region = 'US'; // Nếu không phải trong VN thì đưa về US
	}

	return $v_user_region;
}

// 08/04/2015 anhnt1: log nham vung mien
function _log_sai_vung_mien(){
    return;
	$v_server_name = $_SERVER['SERVER_NAME'];
	$v_ip = index_Get_IP_Address();
	// Neu domain  = US & $_SERVER['SERVER_REGION'] != US
	if (preg_match('#^us\d*.24h.com.vn#', $v_server_name)) {
		if ($_SERVER['SERVER_REGION'] != 'US'){
			$errorMsg = date('Y-m-d H:i:s '). "IP: $v_ip ; Domain: $v_server_name ; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
			@error_log($errorMsg, 3, WEB_ROOT.'logs/sai_vung_mien_vn_to_us.log');	
		}	
	}else{
		// Neu domain  != US & $_SERVER['SERVER_REGION'] = US
		if ($_SERVER['SERVER_REGION'] == 'US'){
			$errorMsg = date('Y-m-d H:i:s '). "IP: $v_ip ; Domain: $v_server_name ; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
			@error_log($errorMsg, 3, WEB_ROOT.'logs/sai_vung_mien_us_to_vn.log');	
		}	
	}
}

/**
 * @author anhnt1 29/09/2014 - Kiem tra xem co phai bot
 */

function index_check_is_bot () {
    if (!isset($_SERVER['HTTP_USER_AGENT'])) return false;
    $arrUserAgent = array('Googlebot', 'msnbot', 'Yahoo', 'bingbot','facebookexternalhit');
    foreach ($arrUserAgent as $ua) {
        if (preg_match('#'.strtolower($ua).'#', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    return false;
}

/**
 * @author anhnt1 29/09/2014 - Kiem tra xem co phai bot mobile
 */
function index_check_is_mobile_bot () {
	//echo strtolower($_SERVER['HTTP_USER_AGENT']);
    if (!isset($_SERVER['HTTP_USER_AGENT'])) return false;
    $arrUserAgent = array('googlebot', 'mediapartners-google', 'adsbot-google', 'msnbot-mobile', 'yahooseeker', 'ysearch', 'W3C_Validator/1.3', 'Validator.nu/LV','W3C-checklink','W3C-mobileOK/DDC-1.0','W3C_I18n-Checker/1.0','NING/1.0','FeedValidator/1.3','Jigsaw/2.3.0 W3C_CSS_Validator_JFouffa/2.0','W3C_Unicorn/1.0', 'bingbot');
    foreach ($arrUserAgent as $ua) {
        if (preg_match('#'.strtolower($ua).'#', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    return false;
}

// Get Remote IP Address in PHP
function index_Get_IP_Address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
/**
 * Hàm thực hiện Replace các kí tự đặc biệt
 * param: $p_string Chuỗi cần replace
 */
function index_fw24h_replace_bad_char($p_string) {
    if ( get_magic_quotes_gpc()) {
        $p_string = stripslashes($p_string);
    }
    $p_string = str_replace('<', '&lt;', $p_string);
    $p_string = str_replace('>', '&gt;', $p_string);
    $p_string = str_replace('"', '&#34;', $p_string);
    $p_string = str_replace("'", '&#39;', $p_string);
    $p_string = str_replace('\\', '&#92;', $p_string);
    $p_string = str_replace('=', '&#61;', $p_string);
    $p_string = str_replace('(', '&#40;', $p_string);
    $p_string = str_replace(')', '&#41;', $p_string);
    $p_string = str_replace("|", '&#124;', $p_string);
    return $p_string;	
}