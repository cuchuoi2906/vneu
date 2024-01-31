<?php
/**
 * Hàm đầu vào xử lý code video body
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_xu_ly_video_body($p_code_video,$p_row_news, $p_row_cat, $p_param_extension = array()) {
    //B1 - B8: Xử lý code video thông thường (video insite)
    $p_code_video = _24h_player_xu_ly_code_video($p_code_video, $p_row_news, $p_row_cat, $p_param_extension);
	/* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
	// B8.1: Thực hiện  gán vị trí video banner sponsor_video_article cho video đầu tiên (ko áp dụng cho bài video đối tác)
    //$p_code_video = _vd_thiet_lap_banner_sponsor_video($p_code_video,$p_row_news, $p_row_cat,$p_param_extension);
    /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
	//Xử lý load sau với video VTV
	if (strpos($p_code_video, 'vtvWrite') !== false) {
		//Lấy tất cả các mã scritp video VTV
		preg_match_all('/<script\s*type="text\/javascript">\s*vtvWrite\((.*)\).*<\/script>/msU', $p_code_video, $v_arr_video_vtv);
		if (check_array($v_arr_video_vtv[1])) {
			foreach ($v_arr_video_vtv[1] as $v_key_video_vtv=>$v_video_vtv) {
				//Script gọi video gốc
				$v_html_video_vtv_goc = $v_arr_video_vtv[0][$v_key_video_vtv];
                if(strpos($p_code_video, "vtvWrite('") !== false){
                    $v_html_video_vtv_goc = str_replace("'",'"', $v_html_video_vtv_goc);
                }
				//Thay thế script gốc thêm addEventListener
				$v_html_video_vtv_goc = preg_replace('/<script.*>(.*)<\/script>/msU', '<script type="text/javascript">window.addEventListener("load", function(){$1})</script>', $v_html_video_vtv_goc);
				//Tạo html script gọi video VTV mới
				$v_html_div_video_vtv = '<div class="video_vtv_container" id="video_vtv_container_'.$v_key_video_vtv.'">'.str_replace('vtvWrite("','vtvWrite("video_vtv_container_'.$v_key_video_vtv.'","',$v_html_video_vtv_goc).'</div>';
				//Thay thế vào script gốc trong Body
				$p_code_video = str_replace($v_arr_video_vtv[0][$v_key_video_vtv], $v_html_div_video_vtv, $p_code_video);
			}
		}
	}
    //Xử lý load sau đối với video đối tác
    if (strpos($p_code_video, 'videoDoiTacWrite') !== false) {
        //Lấy tất cả các mã scritp video VTV
        preg_match_all('/<script\s*type="text\/javascript">\s*videoDoiTacWrite\((.*)\).*<\/script>/msU', $p_code_video, $v_arr_video_doi_tac);
        if (check_array($v_arr_video_doi_tac[1])) {
            foreach ($v_arr_video_doi_tac[1] as $v_key_video_doi_tac=>$v_video_doi_tac) {
                //Script gọi video gốc
                $v_html_video_doi_tac_goc = $v_arr_video_doi_tac[0][$v_key_video_doi_tac];
                if(strpos($p_code_video, "videoDoiTacWrite('") !== false){
                    $v_html_video_doi_tac_goc = str_replace("'",'"', $v_html_video_doi_tac_goc);
                }
                //Thay thế script gốc thêm addEventListener
                $v_html_video_doi_tac_goc = preg_replace('/<script.*>(.*)<\/script>/msU', '<script type="text/javascript">window.addEventListener("load", function(){$1})</script>', $v_html_video_doi_tac_goc);
                //Tạo html script gọi video mới
                $v_html_div_video_doi_tac = '<div id="video_doi_tac_container_'.$v_key_video_doi_tac.'">'.str_replace('videoDoiTacWrite("','videoDoiTacWrite("video_doi_tac_container_'.$v_key_video_doi_tac.'","',$v_html_video_doi_tac_goc).'</div>';
                //Thay thế vào script gốc trong Body
                $p_code_video = str_replace($v_arr_video_doi_tac[0][$v_key_video_doi_tac], $v_html_div_video_doi_tac, $p_code_video);
            }
        }
    }
    return $p_code_video;
}
/**
 * Hàm đầu vào xử lý code video thông thường (code script video không phải outside)
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_xu_ly_code_video($p_code_video,$p_row_news, $p_row_cat, $p_param_extension) {
    //B1: Thiết lập các giá trị cấu hình (tùy thuộc vào các trường hợp gọi mà bổ xung thêm các tham số ban đầu cần thiết)
    $v_arr_config_video = _vd_thiet_lap_thong_so_cau_hinh_video($p_row_news, $p_row_cat,$p_param_extension);
    /* Begin: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    //$p_code_video = _24h_player_loai_bo_video_khong_duoc_hien_thi_sau_n_tieng($p_code_video,$v_arr_config_video);
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    //B2: Loại bỏ video không được phép hiển thị
    //$p_code_video = _24h_player_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);
    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    //$v_arr_data_ads = _24h_player_thiet_lap_thong_so_quang_cao($p_code_video,$p_row_news, $p_row_cat, $v_arr_config_video);
    //B4: Thiết lập dữ liệu gán tracking,ga
    //$v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    //$v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);

    //B5: Thay thế dạng script write tùy theo cấu hình
    $p_code_video = _24h_player_chuyen_doi_giua_cac_ma_script_video($p_code_video,$v_arr_config_video);
	
    //B6: Thiết lập dữ liệu highlight video
    //$v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news);
    //B7: Gen mã html player
	preg_match_all('/<script.*>.*.mp4.*<\/script>/msU', $p_code_video, $v_arr_script_video);
	if (check_array($v_arr_script_video[0])) {
		$v_count_video_body = count($v_arr_script_video[0]);
		$v_arr_config_video['v_count_video_body'] = $v_count_video_body;
	}
    $v_param_extension = array(
        'v_arr_config_video'=> $v_arr_config_video,
        'v_arr_data_ads'=>$v_arr_data_ads,
        'v_arr_data_tracking'=>$v_arr_data_tracking,
        'v_arr_data_highlight_video'=>[],
        'v_row_news'=>$p_row_news,
        'v_row_cat'=>$p_row_cat,
    );
    $v_str_html_player = _24h_player_tao_code_html_player($p_code_video,$v_param_extension);
    //B8: Thay thế các lời gọi player bằng script thành HTML
    $p_code_video = _24h_player_thay_the_code_html_cho_cac_loi_goi_video($p_code_video,$v_str_html_player,$v_param_extension);
    return $p_code_video;
}
/**
 * Hàm loại bỏ video không được hiển thị
 * @param array $p_code_video : Code chứa video
 * @param array $v_arr_config_video : Các thông số cấu hình videos
 * @return array
 */
function _24h_player_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video) {
    $v_region_value = $v_arr_config_video['v_region_value'];
    // nếu $p_code_video trống (hoặc null) thì trả về luôn
    if (empty($p_code_video) || strtolower($v_region_value) != 'us') {
		return $p_code_video;
	}
    // neu la video có tên được cấu hình thì loại bỏ trên us
    $p_code_video = _24h_player_remove_cac_player_co_ten_video_duoc_cau_hinh($p_code_video, $v_arr_config_video);
    return $p_code_video;
}
/*
 *Ham loại bỏ các video có tên mã được cấu hình
 *param : $p_tuong_thuat  nội dung bài tường thuật
 *return: $p_tuong_thuatarray_unique_key
*/
function _24h_player_remove_cac_player_co_ten_video_duoc_cau_hinh($p_content, $p_arr_config_video = array()) {
    $v_image_player_us_config = _get_module_config('cau_hinh_dung_chung', 'v_image_player_us');
	$v_image_player_us = '<img class="width-100 blk" src="'.$v_image_player_us_config.'" alt="">';
	/* begin 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
	$v_event = $p_arr_config_video['v_event'];
	$v_event_no_us = strtolower(trim(get_gia_tri_danh_muc_dung_chung('VIDEO_BAN_QUYEN','SU_KIEN_KHONG_HIEN_THI_US')));
	$arr_event_inter = array();
	if($v_event != '' && $v_event_no_us != '') {
		$arr_event = explode(',', $v_event);
		$arr_event_no_us = explode(',', $v_event_no_us);
		$arr_event_inter = array_intersect($arr_event, $arr_event_no_us);
	}
    $replaceVideo = _check_is_google_bot() ? false : true; // Neu là google bot thì không replace video
	if(check_array($arr_event_inter)){
		$v_arr_loai_video_xoa_tren_us = _get_module_config('cau_hinh_dung_chung','v_arr_loai_video_xoa_tren_us');
		$v_arr_loai_video = array();
		// Lặp để gán thêm các mã video trong tên video
		for($i=0;$i<count($v_arr_loai_video_xoa_tren_us);$i++){
			$v_arr_loai_video[] = str_replace('(<!--loai_video-->)(.*)', '', $v_arr_loai_video_xoa_tren_us[$i]);
		}
		$v_count_loai_video = count($v_arr_loai_video);
		// thay thế video bằng ảnh cảnh báo
		for($i = 0;$i<$v_count_loai_video;$i++){
			preg_match($v_arr_loai_video[$i], $p_content, $arr_file_video);
			if (check_array($arr_file_video) && $replaceVideo) {
				$v_file_video = $arr_file_video[0];
				$p_content = str_replace($v_file_video, $v_image_player_us, $p_content);
			}
		}
	} else {
		$v_arr_loai_video = _vd_get_arr_loai_video_xoa_tren_us();
		$v_count_loai_video = count($v_arr_loai_video);
		for ($j=1; $j>0 && $j<=100; $j++) {
			if(!_vd_config_video_code_has_exits_in_string($p_content)){
				break;
			}
			// lặp tất cả loại video
			for($i = 0;$i<$v_count_loai_video;$i++){
				preg_match($v_arr_loai_video[$i], $p_content, $arr_file_video);
				if (check_array($arr_file_video) && $replaceVideo) {
					$v_file_video = $arr_file_video[0];
					$p_content = str_replace($v_file_video, $v_image_player_us, $p_content);
				}
			}
		}
	}
	/* end 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
	return $p_content;
}
/**
 * Hàm thực hiện thiết lập các thông số quảng cáo theo từng loại video
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_thiet_lap_thong_so_quang_cao($p_code_video,$p_row_news, $p_row_cat, $p_param_extension) {

    $v_is_bai_pr = $p_param_extension['v_is_bai_pr'];
    $v_is_show_banner_video = $p_param_extension['v_is_show_banner_video'];
    $v_url_news =  $p_param_extension['v_url_news'];
    $v_region_value = strtolower($p_param_extension['v_region_value']);
    $v_ma_loai_giai_dau = '';

    if(empty($v_url_news)){
        $v_url_news = get_url_origin_of_news($p_row_news, $p_row_cat);
    }
    // khởi tạo biến mặc định cho quảng cáo
    $v_logo     = '';
    $v_before   = '';
    $v_overlay  = '';
    $v_preroll  = '';
    $v_end      = '';
    $v_ga_code  = '';
    $v_ma_nguon_source = SOURCE_VIDEO_24H;

    $v_is_quang_cao_write = _vd_check_is_quang_cao_write($p_code_video);
    $v_type_quang_cao = intval(_vd_check_is_quang_cao_write($p_code_video,true));
    // Kiểm tra xem bài viết có thuộc layout preroll độc quyền không. Nếu thuộc thì sẽ lấy theo layout
    $v_cat_id_banner = lay_chuyen_muc_layout_quang_cao($p_row_news,$v_is_quang_cao_write);
    // phải là bài viết ko set layout ko hiện thị quảng cáo
    if(!$v_cat_id_banner && $v_is_quang_cao_write && $v_is_show_banner_video == 1){
        $v_ma_loai_giai_dau = _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_code_video);
        // Lấy file vast quảng cáo theo từng chiến dịch
        $v_preroll = _24h_player_lay_file_vast_preroll_theo_chien_dich($v_ma_loai_giai_dau,$v_preroll);
        $v_overlay  = _24h_player_lay_file_vast_overlay_theo_chien_dich($v_ma_loai_giai_dau,$v_overlay);
        $v_end      = _24h_player_lay_file_vast_postroll_theo_chien_dich($v_ma_loai_giai_dau,$v_end);
    }else{
        /* End: 17-06-2020 TuyenNT dieu_chinh_co_che_hien_thi_quang_cao_tren_video_noi_dung */
		//end ducnq - xu_ly_lay_quang_cao_banner_layout_preroll
        $xmlAds = Gnud_Db_read_get_key('video_ads_201403_'.$v_cat_id_banner.'_'.$_SERVER['SERVER_REGION'], _CACHE_TABLE_QUANG_CAO);
        // bài pr và bài set chuyên mục layout không hiện thị quảng cáo video
        if(intval($v_is_bai_pr) == 1 || intval($v_is_show_banner_video) == 0){
            if (_24h_player_xu_ly_bai_viet_ko_chay_quang_cao_video()){
                $xmlAds = '';
            }
        }
        if ($xmlAds != '') {
        	// lấy thông số quảng cáo từ chuỗi xml ads
			$v_arr_data_xmlAds = _24h_player_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds);
			$v_logo     = $v_arr_data_xmlAds['v_logo'];
			$v_before   = $v_arr_data_xmlAds['v_before'];
			$v_overlay  = $v_arr_data_xmlAds['v_overlay'];
			$v_preroll  = $v_arr_data_xmlAds['v_preroll'];
			$v_end      = $v_arr_data_xmlAds['v_end'];
        }
    }
    // xử lý lấy thông tin các tham số quảng cáo
	if ($xmlAds != '') {
		// thực hiện tối ưu các tham số quảng cáo
		kiem_tra_quang_cao_ambient($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);
	}

    # XLCYCMHENG-40731 - player - preroll - vast - add inventory_scope
    $v_preroll = _24h_player_ads_add_plus_params($v_preroll, ['row_news' => $p_row_news], 'pc');

	_24h_player_toi_uu_thong_so_quang_cao($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);

    $v_arr_data_ads = array(
        'v_type_quang_cao'=>$v_type_quang_cao,
        'v_ma_loai_giai_dau'=>$v_ma_loai_giai_dau,
        'v_ads_preroll'=>$v_preroll,
        'v_ads_overlay'=>$v_overlay,
        'v_ads_postroll'=>$v_end,
        'v_ads_ga_code'=>$v_ga_code,
        'v_ma_nguon_source'=>$v_ma_nguon_source,
    );

    return $v_arr_data_ads;
}
/*
 * ham xu_ly_bai_viet_ko_chay_quang_cao_video
 *  @param: $p_new_id id bài viết
 * return boolean
 ***/
// begin 8/11/2016 TuyenNT xu_ly_neu_la_bai_pr_thi_se_bo_qua_loai_hinh_quang_cao_video
function _24h_player_xu_ly_bai_viet_ko_chay_quang_cao_video(){
    $v_ma_cau_hinh_on_off_quang_cao_video_bai_pc = _get_module_config('cau_hinh_dung_chung', 'v_ma_cau_hinh_on_off_quang_cao_video_bai_pc');
    $v_arr_gia_tri = fe_danh_sach_gia_tri_theo_ma_danh_muc($v_ma_cau_hinh_on_off_quang_cao_video_bai_pc);
    $v_arr_gia_tri = _array_convert_index_to_key($v_arr_gia_tri, 'c_ma_gia_tri');
    $v_gia_tri =  intval($v_arr_gia_tri[$v_ma_cau_hinh_on_off_quang_cao_video_bai_pc]['c_ten']);
    if($v_gia_tri == 2){
        return true;
    }
    return false;
}
/**
 * Hàm thực hiện thiết lập các thông số tracking theo từng loại video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return array
 */
function _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $p_param_extension) {

    // thiêt lập các thông số cho viêc lấy dữ liệu
    $v_url_news = trim($p_param_extension['v_url_news']);
    $v_no_is_in_iframe = $p_param_extension['v_no_is_in_iframe'];
    $v_ma_nguon_source  =  $p_param_extension['v_ma_nguon_source'];
    if(empty($v_url_news)){
        $v_url_news = get_url_origin_of_news($p_row_news, $p_row_cat);
        $p_param_extension['v_url_news']= $v_url_news;
    }
    // tracking gtm
    $v_str_tracking_gtm_play_video = '';
    $v_str_tracking_gtm_complete_video = '';
    $v_is_use_gtm_tracking = false;
    $v_on_off_script_tag_manager = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN','ON_OFF_GOOGLE_TAG_MANAGER');
    if ($v_on_off_script_tag_manager === 'TRUE') {
        $v_is_use_gtm_tracking = true;
        if (strpos($v_url_news,'http:') === false && strpos($v_url_news,'https:') === false) {
            $v_url_news_for_tracking_gtm = $v_url_news;
        } else {
            $v_url_news_for_tracking_gtm = preg_replace('/(http|https):\/\/.*\//','/',$v_url_news);
        }
        $v_video_title = str_replace('"', '“', fw24h_restore_bad_char($p_row_news['Title']));
        $v_is_in_iframe =  ($v_no_is_in_iframe != '' && $v_no_is_in_iframe != NULL)?true:false;
        $v_str_tracking_gtm_play_video = _24h_player_create_string_tracking_gtm_video('videoStart', $v_url_news_for_tracking_gtm, $v_video_title, $v_is_in_iframe, '', '', $v_ma_nguon_source);
        $v_str_tracking_gtm_complete_video = _24h_player_create_string_tracking_gtm_video('videoComplete', $v_url_news_for_tracking_gtm, $v_video_title, $v_is_in_iframe, '', '', $v_ma_nguon_source);
    }
     $v_arr_data_tracking_gtm = array(
        'v_is_use_gtm_tracking'=>$v_is_use_gtm_tracking,
        'v_str_tracking_gtm_play_video'=>$v_str_tracking_gtm_play_video,
        'v_str_tracking_gtm_complete_video'=>$v_str_tracking_gtm_complete_video,
    );

    // thiết lập thông số GA
    $v_param_extension_ga   = array_merge($p_param_extension,$v_arr_data_tracking_gtm);
    $v_arr_data_tracking_ga = _24h_player_lay_code_ga_cho_video($p_row_news, $p_row_cat, $v_param_extension_ga);
    // gộp tham số tracking gtm va ga
    $v_arr_data_tracking    = array_merge($v_arr_data_tracking_gtm,$v_arr_data_tracking_ga);

    return $v_arr_data_tracking;
}
/*
 //Begin 14-07-2017 : Thangnb tracking_google_tag_manager_video
 * Hàm tạo ra chuỗi tracking google tag manager cho video
 * params:
 	$p_event : Tên sự kiện cần bắt
	$p_video_url : Url của bài viết chứa video
	$p_video_title : Title của bài viết chứa video
	$p_video_duration : Thời lượng của video
	$p_video_type : để giá trị là Standard
	$p_video_campain : hiện tại để rỗng. Sau này thay = đối tác cung cấp video
	$p_video_percentage : Nhận 1 trong các giá trị 25%, 50%, 75%
	$p_video_watch_duration : Số giây đã xem video (chỉ ghi nhận ở các mốc 25%, 50%, 75%
 * return : String
*/
function _24h_player_create_string_tracking_gtm_video ($p_event = '', $p_video_url = '', $p_video_title = '', $p_is_in_iframe = false, $p_video_duration = '', $p_video_type = '', $p_video_campain = '', $p_video_percentage = '', $p_video_watch_duration = '') {
	$p_video_type = 'Standard';
	if ($p_video_campain == '') {
		$p_video_campain = '24H';
	}
    if(!empty($p_video_title)){
		$p_video_title               = fw24h_restore_bad_char($p_video_title);
		$p_video_title               = str_replace(array('"',"'"),'“', $p_video_title);
	}
	if ($p_is_in_iframe == true) {
		$v_str = "tracking_gtm_video#:|:#$p_event#:|:#$p_video_url#:|:#$p_video_title#:|:#$p_video_duration#:|:#$p_video_type#:|:#$p_video_campain";
		if ($p_video_percentage != '') {
			$v_str .= '#:|:#$p_video_percentage';
		}
		if ($p_video_watch_duration != '') {
			$v_str .= '#:|:#$p_video_watch_duration';
		}
		$v_complete_str = 'window.parent.postMessage("'.$v_str.'","*");';
	} else {
		$v_str = '"event":"'.$p_event.'", "videoUrl":"'.$p_video_url.'", "videoTitle":"'.$p_video_title.'", "videoDuration":"'.$p_video_duration.'", "videoType":"'.$p_video_type.'", "videoCampain":"'.$p_video_campain.'"';
		if ($p_video_percentage != '') {
			$v_str .= ', "videoPercentage":"'.$p_video_percentage.'"';
		}
		if ($p_video_watch_duration != '') {
			$v_str .= ', "videoWatchDuration":"'.$p_video_watch_duration.'"';
		}
		$v_complete_str = 'console.log("Tracking: '.$p_event.'");dataLayer.push({'.$v_str.'});';
	}
	return $v_complete_str;
}
/**
 * Hàm thực hiện lấy tham số gán GA cho video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 */
function _24h_player_lay_code_ga_cho_video($p_row_news, $p_row_cat, $p_param_extension){

    // Khởi tạo các biến
	$v_event_load_trang = '';
	$v_event_quang_cao 	= '';
	$v_url_html_ga_play_video = LINK_GA_VIDEO_MAC_DINH;

    $v_id_news = intval($p_row_news['ID']);
    $v_title_news = fw24h_replace_bad_char($p_row_news['Title']);
    $v_id_cat = intval($p_row_cat['ID']);
    $v_ma_nguon_source  = $p_param_extension['v_ma_nguon_source'];// có thể bỏ vì ko dùng ANTS nữa
    // nếu là quảng cáo write thì giá trị lớn hơn 0
    $v_type_quang_cao   = intval($p_param_extension['v_type_quang_cao']);
    $v_ma_loai_giai_dau = $p_param_extension['v_ma_loai_giai_dau'];
    $v_url_news         = $p_param_extension['v_url_news'];
    $v_is_use_gtm_tracking = $p_param_extension['v_is_use_gtm_tracking'];
    $v_ga_file      = $p_param_extension['v_ga_file'];
    $v_ga_file      = (!empty($v_ga_file))?$v_ga_file:LINK_GA_VIDEO_MAC_DINH;
    $v_no_is_in_iframe = $p_param_extension['v_no_is_in_iframe'];
    $v_is_in_iframe =  ($v_no_is_in_iframe != '' && $v_no_is_in_iframe != NULL) ? true : false;

    // lấy thông số tracking ga theo loại
    $v_tracking_tong_video = _vd_lay_thong_so_tracking_theo_loai($p_row_news,$p_row_cat,$p_param_extension);
    if($_GET['test'] ==1){
		//var_dump($v_tracking_tong_video);die;
	}
    /* begin 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    // kiểm tra nếu bài viết được gắn loại content mới thực hiện lấy mã content
    $v_event_load_trang_content = '';
    $v_event_quang_cao_content = '';
    $v_url_html_ga_play_video_content = '';
    // Lấy chuỗi mã nhóm content
    $v_arr_ga_video_nhom_content = _24h_player_xu_ly_ga_nhom_content($v_tracking_tong_video);

    $v_arr_param_ga_qc = array(
        'v_ma_loai_giai_dau'     =>$v_ma_loai_giai_dau,
        'v_ga_preroll_tracking'  =>$v_arr_ga_video_nhom_content['v_url_preroll_content'],
        'v_ga_overlay_tracking'  =>$v_arr_ga_video_nhom_content['v_url_overlay_content'],
        'v_ga_postroll_tracking' =>$v_arr_ga_video_nhom_content['v_url_postroll_content'],
        'v_is_use_gtm_tracking'   =>$v_is_use_gtm_tracking,
        'v_url_news'  =>$v_url_news,
        'v_ma_nguon_source'=>$v_ma_nguon_source,
        'v_is_in_iframe'=>$v_is_in_iframe,
    );

    $v_event_quang_cao = _24h_player_get_event_load_ga_quang_cao_video($p_row_news,$v_arr_param_ga_qc);

    $v_event_load_trang_content = $v_arr_ga_video_nhom_content['v_event_load_trang_content'];
    $v_url_html_ga_play_video_content = $v_arr_ga_video_nhom_content['v_url_html_ga_play_video_content'];

    /* end 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
	global $gl_ga_load_trang;
    if($gl_ga_load_trang == 1){
        $v_event_load_trang = '';
		$v_event_load_trang_content = '';
    } else {
		$gl_ga_load_trang = 1;
	}
	/* Begin 20/11/2017 Tytv fix_loi_bai_video_load_trang_2_lan_do_bai_chua_video_nhieu_nguon */
    // nếu là bài vừa có chưa video đối tác vừa có chứa video nguồn 24h thì sẽ thiết lập giá trị load trang về Null, thực hiện đo load trang theo video đối tác được thiết lập ở JS
    if((strpos($p_row_news['Body'], 'vtvWrite') !== false || strpos($p_row_news['Body'], 'antvWrite') !== false) && strpos($p_row_news['Body'], 'flashWrite') !== false){
        $v_event_load_trang = '';
		$v_event_load_trang_content = '';
        $gl_ga_load_trang = 1;
    }
	/* End 20/11/2017 Tytv fix_loi_bai_video_load_trang_2_lan_do_bai_chua_video_nhieu_nguon */
	// Tạo mảng kết quả trả về
    $v_arr_ga = array(
        'v_ga_load_trang'=>$v_event_load_trang,
        'v_ga_quang_cao'=>$v_event_quang_cao,
        'v_ga_play_video'=>$v_url_html_ga_play_video,
        /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
        'v_ga_load_trang_content'=>$v_event_load_trang_content,
        'v_ga_play_video_content'=>$v_url_html_ga_play_video_content,
        /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    );
	return $v_arr_ga;
}
/** @author: Tytv
 * Hàm tạo chuỗi event load ga quảng cáo cho video
 * @param array $p_row_news : Mảng các dữ liệu bài viết
 * @param array $p_param_extension : Mảng các dữ liệu truyền vào
 * @return string
 */

function _24h_player_get_event_load_ga_quang_cao_video($p_row_news,$p_param_extension) {
    // thiết lập các thông số cần sử dụng
    $v_ma_loai_giai_dau         = $p_param_extension['v_ma_loai_giai_dau'];
    $v_ga_preroll_tracking      = $p_param_extension['v_ga_preroll_tracking'];
    $v_ga_overlay_tracking      = $p_param_extension['v_ga_overlay_tracking'];
    $v_ga_postroll_tracking     = $p_param_extension['v_ga_postroll_tracking'];
    $v_is_use_gtm_tracking      = $p_param_extension['v_is_use_gtm_tracking'];
    $v_ma_nguon_source          = $p_param_extension['v_ma_nguon_source'];
    $v_is_in_iframe             = $p_param_extension['v_is_in_iframe'];
    $v_url_news                 = $p_param_extension['v_url_news'];
    $v_title_news               = fw24h_restore_bad_char($p_row_news['Title']);
    $v_title_news               = str_replace(array('"',"'"),'“', $v_title_news);
    $v_news_id                  = intval($p_row_news['ID']);

    // Lấy script Ga theo loại giải đấu
    $v_scipt_quang_cao_nevia = _24h_player_get_script_ga_loai_giai_dau($v_ma_loai_giai_dau,$v_news_id);
    $v_event_quang_cao = '';

	if ($v_ma_nguon_source == '') {
		$v_ma_nguon_source = '24H';
	}
	if ($v_is_use_gtm_tracking == true && $v_url_news != '' && $v_title_news != '') {
		if ($v_is_in_iframe == false) {
			$v_gtm_preroll = "dataLayer.push({'event':'videoAdStart', 'videoAdUrl':'$v_url_news', 'videoAdTitle':'$v_title_news', 'videoAdDuration':'', 'videoAdType':'Pre-roll', 'videoAdCampaign':'$v_ma_nguon_source'});";
			$v_gtm_overlay = "dataLayer.push({'event':'videoAdStart', 'videoAdUrl':'$v_url_news', 'videoAdTitle':'$v_title_news', 'videoAdDuration':'', 'videoAdType':'Overlay', 'videoAdCampaign':'$v_ma_nguon_source'});";
			$v_gtm_postroll = "dataLayer.push({'event':'videoAdStart', 'videoAdUrl':'$v_url_news', 'videoAdTitle':'$v_title_news', 'videoAdDuration':'', 'videoAdType':'Post-roll', 'videoAdCampaign':'$v_ma_nguon_source'});";
		} else {
			$v_gtm_preroll_str = "tracking_gtm_ad_video#:|:#$v_url_news#:|:#$v_title_news#:|:#Pre-roll#:|:#$v_ma_nguon_source";
			$v_gtm_preroll = 'window.parent.postMessage("'.$v_gtm_preroll_str.'","*");';
			$v_gtm_overlay_str = "tracking_gtm_ad_video#:|:#$v_url_news#:|:#$v_title_news#:|:#Overlay#:|:#$v_ma_nguon_source";
			$v_gtm_overlay = 'window.parent.postMessage("'.$v_gtm_overlay_str.'","*");';
			$v_gtm_postroll_str = "tracking_gtm_ad_video#:|:#$v_url_news#:|:#$v_title_news#:|:#Post-roll#:|:#$v_ma_nguon_source";
			$v_gtm_postroll = 'window.parent.postMessage("'.$v_gtm_postroll_str.'","*");';
		}
	} else {
		$v_gtm_preroll = '';
		$v_gtm_overlay = '';
		$v_gtm_postroll = '';
	}
	if ($v_ga_preroll_tracking != '') {
		$v_event_quang_cao .= "video".ID_PLAYER_VIDEO.".player.on('onPrerollStart', function(name){
			//console.log('ON PREROLL START');
            docGaPreroll_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga_preroll');
            if(docGaPreroll_".ID_PLAYER_VIDEO."){
                docGaPreroll_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"".$v_ga_preroll_tracking."\"></iframe>';
            }
            /* begin 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
            /*event_ga_preroll_video_content*/
            /* end 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
			".$v_gtm_preroll."
		});";
	}
	if ($v_ga_overlay_tracking != '') {
		$v_event_quang_cao .= "video".ID_PLAYER_VIDEO.".player.on('onOverlayStart', function(name){
			//console.log('ON OVERLAY START');
            docGaOverlay_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga_overlay');
            if(docGaOverlay_".ID_PLAYER_VIDEO."){
                docGaOverlay_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"".$v_ga_overlay_tracking."\"></iframe>';
            }
            /* begin 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
            /*event_ga_overlay_video_content*/
            /* end 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
			".$v_gtm_overlay."
		});";
	}
	if ($v_ga_postroll_tracking != '') {
		$v_event_quang_cao .= "video".ID_PLAYER_VIDEO.".player.on('onPostRollStart', function(name){
			//console.log('ON POSTROLL START');
            docGaPostroll_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga_overlay');
            if(docGaPostroll_".ID_PLAYER_VIDEO."){
                docGaPostroll_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"".$v_ga_postroll_tracking."\"></iframe>';
            }
            /* begin 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
            /*event_ga_postroll_video_content*/
            /* end 4/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
			".$v_gtm_postroll."
		});";
	}
    $v_event_quang_cao .= $v_scipt_quang_cao_nevia;
	return $v_event_quang_cao;
}
/*
 * hàm xử lý ga nhóm content
 *  */
function _24h_player_xu_ly_ga_nhom_content($p_tracking){
    $v_tracking = $p_tracking;
    // cấu hình các url html load ga
    $v_arr_ga_video_content = _get_module_config('get_video_outsite','v_arr_ga_video_content');
    $v_url_html_ga_load_trang_content = $v_arr_ga_video_content['load_trang_video_content'].$v_tracking;
    $v_url_html_ga_play_video_content = $v_arr_ga_video_content['player_video_content'].$v_tracking;
    $v_url_html_ga_quang_cao_preroll_content = $v_arr_ga_video_content['preroll_video_content'].$v_tracking;
    $v_url_html_ga_quang_cao_overlay_content = $v_arr_ga_video_content['overlay_video_content'].$v_tracking;
    $v_url_html_ga_quang_cao_postroll_content = $v_arr_ga_video_content['postroll_video_content'].$v_tracking;

    $v_event_load_trang_content = "var docLoadTrang = document.getElementById('".ID_PLAYER_VIDEO."_content_ga');";
    $v_event_load_trang_content .= "docLoadTrang.innerHTML= '<iframe src=\"".$v_url_html_ga_load_trang_content."\"></iframe>';";
    $v_event_play_content = "var docVideoPlay_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_content_play'); docVideoPlay_".ID_PLAYER_VIDEO.".innerHTML= '<iframe src=\"".$v_url_html_ga_play_video_content."\"></iframe>';";

    $v_arr_ga_nhom_content = array(
        'v_event_load_trang_content'=>$v_event_load_trang_content,
        'v_url_html_ga_play_video_content'=>$v_event_play_content,
        'v_url_preroll_content'=>$v_url_html_ga_quang_cao_preroll_content,
        'v_url_overlay_content'=>$v_url_html_ga_quang_cao_overlay_content,
        'v_url_postroll_content'=>$v_url_html_ga_quang_cao_postroll_content,
    );
    return $v_arr_ga_nhom_content;
}
/** @author Tytv
 * Hàm lấy script Ga theo loại giải đấu
 * @param string $p_ma_loai_giai_dau : Mã giải đấu
 * @param int $p_news_id : Id bài viết
 */
function _24h_player_get_script_ga_loai_giai_dau($p_ma_loai_giai_dau,$p_news_id){
    return '';
    /* Begin: Tytv - 30/10/2017 - toi_uu_code_video_off_tracking */
    $v_event_quang_cao ='';
    $v_link_tracking_doi_tac = '';
    $v_arr_link_loai_giai_dau_nivea = _get_module_config('cau_hinh_dung_chung', 'v_arr_link_loai_giai_dau_nivea');
    $v_arr_link = $v_arr_link_loai_giai_dau_nivea[$p_ma_loai_giai_dau];

    /* End: Tytv - 30/10/2017 - toi_uu_code_video_off_tracking */
    if(intval($p_news_id) > 0){
        $v_link_tracking_doi_tac = _24h_player_get_tracking_by_id_news(intval($p_news_id));
    }
    if($v_arr_link['Impression'] != '' || $v_link_tracking_doi_tac != ''){
        $v_event_quang_cao .= ",AE_impression: function(){";
        if($v_arr_link['Impression'] != ''){
            // Impresion quang cao nivea
            $v_event_quang_cao .= "v_url_impression = replace_chuoi_thoi_gian('".$v_arr_link['Impression']."');
                                var docGaAdsImpres_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
                                if(docGaAdsImpres_".ID_PLAYER_VIDEO."){
                                    docGaAdsImpres_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_impression+'\"></iframe>';
                                }";
        }
        if($v_link_tracking_doi_tac != ''){
            $v_event_quang_cao .= "v_url_impression_doi_tac = replace_chuoi_thoi_gian('".$v_link_tracking_doi_tac."');
                                var docGaAdsImpresDt_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga_doi_tac');
                                if(docGaAdsImpresDt_".ID_PLAYER_VIDEO."){
                                    docGaAdsImpresDt_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_impression_doi_tac+'\"></iframe>';
                                }";
        }
		$v_event_quang_cao .="}";
    }
    if($v_arr_link['start'] != ''){
        $v_event_quang_cao .= ",AE_start: function(){
            v_url_start = replace_chuoi_thoi_gian('".$v_arr_link['start']."');
            var docGaAdsStarted_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga_started');
            if(docGaAdsStarted_".ID_PLAYER_VIDEO."){
                docGaAdsStarted_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_start+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['firstQuartile'] != ''){
        $v_event_quang_cao .= ",AE_firstquartile: function(){
            v_url_firstQuartile = replace_chuoi_thoi_gian('".$v_arr_link['firstQuartile']."');
            var docGaAdsFirstQuartile_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsFirstQuartile_".ID_PLAYER_VIDEO."){
                docGaAdsFirstQuartile_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_firstQuartile+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['midpoint'] != ''){
        $v_event_quang_cao .= ",AE_midpoint: function(){
            v_url_midpoint = replace_chuoi_thoi_gian('".$v_arr_link['midpoint']."');
            var docGaAdsMidpoint_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsMidpoint_".ID_PLAYER_VIDEO."){
                docGaAdsMidpoint_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_midpoint+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['thirdQuartile'] != ''){
        $v_event_quang_cao .= ",AE_thirdquartile: function(){
            v_url_thirdQuartile = replace_chuoi_thoi_gian('".$v_arr_link['thirdQuartile']."');
            var docGaAdsThirdQuartile_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsThirdQuartile_".ID_PLAYER_VIDEO."){
                docGaAdsThirdQuartile_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_thirdQuartile+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['complete'] != ''){
        $v_event_quang_cao .= ",AE_complete: function(){
			if (load_skip".ID_PLAYER_VIDEO." !== true) {
                v_url_complete = replace_chuoi_thoi_gian('".$v_arr_link['complete']."');
                var docGaAdsComplete_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
                if(docGaAdsComplete_".ID_PLAYER_VIDEO."){
                    docGaAdsComplete_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_complete+'\"></iframe>';
                }
				load_skip".ID_PLAYER_VIDEO." = false;
			}
		}";
    }
    if($v_arr_link['mute'] != ''){
        $v_event_quang_cao .= ",AE_muted: function(){
            v_url_mute = replace_chuoi_thoi_gian('".$v_arr_link['mute']."');
            var docGaAdsMute_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsMute_".ID_PLAYER_VIDEO."){
                docGaAdsMute_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_mute+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['unmute'] != ''){
        $v_event_quang_cao .= ",AE_unmuted: function(){
            v_url_unmute = replace_chuoi_thoi_gian('".$v_arr_link['unmute']."');
            var docGaAdsUnMute_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsUnMute_".ID_PLAYER_VIDEO."){
                docGaAdsUnMute_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_unmute+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['pause'] != ''){
        $v_event_quang_cao .= ",AE_paused: function(){
            v_url_pause = replace_chuoi_thoi_gian('".$v_arr_link['pause']."');
            var docGaAdsPause_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsPause_".ID_PLAYER_VIDEO."){
                docGaAdsPause_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_pause+'\"></iframe>';
            }
		}";
    }
    if($v_arr_link['ClickThrough'] != ''){
        $v_event_quang_cao .= ",AE_clicked: function(){
		}";
    }
    if($v_arr_link['skip'] != ''){
        $v_event_quang_cao .= ",AE_skip: function(){
            v_url_skip = replace_chuoi_thoi_gian('".$v_arr_link['skip']."');
            var docGaAdsSkip_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsSkip_".ID_PLAYER_VIDEO."){
                docGaAdsSkip_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_skip+'\"></iframe>';
            }
            load_skip".ID_PLAYER_VIDEO." = true;
		}";
    }
    if($v_arr_link['skip'] != ''){
        $v_event_quang_cao .= ",AE_userClosed: function(){
            v_url_skip = replace_chuoi_thoi_gian('".$v_arr_link['skip']."');
            var docGaAdsSkipClose_".ID_PLAYER_VIDEO." = document.getElementById('".ID_PLAYER_VIDEO."_ga');
            if(docGaAdsSkipClose_".ID_PLAYER_VIDEO."){
                docGaAdsSkipClose_".ID_PLAYER_VIDEO.".innerHTML = '<iframe src=\"'+v_url_skip+'\"></iframe>';
            }
            load_skip".ID_PLAYER_VIDEO." = true;
		}";
    }

    return $v_event_quang_cao;
}
/*
 *  Hàm xử lý lấy cấu hình tracking theo id bài viết
 *  @param: $p_id_news       ID bài viết
 *  return  boolean
 **/
function _24h_player_get_tracking_by_id_news($p_id_news){
    $v_id_news = intval($p_id_news);
    $v_on_off = _get_module_config('cau_hinh_dung_chung', 'v_on_off_tracking_theo_id_bai');
    if($v_id_news <= 0 || !$v_on_off){return '';}
    // Lấy cấu hình mảng bài video clearmen
    $v_arr_tracking = _get_module_config('cau_hinh_dung_chung', 'v_tracking_theo_id_bai');
    return $v_arr_tracking[$p_id_news];
}
/**
 * Hàm chuyển đổi các dạng mã code script video theo cấu hình từng thời điểm
 * @param array $p_body : Code chứa video
 * @param array $p_arr_config_video : mảng chứa các dữ liệu cấu hình video
 * @return array
 */
function _24h_player_chuyen_doi_giua_cac_ma_script_video($p_code_video,$p_arr_config_video) {
    // nếu $p_code_video trống (hoặc null) thì trả về luôn
    if (empty($p_code_video)) {
		return $p_code_video;
	}
    $v_region_value = $p_arr_config_video['v_region_value'];
    $v_region_value = strtolower($v_region_value);
    /* Begin: Tytv - 30/10/2017 - toi_uu_code_video_off_tracking */
    $p_code_video = str_replace(array('heinekenWrite','quangcaoWrite'), 'flashWrite', $p_code_video);
    /* End: Tytv - 30/10/2017 - toi_uu_code_video_off_tracking */

    return $p_code_video;
}
/*
 *Ham xu ly video euroWrite2016
 *param : $p_body       noi dung bai viet
 *param : $p_region     xac dinh vung mien
 *return: $p_body
*/
function _vd_thay_the_video_euroWrite2016($p_body){
    $p_body = preg_replace('/euroWrite2016\(\"([^\)]*)\)/','flashWrite("/images/24hvideo_player.swf?file=$1,418,314)',$p_body);
    return $p_body;
}
/**
 * Hàm thực hiện thiết lập các dữ liêu highlight video theo từng loại video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @return string
 */
function _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news) {

    $v_id_bai_viet = intval($p_row_news['ID']);
    $v_arr_data_highlight = array();
    // Lấy tên dữ liệu highlight video
    if($v_id_bai_viet>0){
        $v_arr_data_highlight   = fe_get_single_highlight_video_news_by_news_id($v_id_bai_viet);
        $v_arr_data_highlight = (intval($v_arr_data_highlight['c_trang_thai_xuat_ban'])==1)?$v_arr_data_highlight:array();
        if(check_array($v_arr_data_highlight) && !empty($v_arr_data_highlight['t_highlight_video'])){
            $v_arr_data_highlight['t_highlight_video'] = _array_convert_index_to_key($v_arr_data_highlight['t_highlight_video'],'c_ten_video');
        }
    }
    return $v_arr_data_highlight;
}
/**
 * Hàm thực hiện tạo mã video từng player video
 * @param array $p_body : Code chứa video
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return array
 */
function _24h_player_tao_code_html_player($p_code_video,$p_param_extension) {
    // GÁN CÁC THAM SỐ ĐẦU VÀO
    // Mảng v_arr_config_video chứa tham số nhận dạng bài viết (v_region_id,v_region_value,v_is_bai_tuong_thuat,v_url_news,...) + thông số đặc thù của video (v_gia_tri_play_video: chế độ play video,...)
    $v_arr_config_video = $p_param_extension['v_arr_config_video'];

    // Mảng v_arr_config_video chứa tham số quảng cáo như: v_type_quang_cao,v_ma_loai_giai_dau,v_ads_preroll,v_ads_overlay,v_ads_postroll,v_ads_ga_code, v_ma_nguon_source,...
    $v_arr_data_ads     = $p_param_extension['v_arr_data_ads'];

    // Mảng v_arr_data_tracking chứa tham số đo tracking (gtm,ga) video  như: v_ga_load_trang,v_ga_quang_cao,v_ga_play_video,v_str_tracking_gtm_play_video,v_str_tracking_gtm_complete_video,v_is_use_gtm_tracking...
    $v_arr_data_tracking    = $p_param_extension['v_arr_data_tracking'];

    // Mảng v_arr_data_highlight_video chứa thông số phục vụ highlight video video
    $v_arr_data_highlight   = $p_param_extension['v_arr_data_highlight_video'];
    $v_row_news             = $p_param_extension['v_row_news'];
    $v_row_cat              = $p_param_extension['v_row_cat'];
    $v_khach_hang_logo_video = intval($v_row_news['khach_hang_logo_video']);

    // lấy ra các tham số cần thiết từ dữ liệu truyền vào
    $v_width_video          = intval($v_arr_config_video['v_width_video']);
    $v_height_video         = intval($v_arr_config_video['v_height_video']);
    $v_url_news             = $v_arr_config_video['v_url_news'];
    $v_che_do_play_video   = $v_arr_config_video['v_che_do_play_video'];

    // thiết lập tham số xác định video là ở trang bài viết hay box video chọn lọc hay trang video
    $v_is_box_video_chon_loc    = $v_arr_config_video['v_is_box_video_chon_loc'];
    $v_is_trang_bai_viet        = $v_arr_config_video['v_is_trang_bai_viet'];
    $v_is_trang_video           = $v_arr_config_video['v_is_trang_video'];

    // Thiết lập tham số xác định cấu hình hiển thị hight light video
    $v_have_pin_video = (isset($v_arr_data_highlight['t_highlight_video']))? true:false;

    $v_stt_pin_video = 0;
    $v_total_pin_video = 0;
    if($v_have_pin_video){
        $v_total_pin_video = count($v_arr_data_highlight['t_highlight_video']);
    }
    // Thiết lập tham số tạo chuỗi html xử lý countdown video
    $v_str_add_for_zplayer = '';
    $v_arr_width_height_video = _24h_player_get_width_height_for_video($v_width_video,$v_height_video,$v_is_trang_bai_viet ,$v_is_box_video_chon_loc,$v_is_trang_video );
    $v_width_video = $v_arr_width_height_video['v_width_video'];
    $v_height_video = $v_arr_width_height_video['v_height_video'];
    $v_div_logo_khach_hang 			= '';
    $v_html_flag_no_inview_video = '';
	/* begin 25/10/2017 TuyenNT fix_loi_video_chon_loc_bai_highlight */
	$v_style_highlight = '';
    if($v_is_box_video_chon_loc){
        $v_html_flag_no_inview_video = '<span class="noInViewVideo" style="display:none"></span>';
		$v_style_highlight = 'style="display:none;"';
    }
	/* end 25/10/2017 TuyenNT fix_loi_video_chon_loc_bai_highlight */
    // thiết lập mã nhận dạng đối tượng PLAYER
    $v_id_player = ID_PLAYER_VIDEO;
    // Tạo chuỗi gen HTML player
	$v_str_player = '<video id="'.ID_PLAYER_VIDEO.'" class="video-js vjs-default-skin vjs-16-9" controls width="'.$v_width_video.'" height="'.$v_height_video.'"
         poster="{poster_video}"  webkit-playsinline playsinline>
    {string_single_video}
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that
      <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>
	';
        // Khai báo div để tránh bị cancle GA
    $v_str_player = $v_div_logo_khach_hang.'<div class="v-24h-media-player" id="'.$v_id_player.'_container" style="display:block;width:'.$v_width_video.'px;height:auto;position:relative"><div id="'.$v_id_player.'_ga" style="display:none"></div><div id="'.$v_id_player.'_content_ga" style="display:none"></div><div id="'.$v_id_player.'_content_play" style="display:none"></div><div id="'.$v_id_player.'_content_preroll" style="display:none"></div><div id="'.$v_id_player.'_content_overlay" style="display:none"></div><div id="'.$v_id_player.'_content_postroll" style="display:none"></div><!--begin:str_video_player--><div id="v-24hContainer_'.$v_id_player.'" style="background: #eee;position:relative;height: auto"></div><!--end:str_video_player-->'.$v_html_flag_no_inview_video.'</div>';

    $v_id_player_pin_info = $v_id_player.'_pinInfo';
    // div chứa bài recommend video
    // $v_str_player .= '<div id="recommend_bai_viet_'.$v_id_player.'"></div>';
	/* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
    $v_str_player .= '<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->';
    /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
    // Khai báo div để tránh bị cancle GA
    $v_str_player .= '<div id="'.$v_id_player.'_ga_preroll" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_postroll" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_overlay" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_started" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_doi_tac" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_error1" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_error2" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_error3" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_error4" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_error5" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_media_slow_waiting" style="display:none"></div>';
    $v_str_player .= '<div id="'.$v_id_player.'_ga_media_slow_metadata" style="display:none"></div>';
	$v_str_player .= '<div id="'.$v_id_player.'_ga_videoviewership" style="display:none"></div>';
    // tạo tham số để tạo mã script cho player
    $v_arr_param_script_player = array(
        'v_id_player'=>$v_id_player,
        'v_che_do_play_video'=>$v_che_do_play_video,
        'v_stt_pin_video'=>$v_stt_pin_video,
        'v_total_pin_video'=>$v_total_pin_video,
        'v_id_player_pin_info'=>$v_id_player_pin_info,
        'v_str_add_for_zplayer'=>$v_str_add_for_zplayer,
        'v_arr_data_tracking'=>$v_arr_data_tracking,
        'v_arr_config_video'=>$v_arr_config_video,
		'v_row_cat' => $v_row_cat,
		'v_row_news' => $v_row_news,
		'width_player' => $v_width_video,
		'height_player' => $v_height_video,
		'v_title' => fw24h_replace_bad_char_to_null($v_row_news['Title'])
    );
    $v_str_player .= _24h_player_tao_ma_html_script_cho_player($v_arr_param_script_player);
    return $v_str_player;
}
/**
 * Hàm thiết lập các giá trị chiều rộng chiều cao cho video
 * @params $p_width_video  Độ rộng khung video
 * @params  $p_height_video: Độ cao khung video
 * @params  $p_is_trang_bai_viet: Phải là trang bài viết hay không
 * @params  $p_is_box_video_chon_loc: Có phải là box video chọn lọc hay không
 * @params  $p_is_trang_video: Có phải là trang video hay không
 * @return string
 */
function _24h_player_get_width_height_for_video($p_width_video,$p_height_video,$p_is_trang_bai_viet ,$p_is_box_video_chon_loc,$p_is_trang_video ){
    if($p_width_video<=0){
        if($p_is_trang_video){
            $p_width_video  = WIDTH_ZPLAYER_TRANG_VIDEO;
        }else if($v_is_box_video_chon_loc){
            $p_width_video  = WIDTH_ZPLAYER_BOX_VIDEO_CHON_LOC;
        }else{
            $p_width_video  = WIDTH_ZPLAYER_TRANG_BAI_VIET_VIDEO;
        }
    }
    if($p_height_video<=0){
        if($p_is_trang_video){
            $p_height_video  = HEIGHT_ZPLAYER_TRANG_VIDEO;
        }else if($p_is_box_video_chon_loc){
            $p_height_video  = HEIGHT_ZPLAYER_BOX_VIDEO_CHON_LOC;
        }else{
            $p_height_video  = HEIGHT_ZPLAYER_TRANG_BAI_VIET_VIDEO;
        }
    }
    return array('v_width_video'=>$p_width_video,'v_height_video'=>$p_height_video);
}
/**
 * Hàm thiết lập hiển thị logo video khách hàng
 * @params $p_id_khach_hang  ID khách hàng
 * @return string
 */
function _24h_player_get_html_khach_hang_logo_video($p_id_khach_hang) {
    return ''; # 20230520 off
	if ($p_id_khach_hang <= 0) {
		return;
	}
	$v_arr_khach_hang_logo_video = _get_module_config('cau_hinh_dung_chung','v_arr_khach_hang_logo_video');
	$v_link_khach_hang = $v_arr_khach_hang_logo_video[$p_id_khach_hang]['Link'];
	$v_logo_khach_hang = $v_arr_khach_hang_logo_video[$p_id_khach_hang]['Logo'];
	$v_html = '<div class="video-ballball"><a href="'.$v_link_khach_hang.'" target="_blank"><img src="'.html_image('/images/'.$v_logo_khach_hang,false).'" style="max-width:519px;width:519px" /></a></div>';
	return $v_html;
}
/**
 * Hàm tạo mã html java script cho player
 * @params
 * @params  $p_str_player: Chuỗi html player video
 * @params  $p_link_video: Chuỗi link video
 * @params  $p_arr_extension: Mảng dữ liệu truyền vào để xử lý chuỗi
 * @return string
 */
function _24h_player_tao_ma_html_script_cho_player($p_arr_param_script_player){
    // thiết lập tham số để gán vào script player
    $v_id_player = $p_arr_param_script_player['v_id_player'];
    $v_che_do_play_video   = $p_arr_param_script_player['v_che_do_play_video'];
    $v_stt_pin_video        = $p_arr_param_script_player['v_stt_pin_video'];
    $v_total_pin_video      = $p_arr_param_script_player['v_total_pin_video'];;
    $v_id_player_pin_info   = $p_arr_param_script_player['v_id_player_pin_info'];
    $v_str_add_for_zplayer  = $p_arr_param_script_player['v_str_add_for_zplayer'];
    $v_arr_data_tracking    = $p_arr_param_script_player['v_arr_data_tracking'];
    $v_arr_config_video     = $p_arr_param_script_player['v_arr_config_video'];
    $v_arr_row_news         = $p_arr_param_script_player['v_row_news'];
    $v_width_video         = $p_arr_param_script_player['width_player'];
    $v_height_video         = $p_arr_param_script_player['height_player'];

    $v_is_bai_tuong_thuat   = $v_arr_config_video['v_is_bai_tuong_thuat'];
    $v_is_box_video_chon_loc   = $v_arr_config_video['v_is_box_video_chon_loc'];
    $v_is_box_xem_video_trang_video   = $v_arr_config_video['v_is_box_xem_video_trang_video'];

    // thiết lập tham số tracking để gán vào script player
    $v_ga_load_trang = $v_arr_data_tracking['v_ga_load_trang'];
    $v_ga_quang_cao  = $v_arr_data_tracking['v_ga_quang_cao'];
    $v_ga_play_video = $v_arr_data_tracking['v_ga_play_video'];
    /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    $v_ga_load_trang_content = $v_arr_data_tracking['v_ga_load_trang_content'];
    $v_ga_play_video_content = $v_arr_data_tracking['v_ga_play_video_content'];
    /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    $v_str_tracking_gtm_play_video      = $v_arr_data_tracking['v_str_tracking_gtm_play_video'];
    $v_str_tracking_gtm_complete_video  = $v_arr_data_tracking['v_str_tracking_gtm_complete_video'];
    $v_is_use_gtm_tracking              = $v_arr_data_tracking['v_is_use_gtm_tracking'];

	$v_count_video_body = $v_arr_config_video['v_count_video_body'];
	$v_is_mini_video = '';
	$v_is_autoplay_view_port = '"viewportAutoPlay": false,';
	$v_videoVol = VIDEO_VOL_24H;
	$v_adVol = '';
    $v_video_tinh_huong = intval($_GET['p_video_tinh_huong']);
    $is_autoplay = intval($_GET['is_autoplay']);
	if ($v_che_do_play_video == 3 && !$v_is_box_video_chon_loc && (!$v_is_bai_tuong_thuat || ($v_is_bai_tuong_thuat && !$v_video_tinh_huong) || ($v_is_bai_tuong_thuat && $is_autoplay))) {
		$v_is_autoplay_view_port = '"viewportAutoPlay": true,';
		$v_videoVol = '0.15';
		$v_adVol = '"adVol" : "0.15",';
	}
    $v_danh_dau_highlight = '';
    if(!$v_is_box_video_chon_loc){
        $v_danh_dau_highlight = '//{set_highlight_zplayer_html5}';
    }
	$v_html_script = '';
	$v_skipAble = '';
	if (USE_SKIP_AD) {
		$v_skipAble = '"skipAble": true,';
	}
	$v_showAdVol = '';
	if (SHOW_AD_VOLUME) {
		$v_showAdVol = '"showAdVol": true,';
	}
    $v_str_in_iframe = '';
	if ($v_arr_config_video['v_is_in_iframe'] == true) {
		$v_str_in_iframe = '"isInIframe": true,"parentOrigin" : "'.rtrim(BASE_URL_FOR_PUBLIC,'/').'",';
	}
    $v_script_focus = 'document.hasFocus();';
	// Với dạng bài tường thuật và click thì k cần focus
    if($v_is_bai_tuong_thuat && $v_arr_config_video['v_is_in_iframe']){
		$v_script_focus = 'true;';
	}
    $v_event_viewership = '';
    $v_code_duration = '';
    $v_script_theater_mod = ''; // chế độ xem video kiểu rạp chiếu phim
    if (!$v_is_box_video_chon_loc && !$v_is_bai_tuong_thuat && !$v_is_box_xem_video_trang_video) {
        $v_script_theater_mod = '"theaterMod": {height: '.VIDEO_HEIGHT_THEATER_MOD.'},';
    }
    $v_arr_tracking_box['c_id_chuyen_muc'] = $v_arr_row_news['CategoryID'];
    $v_arr_tracking_box['c_id_tin_bai'] = $v_arr_row_news['ID'];
    $v_arr_tracking_box['c_id_vi_tri'] = 999;
    $v_html_script = '<script type="text/javascript">
        '.((!$v_is_box_video_chon_loc) ? 'var v_load_recommend_box'.$v_id_player.' = true;' : 'var v_load_recommend_box'.$v_id_player.' = false;').'
		//{VARIABLE_POSTER}
		function initvideo'.$v_id_player.'() {
            var vidLoaded = false,
                dynamicId,
                vidId,
                parentVid = "v-24hContainer_'.$v_id_player.'",
                    videoElmStr = \'<video id="__VIDID'.$v_id_player.'__" class="video-js vjs-default-skin vjs-16-9" width="'.$v_width_video.'" height="'.$v_height_video.'" controls poster="{poster_video}" \' +
                    \'webkit-playsinline playsinline> \' +
                    \'{string_single_video} \' +
                    \'<p class="vjs-no-js"> \'+
                      \'To view this video please enable JavaScript, and consider upgrading to a web browser that \'+
                      \'<a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a> \'+
                    \'</p> \' +
                  \'</video>\',
                video'.$v_id_player.',
                videoAds1Conf = {
                    "poster": "{poster_video}",
                    '.$v_str_in_iframe.'
                    "offsetWatchPercent": '.PERCENTAGE_SHOW_MINI_PLAYER.',
                    "stopOtherOnPlay": true,
                    "parentVid": parentVid,
                    '.$v_config_html_highlight.'
                    "vidErrorLog": '.ON_OFF_GHI_LOG_ERR_VIDEO_GA.',
                    '.$v_skipAble.'
                    "skipTime": '.(TIME_SKIP_BUTTON*1000).',
                    /*CDN_DOMAIN_HLS*/
                    "skipAdsBtnContent": "'.SKIP_TEXT.'",
                    '.$v_showAdVol.'
                    "VIDEOID": "'.$v_id_player.'",
                    /*MINI_VIDEO*/
                    '.$v_is_autoplay_view_port.'
                    "vidVol" : "'.$v_videoVol.'",
                    "adVolIncrease": 0.25,
					"showVolIncreasement":'.ON_OFF_VOLINCREASEMENT.',
                    '.$v_adVol.'
                    "trackSlowMedia":'.ON_OFF_GHI_LOG_MEDIA_SLOW.',
                    "secondToLoadMedia":'.SECOND_TO_LOAD_MEDIA.',
                    "secondToLoadMeta":'.SECOND_TO_LOAD_META.',
                    "prerollTimeEnd" : '.TIME_END_PRE_ROLL.',
                    "midrollTimeEnd" : '.TIME_END_OVERLAY.',
                    "postrollTimeEnd" : '.TIME_END_POST_ROLL.',
                    "skipAdsBtnPos": "'.SKIP_BUTTON_POSITION.'",
                    '.$v_script_theater_mod.'
                    "fastSeek": {step: '.SECOND_TO_FASTSEEK.'},
					"userAgent": "'.$_SERVER['HTTP_USER_AGENT'].'",
                    "adLoadTimeout" : '.ADS_LOAD_TIME_OUT.'
                    /*STRING_ADS_SINGLE_VIDEO*/
                    ,"midTime": '.MID_TIME_ADS.'
                    /*FALL_BACK*/
                    /*PLAYLIST_ITEM*/
                    /*DRM_VIDEO*/
                    '.$v_code_duration.'
                    '.$v_danh_dau_highlight.'
                    /*ID_BANNER_SPONSOR*/
                }
            if(document.getElementById(parentVid).innerHTML.trim() == ""){
                loadVid'.$v_id_player.'();
                vidLoaded = true;
            }
            function loadVid'.$v_id_player.'(){
                dynamicId = new Date().valueOf();
                vidId = "my-video-multiple1_"+dynamicId;
                videoAds1Conf.VIDEOID = vidId;
                if(vidLoaded==true){
                  videoAds1Conf.viewportAutoPlay = false;
                }
                var newVideStr = videoElmStr.replace(/__VIDID'.$v_id_player.'__/g, vidId);

                document.getElementById(parentVid).innerHTML = newVideStr;
                video'.$v_id_player.' = new videoObj(videoAds1Conf);
                playerEvents'.$v_id_player.'(video'.$v_id_player.');
                if(!document.getElementById(parentVid).classList.contains(parentVid+"_loaded")){
                  document.getElementById(parentVid).className += " "+parentVid+"_loaded";
                }

                video'.$v_id_player.'.player.on("videoended", function(){
                    window.autoplayAllowed_24hPlayer = false;
                    videoAds1Conf.viewportAutoPlay = false;
                    videoAds1Conf.autoplay = false;
                    var videoConf = videoAds1Conf;
                    videoConf.viewportAutoPlay = false;
                    var element = document.getElementById(videoConf.VIDEOID);
                    // element.parentNode.removeChild(element);
                    // video'.$v_id_player.' = undefined;
                    video'.$v_id_player.'.player.dispose();
                    loadVid'.$v_id_player.'();
                });

                video'.$v_id_player.'.player.ready(function () {
                    // XLCYCMHENG-38546 - [24H] Tối ưu khoảng trống trên/dưới banner sponsor_video_top / sponsor_video_botton
                    if (this.idBannerBottom != ""){
                        let vidEl = this.getById(this.VIDEOID);
                        let vidHeight = vidEl.getBoundingClientRect().height;

                        // làm tròn xuống & -1 height div chứa player
                        let parentHeight = Math.floor(vidHeight) - 1;
                        vidEl.parentNode.style.height = parentHeight + "px";
                        vidEl.parentNode.style.overflowY = "hidden";

                        // căn player lùi lên 0.7px
                        vidEl.style.marginTop = "-0.7px";

                        // 20220110: fix lỗi khi xem chế độ theater
                        let style = document.createElement("style");
                        style.type = "text/css";
                        style.innerHTML = ".theaterDisplay .video-js.vjs-16-9{margin-top: 0px !important}";
                        document.getElementsByTagName("head")[0].appendChild(style);
                    }
                }.bind(video'.$v_id_player.'));
            }
			function playerEvents'.$v_id_player.'(video'.$v_id_player.'){
                /*RESUME_WATCHING_EVENTS*/
				video'.$v_id_player.'.player.on("onPrerollError", function(name) {
					var objVideos = videojs(vidId+"_html5_api");
					if(!objVideos.muted()){
						var pVol = parseFloat(objVideos.controlBar.volumePanel.volumeControl.volumeBar.getPercent());
						//var pVol = parseFloat(objVideos.controlBar.volumeMenuButton.volumeBar.getPercent());
						if(objVideos.autoplay()){
							objVideos.volume(Math.round(pVol));
						}else{
							objVideos.volume('.$v_videoVol.');
						}
					}else{
						objVideos.volume('.$v_videoVol.');
					}
				});
                video'.$v_id_player.'.player.on("onPlay", function(name) {
					video'.$v_id_player.'.player.on("AE_completed", function(name) {
						var objVideos = videojs(vidId+"_html5_api");
						if(!objVideos.muted()){
							var pVol = parseFloat(objVideos.controlBar.volumePanel.volumeControl.volumeBar.getPercent());
							if(objVideos.autoplay()){
								objVideos.volume(Math.round(pVol));
							}else{
								objVideos.volume('.$v_videoVol.');
							}
						}else{
							objVideos.volume('.$v_videoVol.');
						}
					});
                    // begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc
                    '.$v_ga_play_video_content.'
                    // end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc
                    //Begin 14-07-2017 : Thangnb tracking_google_tag_manager_video
                    '.$v_str_tracking_gtm_play_video.'
                    //End 14-07-2017 : Thangnb tracking_google_tag_manager_video
                    if(document.getElementById("tracking_play_video_ben_thu_ba'.$v_id_player.'")){
                        document.getElementById("tracking_play_video_ben_thu_ba'.$v_id_player.'").innerHTML = \'<iframe src="/ajax/tracking_play_video_ben_thu_ba_pc.php"></iframe>\';
                    }
                });
                // Begin 12-12-2018 trungcq XLCYCMHENG_33549_xu_ly_scroll_to_top
                video'.$v_id_player.'.player.on("playerMinimized", function(name) {
                    // console.log("Listener playerMinimized: ");
                    if ($("#arrowPageUp").length) {
                        $("#arrowPageUp").css("bottom","157px");
                        $("#backpage").css("bottom","200px");
                    }
                });
                video'.$v_id_player.'.player.on("playerUnMinimized", function(name) {
                    // console.log("Listener playerUnMinimized: ");
                    if ($("#arrowPageUp").length) {
                        $("#arrowPageUp").css("bottom","20px");
                    }
                });
                video'.$v_id_player.'.player.on("miniPlayerClosed", function(name) {
                    // console.log("Listener miniPlayerClosed: ");
                    if ($("#arrowPageUp").length) {
                        $("#arrowPageUp").css("bottom","20px");
                    }
                });
            }
			videoAdsInit'.$v_id_player.' = true;
		}
		// Ga video load luôn được chạy
		'.(($v_is_box_video_chon_loc)?'':'/*{event_load_trang}*//*{event_load_trang_content}*/').'
		function inViewport( element ){
			// Get the elements position relative to the viewport
			var bb = element.getBoundingClientRect();
			// Check if the element is outside the viewport
			// Then invert the returned value because you want to know the opposite
			return !(bb.top > innerHeight || bb.bottom < 0);
		}
		var '.$v_id_player.'VpElm = document.getElementById( "'.$v_id_player.'" );

		var vis'.$v_id_player.' = (function () {
		  var stateKey, eventKey, keys = {
			hidden: "visibilitychange",
			webkitHidden: "webkitvisibilitychange",
			mozHidden: "mozvisibilitychange",
			msHidden: "msvisibilitychange"
		  };
		  for (stateKey in keys) {
			if (stateKey in document) {
			  eventKey = keys[stateKey];
			  break;
			}
		  }
		  return function (c) {
			if (c) document.addEventListener(eventKey, c);
			return !document[stateKey];
		  }
		})();

        var videoAds1Elm'.$v_id_player.' = document.getElementById("v-24hContainer_'.$v_id_player.'");
        document.addEventListener("DOMContentLoaded", function () {
            if(videoAds1Elm'.$v_id_player.'){
                var videoAdsInit'.$v_id_player.' = false;
                var checkTabActive'.$v_id_player.' = setInterval(function () {
					if (inViewport(videoAds1Elm'.$v_id_player.') && vis'.$v_id_player.'() && !videoAdsInit'.$v_id_player.' && typeof(window.videojs) !== "undefined" && (typeof(google) !== "undefined" || adBlockCheckedStatus) && videoObj) {
                        initvideo'.$v_id_player.'();
                        clearInterval(checkTabActive'.$v_id_player.');
                    }
                    // 20230817 fix lỗi lần đầu vào đợi onload check chặn quảng cáo quá lâu
                    else if (typeof videoObjRoot != "undefined" && videoObjRoot){
                        if (typeof videoAdsInit'.$v_id_player.'_fix_AdBlockCheck == "undefined"){
                            setTimeout(function(){
                                if (typeof adBlockCheckedStatus != "undefined" && !adBlockCheckedStatus){
                                    adBlockCheckedStatus = true;
                                    adBlockEnabled = false;
                                }
                            }, 1800);

                            videoAdsInit'.$v_id_player.'_fix_AdBlockCheck = true;
                        }
                    }
                }, 100);
            }
        }, false);
</script>';
    return $v_html_script;
}

// html fix lỗi tạm thời cho player trong khi chưa build dc phiên bản player mới
function _24h_player_html_wait_player_fix_bug($v_id_player){
    return '
        video'.$v_id_player.'.player.on("loadeddata", function(){
            // 20220224 fix lỗi không khởi tạo đc playerInitHeight
            if (this.playerInitHeight <= 0){
                this.playerInitHeight = Math.round(this.getById(this.VIDEOID).getBoundingClientRect().height)
                this.dbglog("this.playerInitHeight: " + this.playerInitHeight)

                this.playerHasSponsorInitHeight = Math.floor(this.getById(this.VIDEOID).getBoundingClientRect().height) - 1;// làm tròn xuống & -1
                this.dbglog("this.playerHasSponsorInitHeight: " + this.playerHasSponsorInitHeight);
            }

            // 20220414 fix bug ko lưu giá trị volume do user thay đổi
            if (!this.volumeBtnId){
                this.volumeBtnId = this.player.controlBar.volumePanel // .id(); // 20200414 fix chuyển volumeMenuButton, lấy đúng theo ver 7: volumePanel

                if (this.volumeBtnId) {
                    // track user directly click to the volume button
                    this.volumeBtnId.on(
                        "click",
                        function () {
                            this.dbglog("VOLUME BTN CLICKED")
                            this.addClasses(["vjs-userchanged-volume"])
                        }.bind(this)
                    )
                }
            }

        }.bind(video'.$v_id_player.'));

        video'.$v_id_player.'.player.on("volumechange", function(){
            // 20220414 fix bug ko lưu giá trị volume do user thay đổi

            if (this.volumeBtnId) {
                // 20220414 check thêm slide volume trong controlbar v7
                try {
                    if (this.player.controlBar.volumePanel.volumeControl.volumeBar.hasClass("vjs-slider-active")){
                        this.addClasses(["vjs-userchanged-volume"])
                        this.setState({
                            adVolChanged: true
                        })
                    }
                } catch (err){}
            }
        }.bind(video'.$v_id_player.'));
    ';
}

/** Author: Tytv 01/08/2017
 * Hàm thực hiện thiết lập thông số khi kết thúc video
 * $p_body
 * $p_stt thứ tự video cần thay thế bắt đầu từ 0, nhỏ hơn 0 là thay thế tất
 * @return string
 */
function _24h_player_thiet_lap_thong_so_khi_onStop_video($p_id_player,$p_str_tracking_gtm_complete_video){
	$v_str = $p_str_tracking_gtm_complete_video;
	return $v_str;
}
/**
 * Hàm thực hiện thay thế các mã script video bằng code html video
 * @param array $p_code_video : Code chứa video
 * @param array $p_str_html_video : mảng chứa html player mẫu
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_thay_the_code_html_cho_cac_loi_goi_video($p_code_video,$p_str_html_video,$p_param_extension){

    // GÁN CÁC THAM SỐ ĐẦU VÀO
    // Mảng v_arr_config_video chứa tham số nhận dạng bài viết (v_region_id,v_region_value,v_is_bai_tuong_thuat,v_url_news,...) + thông số đặc thù của video (v_gia_tri_play_video: chế độ play video,...)
    $v_arr_config_video     = $p_param_extension['v_arr_config_video'];

    // Mảng v_arr_config_video chứa tham số quảng cáo như: v_type_quang_cao,v_ma_loai_giai_dau,v_ads_preroll,v_ads_overlay,v_ads_postroll,v_ads_ga_code, v_ma_nguon_source,...
    $v_arr_data_ads         = $p_param_extension['v_arr_data_ads'];

    // Mảng v_arr_data_tracking chứa tham số đo tracking (gtm,ga) video  như: v_ga_load_trang,v_ga_quang_cao,v_ga_play_video,v_str_tracking_gtm_play_video,v_str_tracking_gtm_complete_video,v_is_use_gtm_tracking...
    $v_arr_data_tracking    = $p_param_extension['v_arr_data_tracking'];
    $v_arr_data_highlight   = $p_param_extension['v_arr_data_highlight_video'];
    $v_row_news = $p_param_extension['v_row_news'];
    $v_row_cat  = $p_param_extension['v_row_cat'];
    $v_is_box_video_chon_loc = $v_arr_config_video['v_is_box_video_chon_loc'];
    $v_is_bai_tuong_thuat = intval($v_arr_config_video['v_is_bai_tuong_thuat']);
    $v_is_trang_video = $v_arr_config_video['v_is_trang_video'];
    // Thay thế từng đoạn code video (bài video thường là 1 lần - bài tường thuật trực tiếp là nhiều lần)
    for ($j=1; $j>0 && $j<=100; $j++) {
        if (strpos($p_code_video, 'flashWrite') !== false) {
            $v_str_player = $p_str_html_video;
            // Chỉ gán GA Load trang cho video đầu tiên
            if($j == 1){
                // Gán lại GA load trang cho zplayer
                $v_str_player = str_replace('/*{event_load_trang}*/',$v_arr_data_tracking['v_ga_load_trang'],$v_str_player);
                /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
                $v_str_player = str_replace('/*{event_load_trang_content}*/',$v_arr_data_tracking['v_ga_load_trang_content'],$v_str_player);
                global $gl_ga_load_trang;
                $gl_ga_load_trang = 1;
            }else{
                $v_str_player = str_replace('/*{event_load_trang}*/','',$v_str_player);
                /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
                $v_str_player = str_replace('/*{event_load_trang_content}*/','',$v_str_player);
                /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
            }
            // Thay thế id zplayer
            $v_zplayer_id = 'zplayer_'.substr(md5(microtime()),rand(0,26),5).$j;
            $v_str_player = str_replace(ID_PLAYER_VIDEO, $v_zplayer_id, $v_str_player);

            // Lấy danh sách file video
            preg_match('#file=(.*)\",#', $p_code_video, $v_file_video);
            $v_file_video = $v_file_video[1];
            $v_end_cut = strpos($v_file_video, '",');
            if ($v_end_cut != false) {
                $v_file_video = substr($v_file_video, 0, $v_end_cut);
            }
            $v_file_video = str_replace('file=', '', $v_file_video);
            $v_file_video = str_replace('",', '', $v_file_video);
            $arr_file_video = explode(',', $v_file_video);

            // thực hiện thay thế các thông số player theo file video
            $v_arr_thong_so_theo_file_video = _24h_player_xu_ly_tham_so_player_theo_file_video($arr_file_video,$v_str_player,$p_param_extension);
            $v_str_player    = $v_arr_thong_so_theo_file_video['v_str_player'];
            $v_str_playlist  = $v_arr_thong_so_theo_file_video['v_str_playlist'];

            $v_ad_postroll = count($arr_file_video)-1;
            $v_str_player = str_replace('{postroll_only}', $v_ad_postroll, $v_str_player);
			// Set lai số thứ tự video
			$v_str_player =str_replace('{stt_video}',$j,$v_str_player);

            // Thay thế html video vào nội dung bài viết
            $v_html_banner_sponsor_top = '';
            $v_html_banner_sponsor_botton = '';
            $p_code_video = preg_replace('#flashWrite\(([^\)]*)\);#', '</script>'.$v_html_banner_sponsor_top.'<!-- begin_media_player --><div class="viewVideoPlay">'.$v_str_player.'</div><!-- end_media_player --><script type="text/javascript">', $p_code_video, 1);
        } else {
            break;
        }
    }
    return $p_code_video;
}
/**
 * Hàm thực hiện check hiển thị video phụ cho bài viết
 * @param array $p_code_video : Code chứa video
 * @param array $p_str_html_video : mảng chứa html player mẫu
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_check_hien_thi_video_phu_cho_bai_video($p_row_cat = array(),$p_row_news = array()) {
	if (!check_array($p_row_cat) || !check_array($p_row_news) ) {
		return false;
	}
    $p_cate_id = intval($p_row_cat['ID']);
    global $v_is_bai_tuong_thuat;
    if($v_is_bai_tuong_thuat || $p_cate_id<=0){
		return false;
	}
	$v_ma_cau_hinh_video_phu_cho_bai_video = _get_module_config('cau_hinh_dung_chung','v_ma_cau_hinh_video_phu_cho_bai_video');
	$v_arr_cau_hinh = fe_danh_sach_gia_tri_theo_ma_danh_muc($v_ma_cau_hinh_video_phu_cho_bai_video);
	if(!check_array($v_arr_cau_hinh) || $p_cate_id<=0){
		return false;
	}
    $v_arr_cau_hinh = _array_convert_index_to_key($v_arr_cau_hinh, 'c_ma_gia_tri');
    $v_danh_sach_id_chuyen_muc = trim($v_arr_cau_hinh['DANH_SACH_CHUYEN_MUC_HIEN_THI_VIDEO_PHU']['c_ten']);

    if (empty($v_danh_sach_id_chuyen_muc)) {
		return false;
    }
    $v_arr_danh_sach_id_chuyen_muc = explode(',', $v_danh_sach_id_chuyen_muc);
	if(!check_array($v_arr_danh_sach_id_chuyen_muc)){
		return false;
    }
    /* Begin 24-03-2022 Quyvd Toi_uu_cau_hinh_hien_thi_video_thu_nho */
    if(!empty($p_row_news['c_list_category_id'])){
        $v_arr_cate_id = explode(',', trim($p_row_news['c_list_category_id']));
        foreach($v_arr_cate_id as $v_cate_id){
            $v_cate_id = intval($v_cate_id);
             if ($v_cate_id > 0 && in_array($v_cate_id, $v_arr_danh_sach_id_chuyen_muc)){
                return true;
            }
        }
    }
    return false;
}
/* End  24-03-2022 Quyvd Toi_uu_cau_hinh_hien_thi_video_thu_nho */
/**
 * Hàm thực hiện chuẩn hóa chuỗi video thoe dịnh dạng file video cho player
 * @param array $p_str_file_video : mảng danh sách chứa các file video
 * @param array $p_str_player :Chuỗi html player video
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_xu_ly_tham_so_player_theo_file_video($p_arr_file_video,$p_str_player,$p_param_extension){
    $v_str_playlist = '';
    $v_count_video = count($p_arr_file_video);
	$v_arr_ads = $p_param_extension['v_arr_data_ads'];
	// set mã content vào quảng cáo preroll
    if ($p_param_extension['v_row_news']['c_list_ma_content'] != '' && !empty($v_arr_ads['v_ads_preroll'])) {
		if(strpos($v_arr_ads['v_ads_preroll'],'cust_params') !== false){
			$v_arr_ads['v_ads_preroll'] = str_replace('cust_params=', 'cust_params=content_groups%3D'.urlencode($p_param_extension['v_row_news']['c_list_ma_content']).'%26', $v_arr_ads['v_ads_preroll']);
		}elseif(strpos($v_arr_ads['v_ads_preroll'],'?') !== false) {
			$v_arr_ads['v_ads_preroll'] = $v_arr_ads['v_ads_preroll'].'&cust_params=content_groups%3D'.urlencode($p_param_extension['v_row_news']['c_list_ma_content']);
		} else {
			$v_arr_ads['v_ads_preroll'] = $v_arr_ads['v_ads_preroll'].'?cust_params=content_groups%3D'.urlencode($p_param_extension['v_row_news']['c_list_ma_content']);
		}
    }
    // quảng cáo preroll
    $v_arr_ads['v_ads_preroll'] = '';
    $v_arr_ads['v_ads_postroll'] = '';
    $v_arr_ads['v_ads_overlay'] = '';
    if(check_array($p_arr_file_video)){
        $v_arr_file_video_drm = array();
		if ($v_count_video == 1) {
            $v_file_video_origin = $p_arr_file_video[0];
			$v_arr_file_video_drm[0] = $p_arr_file_video[0];
			if(strpos($p_arr_file_video[0],'http://') === false && strpos($p_arr_file_video[0],'https://') === false){
				$v_arr_file_video_drm[0] = DEFAULT_IMAGE_VIDEO.ltrim($p_arr_file_video[0],'/');
			}
            // Xử lý chỉnh sửa đường dẫn video theo cấu hình
            $p_arr_file_video[0] = _24h_player_thay_the_duong_dan_video_theo_cau_hinh($p_arr_file_video[0],$p_param_extension['v_row_news']);

            $v_type_video = 'video/mp4';
            $v_use_fallback = false;
            $v_ishls = false;
            // Xử lý fallback khi gọi m3u8
            $v_scipt_fallback = '';
            if($v_use_fallback){
                $v_scipt_fallback = "
                ,'fallbackSrc': {
                    'file': '".$v_file_video_origin."',
                    'type': 'video/mp4'
                }";
            }
			$v_str_playlist	= '<!--begin_source--><source src="'.$p_arr_file_video[0].'" type="'.$v_type_video.'"><!--end_source-->';
			$v_item_0 = $p_arr_file_video[0];
			$p_str_player = preg_replace('/{string_single_video}/msi', $v_str_playlist, $p_str_player);
			$v_string_ads_single_video = '
				,"vastUri": "'.urlencode($v_arr_ads['v_ads_preroll']).'"
				,"vastUriPost" : "'.urlencode($v_arr_ads['v_ads_postroll']).'"
				,"vastUriMid" : "'.urlencode($v_arr_ads['v_ads_overlay']).'"
			';
			$p_str_player = str_replace('/*STRING_ADS_SINGLE_VIDEO*/
', $v_string_ads_single_video, $p_str_player);
            $key_random_hls = '';
			if($p_param_extension['v_row_news']['vidhighlight'] == 1){
				$p_str_player = str_replace('"timeupdated"', '"timehupdated"', $p_str_player);
			}
		} else {
            $v_scipt_fallback_list = '';
			$v_str_playlist = ',"playlistConf": [';
			$v_ishls = false;
            $v_ism3u8 = false;
            $v_keyrandom = false;
            $v_type_video = 'video/mp4';
			foreach ($p_arr_file_video as $key => $v_item)  {
				if ($key == 0) {
					$v_item_0 = $v_item;
                    // kiểm tra video có đủ điều kiện hiển thị player
                    $v_scrip_tracking = _vd_off_player_and_tracking_ga_err($v_item,$p_param_extension['v_row_news']);
                    if($v_scrip_tracking != ''){
                        // Bổ sung thêm ga load trang PC
                        preg_match('/<iframe src="\/ajax\/ga_tong_tai_nguyen_video_load_trang.php.*"><\/iframe>/msU', $p_str_player, $iframePlayer);
                        $v_div_video =  time();
                        if($iframePlayer[0] != ''){
                            $gl_ga_load_trang =1;
                            $v_scrip_tracking .= "<div style='display:none' id='".$v_div_video."ga_load_trang_video_error99'></div><script type='text/javascript'>var docLoadTrang = document.getElementById('".$v_div_video."ga_load_trang_video_error99');docLoadTrang.innerHTML='".$iframePlayer[0]."';</script>";
                        }
                        return array('v_str_playlist'=>'','v_str_player'=>$v_scrip_tracking);
                    }
				}
                $v_file_video_origin = $v_item;
				$v_arr_file_video_drm[$key] = $v_file_video_origin;
				if(strpos($v_file_video_origin,'http://') === false && strpos($v_file_video_origin,'https://') === false){
					$v_arr_file_video_drm[$key] = DEFAULT_IMAGE_VIDEO.ltrim($v_file_video_origin,'/');
				}

                // Xử lý chỉnh sửa đường dẫn video theo cấu hình
                $v_item = _24h_player_thay_the_duong_dan_video_theo_cau_hinh($v_item,$p_param_extension['v_row_news']);
                $p_arr_file_video[$key] = $v_item;

                $v_use_fallback = false;
                //$v_ishls = false;
                // Kiểm tra định dạng file m3u8
                if(strpos($v_item,'.m3u8') !==false && $key == 0){
                    $v_type_video='application/x-mpegURL';
                    //$v_use_fallback = true;
                    $v_ism3u8 = true;
                    $v_ishls = _vd_use_hls_config($v_file_video_origin) ? true : false;
                }
                if(strpos($v_item,'.m3u8') ===false && $key > 0 && $v_ism3u8){
                    $v_type_video='application/x-mpegURL';
                    $v_item = str_replace('.mp4','.m3u8', $v_item);
                }
                // Xử lý chuỗi fallback
                if($v_use_fallback){
                    $v_scipt_fallback_list .= '{fallbackSrc: [{
						file: "'.$v_file_video_origin.'",
						type: "video/mp4"
					}]},';
                }
				if (strpos($v_item,DOMAIN_HLS_TAM_CHO_BOX_VIDEO_CHON_LOC) !== false) {
					$v_item = '/'.str_replace(DOMAIN_HLS_TAM_CHO_BOX_VIDEO_CHON_LOC,'',$v_item);
				}
                $v_cdn_domain = '';
                $key_random_hls = '';
                if($v_ishls && ON_OFF_CONFIG_SCRIPT_HLS){
                    // Lấy thư mục video
                    $v_arr_expl_tmp = explode('/', trim($v_file_video_origin));
                    unset($v_arr_expl_tmp[count($v_arr_expl_tmp)-1]);
                    $v_folder_video = implode('/',$v_arr_expl_tmp);
                    if($v_folder_video != ''){
                        $v_script_config_hls = str_replace('<!--folder_video_hls-->',$v_folder_video.'/', CONFIG_SCRIPT_HLS);
                        $p_str_player = str_replace('/*CDN_DOMAIN_HLS*/', $v_script_config_hls, $p_str_player);
                        $v_cdn_domain = str_replace('<!--folder_video_hls-->',$v_folder_video.'/', CONFIG_SCRIPT_HLS_PLAYLIST);
                        $key_random_hls = _vd_key_random_hls_config($v_file_video_origin);
                        if($v_keyrandom && $key > 0 && empty($key_random_hls)){
                            $key_random_hls = '"hlsPrefix": "LNRzclvYycgvrUaIFqPxoGgAeMJXfJnokey",';
                        }
                        if($key_random_hls != ''){
                            $v_keyrandom =true;
                            $p_str_player = str_replace('/*random-hls-prefix*/', $key_random_hls, $p_str_player);
                            $v_cdn_domain = str_replace('/*random-hls-prefix*/', $key_random_hls, $v_cdn_domain);
                        }
                    }
                }
				if ($key == 0) {
					$v_str_playlist .= '{sources: [{
						src: "'.$v_item.'",
						type: "'.$v_type_video.'",
                        '.$v_cdn_domain.'
						adTagUrl : "'.urlencode($v_arr_ads['v_ads_preroll']).'"
					}]},';
				} else if (($key + 1) == $v_count_video) {
					$v_str_playlist .= '{sources: [{
						src: "'.$v_item.'",
                        '.$v_cdn_domain.'
						type: "'.$v_type_video.'",
						adTagUrl : "'.urlencode($v_arr_ads['v_ads_postroll']).'"
					}]},';
				} else {
					$v_str_playlist .= '{sources: [{
						src: "'.$v_item.'",
                        '.$v_cdn_domain.'
						type: "'.$v_type_video.'"
					}]},';
				}
			}
			$v_str_playlist .= ']';
			$v_string_ads_mid_video = '
				,"vastUriMid" : "'.urlencode($v_arr_ads['v_ads_overlay']).'"
			';
            $v_scipt_fallback_conf = '';
            if($v_scipt_fallback_list != ''){
                $v_scipt_fallback_conf = ',"playlistConfFallback": ['.$v_scipt_fallback_list.']';
            }
			// Cấu hình sử dụng fallback video
			if(USE_ON_OFF_FALLBACK){
				$p_str_player = str_replace('/*FALL_BACK*/', $v_scipt_fallback_conf, $p_str_player);
			}
			$p_str_player = str_replace('/*STRING_ADS_SINGLE_VIDEO*/
', $v_string_ads_mid_video, $p_str_player);
        $p_str_player = preg_replace('/{string_single_video}/msi', '', $p_str_player);
			$p_str_player = str_replace('/*PLAYLIST_ITEM*/
', $v_str_playlist, $p_str_player);
            $p_str_player = str_replace('//{VARIABLE_POSTER}', 'var POSTER = "{poster_video}";', $p_str_player);
		}
    }
	$p_str_player = _24h_player_gan_anh_dai_dien_video_vao_player($p_str_player,$v_item_0,$p_param_extension);
    //$p_str_player = str_replace('{tong-so-video}', $v_count_video, $p_str_player);
    return array('v_str_playlist'=>$v_str_playlist,'v_str_player'=>$p_str_player);
}
/**
 * Hàm gán ảnh đại diện theo file video vào string player video
 * @params
 * @params  $p_str_player: Chuỗi html player video
 * @params  $p_link_video: Chuỗi link video
 * @params  $p_arr_extension: Mảng dữ liệu truyền vào để xử lý chuỗi
 * @return string
 */
function _24h_player_gan_anh_dai_dien_video_vao_player($p_str_player,$p_link_video,$p_param_extension){
    // thiết lập tham số biến xử lý cần dùng
    $v_arr_config_video     = $p_param_extension['v_arr_config_video'];
    $v_row_news             = $p_param_extension['v_row_news'];
    $v_row_cat              = $p_param_extension['v_row_cat'];

    $v_is_bai_tuong_thuat = $v_arr_config_video['v_is_bai_tuong_thuat'];
	$v_link_image = html_image($v_row_news['SummaryImg_chu_nhat'],false); 
	$p_str_player = str_replace('{poster_video}', $v_link_image, $p_str_player);
    return $p_str_player;
}
/*
 * Hàm tạo danh sách các sự kiện highlgiht của video
 * @author: Tytv  - 17-08-2016
 * param $p_arr_data Mảng dữ liệu chứa sự kiện highlight
 * retturn string
 *
 */
function _24h_player_thay_the_chuoi_de_thiet_lap_highlight_zplayer_html5($p_arr_data,$p_array_file_video,$p_str_player){
    $v_script = '';
    $v_array_file_video = $p_array_file_video;

    if(check_array($p_arr_data) && check_array($p_arr_data['t_highlight_video']) && check_array($v_array_file_video)){
        $v_arr_config_highlight_video = _get_module_config('cau_hinh_dung_chung', 'v_arr_config_highlight_video');
        $v_total_time = 0;
        $v_arr_thoi_gian_tung_phan = array();
        $v_arr_tinh_huong_highlight = array();
        $v_total_hl = 0;# 20220811 hailt fix ko tạo code hl khi không có tình huống hl nào (lỗi do 1 bài có nhiều player, hl không phải ở tất cả các player)

        foreach ($v_array_file_video as $key => $value) {
            $v_ten_video = end(explode('/', $value));
            $v_arr_highlight_video = $p_arr_data['t_highlight_video'][$v_ten_video];
            $v_arr_info_file_video= fe_chi_tiet_1_file_video_upload($v_ten_video);
            // Lấy thời lượng video
            $v_thoi_luong_video = $v_arr_info_file_video['c_thoi_luong'];

            // Lấy thời lượng của từng video
            if($v_thoi_luong_video != ''){
                $v_arr_thoi_luong = explode(':', $v_thoi_luong_video);
                // Lấy thời gian tổng của từng video
                $v_total_time = $v_total_time + (intval($v_arr_thoi_luong[0]) *60) + intval($v_arr_thoi_luong[1]);
                $v_arr_thoi_gian_tung_phan[] = (intval($v_arr_thoi_luong[0]) *60) + intval($v_arr_thoi_luong[1]);
            }

            $arr_sctipt_highlight =array();
            if(check_array($v_arr_highlight_video['t_highlight'])){
                foreach ($v_arr_highlight_video['t_highlight'] as $key1 => $value1) {
                    $v_time = (($value1['c_so_phut']<10)?('0'.$value1['c_so_phut']):$value1['c_so_phut']).':'.(($value1['c_so_giay']<10)?('0'.$value1['c_so_giay']):$value1['c_so_giay']);
                    $v_giay = intval($value1['c_so_phut'])*60 + intval($value1['c_so_giay']);
                    $v_str_pin = $v_giay;
                    $v_url_icon = (empty($value1['c_url_icon']))?html_image($v_arr_config_highlight_video['url_icon_highlight_default'],false):html_image($value1['c_url_icon'],false);
                    $v_arr_icon = explode('/',$v_url_icon);
                    $v_icon_name = end($v_arr_icon);
                    $v_ghi_chu = $value1['c_ghi_chu'];
                    $arr_sctipt_highlight[] ="{
                        'time': ".$v_giay.",
                        'text': '".$v_ghi_chu."',
                        'icon': '".$v_icon_name."'
                      }";

                    ++$v_total_hl;
                }
            }
            $v_arr_tinh_huong_highlight[] = "[".  implode(',',$arr_sctipt_highlight)."]";# 20220811 hailt fix lỗi hl hiển thị không đúng thứ tự video (lỗi khi video nhiều phần, có phần không chứa hl)
        }

        if ($v_total_hl > 0){ # 20220811 hailt fix ko tạo code hl khi không có tình huống hl nào
            // Tạo chuỗi script highlight video
            $v_value_user_tooltip = USE_TOOLTIP_HIGHLIGHT ? 'true' : 'false';
            $v_script .=",'highlight': {
                'useTooltip': ".$v_value_user_tooltip.",
                'totalTime': ".$v_total_time.",
                'onoffopt': true,
                'hltime': [".implode(',', $v_arr_thoi_gian_tung_phan)."],
                'hl': [".implode(',', $v_arr_tinh_huong_highlight)."]
            }";
        }
    }

    $p_str_player = str_replace('//{set_highlight_zplayer_html5}',$v_script, $p_str_player);
    return $p_str_player;
}
/*
 * Hàm tạo chuỗi giá trị cho biến PINS flash var của zplayer
 * @author: Tytv  - 17-08-2016
 * param $p_arr_data Mảng dữ liệu chứa sự kiện highlight
 * retturn string
 *
 */
function _24h_player_html_tao_chuoi_gia_tri_pin_highlight_video($p_arr_data,$p_str_playlist,$p_phan_cach_video = '***'){
    $v_str_pin = '';
    $v_co_highlight = false;
    if(check_array($p_arr_data) && check_array($p_arr_data['t_highlight_video']) && !empty($p_str_playlist)){
        $v_arr_config_highlight_video = _get_module_config('cau_hinh_dung_chung', 'v_arr_config_highlight_video');
        $v_so_video = sizeof($p_arr_data['t_highlight_video']);
        $v_arr_videos = explode($p_phan_cach_video,$p_str_playlist);
        if(check_array($v_arr_videos)){
            foreach ($v_arr_videos as $value) {
                $v_ten_video = end(explode('/',$value));
                $v_arr_highlight_1_video = $p_arr_data['t_highlight_video'][$v_ten_video]['t_highlight'];
                if(check_array($v_arr_highlight_1_video)){
                    $v_co_highlight = true;
                    foreach ($v_arr_highlight_1_video as $key1 => $value1) {
                        $v_time = $value1['c_so_phut'].':'.$value1['c_so_giay'];
                        $v_giay = intval($value1['c_so_phut'])*60 + intval($value1['c_so_giay']);
                        $v_url_icon = html_image($value1['c_url_icon'],false);
                        $v_url_icon = (empty($value1['c_url_icon']))?html_image($v_arr_config_highlight_video['url_icon_highlight_default'],false):html_image($value1['c_url_icon'],false);
                        /* Begin: Tytv 02/11/2016 - fix_loi_icon_highlight_video_hien_thi_loi */
                        $v_ghi_chu = str_replace(array('"'), array("'"),  fw24h_restore_bad_char($value1['c_ghi_chu']));
                        $v_str_pin          .= $v_giay.'@@@'.$v_ghi_chu.'@@@'.$v_url_icon.'^^^';
                        /* Begin: Tytv 02/11/2016 - fix_loi_icon_highlight_video_hien_thi_loi */
                    }
                    $v_str_pin = rtrim($v_str_pin,'^^^');
                }
                $v_str_pin .= $p_phan_cach_video;
            }
        }
        $v_str_pin = rtrim($v_str_pin,$p_phan_cach_video);
        $v_str_pin .= '&pinImage='.html_image($v_arr_config_highlight_video['url_pin_image_default'],false);
    }
    return $v_co_highlight?$v_str_pin:"";
}
/*
 * Hàm tạo chuỗi hàm khởi tạo sự kiện Next ,prev đối với video nhiều phần
 * @author: Tytv  - 17-08-2016
 * param $p_str_playlist  Chuỗi dữ liệu
 * retturn string
 *
 */
function _24h_player_html_tao_event_next_prev_highlight_video($p_str_playlist,$p_zplayer_id,$p_phan_cach_video = '***',$p_ten_id_chua_ifm = ''){
    $v_arr_video = explode($p_phan_cach_video, $p_str_playlist);
    $v_tong_video = intval(sizeof($v_arr_video));
    if($v_tong_video >1){ // nếu có nhiều video
        $v_str_event_next_highlight =    '
            var v_indexCur = indexVideo;
            var v_indexFur = indexVideo+1;
            if(v_indexFur>'.($v_tong_video-1).'){
                indexVideo = 0;
                v_indexFur = indexVideo;
            }else{
                indexVideo++;
            }
            var vobj1 = document.getElementById(\'idx_video_'.$p_zplayer_id.'_id\'+v_indexCur);
            var vobj2 = document.getElementById(\'idx_video_'.$p_zplayer_id.'_id\'+v_indexFur);
            if(vobj1){
                vobj1.style.display = "none";
            }
            if(vobj2){
                vobj2.style.display = "block";
            }
            // thực hiện thiết lập lại độ cao iframe cho khung load video
            set_iframe_video_highlight_height(\'video_player'.$p_ten_id_chua_ifm.'\');
        ';
        $v_str_event_prev_highlight =    '
            var v_indexCur = indexVideo;
            var v_indexFur = indexVideo-1;
            if(indexVideo<=0){
                indexVideo = '.($v_tong_video-1).';
                v_indexFur = indexVideo;
            }else{
                indexVideo--;
            }
            var vobj1 = document.getElementById(\'idx_video_'.$p_zplayer_id.'_id\'+v_indexCur);
            var vobj2 = document.getElementById(\'idx_video_'.$p_zplayer_id.'_id\'+v_indexFur);
            if(vobj1){
                vobj1.style.display = "none";
            }
            if(vobj2){
                vobj2.style.display = "block";
            }
            // thực hiện thiết lập lại độ cao iframe cho khung load video
            set_iframe_video_highlight_height(\'video_player'.$p_ten_id_chua_ifm.'\');
        ';
    }
    $v_arr_event_next_prev = array(
        'v_str_event_next_highlight'=>$v_str_event_next_highlight,
        'v_str_event_prev_highlight'=>$v_str_event_prev_highlight,
    );
    return $v_arr_event_next_prev;
}
/** Author: Tytv 17/01/2017
 * Hàm thực hiện thay thế chuỗi bắt sự kiện onpins zplayer
 * @return string
 */
function _24h_player_thay_the_chuoi_de_thiet_lap_onpins_zplayer_html5($p_id_player,$p_html){
   $v_str_set_onpins_zplayer_html5 = 'onPins: function(){
                                var pins = JSON.parse(arguments[1]);
                                var pinList = zq("#pinList'.$p_id_player.'");
                                pinList.html("");
                                var html = "";
								if(stt_pin_'.$p_id_player.'> total_pin_'.$p_id_player.' || stt_pin_'.$p_id_player.'<=0){
									stt_pin_'.$p_id_player.' = 1;
								}
								if(total_pin_'.$p_id_player.'>1){
									html += "<div class=\"part-video-zplayer\"><span id=\"sum_video_zplayer\">"+stt_video_'.$p_id_player.'+"/"+total_pin_'.$p_id_player.'+"</span></div>";
									html += "<div class=\"box-time-highlight\">";
									stt_pin_'.$p_id_player.' = stt_pin_'.$p_id_player.'+1;
								}

                                for(var i in pins)
                                {
                                    html += "<div class=\"time-highlight\"><a class=\"highlight\" href=\"javascript:void(0);\" onclick=\"zPlayerAPI(\''.$p_id_player.'\', \'goTime\', \'" + pins[i].time + "\');\"><div class=\"video-time-highlight\"><div class=\"icon-event-highlight\"><img width=\"16\" height=\"18\" alt=\"\" src=\"" + pins[i].image + "\"></div><div><strong>"+zPlayerHTML5.timeFormat(pins[i].time)+"</strong> - "+ pins[i].text +"</div></div></a></div>";
                                }
								if(total_pin_'.$p_id_player.'>1){
									html += "</div>";
								}
                                pinList.html("<div id=\"'.$p_id_player.'_pinInfo\" class=\"khung-zplayer\"><div class=\"khung-zplayer-container\">"+html+"</div></div>");
                            },';
        $p_html = str_replace('//{set_onpins_zplayer_html5}',$v_str_set_onpins_zplayer_html5, $p_html);
    return $p_html;
}
/** Author: Tytv 17/01/2017
 * Hàm thực hiện thay thế chuỗi bắt sự kiện onstop zplayer
 * @return string
 */
function _24h_player_thay_the_chuoi_de_thiet_lap_onstop_change_zplayer_html5($p_id_player,$p_html){
   $v_str_set_mini_zplayer = 'onStop: function(){
                                flag_running_'.$p_id_player.' = false;
                            },';
        $p_html = str_replace('//{set_on_stop_zplayer_html5}',$v_str_set_mini_zplayer, $p_html);
    return $p_html;
}
/** Author: Tytv 17/01/2017
 * Hàm thực hiện thay thế chuỗi xác định video đang phát thành hàm tương ứng
 * @return string
 */
function _24h_player_thay_the_chuoi_de_xac_dinh_video_dang_phat($p_id_player,$p_html){
    $v_str_set_position = '
                            var videoInfo = arguments[1].split("***");
                            var videoFile = videoInfo[0];
                            var postId = videoInfo[1];
                            var newsId = videoInfo[2];
                            window["postIdZplayer"] = postId; /* Tytv 24/03/2017 - trang_video(co_che_dem_nguoc_video) */
                            if(!flag_running_'.$p_id_player.'){
                                flag_running_'.$p_id_player.' = true;';
            // thiết lập vi trí video đang phát
            $v_str_set_position .= '
                                var videoListLi = zq(".item-video-play");
                                for(var i in videoListLi)
                                {
                                    var elm_video = zq(videoListLi[i]);
                                    if(elm_video){
                                        if(elm_video.data("file") == postId){
                                            elm_video.find(".dangchay").css("display", "");
                                        }else{
                                            elm_video.find(".dangchay").css("display", "none");
                                        }
                                    }
                                }
                                ';
    $v_str_set_position .= '}';
    $p_html = str_replace('//{set_position_run_video_zplayer_html5}',$v_str_set_position, $p_html);
    return $p_html;
}
/** Author: Tytv 17/01/2017
 * Hàm thực hiện thay thế chuỗi xác định video đang phát thành hàm tương ứng
 * @return string
 */
function _24h_player_thay_the_chuoi_de_thiet_lap_mini_zplayer_html5($p_id_player,$p_html){
    $v_str_set_mini_zplayer = '';
	/* Begin 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video (đổi thông số cho đồng nhất vị trí hiển thị video thu nhỏ) */
    if(SET_UP_MINI_PLAYER_TRANG_VIDEO){
        // setUpMiniPlayer('zPlayerContainer', width, height, top, left, bottom, right);
        $v_str_set_mini_zplayer = '
			/*
                var left_minizplayer = '.LEFT_MINI_ZPLAYER_TRANG_VIDEO.';
                var rect = document.getElementById("zone_mini_zplayer").getBoundingClientRect();
                //var height_footer = document.getElementById("zone_footer").offsetHeight;
                var bottom_minizplayer = 0;
				// neu có banner trôi chân trang thì lấy độ cao và thiết lập ko bị đè lên
                if (typeof(ADS_109_15s) != \'undefined\' && ADS_109_15s.aNodes.length>0) {
                    bottom_minizplayer = ADS_109_15s.aNodes[0].height;
                }
                // console.log(rect.top, rect.right, rect.bottom, rect.left);
                left_minizplayer = rect.left + 5;
			*/
                ';
        $v_str_set_mini_zplayer .= 'zPlayerAPI(\''.$p_id_player.'\').setUpMiniPlayer(\'zPContainer_'.$p_id_player.'\', '.WIDTH_MINI_ZPLAYER_TRANG_VIDEO.', '.HEIGHT_MINI_ZPLAYER_TRANG_VIDEO.','.TOP_MINI_ZPLAYER_TRANG_VIDEO.','.LEFT_MINI_ZPLAYER_TRANG_VIDEO.','.BOTTOM_MINI_ZPLAYER_TRANG_VIDEO.','.RIGHT_MINI_ZPLAYER_TRANG_VIDEO.');';
    }
/* End 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video (đổi thông số cho đồng nhất vị trí hiển thị video thu nhỏ) */
    $p_html = str_replace('//{set_mini_zplayer_html5}',$v_str_set_mini_zplayer, $p_html);
    return $p_html;
}
/** Author: Tytv 17/01/2017
 * Hàm thực hiện thay thế chuỗi bắt sự kiện onstop zplayer
 * @return string $v_arr_tmp[$key]
 */
function _24h_player_thay_the_chuoi_de_thiet_lap_onplay_extra_change_zplayer_html5($p_id_player,$p_html){
   $v_str_set_mini_zplayer = 'onPlayExtraVideo: function(){
                                var paramOnPlayExtraVideo = JSON.parse(arguments[1]);
                                if(paramOnPlayExtraVideo.postUrlVideo !="" || paramOnPlayExtraVideo.postUrlVideo != "undefined"){
                                    window.location.href = paramOnPlayExtraVideo.postUrlVideo;
                                }else{
                                    console.log(paramOnPlayExtraVideo);
                                }
                            },';
        $p_html = str_replace('//{set_onPlayExtraVideo_zplayer_html5}',$v_str_set_mini_zplayer, $p_html);
    return $p_html;
}
/**
 * Hàm tạo code player video theo url truyền vào
 * @param string $p_str_file_video Chuỗi url video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _vd_xu_ly_tao_player_theo_url_video($p_str_file_video,$p_row_news, $p_row_cat,$p_param_extension) {
    # XLCYCMHENG-40913 - video autoplay theo giải đấu - hỗ trợ check theo url video
    if (!isset($p_row_news['p_str_file_video'])){
        $p_row_news['p_str_file_video'] = $p_str_file_video;
    }

    //B1: Thiết lập các giá trị cấu hình (tùy thuộc vào các trường hợp gọi mà bổ xung thêm các tham số ban đầu cần thiết)
    $v_arr_config_video = _vd_thiet_lap_thong_so_cau_hinh_video($p_row_news, $p_row_cat,$p_param_extension);
    //B2: Loại bỏ video không được phép hiển thị
    //$p_code_video = _vd_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);
    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    $v_arr_data_ads = _24h_player_thiet_lap_thong_so_quang_cao_video_outsite($p_str_file_video,$p_row_news, $p_row_cat, $v_arr_config_video);

    //B4: Thiết lập dữ liệu gán tracking,ga
    $v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    $v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);

    //B5: BƯỚC NÀY BỎ  Thay thế dạng script write tùy theo cấu hình (hàm _vd_chuyen_doi_giua_cac_ma_script_video)

    //B6: Thiết lập dữ liệu highlight video
    $v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news);
    //B7: Gen mã html player
    $v_param_extension = array(
        'v_arr_config_video'=> $v_arr_config_video,
        'v_arr_data_ads'=>$v_arr_data_ads,
        'v_arr_data_tracking'=>$v_arr_data_tracking,
        'v_arr_data_highlight_video'=>$v_arr_data_highlight_video,
        'v_row_news'=>$p_row_news,
        'v_row_cat'=>$p_row_cat,
    );
    $v_str_html_player = _24h_player_tao_code_html_player($p_str_file_video,$v_param_extension);
    //B8: Thay thế mã gen html player bằng 1 player id cụ thể
    $v_html_player_video = _24h_player_tao_html_player_theo_file_video($p_str_file_video,$v_str_html_player,$v_param_extension);
    return $v_html_player_video;
}
/**
 * Hàm thực hiện tạo html player theo chuỗi file video
 * @param array $p_str_file_video : Code chứa video
 * @param array $p_str_html_video : mảng chứa html player mẫu
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_tao_html_player_theo_file_video($p_str_file_video,$p_str_html_video,$p_param_extension){
    // nếu không có file video thì trả về rỗng luôn
    if(empty($p_str_file_video)) return '';

    // GÁN CÁC THAM SỐ ĐẦU VÀO
    // Mảng v_arr_config_video chứa tham số nhận dạng bài viết (v_region_id,v_region_value,v_is_bai_tuong_thuat,v_url_news,...) + thông số đặc thù của video (v_gia_tri_play_video: chế độ play video,...)
    $v_arr_config_video = $p_param_extension['v_arr_config_video'];

    // Mảng v_arr_config_video chứa tham số quảng cáo như: v_type_quang_cao,v_ma_loai_giai_dau,v_ads_preroll,v_ads_overlay,v_ads_postroll,v_ads_ga_code, v_ma_nguon_source,...
    $v_arr_data_ads     = $p_param_extension['v_arr_data_ads'];

    // Mảng v_arr_data_tracking chứa tham số đo tracking (gtm,ga) video  như: v_ga_load_trang,v_ga_quang_cao,v_ga_play_video,v_str_tracking_gtm_play_video,v_str_tracking_gtm_complete_video,v_is_use_gtm_tracking...
    $v_arr_data_tracking    = $p_param_extension['v_arr_data_tracking'];

    $v_arr_data_highlight   = $p_param_extension['v_arr_data_highlight_video'];
    $v_row_news = $p_param_extension['v_row_news'];
    $v_row_cat  = $p_param_extension['v_row_cat'];
    $v_is_trang_video = $v_arr_config_video['v_is_trang_video'];

    // Gán lại GA load trang cho zplayer
    // nếu ko phải là trang video và các video đều chạy qua iframe thì loại bỏ code để bắt sự kiện load trang
	if(intval($v_row_news['vidhighlight']) ==1){
		// Gán lại GA load trang cho zplayer
		$p_str_html_video = str_replace('/*{event_load_trang}*/',$v_arr_data_tracking['v_ga_load_trang'],$p_str_html_video);
		/* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
		$p_str_html_video = str_replace('/*{event_load_trang_content}*/',$v_arr_data_tracking['v_ga_load_trang_content'],$p_str_html_video);
		/* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
		global $gl_ga_load_trang;
		$gl_ga_load_trang = 1;
	}
    if(!$v_is_trang_video){
        $v_str_player = str_replace('/*{event_load_trang}*/','',$p_str_html_video);
    }else{// trang video các video ko chạy qua iframe nên event load trang ko được load từ js nên phải có load ở trong video
        $v_str_player = str_replace('/*{event_load_trang}*/',$v_arr_data_tracking['v_ga_load_trang'],$p_str_html_video);
    }
    /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    $v_str_player = str_replace('/*{event_load_trang_content}*/','',$v_str_player); // $v_arr_data_tracking['v_ga_load_trang']
    /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
    // Thay thế id zplayer
    $v_zplayer_id = 'zplayer_'.substr(md5(microtime()),rand(0,26),5);
    $v_str_player = str_replace(ID_PLAYER_VIDEO, $v_zplayer_id, $v_str_player);

    // Lấy danh sách file video
    $p_str_file_video = fw24h_restore_bad_char($p_str_file_video);
    $arr_file_video = explode(',', trim($p_str_file_video));

    // thực hiện thay thế các thông số player theo file video
    $v_arr_thong_so_theo_file_video = _24h_player_xu_ly_tham_so_player_theo_file_video($arr_file_video,$v_str_player,$p_param_extension);
    $v_str_player    = $v_arr_thong_so_theo_file_video['v_str_player'];
    $v_str_playlist  = $v_arr_thong_so_theo_file_video['v_str_playlist'];

    // xử lý thay thế các thông số hightlight video
	/* Begin: Tytv - 30/10/2017 - fix_loi_highlight_video */
    if(check_array($v_arr_data_highlight)){
        // thay thế danh sách sự kiện highlight $v_str_player .= '{html_list_highlight_event}';//
        $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_highlight_zplayer_html5($v_arr_data_highlight,$arr_file_video,$v_str_player);
    }

    $v_ad_postroll = count($arr_file_video)-1;
    $v_str_player = str_replace('{postroll_only}', $v_ad_postroll, $v_str_player);

    // thay thế chuỗi thiết lập bắt sự kiện onStop zplayer
    $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onstop_change_zplayer_html5($v_zplayer_id,$v_str_player);
    // xử lý gắn cấu hình số liệu thời lượng xem video
    $v_str_player = _config_viewership_video($v_str_player,$arr_file_video);

    # XLCYCMHENG-39836 - thay thế script hỗ trợ tiếp tục xem tiếp khi load trang
    $v_cookie_key = 'player__resume_watching_'.intval($v_row_news['ID']).'_'.md5(json_encode($arr_file_video));
    $v_str_player = _vd_script_resume_watching_events($v_str_player, $v_zplayer_id, $v_cookie_key, intval($v_row_news['CategoryID']));

    if($v_is_trang_video){
        // thay thế mã thiết lập onplay xác định vị trí video đang phát trên trang
        $v_str_player = _24h_player_thay_the_chuoi_de_xac_dinh_video_dang_phat($v_zplayer_id,$v_str_player);
        // thay thế chuỗi thiết lập bắt sự kiện onMiniChange zplayer
        //$v_html = _24h_player_thay_the_chuoi_de_thiet_lap_onmini_change_zplayer_html5($v_zplayer_id,$v_str_player);
        // thay thế mã thiết lập minizplayer
        $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_mini_zplayer_html5($v_zplayer_id,$v_str_player);
        // thay thế chuỗi thiết lập bắt sự kiện onplayExtraVideo zplayer
        $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onplay_extra_change_zplayer_html5($v_zplayer_id,$v_str_player);
    }
    return $v_str_player;
}
/**
 * Hàm thực hiện thay thế đường dẫn video theo cấu hình
 * @param array $p_file_video : file video cần thay thế
 * @return string
 */
function _24h_player_thay_the_duong_dan_video_theo_cau_hinh($p_file_video,$rows_news = array()){
    // Kiểm tra file video có tồn tại
	return $p_file_video;
    if($p_file_video == ''){
        return $p_file_video;
    }
    $v_so_phut_load_file_mp4 	= intval(get_gia_tri_danh_muc_dung_chung('VIDEO_BAN_QUYEN','SO_PHUT_SU_DUNG_FILE_VIDEO_MP4'));
    // kiểm tra thời gian xuất bản của bài viết
    if($v_so_phut_load_file_mp4 > 0 && check_array($rows_news) && $rows_news['PublishedDate2'] != ''){
        $PublishedDate2 = date("Y-m-d H:i:s",strtotime($rows_news['PublishedDate2']." +".$v_so_phut_load_file_mp4." minutes"));
        if(strtotime($PublishedDate2) > strtotime(date("Y-m-d H:i:s"))){
            return $p_file_video;
        }
    }
    // Cấu hình hiển thị theo loại video theo ưu tiên
    //1: Hiển thị theo cơ chế ưu tiên
    //      UT1: Nếu kiểm tra có key đánh dấu video có mã hóa DRM -> Hiển thị video đã được mã hóa DRM
    //      UT2: Nếu kiểm tra có key đánh dấu video có mã hóa với key -> Hiển thị video đã được mã hóa với KEY
    //      UT3: Hiển thị video mp4 thông thường.
    //2: Hiển thị video mp4
    $v_loai_video_theo_uu_tien 	= intval(trim(get_gia_tri_danh_muc_dung_chung('VIDEO_BAN_QUYEN','CAU_HINH_HIEN_THI_LOAI_VIDEO_THEO_UU_TIEN')));
    if($v_loai_video_theo_uu_tien !=1){
        return $p_file_video;
    }
    // cắt nhỏ đường dẫn file video
    $p_arr_file_video = explode('/', $p_file_video);
    // Lấy tên file video
    $v_ten_file_video = $p_arr_file_video[count($p_arr_file_video)-1];

    // Ghép key redis
    $v_ten_key_redis = _make_name_key_redis(array(
     'c_muc_dich_su_dung'=>'keyvalue',
     'c_loai_du_lieu'=>'key_value_video_m3u8',
     'c_kieu_du_lieu'=>'data',
     'c_ten_du_lieu'=>$v_ten_file_video,
    ));
    // Gọi dữ liệu từ key redis
    $v_result_m3u8 = _get_key_value_redis($v_ten_key_redis);
    // Nếu video chưa được chuyển định dạng m3u8 thì không xử lý gì
    if($v_result_m3u8 === false || is_null($v_result_m3u8)){
        return $p_file_video;
    }
    //UT2: Nếu kiểm tra có key đánh dấu video có mã hóa với key -> Hiển thị video đã được mã hóa với KEY
    // Kiểm tra cấu hình && Kiểm tra key có redis hay không
    if(USE_NGINX_M3U8){
        global $gl_has_hls_key;
        $gl_has_hls_key =1;
        // Kiểm tra key có redis hay không. Và replace đuôi mp4 về m3u8
        $p_file_video = str_replace('.mp4','.m3u8', $p_file_video);
    }
    // Có sử dụng wowza m3u8
    if(USE_WOWZA_M3U8){
        if(strpos($p_file_video,'http://') !== false || strpos($p_file_video,'https://') !== false){
            $p_file_video  = str_replace(array(IMAGE_VIDEO,IMAGE_NEWS),'',$p_file_video);
        }
        $p_file_video  = str_replace('{link-video}',rtrim($p_file_video,'/'), LINK_VIDEO_HLS_2018);
    }else{
        /* Begin 04/07/2018 anhpt1 su_dung_domain_cdn_theo_cau_hinh */
        // sử dụng domain cdn theo cấu hình
        $p_file_video = _vd_use_domain_cdn_by_config($p_file_video);
        /* End 04/07/2018 anhpt1 su_dung_domain_cdn_theo_cau_hinh */
    }
    return $p_file_video;
}
/**
 * Hàm thiết lập các tham số global
  * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return array
 */
function _vd_thiet_lap_thong_so_cau_hinh_video($p_row_news, $p_row_cat,$p_param_extension) {
    $v_gia_tri_play_video = _vd_get_che_do_play_video($p_row_cat['ID'], $p_row_news);/* edit: Tytv - 26/09/2017 - toi_uu_code_xu_ly_video */
    $v_is_trang_bai_viet  = $p_param_extension['v_is_trang_bai_viet'];
    $v_is_box_video_chon_loc = $p_param_extension['v_is_box_video_chon_loc'];
    if($v_gia_tri_play_video == 3 && $v_is_trang_bai_viet && $v_is_box_video_chon_loc){
        $v_gia_tri_play_video = 1;
    }
    $p_param_extension['v_che_do_play_video'] = $v_gia_tri_play_video;
    // Bổ sung thêm ngày xuất bản
    $p_param_extension['PublishedDate2'] = $p_row_news['PublishedDate2'];
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    return $p_param_extension;
}
/*
* Hàm kiêm tra có phải mà mã quảng cáo write không
* Author : anhpt1
* param $p_body : Nội dung bài viết
* return true or false
*/
function _vd_check_is_quang_cao_write($p_body,$p_return_type = false){
    // Lấy cấu hình mã quảng cáo
    $v_arr_ma_quang_cao = _get_module_config('cau_hinh_dung_chung', 'v_arr_ma_quang_cao');
    if(!check_array($v_arr_ma_quang_cao)){return false;}
    // Check xem có tồn tại quảng cáo trong mảng
    foreach($v_arr_ma_quang_cao as $v_quang_cao){
        $v_dau_hieu = $v_quang_cao['c_dau_hieu'];
		//Begin 07-09-2017 : Thangnb toi_uu_xu_ly_video_tuong_thuat
		$v_dau_hieu_bai_tuong_thuat = $v_quang_cao['c_dau_hieu_bai_tuong_thuat'];
        // Nếu là mã của quảng cáo write thì trả về true
        if($p_body != '' && (strpos($p_body, $v_dau_hieu) !== false || strpos($p_body, $v_dau_hieu_bai_tuong_thuat) !== false)){
            if($p_return_type){
                return $v_quang_cao['c_type'];
            }else{
                return true;
            }
        }
		//End 07-09-2017 : Thangnb toi_uu_xu_ly_video_tuong_thuat
    }
	if(!$p_return_type && _vd_is_video_rename($p_body)){
		return true;
	}
    return false;
}
/**
 * Hàm lấy các thông sô quảng cáo từ chuỗi xmlads
 * @param array $xmlAds : Chuỗi xml chứa các thông tin về quảng cáo
 * @return array
 */
function _24h_player_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds) {
    if(empty($xmlAds)) return array();
    // loại bỏ ký tự xuống dòng
    $xmlAds = str_replace("\n", '', $xmlAds);
    // lấy các thông số quảng cáo trong từng DOM của
    preg_match('#<corner>(.*)</corner>#', $xmlAds, $corner);
    preg_match('#<before>(.*)</before>#', $xmlAds, $before);
    preg_match('#<overlay>(.*)</overlay>#', $xmlAds, $overlay);
    preg_match('#<preroll>(.*)</preroll>#', $xmlAds, $preroll);
    preg_match('#<end>(.*)</end>#', $xmlAds, $end);
    $v_logo     = ($corner[1]!='' && $corner[1]!='/') ? $corner[1] : '';
    $v_before   = $before[1];
    $v_overlay  = $overlay[1];
    $v_preroll  = $preroll[1];
    $v_end      = $end[1];

    // thiết lập mảng trả về
    $v_arr_data_ads =array(
        'v_logo'=>$v_logo,
        'v_before'=>$v_before,
        'v_overlay'=>$v_overlay,
        'v_preroll'=>$v_preroll,
        'v_end'=>$v_end,
    );
    return $v_arr_data_ads;
}
/**
 * hàm link ga dựa vào mã loại giải đấu của chiến dịch nivea
 * @author:
 */
function _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_body){
    // Loại giải đấu nivea
    $v_arr_loai_giai_dau_nivea = _vd_arr_campain_special();
    if(check_array($v_arr_loai_giai_dau_nivea)){
        // lặp loại giải đấu
        foreach($v_arr_loai_giai_dau_nivea as $v_arr_loai){
            $v_ma_giai_dau = $v_arr_loai['c_code'];
            // Nếu tồn tại loại giải đấu
            if(strpos($p_body, $v_ma_giai_dau) !== false){
                return $v_ma_giai_dau;
            }
        }
    }
    return '';
}
/**
 * hàm lấy file vast preroll theo chiến dịch
 * @param string $p_loai_giai_dau mã loại giải đấu
 * @param arr $p_file_preroll file preroll
 */
function _24h_player_lay_file_vast_preroll_theo_chien_dich($p_loai_giai_dau,$p_file_preroll){
    // Nếu không có loại giải đấu thì trả về preroll như hiện tại
    if($p_loai_giai_dau == ''){ return $p_file_preroll;}
    $p_file_preroll = get_gia_tri_danh_muc_dung_chung($p_loai_giai_dau,'DUONG_DAN_FILE_VAST_DFP_PREROLL_PC');
    return $p_file_preroll;
}
/**
 * hàm lấy file vast overlay theo chiến dịch
 * @param string $p_loai_giai_dau mã loại giải đấu
 * @param arr $p_file_overlay file overlay
 */
function _24h_player_lay_file_vast_overlay_theo_chien_dich($p_loai_giai_dau,$p_file_overlay){
    // Nếu không có loại giải đấu thì trả về preroll như hiện tại
    if($p_loai_giai_dau == ''){ return $p_file_overlay;}
    $p_file_overlay = get_gia_tri_danh_muc_dung_chung($p_loai_giai_dau,'DUONG_DAN_FILE_VAST_DFP_OVERLAY');
    return $p_file_overlay;
}
/**
 * hàm lấy file vast overlay theo chiến dịch
 * @param string $p_loai_giai_dau mã loại giải đấu
 * @param arr $p_file_overlay file overlay
 */
function _24h_player_lay_file_vast_postroll_theo_chien_dich($p_loai_giai_dau,$p_file_postroll){
    // Nếu không có loại giải đấu thì trả về preroll như hiện tại
    if($p_loai_giai_dau == ''){ return $p_file_postroll;}
    $p_file_postroll = get_gia_tri_danh_muc_dung_chung($p_loai_giai_dau,'DUONG_DAN_FILE_VAST_DFP_POSTROLL');
    return $p_file_postroll;
}
/**
 * Hàm thực hiện thiết lập các thông số quảng cáo cho video outside
 * @param array $p_url_video : Chuỗi đường dẫn video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _24h_player_thiet_lap_thong_so_quang_cao_video_outsite($p_url_video,$p_row_news, $p_row_cat, $p_param_extension) {
	$v_start_time = microtime(true);
	$v_start_mem = memory_get_usage();
	// Thực hiện lấy các thông số quảng cáo video theo độ ưu tiên
    // ưu tiên 1: video đối tác + banner đối tác nhập trong tool quan ly video doi tac
    $v_region           = $p_param_extension['v_region_value'];
    $v_type_video       = $p_param_extension['v_type_video'];
    $v_type_quang_cao   = intval($p_param_extension['v_type_quang_cao']);
    $v_is_box_video_chon_loc  = $p_param_extension['v_is_box_video_chon_loc'];
    $v_url_news = $p_param_extension['v_url_news'];
    $v_cat_id = $p_row_cat['ID'];

    // lấy các thông số cấu hình
    $v_arr_loai_video = _get_module_config('get_video_outsite', 'v_arr_loai_video');
    $v_arr_ma_quang_cao = _get_module_config('cau_hinh_dung_chung', 'v_arr_ma_quang_cao');

    // khởi tạo biến mặc định cho quảng cáo
    $v_logo     = '';
    $v_before   = '';
    $v_overlay  = '';
    $v_preroll  = '';
    $v_end      = '';
    $v_ga_code  = '';
    $v_ma_nguon_source = $v_arr_loai_video[$v_type_video]['c_ten'];
    $v_ga_play_video = '';
    // Begin: tytv - 1/11/2017 - fix_loi_hien_thi_quang_cao_khong_dung_chien_dich
    if(trim($v_type_video)=='quang_cao_Write' || trim($v_type_video)=='quangcaoWrite'){// là bài video từ bài tường thuật
		$v_is_quang_cao_write = _vd_check_is_quang_cao_write($v_type_video);
		$v_type_quang_cao   = _vd_check_is_quang_cao_write($v_type_video,true);
	}else{// nếu là loại video kiểu số
		if(!empty($v_ma_nguon_source)){ // nếu có mã nguồn loại video được xác định từ loại video
			$v_is_quang_cao_write = _vd_check_is_quang_cao_write($v_ma_nguon_source);
		}else{
			$v_is_quang_cao_write = _vd_check_is_quang_cao_write($p_url_video);
		}
		$v_type_quang_cao   = intval($v_type_video);
	}
    // End: tytv - 1/11/2017 - fix_loi_hien_thi_quang_cao_khong_dung_chien_dich
    log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 1 : Xu ly PHP ham _24h_player_thiet_lap_thong_so_quang_cao_video_outsite . Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    // lấy dữ liệu quảng cáo
    $v_cat_id_banner = lay_chuyen_muc_layout_quang_cao($p_row_news,$v_is_quang_cao_write);
    if(!$v_cat_id_banner && ($v_is_quang_cao_write || _vd_is_video_doi_tac($v_type_video))){
		$v_start_time = microtime(true);
		$v_start_mem = memory_get_usage();
        $v_ma_loai_giai_dau = _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_url_video);
        // nếu giải đấu là ngoại hạng anh thì thay thế file vast riêng
        $v_preroll = _24h_player_lay_file_vast_preroll_theo_chien_dich($v_ma_loai_giai_dau,$v_preroll);
        $v_overlay  = _24h_player_lay_file_vast_overlay_theo_chien_dich($v_ma_loai_giai_dau,$v_overlay);
        $v_end      = _24h_player_lay_file_vast_postroll_theo_chien_dich($v_ma_loai_giai_dau,$v_end);
        // xác định GA
        if (check_array($xmlAds)) {
            $v_ga_code 	= $xmlAds[0]['c_ga_code'];
        }
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 2 : Xu ly PHP ham _24h_player_thiet_lap_thong_so_quang_cao_video_outsite . Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    }else{
        $v_type_video = intval($v_type_video);
        $v_type_quang_cao = $v_type_video;
        $v_start_time = microtime(true);
        $v_start_mem = memory_get_usage();
        if ($v_type_video >= 0) {
            // lay video quang cao theo chuyên mục nếu xác định rõ loại video ( > 0 )
            $v_ten_key_quang_cao = ($v_is_box_video_chon_loc)? TEN_KEY_QUANG_CAO_VIDEO_CHON_LOC : TEN_KEY_QUANG_CAO_VIDEO;
            /* End: 17-06-2020 TuyenNT dieu_chinh_co_che_hien_thi_quang_cao_tren_video_noi_dung */
            $xmlAds = getKeyValue($v_ten_key_quang_cao.$v_cat_id_banner.'_'.$v_region, _CACHE_TABLE_QUANG_CAO);
            // lấy thông số quảng cáo từ chuỗi xml ads
            $v_arr_data_xmlAds = _24h_player_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds);
            $v_logo     = $v_arr_data_xmlAds['v_logo'];
            $v_before   = $v_arr_data_xmlAds['v_before'];
            $v_overlay  = $v_arr_data_xmlAds['v_overlay'];
            $v_preroll  = $v_arr_data_xmlAds['v_preroll'];
            $v_end      = $v_arr_data_xmlAds['v_end'];
        }
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 4 : Xu ly Quang cao OCM. Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    }

    # XLCYCMHENG-40731 - player - preroll - vast - add inventory_scope
    $v_preroll = _24h_player_ads_add_plus_params($v_preroll, ['row_news' => $p_row_news], 'pc');

    // thực hiện tối ưu các tham số quảng cáo
    _24h_player_toi_uu_thong_so_quang_cao($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);

    $v_arr_data_ads = array(
        'v_type_quang_cao'=>$v_type_quang_cao,
        'v_ma_loai_giai_dau'=>$v_ma_loai_giai_dau,
        'v_ads_preroll'=>$v_preroll,
        'v_ads_overlay'=>$v_overlay,
        'v_ads_postroll'=>$v_end,
        'v_ads_ga_code'=>$v_ga_code,
        'v_ma_nguon_source'=>$v_ma_nguon_source,
    );
    return $v_arr_data_ads;
}
/**
 * Hàm lấy chế độ xem video theo chuyên mục
 * @params $p_cate_id  Id chuyên mục
 * @return int giá trị quy ước theo cấu hình
 */
function _vd_get_che_do_play_video($p_cate_id = '', $p_row_news = []) {
	$v_gia_tri_play_video = 1;
	return $v_gia_tri_play_video;
}
/**
 * Hàm lấy các thông sô quảng cáo từ chuỗi xmlads
 * @param string  $v_before : Chuỗi before của dữ liệu quảng cáo
 * @param string  $v_preroll : Chuỗi before của dữ liệu quảng cáo
 * @param string  $v_overlay : Chuỗi before của dữ liệu quảng cáo
 * @param string  $v_end : Chuỗi before của dữ liệu quảng cáo
 * @param string  $v_url_news : Url bài viết
 * @param string  $v_region_value : Mã region
 * @param string  $v_ma_nguon_source : Mã nguồn video
 * @return Giá trị trả về được gán vào biến con trỏ
 */
function _24h_player_toi_uu_thong_so_quang_cao(&$v_before,&$v_preroll,&$v_overlay,&$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source){
    $v_before   = ($v_before != '' && $v_before != '/') ? $v_before : FLASH_VIDEO_DEFAULT;
    if (strpos($v_url_news,'http') === false) {
        $v_url_prefer = BASE_URL_FOR_PUBLIC.ltrim($v_url_news,'/');
    } else {
        $v_url_prefer = ltrim($v_url_news,'/');
    }
    // nếu là US thì chuyển về link US
    $v_url_prefer = ($v_region_value == 'us')?str_replace(BASE_URL_FOR_PUBLIC,BASE_URL_FOR_PUBLIC_US,$v_url_prefer):$v_url_prefer;
    $v_url_prefer = urlencode($v_url_prefer);
    $v_arr_link_quang_cao = _24h_player_thay_the_tham_so_tren_link_quang_cao($v_url_prefer, $v_ma_nguon_source, $v_preroll, $v_overlay, $v_end);
    $v_preroll  = $v_arr_link_quang_cao[0];
    $v_overlay  = $v_arr_link_quang_cao[1];
    $v_end      = $v_arr_link_quang_cao[2];

    // Gán thêm tham số ambient = 1
    $v_preroll = xu_ly_qc_ambient_cho_zplayer($v_preroll, true);
    $v_overlay = xu_ly_qc_ambient_cho_zplayer($v_overlay, true);
    $v_end = xu_ly_qc_ambient_cho_zplayer($v_end, true);
}
/** Author: Tytv 09/03/2017
 * Hàm tạo link url chi tiêt video
 * @return string
 */
function _vd_create_url_video_news($p_row_news,$p_row_cat_video,$urlHelper = false) {
    if(!$urlHelper){
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
    }
    $p_row_news['CategoryID'] = $p_row_cat_video['ID'];
    $v_slug_video = get_news_slug($p_row_news, $p_row_cat_video); // Lay slug bai viet
    $v_url_video_news = $urlHelper->url_video_news(array('ID'=>$p_row_news['ID'], 'cID'=>$p_row_cat_video['ID'], 'slug'=>$v_slug_video, 'VideoCode' => $p_row_news['VideoCode']));
    return $v_url_video_news;
}
/*
 *Hàm kiểm tra có phải video euroWrite2016
 *param : $p_body  nội dung cần kiểm tra
 *return: $p_tuong_thuat
*/
function _vd_is_euroWrite2016($p_body){
	if ($p_body == ''){
		return false;
	}
    if (strpos($p_body,'euroWrite2016') !== false) {
		return true;
	}
	return false;
}
/**
 * Ham unique mot array theo mot truong
 * @param array $array: array can unique
 * @param string $key: truong can unique
 * @return string
 * @author: none @date: none @desc: create new
 */
function _vd_array_unique_key_video_chon_loc(&$array,$key,$p_number_items)
{
    // kiem tra có phải là video có tên cần loại bỏ trên us không
    $v_region = get_region_value();

    if (!is_array($array)) {
        return;
    }
    $temp_array = array();
    foreach ($array as $v) {
		if (!isset($temp_array[$v[$key]])){
            // Bổ sung thêm nếu là bản us và có
            if ((_vd_config_video_code_has_exits_in_string($v['Body']) && strtolower($v_region) == 'us') || strpos($v['Body'],'eplayer.js') !== false) {
                continue;
            }
            $temp_array[$v[$key]] = $v;
        }
        // Nếu đã lấy đủ số lượng thì không lặp nữa
        if(count($temp_array) == $p_number_items){
            break;
        }
    }
    $array = array_values($temp_array);
}
/*
* 20150528_haiLT_video_doi_tac
* check kiểu video có phải của đối tác hay không
* param p_type: kiểu video
*/
function _vd_is_video_doi_tac($p_type) {
    $p_type = intval($p_type);

	return ($p_type > 0 && $p_type < 999);
}
/*
* Author : 21-01-2016 Thangnb
* Ham thay the tham so tren link quang cao
* param : $p_url_prefer : Link hien tai tren trinh duyet
			$p_source_video : Nguon video (Ballball, 24h)
			$p_preroll : Link quang cao preroll
			$p_overlay : Link quang cao overlay
			$p_end : Link quang cao postroll
* return : Array
*/
function _24h_player_thay_the_tham_so_tren_link_quang_cao($p_url_prefer, $p_source_video, $p_preroll, $p_overlay, $p_end) {
	$date = new DateTime();
	$v_timestamp = $date->getTimestamp();

	$p_preroll = str_replace('{{url}}', $p_url_prefer, $p_preroll);
	$p_preroll = str_replace('{{video_type}}', 'preroll', $p_preroll);
	$p_preroll = str_replace('{{source_id}}', $p_source_video, $p_preroll);

	$p_preroll = str_replace('[url_prefer]', $p_url_prefer, $p_preroll);
	$p_preroll = str_replace('[description_url]', $p_url_prefer, $p_preroll);
	$p_preroll = str_replace('[timestamp]', $v_timestamp, $p_preroll);
	$p_preroll = str_replace('[referrer_url]', $p_url_prefer, $p_preroll);

	$p_overlay = str_replace('{{url}}', $p_url_prefer, $p_overlay);
	$p_overlay = str_replace('{{video_type}}', 'overlay', $p_overlay);
	$p_overlay = str_replace('{{source_id}}', $p_source_video, $p_overlay);

	$p_overlay = str_replace('[url_prefer]', $p_url_prefer, $p_overlay);
	$p_overlay = str_replace('[timestamp]', $v_timestamp, $p_overlay);
	$p_overlay = str_replace('[referrer_url]', $p_url_prefer, $p_overlay);
	$p_overlay = str_replace('[description_url]', $p_url_prefer, $p_overlay);

	$p_end = str_replace('{{url}}', $p_url_prefer, $p_end);
	$p_end = str_replace('{{video_type}}', 'postroll', $p_end);
	$p_end = str_replace('{{source_id}}', $p_source_video, $p_end);

	$p_end = str_replace('[url_prefer]', $p_url_prefer, $p_end);
	$p_end = str_replace('[timestamp]', $v_timestamp, $p_end);
	$p_end = str_replace('[referrer_url]', $p_url_prefer, $p_end);
	$p_end = str_replace('[description_url]', $p_url_prefer, $p_end);

	$p_preroll = str_replace('{{custom_params}}', 'source%3D'.$p_source_video, $p_preroll);

	return array($p_preroll, $p_overlay, $p_end);
}
/** Author: Tytv 08/12/2016
 * Hàm kiểm tra bài viết có video hay không?
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return boolean
 */
function _vd_kiem_tra_bai_viet_co_video($p_body) {
    /* begin 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
    if ((strpos($p_body, 'flashWrite')!== false) || (strpos($p_body, 'antvWrite')!== false) || (strpos($p_body, 'ballball')!== false) || (strpos($p_body, 'vtvWrite')!== false) || (strpos($p_body, 'quangcaoWrite')!== false) || (strpos($p_body, 'ballballWrite2')!== false) || (strpos($p_body, 'emobi_write')!== false)) {
    /* end 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
        return true;
    }
    return false;
}
/*
* Ham nhan biet 1 bai viet co chua video hay khong
* Author :Thangnb 09/06/2015
*/
function _vd_is_video_news($row_news) {
	// neu la bai tuong thuat
	if (preg_match("#<!--tuongthuattructiep_(\d+)-->#", $row_news['Body'], $matchs)) {
        $tuongthuatID = intval($matchs[1]);
        $tuongthuatData = Gnud_Db_read_get_key('tuongthuattructiep_'.$tuongthuatID);
        $row_news['Body'] = str_replace( $matchs[0], $tuongthuatData, $row_news['Body']);
    }
	/* Begin 17-02-2017 : Thangnb co_che_dem_nguoc_video */
    /* begin 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
	if(strpos($row_news['Body'], 'flashWrite') || strpos($row_news['Body'], 'antvWrite') || strpos($row_news['Body'], 'ballball') || strpos($row_news['Body'],'vtvWrite') || ($row_news['VideoCode'] != '') || ($row_news['video_code'] > 0) || ($row_news['Video_code'] > 0) || strpos($row_news['Body'], 'quangcaoWrite') || strpos($row_news['Body'], 'emobi_write')) {
	/* end 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
        return true;
	} else {
		return false;
	}
	/* End 17-02-2017 : Thangnb co_che_dem_nguoc_video */
}
/* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
/**
 * Hàm thực hiện thiết lập banner sponser video cho video
 * @return array
 */
function _vd_thiet_lap_banner_sponsor_video($p_code_video,$p_row_news, $p_row_cat,$p_param_extension){
    // lấy cấu hình banner
    $v_is_box_video_chon_loc = $p_param_extension['v_is_box_video_chon_loc'];
    if(!$v_is_box_video_chon_loc && _hien_thi_banner_san_pham($p_row_cat['ID'],$p_row_news['c_list_ma_content'])){
        $v_str_html_banner_sponser_video = _get_module_config('cau_hinh_dung_chung', 'v_str_html_banner_sponser_video');
        // thay thế vị trí banner cho video đầu tiên
        $p_code_video = preg_replace('#<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->#', '<!--Begin:position_banner_sponsor_video-->'.$v_str_html_banner_sponser_video.'<!--End:position_banner_sponsor_video-->', $p_code_video, 1);
    }
    return $p_code_video;
}
/*
* Ham check co phai bai tuong thuat co video hay khong
* params : $row_news : mang bai viet
* return : true or false
*/
function _vd_is_video_tuong_thuat($row_news) {
	// neu la bai tuong thuat
	if (preg_match("#<!--tuongthuattructiep_(\d+)-->#", $row_news['Body'], $matchs)) {
        $tuongthuatID = intval($matchs[1]);
        $tuongthuatData = Gnud_Db_read_get_key('tuongthuattructiep_'.$tuongthuatID);
        $row_news['Body'] = str_replace( $matchs[0], $tuongthuatData, $row_news['Body']);
    }
	//Begin 07-09-2017 : Thangnb toi_uu_xu_ly_video_tuong_thuat
	if (strpos($row_news['Body'], 'openVideoTuongThuat')) {
		return true;
	} else {
		return false;
	}
	//End 07-09-2017 : Thangnb toi_uu_xu_ly_video_tuong_thuat
}
/** Tytv - 21/02/2017 -  Hàm tạo html hien thi thời lượng video của bài viết
* @param $p_row_news    Mảng bài viết
* @return string Html
*/
function _vd_html_lay_thoi_luong_video_trong_bai ($p_row_news){
    $v_html_thoi_luong_video = '';
    if(!check_array($p_row_news)){
        return $v_html_thoi_luong_video;
    }
    /* Begin: Tytv - 31/08/2017 - fix_loi_luu_key_redis_file_video_upload */
    $v_moc_ngay_lay_thong_tin_file_video = _get_module_config('cau_hinh_dung_chung', 'v_moc_ngay_lay_thong_tin_file_video');
    $v_time_moc = strtotime($v_moc_ngay_lay_thong_tin_file_video);
    $v_ngay_bai_viet = ((empty($p_row_news['PublishedDate2']))?$p_row_news['PublishedDate']:$p_row_news['PublishedDate2']);
    $v_time_news = strtotime($v_ngay_bai_viet);
    if($v_time_news<$v_time_moc){
        return $v_html_thoi_luong_video;
    }
    /* End: Tytv - 31/08/2017 - fix_loi_luu_key_redis_file_video_upload */
    $row_news = $p_row_news;
    $v_body = (!empty($row_news['VideoCode']))? $row_news['VideoCode']:$row_news['Body'];
    // chỉ lấy video đầu tiên của bài viết
    $v_body = _vd_lay_ma_script_video_trong_bai($v_body,0);

    if(strpos($v_body,'antvWrite') !==false){ // không hiển thị thời lượng video
        return $v_html_thoi_luong_video;
    }else{
        $v_str_video = _vd_lay_link_video_trong_script($v_body);
        if(!empty($v_str_video)){
            $v_arr_str_video    = explode(',', trim($v_str_video));
            if(!check_array($v_arr_str_video)){
                return $v_html_thoi_luong_video;
            }
            $v_arr_info_file_video = array();
            foreach ($v_arr_str_video as $key => $value) {
                $v_arr_expl_tmp = explode('/', trim($value));
                $v_ten_video = end($v_arr_expl_tmp);
                // lấy thông tin chi tiết 1 video từ key data
                $v_arr_info_file_video[$key] = fe_chi_tiet_1_file_video_upload($v_ten_video);
            }
            // tính tổng thời lượng các video trong zplayer
            $v_tong_thoi_luong = 0;
            if(check_array($v_arr_info_file_video)){
                $v_date_now = date('Y-m-d');
                $v_str_time_now = strtotime($v_date_now.' 00:00:00');
                foreach ($v_arr_info_file_video as $key => $value) {
                    if(check_array($value)){
                        $v_str_time = strtotime( $v_date_now.' 00:'.$value['c_thoi_luong']);
                        $v_tong_thoi_luong += $v_str_time-$v_str_time_now;
                    }
                }
            }
           $v_html_thoi_luong_video = (($v_tong_thoi_luong>0)?date('i:s',$v_tong_thoi_luong):'');
        }
    }
    return $v_html_thoi_luong_video;
}
/** Author: Tytv 08/12/2016
 * Hàm lấy link video trong bài viết
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return boolean
 */
function _vd_lay_link_video_trong_script($p_script) {
    preg_match('#<script ([^\>]*)>(\w[^\(]*)#', $p_script,$v_matches);
    $v_str_link_video = '';
    if(!empty($v_matches[2])){
        switch (trim($v_matches[2])) {
            case 'quangcaoWrite':
                preg_match('#file=([^,]*),#', $p_script,$v_matches1);
                $v_str_link_video = trim($v_matches1[1]);
                $v_str_link_video = trim($v_str_link_video,'"');
                $v_str_link_video = trim($v_str_link_video,"'");
                break;
            case 'vtvWrite':
                preg_match('#vtvWrite\(([^\)]*)\);#', $p_script,$v_matches1);
                $v_str_link_video = trim($v_matches1[1]);
                $v_str_link_video = trim($v_str_link_video,'"');
                $v_str_link_video = trim($v_str_link_video,"'");
                break;
            case 'flashWrite':
                preg_match('#flashWrite\(\"([^\"]*)\"#', $p_script,$v_matches1);
                $v_str_link_video = trim($v_matches1[1]);
                if(strpos($v_str_link_video,'file=')===false){
                    $v_str_link_video = trim($v_str_link_video);
                }else{
                    $v_arr_link_video_tmp = explode('file=', $v_str_link_video);
                    $v_str_link_video = $v_arr_link_video_tmp[1];
                }
                break;
            case 'euroWrite2016':
                preg_match('#euroWrite2016\(\"([^\"]*)\"#', $p_script,$v_matches1);
                $v_str_link_video = trim($v_matches1[1]);
                break;
            case 'antvWrite':
                preg_match('#antvWrite\(\'([^\']*)\'#', $p_script,$v_matches1);
                $v_id_video = trim($v_matches1[1]);
                // lấy link video tư đối tác
                if(intval($v_id_video)>0){
                    $v_html_script_video = '';
                    ob_start();
                    $v_obj = new box_get_video_outsite_block();
                    $v_player_id = 'div_video_antv_'.time();
                    $v_obj->index(1,$v_id_video,$v_player_id,WIDTH_ZPLAYER_TRANG_VIDEO, HEIGHT_ZPLAYER_TRANG_VIDEO);
                    $v_html_script_video = ob_get_clean();
                    preg_match('#<!-- begin:script_video_outsite -->(.*?)<!-- end:script_video_outsite -->#ism', $v_html_script_video, $v_arr_matches_video_outsite);
                    // thực hiện tách lấy các thông số của hàm script _get_video_outsite
                    if(check_array($v_arr_matches_video_outsite)){
                        preg_match('#_get_video_outsite\(([^\)]*)\);#ism', $v_arr_matches_video_outsite[1], $v_arr_matches_video);
                        $v_str_tham_so = trim($v_arr_matches_video[1]);
                        $v_arr_tham_so = explode(',', $v_str_tham_so);
                        $v_type = intval($v_arr_tham_so[0]);
                        $v_id_player = trim($v_arr_tham_so[1]);
                        $v_id_player = trim($v_id_player,"'");
                        $v_url_get_video = trim($v_arr_tham_so[2]);
                        $v_url_get_video = trim($v_url_get_video,"'");

                        $v_str_link_video = $v_url_get_video;
                        // loại bỏ hậu tố của link video như (/index.xxxx)
                        $v_arr_tmp_vd = explode(',',$v_str_link_video);
                        $v_arr_tmp_vd1 = array();
                        foreach ($v_arr_tmp_vd as $value) {
                            $v_arr_tmp_vd2 = explode('/index.', $value);
                            $v_arr_tmp_vd1[] = $v_arr_tmp_vd2[0];
                        }
                        $v_str_link_video = implode(',', $v_arr_tmp_vd1);
//                        pre($v_str_link_video);die;
                    }
                }
                break;
            /* begin 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
            case 'emobi_write':
                preg_match('#emobi_write\(([^\)]*)\);#', $p_script,$v_matches1);
                $v_str_link_video = trim($v_matches1[1]);
                $v_str_link_video = trim($v_str_link_video,'"');
                $v_str_link_video = trim($v_str_link_video,"'");
                break;
            /* end 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
            default:
                break;
        }
    }
    return $v_str_link_video;
}
/* Tytv - 23/12/2016
 * Hàm thực hiện lấy mã sctip video trong bai viet theo vi tri cần lấy
 * @pram: $p_body       nội dung
 * @param: $p_vi_tri    Vị trí mã script cần lấy thứ tự tính từ 0
 * return: boolean
 */
function _vd_lay_ma_script_video_trong_bai($p_body,$p_vi_tri=-100){
    $v_script = '';
    if(!empty($p_body)){
        $p_vi_tri = intval($p_vi_tri);
        preg_match_all('#<script ([^\>]*)>([^\<]*)<\/script>#', $p_body, $v_arr_match_all);
        if(check_array($v_arr_match_all[0])){
            // loại bỏ script ko phải script video
            $v_arr_script_video = array();
            if(check_array($v_arr_match_all[2])){
                $v_stt = 0;
                foreach ($v_arr_match_all[2] as $key => $value) {
                    if(_vd_kiem_tra_bai_viet_co_video($value)){
                        $v_arr_script_video[0][$v_stt] = $v_arr_match_all[0][$key];
                        $v_arr_script_video[1][$v_stt] = $v_arr_match_all[1][$key];
                        $v_arr_script_video[2][$v_stt] = $v_arr_match_all[2][$key];
                        $v_stt ++;
                    }
                }
            }
            // lấy mã script video theo tham số truyền vào
            if($p_vi_tri<0){ // lay tat ca cac script video
                if(check_array($v_arr_script_video[2])){
                    $v_arr_tmp = array();
                    foreach ($v_arr_script_video[2] as $key => $value) {
                        if(!empty($value)){
                            $v_arr_tmp[] = $v_arr_script_video[0][$key];
                        }
                    }
                    $v_script = implode('<br/>', $v_arr_tmp);
                }
            }else{ // lay theo thu tu truyen vao
                $v_script = $v_arr_script_video[0][$p_vi_tri];
            }
        }
    }
    return $v_script;
}
/** Author: Tytv 06/02/2017
 * Hàm thực hiện lấy ảnh đại diện video html theo cơ chế:
    + Ưu tiên 1: Ảnh được cắt từ video (file ảnh là file video đầu tiên)
    + Ưu tiên 2: Nếu video không có ảnh cắt thì lấy ảnh upload cho video trên trang chủ
    + Ưu tiên 3: nếu không có ảnh upload cho video trên trang chủ thì lấy ảnh đại diện mặc định của video là màn hình xanh
 * @return string
 */
function _vd_lay_anh_dai_dien_video_zplayer_html5($row_news,$p_body = ''){ /* edit: Tytv - 16/06/2017 - fix_loi_anh_dai_dien_video_trong_bai_chua_nhieu_video */
    if(!check_array($row_news)) return '';
    $v_video_image = '';
    /* Begin: Tytv - 23/06/2017 - fix_loi_anh_dai_dien_box_xem_video_trang_video */
	global $is_box_xem_video_trang_video;
    /* Begin: Tytv - 16/06/2017 - fix_loi_anh_dai_dien_video_trong_bai_chua_nhieu_video */
    if(!empty($p_body) && !$is_box_xem_video_trang_video){
	/* End: Tytv - 23/06/2017 - fix_loi_anh_dai_dien_box_xem_video_trang_video */
        $v_body = $p_body;
    }else{
        $v_body = (!empty($row_news['VideoCode']))? $row_news['VideoCode']:$row_news['Body'];
    }
    /* End: Tytv - 16/06/2017 - fix_loi_anh_dai_dien_video_trong_bai_chua_nhieu_video */
    $v_body = _vd_lay_ma_script_video_trong_bai($v_body,0);
	if(empty($v_body)) return '';
	if(strpos($v_body, 'antvWrite') !==false){ // chỉ lấy theo ưu tiên 2,3
        $v_video_image = html_image($row_news['video_homepage_image'],false);
    }else{
        $v_flag = false;
        $v_date_create_news_video = empty($row_news['DateCreated'])?$row_news['Date']:$row_news['DateCreated'];
        $v_id_news_video = $row_news['ID'];
        $v_ngay_hien_thi = _get_module_config('cau_hinh_dung_chung', 'v_ngay_hien_thi_anh_dai_dien_video');
        // on/off tu dong lay anh dai dien video
        $v_on_off_auto_get_image_video = _get_module_config('cau_hinh_dung_chung', 'v_on_off_auto_get_image_video');

        if(($v_ngay_hien_thi > $v_date_create_news_video) || !$v_on_off_auto_get_image_video){
           $v_flag = true;
        }
        // Cấu hình ngày hiển thị ảnh đại diện video
        $v_id_bai_viet_moi_nhat = _get_module_config('cau_hinh_dung_chung', 'v_id_bai_viet_moi_nhat_khi_trien_khai');
        if($v_id_bai_viet_moi_nhat > $v_id_news_video){
          $v_flag = true;
        }
        if($v_flag){ // bài đã quá cũ (ko có ảnh cắt tự động từ clip) hoặc bị off ...
            $v_video_image = $row_news['video_homepage_image'];
        }else{
            $v_str_video = _vd_lay_link_video_trong_script($v_body);
            if(!empty($v_str_video)){
                $v_arr_str_video    = explode(',', trim($v_str_video));
                $v_video_file      = trim($v_arr_str_video[0]);
                if(empty($v_video_file)){
                    $v_video_image = $row_news['video_homepage_image'];
                }else{
					$v_video_file = preg_replace('#http:.*.vn\/#','',$v_video_file);
                    //Tách phần tên file và phần mở rộng
                    $v_vi_tri_dau_cham_dau_tien = strpos($v_video_file, ".");
                    $v_duoi_anh_dai_dien_video = _get_module_config('cau_hinh_dung_chung', 'v_duoi_anh_dai_dien_video');
                    $v_file_image = substr($v_video_file,0,$v_vi_tri_dau_cham_dau_tien).$v_duoi_anh_dai_dien_video;
                    // doi duong dan sang thu muc anh
                    $v_file_image = str_replace('videoclip', 'images', $v_file_image);
                    // Lấy link ảnh chuẩn
                    $v_video_image = html_image($v_file_image,false);
                }
            }else{
                $v_video_image = $row_news['video_homepage_image'];
            }
        }
    }
    return (empty($v_video_image)?PLAYER_BG_IMAGE:$v_video_image);
}
/** Author: Tytv 08/12/2016
 * Hàm hiển thị code html player video đầu trang video
 * @param array $p_code_video : chuỗi có chứa code video
 * @param array $p_arr_extra_data : mảng chứa các dữ liệu của 1 bài viết
 * @return string
 */
function _vd_hien_thi_video_dau_trang_video($p_code_video,$p_arr_extra_data) {

    $row_news   = $p_arr_extra_data['row_news'];
    $row_cat    = $p_arr_extra_data['row_cat'];
    // chỉ lấy video đầu tiên của bài viết
    $p_code_video = _vd_lay_ma_script_video_trong_bai($p_code_video,0);

    // Lấy cấu hình theo bài viết
    $v_arr_configs_bai_viet = _lay_thong_so_cau_hinh_bai_viet($row_news,$row_cat);
    $v_param_extension_video = $v_arr_configs_bai_viet;
    // thiết lập thêm 1 số tham số cần thiết xác định là trang video
    $v_param_extension_video['v_is_trang_bai_viet'] = false;
    $v_param_extension_video['v_is_box_video_chon_loc'] = false;
    $v_param_extension_video['v_is_trang_video'] = true;
    $v_param_extension_video['v_is_box_xem_video_trang_video'] = true;
    // bổ xung 2 tham số để tạo url xác định video tiếp theo cho video đang phát trên trang video
    $v_param_extension_video['v_type_extraDataUrl'] = $p_arr_extra_data['type_extraDataUrl'];
    $v_param_extension_video['v_cat_video_id']      = $p_arr_extra_data['cat_video_id'];

    // kiểm code hiển thị video ở trang video là code video outside hay ko?
    if(_vd_check_is_code_video_outsite($p_code_video)){  // nếu video outside
        $p_code_video =  _vd_xu_ly_code_video_outsite_trang_video($p_code_video, $row_news, $row_cat, $v_param_extension_video);
    }else{ // nếu không phải là video outside
        $p_code_video = _vd_xu_ly_code_video_thong_thuong($p_code_video, $row_news, $row_cat, $v_param_extension_video);
    }
    return $p_code_video;
}
/**
 * Hàm kiểm tra code video là video outside hay không?
 * @param string $p_code Đoạn mã script video
 * @return string
 */
function _vd_check_is_code_video_outsite($p_code) {
    if((strpos($p_code,'vtvWrite')!=false) || (strpos($p_code,'emobi_write')!=false) || (strpos($p_code,'antvWrite')!=false) || (strpos($p_code,'videoDoiTacWrite')!=false)){
        return true;
    }
    return false;
}
/**
 * Hàm đầu vào xử lý code video thông thường (code script video không phải outside)
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _vd_xu_ly_code_video_thong_thuong($p_code_video,$p_row_news, $p_row_cat, $p_param_extension) {

    //B1: Thiết lập các giá trị cấu hình (tùy thuộc vào các trường hợp gọi mà bổ xung thêm các tham số ban đầu cần thiết)
    $v_arr_config_video = _vd_thiet_lap_thong_so_cau_hinh_video($p_row_news, $p_row_cat,$p_param_extension);
    /* Begin: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    $p_code_video = _24h_player_loai_bo_video_khong_duoc_hien_thi_sau_n_tieng($p_code_video,$v_arr_config_video);
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    //B2: Loại bỏ video không được phép hiển thị
    $p_code_video = _24h_player_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);

    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    $v_arr_data_ads = _24h_player_thiet_lap_thong_so_quang_cao($p_code_video,$p_row_news, $p_row_cat, $v_arr_config_video);

    //B4: Thiết lập dữ liệu gán tracking,ga
    $v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    $v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);

    //B5: Thay thế dạng script write tùy theo cấu hình
    $p_code_video = _24h_player_chuyen_doi_giua_cac_ma_script_video($p_code_video,$v_arr_config_video);

    //B6: Thiết lập dữ liệu highlight video
    $v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news);

    //B7: Gen mã html player
    $v_param_extension = array(
        'v_arr_config_video'=> $v_arr_config_video,
        'v_arr_data_ads'=>$v_arr_data_ads,
        'v_arr_data_tracking'=>$v_arr_data_tracking,
        'v_arr_data_highlight_video'=>$v_arr_data_highlight_video,
        'v_row_news'=>$p_row_news,
        'v_row_cat'=>$p_row_cat,
    );

    $v_str_html_player = _24h_player_tao_code_html_player($p_code_video,$v_param_extension);

    //B8: Thay thế các lời gọi player bằng script thành HTML
    $p_code_video = _24h_player_thay_the_code_html_cho_cac_loi_goi_video($p_code_video,$v_str_html_player,$v_param_extension);

    return $p_code_video;
}
/** Author: Tytv 08/12/2016
 * Hàm lấy link video trong bài viết
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return boolean
 */
function _vd_xac_dinh_class_cho_video_trong_box_video($p_vi_tri){
    $v_class = '1';
    $v_class = ($p_vi_tri==1)?'1':$v_class;
    $v_class = ($p_vi_tri==2)?'2':$v_class;
    $v_class = ($p_vi_tri==3)?'3':$v_class;
    return $v_class;
}
/** Author: Tytv 28/12/2016
 * Hàm hiển thị thông tin 1 video cua trang chuyen muc
 * @return string
 */
function _vd_html_thong_tin_1_video_trang_video($p_news,$p_url_helper,$p_row_cat,$p_class,$p_class_box,$p_gia_tri_loai_box,$news_id=0){
    $v_html = '';
    if(!check_array($p_news)){return $v_html;}
    $v_class    = $p_class;
    $row_news   = $p_news;
    $row_cat    = $p_row_cat;
    $urlHelper  = $p_url_helper;
    // lấy loại dữ liệu theo tên box truyền vào (dữ liệu được lấy đúng theo tên box)
    $v_type_data = _vd_lay_ma_loai_de_xac_dinh_du_lieu_cho_video_tiep_theo($p_class_box);

    // lay thong tin chi tiet ve chuyen muc
    $cat_id = intval($row_news['CategoryID']);
    if(!check_array($row_cat)){
        $row_cat = fe_chuyen_muc_theo_id($cat_id);
    }
    $v_slug = get_news_slug($row_news, $row_cat); // Lay slug bai viet
    $v_url_news = $urlHelper->url_news(array('ID'=>$row_news['ID'], 'cID'=>$row_cat['ID'], 'slug'=>$v_slug, 'VideoCode' => $row_news['VideoCode']));
	// lây thông tin chuyên mục video
    if($v_type_data==1){
        $row_cat_video_ax = fe_chi_tiet_chuyen_muc_video_theo_chuyen_muc_duoc_anh_xa($cat_id);
        $cat_video_id = intval($row_cat_video_ax['fk_chuyen_muc']);
    }else{
        $cat_video_id = intval($p_gia_tri_loai_box);
    }
    $cat_video_id = ($cat_video_id<=0)?ID_TRANG_VIDEO:$cat_video_id;
    $row_cat_video = fe_chuyen_muc_theo_id($cat_video_id);
    $v_che_do_xem_video = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TOAN_TRANG_CAC_PHIEN_BAN', 'CHE_DO_XEM_VIDEO_TRANG_TONG_HOP_VIDEO');
    if($v_che_do_xem_video == 1){
        // tao url video
        $v_url_video_news = _vd_create_url_video_news($row_news,$row_cat_video,$urlHelper);
        $v_url_video_news = _tao_link_day_du_domain($v_url_video_news,BASE_URL_FOR_PUBLIC);
    }else{
        $v_url_video_news = get_url_origin_of_news($row_news, $row_cat);
    }

    $v_url_playvideo = "javascript:runPlayVideoByLink('".$p_gia_tri_loai_box."x".$row_news['ID']."',$v_type_data)";
    $Title = fw24h_strip_tags($row_news['Title'], true);
    $row_news['Title'] = $Title;
	$Title = html_entity_decode($Title);

    $right_icon = '';
    $v_comment = fe_tong_so_comment_1_bai_viet($row_news['ID']);
    if ($v_comment > 0) {
        $right_icon .= "<span class=\"icon-cmt\">$v_comment</span>";
    }
    if($v_is_tuong_thuat = check_is_news_tuong_thuat($row_news['c_icon_truc_tiep'])){
        $right_icon .= $v_is_tuong_thuat;
    }
    $v_post_id = _vd_tao_ma_gia_tri_post_id_cho_player_video($v_url_news);//md5($row_news['ID']);//md5($v_url_news); // thiet_lap_gia_tri_post_id
	// lấy thời lượng của video
    $v_html_thoi_luong_video = _vd_html_lay_thoi_luong_video_trong_bai($row_news);

    # XLCYCMHENG-40459 - [24H] Điều chỉnh kích thước ảnh resize sử dụng ở trang Video tổng hợp
    $v_anh_dai_dien_video = $row_news['SummaryImg_chu_nhat'] == '' ? $row_news['SummaryImg'] : $row_news['SummaryImg_chu_nhat'];
	$v_arr_img= explode("/", $v_anh_dai_dien_video, 10);
	$v_ngay_thang = $v_arr_img[count($v_arr_img) - 2];		
    $thumbnail_name = '255x170';
	$get_image_thumbnail = strtotime($row_news['PublishedDate2']) > strtotime(date("2022-01-01 11:00:00")) && strtotime($v_ngay_thang) >  strtotime(date("2022-01-01 11:00:00"));
	
    $v_html .= ' <div class="lstVidCm'.$v_class.' lstVidCmNbm itmVidPl" data-file="'.$v_post_id.'">
                    <div class="mnVidImg">
                        <a href="'.$v_url_video_news.'" title="'.$Title.'">
                            '.html_image_thumbnail($v_anh_dai_dien_video, $v_anh_dai_dien_video, $row_news['Title'], $thumbnail_name, $get_image_thumbnail).'
                        </a>';
            if(!empty($v_html_thoi_luong_video)){
            $v_html .= '<div class="lgVid">
                           <span><a href="'.$v_url_video_news.'" title="'.$Title.'"><img src="'.html_image('images/arrow-video.png',false).'" width="11" height="12" alt="'.$Title.'"></a></span><span class="tmLg1">'.$v_html_thoi_luong_video.'</span>
                       </div>';
            }
                $v_html .= '<div class="dgChg" style="'.(($news_id>0 && $news_id==$row_news['ID'])?'display:block':'display:none').'">
                                <span>Đang phát ...</span>
                            </div>
                    </div>
                    <div class="mnVidCt">
                        <div class="mnVidBd">
                            <div class="vidBdTit">
                                <a href="'.$v_url_video_news.'"  title="'.$Title.'">'.$Title.$right_icon.'</a>
                            </div>
                        </div>
                    </div>
                </div>';
    if($v_class=='3'){$v_html .= '<div class="clF"></div>';}
    return $v_html;
}
/** Author: Tytv 10/01/2017
 * Hàm lấy link video trong bài viết
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return boolean
 */

function _vd_lay_ma_loai_de_xac_dinh_du_lieu_cho_video_tiep_theo($p_class_box){
    $v_type_data = 0;
    switch ($p_class_box) {
        case 'box_video_noi_bat_trang_video_cap1':
            $v_type_data = 1;
            break;
        case 'box_video_theo_chuyen_muc_trang_video':
        case 'box_video_moi_nhat_trang_video_cap2':
        case 'box_video_khac_trang_video_cap2':
            $v_type_data = 2;
            break;
        case 'box_video_noi_bat_trang_video_cap2':
            $v_type_data = 3;
            break;
        default:
            break;
    }
    return $v_type_data;
}
/** Author: Tytv 08/02/2017 thiet_lap_gia_tri_post_id
 * Hàm tạo mã giá trị postId cho video chạy html5
 * @param string chuỗi dữ liệu (có thể là chuối url bài viết hoặc ID bài viết)
 * @return string
 */
function _vd_tao_ma_gia_tri_post_id_cho_player_video($p_value) {
    $v_code = '';
    if(!empty($p_value)){
        if(is_numeric($p_value)){ // là id bài viết
            $v_code = md5($p_value);
        }else{ // dạng url bài viết
            if( preg_match('#a([0-9]+).html#', $p_value, $v_result)){
                $news_id = intval($v_result[1]);
                $v_code = md5($news_id);
            }
        }
        return $v_code;
    }
    return $v_code;
}
/**
 * Hàm đầu vào xử lý code video trang video đối với các loại video là video outsite (chạy qua box_play_video by_url)
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _vd_xu_ly_code_video_outsite_trang_video($p_code_video,$p_row_news, $p_row_cat, $p_param_extension) {

    // kiểm tra nếu bài viết có video emobi_write thì replace sang vtvWrite
    if(strpos($p_code_video,'emobi_write') !== false){
        $p_code_video = str_replace('emobi_write', 'vtvWrite', $p_code_video);
    }
    // thiết lập thông số để chuyển đổi code từ script video sang html player
    $v_gia_tri_play_video   = _vd_get_che_do_play_video($p_row_cat['ID'], $p_row_news);
    $v_is_autoplay          = ($v_gia_tri_play_video == 3)?1:0;
    $v_is_count_down        = ($v_gia_tri_play_video == 2)?1:0;
    $v_url_news             = $p_param_extension['v_url_news'];
    $v_is_bai_tuong_thuat   = $p_param_extension['v_is_bai_tuong_thuat'];
    // thiết lập tham số để phân biệt là trang video
    $v_is_trang_video       = $p_param_extension['v_is_trang_video'];
    $v_type_extraDataUrl    = $p_param_extension['v_type_extraDataUrl'];
    $v_cat_video_id         = $p_param_extension['v_cat_video_id'];

    $v_arr_extra_parram = array(
        'is_autoplay'=>$v_is_autoplay,
        'p_is_box_video_chon_loc'=>0,
        'p_anh_dai_dien_video'=>'',
        'p_url_now'=>$v_url_news,
        'p_is_bai_tuong_thuat'=>$v_is_bai_tuong_thuat,
        'p_is_count_down'=>$v_is_count_down,
        'p_no_is_in_iframe' => true,
        'p_is_trang_video'=>$v_is_trang_video,
        'p_type_extraDataUrl'=>$v_type_extraDataUrl,
        'p_cat_video_id'=>$v_cat_video_id,
    );
    if((strpos($p_code_video,'vtvWrite')!=false)){// loại video vtvWrite,emobi_write
        $v_start_time = microtime(true);
        $v_start_mem = memory_get_usage();
        // loại video vtvWrite,emobi_write
        $p_code_video = _vd_html_chuyen_doi_vtv_write_sang_zplayer($p_code_video, $p_row_cat['ID'],WIDTH_ZPLAYER_TRANG_VIDEO, HEIGHT_ZPLAYER_TRANG_VIDEO,$v_arr_extra_parram);
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "\n Xu ly chuyen doi code VTV WRITE tu nhung iframe sang chay code html5 truc tiep (trong _vd_xu_ly_code_video_outside_trang_video).",WEB_ROOT.'logs/log_chuyen_doi_vtvWrite_sang_zplayer.log');
    } else if((strpos($p_code_video,'antvWrite')!=false)){ // loại video antvWrite
        $v_start_time = microtime(true);
        $v_start_mem = memory_get_usage();
        $p_code_video = _vd_html_chuyen_doi_antv_write_sang_zplayer($p_code_video,$p_row_cat['ID'], WIDTH_ZPLAYER_TRANG_VIDEO, HEIGHT_ZPLAYER_TRANG_VIDEO,$v_arr_extra_parram);
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "\n Xu ly chuyen doi code ANTV WRITE tu nhung iframe sang chay code html5 truc tiep (trong _vd_xu_ly_code_video_outside_trang_video).  ",WEB_ROOT.'logs/log_chuyen_doi_antvWrite_sang_zplayer.log');
    }
    return $p_code_video;
}
/** Author: Tytv 08/12/2016
 * Hàm xử lý thay thế mã script vtvWrite sang html video zplayer (video chạy bằng HTML 5)
 * @return string
 */
/* Begin 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
function _vd_html_chuyen_doi_vtv_write_sang_zplayer($p_body,$p_cat_id,$p_width_zplayer,$p_height_zplayer,$p_arr_extra_parram = array()){
	if(empty($p_body)) return $p_body;
    if((strpos($p_body,'vtvWrite')===false)){
        return $p_body;
    }
    $v_url_video = '';
	/* Begin 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
    preg_match_all('#vtvWrite\(([^\)]*)\);#ism', $p_body, $v_arr_matches_url);
	if(check_array($v_arr_matches_url[0])){
		foreach($v_arr_matches_url[1] as $key=>$value){
			$v_url_video = trim($value);
			$v_url_video = trim($v_url_video,'"');
			$v_url_video = trim($v_url_video,"'");

			if(!empty($v_url_video)){
				$v_html_video_zplayer = '';
				ob_start();
				$v_obj = new box_player_video_by_url_block();
				$v_obj->setParam('is_zplayer_html5', true);
                $v_time = time();
                /* Begin: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
                $v_obj->setParam('url_video', $v_url_video);
                $v_obj->setParam('id_div_video', $v_time);
                $v_obj->setParam('is_autoplay', $p_arr_extra_parram['is_autoplay']);
                $v_obj->setParam('p_is_box_video_chon_loc', $p_arr_extra_parram['p_is_box_video_chon_loc']);
                $v_obj->setParam('p_anh_dai_dien_video', $p_arr_extra_parram['p_anh_dai_dien_video']);
                $v_obj->setParam('p_url_now', $p_arr_extra_parram['p_url_now']);
                $v_obj->setParam('p_is_bai_tuong_thuat', $p_arr_extra_parram['p_is_bai_tuong_thuat']);
                $v_obj->setParam('p_is_count_down', $p_arr_extra_parram['p_is_count_down']);
                $v_obj->setParam('p_is_trang_video', $p_arr_extra_parram['p_is_trang_video']);
                $v_obj->setParam('p_type_extraDataUrl', $p_arr_extra_parram['p_type_extraDataUrl']);
                $v_obj->setParam('p_cat_video_id', $p_arr_extra_parram['p_cat_video_id']);
                $v_obj->setParam('time', $v_time);
                /* End: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
				$v_obj->index($p_cat_id,3,$p_width_zplayer,$p_height_zplayer,$v_url_video);
				$v_html_video_zplayer = ob_get_clean();
                /* End: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
                /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
				preg_match('/<!--Begin:position_banner_sponsor_video-->(.*?)<!--End:position_banner_sponsor_video-->/msU', $v_html_video_zplayer, $v_arr_tmp_sponsor);
                // Loại bỏ code banner cho video khi đưa video ra ngoài ifframe
				if(check_array($v_arr_tmp_sponsor) && !empty($v_arr_tmp_sponsor[0])){
					$v_html_video_zplayer = str_replace($v_arr_tmp_sponsor[0], '<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->', $v_html_video_zplayer);
				}
                /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
				preg_match('#<!-- begin_media_player -->(.*?)<!-- end_media_player -->#ism', $v_html_video_zplayer, $v_arr_matches_player);
				$p_body = str_replace($v_arr_matches_url[0][$key], '</script>'.$v_arr_matches_player[0].'<script type="text/javascript">', $p_body);
			}
		}
        if(intval($p_arr_extra_parram['p_is_box_video_chon_loc'])!=1 && count($v_arr_matches_url[0])==1){
        $p_body .= '<iframe width="0px" height="0px" src="'.BASE_URL_FOR_PUBLIC.'ajax/ga_doi_tac/video_vtv_ga_load_trang.html"></iframe>';
        }
	}
	/* End 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
    return $p_body;
}
/* Begin - Tytv 21/12/2016 - trang_video(box_xem_video_dau_trang) */
/** Author: Tytv 08/12/2016
 * Hàm xử lý thay thế mã script antvWrite sang html video zplayer (video chạy bằng HTML 5)
 * @return string
 */
/* Begin 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
function _vd_html_chuyen_doi_antv_write_sang_zplayer($p_body,$p_cat_id,$p_width_zplayer,$p_height_zplayer,$p_arr_extra_parram = array()){
	if(empty($p_body)) return $p_body;
    if((strpos($p_body,'antvWrite')===false)){
        return $p_body;
    }
    /* Begin 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
    preg_match_all('#antvWrite\(([^\)]*)\);#ism', $p_body, $v_arr_matches_video);
	if(check_array($v_arr_matches_video[0])){
        $v_id_video = 0;
		foreach($v_arr_matches_video[1] as $key=>$value){
            $v_id_video  = trim($value);
            $v_id_video  = trim($v_id_video,"'");
            $v_id_video  = trim($v_id_video,'"');
            $v_id_video  = intval($v_id_video);
            if($v_id_video>0){
                $v_html_script_video = '';
                // lấy
                ob_start();
                $v_obj = new box_get_video_outsite_block();
                $v_player_id = 'div_video_antv_'.time();

                /* Begin: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
                $v_obj->setParam('url_video', $v_url_video);
                $v_obj->setParam('id_div_video', '');
                $v_obj->setParam('is_autoplay', $p_arr_extra_parram['is_autoplay']);
                $v_obj->setParam('p_is_box_video_chon_loc', $p_arr_extra_parram['p_is_box_video_chon_loc']);
                $v_obj->setParam('p_anh_dai_dien_video', $p_arr_extra_parram['p_anh_dai_dien_video']);
                $v_obj->setParam('p_url_now', $p_arr_extra_parram['p_url_now']);
                $v_obj->setParam('p_is_bai_tuong_thuat', $p_arr_extra_parram['p_is_bai_tuong_thuat']);
                $v_obj->setParam('p_is_count_down', $p_arr_extra_parram['p_is_count_down']);
                $v_obj->setParam('p_is_trang_video', $p_arr_extra_parram['p_is_trang_video']);
                $v_obj->setParam('p_no_is_in_iframe', $p_arr_extra_parram['p_no_is_in_iframe']);
                $v_obj->setParam('time', time());
                /* End: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/

                $v_obj->index(1,$v_id_video,$v_player_id,$p_width_zplayer,$p_height_zplayer);
                $v_html_script_video = ob_get_clean();
                preg_match('#<!-- begin:script_video_outsite -->(.*?)<!-- end:script_video_outsite -->#ism', $v_html_script_video, $v_arr_matches_video_outsite);
                // thực hiện tách lấy các thông số của hàm script _get_video_outsite
                if(check_array($v_arr_matches_video_outsite)){
                    preg_match('#_get_video_outsite\(([^\)]*)\);#ism', $v_arr_matches_video_outsite[1], $v_arr_matches_video1);
                    $v_str_tham_so = trim($v_arr_matches_video1[1]);
                    $v_arr_tham_so = explode(',', $v_str_tham_so);
                    $v_type = intval($v_arr_tham_so[0]);
                    $v_id_player = trim($v_arr_tham_so[1]);
                    $v_id_player = trim($v_id_player,"'");
                    $v_url_get_video = trim($v_arr_tham_so[2]);
                    $v_url_get_video = trim($v_url_get_video,"'");
                    $v_url_html_ga = trim($v_arr_tham_so[3]);
                    $v_url_html_ga = trim($v_url_html_ga,"'");
                    $v_width = trim($v_arr_tham_so[4]);
                    $v_width = trim($v_width,"'");
                    $v_height = trim($v_arr_tham_so[5],"'");
                    $v_height = trim($v_height,"'");
                    $v_is_autoplay = trim($v_arr_tham_so[6]);
                    $v_is_autoplay = trim($v_is_autoplay,"'");
                    $v_is_box_video_chon_loc = trim($v_arr_tham_so[7]);
                    $v_is_box_video_chon_loc = intval(trim($v_is_box_video_chon_loc,"'"));
                    $v_anh_dai_dien_video = trim($v_arr_tham_so[8]);
                    $v_anh_dai_dien_video = trim($v_anh_dai_dien_video,"'");
                    $v_url_news = trim($v_arr_tham_so[9]);
                    $v_url_news = trim($v_url_news,"'");

                    $v_html_video_zplayer = '';
                    ob_start();
                    $v_obj2 = new box_player_video_by_url_block();
                    $v_obj2->setParam('is_zplayer_html5', true);
                    /* Begin: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
                    $v_obj2->setParam('url_video', $v_url_video);
                    $v_obj2->setParam('id_div_video', $v_player_id);
                    $v_obj2->setParam('is_autoplay', $v_is_autoplay);
                    $v_obj2->setParam('p_is_box_video_chon_loc', $v_is_box_video_chon_loc);
                    $v_obj2->setParam('p_anh_dai_dien_video', $v_anh_dai_dien_video);
                    $v_obj2->setParam('p_url_now', $p_arr_extra_parram['p_url_now']);
                    $v_obj2->setParam('p_is_bai_tuong_thuat', $p_arr_extra_parram['p_is_bai_tuong_thuat']);
                    $v_obj2->setParam('p_is_count_down', $p_arr_extra_parram['p_is_count_down']);
                    $v_obj2->setParam('p_is_trang_video', $p_arr_extra_parram['p_is_trang_video']);
                    $v_obj2->setParam('p_type_extraDataUrl', $p_arr_extra_parram['p_type_extraDataUrl']);
                    $v_obj2->setParam('p_cat_video_id', $p_arr_extra_parram['p_cat_video_id']);
                    $v_obj2->setParam('p_no_is_in_iframe', $p_arr_extra_parram['p_no_is_in_iframe']);
                    $v_obj2->setParam('time', time());
                    /* End: bổ xung các tham số truyền vào từ GET(khi chạy qua iframe)*/
                    $v_obj2->index($p_cat_id,$v_type,$p_width_zplayer,$p_height_zplayer,$v_url_get_video);
                    $v_html_video_zplayer = ob_get_clean();
                    /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
                    // Loại bỏ code banner cho video khi đưa video ra ngoài ifframe
					preg_match('/<!--Begin:position_banner_sponsor_video-->(.*?)<!--End:position_banner_sponsor_video-->/msU', $v_html_video_zplayer, $v_arr_tmp_sponsor);
					if(check_array($v_arr_tmp_sponsor) && !empty($v_arr_tmp_sponsor[0])){
						$v_html_video_zplayer = str_replace($v_arr_tmp_sponsor[0], '<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->', $v_html_video_zplayer);
					}
                    /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
                    preg_match('#<!-- begin_media_player -->(.*?)<!-- end_media_player -->#ism', $v_html_video_zplayer, $v_arr_matches_player);
                    $v_html_script_video = str_replace($v_arr_matches_video_outsite[0],$v_html_video_zplayer,$v_html_script_video);

                    $p_body = str_replace($v_arr_matches_video[0][$key],'</script>'.$v_html_script_video.'<script type="text/javascript">', $p_body);
                }
            }
        }
    }
    /* End 27/07/2017 Tytv thu_nghiem_hien_thi_video_phu_cho_bai_video */
    return $p_body;
}
/**
 * hàm tạo lấy thông số tracking theo từng loại
 * @param array $p_row_news : Mảng các dữ liệu bài viết
 * @param array $p_row_cat : Mảng dữ liệu chuyên mục
 * @param array $p_param_extension : Mảng các dữ liệu truyền vào
 * @return string
 */
function _vd_lay_thong_so_tracking_theo_loai($p_row_news, $p_row_cat, $p_param_extension){
    // lấy mã content
    $v_ma_content = $p_row_news['c_list_ma_content'];
    if(empty($v_ma_content)){
        $v_ma_content = 'Null';
    }
    // Lấy ID bài viết
    $v_id_news = intval($p_row_news['ID']);
    // Lấy id chuyên mục
    $v_id_cate = intval($p_row_cat['ID']);
    $v_ma_nguon_source  = $p_param_extension['v_ma_nguon_source'];// có thể bỏ vì ko dùng ANTS nữa
    // Nếu mã nguồn video bằng trống hoặc null thì mặc định nó là nguồn video 24h
    if(empty($v_ma_nguon_source) || $v_ma_nguon_source == 'quangcaoWrite' || $v_ma_nguon_source == 'heinekenWrite'){
        $v_ma_nguon_source = '24h';
    }
    // mã video các chiến dịch đặc biệt
    $v_ma_chien_dich = $p_param_extension['v_ma_loai_giai_dau'];
    // Nếu mã nguồn video bằng trống hoặc null thì mặc định nó là nguồn video 24h
    if(empty($v_ma_chien_dich)){
        $v_ma_chien_dich = 'Null';
    }
    // Tạo chuôi tham số tracking ga
    $v_param_tracking = '?id_news='.$v_id_news.'&id_cate='.$v_id_cate.'&ma_content='.$v_ma_content.'&ma_chien_dich='.$v_ma_chien_dich.'&nguon_video='.$v_ma_nguon_source;
    // Kiểm tra biến thiêt bị GA
    if(isset($_GET['p_thiet_bi_ga']) && isset($_GET['p_thiet_bi_ga']) != '')
    {
        $v_thiet_bi_ga = fw24h_replace_bad_char($_GET['p_thiet_bi_ga']);
        $v_param_tracking .= '&v_thiet_bi_ga='.$v_thiet_bi_ga;
    }else{
        global $v_device_global;
        if(isset($v_device_global) && $v_device_global != ''){
            $v_param_tracking .= '&v_thiet_bi_ga='.$v_device_global;
        }
    }
    // check loại bài highlight video
    if($p_row_news['news_type_code_list'] != '' && in_array('vidhighlight',explode(',',$p_row_news['news_type_code_list']))){
        $v_param_tracking .= '&isHighlightArticle=yes';
    }else{
        $v_param_tracking .= '&isHighlightArticle=no';
    }
    if (_vd_is_video_tuong_thuat($p_row_news)) {
        $v_param_tracking .= '&isLiveArticle=yes';
    }else{
        $v_param_tracking .= '&isLiveArticle=no';
    }

    # XLCYCMHENG-40495 - tách ga video nội dung - box chọn lọc
    if ($p_param_extension['v_is_box_video_chon_loc']){
        $v_param_tracking .= '&v_is_box_video_chon_loc=yes';
    }

    return $v_param_tracking;
}

/*
* Author : Thangnnb 29-12-2015
* Ham dem so luong video trong bai viet
* param: $p_row_news : Mang tin bai
* return int
*/
function _vd_get_so_luong_video_trong_bai_viet($p_row_news) {
    if(!check_array($p_row_news)) {
        return 0;
    }
    /* begin 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
    preg_match_all('/<script type="text\/javascript">(flashWrite|vtvWrite|antvWrite|emobi_write).*<\/script>/msU',$p_row_news['Body'], $v_arr_video);
    /* end 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
    if (check_array($v_arr_video[1])) {
        return count($v_arr_video[1]);
    } else {
        return 0;
    }
}
