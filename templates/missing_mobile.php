<?php
include_once WEB_ROOT . 'includes/app_configs_common.php';
//Begin 11-11-2015 : Thangnb bo_sung_thay_doi_domain_vieclam
include_once WEB_ROOT . 'includes/app_common.php';
//chuoi tim kiem
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));

?>
<!doctype html>
<html>
<head>
<?php header(' ', true, 404); ?>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" name="viewport">
<title>Tin tuc | Web giai tri so1 | tin tuc trong ngay| bong da | thoi trang</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
<link rel="stylesheet" href="/css/common_092018_mobile.css" type="text/css" />  
<link rel="stylesheet" href="/css/404_092018_mobile.css" type="text/css">
</head>
<body>
</body>
</html>