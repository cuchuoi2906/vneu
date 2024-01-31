<?php	
// host
$v_current_url = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
// 24h
$server = '24h.com.vn';


$v_host=$v_current_url.'://'.$server.'/';
//chuoi tim kiem
$__REQUEST_URI__ = preg_replace( array( '#^\/#', '#\/$#'), '', str_replace( array( '/index.php','/index.php/','index.php/', str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME']), '//', '?'.$_SERVER['QUERY_STRING'], '/index.php/'), '/', $_SERVER['REQUEST_URI']));
$search = $__REQUEST_URI__;              
// url sai
$v_wrong_url = $v_host.$search;
// code cho 24h,eva, game
$v_html_404 = file_get_contents($v_host."upload/html/error404.html");
$v_html_404 = str_replace( '#wrong_url#', $v_wrong_url, $v_html_404);
$v_html_404 = str_replace( '#search#', $search, $v_html_404);
echo $v_html_404;
?>
		