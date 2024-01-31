<?php
/*
* lấy chi tiết 1 sitemap
* @author: hailt - 10/10/20134
*
* @param: VARCHAR(255)	$p_ten					Tên file sitemap
* @param: BIGINT(20)	$p_sitemap				ID sitemap
* @param: BOLLEAN		$p_read_from_mstdb		khi update check mới cần đọc từ mstdb
* @return: array
*/
function fe_chi_tiet_1_sitemap($p_ten, $p_sitemap = -1, $p_read_from_mstdb = false) 
{ 
	$sql = "CALL fe_chi_tiet_1_sitemap('$p_ten', $p_sitemap)";
	if ($p_read_from_mstdb){
		$rs = Gnud_Db_write_query($sql);
		$rs = $rs[0];
	} else {
		$rs = Gnud_Db_read_query_one($sql);
	}
	if (!is_null($rs['c_html']) && $rs['c_html'] != '') {
		$rs['c_html'] = unserialize(base64_decode($rs['c_html']));
	}
	if (!is_null($rs['c_data']) && $rs['c_data'] != '') {
		$rs['c_data'] = unserialize(base64_decode($rs['c_data']));
	}
    return $rs;
}

/*
* Cập nhật chi tiết 1 sitemap
* @author: hailt - 10/10/20134
*
* @param: BIGINT(20)	$p_sitemap				ID sitemap; < 0 để tạo mới
* @param: TINYINT(4)	$p_type					-- định nghĩa sau; mặc định để 1
* @param: VARCHAR(255)	$p_ten					Tên file sitemap
* @param: TEXT			$p_html					HTML file sitemap
* @param: TEXT			$p_data					data dùng tạo sitemap trong ngày đã thực hiện base64_encode(serialize())
* @return: array
*/
function fe_update_1_sitemap($p_sitemap, $p_type, $p_ten, $p_html, $p_data)
{
    // anhnt1 16/10/2015: dong connection
    Gnud_Db_write_close();
	if (!is_null($p_html) && $p_html != '') {
		$p_html = base64_encode(serialize($p_html));
	}
	if (!is_null($p_data) && $p_data != '') {
		$p_data = base64_encode(serialize($p_data));
	}
	$sql = "CALL fe_update_1_sitemap($p_sitemap, $p_type, '$p_ten', '$p_html', '$p_data')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/*
* Lay khoang thoi gian lay so lieu tin bai de tao sitemap
* @author: phuonghv - 17/10/2015
* @param: INT	$p_so_thang 
* @return: array
*/
function fe_lay_khoang_thoi_gian_tao_sitemap_bai_viet($p_so_thang=1){
    $sql = "CALL fe_lay_khoang_thoi_gian_tao_sitemap_bai_viet($p_so_thang)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
//begin: tao_sitemap_tag_video_image_theo_thang
/*
* Xoa 1 sitemap
* @author: phuonghv - 10/11/2015
* @param: VARCHAR(255)	$p_sitemap_name Tên file sitemap
* @return: array
*/
function fe_delete_1_sitemap($p_sitemap_name)
{    	
    $sql = "CALL fe_delete_1_sitemap('$p_sitemap_name')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];	
}
//end: tao_sitemap_tag_video_image_theo_thang