<?php

/**
* Lay danh sach textlink
* @param string p_cat_id Tim theo id chuyen muc
	string	$p_user_id Tim theo id user sua
	string  $p_status_list Tim theo danh sách trạng thái
	string  $p_link_type_list Tim theo danh sách loại chuyên mục
	string  $p_ascii_nanme Tìm theo tên không dấu
	int		$p_news_id tìm theo id bài viét
	int		$p_event_id tìm theo id sự kiện
	string  $p_start_from_date Tim theo ngày xuất bản từ ngày
	string  $p_start_to_date Tim theo ngày xuất bản đến ngày
	string  $p_end_from_date Tim theo ngày kết thúc từ ngày
	string  $p_end_to_date Tim theo ngày kết thúc đến ngày
	string  $p_orderby_clause mệnh đề order by
	int     $p_thiet_bi id thiet bi    
* @return array
*/

function be_get_all_seo_textlink($p_cat_id, $p_user_id, $p_status_list, $p_link_type_list, $p_ascii_nanme, $p_news_id, $p_event_id, $p_profile_id, $p_start_from_date, $p_start_to_date, $p_end_from_date, $p_end_to_date, $p_orderby_clause, $p_thiet_bi, $p_page, $p_number_item_per_page) 
{
	$sql = "call be_get_all_seo_textlink('$p_cat_id', $p_user_id, '$p_status_list', '$p_link_type_list', '$p_ascii_nanme', $p_news_id, $p_event_id, $p_profile_id, '$p_start_from_date','$p_start_to_date','$p_end_from_date','$p_end_to_date' ,  '$p_orderby_clause', $p_thiet_bi, $p_page, $p_number_item_per_page)";
	//echo $sql;
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
* Lay thong tin chi tiet 1 textlink
* @param int $p_textlink_id
* @return array
*/ 
function be_get_single_seo_textlink($p_textlink_id) 
{
	$sql = "call be_get_single_seo_textlink($p_textlink_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung chuyen muc xuat ban cho textlink
* @param   
		int p_textlink_id		 : id textlink
		int p_catgory_id 		 : id chuyen muc, id bai viet, id su kien
		int p_textlink_type 	 : loai text link
		string p_publish_from_date 	: ngay xuat ban bat dau
		int     $p_thiet_bi id thiet bi   
* @return array
*/
function be_check_duplicate_textlink_category($p_textlink_id, $p_catgory_id, $p_textlink_type, $p_publish_from_date, $p_thiet_bi) 
{
	$sql = "call be_check_duplicate_textlink_category($p_textlink_id, $p_catgory_id, $p_textlink_type, '$p_publish_from_date', $p_thiet_bi)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
} 

/**
* Kiem tra trung bai viet xuat ban cho textlink
* @param   
		int p_textlink_id		 : id textlink
		int p_news_id 		     : id bai viet
		int p_textlink_type 	 : loai text link
		string p_publish_from_date 	: ngay xuat ban bat dau
		int     $p_thiet_bi id thiet bi   
* @return array
*/
function be_check_duplicate_textlink_news($p_textlink_id, $p_news_id, $p_textlink_type, $p_publish_from_date, $p_thiet_bi) 
{
	$sql = "call be_check_duplicate_textlink_news($p_textlink_id, $p_news_id, $p_textlink_type, '$p_publish_from_date',  $p_thiet_bi)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung su kien xuat ban cho textlink
* @param   
		int p_textlink_id		 : id textlink
		int p_event_id 		     : id su kien
		int p_textlink_type 	 : loai text link
		string p_publish_from_date 	: ngay xuat ban bat dau
* @return array
*/
function be_check_duplicate_textlink_event($p_textlink_id, $p_event_id, $p_textlink_type, $p_publish_from_date, $p_thiet_bi) 
{
	$sql = "call be_check_duplicate_textlink_event($p_textlink_id, $p_event_id, $p_textlink_type, '$p_publish_from_date', $p_thiet_bi)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* cap nhat 1 text link
* @param   array $p_data luu thong tin ve 1 textlink
* @return array
*/
function be_update_seo_text_link($p_data)
{
    $v_textlink_id	= $p_data['id'];
	$v_name	= $p_data['c_ten'];
	$v_ascii_name	= $p_data['c_ten_khong_dau'];
	$v_content	= $p_data['c_noi_dung'];
	$v_type	= $p_data['c_loai'];
	$v_publish_from_date = $p_data['c_tu_ngay'];
	$v_publish_to_date	= $p_data['c_den_ngay'];
	$v_publish_status	= $p_data['c_trang_thai_xuat_ban'];
	$v_edited_user	= $p_data['c_nguoi_sua'];
	$v_created_user = $p_data['c_nguoi_tao'];
	$v_thiet_bi = $p_data['c_thiet_bi'];
	$sql = "call be_update_seo_text_link($v_textlink_id, '$v_name', '$v_ascii_name', '$v_content', $v_type,'$v_publish_from_date','$v_publish_to_date',$v_publish_status,'$v_edited_user','$v_created_user', $v_thiet_bi)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0]['c_id'];
}

/**
* Xuat ban textlink cho chuyen muc
* @param   int $p_textlink_id id textlink
* @param   int $p_category_id id chuyen muc
* @return array
*/
function be_update_text_link_category($p_textlink_id, $p_category_id){
	$sql = "call be_update_text_link_category($p_textlink_id, $p_category_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xuat ban textlink cho bai viet
* @param   int $p_textlink_id id textlink
* @param   int $p_news_id id bai viet
* @return array
*/
function be_update_text_link_news($p_textlink_id, $p_news_id){
	$sql = "call be_update_text_link_news($p_textlink_id, $p_news_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xuat ban textlink cho su kien
* @param   int $p_textlink_id id textlink
* @param   int $p_event_id id bai viet
* @return array
*/
function be_update_text_link_event($p_textlink_id, $p_event_id){
	$sql = "call be_update_text_link_event($p_textlink_id, $p_event_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xóa 1 text link
* @param   int p_textlink_id		-- : id textlink
* @return array
*/
function be_delete_seo_text_link($p_textlink_id){	
	$sql = "call be_delete_seo_text_link($p_textlink_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
	
}

/**
* Xóa 1 text link xuat ban tren chuyen muc
* @param   int p_textlink_id		-- : id textlink
* @return array
*/
function be_delete_seo_text_link_category($p_textlink_id){	
	$sql = "call be_delete_seo_text_link_category($p_textlink_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
* Xóa 1 text link xuat ban tren bai viet
* @param   int p_textlink_id		-- : id textlink
* @return array
*/
function be_delete_seo_text_link_news($p_textlink_id){	
	$sql = "call be_delete_seo_text_link_news($p_textlink_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
* Xóa 1 text link xuat ban tren bai viet
* @param   int p_textlink_id		-- : id textlink
* @return array
*/
function be_delete_seo_text_link_event($p_textlink_id){	
	$sql = "call be_delete_seo_text_link_event($p_textlink_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
* Lay danh sach textlink xuat ban 
* @param   int p_textlink_id		-- : id textlink
* @param   string p_table_suffix		-- : phan hau to cua cac bang xuat ban textlink co cac gia tri sau: event, news, category
* @return array
*/
function be_get_all_seo_text_link_publish($p_textlink_id, $p_table_suffix){	
	$sql = "call be_get_all_seo_text_link_publish($p_textlink_id, '$p_table_suffix')";	
	$rs = Gnud_Db_read_query($sql);
	return $rs;	
}

/**
* Lay danh sach seo chi tiet
* @param 	string p_cat_id_list  danh sach id chuyen muc
* @param 	string p_user_id_list  danh sach user
* @param    string p_status_list  danh sach trang thai
* @param 	string p_ten_khong_dau  
* @param    int p_category_id 	
* @param 	string p_orderby_clause 
* @param 	int p_thiet_bi 
* @param 	int p_page 
* @param    int p_number_item_per_page 
* @param    varchar(4000) p_ma_ga mã GA cần tìm
* @return array
*/
function be_get_all_seo_chi_tiet_chuyen_muc($p_cat_id_list, $p_user_id_list, $p_status_list, $p_category_id, $p_orderby_clause, $p_thiet_bi, $p_page, $p_number_item_per_page, $p_ma_ga = ''){	
	$sql = "call be_get_all_seo_chi_tiet_chuyen_muc('$p_cat_id_list', '$p_user_id_list', '$p_status_list', $p_category_id, '$p_orderby_clause', $p_thiet_bi, $p_page, $p_number_item_per_page, '$p_ma_ga')";	
	$rs = Gnud_Db_read_query($sql);
	for ($i = 0, $s = sizeof($rs); $i < $s; ++$i){
		$rs[$i]['arr_ga'] = be_danh_sach_ga(-1, -1, $rs[$i]['pk_category'], -1, '', '', -1, -1);
	}
	return $rs;	
}

/**
* Lay thong tin 1 seo chi tiet theo chuyen muc
* @param 	int p_seo_chi_tiet_chuyen_muc Id seo chi tiet chuyen muc
* @return array
*/
function be_get_single_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc){	
	$sql = "call be_get_single_seo_chi_tiet_chuyen_muc('$p_seo_chi_tiet_chuyen_muc')";	
	$rs = Gnud_Db_read_query($sql);
	if (intval($rs[0]['pk_category']) > 0){
		$rs[0]['arr_ga'] = be_danh_sach_ga(-1, -1, $rs[0]['pk_category'], -1, '', '', -1, -1);
	}
	return $rs[0];	
}

/**
 * Cap nhat thong tin 1 seo chi tiet cho chuyen muc/id
 * @param	int p_seo_chi_tiet_chuyen_muc	
 * @param	int p_category_id	
 * @param	string p_gacode	
 * @param	string p_gacode_video	
 * @param 	string p_sapo	
 * @param 	string p_title	
 * @param   string p_desc	
 * @param   string p_keyword	
 * @param   string p_slug	
 * @param   string p_canonical	
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_nguoi_sua	
 * @param 	string p_tu_khoa_in_nghieng	
 * @param 	string p_tu_khoa_in_dam
 * @param 	string p_tu_khoa_gach_chan
 * @param 	string p_fanpage_facebook
 * @param 	int p_thiet_bi
 * @return array
 */
function be_update_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc, $p_category_id, $p_gacode, $p_sapo, $p_title, $p_desc, $p_keyword, $p_slug, $p_canonical, $p_trang_thai_xuat_ban, $p_nguoi_sua, $p_tu_khoa_in_nghieng, $p_tu_khoa_in_dam, $p_tu_khoa_gach_chan, $p_fanpage_facebook, $p_thiet_bi,$p_script_marketing){	
	$sql = "call be_update_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc, $p_category_id, '$p_gacode', '$p_sapo', '$p_title', '$p_desc', '$p_keyword', '$p_slug', '$p_canonical',  $p_trang_thai_xuat_ban, $p_nguoi_sua, '$p_tu_khoa_in_nghieng', '$p_tu_khoa_in_dam', '$p_tu_khoa_gach_chan', '$p_fanpage_facebook', $p_thiet_bi, '$p_script_marketing')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Cap nhat thong tin 1 seo chi tiet cho chuyen muc/id
 * @param	int p_seo_chi_tiet_chuyen_muc	 
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_nguoi_sua	
 * @return array
 */
function be_update_trang_thai_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc, $p_trang_thai_xuat_ban, $p_nguoi_sua){	
	$sql = "call be_update_trang_thai_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc, $p_trang_thai_xuat_ban, $p_nguoi_sua)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
* Xoa thong tin 1 seo chi tiet cho chuyen muc
* @param  int p_seo_chi_tiet_chuyen_muc: id seo chi tiet chuyen muc 
* @return array
*/
function be_delete_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc){	
	$sql = "call be_delete_seo_chi_tiet_chuyen_muc($p_seo_chi_tiet_chuyen_muc)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
	UC quan tri title-desc-keyword-slug cho chuyen muc
*/

/**
* Lay danh sach seo chi tiet title, desc, keyword cho su kien
* @param 	string p_cat_id_list  id chuyen muc xuat ban su kien
* @param 	string p_user_id  id nguoi sua
* @param    string p_status_list  danh sach trang thai
* @param    string p_event_type_list  danh sach loai su kien
* @param	int p_event_id id su kien
* @param 	string p_event_name  
* @param 	string p_orderby_clause 
* @param 	int p_thiet_bi
* @param 	int p_page 
* @param   int p_number_item_per_page
* @param    varchar(4000) p_ma_ga mã GA cần tìm
* @return array
*/
function be_get_all_seo_chi_tiet_su_kien($p_cat_id_list, $p_user_id, $p_status_list, $p_event_type_list, $p_event_id, $p_event_name, $p_orderby_clause, $p_thiet_bi, $p_page, $p_number_item_per_page, $p_ma_ga = ''){	
	$sql = "call be_get_all_seo_chi_tiet_su_kien('$p_cat_id_list', '$p_user_id', '$p_status_list','$p_event_type_list', $p_event_id,'$p_event_name','$p_orderby_clause', $p_thiet_bi, $p_page, $p_number_item_per_page, '$p_ma_ga')";	
	$rs = Gnud_Db_read_query($sql);
	for ($i = 0, $s = sizeof($rs); $i < $s; ++$i){
		$rs[$i]['arr_ga'] = be_danh_sach_ga(-1, 3, $rs[$i]['pk_event'], -1, '', '', -1, -1);
	}
	return $rs;	
}

/**
 * Lay thong tin 1 seo chi tiet cho su kien
 * @param 	int p_category Id chuyen muc
 * @return array
 */
function be_get_single_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien_id){	
	$sql = "call be_get_single_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien_id)";	
	$rs = Gnud_Db_read_query($sql);
	if (intval($rs[0]['pk_event']) > 0){
		$rs[0]['arr_ga'] = be_danh_sach_ga(-1, 3, $rs[0]['pk_event'], -1, '', '', -1, -1);
	}
	return $rs[0];	
}

/**
 * Cap nhat thong tin 1 seo chi tiet title, desc, keyword cho su kien
 * @param	int p_event:  ID su kien	
 * @param	string p_gacode :	 GA code
 * @param 	string p_sapo : Dong dau sapo bai viet
 * @param 	string p_title	: Template cho title
 * @param   string p_desc	: Template cho description 
 * @param   string p_keyword : Template cho keyword	
 * @param   string p_slug	 : Template cho slug
 * @param   string p_canonical	: Canonical 
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_nguoi_sua	
 * @param 	int p_seo_template 	
 * @param   string p_tu_khoa_in_nghieng
 * @param   string p_tu_khoa_in_dam
 * @param   string p_tu_khoa_gach_chan
 * @param   int p_thiet_bi
 * @return array
 */
// begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh 
function be_update_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien,$p_event, $p_gacode, $p_sapo, $p_title, $p_desc	, $p_keyword, $p_slug, $p_canonical, $p_trang_thai_xuat_ban, $p_nguoi_sua, $p_seo_template, $p_tu_khoa_in_nghieng= '', $p_tu_khoa_in_dam= '', $p_tu_khoa_gach_chan='', $p_chi_cap_nhat_keyword = 0, $p_thiet_bi = 1, $p_title_mxh, $p_des_mxh){	
	$sql = "call be_update_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien, $p_event, '$p_gacode', '$p_sapo', '$p_title', '$p_desc', '$p_keyword', '$p_slug', '$p_canonical',  $p_trang_thai_xuat_ban, '$p_nguoi_sua', $p_seo_template, '$p_tu_khoa_in_nghieng', '$p_tu_khoa_in_dam', '$p_tu_khoa_gach_chan', $p_chi_cap_nhat_keyword, $p_thiet_bi, '$p_title_mxh', '$p_des_mxh')";
// end 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh 
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Cap nhat trang thai xuat ban 
 * @param	int p_seo_chi_tiet_su_kien
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_nguoi_sua	 
 * @return array
 */ 
function be_update_trang_thai_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien, $p_trang_thai_xuat_ban, $p_nguoi_sua){	
	$sql = "call be_update_trang_thai_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien, $p_trang_thai_xuat_ban, '$p_nguoi_sua')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}


/**
 * Cap nhat keyword cho su kien
 * @param	int p_event:  ID su kien
 * @param   string p_keyword : Template cho keyword	
 * @return array
 */
function be_update_event_keyword($p_event, $p_keyword) {
	$sql = "call be_update_event_keyword($p_event, '$p_keyword')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Xoa thong tin 1 seo chi tiet title, desc, keyword cho su kien
 * @param 	int p_seo_chi_tiet_su_kien_id id seo chi tiết su kien 
 * @return array
 */
function be_delete_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien_id){	
	$sql = "call be_delete_seo_chi_tiet_su_kien($p_seo_chi_tiet_su_kien_id)";	 
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Lay 1 ten 1 chuyen muc xuat ban cho su kien
 * @param 	int p_event id su kien
 * @return string ten chuyen muc
 */
function be_get_category_name_by_event($p_event){	
	$sql = "call be_get_category_by_event($p_event)";	
	$rs = Gnud_Db_read_query($sql);
	$v_category_name = '';
	if($rs[0]['id_category']>0) {
		$v_category_name = get_category_name_by_id($rs[0]['id_category']);
	}
	return $v_category_name;	
}

/**
 * Lay danh sach seo chi tiet cho bai viet
 * @param 	string p_cat_id_list  Danh sach id chuyen muc
 * @param 	int p_user_id  id nguoi sua
 * @param   string p_status_list  danh sach trang thai
 * @param   string p_ten_bai_viet
 * @param	int p_id_bai_viet
 * @param 	string p_orderby_clause menh de order by
 * @param	int p_thiet_bi
 * @param 	int p_page 
 * @param   int p_number_item_per_page
 * @return array
 */
/* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
// Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
//Begin 12/5/2020 AnhTT bo_sung_tich_amp
function be_get_all_seo_chi_tiet_bai_viet($p_cat_id_list, $p_user_id, $p_status_list, $p_ten_bai_viet, $p_title_ascii, $p_id_bai_viet, $p_orderby_clause, $p_thiet_bi,$p_edit_date_start, $p_edit_date_end,$p_page, $p_number_item_per_page, $p_is_off_amp){	
	$sql = "call be_get_all_seo_chi_tiet_bai_viet('$p_cat_id_list', $p_user_id, '$p_status_list','$p_ten_bai_viet', '$p_title_ascii', $p_id_bai_viet,'$p_orderby_clause',$p_thiet_bi,'$p_edit_date_start', '$p_edit_date_end', $p_page, $p_number_item_per_page, '$p_is_off_amp')";
	$rs = Gnud_Db_read_query($sql);
	return $rs;	
}

/**
 * Lay chuyen muc xuat ban cua bai viet
 * @param 	int p_news_id  id  bai viet
 * @return array
 */
function be_get_category_by_newsid($p_news_id){
	$sql = "call be_get_category_by_newsid($p_news_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}

/**
 * Cap nhat seo chi tiet bai viet
 * @param	int p_seo_chi_tiet_bai_viet	
 * @param	int p_news	
 * @param 	string p_sapo	
 * @param 	string p_title	
 * @param   string p_desc	
 * @param   string p_keyword	
 * @param   string p_slug	
 * @param   string p_canonical	
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_danh_sach_alt_anh
 * @param 	int p_tu_khoa_in_nghieng
 * @param 	int p_tu_khoa_in_dam
 * @param 	int p_tu_khoa_gach_chan 
 * @param 	int p_nguoi_sua	
 * @param 	int p_thiet_bi
 * @return array
 */
// begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
/* Begin 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
// Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
//Begin 12/5/2020 AnhTT bo_sung_tich_amp
function be_update_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet, $p_news, $p_sapo, $p_title, $p_title_ascii, $p_desc, $p_keyword, $p_slug, $p_canonical, $p_trang_thai_xuat_ban,$p_danh_sach_alt_anh, $p_tu_khoa_in_nghieng , $p_tu_khoa_in_dam, $p_tu_khoa_gach_chan, $p_nguoi_sua, $p_thiet_bi, $p_anh_chia_se_mxh, $p_title_mxh, $p_des_mxh, $p_tu_dong_ha_xuat_ban, $p_ngay_ha_xuat_ban, $p_is_off_amp) {
	$sql = "call be_update_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet, $p_news, '$p_sapo', '$p_title', '$p_title_ascii', '$p_desc', '$p_keyword', '$p_slug', '$p_canonical', $p_trang_thai_xuat_ban,'$p_danh_sach_alt_anh', '$p_tu_khoa_in_nghieng', '$p_tu_khoa_in_dam', '$p_tu_khoa_gach_chan', '$p_nguoi_sua', $p_thiet_bi, '$p_anh_chia_se_mxh', '$p_title_mxh', '$p_des_mxh', $p_tu_dong_ha_xuat_ban, '$p_ngay_ha_xuat_ban','$p_is_off_amp')";	
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}
/* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
/**
 * Cap nhat trang thai seo chi tiet bai viet
 * @param	int p_seo_chi_tiet_bai_viet	 
 * @param 	int p_trang_thai_xuat_ban
 * @param 	int p_nguoi_sua	 
 * @return array
 */
function be_update_trang_thai_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet, $p_trang_thai_xuat_ban, $p_nguoi_sua) {
	$sql = "call be_update_trang_thai_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet, $p_trang_thai_xuat_ban,'$p_nguoi_sua')";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}
/**
 * Lấy thông tin chi tiết title,des bài viết
 * @param	int p_seo_chi_tiet_bai_viet_id	
 * @return array
 */
function be_get_single_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet_id) {
	$sql = "call be_get_single_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}

/**
 * Xóa dữ liệu title,des bài viết
 * @param	int p_seo_chi_tiet_bai_viet_id	
 * @return array
 */
function be_delete_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet_id) {
	$sql = "call be_delete_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Lay danh sach seo template
 * @param	int p_news_id	
 * @return array
 */
function be_get_all_seo_template_title_des($p_cat_id, $p_user_id, $p_status_list, $p_loai_trang_list,$p_ten_template,$p_tu_ngay_bat_dau, $p_tu_ngay_ket_thuc, $p_den_ngay_bat_dau, $p_den_ngay_ket_thuc,$p_orderby_clause, $p_snippet_type, $p_thiet_bi_id, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_template_title_des('$p_cat_id', $p_user_id, '$p_status_list', '$p_loai_trang_list','$p_ten_template','$p_tu_ngay_bat_dau', '$p_tu_ngay_ket_thuc', '$p_den_ngay_bat_dau',
	'$p_den_ngay_ket_thuc','$p_orderby_clause', $p_snippet_type, $p_thiet_bi_id, $p_page, $p_number_item_per_page)";	
	
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay du lieu chi tiet mot template
 * @param	int p_template_id id template	
 * @return array
 */
function be_get_single_seo_template_title_des($p_template_id) 
{
	$sql = "call be_get_single_seo_template_title_des($p_template_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}

/**
 * Lay danh sach chuyen muc template xuat ban
 * @param	int p_template_id id template	
 * @return array
 */
function be_get_all_seo_template_title_des_publish($p_template_id) 
{
	$sql = "call be_get_all_seo_template_title_des_publish($p_template_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs;	
}

/**
 * Cap nhat 1 seo template
 * @param	int $p_template_id id template	
 * @param	string $p_ten ten template
 * @param	string $p_ten_khong_dau  ten template khong dau
 * @param	int $p_loai_trang  ID loai trang
 * @param	int $p_nguoi_sua  ID loai trang
 * @param	int $p_trang_thai_xuat_ban  trang thai xuat ban
 * @param	string $p_template_slug  template cho slug
 * @param	string $p_template_title  template cho title
 * @param	string $p_template_description  template cho description
 * @param	string $p_template_keyword  template cho keyword
 * @param	string $p_template_alt_anh  template cho alt anh
 * @param	string $p_template_breadcrumb  template cho breadcurmb
 * @param	int $p_gioi_han_ky_tu_title  gioi han ky tu hien thi title
 * @param	int $p_gioi_han_ky_tu_des  gioi han ky tu hien thi description
 * @param	int $p_gioi_han_ky_tu_key  gioi han ky tu hien thi keyword
 * @param	int $p_gioi_han_ky_tu_alt  gioi han ky tu hien thi alt anh
 * @param	string $p_tu_ngay  ngay xuat ban tu ngay
 * @param	string $p_tu_ngay  ngay xuat ban den ngay
 * @param 	int $p_snippet_type Loai snippet
 * @param 	int $p_thiet_bi Loai thiết bị
 * @return array
 */
function be_update_seo_template_title_des($p_template_id, $p_ten, $p_ten_khong_dau, $p_loai_trang, $p_nguoi_sua, $p_trang_thai_xuat_ban, $p_template_slug	
	,$p_template_title, $p_template_description, $p_template_keyword, $p_template_alt_anh, $p_template_breadcrumb, $p_gioi_han_ky_tu_title, $p_gioi_han_ky_tu_des	
	,$p_gioi_han_ky_tu_key, $p_gioi_han_ky_tu_alt, $p_tu_ngay, $p_den_ngay, $p_snippet_type, $p_thiet_bi)
{
	$sql = "call be_update_seo_template_title_des(
		$p_template_id
		,'$p_ten'
		,'$p_ten_khong_dau'
		, $p_loai_trang	
		, $p_nguoi_sua	
		, $p_trang_thai_xuat_ban	
		,'$p_template_slug'	
		,'$p_template_title'	
		,'$p_template_description'	
		,'$p_template_keyword'
		,'$p_template_alt_anh'	
		,'$p_template_breadcrumb'	
		, $p_gioi_han_ky_tu_title
		, $p_gioi_han_ky_tu_des	
		, $p_gioi_han_ky_tu_key	
		, $p_gioi_han_ky_tu_alt
		,'$p_tu_ngay'	
		,'$p_den_ngay'
		, $p_snippet_type
		, $p_thiet_bi
	)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Cap nhat 1 seo template xuat ban cho chuyen muc
 * @param	int $p_template_id id template	
 * @param	int $p_category_id id chuyen muc
 * @return array
 */
function be_update_seo_template_title_des_publish($p_template_id, $p_category_id) 
{
	$sql = "call be_update_seo_template_title_des_publish($p_template_id, $p_category_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Xoa mot template
 * @param	int $p_template_id id template	
 * @return array
 */
function be_delete_seo_template_title_des($p_template_id)
{
	$sql = "call be_delete_seo_template_title_des($p_template_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Xoa mot template xuat ban cho chuyen muc
 * @param	int $p_template_id id template	
 * @return array
 */
function be_delete_seo_template_title_des_publish($p_template_id)
{
	$sql = "call be_delete_seo_template_title_des_publish($p_template_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Kiem tra trung xuat ban template cho chuyen muc
 * @param	int $p_template_id id template	
 * @param	int $p_category_id id chuyen muc
 * @param	int $p_loai_trang id loai trang
 * @param	string $p_publish_from_date ngay xuat ban tu ngay
 * @return array
 */
function be_check_duplicate_seo_template_publish($p_template_id, $p_category_id, $p_loai_trang , $p_publish_from_date) 
{
	$sql = "call be_check_duplicate_seo_template_publish($p_template_id, $p_category_id, $p_loai_trang , '$p_publish_from_date')";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}

/**
* Lay danh sach lich xuat ban
* @param string p_cat_id_list Tim theo id chuyen muc
	string	$p_user_id Tim theo id user sua
	string  $p_status_list Tim theo danh sách trạng thái
	string  $p_loai_trang_list Tim theo danh sách loại chuyên mục
	string  $p_ten_lich_xuat_ban Tìm theo tên không dấu
	int		$p_id_bai_viet tìm theo id bài viét
	int		$P_id_su_kien tìm theo id sự kiện
	string  $p_tu_ngay_bat_dau Tim theo ngày xuất bản từ ngày
	string  $p_tu_ngay_ket_thuc Tim theo ngày xuất bản đến ngày
	string  $p_den_ngay_bat_dau Tim theo ngày kết thúc từ ngày
	string  $p_den_ngay_ket_thuc Tim theo ngày kết thúc đến ngày
	string  $p_orderby_clause mệnh đề order by
	
* @return array
*/
 
function be_get_all_seo_lich_xuat_ban($p_cat_id_list, $p_user_id, $p_status_list, $p_loai_trang_list, $p_ten_lich_xuat_ban, $p_id_bai_viet, $p_id_su_kien, $p_tu_ngay_bat_dau, $p_tu_ngay_ket_thuc, $p_den_ngay_bat_dau, $p_den_ngay_ket_thuc, $p_orderby_clause, $p_page, $p_number_per_page) 
{
	$sql = "call be_get_all_seo_lich_xuat_ban('$p_cat_id_list', $p_user_id, '$p_status_list', '$p_loai_trang_list', '$p_ten_lich_xuat_ban', $p_id_bai_viet, $p_id_su_kien,'$p_tu_ngay_bat_dau','$p_tu_ngay_ket_thuc','$p_den_ngay_bat_dau','$p_den_ngay_ket_thuc' ,  '$p_orderby_clause', $p_page, $p_number_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Lay thong tin chi tiet noi dung lich xuat ban
 * @param	int $p_lich_xuat_ban_id id lich xuat ban
 * @return array
 */
function be_get_single_seo_lich_xuat_ban($p_lich_xuat_ban_id) 
{
	$sql = "call be_get_single_seo_lich_xuat_ban($p_lich_xuat_ban_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Xoa noi dung lich xuat ban
 * @param	int $p_lich_xuat_ban_id id lich xuat ban
 * @return array
 */
function be_delete_seo_lich_xuat_ban($p_lich_xuat_ban_id) {
	$sql = "call be_delete_seo_lich_xuat_ban($p_lich_xuat_ban_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];

}

/**
 * Cap nhat 1 lich xuat ban
 * @param	int $p_id :id lich xuat ban
 * @param	string $p_ten :ten lich xuat ban
 * @param	string $p_ten_khong_dau: ten lich xuat ban khong dau
 * @param	int $p_loai_trang :ID loai trang
 * @param	string $p_noi_dung :noi dung lich xuat ban
 * @param	string $p_tu_ngay :ngay xuat ban tu ngay
 * @param	string $p_den_ngay : ngay xuat ban den ngay
 * @param	int $p_trang_thai_xuat_ban : trang thai xuat ban
 * @param	int $p_nguoi_sua : id nguoi sua
 * @return array
 */
function be_update_seo_lich_xuat_ban($p_id, $p_ten, $p_ten_khong_dau, $p_loai_trang, $p_noi_dung, $p_tu_ngay, $p_den_ngay, $p_trang_thai_xuat_ban, $p_nguoi_sua) {
	$sql = "call be_update_seo_lich_xuat_ban($p_id, '$p_ten', '$p_ten_khong_dau', $p_loai_trang, '$p_noi_dung', '$p_tu_ngay', '$p_den_ngay', $p_trang_thai_xuat_ban, '$p_nguoi_sua')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Cap nhat 1 lich xuat ban xuat ban xuat ban cho chuyen muc
 * @param	int $p_lich_xuat_ban_id :id lich xuat ban
 * @param	int $p_object_id :ID doi tuong ( bai viet, su kien, chuyen muc)
 * @param	string $p_type: loai doi tuong ( category, event, news)
 * @return array
 */
function be_update_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, $p_object_id, $p_type) {
	$sql = "call be_update_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, $p_object_id, '$p_type')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
 * Xoa mot lich xuat ban xuat ban cho chuyen muc
 * @param	int $p_lich_xuat_ban_id :id lich xuat ban
 * @param	string $p_type: loai doi tuong ( category, event, news)
 * @return array
 */
function be_delete_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, $p_type) {
	$sql = "call be_delete_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, '$p_type')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Lay thong tin lich xuat ban publish
 * @param	int $p_lich_xuat_ban_id :id lich xuat ban
 * @param	string $p_type: loai doi tuong ( category, event, news)
 * @return array
 */
function be_get_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, $p_type) {
	$sql = "call be_get_seo_lich_xuat_ban_publish($p_lich_xuat_ban_id, '$p_type')";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Kiem tra trung lap lich xuat ban
 * @param	int $p_lich_xuat_ban_id :id lich xuat ban
 * @param	int $p_loai_trang: id loai trang
 * @param	string $p_object_code: ma doi tuong (category, event, news)
 * @param	int $p_object_code: ma doi tuong (category, event, news)
 * @param	int $p_object_id: id chuyen muc/id su kien/id bai viet
 * @param	string $p_tu_ngay: Ngay suat ban tu ngay 
 * @return array
 */
function be_check_seo_lich_xuat_ban_trung_lap($p_lich_xuat_ban_id, $p_loai_trang, $p_object_code, $p_object_id, $p_tu_ngay)
{
	$sql = "call be_check_seo_lich_xuat_ban_trung_lap($p_lich_xuat_ban_id, $p_loai_trang, '$p_object_code', $p_object_id, '$p_tu_ngay')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Lay danh sach footer
* @param	string p_cat_id_list : danh sach id chuyen muc
* @param	int $p_user_id: id nguoi sua
* @param	string $p_status_list: danh sach trang thai xuat ban
* @param	string $p_ten_footer: Ten footer khong dau
* @param	string $p_orderby: menh de order by
* @param	int $p_loai_trang: loai trang hien thi footer
* @param	int $p_page: trang can xem
* @param	int $p_number_item_per_page: so ban ghi tren trang
* @return array
*/
function be_get_all_footer($p_cat_id_list, $p_user_id, $p_status_list, $p_ten_footer, $p_orderby, $p_loai_trang, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_footer('$p_cat_id_list', $p_user_id, '$p_status_list', '$p_ten_footer', '$p_orderby', $p_loai_trang, $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
* Lay thong tin chi tiet footer
* @param	int p_footer_id : ID footer
* @return array
*/
function be_get_single_footer($p_footer_id)
{
	$sql = "call be_get_single_footer($p_footer_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Cap nhat 1 footer
* @param	int p_footer_id : ID footer
* @param	string p_ten : ten footer
* @param	string p_ten_khong_dau : ten footer khong dau
* @param	string p_noi_dung : noi dung footer
* @param	string p_lien_ket_cua_logo : lien ket cua logo
* @param	int p_kieu_mo_lien_ket : kieu mo lien ket
* @param	string p_duong_dan_anh_logo : duong dan anh logo
* @param	string p_textlink_footer_mac_dinh
* @param	int p_trang_thai_xuat_ban : trang thai xuat ban
* @param	int p_nguoi_sua : ID nguoi sua
* @return array
*/
function be_update_footer($p_footer_id, $p_ten, $p_ten_khong_dau, $p_noi_dung, $p_lien_ket_cua_logo, $p_kieu_mo_lien_ket, $p_duong_dan_anh_logo, $p_textlink_footer_mac_dinh, $p_trang_thai_xuat_ban, $p_nguoi_sua, $p_page)
{
	$sql = "call be_update_footer($p_footer_id, '$p_ten', '$p_ten_khong_dau', '$p_noi_dung', '$p_lien_ket_cua_logo', $p_kieu_mo_lien_ket, '$p_duong_dan_anh_logo', '$p_textlink_footer_mac_dinh', $p_trang_thai_xuat_ban, $p_nguoi_sua, $p_page)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Cap nhat 1 footer xuat ban theo chuyen muc
* @param	int p_footer_id : ID footer
* @param	int p_category_id : ID chuyen muc
* @return array
*/
function be_update_footer_category($p_footer_id, $p_category_id)
{
	$sql = "call be_update_footer_category($p_footer_id, $p_category_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xóa 1 footer xuat ban theo chuyen muc
* @param	int p_footer_id : ID footer
* @return array
*/
function be_delete_footer_category($p_footer_id)
{
	$sql = "call be_delete_footer_category($p_footer_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xóa 1 footer 
* @param	int p_footer_id : ID footer
* @return array
*/
function be_delete_footer($p_footer_id)
{
	$sql = "call be_delete_footer($p_footer_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Lay danh sach chuyen muc xuat ban footer
* @param	int p_footer_id : ID footer
* @return array
*/
function be_get_all_footer_category($p_footer_id)
{
	$sql = "call be_get_all_footer_category($p_footer_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay danh sach link rss
 * @param	int $p_user_id: id nguoi sua
 * @param	string $p_ten_rss_link: Ten link rss khong dau
 * @param	string $p_orderby: menh de order by
 * @param	int $p_page: trang can xem
 * @param	int $p_number_item_per_page: so ban ghi tren trang
 * @return array
 */
function be_get_all_rss_link($p_user_id, $p_ten_rss_link, $p_orderby, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_rss_link($p_user_id, '$p_ten_rss_link', '$p_orderby',$p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
* Lay thong tin chi tiet link rss
* @param	int p_id : ID link rss
* @return array
*/
function be_get_single_rss_link($p_id)
{
	$sql = "call be_get_single_rss_link($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* cap nhat chi tiet 1 link rss
* @param	int p_id : ID link rss
* @param	string p_ten : ten link rss
* @param	string p_ten_khong_dau : ten link rss khong dau
* @param	string p_cat_id : tham so id chuyen muc
* @param	string p_post_type : tham so post_type
* @param	int p_count : tham so count
* @param	int p_no_follow : thuoc tinh nofollow
* @param	string p_nguoi_tao : ID nguoi tao
* @return array
*/
function  be_update_rss_link($p_id, $p_ten, $p_ten_khong_dau, $p_category_id, $p_post_type, $p_count, $p_no_follow, $p_nguoi_tao)
{
	$sql = "call be_update_rss_link($p_id, '$p_ten', '$p_ten_khong_dau', '$p_category_id', '$p_post_type', $p_count, $p_no_follow, $p_nguoi_tao)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Xoa du lieu link rss
* @param	int p_id : ID link rss
* @return array
*/
function be_delete_rss_link($p_id) 
{
	$sql = "call be_delete_rss_link($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}


/* 
	UC quan tri cau hinh hien thi tag theo bai viet
*/

/**
 * Lay danh sach cau hinh tag
 * @param	string $p_cat_id_list : danh sach id chuyen muc
 * @param	int $p_user_id: ID nguoi sua
 * @return array
 */
function be_get_all_seo_tag_news_config($p_cat_id_list, $p_user_id, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_tag_news_config('$p_cat_id_list', $p_user_id, $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay thong tin chi tiet 
 * @param	int p_id : ID tag cấu hình
 * @param	int p_cat_id : ID chuyên mục
 * @return array
 */
function be_get_single_seo_tag_news_config($p_id, $p_cat_id)
{
	$sql = "call be_get_single_seo_tag_news_config($p_id, $p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet 1 cau hinh tag
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : id chuyen muc
 * @param	int p_n_date int
 * @param	int p_n_tag int
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */
function  be_update_seo_tag_news_config($p_id, $p_category_id, $p_n_date, $p_n_tag, $p_edited_user)
{
	$sql = "call be_update_seo_tag_news_config($p_id, $p_category_id, $p_n_date, $p_n_tag, $p_edited_user)";
	// echo $sql;
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa du lieu cau hinh tag theo bai viet
 * @param	int p_id : ID cau hinh tag
 * @return array
 */
function be_delete_seo_tag_news_config($p_id) 
{
	$sql = "call be_delete_seo_tag_news_config($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/* 
	II.UC quan tri cau hinh hien thi tag theo chuyen muc
*/

/**
 * Lay danh sach cau hinh tag  tag cho chuyen muc
 * @param	string $p_cat_id_list : danh sach id chuyen muc
 * @param	int $p_user_id: ID nguoi sua
 * @param	string $p_status_list: Danh sach trang thai bat/tat
 * @param	string $p_cat_type_list: Danh sach loai chuyen muc hot/khong hot
 * @return array
 */
function be_get_all_seo_tag_category_config($p_cat_id_list, $p_user_id, $p_status_list, $p_cat_type_list,$p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_tag_category_config('$p_cat_id_list', $p_user_id, '$p_status_list', '$p_cat_type_list',$p_page, $p_number_item_per_page)";
	//echo $sql;
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay thong tin chi tiet cau hinh tag cho chuyen muc
 * @param	int p_id : ID tag cấu hình
 * @param	int p_cat_id : ID chuyên mục
 * @return array
 */
function be_get_single_seo_tag_category_config($p_id, $p_cat_id)
{
	$sql = "call be_get_single_seo_tag_category_config($p_id, $p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lấy thông tin chi tiết trạng thái tag chuyên mục
 * @param	int p_category_id : ID chuyên mục
 * @return array
 */
function be_get_single_category_ctrl_config($p_category_id)
{
	$sql = "call be_get_single_category_ctrl_config($p_category_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet 1 cau hinh tag
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : id chuyen muc
 * @param	int p_n_date int
 * @param	int p_n_tag int
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */
function  be_update_seo_tag_category_config($p_id, $p_category_id, $p_n_date, $p_n_tag, $p_edited_user)
{
	$sql = "call be_update_seo_tag_category_config($p_id, $p_category_id, $p_n_date, $p_n_tag, $p_edited_user)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet trang thai tag chuyen muc
 * @param	int p_category_id : id chuyen muc
 * @param	int p_status int  : trang thai bat/tat
 * @param	int p_hot int : trang thai hot/khong hot
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */
function  be_update_seo_tag_category_status($p_category_id, $p_status, $p_hot, $p_edited_user)
{
	$sql = "call be_update_seo_tag_category_status($p_category_id, $p_status, $p_hot, $p_edited_user)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
 * Xoa du lieu cau hinh tag theo bai viet
 * @param	int p_id : ID cau hinh tag
 * @return array
 */
 
function be_delete_seo_tag_category_config($p_id) 
{
	$sql = "call be_delete_seo_tag_category_config($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/* 
	UC quan tri danh sach tags
*/

/**
 * Lay danh sach cau hinh tag
 * @param	string $p_cat_id_list : danh sach id chuyen muc
 * @param	int $p_user_id: ID nguoi sua
 * @param	string $p_status_list: Danh sach trang thai bat/tat
 * @param	string $p_cat_type_list: Danh sach loai chuyen muc hot/khong hot
 * @return array
 */ 
function be_get_all_seo_tag($p_cat_id_list, $p_user_id, $p_status_list, $p_tag_name, $p_id_tag, $p_order_column, $p_page, $p_number_per_page)
{
	$sql = "call be_get_all_seo_tag('$p_cat_id_list', $p_user_id, '$p_status_list',  '$p_tag_name', $p_id_tag, '$p_order_column', $p_page, $p_number_per_page)";
	//echo $sql;
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay thong tin chi tiet 
 * @param	int p_id : ID tag cấu hình
 * @return array
 */
function be_get_single_seo_tag($p_id)
{
	$sql = "call be_get_single_seo_tag($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet 1 cau hinh tag
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : id chuyen muc
 * @param	int p_n_date int
 * @param	int p_n_tag int
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */	
function  be_update_seo_tag($p_id, $p_title, $p_description, $p_slug, $p_canonical, $p_font_size, $p_noi_bat, $p_bg_color, $p_published, $p_position, $p_editor_id, $p_tag='')
{
    $p_title = fw24h_add_slashes($p_title);
	$p_description = fw24h_add_slashes($p_description);
	$p_slug = fw24h_add_slashes($p_slug);
	$p_tag = fw24h_add_slashes($p_tag);
	$p_canonical = fw24h_add_slashes($p_canonical);
	$p_font_size = fw24h_add_slashes($p_font_size);
	$p_bg_color = fw24h_add_slashes($p_bg_color);
	$sql = "call be_update_seo_tag($p_id, '$p_tag', '$p_title', '$p_description', '$p_slug', '$p_canonical', '$p_font_size', $p_noi_bat, '$p_bg_color', $p_published, $p_position, $p_editor_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa du lieu cau hinh tag theo bai viet
 * @param	int p_id : ID cau hinh tag
 * @return array
 */
function be_delete_seo_tag($p_id) 
{
	$sql = "call be_delete_seo_tag($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Cap nhat bang luu thong tin bai viet chua tag, su dung cho chuc nang cap nhat tin bai
 * @param	int p_news_id : ID bai viet
 * @param	int p_tag_id : id tag
 * @param	int p_category_id int
 * @return array
 */	
function  be_update_seo_tag_post($p_news_id, $p_tag_id, $p_category_id)
{
	$sql = "call be_update_seo_tag_post($p_news_id, $p_tag_id, $p_category_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Lay danh sach cau hinh tag
 * @param	string $p_cat_id_list : danh sach id chuyen muc
 * @param	int $p_user_id: ID nguoi sua
 * @param	string $p_status_list: Danh sach trang thai bat/tat
 * @param	string $p_cat_type_list: Danh sach loai chuyen muc hot/khong hot
 * @return array
 */ 
function be_get_all_news_by_tag($p_tag_id, $p_cat_id_list, $p_news_name, $p_page, $p_number_per_page)
{
	$sql = "call be_get_all_news_by_tag($p_tag_id, '$p_cat_id_list', '$p_news_name', $p_page, $p_number_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay danh sach tag theo chuyen muc dung cho box preview tag footer theo chuyen muc
 * @param	string $p_cat_id : id chuyen muc
 * @return array
 */ 
function be_get_all_seo_tag_by_category($p_cat_id)
{
	$sql = "call be_get_all_seo_tag_by_category($p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay danh bat tag qua khu
 * @param	int $p_cat_id : id chuyen muc
 * @param	int $p_user_id: ID nguoi sua
 * @param	string $p_ngay_bat_dau_tu_ngay: ngay bat dau tu ngay
 * @param	string $p_ngay_bat_dau_den_ngay: ngay bat dau den ngay
 * @param	string $p_ngay_ket_thuc_tu_ngay: ngay ket thuc tu ngay
 * @param	string $p_ngay_ket_thuc_den_ngay: ngay ket thuc den ngay
 * @param	int $p_page: Trang can xem
 * @param	int $p_number_per_page: so ban ghi can xem/trang
 * @return array
 */ 
function be_get_all_seo_tag_ctrl($p_cat_id_list, $p_user_id, $p_ngay_bat_dau_tu_ngay, $p_ngay_bat_dau_den_ngay, $p_ngay_ket_thuc_tu_ngay, $p_ngay_ket_thuc_den_ngay, $p_page, $p_number_per_page)
{
	$sql = "call be_get_all_seo_tag_ctrl($p_cat_id_list, $p_user_id, '$p_ngay_bat_dau_tu_ngay', '$p_ngay_bat_dau_den_ngay', '$p_ngay_ket_thuc_tu_ngay', '$p_ngay_ket_thuc_den_ngay', $p_page, $p_number_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay thong tin chi tiet 1 bat tag qua khu
 * @param	int p_id : ID bat tag qua khu
 * @return array
 */
function be_get_single_seo_tag_ctrl($p_id)
{
	$sql = "call be_get_single_seo_tag_ctrl($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet bat tag qua khu
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : id chuyen muc
 * @param	int p_tu_ngay  string
 * @param	int p_den_ngay string
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */	
function  be_update_seo_tag_ctrl($p_id, $p_category_id, $p_tu_ngay, $p_den_ngay, $p_editor_id)
{
	$sql = "call be_update_seo_tag_ctrl($p_id, $p_category_id, '$p_tu_ngay', '$p_den_ngay', $p_editor_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa du lieu bat tag qua khu
 * @param	int p_id : ID cau hinh tag
 * @return array
 */
function be_delete_seo_tag_ctrl($p_id) 
{
	$sql = "call be_delete_seo_tag_ctrl($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Kiem tra trung thoi gian bat tag 
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : Id chuyen muc
 * @param	string p_tu_ngay : Tu ngay
 * @param	string p_den_ngay : Den ngay
 * @return array
 */
function be_check_duplicate_seo_tag_ctrl($p_id, $p_category_id, $p_tu_ngay, $p_den_ngay) 
{
	$sql = "call be_check_duplicate_seo_tag_ctrl($p_id, $p_category_id, '$p_tu_ngay', '$p_den_ngay')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}


/**
 * Lay danh bao cao xuat ban su kien
 * @param	string $p_status_list : danh sach id trang thai
 * @param	int $p_edited_user: ID nguoi sua
 * @param	string $p_title: ngay bat dau tu ngay
 * @param	int $p_page: Trang can xem
 * @param	int $p_number_per_page: so ban ghi can xem/trang
 * @return array
 */ 
function be_get_all_seo_event_report($p_status_list, $p_edited_user, $p_title, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_event_report('$p_status_list', $p_edited_user, '$p_title', $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Lay thong tin chi tiet bao cao xuat ban su kien
 * @param	int p_id : ID bao cao xb su kien
 * @return array
 */
function be_get_single_seo_event_report($p_id)
{
	$sql = "call be_get_single_seo_event_report($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet bao cao xb su kien
 * @param	int p_id : ID cau hinh tag
 * @param	int p_category_id : id chuyen muc
 * @param	int p_tu_ngay  string
 * @param	int p_den_ngay string
 * @param	int p_edited_user : ID nguoi sua
 * @return array
 */	
function  be_update_seo_event_report($p_id, $p_name, $p_name_ascii, $p_sendto, $p_gui_hangngay, $p_gui_hangtuan, $p_gui_hangthang, $p_xb_hangngay, $p_xb_ngaymai, $p_xb_hangtuan, $p_xb_1thang, $p_xb_2thang, $p_xb_3thang, $p_edited_user, $p_published)
{
	$sql = "call be_update_seo_event_report($p_id, '$p_name', '$p_name_ascii', '$p_sendto', $p_gui_hangngay, $p_gui_hangtuan, $p_gui_hangthang, $p_xb_hangngay, $p_xb_ngaymai, $p_xb_hangtuan, $p_xb_1thang, $p_xb_2thang, $p_xb_3thang, $p_edited_user, $p_published)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa du lieu bao cao xb su kien
 * @param	int p_id : ID bao cao xb su kien
 * @return array
 */
function be_delete_seo_event_report($p_id) 
{
	$sql = "call be_delete_seo_event_report($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Kiem tra bao cao xb su kien bi trung thong tin
 * @param	int p_id 	: id bao cao
 * @param	int p_name : ten tieu de bao cao
 * @param	int p_gui_hangngay 
 * @param	int p_gui_hangtuan 
 * @param	int p_gui_hangthang
 * @param	int p_xb_hangngay 
 * @param	int p_xb_ngaymai 
 * @param	int p_xb_hangtuan 
 * @param	int p_xb_1thang 
 * @param	int p_xb_2thang 
 * @param	int p_xb_3thang  
 * @return array
 */
function be_check_duplicate_event_report($p_id, $p_name, $p_gui_hangngay, $p_gui_hangtuan, $p_gui_hangthang, $p_xb_hangngay, $p_xb_ngaymai, $p_xb_hangtuan, $p_xb_1thang, $p_xb_2thang, $p_xb_3thang) 
{
	$sql = "call be_check_duplicate_event_report($p_id, '$p_name', $p_gui_hangngay, $p_gui_hangtuan, $p_gui_hangthang, $p_xb_hangngay, $p_xb_ngaymai, $p_xb_hangtuan, $p_xb_1thang, $p_xb_2thang, $p_xb_3thang) ";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Lay danh snippet rating
 * @param	int  $p_tag_id : ID tag
 * @param	string $p_tag_name: Ten tag
 * @param	int $p_event_id: ID su kien
 * @param	int $p_news_id: ID bai viet
 * @param	string $p_page_type: Loai trang TAG, NEWS, EVENT
 * @param	int $p_editor_id: ID nguoi sua
 * @param	int $p_page: Trang can xem
 * @param	int $p_number_per_page: so ban ghi can xem/trang
 * @return array
 */ 
function be_get_all_seo_snippet_rating($p_tag_id, $p_tag_name, $p_event_id, $p_news_id, $p_page_type, $p_editor_id, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_snippet_rating($p_tag_id, '$p_tag_name', $p_event_id, $p_news_id, '$p_page_type', $p_editor_id, $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Lay thong tin chi tiet snippet rating
 * @param	int p_id : ID snippet rating
 * @return array
 */
function be_get_single_seo_snippet_rating($p_id)
{
	$sql = "call be_get_single_seo_snippet_rating($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * cap nhat chi tiet snippet rating
 * @param	int $p_id : ID cau hinh tag
 * @param	int $p_page_id : id bai viet, su kien, tag
 * @param	string $p_page_type: loai trang TAG,NEWS,EVENT
 * @param	int $p_rate : luot binh chon
 * @param	int $p_score : diem binh chon
 * @param	int $p_editor_id : ID nguoi sua
 * @return array
 */	
function  be_update_seo_snippet_rating($p_id, $p_page_id, $p_page_type, $p_rate, $p_score, $p_editor_id)
{
	$sql = "call be_update_seo_snippet_rating($p_id, $p_page_id, '$p_page_type', $p_rate, $p_score, $p_editor_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa du lieu snippet rating
 * @param	int p_id : ID snippet rating can xoa
 * @return array
 */
function be_delete_seo_snippet_rating($p_id) 
{
	$sql = "call be_delete_seo_snippet_rating($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}


/**
 * Lay danh sach template noi dung gioi thieu su kien
 * @param	int  $p_editor_id : ID nguoi sua
 * @param	string $p_status_list: Danh sach ma trang thai
 * @param	string $p_template_name: Ten template 
 * @param	int $p_page: Trang can xem
 * @param	int $p_number_per_page: so ban ghi can xem/trang
 * @return array
 */ 
function be_get_all_seo_template($p_editor_id, $p_status_list, $p_template_name, $p_page, $p_number_item_per_page)
{
	$sql = "call be_get_all_seo_template($p_editor_id, '$p_status_list', '$p_template_name', $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Lay thong tin chi tiet template noi dung gioi thieu su kien
 * @param	int p_id : ID template
 * @return array
 */
function be_get_single_seo_template($p_id)
{
	$sql = "call be_get_single_seo_template($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Cap nhat chi tiet template noi dung gioi thieu su kien
 * @param	int $p_id : ID template
 * @param	string $p_name : Ten template
 * @param	string $p_name_ascii: Ten template khong dau
 * @param	string $p_content : Noi dung template
 * @param	int $p_editor_id : ID nguoi sua
 * @param	int $p_published : Trang thai xuat ban
 * @return array
 */	
function  be_update_seo_template($p_id, $p_name, $p_name_ascii, $p_content, $p_editor_id, $p_published)
{
	$sql = "call be_update_seo_template($p_id, '$p_name', '$p_name_ascii', '$p_content', $p_editor_id, $p_published)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Xoa seo template noi dung gioi thieu su kien
 * @param	int p_id : ID seo template 
 * @return array
 */
function be_delete_seo_seo_template($p_id) 
{
	$sql = "call be_delete_seo_seo_template($p_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * Kiem tra keyword co ton tai tag hay chua
 * @param	string p_keyword
 * @return array
 */
function be_check_keyword_exist_tag($p_keyword) 
{
    /* begin 9/1/2017 TuyenNT fix_log_9_1_2017 */
    Gnud_Db_read_close();
    /* end 9/1/2017 TuyenNT fix_log_9_1_2017 */
	$p_keyword = fw24h_add_slashes($p_keyword);
    $sql = "call be_check_keyword_exist_tag('$p_keyword')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/*
 * Ham kiem tra keyword da co trong bang tag hay chua
 * @param int p_tag_id 
 * @param int p_news_id  id chuyen muc
 * @param int p_category_id id loai trang
 * @return array()
 */
function be_check_keyword_exist_tag_post($p_tag_id, $p_news_id, $p_category_id) {
	$v_sql = "CALL be_check_keyword_exist_tag_post($p_tag_id, $p_news_id, $p_category_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs[0]; 
}

/**
 * Ham lay fontsize cua tag, copy tu ocm cu
 * @param	int p_hits
 * @return string
 */
function getfontsite($p_hits = 0)
{
	$p_hits = ($p_hits) ? $p_hits : 1;
	$q = "CALL be_get_tag_fontsite($p_hits)";
	$rs = Gnud_Db_read_query($q);
	return $rs[0]['c_font_size'];
}

/**
 * Ham lay color cua tag, copy tu ocm cu
 * @param	int p_hits
 * @return string
 */
function getcolor($p_hits = 0){

	$p_hits = ($p_hits) ? $p_hits : 1;
	$q = "CALL be_get_tag_color($p_hits)";
	$rs = Gnud_Db_read_query($q);
	return ' color:#'.$rs[0]['c_color'].';';

}
	
/**
 * Ham lay danh sach link campaign
 * @param	int p_category_id
 * @param	int p_loai_trang
 * @param	int p_ten_link
 * @param	int p_page
 * @param	int p_number_item_per_page
 * @return array
 */
function be_get_all_seo_link_campaign($p_category_id, $p_loai_trang, $p_nguoi_sua, $p_ten_link, $p_page, $p_number_item_per_page) {
 	$v_sql = "CALL be_get_all_seo_link_campaign($p_category_id, $p_loai_trang, $p_nguoi_sua, '$p_ten_link', $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /**
 * Ham lay chi tiet 1 link campaign
 * @param	int p_link_campaign_id
 * @return array
 */
function be_get_single_seo_link_campaign($p_link_campaign_id) {
 	$v_sql = "CALL be_get_single_seo_link_campaign($p_link_campaign_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs[0];
}
 
 /**
 * Ham cap nhat chi tiet 1 link campaign
 * @param	int p_link_campaign_id
 * @param	string p_ten
 * @param	string p_ten_khong_dau
 * @param	int p_loai_trang
 * @param	string p_utm_source
 * @param	string p_utm_medium
 * @param	string p_utm_term
 * @param	string p_utm_content
 * @param	string p_utm_campaign
 * @param	int p_nguoi_sua
 * @return array
 */
function be_update_seo_link_campaign($p_link_campaign_id, $p_ten, $p_ten_khong_dau, $p_loai_trang, $p_utm_source, $p_utm_medium, $p_utm_term, $p_utm_content, $p_utm_campaign, $p_nguoi_sua) {
 	$v_sql = "CALL be_update_seo_link_campaign($p_link_campaign_id, '$p_ten', '$p_ten_khong_dau', $p_loai_trang, '$p_utm_source', '$p_utm_medium', '$p_utm_term', '$p_utm_content', '$p_utm_campaign', $p_nguoi_sua)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham cap nhat xuat ban link campaign theo chuyen muc
 * @param int p_link_campaign_id 
 * @param int p_category_id
 * @return array()
 */
function be_update_seo_link_campaign_category($p_link_campaign_id, $p_category_id) {
	$v_sql = "CALL be_update_seo_link_campaign_category($p_link_campaign_id, $p_category_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link campaign google
 * @param int p_link_campaign_id 
 * @return array()
 */
function be_delete_seo_link_campaign($p_link_campaign_id) {
	$v_sql = "CALL be_delete_seo_link_campaign($p_link_campaign_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link campaign xuat ban theo chuyen muc
 * @param int p_link_campaign_id 
 * @return array()
 */
function be_delete_seo_link_campaign_category($p_link_campaign_id) {
	$v_sql = "CALL be_delete_seo_link_campaign_category($p_link_campaign_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham danh sach chuyen muc xuat ban link campaign
 * @param int p_link_campaign_id 
 * @return array()
 */
function be_get_all_seo_link_campaign_category($p_link_campaign_id) {
	$v_sql = "CALL be_get_all_seo_link_campaign_category($p_link_campaign_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /*
 * Ham danh sach box tin ban link campaign
 * @param int p_link_campaign_id 
 * @return array()
 */
function be_get_all_seo_link_campaign_box($p_link_campaign_id) {
	$v_sql = "CALL be_get_all_seo_link_campaign_box($p_link_campaign_id)";
	
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /*
 * Ham cap nhat link campaign xuat ban theo box tin
 * @param int p_link_campaign_id 
 * @param int p_box_id
 * @return array()
 */
function be_update_seo_link_campaign_box($p_link_campaign_id, $p_box_id) {
	$v_sql = "CALL be_update_seo_link_campaign_box($p_link_campaign_id, $p_box_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link campaign xuat ban theo box tin
 * @param int p_link_campaign_id 
 * @return array()
 */
function be_delete_seo_link_campaign_box($p_link_campaign_id) {
	$v_sql = "CALL be_delete_seo_link_campaign_box($p_link_campaign_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
 }
 
 /*
 * Ham lay danh sach box tin
 * @param int p_page so trang
 * @param int p_number_items so ban ghi/trang
 * @return array()
 */
function be_get_all_seo_box($p_page = 0, $p_number_items = 0) {
	$v_sql = "CALL be_get_all_seo_box($p_page, $p_number_items)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /*
 * Ham kiem tra trung chuyen muc, loai trang, loai box khi cap nhat link campaign
 * @param int p_link_campaign_id 
 * @param int p_category_id  id chuyen muc
 * @param int p_page_type id loai trang
 * @param int p_box_id  id box tin
 * @return array()
 */
function be_check_duplicate_link_campaign($p_link_campaign_id, $p_category_id, $p_page_type, $p_box_id) {
	$v_sql = "CALL be_check_duplicate_link_campaign($p_link_campaign_id, $p_category_id, $p_page_type, $p_box_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs[0];
}

// UC quan tri link event
 /**
 * Ham lay danh sach link event
 * @param	int p_category_id
 * @param	int p_loai_trang
 * @param	int p_ten_link
 * @param	int p_page
 * @param	int p_number_item_per_page
 * @return array
 */
function be_get_all_seo_link_event($p_category_id, $p_loai_trang, $p_nguoi_sua, $p_ten_link, $p_page, $p_number_item_per_page) {
 	$v_sql = "CALL be_get_all_seo_link_event($p_category_id, $p_loai_trang, $p_nguoi_sua, '$p_ten_link', $p_page, $p_number_item_per_page)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /**
 * Ham lay chi tiet 1 link event
 * @param	int p_link_event_id
 * @return array
 */
function be_get_single_seo_link_event($p_link_event_id) {
 	$v_sql = "CALL be_get_single_seo_link_event($p_link_event_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs[0];
}
 
 /**
 * Ham cap nhat chi tiet 1 link event
 * @param	int p_link_event_id
 * @param	string p_ten
 * @param	string p_ten_khong_dau
 * @param	int p_loai_trang
 * @param	string p_opt_category
 * @param	string p_opt_action
 * @param	string p_opt_label
 * @param	string p_opt_non
 * @param	int p_nguoi_sua
 * @return array
 */
function be_update_seo_link_event($p_link_event_id, $p_ten, $p_ten_khong_dau, $p_loai_trang, $p_opt_category, $p_opt_action, $p_opt_label, $p_opt_non, $p_nguoi_sua) {
 	$v_sql = "CALL be_update_seo_link_event($p_link_event_id, '$p_ten', '$p_ten_khong_dau', $p_loai_trang, '$p_opt_category', '$p_opt_action', '$p_opt_label', '$p_opt_non', $p_nguoi_sua)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham cap nhat xuat ban link event theo chuyen muc
 * @param int p_link_event_id 
 * @param int p_category_id
 * @return array()
 */
function be_update_seo_link_event_category($p_link_event_id, $p_category_id) {
	$v_sql = "CALL be_update_seo_link_event_category($p_link_event_id, $p_category_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link event
 * @param int p_link_event_id 
 * @return array()
 */
function be_delete_seo_link_event($p_link_event_id) {
	$v_sql = "CALL be_delete_seo_link_event($p_link_event_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link event xuat ban theo chuyen muc
 * @param int p_link_event_id 
 * @return array()
 */
function be_delete_seo_link_event_category($p_link_event_id) {
	$v_sql = "CALL be_delete_seo_link_event_category($p_link_event_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham danh sach chuyen muc xuat ban link event
 * @param int p_link_event_id 
 * @return array()
 */
function be_get_all_seo_link_event_category($p_link_event_id) {
	$v_sql = "CALL be_get_all_seo_link_event_category($p_link_event_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /*
 * Ham danh sach box tin ban link event
 * @param int p_link_event_id 
 * @return array()
 */
function be_get_all_seo_link_event_box($p_link_event_id) {
	$v_sql = "CALL be_get_all_seo_link_event_box($p_link_event_id)";
	$rs = Gnud_Db_read_query($v_sql);
	return $rs;
}
 
 /*
 * Ham cap nhat link event xuat ban theo box tin
 * @param int p_link_event_id 
 * @param int p_box_id
 * @return array()
 */
function be_update_seo_link_event_box($p_link_event_id, $p_box_id) {
	$v_sql = "CALL be_update_seo_link_event_box($p_link_event_id, $p_box_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
 
 /*
 * Ham xoa link event xuat ban theo box tin
 * @param int p_link_event_id 
 * @return array()
 */
function be_delete_seo_link_event_box($p_link_event_id) {
	$v_sql = "CALL be_delete_seo_link_event_box($p_link_event_id)";
	$rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}
  
 /*
 * Ham kiem tra trung chuyen muc, loai trang, loai box khi cap nhat link event
 * @param int p_link_event_id 
 * @param int p_category_id  id chuyen muc
 * @param int p_page_type id loai trang
 * @param int p_box_id  id box tin
 * @return array()
 */
function be_check_duplicate_link_event($p_link_event_id, $p_category_id, $p_page_type, $p_box_id) {
	$v_sql = "CALL be_check_duplicate_link_event($p_link_event_id, $p_category_id, $p_page_type, $p_box_id)";	
	$rs = Gnud_Db_read_query($v_sql);
	return $rs[0];
}

/**
 * Xóa dữ liệu seo chi tiet theo thiet bi
 * @param	string p_doi_tuong_seo	
 * @param	int p_seo_chi_tiet_id	
 * @return array
 */
function be_delete_seo_chi_tiet_thiet_bi($p_doi_tuong_seo, $p_seo_chi_tiet_id) {
	$sql = "call be_delete_seo_chi_tiet_thiet_bi('$p_doi_tuong_seo', $p_seo_chi_tiet_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
 * Lay danh sach seo chi tiet theo thiet bi
 * @param	string p_doi_tuong_seo	
 * @param	int p_seo_chi_tiet_id	
 * @return array
 */
function be_get_single_seo_chi_tiet_thiet_bi($p_doi_tuong_seo, $p_seo_chi_tiet_id)
{
	$sql = "call be_get_single_seo_chi_tiet_thiet_bi('$p_doi_tuong_seo', $p_seo_chi_tiet_id)";		
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Cap nhat seo chi tiet theo thiet bi 
 * @param	int p_seo_chi_tiet_id	
 * @param	int p_thiet_bi_id	
 * @param	string p_doi_tuong_seo	
 * @return array
 */
function be_update_seo_chi_tiet_thiet_bi($p_seo_chi_tiet_id, $p_thiet_bi_id, $p_doi_tuong_seo)
{
	$sql = "call be_update_seo_chi_tiet_thiet_bi($p_seo_chi_tiet_id, $p_thiet_bi_id, '$p_doi_tuong_seo')";		
	$rs = Gnud_Db_write_query($sql);
	return $rs;
}

/**
* Lay danh sach link redirect 301
* @param int p_nguoi_sua 
* @param int p_trang_thai_xuat_ban 
* @param int p_trang_thai_hien_thi 
* @param int p_thiet_bi 
* @param string p_link_goc 
* @param string p_start_form_date
* @param string p_start_to_date 
* @param string p_end_form_date 
* @param string p_end_to_date 
* @param int p_kieu_sap_xep
* @param int p_page 
* @param int p_number_item_per_page
* @return array
*/
/* Begin Tytv 06/09/2016 chinh_ocm_redirect_301 */
function be_get_all_link_redirect_301($p_nguoi_sua, $p_trang_thai_xuat_ban, $p_trang_thai_hien_thi, $p_thiet_bi, $p_link_goc, $p_start_form_date, $p_start_to_date, $p_end_form_date, $p_end_to_date, $p_kieu_sap_xep, $p_page, $p_number_item_per_page,$p_link_redirect = '')
{
	$sql = "call be_get_all_link_redirect_301($p_nguoi_sua, $p_trang_thai_xuat_ban, $p_trang_thai_hien_thi, $p_thiet_bi, '$p_link_goc', '$p_start_form_date', '$p_start_to_date', '$p_end_form_date', '$p_end_to_date', $p_kieu_sap_xep, '$p_link_redirect', $p_page, $p_number_item_per_page)";		
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
/* End Tytv 06/09/2016 chinh_ocm_redirect_301 */
/**
* Lay chi tiet link redirect 301
* @param int p_redirect_301_id
* @return array
*/
function be_get_single_link_redirect_301($p_redirect_301_id)
{
	$sql = "call be_get_single_link_redirect_301($p_redirect_301_id)";		
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Xoa 1 link redirect 301
* @param int p_redirect_301_id
* @return array
*/
function be_delete_link_redirect_301($p_redirect_301_id)
{
	$sql = "call be_delete_link_redirect_301($p_redirect_301_id)";		
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Cap nhat trang thai xuat ban 1 link redirect 301
* @param int p_redirect_301_id
* @param int p_trang_thai_xuat_ban
* @param int p_nguoi_sua
* @return array
*/
function be_update_trang_thai_link_redirect_301($p_redirect_301_id, $p_trang_thai_xuat_ban, $p_nguoi_sua)
{
	$sql = "call be_update_trang_thai_link_redirect_301($p_redirect_301_id, $p_trang_thai_xuat_ban, $p_nguoi_sua)";		
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Cap nhat trang thai xuat ban 1 link redirect 301
* @param array p_array_data mang luu thong tin ve 1 link redirect
* @return array
*/
function be_update_link_redirect_301($p_array_data)
{
    $v_redirect_id = $p_array_data['pk_redirect_301'];
    $v_link_goc = $p_array_data['c_link_goc'];
    $v_link_redirect = $p_array_data['c_link_redirect'];
    $v_begin_date = $p_array_data['c_begin_date'];
    $v_end_date = $p_array_data['c_end_date'];
    $v_status = $p_array_data['c_status'];
    $v_website_code = $p_array_data['c_website_code'];
    $v_updater_id = $p_array_data['c_updater_id'];
    $v_is_delete = $p_array_data['c_is_delete'];
    $v_danh_sach_thiet_bi = $p_array_data['c_danh_sach_thiet_bi'];    
    
	$sql = "call be_update_link_redirect_301($v_redirect_id, '$v_link_goc','$v_link_redirect', '$v_begin_date','$v_end_date', $v_status, '$v_website_code', $v_updater_id, $v_is_delete, '$v_danh_sach_thiet_bi')";		
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/*
* Kiem tra trung thoi gian xuat ban + thiet bi cua 1 link redirect
* @param int p_redirect_301
* @param string p_link_goc
* @param int p_trang_thai_xuat_ban
* @param int p_nguoi_sua
*/
function be_check_trung_xuat_ban_link_redirect_thiet_bi($p_redirect_301, $p_link_goc, $p_thiet_bi, $p_tu_ngay, $p_den_ngay){
    $sql = "call be_check_trung_xuat_ban_link_redirect_thiet_bi($p_redirect_301, '$p_link_goc', $p_thiet_bi,'$p_tu_ngay','$p_den_ngay')";		
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
 
/**
* Cap nhat du lieu import vao bang temp
* @param string p_session_id
* @param string p_link_goc link goc
* @param string p_link_redirect link redirect
* @param string p_begin_date ngay bat dau
* @param string p_end_date ngay ket thuc
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @return array
*/
function be_update_link_redirect_301_temp($p_session_id, $p_link_goc, $p_link_redirect, $p_begin_date, $p_end_date, $p_trang_thai_du_lieu, $p_noi_dung_loi){
    $sql = "call be_update_link_redirect_301_temp('$p_session_id', '$p_link_goc','$p_link_redirect','$p_begin_date','$p_end_date', $p_trang_thai_du_lieu, '$p_noi_dung_loi')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Lay danh sach link redirect da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_link_redirect_301_temp($p_session_id, $p_trang_thai_du_lieu, $p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_link_redirect_301_temp('$p_session_id', $p_trang_thai_du_lieu, $p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
* xoa link redirect da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_link_redirect_301_temp($p_session_id) {
    $sql = "call be_delete_link_redirect_301_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* kiem tra link redirect import vao bang temp
* @param string p_session_id
* @return array
*/
function be_kiem_tra_da_import_link_redirect_vao_bang_tem($p_session_id) {
    $sql = "call be_kiem_tra_da_import_link_redirect_vao_bang_tem('$p_session_id')";		
    $rs = Gnud_Db_read_query($sql);
	return intval($rs[0]['c_result']);
}

/**
* kiem tra link redirect import vao bang temp
* @param string p_session_id
* @return array
*/
function be_get_all_link_goc($p_link_goc, $p_thiet_bi, $p_tu_ngay) {
    $sql = "call be_get_all_link_goc('$p_link_goc', $p_thiet_bi, '$p_tu_ngay')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
 /** Begin anhpt1 10/10/2015 seo_chi_tiet_profile
* Lay danh sach seo chi tiet title, desc, keyword cho su kien
* @param 	string p_cat_id_list  id chuyen muc xuat ban su kien
* @param 	string p_user_id  id nguoi sua
* @param    string p_status_list  danh sach trang thai
* @param    string p_event_type_list  danh sach loai su kien
* @param	int p_event_id id su kien
* @param 	string p_event_name  
* @param 	string p_orderby_clause 
* @param 	int p_thiet_bi
* @param 	int p_page 
* @param   int p_number_item_per_page
* @param    varchar(4000) p_ma_ga mã GA cần tìm
* @return array
*/
function be_get_all_seo_chi_tiet_profile($p_profile_list_id, $p_cate_id, $p_user_id, $p_status_list, $p_loai_thiet_bi, $p_profile_name, $p_ma_ga, $p_orderby_clause, $p_page, $p_number_item_per_page){	
	$sql = "call be_get_all_seo_chi_tiet_profile('$p_profile_list_id', '$p_cate_id', '$p_user_id','$p_status_list', $p_loai_thiet_bi,'$p_profile_name','$p_ma_ga', '$p_orderby_clause', $p_page, $p_number_item_per_page)";
    $rs = Gnud_Db_read_query($sql);
    /* Begin anhpt1 12/07/2016 export_import_title_des_key_profile */
	for ($i = 0, $s = sizeof($rs); $i < $s; ++$i){
		$rs[$i]['arr_ga'] = be_danh_sach_ga(-1, 3, intval($rs[$i]['fk_profile']), -1, '', '', -1, -1);
	}/* End anhpt1 12/07/2016 export_import_title_des_key_profile */
	return $rs;	
}
/**
* update trạng thái seo chi tiết profile
* @param string p_seo_chi_tiet_profile 		id profile
* @param string p_trang_thai_xuat_ban 		Trạng thái xuất bản của profile
* @param string p_nguoi_sua 				Người sửa
* @return array
*/
function be_update_trang_thai_seo_chi_tiet_profile($p_seo_chi_tiet_profile, $p_trang_thai_xuat_ban,$p_nguoi_sua) {
    $sql = "call be_update_trang_thai_seo_chi_tiet_profile($p_seo_chi_tiet_profile, $p_trang_thai_xuat_ban,'$p_nguoi_sua')";
    $rs = Gnud_Db_write_query($sql);
	return $rs;
}
/** Begin anhpt1 10/10/2015 be_get_single_seo_chi_tiet_profile
* Lay chi tiết seo chi tiet title, desc, keyword cho su kien
* @param 	string p_profile_list_id  id profile
* @return array
*/
function be_get_single_seo_chi_tiet_profile($p_profile_id){	
	$sql = "call be_get_single_seo_chi_tiet_profile($p_profile_id)";	
	$rs = Gnud_Db_read_query($sql);
    /* Begin anhpt1 12/07/2016 export_import_title_des_key_profile */
	if(intval($rs[0]['fk_profile']) > 0){
		$rs[0]['arr_ga'] = be_danh_sach_ga(-1, 3, $rs[0]['fk_profile'], -1, '', '', -1, -1);
	}/* End anhpt1 12/07/2016 export_import_title_des_key_profile */
	return $rs[0];	
}
/**
* Xóa 1 seo chi tiết profile
* @param string p_seo_chi_tiet_profile 		id profile
* @return array
*/
function be_delete_seo_chi_tiet_profile($p_seo_chi_tiet_profile) {
    $sql = "call be_delete_seo_chi_tiet_profile($p_seo_chi_tiet_profile)";
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* Update seo chi tiết 1 profile
* @param bigint p_seo_chi_tiet_profile_id 		id seo profile
* @param bigint p_id_profile 					id profile
* @param string p_gacode 						ga code
* @param string p_title 						title
* @param string p_desc 							desc
* @param string p_keyword 						keyword
* @param string p_slug 							slug
* @return array
*/
// begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
function be_update_seo_chi_tiet_profile($p_seo_chi_tiet_profile_id
										,$p_id_profile
										,$p_gacode
										,$p_title
										,$p_desc
										,$p_keyword
										,$p_slug
										,$p_canonical
										,$p_trang_thai_xuat_ban
										,$p_nguoi_sua
										,$p_tu_khoa_in_nghieng= ''
										,$p_tu_khoa_in_dam= ''
										,$p_tu_khoa_gach_chan=''
										,$p_thiet_bi = 1
                                        ,$p_title_mxh
                                        ,$p_des_mxh){	
	$sql = "call be_update_seo_chi_tiet_profile($p_seo_chi_tiet_profile_id
												,$p_id_profile
												,'$p_gacode'
												,'$p_title'
												,'$p_desc'
												,'$p_keyword'
												,'$p_slug'
												,'$p_canonical'
												,$p_trang_thai_xuat_ban
												,'$p_nguoi_sua'
												,'$p_tu_khoa_in_nghieng'
												,'$p_tu_khoa_in_dam'
												,'$p_tu_khoa_gach_chan'
												,$p_thiet_bi
                                                ,'$p_title_mxh'
                                                ,'$p_des_mxh')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}
// end 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
/**
* Lấy tag theo id
* @param bigint p_profile_id 	id profile
* @return array
*/
function be_profile_theo_id($p_profile_id){	
	$sql = "call be_profile_theo_id($p_profile_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs;	
}

// begin 11/11/2015 tuyennt bo_sung_text_link_logo_profile

/**
* Xóa 1 text link xuat ban tren profile
* @param   int p_textlink_id		-- : id textlink
* @return array
*/
function be_delete_seo_text_link_profile($p_textlink_id){	
	$sql = "call be_delete_seo_text_link_profile($p_textlink_id)";	
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];	
}

/**
* Xuat ban textlink cho profile
* @param   int $p_textlink_id id textlink
* @param   int $p_profile_id id profile
* @return array
*/
function be_update_text_link_profile($p_textlink_id, $p_profile_id){
	$sql = "call be_update_text_link_profile($p_textlink_id, $p_profile_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung profile xuat ban cho textlink
* @param   
		int p_textlink_id		 : id textlink
		int $p_profile_id       : id profile
		int p_textlink_type 	 : loai text link
		string p_publish_from_date 	: ngay xuat ban bat dau
		int     $p_thiet_bi id thiet bi   
* @return array
*/
function be_check_duplicate_textlink_profile($p_textlink_id, $p_profile_id, $p_textlink_type, $p_publish_from_date, $p_thiet_bi) 
{
	$sql = "call be_check_duplicate_textlink_profile($p_textlink_id, $p_profile_id, $p_textlink_type, '$p_publish_from_date',  $p_thiet_bi)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
// end 11/11/2015 tuyennt bo_sung_text_link_logo_profile
/* Begin anhpt1 30/5/2016 be_keyword_link */
/**
* Lay danh sach keyword link
*@param int p_id_chuyen_muc	 ID chuyên mục
*@param int p_nguoi_sua ID người sửa
*@param int p_trang_thai_xuat_ban	Trạng thái xuất bản(0: chưa xuất bản, 1 đã xuất bản)
*@param int p_trang_thai_hien_thi	Trạng thái hiển thị(0: không hiển thị, 1: hiển thị)
*@param string p_ten_keyword Keyword
*@param int p_id_bai_viet	ID bài viết
*@param int p_id_su_kien	ID sự kiện
*@param int p_id_profile	ID profile
*@param string p_ngay_bat_dau_tu_ngay	Ngày bắt đầu từ ngày
*@param string p_ngay_bat_dau_den_ngay	Ngày bắt đầu đến ngày
*@param string p_ngay_ket_thuc_tu_ngay	Ngày kết thúc từ ngày
*@param string p_ngay_ket_thuc_den_ngay	Ngày kết thúc đến ngày
*@param string p_page Trang cần xem
*@param string p_number_items Số bản ghi cần xem/trang
*@return array
*/
/* Begin 23/08/2016 TuyenNT toi_uu_chuc_nang_quan_tri_keword_link_bo_sung_dieu_kien_loc_theo_lien_ket */
function be_get_all_keyword_link($p_id_chuyen_muc, $p_nguoi_sua, $p_trang_thai_xuat_ban, $p_trang_thai_hien_thi, $p_ten_keyword, $p_id_bai_viet, $p_id_su_kien, $p_id_profile, $p_ngay_bat_dau_tu_ngay, $p_ngay_bat_dau_den_ngay, $p_ngay_ket_thuc_tu_ngay, $p_ngay_ket_thuc_den_ngay, $p_page, $p_number_items,$p_is_thong_ke = 0,$p_id_keyword = 0,$p_url = '',$p_order = 0) 
{
	$sql = "call be_get_all_keyword_link($p_id_chuyen_muc, $p_nguoi_sua, $p_trang_thai_xuat_ban, $p_trang_thai_hien_thi, '$p_ten_keyword', $p_id_bai_viet, $p_id_su_kien, $p_id_profile, '$p_ngay_bat_dau_tu_ngay', '$p_ngay_bat_dau_den_ngay', '$p_ngay_ket_thuc_tu_ngay', '$p_ngay_ket_thuc_den_ngay', $p_page, $p_number_items,$p_is_thong_ke,$p_id_keyword,'$p_url',$p_order)";
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/* end 23/08/2016 TuyenNT toi_uu_chuc_nang_quan_tri_keword_link_bo_sung_dieu_kien_loc_theo_lien_ket */
/**
* Lay thong tin chi tiet 1 keyword link
*@param int p_id_keywrod	ID keyword link
*@return array
*/
function be_get_single_keyword_link($p_id_keywrod) 
{
	$sql = "call be_get_single_keyword_link($p_id_keywrod)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Xoa 1 keyword link
*@param int p_id_keywrod	ID keyword link
*@return array
*/
function be_delete_keyword_link($p_id_keywrod) 
{
	$sql = "call be_delete_keyword_link($p_id_keywrod)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Cap nhat trang thai cua keyword link tren man hinh danh sach
*@param int p_id_keywrod	ID keyword link
*@param int p_trong_so	Trong so
*@param int p_trang_thai	trang thai xuat ban
*@param int p_id_nguoi_sua	ID nguoi sua
*@return array
*/
function be_update_trang_thai_keyword_link($p_id_keywrod, $p_trong_so, $p_trang_thai, $p_nguoi_sua) 
{
	$sql = "call be_update_trang_thai_keyword_link($p_id_keywrod, $p_trong_so, $p_trang_thai, $p_nguoi_sua)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Lay danh sach loai trang xuat ban keyword link
*@param int p_id_keyword	ID keyword link
*@return array
*/
function be_get_all_trang_xuat_ban_keyword_link($p_id_keyword) {
    $sql = "call be_get_all_trang_xuat_ban_keyword_link($p_id_keyword)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* Lay danh sach loai trang xuat ban keyword link
*@param int p_id_keyword	ID keyword link
*@return array
*/
function be_get_all_ten_trang_xuat_ban_keyword_link($p_id_keyword) {
    $sql = "call be_get_all_ten_trang_xuat_ban_keyword_link($p_id_keyword)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung xuat ban keyword link theo chuyen muc
*@param p_id_keyword	BIGINT, 
*@param p_id_chuyen_muc INT,
*@param p_xuat_ban_tu_ngay VARCHAR(50),
*@param p_xuat_ban_den_ngay VARCHAR(50)
*@return array
*/

function be_kiem_tra_trung_keyword_link_chuyen_muc($p_id_keyword,$p_ten_keyword,$p_id_chuyen_muc, $p_xuat_ban_tu_ngay, $p_xuat_ban_den_ngay) {
    $sql = "call be_kiem_tra_trung_keyword_link_chuyen_muc($p_id_keyword,'$p_ten_keyword',$p_id_chuyen_muc, '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung xuat ban keyword link theo su kien
*@param p_id_keyword	BIGINT, 
*@param p_id_su_kien INT,
*@param p_xuat_ban_tu_ngay VARCHAR(50),
*@param p_xuat_ban_den_ngay VARCHAR(50)
*@return array
*/

function be_kiem_tra_trung_keyword_link_su_kien($p_id_keyword,$p_ten_keyword, $p_id_su_kien, $p_xuat_ban_tu_ngay, $p_xuat_ban_den_ngay) {
    $sql = "call be_kiem_tra_trung_keyword_link_su_kien($p_id_keyword,'$p_ten_keyword',$p_id_su_kien, '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}


/**
* Kiem tra trung xuat ban keyword link theo bai viet
*@param p_id_keyword	BIGINT, 
*@param p_id_bai_viet INT,
*@param p_xuat_ban_tu_ngay VARCHAR(50),
*@param p_xuat_ban_den_ngay VARCHAR(50)
*@return array
*/

function be_kiem_tra_trung_keyword_link_bai_viet($p_id_keyword,$p_ten_keyword, $p_id_bai_viet, $p_xuat_ban_tu_ngay, $p_xuat_ban_den_ngay) {
    $sql = "call be_kiem_tra_trung_keyword_link_bai_viet($p_id_keyword,'$p_ten_keyword',$p_id_bai_viet, '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay')";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
* Kiem tra trung xuat ban keyword link theo profile
*@param p_id_keyword	BIGINT, 
*@param p_id_profile INT,
*@param p_xuat_ban_tu_ngay VARCHAR(50),
*@param p_xuat_ban_den_ngay VARCHAR(50)
*@return array
*/

function be_kiem_tra_trung_keyword_link_profile($p_id_keyword, $p_id_profile,$p_ten_keyword, $p_xuat_ban_tu_ngay, $p_xuat_ban_den_ngay) {
    $sql = "call be_kiem_tra_trung_keyword_link_profile($p_id_keyword, $p_id_profile,'$p_ten_keyword', '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay')";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
*Ham cap nhat keyword link
*@param p_data array
*@return array
*/

function be_sitemap_keyword_update($p_data) {
    $v_id_keyword = $p_data["p_id_keyword"];
    $v_ten_keyword = fw24h_replace_bad_char($p_data["txt_ten_keyword"]);
    $v_ten_keyword_khong_dau = strtolower(_utf8_to_ascii($v_ten_keyword));
    $v_slug_keyword =  str_replace('/', '-', preg_replace("# +#", '-', $v_ten_keyword_khong_dau));
    $v_is_all_news = intval($p_data["chk_all_news"]);
    $v_xuat_ban_tu_ngay = _sql_format_date($p_data["txt_xuat_ban_tu_ngay"]);
    $v_xuat_ban_den_ngay = _sql_format_date($p_data["txt_xuat_ban_den_ngay"]);
    $v_danh_sach_chuyen_muc = $p_data["txt_danh_sach_chuyen_muc"];
    $v_danh_sach_su_kien = $p_data["txt_danh_sach_su_kien"];
    $v_danh_sach_profile = $p_data["txt_danh_sach_profile"];
    $v_danh_sach_bai_viet = $p_data["txt_danh_sach_bai_viet"];
    $v_url =  fw24h_replace_bad_char($p_data["txt_link_gan_keyword"]);            
    $v_trang_thai_xuat_ban = intval($p_data["sel_trang_thai"]);
    $v_trong_so = intval($p_data["txt_trong_so"]);
    $v_to_day = date('Y-m-d H:i:s');
    $v_url_type = 1;
    $v_meta_title=$v_meta_description =$v_meta_keyword = '';    
    $v_nguoi_tao = $v_nguoi_duyet = $v_nguoi_sua=$_SESSION['user_id'];
    $v_sql = "CALL be_sitemap_keyword_update($v_id_keyword,'$v_ten_keyword','EVA.VN',$v_is_all_news,'$v_url',$v_url_type ,$v_trang_thai_xuat_ban ,1 ,'$v_to_day','$v_to_day','$v_ten_keyword_khong_dau','$v_slug_keyword','$v_meta_title','$v_meta_description','$v_meta_keyword',$v_nguoi_tao,$v_nguoi_sua , $v_nguoi_duyet,$v_trong_so,'$v_danh_sach_chuyen_muc','$v_danh_sach_su_kien','$v_danh_sach_bai_viet','$v_danh_sach_profile','$v_xuat_ban_tu_ngay','$v_xuat_ban_den_ngay',0)";
    $rs = Gnud_Db_write_query($v_sql);
	return $rs[0];
}

/**
* Kiem tra trung xuat ban keyword link
*@param p_id_keyword	BIGINT, 
*@param p_id_profile INT,
*@param p_xuat_ban_tu_ngay VARCHAR(50),
*@param p_xuat_ban_den_ngay VARCHAR(50)
*@return array
*/

function be_kiem_tra_trung_ten_keyword($p_id_keyword,$p_ten_keyword, $p_xuat_ban_tu_ngay, $p_xuat_ban_den_ngay) {
    $sql = "call be_kiem_tra_trung_ten_keyword($p_id_keyword,'$p_ten_keyword', '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay')";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* lấy trọng số lớn nhất keyword link
*@param không
*@return array
*/
function be_get_order_keyword_max(){
    $sql = "call be_get_order_keyword_max()";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];  
}

/**
* kiem tra link redirect import vao bang temp
* @param string p_session_id
* @return array
*/
function be_kiem_tra_da_import_keyword_link_vao_bang_tem($p_session_id) {
    $sql = "call be_kiem_tra_da_import_keyword_link_vao_bang_tem('$p_session_id')";		
    $rs = Gnud_Db_read_query($sql);
	return intval($rs[0]['c_result']);
}
function be_update_keyword_link_temp(
        $p_session_id
        ,$p_keyword
        ,$p_url
        ,$p_begin_date
        ,$p_end_date 
        ,$p_ds_id_chuyen_muc
        ,$p_ds_id_profile
        ,$p_ds_id_event
        ,$p_ds_id_news
        ,$p_trang_thai_du_lieu
        ,$p_noi_dung_loi){
    $v_sql = "CALL be_update_keyword_link_temp(
        $p_session_id
        ,'$p_keyword'
        ,'$p_url'
        ,'$p_begin_date'
        ,'$p_end_date' 
        ,'$p_ds_id_chuyen_muc'
        ,'$p_ds_id_profile'
        ,'$p_ds_id_event'
        ,'$p_ds_id_news'
        ,$p_trang_thai_du_lieu
        ,'$p_noi_dung_loi')";
    $rs = Gnud_Db_write_query($v_sql);
}
/**
* Lay danh sach link redirect da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_keyword_link_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_keyword_link_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_keyword_link_temp($p_session_id) {
    $sql = "call be_delete_keyword_link_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* lấy keyword link vào bảng temp theo id
* @param bigint id keyword
* @return array
*/
function be_get_keyword_link_vao_bang_tem_theo_id($p_id_keyword){
    $sql = "call be_get_keyword_link_vao_bang_tem_theo_id($p_id_keyword)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* lấy danh sách chuyên mục
* @param $p_list_id_cate list id chuyen muc
* @return array
*/
function be_get_chuyen_muc_theo_list_id_cate($p_list_id_cate){
    $sql = "call be_get_chuyen_muc_theo_list_id_cate('$p_list_id_cate')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* lấy danh sách profile
* @param $p_list_id_cate list id profile
* @return array
*/
function be_get_profile_theo_list_id_profile($p_list_id_profile){
    $sql = "call be_get_profile_theo_list_id_profile('$p_list_id_profile')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* lấy danh sách event
* @param $p_list_id_event list id event
* @return array
*/
function be_get_event_theo_list_id_event($p_list_id_event){
    $sql = "call be_get_event_theo_list_id_event('$p_list_id_event')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* lấy danh sách profile
* @param $p_list_id_news list id news
* @return array
*/
function be_get_news_theo_list_id_news($p_list_id_news){
    $sql = "call be_get_news_theo_list_id_news('$p_list_id_news')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/* Begin anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
/**
* lấy danh sách bài viết theo ngày
* @param $p_date ngày xuất bản bài viết,
* @param $p_page số dòng,
* @param $p_number_item_per_page số trang,
* @return array
*/
function be_get_all_news_theo_ngay($p_date,$p_page,$p_number_item_per_page){
    $sql = "call be_get_all_news_theo_ngay('$p_date',$p_page,$p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_chi_tiet_bai_viet_temp($p_session_id) {
    $sql = "call be_delete_seo_chi_tiet_bai_viet_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật seo chi tiết bài viết vào bảng temp
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_bai_viet_temp(
    $p_session_id
    ,$p_id_bai_viet
    // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
    ,$p_title
    // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
    ,$p_title_ascii
    ,$p_desc
    ,$p_keyword
    ,$p_slug
    ,$p_thiet_bi
    ,$p_user_id
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi
){
    // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
    $sql = "call be_update_seo_chi_tiet_bai_viet_temp($p_session_id
                                                    ,'$p_id_bai_viet'
                                                    ,'$p_title'
                                                    ,'$p_title_ascii'
                                                    ,'$p_desc'
                                                    ,'$p_keyword'
                                                    ,'$p_slug'
                                                    ,'$p_thiet_bi'
                                                    ,'$p_user_id'
                                                    ,'$p_trang_thai_du_lieu'
                                                    ,'$p_noi_dung_loi')";
    // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/**
* Lay danh sach link redirect da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_chi_tiet_bai_viet_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_chi_tiet_bai_viet_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Lay danh sach seo chi tiet bài viết theo id
* @param string $p_seo_chi_tiet_id id seo chi tiêt tmp
* @return array
*/
function be_get_seo_chi_tiet_bai_viet_tmp_theo_id($p_seo_chi_tiet_id){
    $sql = "call be_get_seo_chi_tiet_bai_viet_tmp_theo_id($p_seo_chi_tiet_id)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
 * Lay danh sach seo chi tiet cho bai viet
 * @param 	string p_cat_id_list  Danh sach id chuyen muc
 * @param 	int p_user_id  id nguoi sua
 * @param   string p_status_list  danh sach trang thai
 * @param   string p_ten_bai_viet
 * @param	int p_id_bai_viet
 * @param 	string p_orderby_clause menh de order by
 * @param	int p_thiet_bi
 * @param 	int p_page 
 * @param   int p_number_item_per_page
 * @return array
 */
function be_get_all_seo_chi_tiet_bai_viet_export($p_cat_id_list, $p_user_id, $p_status_list, $p_ten_bai_viet, $p_id_bai_viet, $p_orderby_clause, $p_thiet_bi,$p_page, $p_number_item_per_page){	
	$sql = "call be_get_all_seo_chi_tiet_bai_viet_export('$p_cat_id_list', $p_user_id, '$p_status_list','$p_ten_bai_viet', $p_id_bai_viet,'$p_orderby_clause',$p_thiet_bi, $p_page, $p_number_item_per_page)";	
    $rs = Gnud_Db_read_query($sql);
	return $rs;	
}
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_update_all_seo_chi_tiet_bai_viet_duoc_export() {
    $sql = "call be_update_all_seo_chi_tiet_bai_viet_duoc_export()";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/* End anhpt1 27/06/2016 export_import_title_des_key_bai_viet */

/* Begin anhpt1 29/06/2016 export_import_title_des_key_su_kien */
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_chi_tiet_su_kien_temp($p_session_id) {
    $sql = "call be_delete_seo_chi_tiet_su_kien_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật seo chi tiết bài viết vào bảng temp
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_su_kien_temp(
    $p_session_id
    ,$p_id_su_kien
    ,$p_title
    ,$p_desc
    ,$p_keyword
    ,$p_slug
    ,$p_thiet_bi
    ,$p_user_id
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi
){
    $sql = "call be_update_seo_chi_tiet_su_kien_temp($p_session_id
                                                    ,'$p_id_su_kien'
                                                    ,'$p_title'
                                                    ,'$p_desc'
                                                    ,'$p_keyword'
                                                    ,'$p_slug'
                                                    ,'$p_thiet_bi'
                                                    ,'$p_user_id'
                                                    ,'$p_trang_thai_du_lieu'
                                                    ,'$p_noi_dung_loi')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/**
* Lay danh sach seo chi tiet da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_chi_tiet_su_kien_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_chi_tiet_su_kien_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Lay danh sach seo chi tiet bài viết theo id
* @param string $p_seo_chi_tiet_id id seo chi tiêt tmp
* @return array
*/
function be_get_seo_chi_tiet_su_kien_tmp_theo_id($p_seo_chi_tiet_id){
    $sql = "call be_get_seo_chi_tiet_su_kien_tmp_theo_id($p_seo_chi_tiet_id)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* update tat ca seo chi tiet su kien duoc export
* @param string p_session_id
* @return array
*/
function be_update_all_seo_chi_tiet_su_kien_duoc_export() {
    $sql = "call be_update_all_seo_chi_tiet_su_kien_duoc_export()";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* update seo chi tiet su kien duoc export
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_su_kien_import($p_event,$p_title,$p_desc,$p_keyword,$p_slug,$p_nguoi_sua,$p_thiet_bi){
    $sql = "call be_update_seo_chi_tiet_su_kien_import($p_event,'$p_title','$p_desc','$p_keyword','$p_slug','$p_nguoi_sua',$p_thiet_bi)";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* update seo chi tiet su kien duoc export
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_bai_viet_import($p_news,$p_title,$p_desc,$p_keyword,$p_slug,$p_nguoi_sua,$p_thiet_bi){
    $sql = "call be_update_seo_chi_tiet_bai_viet_import($p_news,'$p_title','$p_desc','$p_keyword','$p_slug','$p_nguoi_sua',$p_thiet_bi)";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/* End anhpt1 29/06/2016 export_import_title_des_key_su_kien */

/* Begin anhpt1 12/07/2016 export_import_title_des_key_profile */
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_chi_tiet_profile_temp($p_session_id) {
    $sql = "call be_delete_seo_chi_tiet_profile_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật seo chi tiết bài viết vào bảng temp
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_profile_temp(
    $p_session_id
    ,$p_id_profile
    ,$p_title
    ,$p_desc
    ,$p_keyword
    ,$p_slug
    ,$p_thiet_bi
    ,$p_user_id
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi
){
    $sql = "call be_update_seo_chi_tiet_profile_temp($p_session_id
                                                    ,'$p_id_profile'
                                                    ,'$p_title'
                                                    ,'$p_desc'
                                                    ,'$p_keyword'
                                                    ,'$p_slug'
                                                    ,'$p_thiet_bi'
                                                    ,'$p_user_id'
                                                    ,'$p_trang_thai_du_lieu'
                                                    ,'$p_noi_dung_loi')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/**
* Lay danh sach seo chi tiet da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_chi_tiet_profile_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_chi_tiet_profile_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Lay danh sach seo chi tiet bài viết theo id
* @param string $p_seo_chi_tiet_id id seo chi tiêt tmp
* @return array
*/
function be_get_seo_chi_tiet_profile_tmp_theo_id($p_seo_chi_tiet_id){
    $sql = "call be_get_seo_chi_tiet_profile_tmp_theo_id($p_seo_chi_tiet_id)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* update tat ca seo chi tiet profile duoc export
* @param string p_session_id
* @return array
*/
function be_update_all_seo_chi_tiet_profile_duoc_export() {
    $sql = "call be_update_all_seo_chi_tiet_profile_duoc_export()";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* update seo chi tiet profile trạng thái hợp lệ khi import
* @param bigint $p_id_profile,
* @param string	$p_title,
* @param string	$p_desc,
* @param string	$p_keyword,
* @param string	$p_slug,
* @param string	$p_nguoi_sua,
* @param int $p_thiet_bi
* @return INT
*/
function be_update_seo_chi_tiet_profile_import(
	$p_id_profile,
	$p_title,
	$p_desc,
	$p_keyword,
	$p_slug,
	$p_nguoi_sua,
    $p_thiet_bi
){
    $sql = "call be_update_seo_chi_tiet_profile_import(	$p_id_profile,
            '$p_title',
            '$p_desc',
            '$p_keyword',
            '$p_slug',
            '$p_nguoi_sua',
            $p_thiet_bi)";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/* End anhpt1 12/07/2016 export_import_title_des_key_profile */
/* Begin anhpt1 13/07/2016 export_import_title_des_key_tag */
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_tags_temp($p_session_id) {
    $sql = "call be_delete_seo_tags_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật seo chi tiết bài viết vào bảng temp
* @param string p_session_id
* @return array
*/        
function be_update_seo_tags_temp(
    $p_session_id
    ,$p_id_tags
    ,$p_ten_tag
    ,$p_title
    ,$p_desc
    ,$p_canonical
    ,$p_slug
    ,$p_user_id
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi
){
    $sql = "call be_update_seo_tags_temp($p_session_id
                                                    ,$p_id_tags
                                                    ,'$p_ten_tag'
                                                    ,'$p_title'
                                                    ,'$p_desc'
                                                    ,'$p_canonical'
                                                    ,'$p_slug'
                                                    ,'$p_user_id'
                                                    ,'$p_trang_thai_du_lieu'
                                                    ,'$p_noi_dung_loi')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/**
* Lay danh sach seo chi tiet da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_tags_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_tags_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Lay danh sach seo chi tiet bài viết theo id
* @param string $p_seo_chi_tiet_id id seo chi tiêt tmp
* @return array
*/
function be_get_seo_tags_tmp_theo_id($p_seo_chi_tiet_id){
    $sql = "call be_get_seo_tags_tmp_theo_id($p_seo_chi_tiet_id)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* Lay danh sách seo tags theo tên tags
* @param string $p_name_tags tên tags
* @return array
*/
function be_get_seo_tags_theo_tags($p_name_tags){
    $sql = "call be_get_seo_tags_theo_tags('$p_name_tags')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* cập nhật all seo tags được export
* @param string $p_name_tags tên tags
* @return array
*/
function be_update_all_seo_tags_duoc_export($p_session_id){
    $sql = "call be_update_all_seo_tags_duoc_export('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật all seo tags được export
* @param bigint(20) $p_id_tags
* @param varchar $p_title 
* @param string $p_desc
* @param string $p_canonical
* @param int $p_slug
* @param int $p_user_id
* @return array
*/
function be_update_seo_tags_import($p_id_tags,$p_title,$p_desc,$p_canonical,$p_slug,$p_user_id){
    $sql = "call be_update_seo_tags_import($p_id_tags,'$p_title','$p_desc','$p_canonical','$p_slug',$p_user_id)";
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/* End anhpt1 13/07/2016 export_import_title_des_key_tag */
/* Begin anhpt1 29/07/2016 export_import_title_desc_chuyen_muc */
/**
* xoa keyword da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_chi_tiet_chuyen_muc_temp($p_session_id) {
    $sql = "call be_delete_seo_chi_tiet_chuyen_muc_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật seo chi tiết bài viết vào bảng temp
* @param string p_session_id
* @return array
*/
function be_update_seo_chi_tiet_chuyen_muc_temp(
    $p_session_id
    ,$p_id_category
    ,$p_title
    ,$p_desc
    ,$p_keyword
    ,$p_slug
    ,$p_thiet_bi
    ,$p_user_id
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi
){
    $sql = "call be_update_seo_chi_tiet_chuyen_muc_temp($p_session_id
                                                    ,'$p_id_category'
                                                    ,'$p_title'
                                                    ,'$p_desc'
                                                    ,'$p_keyword'
                                                    ,'$p_slug'
                                                    ,'$p_thiet_bi'
                                                    ,'$p_user_id'
                                                    ,'$p_trang_thai_du_lieu'
                                                    ,'$p_noi_dung_loi')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/**
* Lay danh sach seo chi tiet da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_chi_tiet_chuyen_muc_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_chi_tiet_chuyen_muc_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Lay danh sach seo chi tiet bài viết theo id
* @param string $p_seo_chi_tiet_id id seo chi tiêt tmp
* @return array
*/
function be_get_seo_chi_tiet_chuyen_muc_tmp_theo_id($p_seo_chi_tiet_id){
    $sql = "call be_get_seo_chi_tiet_chuyen_muc_tmp_theo_id($p_seo_chi_tiet_id)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/*
* update tat ca seo chi tiet profile duoc export
* @param string p_session_id
* @return array
*/
function be_update_all_seo_chi_tiet_chuyen_muc_duoc_export() {
    $sql = "call be_update_all_seo_chi_tiet_chuyen_muc_duoc_export()";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* update seo chi tiet profile trạng thái hợp lệ khi import
* @param bigint $p_id_profile,
* @param string	$p_title,
* @param string	$p_desc,
* @param string	$p_keyword,
* @param string	$p_slug,
* @param string	$p_nguoi_sua,
* @param int $p_thiet_bi
* @return INT
*/
function be_update_seo_chi_tiet_chuyen_muc_import(
	$p_id_chuyen_muc,
	$p_title,
	$p_desc,
	$p_keyword,
	$p_slug,
	$p_nguoi_sua,
    $p_thiet_bi
){
    $sql = "call be_update_seo_chi_tiet_chuyen_muc_import(
            $p_id_chuyen_muc,
            '$p_title',
            '$p_desc',
            '$p_keyword',
            '$p_slug',
            $p_nguoi_sua,
            $p_thiet_bi)";
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
} /*End anhpt1 29/07/2016 export_import_title_desc_chuyen_muc */
/* Begin anhpt1 2/08/2016 export_import_text_link */
/**
* update seo chi tiet profile trạng thái hợp lệ khi import
* @param bigint $p_id_profile,
* @param string	$p_title,
* @param string	$p_desc,
* @param string	$p_keyword,
* @param string	$p_slug,
* @param string	$p_nguoi_sua,
* @param int $p_thiet_bi
* @return INT
*/
function be_get_data_text_link_theo_id($p_id_text_link){
    $sql = "call be_get_data_text_link_theo_id($p_id_text_link)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/**
* xoa tex link da import vao bang temp
* @param string p_session_id
* @return array
*/
function be_delete_seo_text_link_temp($p_session_id) {
    $sql = "call be_delete_seo_text_link_temp('$p_session_id')";		
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/**
* cập nhật dữ liệu text link vào bảng temp
* @param string p_session_id
* @return array
*/
function be_update_seo_text_link_temp(
    $p_session_id
    ,$p_ten
    ,$p_ten_khong_dau
    ,$p_loai
    ,$p_noi_dung
    ,$p_tu_ngay
    ,$p_den_ngay
    ,$p_thiet_bi
    ,$p_ds_id_chuyen_muc
    ,$p_ds_id_bai_viet
    ,$p_ds_id_su_kien
    ,$p_ds_id_profie
	,$p_user_name
    ,$p_trang_thai_du_lieu
    ,$p_noi_dung_loi){
        $sql = "call be_update_seo_text_link_temp(
                $p_session_id
                ,'$p_ten'
                ,'$p_ten_khong_dau'
                ,'$p_loai'
                ,'$p_noi_dung'
                ,'$p_tu_ngay'
                ,'$p_den_ngay'
                ,$p_thiet_bi
                ,'$p_ds_id_chuyen_muc'
                ,'$p_ds_id_bai_viet'
                ,'$p_ds_id_su_kien'
                ,'$p_ds_id_profie'
                ,'$p_user_name'
                ,$p_trang_thai_du_lieu
                ,'$p_noi_dung_loi')";
        $rs = Gnud_Db_write_query($sql);
        return $rs[0];
    }
/**
* Lay danh sach seo chi tiet da import vao bang temp
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_get_all_seo_text_link_temp($p_session_id,$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page) {
    $sql = "call be_get_all_seo_text_link_temp('$p_session_id',$p_trang_thai_du_lieu,$p_page, $p_number_item_per_page)";		
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
* Cập nhật seo text link
* @param string p_session_id
* @param int p_trang_thai_du_lieu 
* @param string p_noi_dung_loi
* @param int p_page
* @param int p_number_item_per_page
* @return array
*/
function be_update_seo_text_link_import(
	$p_ten,
	$p_ten_khong_dau,
	$p_loai,
	$p_noi_dung,
	$p_tu_ngay,
	$p_den_ngay,
    $p_thiet_bi,
    $p_ds_id_chuyen_muc,
    $p_ds_id_bai_viet,
    $p_ds_id_su_kien,
    $p_ds_id_profile,
    $p_user_name
){
    $sql = "call be_update_seo_text_link_import(	'$p_ten',
            '$p_ten_khong_dau',
            $p_loai,
            '$p_noi_dung',
            '$p_tu_ngay',
            '$p_den_ngay',
            $p_thiet_bi,
            '$p_ds_id_chuyen_muc',
            '$p_ds_id_bai_viet',
            '$p_ds_id_su_kien',
            '$p_ds_id_profile',
            '$p_user_name')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/**
* lấy seo text link theo id temp
* @param string $p_id_seo id seo temp
* @return array
*/
function be_get_seo_text_link_tmp_theo_id($p_id_seo){
    $sql = "call be_get_seo_text_link_tmp_theo_id('$p_id_seo')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/* End anhpt1 2/08/2016 export_import_text_link */

/* Begin 03/03/2017 LuanAD XLCYCMHENG_17432_toi_uu_redirect_301_ocm_24h */
/**
* Lấy thông tin redirect 301 theo path của url
* @param int $p_link_redirect_id id của redirect
* @param string $p_path path url
* @return array
*/
function be_get_link_301_by_path($p_link_redirect_id, $p_path) {
    $sql = "call be_get_link_301_by_path($p_link_redirect_id, '$p_path')";		
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/* End 03/03/2017 LuanAD XLCYCMHENG_17432_toi_uu_redirect_301_ocm_24h */

/* 
//Begin 11-09-2017 : Thangnb tao_key_fe_footer
 * Ham lay danh sach seo footer theo chuyen muc 
 * params :
 	$p_cat_di : ID chuyen muc
	$p_loai_trang : Loai trang
 * return : array
*/
function be_footer($p_cat_id, $p_loai_trang) {
	$sql = "call fe_footer($p_cat_id, $p_loai_trang)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}

function be_get_all_category_by_footer_id($p_footer_id) {
	$sql = "call be_get_all_category_by_footer_id($p_footer_id)";	
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];	
}
/* //End 11-09-2017 : Thangnb tao_key_fe_footer */

// Begin TungVN 18-10-2017 - on_box_lich_xuat_ban_duoi_sapo
/**
 * Lấy thông tin xuất bản lịch xuất bản theo bài viết
 * @param integer $p_news_id
 * @return array
 */
function be_get_seo_lich_xuat_ban_news($p_news_id)
{
    $sql = "call be_get_seo_lich_xuat_ban_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Lấy thông tin xuất bản lịch xuất bản theo chủ đề
 * @param integer $p_event_id
 * @return array
 */
function be_get_seo_lich_xuat_ban_event($p_event_id)
{
    $sql = "call be_get_seo_lich_xuat_ban_event($p_event_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Lấy thông tin xuất bản lịch xuất bản theo chuyên mục
 * @param integer $p_category_id
 * @return array
 */
function be_get_seo_lich_xuat_ban_category($p_category_id)
{
    $sql = "call be_get_seo_lich_xuat_ban_category($p_category_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
// End TungVN 18-10-2017 - on_box_lich_xuat_ban_duoi_sapo

//  07-01-2021 DanNC begin begin tao sp goi max_order
function be_get_single_keyword_link_max_order($p_id_keywrod) 
{
	$sql = "call be_get_single_keyword_link_max_order($p_id_keywrod)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
//  07-01-2021 DanNC end begin tao sp goi max_order