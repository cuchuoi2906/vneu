<?php
error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
//Khai bao su dung cac ham dung chung
define('WEB_ROOT', str_replace('/includes','/',realpath(dirname(__FILE__))));
include 'app_common.php';

// Goi khi click vao 1 nhac
function server_ClickedMediaUpdate($p_media_id,$p_box_code,$p_box_id,$p_ip_address,$p_account_name){
	$rs=Gnud_Db_write_query("Call ClickedMediaUpdate($p_media_id,'$p_box_code',$p_box_id,'$p_ip_address','$p_account_name')");
	if (is_object($rs)){
		$arr_result = mysqli_fetch_array($rs,MYSQLI_ASSOC);
		$ret_error = "";
		if (isset($arr_result['RET_ERROR'])){
			$ret_error = (trim($arr_result['RET_ERROR']));
			if ($ret_error!=""){
				$log_error = date( 'Y-m-d H:i:s').":Call ClickedMediaUpdate($p_media_id,'$p_box_code',$p_box_id,'$p_ip_address','$p_account_name'):".$ret_error;
				error_log($log_error,3,'../logs/master_update.log');	
				return $ret_error;
			}
		}	
	}	
	return '';
}

ini_set("soap.wsdl_cache_enabled", "0");
$server = new SoapServer(null, array('uri' => MASTER_URL."includes/"));
$server->addFunction("server_ClickedMediaUpdate");
$server->handle();
?>