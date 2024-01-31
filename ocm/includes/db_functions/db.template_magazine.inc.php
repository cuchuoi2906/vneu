<?php
// begin 03-07-2018 bangnd XLCYCMHENG_28354_xay_dung_chuc_nang_quan_tri_template_bai_magazine
/**
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string $p_template_name
 * @return int template_id
 */
function be_create_magazine_template($p_template_name, $p_user_id)
{
    // santinize inputs
    $p_template_name    = fw24h_replace_bad_char($p_template_name);
    $p_user_id    = intval($p_user_id);

    $sql = "call be_create_magazine_template('$p_template_name', $p_user_id)";
    $result = Gnud_Db_write_query($sql);

    return intval($result[0]['template_id']);
}

function be_update_magazine_template($p_template_id, $p_template_name, $p_original_html, $p_html, $p_html_template, $p_html_map, $p_edited_by, $p_template_description = '', $p_thumbnail = '', $p_position = 0, $p_status = 0) {

    $p_template_id      = intval($p_template_id);
    $p_template_name    = fw24h_replace_bad_char($p_template_name);
    $p_original_html    = fw24h_replace_bad_char($p_original_html);
    $p_html             = fw24h_replace_bad_char($p_html);
    $p_html_template    = fw24h_replace_bad_char($p_html_template);
    $p_html_map         = fw24h_add_slashes(json_encode($p_html_map));
    $p_thumbnail        = fw24h_replace_bad_char($p_thumbnail);
    $p_template_description = fw24h_replace_bad_char($p_template_description);
    $p_position         = intval($p_position);
    $p_position = $p_position > 999 ? 999: $p_position;

    $p_status           = intval($p_status);
    $p_edited_by        = intval($p_edited_by);

    $sql = "call be_update_magazine_template($p_template_id, '$p_template_name', '$p_original_html', '$p_html', '$p_html_template', '$p_html_map', $p_edited_by, '$p_template_description', '$p_thumbnail', $p_position, $p_status)";
    $result = Gnud_Db_write_query($sql);

    // return intval($result[0]['template_id']);
    return $result[0];
}

/**
 * lấy thông tin 1 magazine template
 * @param  integer $p_template_id [id của template]
 * @return array
 */
function be_get_magazine_template($p_template_id)
{
    $p_template_id = intval($p_template_id);
    $sql = "call be_get_magazine_template($p_template_id)";
    $result = Gnud_Db_read_query($sql);
    return $result[0];
}

function be_get_all_magazine_template($p_template_id, $p_template_name = '', $p_status, $p_use_status, $p_edited_by, $p_page, $p_perpage)
{
    $p_template_id      = intval($p_template_id);
    $p_template_name    = fw24h_replace_bad_char($p_template_name);
    $p_edited_by        = intval($p_edited_by);
    $p_page             = intval($p_page);
    $p_perpage          = intval($p_perpage);
    $p_status           = intval($p_status);
    $p_use_status       = intval($p_use_status);

    $sql = "call be_get_all_magazine_template($p_template_id, '$p_template_name', $p_status, $p_use_status, $p_edited_by, $p_page, $p_perpage)";
    $result = Gnud_Db_read_query($sql);
    return $result;
}

/**
 * cập nhật thông tin 1 file thuộc magazine template
 * @param  string  $p_file_name             [tên file sau khi upload]
 * @param  string  $p_file_original_name    [tên file trước khi upload]
 * @param  string  $p_file_path             [đường dân folder chứa file]
 * @param  string  $p_file_extension        [phần mở rộng của file]
 * @param  string  $p_mime_type
 * @param  string  $p_type                  [kiểu file: image, video, font, css, js, html]
 * @param  integer $p_template_id           [id template mà file được tải lên]
 * @param  integer $p_user_id               [người tạo/sửa]
 * @param  string  $p_file_url              [đường dẫn của file, file_path]
 * @param  string  $p_file_metadata         [các thông tin thêm của file: width, height,..]
 * @param  string  $p_file_content          [description]
 * @param  string  $p_file_original_content [description]
 * @param  string  $p_file_content_template [description]
 * @param  string  $p_file_content_map      [description]
 * @param  string  $p_hash                  [mã hash của file]
 * @param  integer $fk_magazine_template    [id template mà file chính thức thuộc về]
 * @param  integer $p_file_id               [id của file]
 * @return array
 */
function be_update_magazine_template_fileupload($p_file_name, $p_file_original_name, $p_file_path, $p_file_extension, $p_mime_type, $p_type, $p_template_id, $p_user_id = 0, $p_file_url = '', $p_file_metadata = null, $p_file_content = '', $p_file_original_content = '', $p_file_content_template = '', $p_file_content_map = '', $p_hash = '', $fk_magazine_template = 0, $p_file_id = -100)
{
    // santinize inputs
    $p_file_name        = fw24h_replace_bad_char($p_file_name);
    $p_file_original_name = fw24h_replace_bad_char($p_file_original_name);
    $p_file_path        = fw24h_replace_bad_char($p_file_path);
    $p_file_extension   = fw24h_replace_bad_char($p_file_extension);
    $p_template_id      = intval($p_template_id);
    $p_user_id          = intval($p_user_id);
    $p_file_status      = 1;
    $p_file_content     = fw24h_replace_bad_char($p_file_content);
    $p_file_metadata    = json_encode($p_file_metadata);
    $p_file_original_content = fw24h_replace_bad_char($p_file_original_content);
    $p_file_content_template = fw24h_replace_bad_char($p_file_content_template);
    $p_file_content_map = addslashes(json_encode($p_file_content_map));
    $p_hash = fw24h_replace_bad_char($p_hash);
    $fk_magazine_template = intval($fk_magazine_template);
    $p_file_id = intval($p_file_id);

    $sql = "call be_update_magazine_template_fileupload('$p_file_name', '$p_file_original_name', '$p_file_path', '$p_file_extension', '$p_mime_type', '$p_type', $p_template_id, $p_user_id, $p_file_status, '$p_file_url', '$p_file_metadata', '$p_file_content', '$p_file_original_content', '$p_file_content_template', '$p_file_content_map', '$p_hash', $fk_magazine_template, $p_file_id)";
    $result = Gnud_Db_write_query($sql);

    return intval($result[0]['fileupload_id']);
}

function be_assign_fileupload_to_template($p_template_id, array $p_fileupload_ids = array())
{
    $p_template_id      = intval($p_template_id);
    $p_str_fileupload_ids = empty($p_fileupload_ids) ? '' : implode(',', $p_fileupload_ids);

    $sql = "call be_assign_fileupload_to_template('$p_str_fileupload_ids', $p_template_id)";
    $result = Gnud_Db_write_query($sql);

    return $result;
}

function be_update_fileupload_content($p_file_id, $p_template_id, $p_file_hash, $p_file_content, $p_file_content_map) {

    $p_file_id          = intval($p_file_id);
    $p_template_id      = intval($p_template_id);
    $p_file_content     = fw24h_replace_bad_char($p_file_content);
    $p_file_content_map = addslashes(json_encode($p_file_content_map));
    $p_file_hash        = fw24h_replace_bad_char($p_file_hash);

    $sql = "call be_update_fileupload_content($p_file_id,  $p_template_id, '$p_file_hash', '$p_file_content', '$p_file_content_map')";

    $result = Gnud_Db_write_query($sql);

    return $result;
}

function be_get_all_magazine_template_fileupload($p_template_id, $p_file_type = '', $p_order = 'desc', $p_file_status = 1, $p_limit = 0)
{
    $p_template_id = intval($p_template_id);
    $p_file_status = intval($p_file_status);
    $p_file_type = fw24h_replace_bad_char($p_file_type);
    $p_order = fw24h_replace_bad_char($p_order);
    $p_limit = intval($p_limit);

    $sql = "call be_get_all_magazine_template_fileupload($p_template_id, $p_file_status, '$p_file_type', '$p_order', $p_limit)";
    $result = Gnud_Db_read_query($sql);
    return $result;
}

/**
 * Xóa 1 file tải lên theo khóa chính của file (magazine_template_fileupload)
 * @author bangnd <bangnd@24h.com.vn>
 * @param  integer  $p_fileupload_id    [khóa chính của file]
 * @return array
 */
function be_remove_magazine_template_fileupload($p_fileupload_id)
{
    $p_fileupload_id = intval($p_fileupload_id);
    $sql = "call be_remove_magazine_template_fileupload($p_fileupload_id)";
    $result = Gnud_Db_write_query($sql);
    return $result;
}

function be_remove_all_magazine_template_fileupload($p_template_id)
{
    $p_template_id = intval($p_template_id);
    $sql = "call be_remove_all_magazine_template_fileupload($p_template_id)";
    $result = Gnud_Db_write_query($sql);
    return $result;
}

function be_magazine_template_cap_nhat_lich_su($p_template_id, $p_loai_thay_doi = 0) {

    $p_template_id      = intval($p_template_id);
    $p_loai_thay_doi    = intval($p_loai_thay_doi);
    $p_files = '';
    // $p_files    = fw24h_replace_bad_char($p_files);

    $sql = "call be_magazine_template_cap_nhat_lich_su($p_template_id, $p_loai_thay_doi, '$p_files')";
    $result = Gnud_Db_write_query($sql);

    return $result;
}

function be_get_all_magazine_template_history($p_template_id, $page, $number_per_page) {
    $p_template_id = intval($p_template_id);
    $page = intval($page);
    $number_per_page = intval($number_per_page);

    $sql = "call be_get_all_magazine_template_history($p_template_id, $page, $number_per_page)";
    $rs = Gnud_Db_read_query($sql);
    $return['data'] = $rs['record0'];
    $return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
    return $return;
}

function be_get_single_magazine_template_history($p_template_history_id) {
    $p_template_history_id = intval($p_template_history_id);

    $sql = "call be_get_single_magazine_template_history($p_template_history_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}

function be_update_position_magazine_template($p_template_id, $p_position, $p_user_id, $p_status = -1)
{
    $p_template_id = intval($p_template_id);
    $p_position = intval($p_position);
    $p_position = $p_position > 999 ? 999 : $p_position;
    $p_user_id = intval($p_user_id);
    $p_status = intval($p_status);

    $sql = "call be_update_position_magazine_template($p_template_id, $p_position, $p_user_id, $p_status)";
    $result = Gnud_Db_write_query($sql);
    return $result;
}

function be_count_magazine_has_template_id($p_template_id)
{
    $p_template_id = intval($p_template_id);

    $sql = "call be_count_magazine_has_template_id($p_template_id)";
    $result = Gnud_Db_read_query($sql);
    return $result[0]['tong_so'];
}

/**
 * xóa 1 magazine template
 * @author bangnd <bangnd@24h.com.vn>
 * @param  int $p_template_id [id của magazine template]
 * @return array
 */
function be_delete_magazine_template($p_template_id)
{
    $p_template_id = intval($p_template_id);

    $sql = "call be_delete_magazine_template($p_template_id)";
    $result = Gnud_Db_write_query($sql);
    return $result;
}

/**
 * [be_get_magazine_template_by_name description]
 * @param  [type]  $p_template_name      [description]
 * @param  integer $p_except_template_id [description]
 * @return [type]                        [description]
 */
function be_get_magazine_template_by_name($p_template_name, $p_except_template_id = 0)
{
    $p_template_name = fw24h_replace_bad_char($p_template_name);
    $p_except_template_id = intval($p_except_template_id);

    $sql = "call be_get_magazine_template_by_name('$p_template_name', $p_except_template_id)";
    $result = Gnud_Db_read_query($sql);
    return $result;
}
// end 03-07-2018 bangnd XLCYCMHENG_28354_xay_dung_chuc_nang_quan_tri_template_bai_magazine
