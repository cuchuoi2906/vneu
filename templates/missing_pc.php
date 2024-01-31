<?php
include_once WEB_ROOT . 'includes/app_configs_common.php';
//Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
include_once WEB_ROOT . 'includes/app_common.php';
//chuoi tim kiem
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="//www.w3.org/1999/xhtml">
<head>
<?php header(' ', true, 404); ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Tin tuc | Web giai tri so1 | tin tuc trong ngay| bong da | thoi trang</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<link rel="stylesheet" href="/css/404_092018_pc.css" type="text/css" />    
<link rel="stylesheet" href="/css/common_092018_pc.css" type="text/css" />   
</head>
<body>
	day la trang 404
</body>
</html>