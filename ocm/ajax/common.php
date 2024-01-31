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

define('WEB_ROOT', str_replace('ajax/', '', realpath(dirname(__FILE__)).'/'));

define('GNUD_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'../gnud/');
set_include_path(get_include_path().';'.GNUD_PATH.';'.WEB_ROOT.'includes/');

if (!isset($_is_connect_db) || $_is_connect_db) {
    include WEB_ROOT .'includes/app_db_configs.php';
    include WEB_ROOT . 'gnud/gnud_db_functions.php';
}

include WEB_ROOT.'includes/app_configs.php';

include WEB_ROOT . 'gnud/fw24h_functions.php';
include WEB_ROOT . 'includes/html_functions.php';

fw24h_array_walk_recursive($_REQUEST, 'fw24h_replace_bad_char2'); 
fw24h_array_walk_recursive($_POST, 'fw24h_replace_bad_char2'); 
fw24h_array_walk_recursive($_COOKIE, 'fw24h_replace_bad_char2'); 
fw24h_array_walk_recursive($_GET, 'fw24h_replace_bad_char2'); 

set_error_handler('_myErrorHandler_ajax');



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
function _myErrorHandler_ajax($errno, $errstr, $errfile, $errlile)
{ 
    if ( $errno == E_NOTICE  || $errno == E_STRICT) { // Notice - bo qua
        return true;
    }

    $GLOBALS['_FW24H_ERROR_'] = true;

    $errorMsg = date('Y-m-d H:i:s')."; Err_Code: $errno; Err_Str: $errstr; Err_File: $errfile; Err_Line: $errlile\n";

    error_log($errorMsg, 3, WEB_ROOT.'/logs/runtime-errors_ajax.log');
    	
    // ---
    exit(0);
}
