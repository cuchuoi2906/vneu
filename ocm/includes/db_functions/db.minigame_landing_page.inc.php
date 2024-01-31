<?php

/* Hàm thực hiện lấy danh sách landing page
	p_chien_dich_id				BIGINT(20), ID chiến dịch
	p_game_id					BIGINT(20), ID game
	p_status					TINYINT(4), Trạng thái xuất bản
	p_userid					INT, user id
	p_ten_landing_page			VARCHAR(300), tên landing page
	p_landing_page_id			BIGINT(20), Landing page id
	p_url						VARCHAR(500), Đường dẫn landing page
	p_last_update_from_date		VARCHAR(50), Thời gian cập từ
	p_last_update_to_date		VARCHAR(50), Thời gian cập nhật đến
	p_page						INT(11), số trang
	p_num_per_page 				INT(11) Số dòng trên mỗi trang
 */

function be_minigame_danh_sach_landing_page(
    $p_chien_dich_id,
    $p_game_id,
    $p_status,
    $p_userid,
    $p_ten_landing_page,
    $p_landing_page_id,
    $p_url,
    $p_last_update_from_date,
    $p_last_update_to_date,
    $p_page,
    $p_num_per_page
)
{
    $sql = "call be_minigame_danh_sach_landing_page(
        $p_chien_dich_id,
        $p_game_id,
        $p_status,
        $p_userid,
        '$p_ten_landing_page',
        $p_landing_page_id,
        '$p_url',
        '$p_last_update_from_date',
        '$p_last_update_to_date',
        $p_page,
        $p_num_per_page
    )";
    //echo $sql;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* Hàm thực hiện cập nhật một landing page
	$p_landing_page_id  ID landing page
	,$p_chien_dich_id ID chiến dịch
	,$p_ten Tên landing page
	,$p_ten_khong_dau Tên không dấu
	,$p_slug slug
	,$p_ma_banner_layout mã banner layout
	,$p_click_tracking_link tracking click
	,$p_impression_link impression link
	,$p_html_origin HTML gốc
	,$p_html HTML
	,$p_show_box_tham_du_game
	,$p_noi_dung_box_tham_du_game
	,$p_show_box_giai_thuong_game
	,$p_noi_dung_box_giai_thuong_game
	,$p_trang_thai
	,$p_userid
 */
function be_update_single_mini_game_landing_page(
    $p_landing_page_id
    , $p_chien_dich_id
    , $p_ten
    , $p_ten_khong_dau
    , $p_slug
    , $p_url
    , $p_ma_banner_layout
    , $p_click_tracking_link
    , $p_impression_link
    , $p_html_origin
    , $p_html
    , $p_show_box_tham_du_game
    , $p_noi_dung_box_tham_du_game
    , $p_show_box_giai_thuong_game
    , $p_noi_dung_box_giai_thuong_game
    , $p_show_box_trung_thuong
    , $p_trang_thai
    , $p_userid
    , $p_anh_mxh
)
{
    $sql = "call be_update_single_mini_game_landing_page(
        $p_landing_page_id
        ,$p_chien_dich_id
        ,'$p_ten'
        ,'$p_ten_khong_dau'
        ,'$p_slug'
        ,'$p_url'
        ,'$p_ma_banner_layout'
        ,'$p_click_tracking_link'
        ,'$p_impression_link'
        ,'$p_html_origin'
        ,'$p_html'
        ,$p_show_box_tham_du_game
        ,'$p_noi_dung_box_tham_du_game'
        ,$p_show_box_giai_thuong_game
        ,'$p_noi_dung_box_giai_thuong_game'
        ,$p_show_box_trung_thuong
        ,$p_trang_thai
        ,$p_userid
        ,'$p_anh_mxh'
    )";
    //echo $sql;die;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện cập nhật một landing page file upload
	$p_landing_page_id					BIGINT(20),
	$p_origin_content					LONGTEXT,
	$p_name								VARCHAR(3000),
	$p_name_goc							VARCHAR(300),
	$p_url								VARCHAR(500),
	$p_type								VARCHAR(50),
	$p_path								VARCHAR(300)
 */
function be_minigame_cap_nhat_template_landing_page_fileupload(
    $p_landing_page_id,
    $p_origin_content,
    $p_name,
    $p_name_goc,
    $p_url,
    $p_type,
    $p_path
)
{
    $sql = "call be_minigame_cap_nhat_template_landing_page_fileupload(
        $p_landing_page_id,
        '$p_origin_content',
        '$p_name',
        '$p_name_goc',
        '$p_url',
        '$p_type',
        '$p_path'
    )";
    //echo $sql;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện xóa file upload theo id landing page
	$p_landing_page_id BIGINT(20) landing page id
 */
function be_minigame_xoa_fileupload_theo_id_landing_page(
    $p_landing_page_id
)
{
    $sql = "call be_minigame_xoa_fileupload_theo_id_landing_page(
        $p_landing_page_id
    )";
    //echo $sql;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện cập nhật box danh sách trúng thưởng
	$p_landing_page_id					BIGINT(20),
	$p_stt								INT,
	$p_ten								VARCHAR(300),
	$p_ten_khong_dau						VARCHAR(300),
	$p_so_cmt							VARCHAR(500),
	$p_ma_giai_thuong						VARCHAR(300)
 */
function be_minigame_cap_nhat_box_danh_sach_trung_thuong(
    $p_landing_page_id,
    $p_stt,
    $p_ten,
    $p_ten_khong_dau,
    $p_so_cmt,
    $p_ma_giai_thuong,
    $p_giai_doan_trung_thuong
)
{
    $sql = "call be_minigame_cap_nhat_box_danh_sach_trung_thuong(
        $p_landing_page_id,
        $p_stt,
        '$p_ten',
        '$p_ten_khong_dau',
        '$p_so_cmt',
        '$p_ma_giai_thuong',
        $p_giai_doan_trung_thuong
    )";
    //echo $sql;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện xóa danh sách box trúng thưởng theo id
	$p_landing_page_id					BIGINT(20)
 */
function be_minigame_xoa_box_trung_thuong_theo_id_landing_page(
    $p_landing_page_id
)
{
    $sql = "call be_minigame_xoa_box_trung_thuong_theo_id_landing_page(
        $p_landing_page_id
    )";
    //echo $sql;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện lấy tất cả box trúng thưởng
	$p_page, Số trang
	$p_num_per_page Số dòng mỗi trang
 */
function be_get_all_game_box_trung_thuong(
    $p_landing_page_id,
    $p_page,
    $p_num_per_page
)
{
    $sql = "call be_get_all_game_box_trung_thuong(
        $p_landing_page_id,
        $p_page,
        $p_num_per_page
    );";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* Hàm thực hiện lấy tất cả file upload landing page
	$p_landing_page_id, landing page id
	$p_page, Số trang
	$p_num_per_page Số dòng mỗi trang
 */
function be_get_all_game_file_upload_landing_page(
    $p_landing_page_id,
    $p_page,
    $p_num_per_page
)
{
    $sql = "call be_get_all_game_file_upload_landing_page(
        $p_landing_page_id,
        $p_page,
        $p_num_per_page
    );";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* Hàm thực hiện lấy tất cả file upload landing page
    $p_landing_page_id BIGINT(20)  ID langding page
	,$p_trang_thai_xuat_ban	TinyINT(4) trạng thái xuất bản
	,$p_nguoi_sua	INT Người sửa
 */
function be_update_trang_thai_game_landing_page(
    $p_landing_page_id
    , $p_trang_thai_xuat_ban
    , $p_nguoi_sua
)
{
    $sql = "call be_update_trang_thai_game_landing_page(
        $p_landing_page_id
        ,$p_trang_thai_xuat_ban
        ,$p_nguoi_sua
    )";
    //echo $sql;die;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện lấy chi tiết 1 landing page
    $p_landing_page_id BIGINT(20)  ID langding page
 */
function be_get_single_game_landing_page($p_landing_page_id)
{
    $sql = "call be_get_single_game_landing_page(
        $p_landing_page_id
    );";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}

/* Hàm thực hiện xóa chi tiết 1 game landing page
    $p_landing_page_id BIGINT(20)  ID langding page
 */
function be_delete_single_game_landing_page(
    $p_landing_page_id
)
{
    $sql = "call be_delete_single_game_landing_page(
        $p_landing_page_id
    )";
    //echo $sql;die;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện xóa chi tiết 1 game landing page
    p_landing_page_id 			BIGINT(20), ID landing page
	p_page						INT(11), so trang
	p_num_per_page 				INT(11) so dong
 */
function be_get_all_game_by_landing_page_id(
    $p_landing_page_id,
    $p_page = 1,
    $p_num_per_page = 2000
)
{
    $sql = "call be_get_all_game_by_landing_page_id(
        	$p_landing_page_id,
            $p_page,
            $p_num_per_page
    );";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* Hàm thực hiện update thong tin landing trong box game
    $p_game_game_id 			BIGINT(20), ID game
	$p_order						INT(11), trong so
	$p_hien_thi 				INT(11) hien thi box game
 */
function be_update_game_landing_page_info(
    $p_game_game_id,
    $p_order,
    $p_hien_thi
)
{
    $sql = "call be_update_game_landing_page_info(
        $p_game_game_id,
        $p_order,
        $p_hien_thi
    )";
    //echo $sql;die;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* hàm thực hiện lấy tất cả game fillter
    $p_page 			INT), Số trang
	$p_num_per_page						INT(11), số dòng
 */
function be_get_all_game_game_filter(
    $p_page,
    $p_num_per_page
)
{
    $sql = "call be_get_all_game_game_filter(
            $p_page,
            $p_num_per_page
    );";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* hàm thực hiện lấy d
    $p_chien_dich_id BIGINT ID chiến dịch
 */
function be_get_game_landing_page_by_chien_dich_id(
    $p_chien_dich_id
)
{
    $sql = "call be_get_game_landing_page_by_chien_dich_id(
        $p_chien_dich_id
    );";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}


/* Hàm thực hiện lấy tất cả box trúng thưởng
	$p_page, Số trang
	$p_num_per_page Số dòng mỗi trang
 */
function be_get_all_game_box_giai_doan_trung_thuong(
    $p_landing_page_id,
    $p_page,
    $p_num_per_page
)
{
    $sql = "call be_get_all_game_box_giai_doan_trung_thuong(
        $p_landing_page_id,
        $p_page,
        $p_num_per_page
    );";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/* Hàm thực hiện cập nhật box giai đoạn trúng thưởng
	$p_landing_page_id					BIGINT(20),
	$p_stt								INT,
	$p_ten								VARCHAR(300),
	$p_ten_khong_dau						VARCHAR(300),
	$p_so_cmt							VARCHAR(500),
	$p_ma_giai_thuong						VARCHAR(300)
 */
function be_minigame_cap_nhat_game_box_giai_doan_trung_thuong(
    $p_landing_page_id,
    $p_ten,
    $p_stt,
    $p_status,
    $p_nguoi_sua
)
{
    $sql = "call be_minigame_cap_nhat_game_box_giai_doan_trung_thuong(
        $p_landing_page_id,
        '$p_ten',
        $p_stt,
        $p_status,
        $p_nguoi_sua
    )";
    //echo $sql;
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}