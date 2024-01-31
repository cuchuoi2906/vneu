<?php
// danh sach cac modul/action ko can kiem tra dang nhap
$arr_skip_check = array(
	'user/login',
	'user/dologin',
);

$current_modul_action = $fwModuleName.'/'.$fwAction;
if (!in_array($current_modul_action, $arr_skip_check)) 
{
	$user = new user_block();
	if (!$user->is_logined()) {
        $v_redirect = BASE_URL . 'user/login';
		$v_fwRequestUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
		$var = array('/index.php', '/index.php/', 'index.php/', $v_fwRequestUri, '//', '/index.php/');
		$v_fwRequestUri = str_replace($var, '/', $_SERVER['REQUEST_URI']);
		if ($v_fwRequestUri != '' && preg_match("/\/ajax/", $v_fwRequestUri) !== 1 || preg_match("/\/[a-zA-Z0-9\_]+\/act_.*?/", $v_fwRequestUri) !== 1) {
			$v_redirect_uri = base64_encode(urlencode(str_replace('ocm//', 'ocm/', BASE_URL . $v_fwRequestUri)));
			$_SESSION['redirect'] = $v_redirect_uri;
		}
		header('location: ' . $v_redirect);
		exit;	
	}
}