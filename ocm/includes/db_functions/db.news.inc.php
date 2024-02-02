<?php

/**
 * Lay danh sach bai viet dang soan 
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		int 			$p_category_khampha_id Tìm theo ID chuyên mục xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		int				$p_user_id		Tìm theo ID biên tập viên tạo bài
										-1: nếu không chọn BTV
		varchar(50) 	$p_type		Tìm loại bài
							- Bài thường
							- Bài video (VideoCode!=’’ hoặc Video_code=1)
							- Bài ảnh (Album_trang_anh>0)
							- Bài PR đầu trang (pr_dau_trang=1)
							- Bài PR ưu tiên (pr=1)
		tinyint			$p_has_poll		Tìm bài đã chọn poll hay chưa
								0: chưa chọn poll
								1: đã chọn poll
		tinyint			$p_has_comment		Tìm bài đã có bình luận mồi hay chưa
							0: chưa có
							1: đã có
		tinyint			$p_pr_region		Tìm vùng miền hiển thị bài PR
								0: Toàn quốc
								1: Miền bắc
								2: Miền nam
		Varchar(50)		$p_edit_date_start		Khoảng thời gian sửa bài, định dạng YYYY-MM-DD
		Varchar(50)		$p_edit_date_end
		int				$p_event_id  ID sự kiện
		Varchar(255)	$p_event_name  Tên sự kiện
		Varchar(255)	$p_so_hop_dong  Số hợp đồng
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
/* Begin 20-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
function be_get_all_drafting_news($p_news_id, $p_category_id, $p_category_khampha_id, $p_title, $p_user_id, $p_type , $p_has_poll, $p_pr_region, $p_pr_device, $p_bien_tap_lai, $p_edit_date_start, $p_edit_date_end, $p_event_id, $p_event_name, $p_so_hop_dong, $p_page_number, $p_row_per_page, $p_giai_dau_ids = '', $p_cm_banner_ids = '', $p_thongke = 0, $p_sk_mo_rong = 0, $p_category_partners_id = 0, $p_code = '',$p_type_pr = '',$p_no_pr = -1,$p_source_id='',$p_day_bai_24hmoney = 0,$p_day_bai_layout_app_tinmoi = 0)
{
    //        AnhTT toi_uu_tim_kiem_nhieuCM
	$sql = "call be_get_all_drafting_news($p_news_id, '$p_category_id', $p_category_khampha_id, '$p_title', $p_user_id, '$p_type','',-1,$p_has_poll, $p_pr_region, $p_pr_device, $p_bien_tap_lai, '$p_edit_date_start', '$p_edit_date_end', $p_event_id, '$p_event_name', '$p_so_hop_dong', $p_page_number, $p_row_per_page, '$p_giai_dau_ids', '$p_cm_banner_ids', $p_thongke, $p_sk_mo_rong, $p_category_partners_id, '$p_code','$p_source_id',$p_day_bai_24hmoney,$p_day_bai_layout_app_tinmoi)";  
//        AnhTT toi_uu_tim_kiem_nhieuCM
    /* End 20-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
    $rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Lay danh sach bai viet xoa tam
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		int				$p_user_id		Tìm theo ID biên tập viên tạo bài
										-1: nếu không chọn BTV
		Varchar(50)		$p_edit_date_start		Khoảng thời gian sửa bài, định dạng YYYY-MM-DD
		Varchar(50)		$p_edit_date_end
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
function be_get_all_deleted_news($p_news_id, $p_category_id, $p_title, $p_editor_id, $p_user_deleted_id, $p_status_before_delete, $p_edit_date_start, $p_edit_date_end, $p_page_number, $p_row_per_page)
{
	$sql = "call be_get_all_deleted_news($p_news_id, $p_category_id, '$p_title', $p_editor_id, $p_user_deleted_id, $p_status_before_delete, '$p_edit_date_start', '$p_edit_date_end', $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Lay danh sach bai viet cho duyet
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		int 			$p_category_khampha_id Tìm theo ID chuyên mục khám phá xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		int				$p_editor_id		Tìm theo ID biên tập viên tạo bài
										-1: nếu không chọn BTV
		int				$p_approved_id		Tìm theo ID nguoi duyet bài
										-1: nếu không chọn BTV
		int				$p_published_id		Tìm theo ID nguoi XB bài
										-1: nếu không chọn BTV
		varchar(50) 	$p_type		Tìm loại bài
							- Bài thường
							- Bài video (VideoCode!=’’ hoặc Video_code=1)
							- Bài ảnh (Album_trang_anh>0)
							- Bài PR đầu trang (pr_dau_trang=1)
							- Bài PR ưu tiên (pr=1)
		tinyint			$p_has_poll		Tìm bài đã chọn poll hay chưa
								0: chưa chọn poll
								1: đã chọn poll
		tinyint			$p_has_comment		Tìm bài đã có bình luận mồi hay chưa
							0: chưa có
							1: đã có
		tinyint			$p_pr_region		Tìm vùng miền hiển thị bài PR
								0: Toàn quốc
								1: Miền bắc
								2: Miền nam
		Varchar(50)		$p_edit_date_start		Khoảng thời gian sửa bài, định dạng YYYY-MM-DD
		Varchar(50)		$p_edit_date_end
		int 			$p_event_id ID sự kiện
		Varchar(255)	$p_event_name Tên sự kiện
        Varchar(255)	$p_so_hop_dong Số hợp đồng
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
/* Begin 20-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
function be_get_all_pending_approval_news($p_news_id, $p_category_id, $p_category_khampha_id, $p_title, $p_editor_id, $p_approved_id, $p_published_id, $p_type, $p_has_poll, $p_pr_region, $p_pr_device, $p_gui_duyet_lai, $p_edit_date_start, $p_edit_date_end, $p_event_id, $p_event_name, $p_so_hop_dong, $p_page_number, $p_row_per_page, $p_giai_dau_ids = '', $p_cm_banner_ids = '', $p_thongke = 0, $p_sk_mo_rong = 0, $p_category_partners_id = 0, $p_code = '' ,$p_type_pr = '',$p_no_pr = -1,$source_id_list = '',$p_day_bai_24hmoney =0,$p_day_bai_layout_app_tinmoi = 0, $p_only_pr = -1)
{
	$sql = "call be_get_all_pending_approval_news($p_news_id, '$p_category_id', '$p_category_khampha_id', '$p_title', $p_editor_id, $p_approved_id, $p_published_id, '$p_type','$p_type_pr',$p_no_pr , $p_has_poll, $p_pr_region, $p_pr_device, $p_gui_duyet_lai, '$p_edit_date_start', '$p_edit_date_end', $p_event_id, '$p_event_name', '$p_so_hop_dong', $p_page_number, $p_row_per_page, '$p_giai_dau_ids', '$p_cm_banner_ids', $p_thongke, $p_sk_mo_rong, $p_category_partners_id, '$p_code','$source_id_list',$p_day_bai_24hmoney,$p_day_bai_layout_app_tinmoi, $p_only_pr)";
/* End 20-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
    $rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Lay danh sach bai viet cho xuat ban
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		int 			$p_category_khampha_id Tìm theo ID chuyên mục khám phá xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		int				$p_user_id		ID nguoi xem danh sach
		int				$p_editor_id		Tìm theo ID biên tập viên tạo bài
										-1: nếu không chọn BTV
		int				$p_approved_id		Tìm theo ID nguoi duyet bài
										-1: nếu không chọn BTV
		int				$p_published_id		Tìm theo ID nguoi XB bài
										-1: nếu không chọn BTV
		varchar(50) 	$p_type		Tìm loại bài
							- Bài thường
							- Bài video (VideoCode!=’’ hoặc Video_code=1)
							- Bài ảnh (Album_trang_anh>0)
							- Bài PR đầu trang (pr_dau_trang=1)
							- Bài PR ưu tiên (pr=1)
		tinyint			$p_has_poll		Tìm bài đã chọn poll hay chưa
								0: chưa chọn poll
								1: đã chọn poll
		tinyint			$p_has_comment		Tìm bài đã có bình luận mồi hay chưa
							0: chưa có
							1: đã có
		tinyint			$p_pr_region		Tìm vùng miền hiển thị bài PR
								0: Toàn quốc
								1: Miền bắc
								2: Miền nam
		Varchar(50)		$p_edit_date		Thời gian sửa bài, định dạng YYYY-MM-DD
		int				$p_event_id			ID sự kiện
		Varchar(255)	$p_event_name		Tên sự kiện
        Varchar(255)	$p_so_hop_dong		Số hợp đồng
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
/* Begin 21-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */    
function be_get_all_pending_publication_news($p_news_id, $p_category_id, $p_category_khampha_id, $p_title, $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, $p_type ,$p_has_poll, $p_pr_region, $p_pr_device, $p_bai_ha_xuat_ban, $p_edit_date_start, $p_edit_date_end, $p_pending_date_start, $p_pending_date_end, $p_event_id, $p_event_name, $p_so_hop_dong, $p_page_number, $p_row_per_page, $p_giai_dau_ids = '', $p_cm_banner_ids = '', $p_thongke = 0, $p_sk_mo_rong = 0, $p_category_partners_id = 0, $p_code = '', $p_chk_approve_status_partners = 0, $p_type_pr = '',$p_no_pr = -1,$source_id_list = '',$p_day_bai_24hmoney =0,$p_day_bai_layout_app_tinmoi = 0, $p_chuyen_muc_gan_link = 0, $p_only_pr = -1)
{
    //        AnhTT toi_uu_tim_kiem_nhieuCM
	$sql = "call be_get_all_pending_publication_news($p_news_id, '$p_category_id', $p_category_khampha_id, '$p_title', $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, '$p_type' , '$p_type_pr' , $p_no_pr , $p_has_poll, $p_pr_region, $p_pr_device, $p_bai_ha_xuat_ban, '$p_edit_date_start','$p_edit_date_end', '$p_pending_date_start', '$p_pending_date_end', $p_event_id, '$p_event_name', '$p_so_hop_dong', $p_page_number, $p_row_per_page, '$p_giai_dau_ids', '$p_cm_banner_ids', $p_thongke, $p_sk_mo_rong, $p_category_partners_id, '$p_code', $p_chk_approve_status_partners,'$source_id_list',$p_day_bai_24hmoney,$p_day_bai_layout_app_tinmoi,$p_chuyen_muc_gan_link, $p_only_pr)";
//        AnhTT toi_uu_tim_kiem_nhieuCM
    /* End 21-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */  
    $rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Lay danh sach bai viet da xuat ban
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		int 			$p_category_khampha_id Tìm theo ID chuyên mục khampha xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		int				$p_user_id		ID nguoi xem danh sach
		int				$p_editor_id		Tìm theo ID biên tập viên tạo bài
										-1: nếu không chọn BTV
		int				$p_approved_id		Tìm theo ID nguoi duyet bài
										-1: nếu không chọn BTV
		int				$p_published_id		Tìm theo ID nguoi XB bài
										-1: nếu không chọn BTV
		varchar(50) 	$p_type		Tìm loại bài
							- Bài thường
							- Bài video (VideoCode!=’’ hoặc Video_code=1)
							- Bài ảnh (Album_trang_anh>0)
							- Bài PR đầu trang (pr_dau_trang=1)
							- Bài PR ưu tiên (pr=1)
		tinyint			$p_has_poll		Tìm bài đã chọn poll hay chưa
								0: chưa chọn poll
								1: đã chọn poll
		tinyint			$p_has_comment		Tìm bài đã có bình luận mồi hay chưa
							0: chưa có
							1: đã có
		tinyint			$p_pr_region		Tìm vùng miền hiển thị bài PR
								0: Toàn quốc
								1: Miền bắc
								2: Miền nam
		Varchar(50)		$p_edit_date		Thời gian sửa bài, định dạng YYYY-MM-DD
		int				$p_event_id 		ID sự kiện
		Varchar(255)	$p_event_name		Tên sự kiện
        Varchar(255)	$p_so_hop_dong		Số hợp đồng
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
/* Begin 21-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
function be_get_all_published_news($p_news_id, $p_category_id, $p_category_khampha_id, $p_title, $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, $p_type , $p_has_poll, $p_has_comment, $p_pr_region, $p_pr_device, $p_published_date, $p_published_to_date, $p_event_id, $p_event_name, $p_so_hop_dong, $p_page_number, $p_row_per_page, $p_giai_dau_ids = '', $p_cm_banner_ids = '', $p_thongke = 0, $p_sk_mo_rong = 0, $p_category_partners_id = 0, $p_code = '',$p_type_pr = '',$p_no_pr = -1,$source_id_list='',$p_day_bai_24hmoney = 0,$p_day_bai_layout_app_tinmoi = 0, $p_chuyen_muc_gan_link = 0, $p_only_pr = -1)
{
//        AnhTT toi_uu_tim_kiem_nhieuCM
	$sql = "call be_get_all_published_news($p_news_id, '$p_category_id', $p_category_khampha_id, '$p_title', $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, '$p_type','$p_type_pr' ,$p_no_pr, $p_has_poll, $p_has_comment, $p_pr_region, $p_pr_device, '$p_published_date', '$p_published_to_date', $p_event_id, '$p_event_name', '$p_so_hop_dong', $p_page_number, $p_row_per_page, '$p_giai_dau_ids', '$p_cm_banner_ids', $p_thongke, $p_sk_mo_rong, $p_category_partners_id, '$p_code','$source_id_list',$p_day_bai_24hmoney,$p_day_bai_layout_app_tinmoi, $p_chuyen_muc_gan_link, $p_only_pr)";
//        AnhTT toi_uu_tim_kiem_nhieuCM
/* End 21-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_bo_sung_tieu_thuc_loc_cac_man_hinh_ds */
    $rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

function be_get_all_published_news_khampha($p_news_id, $p_category_id, $p_title, $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, $p_type , $p_has_poll, $p_has_comment, $p_pr_region, $p_pr_device, $p_published_date, $p_published_to_date, $p_page_number, $p_row_per_page)
{
	$sql = "call be_get_all_published_news_khampha($p_news_id, $p_category_id, '$p_title', $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, '$p_type' , $p_has_poll, $p_has_comment, $p_pr_region, $p_pr_device, '$p_published_date', '$p_published_to_date', $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * lấy thông chuyên mục xuất bản của 1 bài viết
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_all_category_by_one_news($p_news_id)
{
    /* begin 14/12/2016 TuyenNT fix_log_14_12_2016 */
    Gnud_Db_read_close();
    /* end 14/12/2016 TuyenNT fix_log_14_12_2016 */
	$sql = "call be_get_all_category_by_one_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * lấy thông chuyên mục xuất bản ben khampha của 1 bài viết
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_all_category_khampha_by_one_news($p_news_id)
{
	//Begin 20-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
	Gnud_Db_read_close();
	//End 20-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
	$sql = "call be_get_all_category_khampha_by_one_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Lay thong tin xuat ban kham pha theo id bang newscategory_khampha
 * @param int $p_id ID bang newscategory_khampha
 * @return array
 */
function be_get_newscategory_khampha_by_id($p_id)
{
	$sql = "call be_get_newscategory_khampha_by_id($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * lấy thông chuyên mục xuất bản của 1 album
 * @param int $p_album_id ID album
 * @return array
 */
function be_get_all_category_by_one_album($p_album_id)
{
	$sql = "call be_get_all_category_by_one_album($p_album_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * xoa tam mot bai viet
 * @param int $p_news_id ID bài viết
 * @param int $p_user_id ID nguoi xoa
 * @return array
 */
function be_delete_news($p_news_id, $p_user_id)
{
	$sql = "call be_delete_news($p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}
/**
 * Khoi phuc mot bai viet xoa tam
 * @param int $p_news_id ID bài viết
 * @param int $p_user_id ID nguoi phoi phuc
 * @return array
 */
function be_restore_deleted_news($p_news_id, $p_user_id)
{
	$sql = "call be_restore_deleted_news($p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * lay thong tin nhan xet mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_single_newscomment($p_news_id)
{
	$sql = "call be_get_single_newscomment($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * xoa han mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_remove_news($p_news_id)
{
	$sql = "call be_remove_news($p_news_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * gui duyet mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_update_news_to_pending_approval($p_news_id, $p_user_id=0)
{
	$p_user_id=($p_user_id>0)?$p_user_id:(int)$_SESSION['user_id'];
	$sql = "call be_update_news_to_pending_approval($p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * gui bien tap lai mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_update_news_to_drafting($p_news_id, $p_user_id=0)
{
	$p_user_id=($p_user_id>0)?$p_user_id:(int)$_SESSION['user_id'];
	$sql = "call be_update_news_to_drafting($p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}


/**
 * gui xuat ban mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_update_news_to_pending_publication($p_news_id, $p_user_id)
{
	$sql = "call be_update_news_to_pending_publication($p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}


/**
 * Thuc hien xuat ban mot bai viet
 * @param int $p_newscategory_id ID  newscategory
 * @return array
 */
function be_update_news_to_published($p_newscategory_id, $p_status, $p_user_id)
{
	$sql = "call be_update_news_to_published($p_newscategory_id, $p_status, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien hen gio xuat ban mot bai viet
 * @param int $p_newscategory_id ID  newscategory
 * @return array
 */
function be_update_news_publication_time($p_table_name='newscategory', $p_newscategory_id, $p_pending_status, $p_pending_date, $p_user_id)
{
	$sql = "call be_update_news_publication_time('$p_table_name', $p_newscategory_id, $p_pending_status, '$p_pending_date', $p_user_id)";

	$rs = Gnud_Db_write_query($sql);

}

/**
 * Thuc hien hen gio xuat ban mot bai viet
 * @param int $p_newscategory_id ID  newscategory
 * @return array
 */
function be_delete_news_publication_time($p_news_id)
{
	$sql = "call be_delete_news_publication_time($p_news_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien xuat ban mot bai viet tren chuyen muc kham pha
 * @param int $p_news_id ID bài viết
 * @return array
 */
//Begin 17-03-2016 : Thangnb dieu_chinh_thoi_gian_day_bai_sang_khampha
function be_update_newscategory_khampha_to_published($p_newscategory_khampha_id, $p_status, $p_user_id, $p_time_khampha = '')
{
	$sql = "call be_update_newscategory_khampha_to_published($p_newscategory_khampha_id, $p_status, $p_user_id, '$p_time_khampha')";
	$rs = Gnud_Db_write_query($sql);
}
//End 17-03-2016 : Thangnb dieu_chinh_thoi_gian_day_bai_sang_khampha
/**
 * Lấy danh sách bài viết xuất bản theo id sự kiện
 * @param int	p_event_id INT ID sự kiện
 */
function be_get_all_news_by_event($p_event_id, $p_date, $p_page, $p_page_number)
{
	$sql = "CALL be_get_all_news_by_event($p_event_id, '$p_date', $p_page, $p_page_number)";
	$rs = Gnud_Db_write_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Thuc hien cap nhat thong tin box dac biet dau trang chu
 * @param
 *      int $p_specialbox_id box dac biet
 *      int $p_category_id id chuyen muc
 *      int $p_news_id id bai viet
 *      int $p_user_id id BTV
 * @return array
 */
function be_specialbox_news_update($p_specialbox_id, $p_category_id, $p_news_id, $p_user_id)
{
	$sql = "call be_specialbox_news_update($p_specialbox_id, $p_category_id, $p_news_id, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien cap nhat thong tin nhan xet tin bai
 * @param
 *      int $p_newscomment_id  id nhan xet tin bai
 *      int $p_news_id id bai viet
 *      int $p_user_id id BTV
 *      int $p_user_name tai khoan truy cap cua BTV
 *      varchar(255) $p_date ngay cap nhat
 *      text $p_comment noi dung nhan xet
 * @return null
 */
function be_newscomment_update($p_newscomment_id, $p_news_id, $p_user_id, $p_user_name, $p_date, $p_comment)
{
	$sql = "CALL be_newscomment_update('$p_newscomment_id', $p_news_id, $p_user_id, '$p_user_name', '$p_date', '$p_comment')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien cap nhat binh luan doc gia gui
 * @param
 *      int $p_comment_id id binh luan doc gia gui
 *      varchar(255) $p_image duong dan anh
 *      varchar(255) $p_subject tieu de binh luan
 *      text $p_content noi dung binh luan
 *      varchar(30) $p_ngay_gui ngay gui binh luan, dinh dang YYYY-MM-DD HH:II:SS
 *      int $p_chieu_binh_luan chieu binh luan
 *      varchar(255) $p_source ten nguoi gui binh luan
 *      varchar(30) $p_ngay_duyet ngay duyet binh luan, dinh dang YYYY-MM-DD HH:II:SS
 *      int $p_nguoi_duyet_id id nguoi gui binh luan
 *      int $p_status trang thai xuat ban
 *      int $p_news_id id bai viet
 *      int $p_news_category id chuyen muc
 *      int $p_last_edit_id id nguoi sua cuoi
 *      int $p_parent id binh luan cap 1
 *      int $p_parent_num so luong binh luan cap 2
 * @return null
 */
function be_comment_update($p_comment_id, $p_image, $p_subject, $p_content, $p_ngay_gui, $p_chieu_binh_luan, $p_source, $p_ngay_duyet, $p_nguoi_duyet_id, $p_status, $p_news_id, $p_news_category, $p_last_edit_id, $p_email, $p_parent, $p_parent_num)
{
	$p_subject = fw24h_add_slashes($p_subject);
	$p_content = fw24h_add_slashes($p_content);
	$sql = "CALL be_comment_update('$p_comment_id', '$p_image', '$p_subject', '$p_content', '$p_ngay_gui', $p_chieu_binh_luan, '$p_source', '$p_ngay_duyet', $p_nguoi_duyet_id, $p_status, $p_news_id, $p_news_category, $p_last_edit_id, '$p_email', $p_parent, $p_parent_num)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Danh sach xuat ban them cua mot bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_all_xuat_ban_them_by_one_news($p_news_id)
{   
    /* Begin:Tytv - 24/04/2018 - fix_loi_log_bao_khi_gia_tri_truyen_vao_null */
    $rs = array();
    $p_news_id = intval($p_news_id);
    if($p_news_id<=0) return $rs;
    /* End:Tytv - 24/04/2018 - fix_loi_log_bao_khi_gia_tri_truyen_vao_null */
	$sql = "call be_get_all_xuat_ban_them_by_one_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Danh sach xuat ban them cua chuyen muc và loai
 * @param int $p_cat_id ID bài viết
 * @return array
 */
function be_get_all_xuat_ban_them($p_cat_id, $p_type, $page, $number_per_page, $p_title='')
{
	$sql = "call be_get_all_xuat_ban_them($p_cat_id, '$p_type', '$p_title', $page, $number_per_page)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Cap nhat thu tu cua xuat ban them
 * @param int $p_cat_id ID bài viết
 * @return array
 */
function be_update_order_xuat_ban_them($p_xuat_ban_them_id, $p_order, $p_user_id, $p_status)
{
	$sql = "call be_update_order_xuat_ban_them($p_xuat_ban_them_id, '$p_order', $p_user_id, $p_status)";
	$rs = Gnud_Db_write_query($sql);
	return $rs;
}

/**
 * Xoa xuat ban them
 * @param int $p_xuat_ban_them_id ID xuat ban them
 * @return array
 */
function be_delete_xuat_ban_them($p_xuat_ban_them_id)
{
	$sql = "call be_delete_xuat_ban_them($p_xuat_ban_them_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
 * lay thong tin xuat ban them xuat ban them
 * @param int $p_xuat_ban_them_id ID xuat ban them
 * @return array
 */
function be_get_single_xuat_ban_them($p_xuat_ban_them_id)
{
	$sql = "call be_get_single_xuat_ban_them($p_xuat_ban_them_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Danh sach cac box dac biet ma mot bai viet duoc de cu
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_all_special_box_by_one_news($p_news_id)
{
	$rs = be_get_all_special_box_by_select($p_news_id, 0);
	if (is_array($rs['data'])) {
		return $rs['data'];
	} else {
		return array();
	}
}

/**
 * Danh sach cac box dac biet ma mot bai viet duoc de cu
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_get_all_special_box_by_select($p_news_id=0, $p_news_category=0)
{
	$sql = "CALL be_get_all_special_box_by_select($p_news_id, $p_news_category)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Danh sach cac poll
 * @param int $p_poll_id ID poll
 * @param varchar(255) $p_name ten poll khong dau
 * @param int $p_page_number hien thi trang so may
 * @param int $p_row_per_page So luong ban ghi tren 1 trang
 * @return array
 */
function be_get_all_poll_by_select($p_poll_id=0, $p_name='', $p_page_number, $p_row_per_page)
{
	$sql = "CALL be_get_all_poll_by_select($p_poll_id, '$p_name', $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Danh sach bai viet da xuat ban
 * @param int $p_news_id ID bai viet
 * @param int $p_category_id ID chuyen muc xuat ban
 * @param varchar(255) $p_title tieu de bai viet khong dau
 * @param int $p_user_id ID BTV tao bai
 * @param varchar(50) $p_published_start Ngay xuat ban
 * @param varchar(50) $p_published_end Ngay xuat ban
 * @param int $p_page_number hien thi trang so may
 * @param int $p_row_per_page So luong ban ghi tren 1 trang
 * @param string $p_so_hop_dong So hop dong // phuonghv add 04/09/2015
 * @param string $p_news_type Loai tin bai // phuonghv add 04/09/2015
 * @return array
 */
function be_get_all_published_news_by_select($p_news_id=0, $p_category_id=0, $p_title='', $p_user_id=-1, $p_published_start='', $p_published_end='', $p_page_number=1, $p_row_per_page=30, $p_so_hop_dong='', $p_news_type='')
{
	$sql = "CALL be_get_all_published_news_by_select($p_news_id, $p_category_id, '$p_title', $p_user_id, '$p_published_start', '$p_published_end',$p_page_number, $p_row_per_page, '$p_so_hop_dong','$p_news_type')";
    $rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Cap nhat canonical cho bai viet
 * @param int $p_news_id ID bài viết
 * @param string $p_url url canonical cua bai viet
 * @param string $p_slug_title slug cua bai viet
 * @return array
 */
function be_update_canonical_news($p_news_id, $p_url, $p_slug_title='')
{
	$sql = "CALL update_data('news', 'url', '$p_url', 'Where ID=$p_news_id')";
	$rs = Gnud_Db_write_query($sql);
    if ($p_slug_title != '') {
        $p_slug_title = fw24h_iso_ascii($p_slug_title, '');
		$p_slug_title = str_replace(array(' / ',' /','/ '), '/', $p_slug_title);
		$p_slug_title = str_replace( ' ', '-', $p_slug_title);
        $sql = "CALL update_data('news', 'SlugTitle', '$p_slug_title', 'Where ID=$p_news_id')";
        $rs = Gnud_Db_write_query($sql);
    }
}

/**
 * Cap nhat thoi gian thao tac bai viet
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_update_date_news($p_news_id)
{
	$v_date = date('Y-m-d H:i:s');
	$sql = "CALL update_data('news', 'Date', '$v_date', 'Where ID=$p_news_id')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * cap nhat chuyen muc xuat ban cho bai viet
 * @param int $p_news_id ID bài viết
 * @param int $p_category_id ID chuyen muc
 * @param int $p_status trang thai xuat ban
 * @param int $p_published_date thoi gian xuat ban YYYY-mm-dd
 * @param string $p_user_id int nguoi xuat ban
 * @param string $p_has_video int danh dau la bai video
 * @param string $p_album_id int ID album anh
 * @return null
 */
function be_newscategory_update($p_news_id, $p_category_id, $p_status, $p_published_date, $p_user_id, $p_has_video=-1, $p_album_id=-1)
{
	$sql = "CALL be_newscategory_update($p_news_id, $p_category_id, $p_status, '$p_published_date', $p_user_id, $p_has_video, $p_album_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * cap nhat chuyen muc khampha.vn cho bai viet
 * @param int $p_news_id ID bài viết
 * @param int $p_category_id ID chuyen muc khampha.vn
 * @param int $p_status trang thai xuat ban
 * @param int $p_published_date thoi gian xuat ban YYYY-mm-dd
 * @param string $p_user_id int nguoi xuat ban
 * @return null
 */
function be_newscategory_khampha_update($p_news_id, $p_category_id, $p_status, $p_published_date, $p_user_id, $p_has_video=-1)
{
	$sql = "CALL be_newscategory_khampha_update($p_news_id, $p_category_id, $p_status, '$p_published_date', $p_user_id, $p_has_video)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Xoa cac chuyen muc khong duoc chon khi cap nhat chuyen muc cho bai viet
 * @param string $p_where : Dieu kien xoa
 * @return null
 */
function be_newscategory_delete_by_where($p_where)
{
	$sql = "CALL delete_data('newscategory', '$p_where')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Xoa cac chuyen muc khong duoc chon khi cap nhat chuyen muc cho bai viet
 * @param string $p_where : Dieu kien xoa
 * @return null
 */
function be_newscategory_khampha_delete_by_where($p_where)
{
	$sql = "CALL delete_data('newscategory_khampha', '$p_where')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Xoa cac thong tin xuat ban dau trang chu cua 1 bai viet
 * @param string $p_where : Dieu kien xoa
 * @return null
 */
function be_specialbox_news_delete_by_where($p_where)
{

	$p_where = fw24h_add_slashes($p_where);
	$sql = "CALL delete_data('t_specialbox_news', '$p_where')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Cap nhat thong tin xuat ban them cho 1 bai viet
 * @param int $p_news_id : ID bai viet
 * @param string $p_sql_select : cau lenh tra ve danh sach can them vao bang
 * @return null
 */
function be_xuat_ban_them_update($p_news_id, $p_sql_select)
{
	$p_sql_select = fw24h_add_slashes($p_sql_select);
	$sql = "CALL be_xuat_ban_them_update($p_news_id, '$p_sql_select')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Xoa xuat ban theo tin id bai viet vao ma box
 * @param int $p_news_id : ID bai viet
 * @param string $p_box_code : Box code
 * @return string
 */
function be_remove_xuat_ban_them_by_news_id($p_news_id, $p_box_code)
{
	$sql = "CALL be_remove_xuat_ban_them_by_news_id($p_news_id, '$p_box_code')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Them bai viet
 * @param array $p_arr : array chua thong tin bai viet
 * @param int $p_news_id : ID bai viet
 * @param int $p_user_id : ID BTV
 * @return ID bai viet moi duoc insert
 */
function be_update_news($p_arr, $p_news_id, $p_user_id, $p_status=0)
{
    if (!is_array($p_arr)) {
        return false;
    }
    // replace bỏ thẻ p trống 
    $p_arr['txt_body'] = str_replace('<p></p>', '', $p_arr['txt_body']);
	$v_str_params = $p_news_id.", ";
	$v_str_params .= "'".$p_arr['txt_title']."', ";
	$v_str_params .= "'"._utf8_to_ascii($p_arr['txt_title'])."', ";
	$v_str_params .= "'".$p_arr['txt_slug_title']."', ";
	$v_str_params .= "'".$p_arr['txt_summary_image']."', ";
	$v_str_params .= "'".$p_arr['txt_summary_image_chu_nhat']."', "; // edit VinhLQ
	$v_str_params .= "'".str_replace(chr(8),'',$p_arr['txt_summary_image_tip'])."', ";
	$v_str_params .= "'".$p_arr['txt_summary_image_text']."', ";
	$v_str_params .= "'".$p_arr['txt_breaking_news_image']."', ";
	$v_str_params .= "'".$p_arr['txt_breaking_news_summary']."', ";
	$v_str_params .= "'".$p_arr['txt_summary']."', ";
	$v_str_params .= "'".$p_arr['txt_short_summary']."', ";
	$v_str_params .= "'".$p_arr['txt_body']."', ";
	$v_str_params .= "'".$p_arr['txt_bai_lien_quan']."', ";
	$v_str_params .= "'".$p_arr['txt_author']."', ";
	$v_str_params .= "'".$p_arr['txt_source']."', ";
	$v_str_params .= ($p_arr['txt_album_id']>0) ? $p_arr['txt_album_id'].', ' : '0, ';
	$v_str_params .= ($p_arr['sel_chuyen_muc']>0) ? $p_arr['sel_chuyen_muc'].', ' : '0, ';
    $v_str_params .= "'".$p_arr['txt_keywords']."', ";
    $v_str_params .= "'".$p_arr['txt_sapo']."', ";
    $v_str_params .= ($p_arr['chk_sao_xanh']>0) ? $p_arr['chk_sao_xanh'].', ' : '0, ';
    $v_str_params .= ($p_arr['chk_sao_do']>0) ? $p_arr['chk_sao_do'].', ' : '0, ';
    $v_str_params .= ($p_arr['chk_pr_uu_tien']>0) ? $p_arr['chk_pr_uu_tien'].', ' : '0, ';
    $v_str_params .= ($p_arr['chk_pr_dau_trang']>0) ? $p_arr['chk_pr_dau_trang'].', ' : '0, ';
    $v_str_params .= ($p_arr['chk_pr_tu_van']>0) ? $p_arr['chk_pr_tu_van'].', ' : '0, ';
    $v_str_params .= ($p_arr['chk_pr_diem_thi']>0) ? $p_arr['chk_pr_diem_thi'].', ' : '0, ';
    $v_str_params .= '0, ';
    $v_str_params .= '0, ';
    $v_str_params .= '0, ';
    $v_str_params .= ($p_arr['sel_pr_region']>0) ? $p_arr['sel_pr_region'].', ' : '0, ';
    $v_str_params .= "'".$p_arr['txt_so_hop_dong']."', ";
    $v_str_params .= ($p_arr['chk_thu_phi']>0 && $v_is_pr_news) ? $p_arr['chk_thu_phi'].', ' : '0, ';
    $v_str_params .= ($p_arr['rad_customer_info_type']>0) ? $p_arr['rad_customer_info_type'].', ' : '0, ';
    $v_str_params .= "'".$p_arr['txt_customer_info']."', ";
    $v_str_params .= "'".$p_arr['txt_video_code']."', ";
    $v_str_params .= "'".$p_arr['txt_video_homepage_image']."', ";
    $v_str_params .= "'".$p_arr['txt_video_mobile_url']."', ";
    $v_str_params .= "'".$p_arr['txt_video_mobile_hd_url']."', ";
    $v_str_params .= "'$today', ";
    $v_str_params .= "'$today', ";
    $v_str_params .= "'$today', ";
    $v_str_params .= $p_status.', ';
    $v_str_params .= $p_user_id.', ';
	$v_str_params .= "'".intval($p_arr['txt_poll_id'])."', ";
	$v_str_params .= "'".$p_arr['txt_next_bai_phan_tach']."', ";
	$v_str_params .= "'".$p_arr['txt_prev_bai_phan_tach']."',";
	$v_str_params .= "'".$p_arr['c_anh_chia_se_mxh']."',";
    $v_str_params .= "'".$p_arr['txt_bai_lien_quan_duoi_sapo']."',";
    //  03-02-2021 DanNC begin bo sung cat anh ty le 3:2
    $v_str_params .= "'".$p_arr['txt_summary_image_chu_nhat_3_2']."',";
    $v_str_params .= "'".$p_arr['rad_news_type']."'";
    //  03-02-2021 DanNC end bo sung cat anh ty le 3:2
	$sql = "CALL be_update_news($v_str_params)";
	$rs = Gnud_Db_write_query($sql);
    return $rs[0]['news_id'];
}

/**
 * Cap nhat thong tin tim kiem cho bang news
 * @param int $p_news_id : ID bai viet
 * @param varchar(255) $p_ascii_title : Tieu de bai viet khong dau
 * @param int $p_is_video : danh dau la bai video (-1 neu khong thay doi du lieu)
 * @param int $p_has_approved_comments : danh dau co binh luan duoc duyet (-1 neu khong thay doi du lieu)
 * @param string $p_ascii_summary Sapo bai viet ko dau
 * @param string $p_ascii_body Body bai viet ko dau
 */
function be_update_news_search($p_news_id, $p_ascii_title, $p_is_video=-1, $p_poll_id=-1, $p_has_approved_comments=-1, $p_ascii_summary='', $p_ascii_body='')
{
    $sql = "CALL be_update_news_search($p_news_id, '$p_ascii_title', $p_is_video, $p_poll_id, $p_has_approved_comments, \"$p_ascii_summary\", \"$p_ascii_body\")";
    $rs = Gnud_Db_write_query($sql);
}

/**
 * Lay ghi chu bai viet cua bien tap vien
 * @param int $p_news_id : ID bai viet
 * @return string comment
 */
/*Begin 27-07-2016 trungcq bo_sung_xem_lich_su_nhan_xet_tin_bai*/
function be_get_note_by_one_news($p_news_id, $p_get_all=0)
{

    $sql = "CALL be_get_note_by_one_news($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    if($p_get_all == 1) return $rs;
    return $rs[0]['Comment'];
}
/*End 27-07-2016 trungcq bo_sung_xem_lich_su_nhan_xet_tin_bai*/

 /**
* Luu lich su thay doi cua bai viet
* @param int $p_khoa ID cua doi tuong thay doi
* @param string $p_loai Loai doi tuong (news, category)
* @param array $p_dulieu Du lieu truoc thay doi
* @param string $p_nguoi_tao username nguoi thay doi
* @return array
*/
function be_news_cap_nhat_lich_su($p_khoa, $p_loai, $p_dulieu, $p_nguoi_tao) {
	$p_dulieu = base64_encode(gzcompress(json_encode($p_dulieu)));
	$sql = "call be_news_cap_nhat_lich_su($p_khoa, '$p_loai', '$p_dulieu', '$p_nguoi_tao')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

 /**
* Thay doi thu tu xuat ban cua bai viet
* @param int $p_newscategory_id_above
* @param int $p_newscategory_id_under
* @param int $p_user_id id nguoi thay doi
* @return string
*/
function be_reorder_newscategory($p_newscategory_id_above, $p_newscategory_id_under, $p_user_id) {
	$sql = "call be_reorder_newscategory($p_newscategory_id_above, $p_newscategory_id_under, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0]['RET_ERROR'];
}

 /**
* Lay danh sach lich su thay doi cua bai viet
* @param int $p_khoa ID cua doi tuong thay doi
* @param int $page Trang can lay
* @param int $number_per_page So doi tuong tren trang
* @return array
*/
function be_news_danh_sach_lich_su($p_khoa, $page, $number_per_page) {
	$sql = "call be_news_danh_sach_lich_su($p_khoa, $page, $number_per_page)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

 /**
* Lay chi tiet lich su thay doi cua bai viet
* @param int $p_history_id ID cua lich su
* @return array
*/
function be_news_chi_tiet_lich_su($p_history_id) {
	$sql = "call be_news_chi_tiet_lich_su($p_history_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/**
 * Danh sach album da tao
 * @param int $p_album_id ID album
 * @param varchar(255) $p_name ten album
 * @param int $p_category_id ID chuyen muc
 * @param int $p_user_id ID BTV tao album
 * @param varchar(50) $p_created_start Ngay xuat ban
 * @param varchar(50) $p_created_end Ngay xuat ban
 * @param int $p_page_number hien thi trang so may
 * @param int $p_row_per_page So luong ban ghi tren 1 trang
 * @return array
 */
function be_get_all_album($p_album_id=0, $p_name='', $p_category_id=0, $p_user_id=-1, $p_created_start='', $p_created_end='', $p_page_number=1, $p_row_per_page=30, $p_kich_thuoc = 0)
{
	$sql = "CALL be_get_all_album($p_album_id, '$p_name', $p_category_id, $p_user_id, '$p_created_start', '$p_created_end', $p_page_number, $p_row_per_page, $p_kich_thuoc)";
	$rs = Gnud_Db_read_query($sql);
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	return $return;
}

/**
 * Lấy dữ liệu bài viết
 * 
 * @params int $p_news_id ID bài viết
 * @return array
 */
function be_get_news_data($p_news_id) {
    /* begin 13/12/2016 TuyenNT Fix_bug_MySQL_server_has_gone_away_CALL_be_get_news_data */
    Gnud_Db_read_close();
    /* end 13/12/2016 TuyenNT Fix_bug_MySQL_server_has_gone_away_CALL_be_get_news_data */
	$sql = "CALL be_get_news_data($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Cập nhật dữ liệu cho bài viết
 * 
 * @params int $p_news_id ID bài viết
 * @params string $p_key Kiểu dữ liệu
 * @params string $p_value Giá trị của dữ liệu
 * @return
 */
function be_update_news_data($p_news_id, $p_key, $p_value)
{
    if ($p_news_id <= 0 || $p_key == '') {
        return false;
    }
    // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
    if ($p_value === 0 || $p_value == '') {
    // end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
        $v_sql = "CALL delete_data('t_news_data', 'c_news_id = $p_news_id AND c_key = \'$p_key\'')";
    } else {
        $v_sql = "CALL be_update_news_data($p_news_id, '$p_key', '$p_value')";
    }
    $rs = Gnud_Db_write_query($v_sql);
}
/**
* phuonghv add 12/09/2015
* Lấy chi tiet 1 chủ đề 
* @param int p_tag_app_id  ID chu de
* @return array
*/
function be_get_single_tag_app_profile($p_tag_app_id) {
    $sql = "call be_get_single_tag_app_profile($p_tag_app_id)";
    $rs = Gnud_Db_read_query($sql);
	return $rs[0];
}
/**
* phuonghv add 12/09/2015
* Lấy danh sach chuyen muc theo tag id
* @param int p_tag_app_id  ID chu de
* @return array
*/
function be_get_all_category_by_tag_app_id($p_tag_app_id){
    $sql = "call be_get_all_category_by_tag_app_id($p_tag_app_id)";
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
* phuonghv add 12/09/2015
* Cập nhật chủ đề trên màn hình danh sách.
* @param int p_tag_app_id  ID chu de
* @param string p_tag_tren_app  Tên tag hiển thị trên app
* @param int p_tag_hot  loại tag hot
* @param int p_trang_thai_xuat_ban  Trạng thái xuất bản
* @param int p_nguoi_sua  ID người sửa
* @return array
*/
function be_update_seo_tag_app_on_list($p_tag_app_id, $p_tag_tren_app, $p_tag_hot, $p_trang_thai_xuat_ban, $p_nguoi_sua){
    $sql = "call be_update_seo_tag_app_on_list($p_tag_app_id, '$p_tag_tren_app', $p_tag_hot, $p_trang_thai_xuat_ban, $p_nguoi_sua)";
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/*
* phuonghv add 14/09/2015
* Xóa 1 chủ đề
* @param int p_tag_app_id  ID chu de
* @return array
*/
function be_delete_seo_tag_app($p_tag_app_id){
    $sql = "call be_delete_seo_tag_app($p_tag_app_id)";
    $rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/*
* phuonghv add 14/09/2015
* Xóa 1 chủ đề là profile
* @param int p_tag_app_id  ID chu de
* @return array
*/
function be_delete_seo_tag_app_profile($p_tag_app_id){
	$sql = "call be_delete_seo_tag_app_profile($p_tag_app_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}


/*============== Begin: 27/10/2015- trungcq bo sung chuc_nang_chon_bai_viet_cho_chu_de ==============*/
/*
* trungcq add 27/10/2015
* Xóa 1 bài viết được gắn vào chủ đề
* @param int p_news_id  ID tin tức
* @param int p_tag_app_id  ID chủ đề
* @return array
*/
function be_delete_news_by_one_tag($p_news_id, $p_tag_app_id){
	$sql = "CALL be_delete_news_by_one_tag($p_news_id, $p_tag_app_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

// begin 18/02/2016 tuyennt toi_uu_man_hinh_chon_bai_viet_vao_chu_de
/**
* author: Tuyennt
* Lay danh sach bài viết để thực hiện chọn thêm bài viết vào chủ đề
* @param int p_news_id ID bài viết
* @param int p_category_id ID chuyen muc
* @param int p_title Tiêu đề bài viết
* @param int p_user_id ID người sửa
* @param int p_published_start Ngày xuất bản từ ngày
* @param string p_published_end Ngày xuất bản đến ngày
* @param int p_page_number Trang cần xem
* @param string p_number_item_per_page Số bản ghi/trang
* @param string p_so_hop_dong Số hợp đồng
* @param string p_type Loại bài viết
* @param int p_news_status Trạng thái xuất bản 0: chưa xuất bản ,1: đã xuất bản.
* @param text p_list_id_news_tag danh sách id bài viết đã được chọn vào chủ đề
* @return array
*/
function be_get_all_news_for_tag_app($p_news_id, $p_category_id, $p_title, $p_danh_sach_id_bai_viet, $p_user_id, $p_published_start, $p_published_end, $p_page_number, $p_row_per_page, $p_so_hop_dong, $p_type, $p_news_status, $p_list_id_news_tag) {
    $sql = "call be_get_all_news_for_tag_app($p_news_id, $p_category_id, '$p_title','$p_danh_sach_id_bai_viet', $p_user_id, '$p_published_start', '$p_published_end', $p_page_number, $p_row_per_page, '$p_so_hop_dong', '$p_type', $p_news_status, '$p_list_id_news_tag')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}

// end 18/02/2016 tuyennt toi_uu_man_hinh_chon_bai_viet_vao_chu_de
/*Begin 29-04-2016 : Thangnb xu_ly_bai_pr_gia_re */
function be_delete_from_t_news_data($p_news_id, $p_key) {
	$sql = "CALL be_delete_from_t_news_data($p_news_id, '$p_key');";
	$rs = Gnud_Db_write_query($sql); 
}
/*End 29-04-2016 : Thangnb xu_ly_bai_pr_gia_re */

// begin 08/08/2016 TuyenNT xay_dung_co_che_auto_save_bai_trong_ocm_24h
/**
* Lấy lay danh sach log auto save news
* @param 
 *  $p_user_id     id user dang nhap
 *  $page           so trang
 *  $p_number       so items hien thi tren trang
* @return array
*/
function be_danh_sach_log_auto_save_news($p_user_id,$page,$p_number){
    $sql = "call be_danh_sach_log_auto_save_news('$p_user_id','$page','$p_number')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/**
* Lấy chi tiet 1 auto log save news
* @param 
 *  $p_log_id     id 1 log auto save news
* @return array
*/
function be_chi_tiet_1_log_auto_save_news($p_log_id){
    $sql = "call be_chi_tiet_1_log_auto_save_news('$p_log_id')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/**
* Lấy chi tiet 1 auto log save news
* @param 
 *  $p_log_id     id 1 log auto save news
* @return array
*/
//Begin AnhTT bo_sung_thoi_gian_auto_save_news
function be_cap_nhat_1_log_auto_save_news($p_log_id,$p_edit_user,$p_title,$p_Summary,$p_Body,$p_data_detail_json){
    $sql = "call be_cap_nhat_1_log_auto_save_news('$p_log_id','$p_edit_user','$p_title','$p_Summary','$p_Body','$p_data_detail_json')";
    //Begin AnhTT bo_sung_thoi_gian_auto_save_news
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}
/**
* xoa 1 auto log save news
* @param 
 *  $p_log_id     id 1 log auto save news
* @return array
*/
function be_xoa_1_log_auto_save_news($p_log_id){
    $sql = "call be_xoa_1_log_auto_save_news('$p_log_id')";
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}
// end 08/08/2016 TuyenNT xay_dung_co_che_auto_save_bai_trong_ocm_24h
/* Begin anhpt1 12/08/2016 xy_ly_loc_bai_viet_theo_su_kien */
function be_get_all_event_by_one_news($p_news_id){
    $sql = "call be_get_all_event_by_one_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/* End anhpt1 12/08/2016 xy_ly_loc_bai_viet_theo_su_kien */
/**
* author: anhpt1
* Lay danh sach bài viết để thực hiện chọn thêm bài viết vào chủ đề
* @param Text $p_ds_category_id ds ID chuyên mục
* @param INT $p_number_items số lượng keyword lấy ra
* @return array
*/
function  be_keyword_theo_list_chuyen_muc($p_ds_category_id,$p_number_items = 500){
    $sql = "call be_keyword_theo_list_chuyen_muc('$p_ds_category_id',$p_number_items)";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/**
* author: anhpt1
* Lấy danh sách url keyword được nhập trong bài viết
* @param Text $p_lis_keyword ds keyword được nhập trong bài viết
* @return array
*/
function be_tag_url_theo_list_keyword($p_lis_keyword){
    $sql = "call be_tag_url_theo_list_keyword('$p_lis_keyword')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/**
* author: anhpt1
* Lấy event theo danh sách ID 
* @param Text $p_lis_id_event ds id event
* @return array
*/
function be_event_theo_ds_id($p_list_id_event){
    $sql = "call be_event_theo_ds_id('$p_list_id_event')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/* End anhpt1 29/3/2016 de_xuat_event_profile_theo_noi_dung_bai_viet */
// begin 19/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo
/* Begin 04-08-2016 trungcq bo_sung_gui_mail_thong_bao_toi_uu_seo_bai_viet*/
/**
 * Cap nhat thoi gian thao gửi xuất bản bài viết, trường hợp thêm mới bài viết và thực hiện gửi xuất bản luôn
 * @param int $p_news_id ID bài viết
 * @return array
 */
function be_update_date_approved_news($p_news_id)
{
	$v_date = date('Y-m-d H:i:s');
	$sql = "CALL update_data('news', 'DateApproved', '$v_date', 'Where ID=$p_news_id')";
	$rs = Gnud_Db_write_query($sql);
}
// end 19/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo


/* Begin - Tytv: 25/10/2016 - quan_ly_quiz (tích hợp quiz vào bài viết) */
/*
* SP: be_delete_single_quiz_news: Xóa dữ liệu quan hệ bảng news và quiz
* @param bigint			p_news_id		ID news
* @param bigint			p_news_id		ID news
* @Test:
	Call be_delete_single_quiz_news(1,1);
	Call be_delete_single_quiz_news(1,2);
*/
function be_delete_single_quiz_news($p_id_quiz, $p_news_id)
{
    $sql = "call be_delete_single_quiz_news($p_id_quiz,$p_news_id)";
	$rs = Gnud_Db_write_query($sql);
}
/*
* SP: be_update_single_quiz: Cập nhật 1 chi tiết quiz
		p_id_quiz_news	bigint(20)	NOT NULL	Khóa của bảng t_quiz_news
		p_id_quiz	bigint(20)	NOT NULL	Khóa của bảng t_quiz
		p_id_news	bigint(20)	NOT NULL	Khóa của bảng news 

* @Test:
	Call be_update_single_quiz_news(786853,1,1);	
*/
function be_update_single_quiz_news($p_quiz_news_id,$p_id_quiz,$p_news_id)
{
    $sql = "call be_update_single_quiz_news($p_quiz_news_id,$p_id_quiz,$p_news_id)";
	$rs = Gnud_Db_write_query($sql);
}
/* End - Tytv: 25/10/2016 quan_ly_quiz (tích hợp quiz vào bài viết) */


/*
SP: be_get_all_published_news_layout: Lấy danh sách bài viết đã xuất bản chuyên mục layout
Tham so:
	p_news_id 				BIGINT(20)		Tìm theo ID bài viết
	p_category_id			BIGINT(20)		Tìm theo ID chuyên mục xuất bản
	p_title					VARCHAR(255)	Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
	p_published_date		VARCHAR(50)		Ngày bắt đầu xuất bản
	p_published_to_date		VARCHAR(50)		Ngày kết thúc xuất bản
	p_giai_dau_ids			TEXT			Danh sách ID giải đấu
	p_cm_banner_ids			TEXT			Danh sách ID chuyên mục xuất bản layout
	p_thong_ke				INT(11)			Thống kê
	p_page_number			INT(11)			Số trang
	p_row_per_page			INT(11)			Số dòng trên 1 trang
return: array
*/
function be_get_all_published_news_layout(
	$p_news_id
	,$p_category_id
	,$p_title
	,$p_published_date
	,$p_published_to_date
	,$p_giai_dau_ids
	,$p_cm_banner_ids
	,$p_thong_ke
	,$p_export_excel
	,$p_page_number
	,$p_row_per_page
)
{
    $sql = "call be_get_all_published_news_layout($p_news_id, $p_category_id, '$p_title', '$p_published_date', '$p_published_to_date', '$p_giai_dau_ids', '$p_cm_banner_ids', $p_thong_ke, $p_export_excel, $p_page_number, $p_row_per_page)";
	$rs = Gnud_Db_read_query($sql); 
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	$return['thong_ke'] = $rs[0]['so_dong'];
	return $return;
}

/*Begin 02-07-2018 trungcq XLCYCMHENG_31872_toi_uu_chuc_nang_nhap_nguon_thong_tin*/
/**
* author: truncq
* Lấy nguồn theo ID tin bài
* @param integer $p_news_id ID tin bài
* @return array
*/
function be_get_source_by_news_id($p_news_id=0){
    $sql = "call be_get_source_by_news_id($p_news_id)";
	$rs = Gnud_Db_read_query($sql); 
	return $rs[0];
}

/**
* author: truncq
* Cập nhật nguồn thông tin
* @param integer $p_news_id ID tin bài
* @param integer $p_source_id ID nguồn thông tin
* @return array
*/
function be_news_source_update($p_news_id=0, $p_source_id=0)
{
    $sql = "call be_news_source_update($p_news_id,$p_source_id)";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}
/*End 02-07-2018 trungcq XLCYCMHENG_31872_toi_uu_chuc_nang_nhap_nguon_thong_tin*/

/*Begin XLCYCMHENG_31988_bo_sung_chuc_nang_thong_ke_bai_viet_theo_nguon*/
/*
SP: be_get_all_published_news_by_source: Lấy danh sách bài viết đã xuất bản theo nguồn
Tham so:
	p_news_id 				BIGINT(20)		Tìm theo ID bài viết
	p_category_id			BIGINT(20)		Tìm theo ID chuyên mục xuất bản
	p_title					VARCHAR(255)	Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
	p_published_date		VARCHAR(50)		Ngày bắt đầu xuất bản
	p_published_to_date		VARCHAR(50)		Ngày kết thúc xuất bản
	p_source_id_list		TEXT			Danh sách ID nguồn
	p_thong_ke				INT(11)			Thống kê
	p_export_excel			INT(11)			Xuất exel
	p_page_number			INT(11)			Số trang
	p_row_per_page			INT(11)			Số dòng trên 1 trang
return:array 
*/
function be_get_all_published_news_by_source(
	$p_news_id
	,$p_category_id
	,$p_user_id
	,$p_title
	,$p_published_date
	,$p_published_to_date
	,$p_source_id_list
	,$p_published_id
	,$p_thong_ke
	,$p_export_excel
	,$p_page_number
	,$p_row_per_page
    ,$v_cach_tao_bai = -1
)
{
    $sql = "call be_get_all_published_news_by_source($p_news_id, $p_category_id, $p_user_id, '$p_title', '$p_published_date', '$p_published_to_date', '$p_source_id_list',$p_published_id, $p_thong_ke, $p_export_excel, $p_page_number, $p_row_per_page, $v_cach_tao_bai)";
	$rs = Gnud_Db_read_query($sql); 
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	$return['thong_ke'] = $rs[0];
	return $return;
}
/*End XLCYCMHENG_31988_bo_sung_chuc_nang_thong_ke_bai_viet_theo_nguon*/


/*
SP: be_get_all_published_news_layout: Lấy danh sách bài viết đã xuất bản chuyên mục layout
Tham so:
	p_news_id 				BIGINT(20)		Tìm theo ID bài viết
	p_category_id			BIGINT(20)		Tìm theo ID chuyên mục xuất bản
	p_title					VARCHAR(255)	Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
	p_published_date		VARCHAR(50)		Ngày bắt đầu xuất bản
	p_published_to_date		VARCHAR(50)		Ngày kết thúc xuất bản
	p_giai_dau_ids			TEXT			Danh sách ID giải đấu
	p_giai_dau_ids_full		TEXT			Danh sách ID giải đấu full ==> Do lưu trong bảng t_news_data có nhiều dữ liệu khác nên cần giới hạn
	p_cm_banner_ids			TEXT			Danh sách ID chuyên mục xuất bản layout
	p_dong_bo_erp			INT(11)			Trạng thái đông bộ ERP
	p_so_hop_dong			VARCHAR(500)	Số hợp đồng
	p_ma_book				VARCHAR(500)	Mã book
	p_thong_ke				INT(11)			Thống kê
	p_page_number			INT(11)			Số trang
	p_row_per_page			INT(11)			Số dòng trên 1 trang
return: array
*/
function be_get_all_published_news_layout_2018(
	$p_news_id
	,$p_category_id
	,$p_title
	,$p_published_date
	,$p_published_to_date
	,$p_giai_dau_ids
	,$p_giai_dau_ids_full
	,$p_cm_banner_ids
    ,$v_loai_bai_send_erp
	,$p_dong_bo_erp
	,$p_so_hop_dong
	,$p_ma_book
	,$p_thong_ke
	,$p_page_number
	,$p_row_per_page
)
{
    $sql = "call be_get_all_published_news_layout_2018("
		. "$p_news_id"
		. ", $p_category_id"
		. ", '$p_title'"
		. ", '$p_published_date'"
		. ", '$p_published_to_date'"
		. ", '$p_giai_dau_ids'"
		. ", '$p_giai_dau_ids_full'"
		. ", '$p_cm_banner_ids'"
		. ", '$v_loai_bai_send_erp'"
		. ", '$p_dong_bo_erp'"
		. ", '$p_so_hop_dong'"
		. ", '$p_ma_book'"
		. ", $p_thong_ke"
		. ", $p_page_number"
		. ", $p_row_per_page
	)";
	$rs = Gnud_Db_read_query($sql); 
	$return['data'] = $rs['record0'];
	$return['tong_so_dong'] = $rs['record1'][0]['tong_so_dong'];
	$return['thong_ke'] = $rs[0]['so_dong'];
	return $return;
}

/**
* Trả lại array chứa danh sách trạng thái đồng bộ erp
* @return array
*/ 
function be_danh_sach_trang_thai_dong_bo_erp()
{
	$v_arr_trang_thai = array();
	$v_arr_trang_thai[] = array('c_code'=>'-1', 'c_name'=>'Tất cả');
	$v_arr_trang_thai[] = array('c_code'=>'1', 'c_name'=>'Đã có hợp đồng');
	$v_arr_trang_thai[] = array('c_code'=>'2', 'c_name'=>'Chưa có hợp đồng');
	return $v_arr_trang_thai;
}


/*
* cập nhật bảng t_news_layout_giai_dau_erp
* @param bigint			p_id				ID 
* @param varchar(300)	p_so_hop_dong		Số hợp đồng
* @param varchar(300)	p_ma_book			Mã Book
* @param tinyint		p_trang_thai 		Trạng thái đồng bộ thành công/không thành công ERP
* @param varchar(255)	p_nguoi_cap_nhat	Người sửa cuôi
* @return:array
*/
function be_update_news_layout_giai_dau_erp($p_id=0,$p_news_id=0, $p_so_hop_dong='', $p_ma_book='', $p_trang_thai='', $p_loai='', $p_nguoi_cap_nhat='')
{
	$sql = "call be_update_news_layout_giai_dau_erp($p_id, $p_news_id, '$p_so_hop_dong', '$p_ma_book', '$p_trang_thai', '$p_loai', '$p_nguoi_cap_nhat')";
	$rs = Gnud_Db_write_query($sql);
	return $rs[0];
}

/**
* Lbe_chi_tiet_news_layout_giai_dau_erp: Lấy chi tiết tin bài đồng bộ erp
* @param int $p_id
* @return array
*/
function be_chi_tiet_news_layout_giai_dau_erp($p_id) {
	$sql = "call be_chi_tiet_news_layout_giai_dau_erp($p_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

/* Begin: 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong */
/**
 * Hàm cập nhật chuyên mục đối tác cho tin bài
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 8-11-2018
 * @param:
 *  $p_news_id          ID bài viết
 *  $p_category_id      ID chuyen muc đối tác
 *  $p_code             mã đối tác
 *  $p_status           trang thai xuat ban
 *  $p_published_date   thoi gian xuat ban YYYY-mm-dd
 *  $p_user_id          int nguoi xuat ban
 *  $p_has_video        Xác định có phải bài video hay không
 * @return null
 */
function be_newscategory_partners_update($p_news_id, $p_category_id, $p_code, $p_status, $p_published_date, $p_user_id, $p_has_video=-1)
{
	$sql = "CALL be_newscategory_partners_update($p_news_id, $p_category_id, '$p_code', $p_status, '$p_published_date', $p_user_id, $p_has_video)";
	$rs = Gnud_Db_write_query($sql);
}


/**
 * Lấy chi tiết thông tin đẩy bài viết từ 24h sang đối tác theo id bài viết
 * @tham số 
 *  $p_news_id    ID bài viết
 * @return array
 */
function be_get_single_24h_partners_by_news($p_news_id) {
    $sql = "call be_get_single_24h_partners_by_news($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}

/**
 * Cập nhật thông tin đẩy bài viết 24h sang đối tác
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 8-11-2018
 * @tham số 
 *  p_pk_ID                 ID bảng
 *  p_fk_NewsID_24h         ID bài viết 24h
 *  p_fk_NewsID_partners    đối tác
 *  p_code                  Mã đối tác
 *  p_status                Trạng thái
 *  p_user_update           NGười cập nhật cuối
 * @return array
 */
function be_update_24h_partners($p_pk_ID, $p_fk_NewsID_24h, $p_fk_NewsID_partners, $p_code, $p_status, $p_user_update, $p_24h_news_status = 0) {
    $sql = "call be_update_24h_partners($p_pk_ID, $p_fk_NewsID_24h, $p_fk_NewsID_partners, '$p_code', $p_status, '$p_user_update', $p_24h_news_status)";
    $rs = Gnud_Db_write_query($sql);
}

/**
 * lấy thông chuyên mục xuất bản ben đối tác của 1 bài viết
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 9-11-2018
 * @param
 *  $p_news_id  ID bài viết
 *  $p_code     Mã đối tác
 * @return array
 */
function be_get_all_category_partners_by_one_news($p_news_id, $p_code)
{
	Gnud_Db_read_close();
	$sql = "call be_get_all_category_partners_by_one_news($p_news_id, '$p_code')";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}

/**
 * Xoa cac chuyen muc khong duoc chon khi cap nhat chuyen muc cho bai viet
 * @param string $p_where : Dieu kien xoa
 * @return null
 */
function be_newscategory_part_ners_delete_by_where($p_where)
{
	$sql = "CALL delete_data('t_newscategory_partners', '$p_where')";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien xuat ban mot bai viet tren chuyen muc đối tác
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 12-11-2018
 * @param:
 *  $p_newscategory_partners_id     ID bảng t_newscategory_partners
 *  $p_status                       Trạng thái 
 *  $p_user_id                      Người cập nhật cuối
 *  $p_time_partners                Thời gian delay đối tác
 *  $p_pending_status               Trạng thái hẹn giờ
 * @return array
 */
function be_update_newscategory_partners_to_published($p_newscategory_partners_id, $p_status, $p_user_id, $p_time_partners = 0, $p_pending_status)
{
	$sql = "call be_update_newscategory_partners_to_published($p_newscategory_partners_id, $p_status, $p_user_id, '$p_time_partners', $p_pending_status)";
	$rs = Gnud_Db_write_query($sql);
}

function be_update_newscategory_partners_to_published_dulich($p_newscategory_partners_id, $p_status, $p_user_id, $p_time_partners = 0, $p_pending_status)
{
	$sql = "call be_update_newscategory_partners_to_published_dulich($p_newscategory_partners_id, $p_status, $p_user_id, '$p_time_partners', $p_pending_status)";
	$rs = Gnud_Db_write_query($sql);
}

/**
 * Thuc hien hen gio xuat ban mot bai viet
 * @param
 *  $p_newscategory_id      ID  newscategory
 *  $p_pending_status       Trạng thái hẹn giờ
 *  $p_PublishedDate        Thời ngày xuất bản bài viết
 *  $p_PublishedDate2       Thời gian xuất bản bài viết
 *  $p_pending_date         Ngày hẹn giờ
 *  $p_user_id              ID user cập nhật cuối
 * @return array
 */
function be_update_news_publication_time_partners($p_newscategory_id, $p_pending_status, $p_PublishedDate, $p_PublishedDate2, $p_pending_date, $p_user_id)
{
	$sql = "call be_update_news_publication_time_partners($p_newscategory_id, $p_pending_status, '$p_PublishedDate', '$p_PublishedDate2', '$p_pending_date', $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}
/* End: 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong */

/* Begin: 26-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong_xem_thong_tin_ghi_chu */
/**
 * Lay danh sach bai viet đẩy sang đối tác
 * @param int 			$p_news_id Tìm theo ID bài viết
		int 			$p_category_id Tìm theo ID chuyên mục xuất bản
		varchar(255)	$p_title		Chuỗi tìm kiếm tiếng Việt không dấu theo tiêu đề bài viết
		varchar(50) 	$p_type		Tìm loại bài
							- Bài thường
							- Bài video (VideoCode!=’’ hoặc Video_code=1)
							- Bài ảnh (Album_trang_anh>0)
							- Bài PR đầu trang (pr_dau_trang=1)
							- Bài PR ưu tiên (pr=1)
		Varchar(50)		$p_edit_date		Thời gian sửa bài, định dạng YYYY-MM-DD
		int				$p_page_number		Hiển thị trang số mấy
		int 			$p_row_per_page		Số lượng bản ghi trên 1 trang
 * @return array
 */
function be_get_all_news_push_to_partners($p_news_id, $p_category_id, $p_category_partner_id, $p_code, $p_approve_status_partners, $p_title, $p_edit_date_start, $p_edit_date_end, $p_type , $p_type_note, $p_page_number, $p_row_per_page)
{
	$sql = "call be_get_all_news_push_to_partners($p_news_id, $p_category_id, $p_category_partner_id, '$p_code', $p_approve_status_partners, '$p_title', '$p_edit_date_start', '$p_edit_date_end', '$p_type' , '$p_type_note', $p_page_number, $p_row_per_page)";
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}
/**
 * Thuc hien xuat ban mot bai viet
 * @param int $p_newscategory_id ID  newscategory
 * @return array
 */
function be_update_news_to_published_partners($p_newscategory_id, $p_status, $p_user_id)
{
	$sql = "call be_update_news_to_published_partners($p_newscategory_id, $p_status, $p_user_id)";
	$rs = Gnud_Db_write_query($sql);
}
/* End: 26-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong_xem_thong_tin_ghi_chu */
/*
* SP cập nhật phân loại sự kiện cho bài viết
* p_content_profile_info_id  : ID phân loại
* p_news_id : ID bài viết
*/
function be_update_content_event_info_news($p_news_id = 0, $p_content_event_info_id = 0) {
    $sql = "call be_update_content_event_info_news($p_news_id, $p_content_event_info_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs;
}
/*
 *
 * SP lấy danh sách bài viết và phân loại nội dung bằng id bài viết
 * p_news_id		ID bài viết
*/
function be_get_content_event_info_news_by_news_id($p_news_id = 0) {
    $sql = "call be_get_content_event_info_news_by_news_id($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/*
 *
 * SP lấy danh sách bài viết và phân loại nội dung bằng id nội dung sự kiện
 * p_content_profile_info_id		ID nội dung profile
*/
function be_get_content_event_info_news_by_info_id($p_content_profile_info_id = 0) {
    $sql = "call be_get_content_event_info_news_by_info_id($p_content_profile_info_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/*
* SP xóa phân loại nội dung bài viết bằng ID bài viết
* p_news_id : ID bài viết
*/
function be_delete_content_event_info_news_by_news_id($p_news_id = 0) {
    $sql = "call be_delete_content_event_info_news_by_news_id($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs;
}
/*
* 
* $p_news_id : ID bài viết
* $p_pr_the_thao : Đánh dấu PR thể thao
* $p_pr_bong_da : Đánh dấu PR bong da
*/
function be_update_pr_extend($p_news_id,$p_pr_link_dac_biet,$p_pr_trong_muc) {
    $sql = "call be_update_pr_extend($p_news_id,$p_pr_link_dac_biet,$p_pr_trong_muc)";
    $rs = Gnud_Db_write_query($sql);
    return $rs;
}
/*
* 
* $p_news_id : ID bài viết
* $p_pr_the_thao : Đánh dấu PR thể thao
* $p_pr_bong_da : Đánh dấu PR bong da
*/
function be_single_pr_extend($p_news_id){
    $sql = "call be_single_pr_extend($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/* Begin: 13-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
/*
 * Hàm lấy các nội dung bổ sung từ bảng t_news_search của bài viết
 ** Params :
 * $p_news_id : ID bai viet
 ** Return : array
 */
function be_get_news_content_for_elastic_search($p_news_id) {
    $sql = "call be_get_news_content_for_elastic_search($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
function be_get_seo_news_by_news_id($p_news_id) {
    $sql = "SELECT pk_seo_chi_tiet_bai_viet FROM t_seo_chi_tiet_bai_viet WHERE pk_news = $p_news_id LIMIT 1";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/*
 * Hàm lấy các thông tin bài PR của bài viết
 ** Params :
 * $p_news_id : ID bai viet
 ** Return : array
 */
function be_get_news_pr_info_for_elastic_search($p_news_id) {
    $sql = "call be_get_news_pr_info_for_elastic_search($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs;
}
/*
 * Hàm lấy danh sách các chuyên mục có bài viết được phân quyền cho người sử dụng
 ** Params :
 * $p_news_id : ID bài viết
 * $p_status : Trạng thái bài viết
 * $p_category_id : ID chuyên mục
 * $p_category_khampha_id : ID chuyên mục khám phá
 * $p_title : Tiêu đề bài
 * $p_user_id : ID người dùng
 * $p_editor_id : Người sửa
 * $p_approved_id : Người duyệt
 * $p_published_id : người xuất bản
 * $p_type : Loại bài
 * $p_has_poll : có poll hay không
 * $p_has_comment : có comment hay không
 * $p_pr_region : vùng miền của bài
 * $p_pr_device : thiết bị bài pr
 * $p_published_date : từ ngày
 * $p_published_to_date : đến ngày
 * $p_event_id : ID sự kiện
 * $p_event_name : tên sự kiện
 * $p_so_hop_dong : Số hợp đồng
 * $p_page_number : Số trang
 * $p_row_per_page : Số item trên trang
 ** Return : array
 */
function be_get_all_user_category_by_news($p_news_id, $p_status, $p_category_id, $p_category_khampha_id, $p_title, $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, $p_type , $p_has_poll, $p_has_comment, $p_pr_region, $p_pr_device, $p_published_date, $p_published_to_date, $p_event_id, $p_event_name, $p_so_hop_dong, $p_page_number, $p_row_per_page)
{
    $sql = "call be_get_all_user_category_by_news($p_news_id, $p_status, $p_category_id, $p_category_khampha_id, '$p_title', $p_user_id, $p_editor_id, $p_approved_id, $p_published_id, '$p_type' , '$p_has_poll', '$p_has_comment', '$p_pr_region', '$p_pr_device', '$p_published_date', '$p_published_to_date', $p_event_id, '$p_event_name', '$p_so_hop_dong', $p_page_number, $p_row_per_page)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
/* End: 13-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
/* Hàm lấy tất cả poll câu trả lời khác
 * 	$p_poll_id				BIGINT poll id
	,$p_thong_ke				INT Có lấy thống kê không
	,$p_page					int số trang
	,$p_number_item_per_page	int  Số dòng
 */
function be_get_all_poll_answers_other(
	$p_poll_id
	,$p_thong_ke
	,$p_page
	,$p_number_item_per_page
){
    $sql = "call be_get_all_poll_answers_other($p_poll_id, $p_thong_ke,$p_page, $p_number_item_per_page)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
/* Hàm lấy tất cả poll câu trả lời khác
 * 	$p_poll_other_answer_id				BIGINT poll id
 */
function be_delete_poll_other_answer(
	$p_poll_other_answer_id
){
    $sql = "call be_delete_poll_other_answer($p_poll_other_answer_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/* Hàm thực hiện cập nhật textlink box ID theo bài viết
 * 	$p_poll_other_answer_id				BIGINT poll id
 */
function be_update_textlink_box_news($p_textlink_box_id,$p_news_id){
    $sql = "call be_update_textlink_box_news($p_textlink_box_id,$p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/* Hàm thực hiện xóa textlink box theo bài viết
 * 	$p_poll_other_answer_id				BIGINT poll id
 */
function be_delete_single_textlink_box_news($p_news_id){
    $sql = "call be_delete_single_textlink_box_news($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/* Hàm thực hiện cập nhật minigame ID theo bài viết
 * 	$p_poll_other_answer_id				BIGINT poll id
 */
function be_update_minigame_news($p_minigame_id,$p_news_id){
    $sql = "call be_update_minigame_news($p_minigame_id,$p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/* Hàm thực hiện xóa minigame theo bài viết
 * 	$p_poll_other_answer_id				BIGINT poll id
 */
function be_delete_single_minigame_news($p_news_id){
    $sql = "call be_delete_single_minigame_news($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Begin: 7-11-2019 TuyenNT bo_sung_link_goc_cho_bai_khai_thac_day_bai */
/**
 * Check xem tin bài có phải từ đối tác đẩy sang không
 * @param $p_news_id: id bai viet
 * @return array
 */
function be_check_tin_bai_doi_tac($p_news_id)
{
    $sql = "call be_check_tin_bai_doi_tac($p_news_id)";
    $rs = Gnud_Db_read_query_one($sql);
    return $rs;
}
/* End: 7-11-2019 TuyenNT bo_sung_link_goc_cho_bai_khai_thac_day_bai */

function be_get_all_published_news_layout_no_show_banner($p_news_id=0, $p_category_id=0, $p_category_id_layout='', $p_editor_id=0, $p_title_ascii='', $p_published_date='', $p_published_to_date= '', $p_edit_date='', $p_edit_to_date='', $p_page_number=1, $p_row_per_page=30, $p_is_tk=0, $p_column_sort='') 
{
    if ($p_published_date != '') {
        $p_published_date = _sql_format_date($p_published_date);
    }
    if ($p_published_to_date != '') {
        $p_published_to_date = _sql_format_date($p_published_to_date);
        $p_published_to_date.=' 23:59:59';
    }
    
    if ($p_edit_date != '') {
        $p_edit_date = _sql_format_date($p_edit_date);
    }
    if ($p_edit_to_date != '') {
        $p_edit_to_date = _sql_format_date($p_edit_to_date);
        $p_edit_to_date.=' 23:59:59';
    }
    $v_column_sort = '';
    if($p_column_sort != ''){
        if($p_column_sort == 'c_PublishedDate2_desc'){
            $v_column_sort = 'nc.PublishedDate2 DESC,';
        }elseif($p_column_sort == 'c_PublishedDate2_asc'){
            $v_column_sort = 'nc.PublishedDate2 ASC,';
        }elseif($p_column_sort == 'c_ngay_sua_asc'){
            $v_column_sort = 'cmbn.c_ngay_sua ASC,';
        }
    }
    
	$sql = "CALL be_get_all_published_news_layout_no_show_banner($p_news_id, $p_category_id, '$p_category_id_layout', $p_editor_id, '$p_title_ascii', '$p_published_date', '$p_published_to_date', '$p_edit_date', '$p_edit_to_date', $p_page_number, $p_row_per_page, $p_is_tk, '$v_column_sort')";
    $rs = Gnud_Db_read_query($sql);
	return $rs; 
}
//Begin Anhtt 02/11/2020 them_thong_ke_loai_bai
/**
* Trả lại array chứa danh sách loại bai erp
* @return array
*/ 
function be_danh_sach_loai_bai_erp()
{
	$v_arr_loai_bai = array();
	$v_arr_loai_bai[] = array('c_code'=>'TAT_CA', 'c_name'=>'Tất cả');
	$v_arr_loai_bai[] = array('c_code'=>'BAI_BEN_LE', 'c_name'=>'Bài bên lề');
	$v_arr_loai_bai[] = array('c_code'=>'BAI_HIGHLIGHT', 'c_name'=>'Bài hightlight');
	return $v_arr_loai_bai;
}

//End Anhtt 02/11/2020 them_thong_ke_loai_bai

/* Begin: 14-02-2019 TuyenNT hoi_dap_bo_sung_chuc_nang_chen_cau_hoi_khi_cap_nhat_bai_viet */
/* Hàm thực hiện xóa câu hỏi theo bài viết
 * 	$p_news_id      ID bài viết
 */
function be_hoi_dap_xoa_hoidap_bai_viet($p_news_id){
    $sql = "call be_hoi_dap_xoa_hoidap_bai_viet($p_news_id)";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}

/* Hàm thực hiện cập nhật câu hỏi ID theo bài viết
 * author: TuyenNT
 * 	$p_pk_hoi_dap_bai_viet      ID câu hỏi đáp bài viết
 *  $p_fk_hoi_dap_id            ID câu hỏi
 *  $p_news_id                  ID bài viết
 *  $p_fk_cate_news_id          ID chuyên mục chính
 *  $p_news_title_ascii         Tiêu đề bài viết không dấu
 */
function be_hoi_dap_cap_nhat_cau_hoi_bai_viet($p_pk_hoi_dap_bai_viet,$p_fk_hoi_dap_id,$p_news_id,$p_fk_cate_news_id,$p_news_title_ascii){
    $sql = "call be_hoi_dap_cap_nhat_cau_hoi_bai_viet($p_pk_hoi_dap_bai_viet,$p_fk_hoi_dap_id,$p_news_id,$p_fk_cate_news_id,'$p_news_title_ascii')";
    $rs = Gnud_Db_write_query($sql);
    return $rs[0];
}
/* End: 14-02-2019 TuyenNT hoi_dap_bo_sung_chuc_nang_chen_cau_hoi_khi_cap_nhat_bai_viet */

/* Begin: 24-02-2020 TuyenNT hoi_dap_xu_ly_frontend_dang_bai_hoi_dap */
/*
 * Hàm gọi key html box hỏi đáp
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 17-02-2020
 * @return: String
 *   */
function be_get_key_html_box_hoidap($p_hoidap_id){
    $v_html = '';
    if(intval($p_hoidap_id) > 0){
        $v_html = Gnud_Db_read_get_key('hoidap_key_html_id_'.intval($p_hoidap_id), _CACHE_TABLE);
    }
    return $v_html;
}
/* End: 24-02-2020 TuyenNT hoi_dap_xu_ly_frontend_dang_bai_hoi_dap */ 

/*
 * hàm thực hiện cập nhật bài viết vào bảng news crawl
*/ 
function be_update_news_crawl($p_news_id){
	$v_sql = "CALL be_update_news_crawl($p_news_id)";
	$rs = Gnud_Db_write_query($v_sql);
}

/**
 * 
 * @param type $p_pk_news
 * @param type $p_thao_tac
 * @param type $p_thoi_gian_thao_tac
 * @param type $p_user_name
 * @return type
 */
function be_update_thao_tac_ha_xb($p_pk_news, $p_thao_tac, $p_thoi_gian_thao_tac, $p_user_name)
{
    $v_thoi_gian_thao_tac = date('Y-m-d H:i:s', $p_thoi_gian_thao_tac);
    $sql = "CALL be_update_thao_tac_ha_xb($p_pk_news, '$p_thao_tac', '$v_thoi_gian_thao_tac', '$p_user_name')";
    $rs = Gnud_Db_write_query($sql);
    
    return $rs;
}
/*
 * Hàm thực hiện cập nhật update bài pr đẩy sang 24hmoney
 * param $p_news_id ID bài viết
 * param $p_xuat_ban_tu_ngay Xuất bản từ ngày
 * param $p_xuat_ban_den_ngay Xuất bản đến ngày
 */
function be_update_news_pr_24hmoney(
    $p_news_pr_24hmoney,    
    $p_news_id,
    $p_pr_position,
	$p_xuat_ban_tu_ngay,	
	$p_xuat_ban_den_ngay,
	$p_partner_code = '24H_MONEY'
){
    $sql = "CALL be_update_news_pr_24hmoney($p_news_pr_24hmoney,$p_news_id,'$p_pr_position', '$p_xuat_ban_tu_ngay', '$p_xuat_ban_den_ngay','$p_partner_code')";
    $rs = Gnud_Db_write_query($sql);
}
/*
 * Hàm thực hiện xóa bài bài pr đẩy sang 24hmoney
 * param $p_news_id ID bài viết
 */
function be_delete_news_pr_24hmoney(
    $p_news_id,
    $p_partner_code = '24H_MONEY'
){
    $sql = "CALL be_delete_news_pr_24hmoney($p_news_id,'$p_partner_code')";
    $rs = Gnud_Db_write_query($sql);
}

/*
 * Hàm thực hiện lấy thông tin chi tiết bài viết đẩy sang 24hmoney
 * param $p_news_id ID bài viết
 */
function be_get_single_news_pr_24h_money(
    $p_news_id
    ,$p_partner_code = '24H_MONEY'
){
    $sql = "CALL be_get_single_news_pr_24h_money($p_news_id,'$p_partner_code')";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
/*
 * Hàm thực hiện lấy thông tin chi tiết bài viết đẩy sang 24hmoney
 * param $p_news_id ID bài viết
 */
function be_get_cau_hinh_bai_pr_theo_id_bai_viet(
    $p_news_id
){
    $sql = "CALL be_get_cau_hinh_bai_pr_theo_id_bai_viet($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/*
 * Hàm thực hiện cập nhật trạng thái đẩy sang 24hmoney
 */
function be_update_status_news_pr_24hmoney(
	$p_news_pr_24hmoney_id
){
    $sql = "CALL be_update_status_news_pr_24hmoney($p_news_pr_24hmoney_id)";
    $rs = Gnud_Db_write_query($sql);
}
/*
 * Hàm thực hiện lấy thông tin chi tiết bài pr 24hmoney
 */
function be_get_single_news_pr_24h_money_by_id(
	$p_news_pr_24hmoney_id 
){
    $sql = "CALL be_get_single_news_pr_24h_money_by_id($p_news_pr_24hmoney_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/*
 * Hàm thực hiện lấy thông tin chi tiết bài pr 24hmoney
 */
function be_delete_news_pr_24hmoney_by_id(
	$p_news_pr_24hmoney_id
){
    $sql = "CALL be_delete_news_pr_24hmoney_by_id($p_news_pr_24hmoney_id)";
    $rs = Gnud_Db_write_query($sql);
}
//22-02-2021 begin DanNC day bai sang du lich
function be_get_all_category_dulich_by_one_news($p_news_id)
{
	Gnud_Db_read_close();
	$sql = "call be_get_all_category_dulich_by_one_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs;
}
//22-02-2021 end DanNC day bai sang du lich
/*
 * Hàm thực hiện cập nhật tài liệu bài viết
 */
function be_update_tai_lieu_bai_viet($p_tai_lieu_id,$p_news_id,$p_name,$p_type_news = 1){
    $sql = "call be_update_tai_lieu_bai_viet($p_tai_lieu_id,$p_news_id,'$p_name',$p_type_news)";
    $rs = Gnud_Db_write_query($sql);
}
/*
 * Hàm thực hiện lấy chi tiết tài liệu theo id bài viết
 */
function be_chi_tiet_tai_lieu_by_news_id($p_news_id,$p_type_news = 1){
    $p_news_id = intval($p_news_id);
    $rs =array();
    if($p_news_id > 0){
        $sql = "call be_chi_tiet_tai_lieu_by_news_id($p_news_id,$p_type_news)";
        $rs = Gnud_Db_read_query($sql);	
        return $rs[0];
    }
	return $rs;
}
function be_create_trigger_bai_pr_dfp_theo_news_id(
	$p_news_id
){
    $sql = "CALL be_create_trigger_bai_pr_dfp_theo_news_id(
                $p_news_id
            )";
    $v_result = Gnud_Db_write_query($sql);
    return $v_result;

}
//10-04-2021 DanNC begin SP lưu hãng xe loại xe
function be_update_news_model_car($p_car_id, $p_news_id, $p_hang_xe, $p_loai_xe) {
    Gnud_Db_read_close();
    $sql = "CALL be_update_news_model_car($p_car_id, $p_news_id, $p_hang_xe, '$p_loai_xe');";
    $rs = Gnud_Db_write_query($sql);
    return $rs;
}
//10-04-2021 DanNC end SP lưu hãng xe loại xe

function be_get_id_car_by_news_id($p_news_id) {
    $sql = "CALL be_get_id_car_by_news_id($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
	return $rs;
}

function be_delete_car_by_id($p_id) {
    $sql = "call be_delete_car_by_id($p_id)";
	$rs = Gnud_Db_write_query($sql);
}

/**
* Lấy lay danh sach log auto save album
* @param 
 *  $p_user_id     id user dang nhap
 *  $page           so trang
 *  $p_number       so items hien thi tren trang
* @return array
*/
function be_danh_sach_log_auto_save_album($p_user_id,$page,$p_number){
    $sql = "call be_danh_sach_log_auto_save_album('$p_user_id','$page','$p_number')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/**
* Lấy chi tiet 1 auto log save news
* @param 
 *  $p_log_id     id 1 log auto save news
* @return array
*/
function be_chi_tiet_1_log_auto_save_album($p_log_id){
    $sql = "call be_chi_tiet_1_log_auto_save_album('$p_log_id')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}

/**
* Lấy chi tiet 1 auto log save album
* @param 
 *  $p_log_id     id 1 log auto save album
* @return array
*/
function be_cap_nhat_1_log_auto_save_album($p_log_id,$p_edit_user,$p_album_name,$p_play_time,$p_note,$p_total_images, $p_type){
    $sql = "call be_cap_nhat_1_log_auto_save_album('$p_log_id','$p_edit_user','$p_album_name','$p_play_time','$p_note','$p_total_images', '$p_type')";
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}
/**
* xoa 1 auto log save album
* @param 
 *  $p_log_id     id 1 log auto save album
* @return array
*/
function be_xoa_1_log_auto_save_album($p_log_id){
    $sql = "call be_xoa_1_log_auto_save_album('$p_log_id')";
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}
/**
* Lấy chi tiet 1 auto log save album
* @param 
 *  $p_log_id     id 1 log auto save album
* @return array
*/
function be_cap_nhat_1_log_auto_save_album_images($p_id,$p_fk_save_id,$p_Bigimg,$p_Thumbimg,$p_Description,$p_nOrder){
    $sql = "call be_cap_nhat_1_log_auto_save_album_images('$p_id','$p_fk_save_id','$p_Bigimg','$p_Thumbimg','$p_Description','$p_nOrder')";
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}

function be_danh_sach_log_auto_save_album_image($p_log_id){
    $sql = "call be_danh_sach_log_auto_save_album_image('$p_log_id')";
	$rs = Gnud_Db_read_query($sql); 
	return $rs;
}
/*
 * Hàm thực hiện update img gif video
 */
function be_news_update_img_gif_video(
	$p_news_id
    ,$p_img_gif
){
    $sql = "call be_news_update_img_gif_video('$p_news_id','$p_img_gif')";
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
} 

/*
 * Hàm thuc hien lay danh sach bai viet
 */
function be_get_all_news(
	$p_news_id
	,$p_category_id
	,$p_user_id
    ,$p_status_list
    ,$p_news_name
){
    $sql = "call be_get_all_news($p_news_id,$p_category_id,$p_user_id,'$p_status_list','$p_news_name')";
    //echo $sql;die;
	$rs = Gnud_Db_read_query($sql); 
    return $rs;
}

function be_get_single_news(
	$p_news_id
){
    $sql = "call be_get_single_news($p_news_id)";
	$rs = Gnud_Db_read_query($sql); 
    return $rs[0];
}
/**
 * Ham thuc hien xoa vinh vien 1 chuyen muc
 * @param int $p_cat_id Id chuyen muc
 */
function be_delete_news_single($p_cat_id) {
    $sql = "call be_delete_news_single($p_cat_id)";
	$rs = Gnud_Db_read_query($sql);
	return $rs[0];
}

function be_update_news_langue(
	$p_news_id
    ,$p_title
    ,$p_summary
    ,$p_short_summary
    ,$p_body
    ,$p_langue
){
    $sql = "call be_update_news_langue(
            '$p_news_id'
            ,'$p_title'
            ,'$p_summary'
            ,'$p_short_summary'
            ,'$p_body'
            ,'$p_langue'
        )";
    //echo $sql;die;
	$rs = Gnud_Db_write_query($sql); 
	return $rs;
}