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

define('DS', DIRECTORY_SEPARATOR);

define('GNUD_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'../gnud/');
set_include_path(get_include_path().':'.GNUD_PATH.':'.WEB_ROOT.'includes/');

include WEB_ROOT . 'includes/app_db_configs.php';
include WEB_ROOT . 'includes/app_configs.php';

include WEB_ROOT . 'includes/functions.php';
include WEB_ROOT . 'includes/html_functions.php';
include WEB_ROOT . 'includes/url_function.php';
include GNUD_PATH . 'services-tier.php';
include_once GNUD_PATH . 'fw24h_functions.php';
include GNUD_PATH . 'gnud_db_functions.php';
include GNUD_PATH . 'fw24h_block.php';
include GNUD_PATH . 'vste.php';
include WEB_ROOT . 'includes/class/url_helper.php';
include WEB_ROOT . 'includes/class/fw24h_pagination_component.php';

//set_error_handler('_myErrorHandler');

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
    if ($errno == E_NOTICE || $errno == E_STRICT) { // Notice - bo qua
        return true;
    }
    
	global $gnud_filter;
	if( is_array( $gnud_filter['_myerrorhandler']) && sizeof($gnud_filter['_myerrorhandler']) > 0) {
		return gnud_apply_filters('_myerrorhandler', $errstr);
	}

    $GLOBALS['_FW24H_ERROR_'] = true;
    global $fwRequestUri;
    
    //$arr_debug_backtrace = debug_backtrace();
    //$post = serialize($_POST);
    $errorMsg = date('Y-m-d H:i:s') . "; Err_Code: $errno; Err_Str: $errstr; Err_File: $errfile; Err_Line: $errlile; RequestUri:".$fwRequestUri."; IP_addess:". fw24h_Get_IP_Address()."; _POST: \n";
    
	error_log($errorMsg, 3, WEB_ROOT . 'logs/runtime-errors.log');

	if (DISPLAY_ERROR){
		echo $errorMsg;
	}	
    
	$GLOBALS['_FW24H_ERROR_'] = true;
	$GLOBALS['QUICKCACHE_ON'] = 0; // ko cache du lieu bi loi

	if (IS_REDIRECT_TO_ERROR_URL){	
		$url = ERROR_URL.'?&SERVER_ID='.SERVER_NUMBER.'&url='.fw24h_base64_url_encode($_GET['web_url']);
		echo "<script>location.href='".$url."';</script>";
		while(@ob_end_clean()){}
		header('location: '.$url, true, 302);
	}	
    exit(0);
}

return 1;

// $Id: app_common.php 2055 2011-11-24 01:50:00Z dungpt $

