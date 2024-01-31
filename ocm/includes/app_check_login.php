<?php
echo $fwModuleName.'=fwModuleName',$fwAction.'=fwAction';
if ($fwAction != 'login' && $fwAction != 'dologin') {
	if (!isset($_SESSION['staff_id']) || ($_SESSION['staff_id'] =='')) {
		Header("Location:".BASE_URL."user/login"); exit;		
	}
} else {
echo 'khong vao vao dây ';//die;
}
