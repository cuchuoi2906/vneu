<?php
ini_set('memory_limit','2048M');
$fwLayout = 'content_only.php';	

$v_ten_file = substr($fwRequestUri, 0, strlen($fwRequestUri) - 4);
//begin: sua_doi_he_thong_sitemap_24h
//phuonghv add 20/10/2015 xu ly redirect ve dung phien ban pc neu go sai ten file cua ban mobile.
if (strpos($v_ten_file, 'sitemap-m') !==false) {
    $url = str_replace('/ajax/', '/', $v_ten_file.'.xml');
    $url = str_replace('sitemap-m', 'sitemap-', $url);
    header('location: '.$url);    
} 
//begin: sua_doi_he_thong_sitemap_24h

$template = new vTemplate();
$template->set('v_ten_file', $v_ten_file);
$__MASTER_CONTENT__ = $template->fetch(__get_module_path().'view/index.php');