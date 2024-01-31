<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Backend manager - 24h.com.vn</title>
<link href="<?php echo BASE_URL?>css/style.css" type="text/css" rel="stylesheet" />
</head>
<body style="background:#1f1f1f">
<div class="login-container">
    	<div class="login-top">
       	  <div class="login-logo"><a href="http:www.24h.com.vn" target="_blank"><img src="<?php echo BASE_URL?>images/login-logo.gif" /></a></div>
            <div class="login-text"><span>HỆ THỐNG</span><br/><span style="font-size:23px">QUẢN LÝ OCM 24H</span></div>
        </div>
		<form action="<?php echo BASE_URL;?>ajax/user/dologin.php" name="frmLogin" method="post" target="fr_submit">
        <div class="login-content">
        	<div class="login-note" id="login-note" style="display:none;"></div>
         	<table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr><td colspan="2" height="15"></td></tr>
            	<tr>
                	<td class="loginLabel" width="135" height="32">Tài khoản</td>
                    <td><input class="txt" name="username" maxlength="25" type="text"/></td>
                </tr>
                <tr>
                	<td class="loginLabel" height="32">Mật khẩu</td>
                    <td><input class="txt" name="password" maxlength="15" type="password"/></td>
                </tr>
                <tr>
                	<td class="loginLabel" height="40"></td>
                    <td><input type="image" src="<?php echo BASE_URL;?>images/btnlogin.gif" /></td>
                </tr>
            </table>
			<iframe name="fr_submit" width="0" height="0" style="visibility:hidden"></iframe>
        </div>		
		</form>
    	<div class="login-bottom"></div>
    </div>

</body>
</html>
