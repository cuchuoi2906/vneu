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

$_FW24H_MAIL_CONF['from'] = 'noreply@email.24h.com.vn';
$_FW24H_MAIL_CONF['mail_server'] = 'email-mx.24h.com.vn';
$_FW24H_MAIL_CONF['username'] = 'noreply@email.24h.com.vn';
$_FW24H_MAIL_CONF['password'] = 'noreply24h.com';
$_FW24H_MAIL_CONF['method'] = 'smtp';
$_FW24H_MAIL_CONF['bcc'] = '';

define('DEBUG_MODE', false); // Che do ghi log qua trinh chạy code
define('DISPLAY_ERROR', false); // hien thi loi
define('IS_REDIRECT_TO_ERROR_URL', false); // co redirect den url xu ly loi khi xuat hien loi hay khong
if(!defined('GNUD_DEBUG_MODE')) {
    define('GNUD_DEBUG_MODE', false);
}
// Neu CRON_DEBUG_MODE = true thi se ghi log khi chay cron
if(!defined('CRON_DEBUG_MODE')) {
    define('CRON_DEBUG_MODE', true);
}

define('GOI_SP_KHI_KHONG_CO_KEY_DATA', false); // Bat/tat che do goi SP khi khong co du lieu o key-data
define('GOI_SEO_CHI_TIET_TRONG_BLOCK', false); // Bat/tat che do goi SEO chi tiet trong block

define('BASE_URL', '/'); // duong dan tuong doi thu muc goc (them vao truoc cac duong dan kieu ajax/...)
define('BASE_URL_FOR_PUBLIC',  'https://dev.vneu.vn/'); // domain SEO
define('NAME_THIET_BI_MOBILE', 'mobile');
define('NAME_BOT_MOBILE', 'botmobi');
define('NAME_THIET_BI_PC', 'pc');
define('NAME_BOT_PC', 'botpc');
define('NAME_THIET_BI_TABLET', 'tablet');
define('NAME_THIET_BI_AMP', 'amp');
define('NAME_BOT_AMP', 'botamp');
define('IMAGE_STATIC', 'https://dev.vneu.vn/');
define('IMAGE_NEWS', 'https://dev.vneu.vn/');
define('MAX_REPLACE_ALT_IMG', 70); // Số lượng tối đa cho phép thay thế alt ảnh trong bài viết
define('USE_IMAGE_STATIC',true); // c?u hình su dung img statis
define('SO_LUONG_BAI_VIET_TRONG_KEY_DATA',200);
define('ID_TRANG_CHU',1);