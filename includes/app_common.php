<?php
/**
* SVN FILE: $Id: app_common.php 2055 2011-11-24 01:50:00Z dungpt $
*
* $Author: dungpt
* $Revision: 2055 $
* $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/includes/app_common.php $
*
*/

error_reporting(E_ALL ^ E_NOTICE); 

define('DS', DIRECTORY_SEPARATOR);

define('GNUD_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'../gnud/');
set_include_path(get_include_path().';'.GNUD_PATH.';'.WEB_ROOT.'includes/');

include WEB_ROOT . 'includes/app_db_configs.php';
include WEB_ROOT . 'includes/app_configs_common.php';
include_once WEB_ROOT . 'includes/functions_common.php';
include WEB_ROOT . 'includes/db_functions/db.general_common.inc.php';
include WEB_ROOT . 'includes/html_functions_common.php';
global $v_device_global;

if ($v_device_global == 'mobile' || $v_device_global == 'botmobi') {
    include WEB_ROOT . 'includes/app_configs_mobile.php';
	include WEB_ROOT . 'includes/video_ad_configs_mobile.php';
	include_once WEB_ROOT . 'includes/video_24h_functions_mobile.php';
    include_once WEB_ROOT . 'includes/functions_mobile.php';
} else if ($v_device_global == 'ipad' || $v_device_global == 'tablet') {
    include WEB_ROOT . 'includes/app_configs_ipad.php';
    include WEB_ROOT . 'includes/counter_config_ipad.php';
    include WEB_ROOT . 'includes/video_ad_configs_ipad.php';
    include WEB_ROOT . 'includes/html_functions_ipad.php';
    include_once WEB_ROOT . 'includes/functions_ipad.php';
}else{
    include WEB_ROOT . 'includes/app_configs_pc.php';
    include WEB_ROOT . 'includes/html_functions_pc.php';
    include_once WEB_ROOT . 'includes/functions_pc.php';
	include WEB_ROOT . 'includes/video_ad_configs_pc.php';
	include_once WEB_ROOT . 'includes/video_24h_functions_pc.php';
}
include WEB_ROOT . 'includes/url_function.php';
include WEB_ROOT . 'includes/url_helper.php';
include GNUD_PATH . 'services-tier.php';
//include WEB_ROOT . 'includes/cache.php';
include_once GNUD_PATH . 'fw24h_functions.php';
include GNUD_PATH . 'gnud_db_functions.php';
include GNUD_PATH . 'fw24h_block.php';
include GNUD_PATH . 'vste.php';


$_REQUEST = fw24h_array_walk_recursive($_REQUEST, 'fw24h_replace_bad_char');
$_POST = fw24h_array_walk_recursive($_POST, 'fw24h_replace_bad_char');
$_COOKIE = fw24h_array_walk_recursive($_COOKIE, 'fw24h_replace_bad_char');
$_GET = fw24h_array_walk_recursive($_GET, 'fw24h_replace_bad_char');

$_SERVER['PR_REGION'] = ($_SERVER['SERVER_REGION']!='HN' && $_SERVER['SERVER_REGION']!='HCM') ? 'HCM' : $_SERVER['SERVER_REGION'];
//$_SERVER['PR_REGION'] = $_SERVER['SERVER_REGION'];

set_error_handler('_myErrorHandler');


/**
 * dinh nghia ham bao loi rieng - neu ko co ham _myErrorHandler
 * se su dung ham fw24h_myErrorHandler cua fw24h.
 * _myErrorHandler()
 *
 * @param mixed $p_errno
 * @param mixed $p_errstr
 * @param mixed $p_errfile
 * @param mixed $p_errlile
 * @return
 */
function _myErrorHandler ($errno, $errstr, $errfile, $errlile)
{
    if ($errno == E_NOTICE || $errno == E_STRICT || $errno == 256 || $errno == 2) { // Notice, MySQL server has gone away - bo qua
        return true;
    }

    global $gnud_filter;
    if( is_array( $gnud_filter['_myerrorhandler']) && sizeof($gnud_filter['_myerrorhandler']) > 0) {
        return gnud_apply_filters('_myerrorhandler', $errstr);
    }

    $GLOBALS['_FW24H_ERROR_'] = true;
    global $fwRequestUri;

    $time = microtime(true) - START_TIME;
    $time = number_format($time, 12);
    
    $errorMsg = date('Y-m-d H:i:s') . "; Err_Code: $errno; Err_Str: $errstr; Err_File: $errfile; Err_Line: $errlile; RequestUri:".$fwRequestUri."; IP_addess:". fw24h_Get_IP_Address()."; Execution_Time: $time\n";
    fw24h_write_log($errorMsg, WEB_ROOT . '/logs/runtime-errors.log');

    if (DISPLAY_ERROR) {
        echo 'DEBUG MODE:<br>';
        echo $errorMsg;
    }

    $GLOBALS['_FW24H_ERROR_'] = true;
    $GLOBALS['QUICKCACHE_ON'] = 0; // ko cache du lieu bi loi
	
	// Neu khong phai may chu cron thi chuyen sang sv khac
	if (!preg_match('#'.$_SERVER['SERVER_NAME'].'#', CRON_URL) && IS_REDIRECT_TO_ERROR_URL) {
		$url = ERROR_URL.'?&SERVER_ID='.SERVER_NUMBER.'&url='.fw24h_base64_url_encode($_GET['web_url']);
		echo "<script>location.href='".$url."';</script>";
		while(@ob_end_clean()){}
		header('location: '.$url, true, 302);
	}	
	return true;
}
