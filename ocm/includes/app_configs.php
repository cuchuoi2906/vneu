<?php
/* SVN FILE: $Id: app_configs.php 2781 2011-12-21 07:07:48Z dungpt $ */
/**
 *
 * @author $Author: dungpt $
 * @version $Revision: 2781 $
 * @lastrevision $Date: 2011-12-21 14:07:48 +0700 (Wed, 21 Dec 2011) $
 * @modifiedby $LastChangedBy: dungpt $
 * @lastmodified $LastChangedDate: 2011-12-21 14:07:48 +0700 (Wed, 21 Dec 2011) $
 * @filesource: https://ladybirdphp.googlecode.com/svn/trunk/website/ocm/app/config/core.php $
 */

$fwQueries = array();
$fwError = false;
$fwLayout = 'layout.php';
$fwTheme = 'default';

define('DEBUG_MODE', true);
define('APP_CODE', 'TINTUC24H');

define('BASE_URL2', '/ocm/'); // dia chi cua website ko domain
define('BASE_DOMAIN', 'https://vneu.vn/'); // dia chi cua website,...
define('SEO_DOMAIN', 'https://vneu.vn/'); // dia chi cua website,...
define('FRONTEND_DOMAIN', ''); // dia chi cua website (frontend),...
define('MST_DOMAIN', BASE_DOMAIN); // dia chi mst cua website,...
define('SERVICES_TIER_URL', BASE_DOMAIN .substr(BASE_URL2,1));
define('BASE_URL', BASE_DOMAIN .substr(BASE_URL2,1)); // dia chi cua trang quan tri,...

//End 08-06-2016 : Thangnb xu_ly_thay_the_domain_static
define('ROOT_FOLDER', '/home/nhoffcor/public_html/'); // duong dan thu muc goc
define('DISPLAY_ERROR', false); // hien thi loi
define('IS_REDIRECT_TO_ERROR_URL', false); // hien thi loi
define('PAGINATION_NUMBER', 4); // So trang hien thi mac dinh cho cac box duoc phan trang.

if (! defined('E_STRICT')) {
    define('E_STRICT', 2048);
}// Neu GNUD_DEBUG_MODE = true thi se ghi log goi cac block, log chay dich vu truy cap DB
if(!defined('GNUD_DEBUG_MODE')) {
    define('GNUD_DEBUG_MODE', false);
}
// Neu CRON_DEBUG_MODE = true thi se ghi log khi chay cron
if(!defined('CRON_DEBUG_MODE')) {
    define('CRON_DEBUG_MODE', true);
}

$_LEFT_CONTENT = array(
	'object:menu/be_menu_user()'
);
define("_CONST_NUMBER_OF_ROW_PER_LIST",20);
define("_CONST_NUMBER_OF_ROW_PER_LIST_BANNER_CONFIG",30);
define("_CONST_NUMBER_OF_ROW_PER_LIST_DOI_TAC_QUANG_CAO_VIDEO",20);
define("_CONST_NUMBER_OF_ROW_PER_LIST_VIDEO_ADS_CONFIG",20);
define("_CONST_MAX_NUMBER_PAGE",100);


define('KIEM_TRA_OTP', false);
define('DEFAULT_PAGE_TITLE', 'Online Content Management'); // Title mac dinh (trong the <title>)

define('MAX_SUMMARY_IMAGE_SIZE', 102400*5); // Gioi han dung luong anh dai dien bai viet
define('UPLOAD_FOLDER', ROOT_FOLDER.'upload/'); // duong dan thu upload
define('BANNER_EXTENSION_ALLOW', 'gif,jpg,png,jpeg,pjpeg,swf,html,htm,flv,mp4'); // Dinh dang banner upload
define('IMAGE_EXTENSION_ALLOW', 'gif,jpg,png,jpeg,pjpeg'); // Dinh dang anh upload
define('VIDEO_EXTENSION_ALLOW', 'wmv,flv,mp4,3gp'); // Dinh dang video upload
define('MAX_IMAGE_SIZE', 5*1024*1024); // Gioi han dung luong anh upload
define('MAX_VIDEO_SIZE', 300*1024*1024); // Gioi han dung luong video