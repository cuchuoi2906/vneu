<?php
/* SVN FILE: $Id: common.php 2055 2011-11-24 01:50:00Z dungpt $ */
/**
*
* @author $Author: dungpt $
* @version $Revision: 2055 $
* @lastrevision $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* @modifiedby $LastChangedBy: dungpt $
* @lastmodified $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* @filesource $URL: http://svn.24h.com.vn/svn_24h/services-tier/ajax/common.php $
*/

error_reporting(E_ALL ^ E_NOTICE);

define('DS', DIRECTORY_SEPARATOR);

define('WEB_ROOT', str_replace('crondaemon/', '', realpath(dirname(__FILE__)).'/'));

define('GNUD_PATH', WEB_ROOT.'gnud/');
set_include_path(get_include_path().';'.GNUD_PATH.';'.WEB_ROOT.'includes/;'.WEB_ROOT.'crondaemon/;');

//Begin 21-08-2017 : Thangnb chay_cron_cho_cac_chuyen_muc_an
$DANH_SACH_CHUYEN_MUC_AN_CHAY_CRON = array(
	array(
		'ID' => 70,
		'Name' => 'Cười 24h',
		'Title' => 'Cười 24h'
	),
);
//End 21-08-2017 : Thangnb chay_cron_cho_cac_chuyen_muc_an

include WEB_ROOT .'includes/app_db_configs.php';
include WEB_ROOT . 'includes/db_functions/db.general_common.inc.php';
include WEB_ROOT.'includes/app_configs_pc.php';
include WEB_ROOT.'includes/app_configs_common.php';
set_error_handler('_myErrorHandler_crondeamon');

include WEB_ROOT . 'gnud/fw24h_functions.php';
include WEB_ROOT . 'gnud/gnud_db_functions.php';
include WEB_ROOT . 'includes/functions_common.php';
// begin 17/04/2018 Tytv toi_uu_key_tao_html_bai_video_theo_cm_video
include_once WEB_ROOT . 'includes/functions_video_common.php';
// end 17/04/2018 Tytv toi_uu_key_tao_html_bai_video_theo_cm_video
include WEB_ROOT . 'includes/functions_pc.php';
include WEB_ROOT . 'includes/html_functions_common.php';
include WEB_ROOT . 'includes/html_functions_pc.php';


include_once WEB_ROOT . 'includes/video_ad_configs_pc.php';
// Begin: toi_uu_key_value ducnq: 29/09/2015
include WEB_ROOT . 'includes/app_redis_configs.php'; 
include_once GNUD_PATH . 'gnud_redis_function.php'; 
// End: toi_uu_key_value ducnq: 29/09/2015

// Tham chieu cac ham xu ly CSDL
__get_db_functions('db.general');

// Tham chieu cac ham xu ly url
include WEB_ROOT . 'includes/url_helper.php';
include_once WEB_ROOT . 'includes/url_function.php';
// Tham chieu cac ham xu ly quang cao
include WEB_ROOT . 'includes/ads_functions.php';
// Tham chieu cac ham xu ly key value
include WEB_ROOT . 'includes/genkey_functions.php';

// include thêm block 24h
include GNUD_PATH . 'fw24h_block.php';
include GNUD_PATH . 'vste.php';
include WEB_ROOT . 'includes/class/fw24h_pagination_component.php';
/**
 * dinh nghia ham bao loi rieng - neu ko co ham _myErrorHandler se su dung ham fw24h_myErrorHandler cua fw24h.
 * _myErrorHandler()
 * 
 * @param mixed $errno
 * @param mixed $errstr
 * @param mixed $errfile
 * @param mixed $errlile
 * @return
 */
function _myErrorHandler_crondeamon($errno, $errstr, $errfile, $errlile)
{ 
    if ($errno == E_NOTICE || $errno == E_STRICT || $errno == 256 || $errno == 2) { // Notice, MySQL server has gone away - bo qua
        return true;
    }
    //$GLOBALS['_FW24H_ERROR_'] = true;
    $errorMsg = date('Y-m-d H:i:s')."; Err_Code: $errno; Err_Str: $errstr; Err_File: $errfile; Err_Line: $errlile\n";
    error_log($errorMsg, 3, WEB_ROOT.'/logs/runtime-errors_crondeamon.log');
	return true;
    // ---
    // exit(0);
}