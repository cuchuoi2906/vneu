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
//Begin 03-02-2016 : Thangnb fix_loi_bao_mat_rapid7
@session_start();
//End 03-02-2016 : Thangnb fix_loi_bao_mat_rapid7
$_SESSION['slow_sql'] = array();
error_reporting( E_ALL );
//ini_set('display_errors', 1);

$fwRequestUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$var = array('/index.php', '/index.php/', 'index.php/', $fwRequestUri, '//', 
'?' . $_SERVER['QUERY_STRING'], '/index.php/');
$fwRequestUri = str_replace($var, '/', $_SERVER['REQUEST_URI']);
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));
$fwRequestUri = preg_replace(array('#^\/#', '#\/$#'), '', $fwRequestUri);
$_GET['web_url'] = $fwRequestUri;

define('WEB_ROOT', realpath(dirname(__FILE__)) . '/');
$fwModuleName = '';
require 'route.php';

$from_route = false;
if ($fwRequestUri != '') {
    foreach ($fwRoute as $key => $value) {
        $url = $value['url'];
		if (preg_match('#' . $url . '#', $fwRequestUri, $fwUrlResult)) {
            $fwModuleName = $key;
			$from_route = true;
            break;
        }
    }
}

if ($fwModuleName == '') {
    $fwModuleName = preg_replace('#\.html$#', '', $fwRequestUri);
    $fwModuleName = preg_replace('#\.php$#', '', $fwRequestUri);
}

$fwAppPathInfo = array();
$fwAppPathInfo = explode('/', $fwModuleName);
if (empty($fwAppPathInfo[0])) {
	$fwModuleName = 'home';
	$from_route = true;
} else {
	$fwModuleName = $fwAppPathInfo[0];
}

$fwModuleName = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwModuleName);
$fwAction = ! empty($fwAppPathInfo[1]) ? $fwAppPathInfo[1] : 'index';
$fwAction = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwAction);

require WEB_ROOT . 'includes/app_common.php';
require WEB_ROOT . 'modules/before.php';
if ($from_route) {
	$module = WEB_ROOT . 'modules/' . $fwModuleName . '/' . $fwAction . '.php';
	if (!file_exists($module)) {
		include WEB_ROOT . '/templates/missing.php';
		die();
	}
	ob_start(); 
	require $module;
	$filter_file = WEB_ROOT.'modules/'.$fwModuleName.'/filters.php';
	if (file_exists($filter_file)) {
		include $filter_file;
	}
} else {
	$module = WEB_ROOT . 'modules/blocks/' . $fwModuleName . '/' . $fwModuleName . '.php';
	
	if (!file_exists($module)) {
		include WEB_ROOT . '/templates/missing.php';
		die();
	}

	ob_start();

	$params = array();
	if( count( $fwAppPathInfo) > 2) {
		$params = $fwAppPathInfo;
		array_shift( $params);
		array_shift( $params);
	}

	$class = $fwModuleName.'_block';
	$object = new $class();
	$object->autoRender = false;
	if (method_exists ($object, $fwAction)) {
		call_user_func_array( array( &$object, $fwAction),  $params);
	} else {
		include WEB_ROOT . 'templates/missing.php';
		die();
	}

	$__MASTER_CONTENT__ = $object->blockContent;
}

// Start output buffering
require WEB_ROOT . 'templates/' . $fwTheme . '/' . $fwLayout;

require WEB_ROOT . 'modules/after.php';
$GNUD_HTML = ob_get_contents(); // Get the contents of the buffer
ob_end_clean(); 
include WEB_ROOT . 'includes/services-tier_do_end.php';
$the_main_content = gnud_apply_filters('the_main_content', $GNUD_HTML);

echo $the_main_content;

/** Begin: xu_lu_loi_xss_07_2015
 *  Xử lý Xss với các biến $_REQUEST, $_POST, $_GET
 *  @author     anhpt1
 *  @date       11-07-2016
 */
function request_xss_clean(&$request){
    if($request){
        foreach($request AS $key => $value){
            if(!is_array($value)){
                // Tác chuỗi để xử lý biến truyền vào
                // kiểm tra biến truyền vào có mã hóa base64 kèm mã script không
                // nếu có thì loại bỏ biến đó đưa về rỗng
                $v_arr_item = explode('/', $value);
                if(is_array($v_arr_item)){
                    foreach($v_arr_item AS $key_item => $value_item){
                        if(preg_match('/<script/', base64_decode($value_item))){
                            $v_arr_item[$key_item] = '';
                        }
                    }
                    $value  = implode('/', $v_arr_item);
                }
                if($key == 'hdn_summary_image_chu_nhat_preview' && isset($_FILES['file_summary_image_chu_nhat']) && strpos($_FILES['file_summary_image_chu_nhat']['name'],'.gif') !== false){
                    $request[$key]  = $value;
                }else{
                    $request[$key]  = index_xss_clean(strip_tags($value));
                }
            }
        }
    }
}

/*
* HÃ m xá»­ lÃ½ xÃ³a cÃ¡c tháº» cÃ³ thá»ƒ gÃ¢y lá»—i xss.
* @author: ducnq - 08/01/2015
* @param string data chuá»—i ná»™i dung
* @return string 
*/
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
}/* End anhpt1 07/07/2016 fix_loi_bao_mat_reflected_cross */