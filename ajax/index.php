<?php
/**
 * SVN FILE: $Id: index.php 2055 2011-11-24 01:50:00Z dungpt $
 *
 * $Author: dungpt
 * $Revision: 2055 $
 * $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 * $LastChangedBy: dungpt $
 * $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 * $URL: http://svn.24h.com.vn/svn_24h/services-tier/ajax/index.php $
 *
 */
//error_reporting(E_ALL ^ E_NOTICE);
define('START_TIME', microtime(true));
define('WEB_ROOT', str_replace('/ajax','/',realpath(dirname(__FILE__))));

////////////////////////////////////////////// XỬ LÝ VÙNG MIỀN ////////////////////////////////////////////////////
// danh sach cac vung mien
$v_arr_region = array('HN', 'US', 'HCM');
// ưu tiên 1: lấy vùng miền theo bien region trên url
$v_uu_tien1 = 0;

////////////////////////////////////////////// HẾT XỬ LÝ VÙNG MIỀN ////////////////////////////////////////////////////
$fwRequestUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$var = array('/index.php', '/index.php/', 'index.php/', $fwRequestUri, '//',
'?' . $_SERVER['QUERY_STRING'], '/index.php/');
$fwRequestUri = str_replace($var, '/', $_SERVER['REQUEST_URI']);
$fwRequestUri = str_replace('dsp_poll.php', 'dsp_poll', $fwRequestUri);
$fwRequestUri = preg_replace(array('#^\/#', '#\/$#'), '', $fwRequestUri);
$_GET['web_url'] = $fwRequestUri;

$fwModuleName = '';

if ($fwModuleName == '') {
    $fwModuleName = preg_replace('#\.html$#', '', $fwRequestUri);
}

// 06/11/2014 HailT cắt phần tham số sau ? ra riêng 1 phần
$v_vi_tri_cat = strpos($fwModuleName, '?');
$fwAppPathInfo_2 = array();
if ($v_vi_tri_cat > 0) {
	$fwAppPathInfo_2[] = substr($fwModuleName, $v_vi_tri_cat);
	$fwModuleName = substr($fwModuleName, 0, $v_vi_tri_cat);
}

$fwAppPathInfo = array();
$fwAppPathInfo = explode('/', $fwModuleName);
$fwModuleName = ! empty($fwAppPathInfo[0]) ? $fwAppPathInfo[0] : 'home';
$fwModuleName = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwModuleName);
$fwAction = ! empty($fwAppPathInfo[1]) ? $fwAppPathInfo[1] : 'index';
$fwAction = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwAction);
global $v_device_global;
$v_device_global = 'pc';
//Tạo cache toàn trang theo phiên bản
$_GET['v_device_global']= $v_device_global;
$module = WEB_ROOT . 'modules/blocks/' . $fwModuleName . '/' . $fwModuleName . '.php';

// gắn trả lại các tham số sau dấu ?
$fwAppPathInfo = array_merge($fwAppPathInfo, $fwAppPathInfo_2);


if (! file_exists($module)) {
	if ($v_device_global == 'mobile' || $v_device_global == 'botmobi') {
		include WEB_ROOT . 'templates/missing_mobile.php';
	} else if ($v_device_global == 'pc' || $v_device_global == 'botpc'){
		include WEB_ROOT . 'templates/missing_pc.php';
	} else if ($v_device_global == 'tablet' || $v_device_global == 'bottablet') {
		include WEB_ROOT . 'templates/missing_ipad.php';
	}else if ($v_device_global == 'amp' || $v_device_global == 'botamp') {
		include WEB_ROOT . 'templates/missing_amp.php';
	}
    die();
}

require WEB_ROOT . 'includes/app_common.php';

gnud_add_filter('the_main_content', '_qc_static_replace');

$params = array();
if( count( $fwAppPathInfo) > 2) {
	$params = $fwAppPathInfo;
	array_shift( $params);
	array_shift( $params);
}

$class = $fwModuleName.'_block';
$object = new $class();

$fwLayout = 'ajax.php';

$object->autoRender = false;
call_user_func_array( array( &$object, $fwAction),  $params);

$masterContent = $object->blockContent;
$masterContent = gnud_apply_filters('the_main_content', $masterContent);

$_REQUEST['param'] = fw24h_replace_bad_char($str_param);
require WEB_ROOT . 'templates/' . $fwTheme . '/' . $fwLayout;
$the_main_content = ob_get_contents(); // Get the contents of the buffer
ob_end_clean();
echo $the_main_content;


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

//echo "<!--$fwModuleName-->";
/*
$time = round((microtime(true) - START_TIME), 5);
$memory = round((memory_get_usage() - START_MEMORY_USAGE) / 1024);
echo '<pre> Rendered in '. $time. ' seconds using '. $memory. ' kb of memory <br>';

print_r( get_included_files());  */
/**
 * Hàm thực hiện reaplce kí tự đặc biết
 * @param string $p_string : Chuỗi cần replace
 * @return string
 */
function index_fw24h_replace_bad_char($p_string) {
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
/*
 * Lấy tên file theo cấu hình domain
*/
function get_name_file_by_domain_config(){
    if(empty($_SERVER['HTTP_HOST'])){
        return '';
    }
    include WEB_ROOT.'includes/module_configs/domain_chuyen_trang.conf.php';
    $hostHttps = $_SERVER['HTTP_HOST'];
    $arr_temp = explode('.', $hostHttps);
    $v_first = $arr_temp[0];
    // Loại bỏ các số trong domain vd: www1,www2,www3
    $v_first_new = preg_replace('#[0-9]+#','', $v_first);
    $host = str_replace(array('.',$v_first),array('_',$v_first_new), $_SERVER['HTTP_HOST']);
    if(isset($v_arr_domain_chuyen_trang[$host]['file_name']) && $v_arr_domain_chuyen_trang[$host]['file_name'] != ''){
        return $v_arr_domain_chuyen_trang[$host]['file_name'];
    }
    return '';
}