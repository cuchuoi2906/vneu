<?php 
/**
 * 1. SP lấy danh sách bài magazine
 * @param  string  $p_magazine_name [Tên bài magazine]
 * @param  integer $p_category_id   [Id chuyên mục của bài viết chứa bài magazine]
 * @param  integer $p_user_id       [Id Người sửa cuối]
 * @param  integer $p_news_id       [Id bài viết chứa bài magazine]
 * @param  integer $p_page          [Số thứ tự của trang]
 * @param  integer $p_perpage       [Số bản ghi / 1 trang]
 * @return array
 */
function be_get_all_magazine($p_magazine_name = '', $p_category_id = 0, $p_user_id = 0, $p_status = -1, $p_news_id = 0, $p_page = 1, $p_perpage = 30, $p_bai_tham_khao = 0)
{
	$p_magazine_name 	= fw24h_replace_bad_char(_utf8_to_ascii($p_magazine_name));
	$p_category_id 		= intval($p_category_id);
	$p_user_id 			= intval($p_user_id);
	$p_news_id 			= intval($p_news_id);
	$p_page 			= intval($p_page);
	$p_perpage 			= intval($p_perpage);
	$p_status 			= intval($p_status);

	$sql = "call be_get_all_magazine('$p_magazine_name', $p_category_id, $p_user_id, $p_status, $p_news_id, $p_page, $p_perpage, $p_bai_tham_khao)";
	$result = Gnud_Db_read_query($sql);
	$return['data'] = $result['record0'];
	$return['tong_so_dong'] = $result['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * xóa 1 bài magazine
 * @param  integer 	$p_magazine_id 	[Khóa chính của magazine]
 * @return array
 */
function be_delete_magazine($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);

    $sql = "call be_delete_magazine($p_magazine_id)";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

/**
 * cập nhật trạng thái xuất bản 1 bài magazine 
 * @param  integer 	$p_magazine_id 			[Khóa chính của magazine]
 * @param  integer 	$p_magazine_status      [Trạng thái xuất bản của magazine]
 * @return array
 */
function be_update_status_magazine($p_magazine_id, $p_magazine_status, $p_user_id, $p_html = '', $p_head_files = array(), $p_bai_tham_khao)
{
	$p_magazine_id = intval($p_magazine_id);
	$p_template_status = intval($p_magazine_status);
	$p_user_id = intval($p_user_id);
	$p_bai_tham_khao = intval($p_bai_tham_khao);
	$p_html = fw24h_replace_bad_char($p_html);
	$p_head_files = addslashes(json_encode($p_head_files));
	
    $sql = "call be_update_magazine($p_magazine_id, '', $p_magazine_status, $p_user_id, '$p_html', '$p_head_files', $p_bai_tham_khao)";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

/**
 * lấy thông tin chi tiết 1 bài magazine
 * @param  integer 	$p_magazine_id 	[Khóa chính của magazine]
 * @return array
 */
function be_get_single_magazine($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);

	$sql = "call be_get_single_magazine($p_magazine_id)";
	$result = Gnud_Db_read_query($sql);
	return $result[0];
}

/**
 * lấy danh sách các magazine template sử dụng cho selectbox
 * @param  integer 	$p_template_status 	[Trạng thái xuất bản của template]
 * @return array
 */
function be_get_all_magazine_template_for_select($p_template_status = 1)
{
	$p_template_status = intval($p_template_status);
	
	$sql = "call be_get_all_magazine_template_for_select($p_template_status)";
	$result = Gnud_Db_read_query($sql);
	return $result;
}

/**
 * [be_update_magazine_history description]
 * @param  integer $p_magazine_id [description]
 * @param  integer $p_action_type [description]
 * @return array
 */
function be_update_magazine_history($p_magazine_id, $p_action_type = 0)
{
	$p_magazine_id = intval($p_magazine_id);
	$p_action_type = intval($p_action_type);

	$v_arr_magazine_content = be_get_all_magazine_content($p_magazine_id);
	$p_magazine_content = fw24h_add_slashes(json_encode($v_arr_magazine_content));
	
    $sql = "call be_update_magazine_history($p_magazine_id, $p_action_type, '$p_magazine_content')";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

/**
 * lấy danh sách nội dung chi tiết của 1 magazine
 * @param  integer 	$p_magazine_id     	[Khóa chính của magazine]
 * @return array
 */
function be_get_all_magazine_content($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);
	
	$sql = "call be_get_all_magazine_content($p_magazine_id)";
	$result = Gnud_Db_read_query($sql);
	return $result;
}

/**
 * thêm mới 1 magazine
 * @param  integer 	$p_user_id     		[Người tạo]
 * @param  string 	$p_magazine_name   	[Tên bài magazine]
 * @param  integer 	$p_magazine_status 	[Trạng thái xuất bản của magazine]
 * @return array
 */
function be_create_magazine($p_magazine_name, $p_magazine_status, $p_user_id, $p_chk_bai_tham_khao, $p_magazine_font = '')
{
	$p_magazine_name = fw24h_replace_bad_char($p_magazine_name);
	$p_magazine_status = intval($p_magazine_status);
	$p_user_id = intval($p_user_id);
	$p_chk_bai_tham_khao = intval($p_chk_bai_tham_khao);

    $sql = "call be_create_magazine('$p_magazine_name', $p_magazine_status, $p_user_id, $p_chk_bai_tham_khao, '$p_magazine_font')";
	$result = Gnud_Db_write_query($sql);
	return $result[0];
}

/**
 * cập nhật thông tin 1 magazine
 * @param  integer 	$p_magazine_id     	[Khóa chính của magazine]
 * @param  string 	$p_magazine_name   	[Tên bài magazine]
 * @param  integer 	$p_magazine_status 	[Trạng thái xuất bản của magazine]
 * @return array
 */
function be_update_magazine($p_magazine_id, $p_magazine_name, $p_magazine_status, $p_user_id, $p_html = '', $p_head_files = array(), $p_chk_bai_tham_khao = 0, $p_magazine_font = '')
{
	$p_magazine_id = intval($p_magazine_id);
	$p_magazine_name = fw24h_replace_bad_char($p_magazine_name);
	$p_magazine_status = intval($p_magazine_status);
	$p_user_id = intval($p_user_id);
	$p_chk_bai_tham_khao = intval($p_chk_bai_tham_khao);
	$p_html = fw24h_replace_bad_char($p_html);
	$p_head_files = addslashes(json_encode($p_head_files));

    $sql = "call be_update_magazine($p_magazine_id, '$p_magazine_name', $p_magazine_status, $p_user_id, '$p_html', '$p_head_files', $p_chk_bai_tham_khao, '$p_magazine_font')";
	$result = Gnud_Db_write_query($sql);
	return $result[0];
}

/**
 * lấy danh sách lịch sử sửa đổi của 1 magazine
 * @param  integer  $p_magazine_id 		[Khóa chính của magazine]
 * @param  integer 	$p_page          	[Số thứ tự của trang]
 * @param  integer 	$p_perpage       	[Số bản ghi / 1 trang]
 * @return array
 */
function be_get_all_magazine_history($p_magazine_id, $p_page = 1, $p_perpage = 30)
{
	$p_magazine_id = intval($p_magazine_id);
	$p_page = intval($p_page);
	$p_perpage = intval($p_perpage);
	
	$sql = "call be_get_all_magazine_history($p_magazine_id, $p_page, $p_perpage)";
	$result = Gnud_Db_read_query($sql);
	$return['data'] = $result['record0'];
	$return['tong_so_dong'] = $result['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * lấy thông tin chi tiết 1 lịch sử sửa đổi
 * @param  integer  $p_magazine_history_id  [Khóa chính của magazine history]
 * @return array
 */
function be_get_single_magazine_history($p_magazine_history_id)
{
	$p_magazine_history_id = intval($p_magazine_history_id);
	
	$sql = "call be_get_single_magazine_history($p_magazine_history_id)";
	$result = Gnud_Db_read_query($sql);
	return empty($result) ? $result : $result[0];
}

/**
 * lấy thông tin chi tiết 1 slot nội dung magazine
 * @param  integer  $p_magazine_content_id  [Khóa chính của magazine_content]
 * @return array
 */
function be_get_single_magazine_content($p_magazine_content_id)
{
	$p_magazine_content_id = intval($p_magazine_content_id);
	
	$sql = "call be_get_single_magazine_content($p_magazine_content_id)";
	$result = Gnud_Db_read_query($sql);
	return empty($result) ? $result : $result[0];
}

/**
 * xóa các nội dung cũ của 1 magazine
 * @param  integer 	$p_magazine_id     	[Khóa chính của magazine]
 * @return array
 */
function be_delete_magazine_content($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);

    $sql = "call be_delete_magazine_content($p_magazine_id)";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

/**
 * cập nhật nội dung mới cho 1 magazine
 * @param  integer 	$p_magazine_id          	[Khóa của magazine]
 * @param  integer 	$p_magazine_template_id 	[Khóa của magazine template]
 * @param  string 	$p_html_template        	[Html template của magazine template]
 * @param  string 	$p_html_map             	[Json mảng các phần tử định nghĩa và các phần tử được trích xuất tự động]
 * @param  integer 	$p_position             	[Trọng số]
 * @param  integer 	$p_user_id              	[Id người sửa]
 * @return array
 */
function be_update_magazine_content($p_magazine_id, $p_magazine_template_id, $p_html_template, $p_html_map, $p_position, $p_user_id)
{
	$p_magazine_id = intval($p_magazine_id);
	$p_magazine_template_id = intval($p_magazine_template_id);
	$p_html_template = fw24h_replace_bad_char($p_html_template);
	$p_html_map = addslashes(json_encode($p_html_map));
	$p_position = intval($p_position);
	$p_position = ($p_position > 999 || $p_position < 0)  ? 999: $p_position;
	$p_user_id = intval($p_user_id);

    $sql = "call be_update_magazine_content($p_magazine_id, $p_magazine_template_id, '$p_html_template', '$p_html_map', $p_position, $p_user_id)";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

/**
 * Tổng số bài viết đã sử dụng magazine
 * @author bangnd <bangnd@24h.com.vn>
 * @param  integer 	$v_magazine_id 	[khóa chính của magazine]
 * @return integer
 */
function be_count_news_has_magazine($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);

	$sql = "call be_count_news_has_magazine($p_magazine_id)";
	$result = Gnud_Db_read_query($sql);
	return $result[0]['tong_so'];
}

function be_update_magazine_full_html($p_magazine_id, $p_html, $p_head_files)
{
	$p_magazine_id = intval($p_magazine_id);
	$p_html = fw24h_replace_bad_char($p_html);
	$p_head_files = addslashes(json_encode($p_head_files));
    $sql = "call be_update_magazine_full_html($p_magazine_id, '$p_html', '$p_head_files')";
	$result = Gnud_Db_write_query($sql);
	return $result;
}

function be_gen_magazine_data($p_magazine_id)
{
    return;
	$p_magazine_id = intval($p_magazine_id);
	// Lay du lieu - KHONG doc tu key-value
	$v_array_items = be_get_single_magazine($p_magazine_id);
    $v_key = 'data_magazine_theo_id'.$p_magazine_id;
	if(check_array($v_array_items)){
		$v_array_items = gzcompress(serialize($v_array_items)); 
		$data['C_KEY'] 	 = $v_key;
		$data['C_VALUE'] = $v_array_items;
		$data['C_DESCRIPTION']  = 'gzip ,serialize';
		Gnud_Db_write_update_key($data, _CACHE_TABLE);
		_write_key_to_redis($data);		
	}else{
		fe_delete_key($v_key);
	}
}
function be_get_all_news_by_magazine_id($p_magazine_id)
{
	$p_magazine_id = intval($p_magazine_id);
	$sql = "call be_get_all_news_by_magazine_id($p_magazine_id)";
	$result = Gnud_Db_read_query($sql);
	return $result;
}