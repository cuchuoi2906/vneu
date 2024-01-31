<?php

/**
 * lấy danh sách chuyên mục để chọn khi cập nhật tin bài, lọc tin bài
 * @param int $p_active Tìm theo trạng thái xuất bản chuyên mục
					0: tất cả chuyên mục
					1: chỉ lấy chuyên mục đã xuất bản
		int	$p_user_id Tìm theo ID biên tập viên => chỉ hiển hiện chuyên mục do BTV quản lý
					-1: nếu không tìm theo BTV
		int	$p_is_link Tìm chuyên mục có phải dạng liên kết
					0: Không phải dạng liên kết
					1: Dạng liên kết 

 * @return array
 */
function be_get_all_category_by_select($p_active, $p_user_id, $p_is_link) 
{
	$sql = "call be_get_all_category_by_select($p_active ,$p_user_id, $p_is_link)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}
function be_get_all_category_khampha($p_user_id=-1) 
{
	// user id
	$p_user_id = intval($p_user_id);
	$sql = "call be_get_all_category_khampha($p_user_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * lấy danh thông tin chi tiết về chuyên mục
 * @param int $p_category_id ID chuyên mục				
 * @return array
 */
function be_get_single_category($p_category_id)
{
    /* begin 11/7/2017 TuyenNT fix_log_ngay_11_7_2017 */
    Gnud_Db_read_close();
    /* end 11/7/2017 TuyenNT fix_log_ngay_11_7_2017 */
    $sql = "call be_get_single_category($p_category_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lấy tên chuyên mục theo id
 * @param int $p_category_id ID chuyên mục				
 * @return array
 */
function get_category_name_by_id($p_category_id)
{
    $v_category = be_get_single_category($p_category_id);
    return $v_category['Name'];
}

/**
 * lay danh sach chuyen muc theo ID chuyen muc cap 1
 * @param varchar(255) $p_parent_ids Danh sach ID chuyen muc cap 1, cach nhau bang dau ','
 */
function be_get_all_category_by_parent($p_parent_ids)
{
    $sql = "call be_get_all_category_by_parent('$p_parent_ids')";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Ham thuc hien kiem tra 1 chuyen muc co duoc phep xoa hay khong
 * @param int $p_cat_id Id chuyen muc
 */
function be_is_valid_delete_category($p_cat_id) {
    $sql = "call be_is_valid_delete_category($p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
    return ($rs[0]['c_check']==0)? true: false;
    
}
/**
 * Ham thuc hien xoa vinh vien 1 chuyen muc
 * @param int $p_cat_id Id chuyen muc
 */
function be_delete_category($p_cat_id) {
    $sql = "call be_delete_category($p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Ham thuc cap nhat 1 chuyen muc
 * @param array $rs_data mang luu thong tin ve chuyen muc 
 */
function be_update_category($rs_data) {
    // Ép kiểu menu ngang footer ipad
    $rs_data['c_menu_ngang_footer_ipad'] = intval($rs_data['c_menu_ngang_footer_ipad']);
	// begin 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
    // begin 10-04-2017 : trungcq xu_ly_footer_ipad
    $sql = "call be_update_category(".$rs_data['ID'].""
			. ",'".$rs_data['Name']."'"
			. ",'".$rs_data['c_ascii_name']."'"
			. ",'".$rs_data['Link']."'"
			. ",".$rs_data['LinkType'].""
			. ",".$rs_data['Parent'].""
			. ",".$rs_data['Activate'].""
			. ",".$rs_data['Position'].""
			. ",".$rs_data['last_edit_id'].""
			. ",".$rs_data['footerOption'].""
			. ",".$rs_data['nhahang'].""
			. ",'".$rs_data['nhahang_image']."'"
			. ", ".$rs_data['c_menu_ngang_footer'].""
			. ", ".$rs_data['c_menu_ngang_footer_ipad'].""
			. ", '".$rs_data['c_ten_menu_ngang_footer']."'"
			. ", ".$rs_data['c_is_show_on_pc'].""
			. ", '".$rs_data['Urlslugs']."'"
			. ", '".$rs_data['c_anh_dai_dien']."'"
			. ", '".$rs_data['c_anh_chia_se_mxh']."'"
			. ")";
    // end 10-04-2017 : trungcq xu_ly_footer_ipad
    // end 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
 * Ham thuc cap nhat 1 menu mobile
 * @param array $rs_data mang luu thong tin ve chuyen muc 
 */
function be_update_mobile_menu($rs_data) {
    $sql = "call be_update_mobile_menu(".$rs_data['ID'].",".$rs_data['menuID'].",".$rs_data['onmobile'].",".$rs_data['onmobilemenu'].",'".$rs_data['menutext']."',".$rs_data['published'].",".$rs_data['mb_dropdown_position'].",".$rs_data['mb_position'].")";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0]['c_menu_id'];
}
/**
 * Ham thuc cap nhat 1 menu mobile
 * @param array $rs_data mang luu thong tin ve chuyen muc 
 */
function be_get_single_mobile_menu($p_category_id) {
    $sql = "call be_get_single_mobile_menu($p_category_id)";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Ham thuc cap nhat 1 menu mobile
 * @param array $rs_data mang luu thong tin ve chuyen muc 
 */
function be_reorder_category($p_category_id_above, $p_category_id_under) {
    $sql = "call be_reorder_category($p_category_id_above, $p_category_id_under)";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Ham lay danh sach
 * @param array $rs_data mang luu thong tin ve chuyen muc 
 */
//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
function be_get_all_category($p_cat_id, $p_user_id, $p_status_list, $p_cat_name, $p_is_dropdown_menu_mobile, $p_is_menu_mobile, $p_user_view, $p_trang_danh_ba = -1, $p_is_show_on_pc=-1) 
{
	$sql = "call be_get_all_category($p_cat_id ,$p_user_id, '$p_status_list', '$p_cat_name',$p_is_dropdown_menu_mobile, $p_is_menu_mobile, $p_user_view, $p_trang_danh_ba, $p_is_show_on_pc)";   
	$rs = Gnud_Db_read_query($sql);	
	return $rs;
}
//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc

/**
 * Lấy danh sach box chuyên mục
 * @param string $p_cat_id_list ds ID chuyên mục	
          string $p_box_id_list ds ID box
          string $p_user_id_list ds nguoi cap nhat cuoi
          int    $p_box_number vị trí hiển thị box
          int    $p_box_position vị trí hiển thị tab
          string $p_box_title tiều đề box
          int $p_page_number  trang can xem
          int $p_row_per_page so ban ghi tren/trang
 * @return array
 */
function be_get_all_home_index_box($p_cat_id_list = '', $p_box_id = 0,$p_user_id = 0, $p_box_number=-1, $p_box_position= -1, $p_box_title = '', $p_page_number = 1, $p_row_per_page= 500) {
    $sql = "call be_get_all_home_index_box('$p_cat_id_list' ,$p_box_id, $p_user_id, $p_box_number, $p_box_position, '$p_box_title',  $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}
/**
 * Lấy danh sach box chuyên mục trang chu mobile
 * @param string $p_cat_id_list ds ID chuyên mục	
          string $p_box_id_list ds ID box
          string $p_user_id_list ds nguoi cap nhat cuoi
          int    $p_box_number vị trí hiển thị box
          int    $p_box_position vị trí hiển thị tab
          string $p_box_title tiều đề box
          int $p_page_number  trang can xem
          int $p_row_per_page so ban ghi tren/trang
 * @return array
 */
function be_get_all_home_index_box_mb($p_cat_id_list = '', $p_box_id =0,$p_user_id = 0, $p_box_number=-1, $p_box_position= -1, $p_box_title = '', $p_page_number = 1, $p_row_per_page= 500) {
    $sql = "call be_get_all_home_index_box_mb('$p_cat_id_list' ,$p_box_id, $p_user_id, $p_box_number, $p_box_position, '$p_box_title',  $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}
/**
 * Lấy danh sach box chuyên mục trang chu ipad
 * @param string $p_cat_id_list ds ID chuyên mục	
          string $p_box_id_list ds ID box
          string $p_user_id_list ds nguoi cap nhat cuoi
          int    $p_box_number vị trí hiển thị box
          int    $p_box_position vị trí hiển thị tab
          string $p_box_title tiều đề box
          int $p_page_number  trang can xem
          int $p_row_per_page so ban ghi tren/trang
 * @return array
 */
function be_get_all_home_index_box_ipad($p_cat_id_list = '', $p_box_id = 0,$p_user_id = 0, $p_box_number=-1, $p_box_position= -1, $p_box_title = '', $p_page_number = 1, $p_row_per_page= 500) {
    $sql = "call be_get_all_home_index_box_ipad('$p_cat_id_list',$p_box_id, $p_user_id, $p_box_number, $p_box_position, '$p_box_title',  $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}
/**
 * Lấy chi tiết một box
 * @param int $p_box_id ID box				
 * @return array
 */
function be_get_single_home_index_box($p_box_id) {
    $sql = "call be_get_single_home_index_box($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lấy chi tiết một box trang chủ mobile
 * @param int $p_box_id ID box				
 * @return array
 */
function be_get_single_home_index_box_mb($p_box_id) {
    $sql = "call be_get_single_home_index_box_mb($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lấy chi tiết một box trang chủ ipad
 * @param int $p_box_id ID box				
 * @return array
 */
function be_get_single_home_index_box_ipad($p_box_id) {
    $sql = "call be_get_single_home_index_box_ipad($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Cập nhật một box
 * params: p_box_id int : ID box
    p_box_title	Varchar(255) : Tieu de box
    p_box_category	int : ID chuyen muc
    p_box_number	Int : Vi tri box
    p_box_position	Int : Vi tri tab
    p_last_editor	Int : ID nguoi cap nhat			
 * @return array
 */
function be_update_home_index_box($data) {
    $v_box_id = $data['box_id'];
    $v_box_title = $data['box_title'];
    $v_box_ascii_title = $data['box_ascii_title'];
    $v_box_category = $data['box_category'];
    $v_box_number = $data['box_number'];
    $v_box_position = $data['box_position'];
    $v_last_editor = $data['last_editor'];
    /*Begin 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
    $v_type_link = intval($data['type_link']);
    $v_url = $data['url'];
    $v_open_new_tab = intval($data['open_new_tab']);
    $sql = "call be_update_home_index_box($v_box_id, '$v_box_title', '$v_box_ascii_title', $v_box_category, $v_box_number, $v_box_position, $v_last_editor, $v_type_link, '$v_url', $v_open_new_tab)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
    /*End 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
}

/**
 * Cập nhật một box trang chủ mobile
 * params: p_box_id int : ID box
    p_box_title	Varchar(255) : Tieu de box
    p_box_category	int : ID chuyen muc
    p_box_number	Int : Vi tri box
    p_box_position	Int : Vi tri tab
    p_last_editor	Int : ID nguoi cap nhat			
 * @return array
 */
function be_update_home_index_box_mb($data) {
    $v_box_id = $data['box_id'];
    $v_box_title = $data['box_title'];
    $v_box_ascii_title = $data['box_ascii_title'];
    $v_box_category = $data['box_category'];
    $v_box_number = $data['box_number'];
    $v_box_position = $data['box_position'];
    $v_last_editor = $data['last_editor'];
    /*Begin 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
    $v_type_link = intval($data['type_link']);
    $v_url = $data['url'];
    $v_open_new_tab = intval($data['open_new_tab']);
    	//Begin 17/3/2020 AnhTT bo_sung_tab_su_kien_dac_biet
    $v_hien_thi_cap1 = $data['v_hien_thi_cap1'];
    $v_link_img_logo = $data['v_link_img_logo'];
    $v_vi_tri_logo = $data['v_vi_tri_logo'];
    $v_mau_nen = $data['v_mau_nen'];
    $v_mau_text = $data['v_mau_text'];
    $v_hen_gio_xb = $data['v_hen_gio_xb'];
    $v_xuat_ban_tu_ngay = $data['v_xuat_ban_tu_ngay'];
    $v_xuat_ban_den_ngay = $data['v_xuat_ban_den_ngay'];
    $sql = "call be_update_home_index_box_mb($v_box_id, '$v_box_title','$v_box_ascii_title', $v_box_category, $v_box_number, $v_box_position, $v_last_editor, $v_type_link, '$v_url', $v_open_new_tab, $v_hien_thi_cap1, '$v_link_img_logo', $v_vi_tri_logo, '$v_mau_nen','$v_mau_text',$v_hen_gio_xb, '$v_xuat_ban_tu_ngay', '$v_xuat_ban_den_ngay')";
	//Begin 17/3/2020 AnhTT bo_sung_tab_su_kien_dac_biet
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
    /*End 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
}
/**
 * Cập nhật một box trang chủ ipad
 * params: p_box_id int : ID box
    p_box_title	Varchar(255) : Tieu de box
    p_box_category	int : ID chuyen muc
    p_box_number	Int : Vi tri box
    p_box_position	Int : Vi tri tab
    p_last_editor	Int : ID nguoi cap nhat			
 * @return array
 */
function be_update_home_index_box_ipad($data) {
    $v_box_id = $data['box_id'];
    $v_box_title = $data['box_title'];
    $v_box_ascii_title = $data['box_ascii_title'];
    $v_box_category = $data['box_category'];
    $v_box_number = $data['box_number'];
    $v_box_position = $data['box_position'];
    $v_last_editor = $data['last_editor'];/*Begin 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
    $v_type_link = intval($data['type_link']);
    $v_url = $data['url'];
    $v_open_new_tab = intval($data['open_new_tab']);
    $sql = "call be_update_home_index_box_ipad($v_box_id, '$v_box_title','$v_box_ascii_title', $v_box_category, $v_box_number, $v_box_position, $v_last_editor, $v_type_link, '$v_url', $v_open_new_tab)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
    /*End 18-08-2017 trungcq XLCYCMHENG_24180_nang_cap_chuc_nang_quan_tri_box_chuyen_muc*/
}
/**
 * xóa dữ liệu một box
 * params: p_box_id int : ID box
 * @return array
 */
function be_delete_home_index_box($p_box_id) {
    $sql = "call be_delete_home_index_box($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * xóa dữ liệu một box trang chủ mobile
 * params: p_box_id int : ID box
 * @return array
 */
function be_delete_home_index_box_mb($p_box_id) {
    $sql = "call be_delete_home_index_box_mb($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * xóa dữ liệu một box trang chủ ipad
 * params: p_box_id int : ID box
 * @return array
 */
function be_delete_home_index_box_ipad($p_box_id) {
    $sql = "call be_delete_home_index_box_ipad($p_box_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lấy danh sach menu ngang
 * @param string $p_cat_id_list ds ID chuyên mục	         
          string $p_user_id_list ds nguoi cap nhat cuoi
          string $p_status_list ds trang thai xuat ban
          string $p_menu_name tên menu ngang          
 * @return array
 */
function be_get_all_menu_ngang($p_cat_id_list, $p_user_id, $p_status_list = '', $p_menu_name = '') {
    $sql = "call be_get_all_menu_ngang('$p_cat_id_list' ,$p_user_id, '$p_status_list', '$p_menu_name')";
	$rs = Gnud_Db_read_query($sql);	
	return $rs;
}
/**
 * Lấy thong tin chi tiet 1 menu
 * @param int $p_menu_id  id chuyen muc
 * @return array
 */
function be_get_single_menu_ngang($p_menu_id) {
    $sql = "call be_get_single_menu_ngang($p_menu_id)";
	$rs = Gnud_Db_read_query($sql);	
	return $rs[0];
}
/**
 * Lấy danh sach chuyen muc xuat ban cua 1 menu ngang
 * @param int $p_menu_id id menu
 * @return array
 */
function be_get_all_menu_ngang_published($p_menu_id) {
    $sql = "call be_get_all_menu_ngang_published($p_menu_id)";
	$rs = Gnud_Db_read_query($sql);	
	return $rs;
}
/**
 * Lấy danh sach chuyen muc xuat ban cua 1 menu ngang
 * @param array $rs_data mang chua thong tin chi tiet 1 menu
 * @return array
 */
function be_update_menu_ngang($rs_data) {
    $v_menu_id = $rs_data['id'];
    $v_name = $rs_data['name'];
    $v_name_ascii = $rs_data['name_ascii'];
    $v_title = $rs_data['title'];
    $v_url = $rs_data['url'];
    $v_editor_id = $rs_data['editor_id'];
    $v_order = $rs_data['order'];
    $v_published = $rs_data['published'];
    $v_target = $rs_data['target'];
    $v_url_icon = $rs_data['url_icon'];    
    $sql = "call be_update_menu_ngang($v_menu_id ,'$v_name','$v_name_ascii','$v_title', '$v_url', $v_editor_id, $v_order, $v_published , $v_target, '$v_url_icon')";
	$rs = Gnud_Db_read_query($sql);	
	return $rs[0];
}
/**
 * Cap nhat danh sach chuyen muc xuat ban cua 1 menu ngang
 * @param array $rs_data mang chua thong tin
 * @return array
 */
function be_publish_menu_ngang($rs_data) {
    $v_menu_id = $rs_data['menu_id'];
    $v_category_id = $rs_data['cat_id'];
    $v_order = $rs_data['order'];
    $v_published = $rs_data['published'];
    $v_editor_id = $rs_data['editor_id'];
    $sql = "call be_publish_menu_ngang( $v_menu_id ,$v_category_id, $v_order, $v_published, $v_editor_id)";
    $rs = Gnud_Db_read_query($sql);	
    echo '<br>'.$sql;
	return $rs[0]['RET_ERROR'];
}

/**
 * Cap nhat danh sach chuyen muc xuat ban cua 1 menu ngang
 * @param array $rs_data mang chua thong tin
 * @return array
 */
function be_delete_menu_ngang_published($p_menu) {
    $sql = "call be_delete_menu_ngang_published($p_menu)";
    $rs = Gnud_Db_read_query($sql);	
	return $rs[0]['RET_ERROR'];
}
/**
 * Xoa 1 menu ngang
 * @param int p_menu_id
 * @return array
 */
function be_delete_menu_ngang($p_menu_id) {
    $sql = "call be_delete_menu_ngang($p_menu_id)";
	$rs = Gnud_Db_read_query($sql);	
	return $rs[0];
}

/**
 * Lay chuyen muc hien thi tren menu trang chu
 * @param int p_category_id
 * @return array
 */
function be_get_single_category_home($p_category_id) {
    $sql = "call be_get_single_category_home($p_category_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}


function be_update_order_menu_mobile($p_menu_id, $p_order, $p_menu_type) {
	$sql = "call be_update_order_menu_mobile($p_menu_id, $p_order, '$p_menu_type')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Kiem tra trung vi tri box, vi tri tab chuyen muc trang chu web voi 1 box da tao thanh cong truoc do
 * @param int p_box_id ID box
 * @param int p_box_number Vi tri box
 * @param int p_box_position vi tri tab
 * @return array
 */
function be_check_duplicate_home_index_box($p_box_id, $p_box_number, $p_box_position) {
	$sql = "call be_check_duplicate_home_index_box($p_box_id, $p_box_number, $p_box_position)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Kiem tra trung vi tri box, vi tri tab chuyen muc trang chu mobile voi 1 box da tao thanh cong truoc do
 * @param int p_box_id ID box
 * @param int p_box_number Vi tri box
 * @param int p_box_position vi tri tab
 * @return array
 */
function be_check_duplicate_home_index_box_mb($p_box_id, $p_box_number, $p_box_position) {
	$sql = "call be_check_duplicate_home_index_box_mb($p_box_id, $p_box_number, $p_box_position)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Kiem tra trung vi tri box, vi tri tab chuyen muc trang chu ipad voi 1 box da tao thanh cong truoc do
 * @param int p_box_id ID box
 * @param int p_box_number Vi tri box
 * @param int p_box_position vi tri tab
 * @return array
 */
function be_check_duplicate_home_index_box_ipad($p_box_id, $p_box_number, $p_box_position) {
	$sql = "call be_check_duplicate_home_index_box_ipad($p_box_id, $p_box_number, $p_box_position)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
// begin 18/10/2016 TuyenNT bo_sung_chuc_nang_cau_hinh_chuyen_muc_nhan_mail_nv_seo
/**
 * Hien thi danh sach user de phan quyen chuyen muc SEO
 * @p_unit_id int : ID phong ban
 * @p_user_id id: ID user
 * @p_category_id int : ID chuyen muc
 * @p_editor_id int : ID nguoi sua cuoi
 * @p_page_number int : Hien thi trang so may
 * @p_row_per_page int : So luong ban ghi tren 1 trang
 * @return array
 */
function be_get_all_user_category_seo($p_unit_id, $p_user_id, $p_category_id, $p_editor_id, $p_page_number, $p_row_per_page) 
{
	$sql = "call be_get_all_user_category_seo($p_unit_id, $p_user_id, $p_category_id, $p_editor_id, $p_page_number, $p_row_per_page)";	
	$rs = Gnud_Db_read_query($sql); 
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * lấy danh sách chuyên mục để chọn khi cập nhật tin bài, lọc tin bài
 * @param int $p_active Tìm theo trạng thái xuất bản chuyên mục
        0: tất cả chuyên mục
        1: chỉ lấy chuyên mục đã xuất bản
    int	$p_user_id Tìm theo ID biên tập viên => chỉ hiển hiện chuyên mục do BTV quản lý
        -1: nếu không tìm theo BTV
    int	$p_is_link Tìm chuyên mục có phải dạng liên kết
        0: Không phải dạng liên kết
        1: Dạng liên kết 
 * @return array
 */
function be_get_all_category_seo_by_select($p_active, $p_user_id, $p_is_link) 
{
	$sql = "call be_get_all_category_seo_by_select($p_active ,$p_user_id, $p_is_link)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Cập nhat chuyen muc phan quyen seo
 * @param 
    * @p_user_id id: ID user
    * @p_category_id int : ID chuyen muc
 * @return array
 */
function be_update_user_category_seo($p_user_id, $p_category_id) 
{
	$sql = "call be_update_user_category_seo($p_user_id, $p_category_id)";	
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}

/**
 * Xoa chuyen muc phan quyen seo
 * @param 
    * $p_where string dieu kien xoa
 * @return array
 */
function be_user_category_seo_delete_by_where($p_where) 
{
	$sql = "CALL delete_data('user_category_seo', '$p_where')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Xoa chuyen muc phan quyen seo
 * @param 
    * @p_user_id id: ID user
 * @return array
 */
function be_delete_user_category_seo($p_user_id) 
{
	$sql = "CALL be_delete_user_category_seo($p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}
// end 18/10/2016 TuyenNT bo_sung_chuc_nang_cau_hinh_chuyen_muc_nhan_mail_nv_seo

/* Begin 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */
/*
 * hàm lấy danh sách chuyên mục theo user_id và mã website
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 6-11-2018
 * @param:
 *  $p_user_id      User id
 *  $p_code         Mã website
 * return array
 *  */
function be_get_all_category_partners($p_user_id=-1, $p_code = '') 
{
	$sql = "call be_get_all_category_partners($p_user_id, '$p_code')";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/* End 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */















