<?php
/**
 * lấy danh sách phong ban trong don vị
	Tham so: int $p_status : trang thai cua phong ban
 */ 
function user_all($username1)
{
    $sql = "SELECT * FROM users WHERE Username='".$username1."';";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

function be_get_all_users($p_username, $page, $number_items)
{
	$sql = "call be_get_all_users('$p_username', $page, $number_items)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}
function be_update_user_category($p_user_id, $p_category_id) 
{
	$sql = "call be_update_user_category($p_user_id, $p_category_id)";	
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}