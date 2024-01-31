<?php
/**
 * SVN FILE: $Id: index.php 2055 2011-11-24 01:50:00Z dungpt $
 * 
 * $Author: dungpt
 * $Revision: 2055 $
 * $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 * $LastChangedBy: dungpt $
 * $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 *
 */
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$fwRequestUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

//Begin 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss
$var = array('/index.php', '/index.php/', 'index.php/', $fwRequestUri, 
'?' . $_SERVER['QUERY_STRING'], '/index.php/', '//');
//End 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss

$fwRequestUri = str_replace($var, '/', $_SERVER['REQUEST_URI']);
$fwRequestUri = preg_replace(array('#^\/#', '#\/$#'), '', $fwRequestUri);
$_GET['web_url'] = $fwRequestUri;
define('WEB_ROOT', str_replace('/ajax','/',realpath(dirname(__FILE__))));
$fwModuleName = '';

if ($fwModuleName == '') {
    $fwModuleName = preg_replace('#\.html$#', '', $fwRequestUri);
    $fwModuleName = preg_replace('#\.php$#', '', $fwRequestUri);
}

$fwAppPathInfo = array();
$fwAppPathInfo = explode('/', $fwModuleName);
$fwModuleName = ! empty($fwAppPathInfo[0]) ? $fwAppPathInfo[0] : 'home';
$fwModuleName = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwModuleName);
$fwAction = ! empty($fwAppPathInfo[1]) ? $fwAppPathInfo[1] : 'index';
$fwAction = preg_replace('#[^a-z0-9A-Z\-\_]*#im', '', $fwAction);
$module = WEB_ROOT . 'modules/blocks/' . $fwModuleName . '/' . $fwModuleName . '.php';
if (! file_exists($module)) {
    include WEB_ROOT . 'templates/missing.php';
    die();
}
require WEB_ROOT . 'includes/app_common.php';
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

//require WEB_ROOT . 'templates/' . $fwTheme . '/' . $fwLayout;

if ($object->getParam('noindex')) {
    require WEB_ROOT . 'templates/' . $fwTheme . '/' . $fwLayout;
} else {
	echo $masterContent;
}

//echo "<!--$fwModuleName-->";
/* 
$time = round((microtime(true) - START_TIME), 5);
$memory = round((memory_get_usage() - START_MEMORY_USAGE) / 1024);
echo '<pre> Rendered in '. $time. ' seconds using '. $memory. ' kb of memory <br>';

print_r( get_included_files());  */
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