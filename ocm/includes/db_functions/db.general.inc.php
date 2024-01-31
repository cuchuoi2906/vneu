<?php

/**
 * Tra lai danh sach cac trang thai cua 1 dong trong list table
 */
function get_list_pr_device()
{
	$v_arr = array();
	$v_arr[] = array('c_code'=>'1', 'c_name'=>'Web');
	$v_arr[] = array('c_code'=>'2', 'c_name'=>'Ipad');
	$v_arr[] = array('c_code'=>'3', 'c_name'=>'Mobile');
	return $v_arr;
}
/**
 * Lay danh sach menu backend
 * @param : 
 * @return array
 */
function be_menu_get_all()
{
	include_once (WEB_ROOT. 'includes/menu.php');
	$rs = array();
	$rs['arr_menu_box'] = $arr_menu_box;
	$rs['arr_menu_item'] = $arr_menu_item;
	return $rs;
}
function _check_permision($p_permission_code) {
	return true;
}

/**
 * Tra lai danh sach loai trang thai xuat ban
 */
function get_list_trang_thai_xuat_ban()
{
    $v_arr = array();
    $v_arr[] = array('c_code'=>'0', 'c_name'=>'Chưa xuất bản');
    $v_arr[] = array('c_code'=>'1', 'c_name'=>'Đã xuất bản');
    return $v_arr;
}

 /**
* Luu lich su thay doi cua doi tuong
* @param string $p_khoa ID cua doi tuong thay doi
* @param string $p_loai Loai doi tuong (news, category)
* @param array $p_dulieu Du lieu truoc thay doi
* @param string $p_nguoi_tao username nguoi thay doi
* @return array
*/
function be_cap_nhat_lich_su($p_khoa, $p_loai, $p_dulieu, $p_nguoi_tao){
    $p_dulieu = addslashes(json_encode($p_dulieu));
    $sql = "call be_cap_nhat_lich_su('$p_khoa', '$p_loai', '$p_dulieu', '$p_nguoi_tao')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/**
* Lay danh sach lich su thay doi cua doi tuong
* @param string $p_key ID cua doi tuong can lay
* @param string $p_loai Loai doi tuong (news, category)
* @return array
*/
function be_danh_sach_lich_su($p_key, $p_loai, $page, $number_per_page) {
    $sql = "call be_danh_sach_lich_su('$p_key', '$p_loai', $page, $number_per_page)";
    $rs = Gnud_Db_read_query($sql);
    $data = $rs['record0'];
    $v_count = count($data);
    if (is_array($data) && $v_count > 0) {
        for ($i=0;$i<$v_count;$i++) {
            //$data[$i]['du_lieu'] = json_decode($data[$i]['du_lieu']);
        }
    }
    $return['data'] = $data;
    $return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
    return $return;
}

/**
* Lay chi tiet lich su thay doi
* @param int $p_history_id ID cua lich su
* @return array
*/
function be_chi_tiet_lich_su($p_history_id) {
    $sql = "call be_chi_tiet_lich_su($p_history_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
function be_update_data($p_table, $p_column_name, $p_value, $p_where) {
    $sql = "call update_data('$p_table', '$p_column_name', '$p_value', '$p_where')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/**
 * Lay ten BTV
 * @param int $editor_id ID của BTV
 * @return string Tên BTV
 */
function get_name_of_editor($editor_id)
{
    global $V_ARR_USERS;
    if (!is_array($V_ARR_USERS)) {
        $V_ARR_USERS = be_get_all_users('', 1, 2000);
        $V_ARR_USERS = $V_ARR_USERS['data'];
    }
    $name = get_name_in_array($V_ARR_USERS, 'ID', 'Username', $editor_id);
    if($name == '' && $editor_id == -999){
        $name = 'Danviet';
    }
    return $name;
}
/**
 * Tra lai text cua column $p_column_name có $p_column_id là $p_value_id trong array $p_array
 */
function get_name_in_array ($p_array, $p_column_id='c_code', $p_column_name='c_name', $p_value_id)
{
    if (is_array($p_array) && count($p_array) > 0) {
        foreach ($p_array as $v_row) {
            if ($v_row[$p_column_id] == $p_value_id) {
                return $v_row[$p_column_name] ;
            }
        }
    }
    return '';
}
/**
 * Tra lai danh sach trang thai su dung giao dien poll
 */
function get_list_trang_thai_su_dung()
{
	$v_arr = array();
	$v_arr[] = array('c_code'=>'0', 'c_name'=>'Chưa sử dụng');
	$v_arr[] = array('c_code'=>'1', 'c_name'=>'Đã sử dụng');
	return $v_arr;
}

/**
 * Tra lai danh sach loai trang thai xuat ban
 */
function get_list_bai_tham_khao_magazine()
{
    $v_arr = array();
    $v_arr[] = array('c_code'=>'0', 'c_name'=>'Chưa tích chọn');
    $v_arr[] = array('c_code'=>'1', 'c_name'=>'Đã tích chọn');
    return $v_arr;
}