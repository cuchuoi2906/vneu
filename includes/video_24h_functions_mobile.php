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
    $v_che_do_load_khung_player = intval(get_gia_tri_danh_muc_dung_chung('CHE_DO_PLAY_VIDEO', 'CHE_DO_LOAD_KHUNG_PLAYER'));
    if ($v_che_do_load_khung_player == 1){ // nếu sử dụng qua iframe
        $p_code_video = _vd_xu_ly_code_video_chay_qua_iframe($p_code_video,$p_row_news, $p_row_cat, $p_param_extension);
    }else{// nếu không sử dụng qua iframe
		//B1 - B8: Xử lý code video thông thường (video insite)
		$p_code_video = _24h_player_xu_ly_code_video($p_code_video, $p_row_news, $p_row_cat, $p_param_extension);
    }
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
    // Xử lý loại bỏ video đối với chiến dịch được cấu hình theo n tiếng
    $p_code_video = _24h_player_loai_bo_video_khong_duoc_hien_thi_sau_n_tieng($p_code_video,$v_arr_config_video);
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    //B2: Loại bỏ video không được phép hiển thị
    $p_code_video = _vd_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);

    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    $v_arr_data_ads = _24h_player_thiet_lap_thong_so_quang_cao($p_code_video,$p_row_news, $p_row_cat, $v_arr_config_video);
    //B4: Thiết lập dữ liệu gán tracking,ga
    $v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    $v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);
    //B5: Thay thế dạng script write tùy theo cấu hình
    $p_code_video = _24h_player_chuyen_doi_giua_cac_ma_script_video($p_code_video,$v_arr_config_video);


    //B6: Thiết lập dữ liệu highlight video
    $v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news,$v_arr_config_video);
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
        'v_arr_data_highlight_video'=>$v_arr_data_highlight_video,
        'v_row_news'=>$p_row_news,
        'v_row_cat'=>$p_row_cat,
    );

    $v_str_html_player = _24h_player_tao_code_html_player($p_code_video,$v_param_extension);
    //B8: Thay thế các lời gọi player bằng script thành HTML
    $p_code_video = _24h_player_thay_the_code_html_cho_cac_loi_goi_video($p_code_video,$v_str_html_player,$v_param_extension);
	// B9: xử lý video banner sponsor
	$p_code_video = _vd_thiet_lap_banner_sponsor_video_no_iframe($p_code_video,$p_row_news, $p_row_cat,$v_param_extension);
    return $p_code_video;
}
/**
 * Hàm loại bỏ video không được hiển thị
 * @param array $p_code_video : Code chứa video
 * @param array $v_arr_config_video : Các thông số cấu hình videos
 * @return array
 */
function _vd_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video) {
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
    // lấy dữ liệu quảng cáo
    // phải là bài viết ko set layout ko hiện thị quảng cáo
    if(!$v_cat_id_banner && $v_is_quang_cao_write && $v_is_show_banner_video == 1){
        $v_ma_loai_giai_dau = _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_code_video);
        // nếu giải đấu là ngoại hạng anh thì thay thế file vast riêng
        $v_preroll = _24h_player_lay_file_vast_preroll_theo_chien_dich($v_ma_loai_giai_dau,$v_preroll);
        $v_overlay  = _24h_player_lay_file_vast_overlay_theo_chien_dich($v_ma_loai_giai_dau,$v_overlay);
        $v_end      = _24h_player_lay_file_vast_postroll_theo_chien_dich($v_ma_loai_giai_dau,$v_end);
    }else{
        /* End: 17-06-2020 TuyenNT dieu_chinh_co_che_hien_thi_quang_cao_tren_video_noi_dung */
        $xmlAds = Gnud_Db_read_get_key(TEN_KEY_QUANG_CAO_VIDEO.$v_cat_id_banner.'_'.$_SERVER['SERVER_REGION'], _CACHE_TABLE_QUANG_CAO);
        // bài pr và bài set chuyên mục layout không hiện thị quảng cáo video
        if(intval($v_is_bai_pr) == 1 || intval($v_is_show_banner_video) == 0){
            if (_24h_player_xu_ly_bai_viet_ko_chay_quang_cao_video()){
                $xmlAds = '';
            }
        }
        if ($xmlAds != '') {
			// lấy thông số quảng cáo từ chuỗi xml ads
			$v_arr_data_xmlAds = _vd_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds);
			$v_logo     = $v_arr_data_xmlAds['v_logo'];
			$v_before   = $v_arr_data_xmlAds['v_before'];
			$v_overlay  = $v_arr_data_xmlAds['v_overlay'];
			$v_preroll  = $v_arr_data_xmlAds['v_preroll'];
			$v_end      = $v_arr_data_xmlAds['v_end'];
        }
    }

    # XLCYCMHENG-40731 - player - preroll - vast - add inventory_scope
    $v_preroll = _24h_player_ads_add_plus_params($v_preroll, ['row_news' => $p_row_news], 'mobile');

    // xử lý lấy thông tin các tham số quảng cáo
    if ($xmlAds != '') {
		// thực hiện tối ưu các tham số quảng cáo
		_vd_toi_uu_thong_so_quang_cao($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);
	}

	_vd_toi_uu_thong_so_quang_cao($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);

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
    $p_file_preroll = get_gia_tri_danh_muc_dung_chung($p_loai_giai_dau,'DUONG_DAN_FILE_VAST_DFP_PREROLL_MOBILE');
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
function _vd_toi_uu_thong_so_quang_cao(&$v_before,&$v_preroll,&$v_overlay,&$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source){
    $v_before   = ($v_before != '' && $v_before != '/') ? $v_before : FLASH_VIDEO_DEFAULT;
    if (strpos($v_url_news,'http:') === false && strpos($v_url_news,'https:') === false) {
        $v_url_prefer = BASE_URL_FOR_PUBLIC.ltrim($v_url_news,'/');
    } else {
        $v_url_prefer = ltrim($v_url_news,'/');
    }
    // nếu là US thì chuyển về link US
    $v_url_prefer = ($v_region_value == 'us')?str_replace(BASE_URL_FOR_PUBLIC,BASE_URL_FOR_PUBLIC_US,$v_url_prefer):$v_url_prefer;
    $v_url_prefer = urlencode($v_url_prefer);
    $v_arr_link_quang_cao = _24h_player_thay_the_tham_so_tren_link_quang_cao($v_url_prefer, $v_ma_nguon_source, $v_preroll, $v_overlay, $v_end);
    $v_preroll  = str_replace(array('"',"'","\r","\n"), '', $v_arr_link_quang_cao[0]);
    $v_overlay  = str_replace(array('"',"'","\r","\n"), '', $v_arr_link_quang_cao[1]);
    $v_end      = str_replace(array('"',"'","\r","\n"), '', $v_arr_link_quang_cao[2]);

    // Gán thêm tham số ambient = 1
    $v_preroll = _24h_player_xu_ly_qc_ambient_cho_zplayer($v_preroll, true);
    $v_overlay = _24h_player_xu_ly_qc_ambient_cho_zplayer($v_overlay, true);
    $v_end     = _24h_player_xu_ly_qc_ambient_cho_zplayer($v_end, true);
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

	return array($p_preroll, $p_overlay, $p_end);
}
/*
* Thangnb xu_ly_qc_ambient_22_05_2015
* params $p_string
* return $p_string da qua xu ly ambient
*/
function _24h_player_xu_ly_qc_ambient_cho_zplayer($p_string) {
	if (_24h_player_kiem_tra_quang_cao_ambient($p_string)) {
		if (strpos($p_string,'ambient=1') !== false) {
			return $p_string;
		} else if (strpos($p_string,'?') !== false) {
			$p_string = $p_string.'&ambient=1';
		} else {
			$p_string = $p_string.'?ambient=1';
		}
	}
	return $p_string;
}
/**
 * @author Thangnb 16/04/2015 - Kiểm tra quảng cáo ambient
 * @param string p_ads chuỗi quảng cáo
 * @return: boolean
 */
function _24h_player_kiem_tra_quang_cao_ambient($p_ads) {
    // Lấy mảng dấu hiệu nhận biết qc adsense & dfp
    $v_check = ARR_DAU_HIEU_NHAN_BIET_QUANG_CAO_AMBIENT;
    $v_arr_check = array();
    if ($v_check != '') {
        $v_arr_check = unserialize($v_check);
    }
    // Trường hợp không có quảng cáo hoặc mảng nhận biết null
    if ($p_ads == '' || !check_array($v_arr_check)) {
        return false;
    }
    // Kiểm tra dấu hiệu nếu có 1 dấu hiệu thì dừng vòng lặp và trả về giá trị true
    for ($i=0, $s=count($v_arr_check) ; $i<$s; $i++) {
        if (strpos(strtolower($p_ads), $v_arr_check[$i])) {
            return true;
        }
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

    // kiểm tra nếu bài viết được gắn loại content mới thực hiện lấy mã content
    $v_event_load_trang_content = '';
    $v_event_quang_cao_content = '';
    $v_url_html_ga_play_video_content = '';
    $v_event_quang_cao = '';
    $v_url_html_ga_play_video ='';
    // lấy thông số tracking ga theo loại
    $v_tracking_tong_video = _vd_lay_thong_so_tracking_theo_loai($p_row_news,$p_row_cat,$p_param_extension);

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
    $v_che_do_load_khung_player = intval(get_gia_tri_danh_muc_dung_chung('CHE_DO_PLAY_VIDEO', 'CHE_DO_LOAD_KHUNG_PLAYER'));
    if(!IFRAME_VIDEO_PLAYER || $p_param_extension['v_is_box_xem_video_trang_video'] || intval($p_row_news['vidhighlight']) ==1 || ($v_che_do_load_khung_player == 2 && !intval($p_param_extension['v_is_bai_tuong_thuat']))
		|| ($v_che_do_load_khung_player == 2 && intval($p_param_extension['v_is_bai_tuong_thuat']) && !isset($p_param_extension['v_id_div_video_iframe']))){

        // nếu không chạy qua iframe thì mới gắn mã ga load trang.
        $v_event_load_trang_content = $v_arr_ga_video_nhom_content['v_event_load_trang_content'];
    }
    $v_url_html_ga_play_video_content = $v_arr_ga_video_nhom_content['v_url_html_ga_play_video_content'];
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

    // chuyển đổi script cho video euroWrite2016
    if ($v_region_value != 'us') {  // neu khong phai us thi gan euroWrite2016 -> flashWrite
        $p_code_video = _24h_player_thay_the_video_euroWrite2016($p_code_video);
    }
    // kiểm tra nếu bài viết có video emobi_write thì replace sang vtvWrite
    $p_code_video = _thay_the_emobi_write_ve_video_doi_tac_write($p_code_video);

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
function _24h_player_thay_the_video_euroWrite2016($p_body){
    $p_body = preg_replace('/euroWrite2016\(\"([^\)]*)\)/','flashWrite("/images/24hvideo_player.swf?file=$1,418,314)',$p_body);
    return $p_body;
}
/**
 * Hàm thực hiện thiết lập các dữ liêu highlight video theo từng loại video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @return string
 */
function _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news,$p_arr_config_video =array()) {

    $v_id_bai_viet = intval($p_row_news['ID']);
    $v_arr_data_highlight = array();
    // Lấy tên dữ liệu highlight video
    if($v_id_bai_viet>0 && !intval($p_arr_config_video['v_use_no_highlight_video'])){
        $v_arr_data_highlight   = array();
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
    $v_check_16_9           = $v_arr_config_video['v_check_16_9'];

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
    $v_width_video  = WIDTH_ZPLAYER_VIDEO;
    $v_height_video = HEIGHT_ZPLAYER_VIDEO;

    $v_div_logo_khach_hang 			= '';
    if ($v_khach_hang_logo_video > 0) {
        $v_div_logo_khach_hang = _24h_player_get_html_khach_hang_logo_video($v_khach_hang_logo_video);
    }
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
	$v_str_player = '<video id="'.ID_PLAYER_VIDEO.'" class="video-js vjs-default-skin" controls webkit-playsinline playsinline width="'.$v_width_video.'" height="'.$v_height_video.'"
         poster="{poster_video}">
    {string_single_video}
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that
      <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>
	';
	$v_width_video = '100%';
	if (strpos($v_width_video,'%') === false && strpos($v_width_video,'px') === false) {
		$v_width_video = $v_width_video.'px';
	}
	if (strpos($v_height_video,'%') === false && strpos($v_height_video,'px') === false) {
		$v_height_video = $v_height_video.'px';
	}
    // kiểm tra cấu hình size 16:9
    $v_height_video = $v_check_16_9 ? 'auto' : $v_height_video;
    /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
	$v_height_video_24h_media = $v_height_video;
	$v_che_do_load_khung_player = intval(get_gia_tri_danh_muc_dung_chung('CHE_DO_PLAY_VIDEO', 'CHE_DO_LOAD_KHUNG_PLAYER'));
	if ($v_che_do_load_khung_player == 2){ // nếu sử dụng qua iframe
		$v_height_video_24h_media = 'auto';
	}
    $v_str_player = $v_div_logo_khach_hang.'<div class="v-24h-media-player v-mobile" id="'.$v_id_player.'_container" style="display:block;width:'.$v_width_video.';height:'.$v_height_video_24h_media.';position:relative"><div id="'.$v_id_player.'_ga" style="display:none"></div><div id="'.$v_id_player.'_content_ga" style="display:none"></div><div id="'.$v_id_player.'_content_play" style="display:none"></div><div id="'.$v_id_player.'_content_preroll" style="display:none"></div><div id="'.$v_id_player.'_content_overlay" style="display:none"></div><div id="'.$v_id_player.'_content_postroll" style="display:none"></div><!--begin:str_video_player--><div id="v-24hContainer_'.$v_id_player.'" style="background: #eee;position:relative;height: '.$v_height_video.'"></div><!--end:str_video_player-->'.$v_html_flag_no_inview_video.'</div>';
    /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
	/* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
    $v_str_player .= '<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->';
    /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/

    $v_id_player_pin_info = $v_id_player.'_pinInfo';
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
		,'v_check_16_9' => $v_check_16_9
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
    if(strpos($p_width_video, '%')){
        $p_width_video  = trim($p_width_video);
    }else{
        if($p_width_video<=0){
            if($p_is_trang_video){
                $p_width_video  = WIDTH_ZPLAYER_VIDEO;
            }else if($v_is_box_video_chon_loc){
                $p_width_video  = WIDTH_ZPLAYER_VIDEO;
            }else{
                $p_width_video  = WIDTH_ZPLAYER_VIDEO;
            }
        }
    }
    if(strpos($p_height_video, '%')){
        $p_height_video  = trim($p_height_video);
    }else{
        if($p_height_video<=0){
            if($p_is_trang_video){
                $p_height_video  = HEIGHT_ZPLAYER_VIDEO;
            }else if($p_is_box_video_chon_loc){
                $p_height_video  = HEIGHT_ZPLAYER_VIDEO;
            }else{
                $p_height_video  = HEIGHT_ZPLAYER_VIDEO;
            }
        }
    }
    return array('v_width_video'=>$p_width_video,'v_height_video'=>$p_height_video);
}
//Begin 16-06-2016 : Thangnb thay_doi_logo_video_doi_tac
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
//End 16-06-2016 : Thangnb thay_doi_logo_video_doi_tac
/** Author: Tytv 08/02/2017 thiet_lap_gia_tri_post_id
 * Hàm tạo mã giá trị postId cho video chạy html5
 * @param string chuỗi dữ liệu (có thể là chuối url bài viết hoặc ID bài viết)
 * @return string
 */
function _24h_player_tao_ma_gia_tri_post_id_cho_player_video($p_value) {
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
    $v_row_news             = $p_arr_param_script_player['v_row_news'];
    $v_width_video         = $p_arr_param_script_player['width_player'];
    $v_height_video         = $p_arr_param_script_player['height_player'];
    $v_check_16_9         = $p_arr_param_script_player['v_check_16_9'];

    $v_is_bai_tuong_thuat   = $v_arr_config_video['v_is_bai_tuong_thuat'];
    $v_is_box_video_chon_loc   = $v_arr_config_video['v_is_box_video_chon_loc'];
    $v_id_div_video_iframe   = $v_arr_config_video['v_id_div_video_iframe'];

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
	$v_is_autoplay_view_port = '';
	$v_mobileAutoplay = '"mobileAutoplay": false,';
	$v_videoVol = VIDEO_VOL_24H;
	$v_adVol = '';
    $v_stt_video_in_body = intval($_GET['stt']) + 1;
	if ($v_che_do_play_video == 3 && !$v_is_box_video_chon_loc && !$v_is_bai_tuong_thuat) {
        $v_is_autoplay_view_port = '"viewportAutoPlay": false,';
        $v_mobileAutoplay = '"mobileAutoplay": true,';
		$v_videoVol = '0.15';
		$v_adVol = '"adVol" : "0.15",';
	}
    // xử lý autoplay bài tường thuật
    $v_count_video = intval($_GET['count_video']);
    if($v_count_video ==0 && $v_is_bai_tuong_thuat && !$v_is_box_video_chon_loc){
        $v_auto_play_video = strtolower(trim(get_gia_tri_danh_muc_dung_chung('VIDEO_BAN_QUYEN','AUTOPLAY_BAI_TUONG_THUAT_MOBILE')));
        if($v_auto_play_video == 'true'){
            $v_mobileAutoplay = '"mobileAutoplay": true,';
        }
    }
	// Không auto play với trình duyêt friefox mobile
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'fxios') || strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'firefox') || ($v_is_bai_tuong_thuat && intval($_GET['off_autoplay']) == 1)){
		$v_mobileAutoplay = '"mobileAutoplay": false,';
	}
    $v_isLiveArticle = '';
    if($v_is_bai_tuong_thuat){
        $v_isLiveArticle = '"isLiveArticle": "",';
    }

    $v_stt_video = '"vidPosition": '.$v_row_news['ID'].$v_stt_video_in_body.',';

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
    // Lấy link GA video mặc định
    $v_link_ga_err_video = LINK_GA_ERR_VIDEO_MAC_DINH.'?id_news='.$v_row_news['ID'];
    // không đo ga log với bài tường thuật
    $v_event_viewership = '';
    $v_code_duration = '';
    if(!$v_is_bai_tuong_thuat){
        $v_event_viewership = _script_event_viewership_video($v_id_player,$v_row_news['ID'],$v_row_news['CategoryID']);
        $v_code_duration  = '/*VIDEO_DURATION_CONFIG*/';
    }

    $v_str_in_iframe = '';
    if ($v_arr_config_video['v_is_in_iframe'] == true) {
        $v_str_in_iframe = '"isInIframe": true,"parentOrigin" : "'.rtrim(BASE_URL_FOR_PUBLIC,'/').'",';
    }
    $v_load_video_to_iframe = !empty($v_id_div_video_iframe) ? 1 : 0;
    $v_class_16_9 = $v_check_16_9 ? 'vjs-16-9' : '';
    // Check cấu hình hiển thị box higlight
    $v_show_video_highlight               = strtolower(trim(get_gia_tri_danh_muc_dung_chung('VIDEO_BAN_QUYEN','HIEN_THI_BOX_HIGTLIGHT_VIDEO')));
    $v_config_html_highlight = '"offHighlightOnBannerBottom":false,';
    if($v_show_video_highlight !== 'true'){
        $v_config_html_highlight = '"offHighlightOnBannerBottom":true,';
    }
    // nếu là box video chọn lọc thì ko load box recomment
    $v_load_recommend_box = 'var v_load_recommend_box'.$v_id_player.' = true;';
    if($v_is_box_video_chon_loc || $v_is_bai_tuong_thuat){
        $v_load_recommend_box = 'var v_load_recommend_box'.$v_id_player.' = false;';
    }
    $v_html_script = '<script type="text/javascript">
        '.$v_load_recommend_box.'
        var v_load_video_to_iframe'.$v_id_player.' = '.intval($v_load_video_to_iframe).';
        //{VARIABLE_POSTER}
        function initvideo'.$v_id_player.'() {
            var vidLoaded = false,
                dynamicId,
                vidId,
                parentVid = "v-24hContainer_'.$v_id_player.'",
                    videoElmStr = \'<video id="__VIDID'.$v_id_player.'__" class="video-js vjs-default-skin '.$v_class_16_9.'" width="'.$v_width_video.'" height="'.$v_height_video.'" controls poster="{poster_video}" \' +
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
					"stopOtherOnPlay": true,
                    '.$v_config_html_highlight.'
                    "vidErrorLog": '.ON_OFF_GHI_LOG_ERR_VIDEO_GA.',
					'.$v_skipAble.'
					"skipTime": '.(TIME_SKIP_BUTTON*1000).',
                    /*CDN_DOMAIN_HLS*/
                    /*MINI_VIDEO*/
					"skipAdsBtnContent": "'.SKIP_TEXT.'",
					'.$v_showAdVol.'
					"VIDEOID": "'.$v_id_player.'",
					'.$v_is_mini_video.'
					'.$v_is_autoplay_view_port.'
					'.$v_mobileAutoplay.'
					'.$v_isLiveArticle.'
					'.$v_stt_video.'
					"vidVol" : "'.$v_videoVol.'",
					'.$v_adVol.'
                    "trackSlowMedia":'.ON_OFF_GHI_LOG_MEDIA_SLOW.',
                    "secondToLoadMedia":'.SECOND_TO_LOAD_MEDIA.',
                    "secondToLoadMeta":'.SECOND_TO_LOAD_META.',
					"prerollTimeEnd" : '.TIME_END_PRE_ROLL.',
					"midrollTimeEnd" : '.TIME_END_OVERLAY.',
					"postrollTimeEnd" : '.TIME_END_POST_ROLL.',
					"skipAdsBtnPos": "'.SKIP_BUTTON_POSITION.'",
                    "nativeMobileTouch": '.NATIVE_MOBILE_TOUCH.',
					"userAgent": "'.$_SERVER['HTTP_USER_AGENT'].'",
                    "fastSeek": {step: '.SECOND_TO_FASTSEEK.'},
					"adLoadTimeout" : '.ADS_LOAD_TIME_OUT.'
					/*STRING_ADS_SINGLE_VIDEO*/
					,"midTime": '.MID_TIME_ADS.'
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
					console.log("listener highlighttableshow Endvideo: ");
					if(video'.$v_id_player.'){
						video'.$v_id_player.'.player.dispose();
						delete videojs.getPlayers()[videoAds1Conf.VIDEOID];
					}
					console.log("dispose xong: ");
                    videoAds1Conf.viewportAutoPlay = false;
                    var videoConf = videoAds1Conf;
                    videoConf.viewportAutoPlay = false;
                    videoConf.mobileAutoplay = false;
                    //var element = document.getElementById(videoConf.VIDEOID);
                    //element.parentNode.removeChild(element);
                    //video'.$v_id_player.' = undefined;
                    loadVid'.$v_id_player.'();
					set_iframe_video_highlight_height("'.$v_id_div_video_iframe.'");
					console.log("ket thuc khoi tao lai player");
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

                        if (this.isMobile){
                            // fix riêng vạch trắng ngay dưới banner top cho safari
                            if (playerUtil.isIos() && playerUtil.iOSVersion() && playerUtil.isSafari()) {
                                let style = document.createElement("style");
                                style.type = "text/css";
                                style.innerHTML = ".viewVideoPlay.minus-margin-v{margin-top: -0.2px !important}";
                                document.getElementsByTagName("head")[0].appendChild(style);
                            }

                            // làm tròn xuống height div chứa player khi xoay màn hình
                            window.addEventListener("resize", function (){
                                setTimeout(function () {// đợi 0.5s để player nhận height mới
                                    let vidEl = this.getById(this.VIDEOID);
                                    let vidHeight = vidEl.getBoundingClientRect().height;

                                    // làm tròn xuống & -1 height div chứa player
                                    let parentHeight = Math.floor(vidHeight) - 1;
                                    vidEl.parentNode.style.height = parentHeight + "px";
                                }.bind(this), 500);
                            }.bind(this));
                        }
                    }

                    // XLCYCMHENG-40280 - box_template_video_highlight - news - điều chỉnh lại chiều cao slide sau khi ready
                    if (typeof id_video_player_vscl != "undefined" && id_video_player_vscl == parentVid){
                        let slide_name = "video_cung_chuyen_muc_news";
                        let swiperDivObj = document.getElementById(parentVid).closest(".swiper");
                        if (
                            swiperDivObj
                            && swiperDivObj.classList.contains("slide_"+slide_name)
                            && typeof window["Swiper_"+slide_name+""] != "undefined"
                        ){
                            window["Swiper_"+slide_name+""].updateAutoHeight(50);
                        }
                    }
                }.bind(video'.$v_id_player.'));
            }
            function playerEvents'.$v_id_player.'(video'.$v_id_player.'){
                /*RESUME_WATCHING_EVENTS*/
                video'.$v_id_player.'.player.on("onPlay", function(name) {
                    // begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc
                    '.$v_ga_play_video_content.'
                    // end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc
                    //Begin 14-07-2017 : Thangnb tracking_google_tag_manager_video
                    '.$v_str_tracking_gtm_play_video.'
                    //End 14-07-2017 : Thangnb tracking_google_tag_manager_video
                });
                '._vd_script_event_tracking_click_preroll($v_id_player,$v_row_news['ID'],$v_row_news['CategoryID']).'
                '.  _vd_script_event_tracking_impresion_preroll($v_id_player,$v_row_news['ID'],$v_row_news['CategoryID']).'
                '._vd_script_event_media_error($v_id_player,$v_row_news['ID']).'
                '._vd_script_event_slow_playlist_next_video($v_id_player,$v_row_news['ID']).'
                video'.$v_id_player.'.player.on("timeupdated", function () {
                    if(v_load_recommend_box'.$v_id_player.' &&  typeof(v_thoi_luong_hien_thi) != "undefined" && v_thoi_luong_hien_thi != ""){
                        var v_time_current = video'.$v_id_player.'.player.currentTime();
                        if(v_time_current > 0){
                            if(v_time_current >= v_thoi_luong_hien_thi){
                                var v_check_highlight = 0;
                                if ($("#vjs-marker-tableinfo-'.$v_id_player.'").length) {
                                    v_check_highlight = 1;
                                }
                                try{
                                    if(v_load_video_to_iframe'.$v_id_player.' == 1){
                                        // Đưa message ra ngoài để load box recommend
										var data = {type:"recommend_news",id_video:"'.$v_id_player.'",id_news:'.$v_row_news['ID'].',id_event:'.$v_row_news['EventID'].',id_cate:'.$v_row_news['CategoryID'].', check_highlight:v_check_highlight};
										data = JSON.stringify(data);
										window.parent.postMessage(data, "*");
									}else{
                                        function getJsonRecomment(url,callback){
                                            if(url == ""){
                                                return "";
                                            }
                                            dynamicIdLive = new Date().valueOf();
                                            url = url+dynamicIdLive;
                                            var xhr = new XMLHttpRequest();
                                            // Get url
                                            xhr.open("GET", url, true);
                                            // Kiểu phản hồi
                                            xhr.responseType = "json";
                                            xhr.timeout = 50000; // Set timeout to 2 seconds
                                            xhr.ontimeout = function () {console.log("timeout");}
                                            // Load data
                                            xhr.onload = function() {
                                                var status = xhr.status;
                                                if (status == 200) {
                                                    callback(xhr.response);
                                                }else{
                                                    console.log("B3: status != 200");
                                                }
                                            };
                                            xhr.send();
                                        }
                                        if(typeof v_url_json_data_recomment != "undefined"){
                                            getJsonRecomment(v_url_json_data_recomment,function(v_object_recomend){
                                                if(typeof v_object_recomend != "undefined" && parseInt(v_object_recomend.length) > 0){
                                                    var recomment_arr_video_ngay_hien_tai = _getStorageJson24h("recomment_video_ngay_hien_tai_data_news_id");
                                                    var recomment_video_ngay_hom_qua_data_news_id = _getStorageJson24h("recomment_video_ngay_hom_qua_data_news_id");
                                                    // Lặp để loại bỏ những bài đang xem
													var arr_recomend_video = new Array();
													var k=0;
                                                    var v_so_luong_tin_hien_thi = parseInt(v_object_recomend[0]["c_tong_so_luong_tin_hien_thi"]);
													for(j=0;j<v_object_recomend.length;j++){
														if(v_object_recomend[j]["c_new_id"] > 0){
															if(v_recomment_news_id== parseInt(v_object_recomend[j]["c_new_id"])){

																continue;
															}
															if(typeof(recomment_arr_video_ngay_hien_tai) != "undefined" && recomment_arr_video_ngay_hien_tai.indexOf(parseInt(v_object_recomend[j]["c_new_id"]))  >= 0){

																continue;
															}
															if(typeof(recomment_video_ngay_hom_qua_data_news_id) != "undefined" && recomment_video_ngay_hom_qua_data_news_id.indexOf(parseInt(v_object_recomend[j]["c_new_id"]))  >= 0){
																continue;
															}
														}
														arr_recomend_video[k] = v_object_recomend[j];
														k++;
                                                        if(k >= v_so_luong_tin_hien_thi){
                                                            break;
                                                        }
													}
													console.log(arr_recomend_video);
                                                    var v_total_page_recommend =0;
                                                    v_total_page_recommend = parseInt(Math.ceil(parseInt(arr_recomend_video.length)/2));
                                                    // Tao HTML video recommend
                                                    var v_html_recomend = tao_html_recommend_video_tu_object(arr_recomend_video,"'.$v_id_player.'");
                                                    // Gắn HTML vào chuỗi
                                                    if(v_html_recomend !=""){
                                                        // Nếu tồn tại video highlight
                                                        if ($("#vjs-marker-tableinfo-'.$v_id_player.'").length) {
                                                            $("#vjs-marker-tableinfo-'.$v_id_player.'").after(v_html_recomend);
                                                        } else {
                                                            if ($("#'.$v_id_player.'_ga_preroll").length) {
                                                                $("#'.$v_id_player.'_ga_preroll").before(v_html_recomend);
                                                            }
                                                        }
                                                        // Hiển thị dạng slide
                                                        if ($("#box_recommend_'.$v_id_player.'").length) {
                                                            $("#box_recommend_'.$v_id_player.'").show(1000);
                                                            setTimeout(function(){
                                                                create_slide_page_number_recommend_video_json("swiper_recommend_v2018'.$v_id_player.'", "swpier_container_'.$v_id_player.'","swiper_active_slide_'.$v_id_player.'", "pagination-'.$v_id_player.'","wrapper_'.$v_id_player.'",v_total_page_recommend, "swiper-slide slide_'.$v_id_player.'", "prvVid_recommend'.$v_id_player.'", "nxtVid_recommend'.$v_id_player.'");
																$("#'.$v_id_player.'_container").attr("style", "margin-bottom: 0px !important");
                                                            }, 900);
															setTimeout(function(){
																$("#box_recommend_'.$v_id_player.'").attr("style", "height:auto;margin-bottom: 15px !important");
															},1200);
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                    }
                                    v_load_recommend_box'.$v_id_player.' = false;
                                }catch(e){
                                    console.log(["Errors", e]);
                                }
                            }
                        }
                    }
                });
                '.$v_event_viewership.'
                '._vd_script_event_media_slow($v_id_player,$v_row_news['ID']).'
                '._vd_script_event_onResume($v_id_player).'
                '.$v_ga_quang_cao.'
                video'.$v_id_player.'.player.on("onStop", function(name) {
                    //console.log("listener onStop: ");
                    '._24h_player_thiet_lap_thong_so_khi_onStop_video($v_id_player,$v_str_tracking_gtm_complete_video).'
                });
				video'.$v_id_player.'.player.on("highlighttableshow", function(name) {
                    //console.log("listener highlighttableshow: ");
					set_iframe_video_highlight_height("'.$v_id_div_video_iframe.'");
                });
                '.$v_str_add_for_zplayer.'
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
/**
 * hàm lấy khai báo biên
 * @author:$p_bien_true đặt mặc định là true/false
 */
function _24h_player_khai_bao_script_ga_loai_giai_dau($p_id_player){
    $v_html_scipt = "var load_Impression".$p_id_player." = false;";
    $v_html_scipt .= "var load_start".$p_id_player." = false;";
    $v_html_scipt .= "var load_firstQuartile".$p_id_player." = false;";
    $v_html_scipt .= "var load_midpoint".$p_id_player." = false;";
    $v_html_scipt .= "var load_thirdQuartile".$p_id_player." = false;";
    $v_html_scipt .= "var load_complete".$p_id_player." = false;";
    $v_html_scipt .= "var load_mute".$p_id_player." = false;";
    $v_html_scipt .= "var load_unmute".$p_id_player." = false;";
    $v_html_scipt .= "var load_pause".$p_id_player." = false;";
    $v_html_scipt .= "var load_clickchrough".$p_id_player." = false;";
    $v_html_scipt .= "var load_skip".$p_id_player." = false;";
    return $v_html_scipt;
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
    $v_check_16_9           = $v_arr_config_video['v_check_16_9'];

    # XLCYCMHENG-41188 - on amp - fix player trong bài viết
    $v_stt_video_amp = isset($v_arr_config_video['v_stt_video_amp']) ? intval($v_arr_config_video['v_stt_video_amp']) : -1;

    $v_is_trang_video = $v_arr_config_video['v_is_trang_video'];
    // Thay thế từng đoạn code video (bài video thường là 1 lần - bài tường thuật trực tiếp là nhiều lần)
    for ($j=1; $j>0 && $j<=100; $j++) {
        if (strpos($p_code_video, 'flashWrite') !== false) {
            # XLCYCMHENG-41188 - on amp - fix player trong bài viết
            if ($v_stt_video_amp >= 0 && $v_stt_video_amp != $j - 1){
                $p_code_video = preg_replace('#flashWrite\(([^\)]*)\);#', 'console.log("remove flashWrite '.$j.'");', $p_code_video, 1);
                continue;
            }

            $v_str_player = $p_str_html_video;
            // Chỉ gán GA Load trang cho video đầu tiên
            if($j == 1){
                // Gán lại GA load trang cho zplayer
                $v_str_player = str_replace('/*{event_load_trang}*/',$v_arr_data_tracking['v_ga_load_trang'],$v_str_player);
                /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */
                $v_str_player = str_replace('/*{event_load_trang_content}*/',$v_arr_data_tracking['v_ga_load_trang_content'],$v_str_player);
                /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_pc */

                # 20220128 XLCYCMHENG-38141 - neo video đầu trang mobile
                if (!$v_is_box_video_chon_loc){# 20220214 không áp dụng cho box video chọn lọc
                    $v_config_mini_video = _24h_player_get_config_hien_thi_video_phu_cho_bai_video($v_row_cat, $v_row_news);
                    $v_str_player = str_replace('/*MINI_VIDEO*/', $v_config_mini_video, $v_str_player);
                }
                # 20220128 XLCYCMHENG-38141 - neo video đầu trang mobile -> end

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

            // thay thế script drm video theo cấu hình
            $v_str_player = _24h_player_use_drm_video_by_config($arr_file_video,$v_str_player);

            // xử lý thay thế các thông số hightlight video
            if(check_array($v_arr_data_highlight)){
                // Tạo chuỗi thiết lập
                $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_highlight_zplayer_html5($v_arr_data_highlight,$arr_file_video,$v_str_player);
            }
            $v_ad_postroll = count($arr_file_video)-1;
            $v_str_player = str_replace('{postroll_only}', $v_ad_postroll, $v_str_player);

            // thay thế chuỗi thiết lập bắt sự kiện onpins zplayer
            $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onpins_zplayer_html5($v_zplayer_id,$v_str_player);
            // thay thế chuỗi thiết lập bắt sự kiện onStop zplayer
            $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onstop_change_zplayer_html5($v_zplayer_id,$v_str_player);
            if($v_is_trang_video){
                // thay thế mã thiết lập onplay xác định vị trí video đang phát trên trang
                $v_str_player = _24h_player_thay_the_chuoi_de_xac_dinh_video_dang_phat($v_zplayer_id,$v_str_player);
                // thay thế mã thiết lập minizplayer
                $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_mini_zplayer_html5($v_zplayer_id,$v_str_player);
                // thay thế chuỗi thiết lập bắt sự kiện onplayExtraVideo zplayer
                $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onplay_extra_change_zplayer_html5($v_zplayer_id,$v_str_player);
            }
			// Gắn thêm cấu hình thời lượng video
            $v_str_player = _config_viewership_video($v_str_player,$arr_file_video);

            # XLCYCMHENG-39836 - thay thế script hỗ trợ tiếp tục xem tiếp khi load trang
            $v_cookie_key = 'player__resume_watching_'.intval($v_row_news['ID']).'_'.md5(json_encode($arr_file_video));
            $v_str_player = _vd_script_resume_watching_events($v_str_player, $v_zplayer_id, $v_cookie_key, intval($v_row_news['CategoryID']));

            // Thay thế html video vào nội dung bài viết
            $v_html_banner_sponsor_top = '';
            $v_html_banner_sponsor_botton = '';
            if($j == 1 && $v_check_16_9 && !$v_is_box_video_chon_loc && !$v_is_bai_tuong_thuat && !$v_is_trang_video){
                $v_html_banner_sponsor_top = _hien_thi_quang_cao_tren_trang("ADS_235_15s","bnr clF bnSponTop",'','');
                $v_html_banner_sponsor_botton = _hien_thi_quang_cao_tren_trang("ADS_236_15s","bnr clF bnSponBot",'','');
                $v_str_player = str_replace("<!--end:str_video_player-->", '<div id="bnnSponBot_'.$v_zplayer_id.'">'.$v_html_banner_sponsor_botton.'</div>', $v_str_player);
                $v_str_player = str_replace("/*ID_BANNER_SPONSOR*/", ',"idBannerBottom": "bnnSponBot_'.$v_zplayer_id.'"', $v_str_player);
            }

            # XLCYCMHENG-41188 - on amp - fix player trong bài viết
            if ($v_stt_video_amp >= 0 && $v_stt_video_amp == $j - 1){
                return $v_html_banner_sponsor_top.'<!-- begin_media_player --><div class="viewVideoPlay minus-margin-v">'.$v_str_player.'</div><!-- end_media_player -->';
            }

            // Thay thế html video vào nội dung bài viết
            $p_code_video = preg_replace('#flashWrite\(([^\)]*)\);#', '</script>'.$v_html_banner_sponsor_top.'<!-- begin_media_player --><div class="viewVideoPlay minus-margin-v">'.$v_str_player.'</div><!-- end_media_player --><script type="text/javascript">', $p_code_video, 1);
        } else {
            break;
        }
    }
    return $p_code_video;
}

# Hàm lấy config hiển thị video phụ cho bài viết
function _24h_player_get_config_hien_thi_video_phu_cho_bai_video($p_row_cat = [], $p_row_news = [], $check_cate_parent = false, $check_extra_cate = true, $check_extra_cate_parent = false) {
    if (!check_array($p_row_cat) || !check_array($p_row_news)) {
        return '';
    }

    $p_cate_id = intval($p_row_cat['ID']);
    global $v_is_bai_tuong_thuat;
    if ($v_is_bai_tuong_thuat || $p_cate_id<=0){
        return '';
    }

    $v_ma_cau_hinh_video_phu_cho_bai_video = _get_module_config('cau_hinh_dung_chung','v_ma_cau_hinh_video_phu_cho_bai_video');
    $v_arr_cau_hinh = fe_danh_sach_gia_tri_theo_ma_danh_muc($v_ma_cau_hinh_video_phu_cho_bai_video);
    if (!check_array($v_arr_cau_hinh) || $p_cate_id<=0){
        return '';
    }

    $v_arr_cau_hinh            = _array_convert_index_to_key($v_arr_cau_hinh, 'c_ma_gia_tri');
    $v_danh_sach_id_chuyen_muc = trim($v_arr_cau_hinh['MOBILE_DANH_SACH_CHUYEN_MUC_HIEN_THI_VIDEO_PHU']['c_ten']);
    $v_danh_sach_id_su_kien    = trim($v_arr_cau_hinh['MOBILE_DANH_SACH_SU_KIEN_HIEN_THI_VIDEO_PHU']['c_ten']);
    $v_danh_sach_id_layout     = trim($v_arr_cau_hinh['MOBILE_DANH_SACH_LAYOUT_HIEN_THI_VIDEO_PHU']['c_ten']);

    if (empty($v_danh_sach_id_chuyen_muc) && empty($v_danh_sach_id_su_kien) && empty($v_danh_sach_id_layout)) {
        return '';
    }

    $v_arr_danh_sach_id_chuyen_muc = explode(',', $v_danh_sach_id_chuyen_muc);
    $v_arr_danh_sach_id_su_kien    = explode(',', $v_danh_sach_id_su_kien);
    $v_arr_danh_sach_id_layout     = explode(',', $v_danh_sach_id_layout);
    if (!check_array($v_arr_danh_sach_id_chuyen_muc) && !check_array($v_arr_danh_sach_id_su_kien) && !check_array($v_arr_danh_sach_id_layout)){
        return '';
    }

    # kiểm tra chuyên mục áp dụng
    $p_parent_cate_id = intval($p_row_cat['Parent']);
    $v_ap_dung_theo_chuyen_muc = false;
    if (check_array($v_arr_danh_sach_id_chuyen_muc)){
        if (
            strtolower($v_danh_sach_id_chuyen_muc) == 'all' # áp dụng cho tất cả
            ||
            ($check_cate_parent && $p_parent_cate_id > 0 && in_array($p_parent_cate_id, $v_arr_danh_sach_id_chuyen_muc)) # áp dụng cho mục cha
            ||
            ($p_cate_id > 0 && in_array($p_cate_id, $v_arr_danh_sach_id_chuyen_muc)) # áp dụng cho mục con
        ){
            $v_ap_dung_theo_chuyen_muc = true;
        } elseif ($check_extra_cate) {
            # 20220208 check thêm các chuyên mục phụ
            if (!empty($p_row_news['c_list_category_id'])){
                $v_arr_cate_id = explode(',', trim($p_row_news['c_list_category_id']));
                foreach ($v_arr_cate_id as $v_cate_id){
                    $v_cate_id = intval($v_cate_id);
                    if ($v_cate_id <= 0 || $v_cate_id == $p_parent_cate_id || $v_cate_id == $p_cate_id){
                        continue;
                    }

                    if (in_array($v_cate_id, $v_arr_danh_sach_id_chuyen_muc)){
                        $v_ap_dung_theo_chuyen_muc = true;
                        break;
                    } elseif ($check_extra_cate_parent) {
                        $v_arr_cate = fe_chuyen_muc_theo_id($v_cate_id);
                        if (check_array($v_arr_cate)){
                            $v_parent_cate_id = intval($v_arr_cate['Parent']);
                            if ($v_parent_cate_id > 0 && in_array($v_parent_cate_id, $v_arr_danh_sach_id_chuyen_muc)){
                                $v_ap_dung_theo_chuyen_muc = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    # kiểm tra sự kiện áp dụng
    $v_ap_dung_theo_su_kien = false;
    if (check_array($v_arr_danh_sach_id_su_kien) && !empty($p_row_news['c_list_event_id'])){
        $v_arr_event_id = explode(',', trim($p_row_news['c_list_event_id']));
        foreach ($v_arr_event_id as $v_event_id){
            $v_event_id = intval($v_event_id);

            if ($v_event_id > 0 && in_array($v_event_id, $v_arr_danh_sach_id_su_kien)){
                $v_ap_dung_theo_su_kien = true;
                break;
            }
        }
    }

    # kiểm tra layout áp dụng
    $v_ap_dung_theo_layout = false;
    if (
        !$v_ap_dung_theo_chuyen_muc # ko áp dụng theo chuyên mục xuất bản
        &&
        !$v_ap_dung_theo_su_kien # ko áp dụng theo sự kiện xuất bản
        &&
        check_array($v_arr_danh_sach_id_layout) # có cấu hình layout áp dụng
    ){
        if (isset($GLOBALS['v_arr_check_id_cat_banner'])){
            $v_arr_check_id_cat_banner = $GLOBALS['v_arr_check_id_cat_banner'];
        } else {
            $v_arr_check_id_cat_banner = _get_chuyen_muc_xuat_ban_banner($p_row_news['ID'], $p_cate_id);
        }

        if (check_array($v_arr_check_id_cat_banner)){
            if (
                $v_arr_check_id_cat_banner['v_is_xuat_ban_banner_rieng'] > 0
                &&
                $v_arr_check_id_cat_banner['v_cat_id_banner'] > 0
                &&
                in_array($v_arr_check_id_cat_banner['v_cat_id_banner'], $v_arr_danh_sach_id_layout)
            ) {
                $v_ap_dung_theo_layout = true;
            }
        }
    }

    if (
        !$v_ap_dung_theo_chuyen_muc # ko áp dụng theo chuyên mục xuất bản
        &&
        !$v_ap_dung_theo_su_kien # ko áp dụng theo sự kiện xuất bản
        &&
        !$v_ap_dung_theo_layout # ko áp dụng theo layout xuất bản
    ){
        return '';
    }

    $v_config_mini_video = '"minAble": true,"vidTitle" : "'.fw24h_replace_bad_char_to_null($p_row_news['Title']).'",';

    // vị trí bắt đầu neo player
    $percentage_show_mini_player = intval($v_arr_cau_hinh['MOBILE_VI_TRI_HIEN_THI_VIDEO_PHU']['c_ten']);
    $v_config_mini_video .= '"offsetWatchPercent": '.$percentage_show_mini_player.',';

    // vị trí kết thúc neo player; ko config sẽ neo tới cuối trang
    $v_config_mini_video .= '"unminimize": "'.trim($v_arr_cau_hinh['MOBILE_VI_TRI_BO_HIEN_THI_VIDEO_PHU']['c_ten']).'",';

    return $v_config_mini_video;
}

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

	if (_is_test_domain()) {
		//$v_arr_ads['v_ads_preroll'] = 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/ad_rule_samples&ciu_szs=300x250&ad_rule=1&impl=s&gdfp_req=1&env=vp&output=vmap&unviewed_position_start=1&cust_params=deployment%3Ddevsite%26sample_ar%3Dpreonly&cmsid=496&vid=short_onecue&correlator=';
		//$v_arr_ads['v_ads_overlay'] = 'https://demo.tremormedia.com/proddev/vast/vast_inline_nonlinear3.xml';
		//$v_arr_ads['v_ads_postroll'] = 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/ad_rule_samples&ciu_szs=300x250&ad_rule=1&impl=s&gdfp_req=1&env=vp&output=vmap&unviewed_position_start=1&cust_params=deployment%3Ddevsite%26sample_ar%3Dpostonly&cmsid=496&vid=short_onecue&correlator=';
	}
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
    $v_arr_ads['v_ads_preroll'] = _set_tempvast_when_ad_vast_empty($v_arr_ads['v_ads_preroll']);
	$v_arr_ads['v_ads_postroll'] = _set_tempvast_when_ad_vast_empty($v_arr_ads['v_ads_postroll']);
    $v_arr_ads['v_ads_overlay'] = _set_tempvast_when_ad_vast_empty($v_arr_ads['v_ads_overlay']);
    if(check_array($p_arr_file_video)){
		$v_arr_file_video_drm = array();
		if ($v_count_video == 1) {
            // Chỉnh lại đường dẫn file video
            $v_file_video_origin = $p_arr_file_video[0];
			$v_arr_file_video_drm[0] = $p_arr_file_video[0];
			if(strpos($p_arr_file_video[0],'http://') === false && strpos($p_arr_file_video[0],'https://') === false){
				$v_arr_file_video_drm[0] = DEFAULT_IMAGE_VIDEO.ltrim($p_arr_file_video[0],'/');
			}
            // kiểm tra video có đủ điều kiện hiển thị player
            $v_scrip_tracking = _vd_off_player_and_tracking_ga_err($p_arr_file_video[0],$p_param_extension['v_row_news']);
            if($v_scrip_tracking != ''){
                return array('v_str_playlist'=>'','v_str_player'=>$v_scrip_tracking);
            }
			$p_arr_file_video[0] = _vd_thay_the_duong_dan_video_theo_cau_hinh($p_arr_file_video[0],$p_param_extension['v_row_news']);
            // Chỉ xử lý cho mã video cd_nivea_fifa
            $v_type_video = 'video/mp4';
            $v_use_fallback = false;
            $v_ishls = false;
            if(strpos($p_arr_file_video[0],'.m3u8') !==false){
                $v_type_video='application/x-mpegURL';
                //$v_use_fallback = true;
                $v_ishls = _vd_use_hls_config($v_file_video_origin) ? true : false;
            }
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
			$p_str_player = str_replace('/*STRING_ADS_SINGLE_VIDEO*/', $v_string_ads_single_video, $p_str_player);
			// Cấu hình sử dụng fallback video
			if(USE_ON_OFF_FALLBACK){
				$p_str_player = str_replace('/*FALL_BACK*/', $v_scipt_fallback, $p_str_player);
			}
            $key_random_hls = '';
            if($v_ishls && ON_OFF_CONFIG_SCRIPT_HLS){
                // Lấy thư mục video
                $v_arr_expl_tmp = explode('/', trim($v_file_video_origin));
                unset($v_arr_expl_tmp[count($v_arr_expl_tmp)-1]);
                $v_folder_video = implode('/',$v_arr_expl_tmp);
                if($v_folder_video != ''){
                    $v_script_config_hls = str_replace('<!--folder_video_hls-->',$v_folder_video.'/', CONFIG_SCRIPT_HLS);
                    $p_str_player = str_replace('/*CDN_DOMAIN_HLS*/', $v_script_config_hls, $p_str_player);
                    $key_random_hls = _vd_key_random_hls_config($v_file_video_origin);
                    if($key_random_hls != ''){
                        $p_str_player = str_replace('/*random-hls-prefix*/', $key_random_hls, $p_str_player);
                    }
                }
            }
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
                        return array('v_str_playlist'=>'','v_str_player'=>$v_scrip_tracking);
                    }
				}
                $v_file_video_origin = $v_item;
				$v_arr_file_video_drm[$key] = $v_file_video_origin;
				if(strpos($v_file_video_origin,'http://') === false && strpos($v_file_video_origin,'https://') === false){
					$v_arr_file_video_drm[$key] = DEFAULT_IMAGE_VIDEO.ltrim($v_file_video_origin,'/');
				}
                // Chỉnh lại đường dẫn file video
				$v_item = _vd_thay_the_duong_dan_video_theo_cau_hinh($v_item,$p_param_extension['v_row_news']);
                $p_arr_file_video[$key] = $v_item;
                // Chỉ xử lý cho mã video cd_nivea_fifa

                $v_use_fallback = false;
                //$v_ishls = false;
                if(strpos($p_arr_file_video[0],'.m3u8') !==false && $key == 0){
                    $v_type_video ='application/x-mpegURL';
                    $v_ism3u8 = true;
                    //$v_use_fallback = true;
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
                            $v_keyrandom = true;
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
						type: "'.$v_type_video.'",
                        '.$v_cdn_domain.'
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
			$p_str_player = str_replace('/*STRING_ADS_SINGLE_VIDEO*/', $v_string_ads_mid_video, $p_str_player);
            $p_str_player = preg_replace('/{string_single_video}/msi', '', $p_str_player);
			$p_str_player = str_replace('/*PLAYLIST_ITEM*/', $v_str_playlist, $p_str_player);
			$p_str_player = str_replace('//{VARIABLE_POSTER}', 'var POSTER = "{poster_video}";', $p_str_player);
		}
        // thay thế script drm video theo cấu hình
        $v_str_player = _24h_player_use_drm_video_by_config($v_arr_file_video_drm,$p_str_player);
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
    $v_is_box_video_chon_loc = $v_arr_config_video['v_is_box_video_chon_loc'];
	$v_size_img_video_highlight = $v_arr_config_video['v_size_img_video_highlight'];
    $v_id_news = intval($v_row_news['ID']);
    $v_date_create_news_video = (empty($v_row_news['DateCreated']) ||  $v_date_create_news_video == '0000-00-00 00:00:00')?$v_row_news['Date']:$v_row_news['DateCreated'];
	// trường hợp 1 số bài ở boxvideo chon loc ko có trường DateCreated,Date = 0000-00-00 00:00:00
	$v_date_create_news_video = (empty($v_date_create_news_video) || $v_date_create_news_video == '0000-00-00 00:00:00')?$v_row_news['PublishedDate']:$v_date_create_news_video;
    /* Begin: Tytv - 1/11/2017 - fix_loi_hien_thi_video_antv */
    if(isset($p_param_extension['v_arr_data_ads']['v_ma_nguon_source'])){
        $v_ma_nguon_source_video = strtoupper($p_param_extension['v_arr_data_ads']['v_ma_nguon_source']);
        if($v_ma_nguon_source_video == 'ANTV'){
            return $p_str_player;
        }
    }
    /* End: Tytv - 1/11/2017 - fix_loi_hien_thi_video_antv */
    // nếu là bài tương thuật thì lấy ảnh đại diện theo bài viết hoặc ảnh đại diện box video chọn lọc
    if($v_is_bai_tuong_thuat){
        $v_on_off_anh_dai_dien_video = strtolower(get_gia_tri_danh_muc_dung_chung('CHE_DO_PLAY_VIDEO', 'ON_OFF_CAT_ANH_DAI_DIEN_BAI_TUONG_THUAT'));
        if($v_on_off_anh_dai_dien_video == 'true' && $v_row_news['ID'] > ID_BAI_HIEN_THI_ANH_DAI_DIEN_TU_DONG){
            $tmp_arr_exp  = explode('.', $p_link_video);
            $v_duoi_video = '.'.end($tmp_arr_exp);
            $v_duoi_anh = _get_module_config('cau_hinh_dung_chung','v_duoi_anh_dai_dien_video');
            $v_anh_dai_dien_video = str_replace(array('videoclip',$v_duoi_video),array('images',$v_duoi_anh), $p_link_video);
        }else{
            $v_anh_dai_dien_video = $v_row_news['video_homepage_image'];
            if ($v_anh_dai_dien_video == '') {
                $v_anh_dai_dien_video = $v_row_news['SummaryImg_chu_nhat'];
            } else if($v_row_news['SummaryImg_chu_nhat'] == '') {
                $v_anh_dai_dien_video = $v_row_news['SummaryImg'];
            }
        }
        $v_link_image = html_image($v_anh_dai_dien_video,false);
        $p_str_player = str_replace('{poster_video}', $v_link_image, $p_str_player);
        return $p_str_player;
    }

    if($v_date_create_news_video == ''){
        return $p_str_player;
    }
    $v_ngay_hien_thi = _get_module_config('cau_hinh_dung_chung', 'v_ngay_hien_thi_anh_dai_dien_video');
    // on/off tu dong lay anh dai dien video
    $v_on_off_auto_get_image_video = _get_module_config('cau_hinh_dung_chung', 'v_on_off_auto_get_image_video');
    if($v_ngay_hien_thi > $v_date_create_news_video || !$v_on_off_auto_get_image_video){
       return $p_str_player;
    }
	// Cấu hình ngày hiển thị ảnh đại diện video
    $v_id_bai_viet_moi_nhat = _get_module_config('cau_hinh_dung_chung', 'v_id_bai_viet_moi_nhat_khi_trien_khai');
    if($v_id_bai_viet_moi_nhat > $v_id_news){
       return $p_str_player;
    }
    // Neu anh dai dien video la anh mac dinh thi bat dau thay the
    if(strpos($p_str_player, '{poster_video}') !== false){
        // Lấy link ảnh chuẩn
        if($v_is_box_video_chon_loc){
            $v_anh_dai_dien_video = $v_row_news['video_homepage_image'];
           if ($v_anh_dai_dien_video == '') {
               $v_anh_dai_dien_video = $v_row_news['SummaryImg_chu_nhat'];
           } else if($v_row_news['SummaryImg_chu_nhat'] == '') {
               $v_anh_dai_dien_video = $v_row_news['SummaryImg'];
           }
           // nếu là bài magazine và poster video dc upload riêng
           if(intval($v_row_news['c_news_magazine']) == 1 && $v_row_news['v_file_poster_video_magazine'] != ''){
               $v_anh_dai_dien_video = $v_row_news['v_file_poster_video_magazine'];
           }
		   $SummaryImg_chu_nhat = ($v_row_news['SummaryImg_chu_nhat']!='')?$v_row_news['SummaryImg_chu_nhat']:$v_row_news['video_homepage_image'];
           if($v_size_img_video_highlight){
               $v_size_img = '455x303';
               $v_link_image = html_image(get_image_thumbnail($SummaryImg_chu_nhat, $v_size_img),false);
           }else{
           $v_link_image = html_image($v_anh_dai_dien_video,false);
           }
       }else{
            $tmp_arr_exp  = explode('.', $p_link_video);
            $v_duoi_video = '.'.end($tmp_arr_exp);
            $v_duoi_anh = _get_module_config('cau_hinh_dung_chung','v_duoi_anh_dai_dien_video');
            $v_file_image = str_replace(array('videoclip',$v_duoi_video),array('images',$v_duoi_anh), $p_link_video);
            $v_link_image = html_image($v_file_image,false);
       }
	   $v_link_image = str_replace(IMAGE_VIDEO, IMAGE_NEWS, $v_link_image);
       	// Nếu video được setup theo video có bản quyền thì sẽ lấy ảnh dại diện mặc định
        $v_ma_loai_giai_dau = $p_param_extension['v_arr_data_ads']['v_ma_loai_giai_dau'];
        $v_ma_ten_video_anh_dai_dien_fifa = _get_module_config('cau_hinh_dung_chung', 'v_ma_ten_video_anh_dai_dien_fifa');
        if(ON_OFF_LAY_ANH_DAI_DIEN_FIFA && !empty($v_ma_loai_giai_dau) && $v_ma_loai_giai_dau == $v_ma_ten_video_anh_dai_dien_fifa){
            $p_str_player = str_replace('{poster_video}', ANH_DAI_DIEN_FIFA_3S, $p_str_player);
            return $p_str_player;
        }else{
            // Thay thế ảnh đại diện zplayer
            $p_str_player = str_replace('{poster_video}', $v_link_image, $p_str_player);
        }
    }
    return $p_str_player;
}
//Begin 11-08-2016 : Tytv highlight_video
/*
 * Hàm tạo danh sách các sự kiện highlgiht của video
 * @author: Tytv  - 17-08-2016
 * param $p_arr_data Mảng dữ liệu chứa sự kiện highlight
 * retturn string
 *
 */
function _24h_player_html_tao_danh_sach_su_kien_highlight($p_arr_data,$p_arr_extra){
    $v_html = '';
    $v_id_player = $p_arr_extra['v_id_player'];
    $v_id_player_pin_info = $p_arr_extra['v_id_player_pin_info'];
    $v_str_playlist = $p_arr_extra['v_str_playlist'];
    $v_chuoi_phan_cach = $p_arr_extra['v_chuoi_phan_cach'];

    $v_arr_videos = explode($v_chuoi_phan_cach,$v_str_playlist);

    if(check_array($p_arr_data) && check_array($p_arr_data['t_highlight_video']) && check_array($v_arr_videos)){
        $v_arr_config_highlight_video = _get_module_config('cau_hinh_dung_chung', 'v_arr_config_highlight_video');
        $v_so_video = sizeof($v_arr_videos);
        $v_html .=' <div id="'.$v_id_player_pin_info.'" class="khung-zplayer">';
        $v_thu_tu_video = 0;
        $v_co_highlight = false;
        foreach ($v_arr_videos as $key => $value) {
            $v_ten_video = end(explode('/', $value));
            $v_arr_highlight_video = $p_arr_data['t_highlight_video'][$v_ten_video];
            $v_style_display = ($v_thu_tu_video>0)?'display:none':'display:block';
			$v_html_noi_dung_highlight = '';
            $v_html .= '<div id="idx_video_'.$v_id_player.'_id'.$v_thu_tu_video.'" class="khung-zplayer-container" style="'.$v_style_display.'">';
            $v_thu_tu_video++;
            if(check_array($v_arr_highlight_video['t_highlight'])){
                if($v_so_video>1){
                $v_html_noi_dung_highlight .= '<div class="part-video-zplayer">
                              <span id="sum_video_zplayer">'.($v_thu_tu_video).'/'.$v_so_video.'</span>
                                </div>';
                }
                $v_html_noi_dung_highlight .= '<div class="box-time-highlight">';
                $v_co_highlight = true;
                foreach ($v_arr_highlight_video['t_highlight'] as $key1 => $value1) {
                    $v_time = (($value1['c_so_phut']<10)?('0'.$value1['c_so_phut']):$value1['c_so_phut']).':'.(($value1['c_so_giay']<10)?('0'.$value1['c_so_giay']):$value1['c_so_giay']);
                    $v_giay = intval($value1['c_so_phut'])*60 + intval($value1['c_so_giay']);
                    $v_str_pin = $v_giay;
                    $v_url_icon = (empty($value1['c_url_icon']))?html_image($v_arr_config_highlight_video['url_icon_highlight_default'],false):html_image($value1['c_url_icon'],false);
                    $v_html_noi_dung_highlight .=' <div class="time-highlight">
                                    <a class="highlight" href="javascript:void(0);" onclick="zPlayerAPI(\''.$v_id_player.'\', \'goTime\', \''.$v_str_pin.'\');">
                                    <div class="video-time-highlight">
                                      <div class="icon-event-highlight"><img width="16" height="18" alt="" src="'.$v_url_icon.'"></div>
                                      <div><strong>'.$v_time.'</strong> - '.$value1['c_ghi_chu'].'</div>
                                    </div>
									</a>
                                </div>';
                }
				$v_html_noi_dung_highlight .='</div>';
            }
            $v_html_noi_dung_highlight = ($v_co_highlight)?$v_html_noi_dung_highlight:'';
            $v_html .= $v_html_noi_dung_highlight;
            $v_html .='</div>';
        }
        $v_html .='</div>';
    }
    return $v_co_highlight?$v_html:"";
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
//End 11-08-2016 : Tytv highlight_video
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
function _vd_xu_ly_tao_player_video_outsite($p_str_file_video,$p_row_news, $p_row_cat,$p_param_extension) {

    //B1: Thiết lập các giá trị cấu hình (tùy thuộc vào các trường hợp gọi mà bổ xung thêm các tham số ban đầu cần thiết)
    $v_arr_config_video = _vd_thiet_lap_thong_so_cau_hinh_video($p_row_news, $p_row_cat,$p_param_extension);
    //B2: Loại bỏ video không được phép hiển thị
    $v_region_value = $p_param_extension['v_region_value'];
    // kiem tra có phải là video có tên cần loại bỏ trên us không
    if(strtolower($v_region_value) == 'us' && _vd_config_video_code_has_exits_in_string($v_ten_ma_video)  && intval($p_param_extension['v_is_bai_tuong_thuat'])==0){
        return '';
    }
	// End - Tytv - 13/2/2018 - fix_loi_video_ko_hien_thi_us_van_hien_thi_us
    //        $p_code_video = _vd_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);
    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    $v_arr_data_ads = _vd_thiet_lap_thong_so_quang_cao_video_outsite($p_str_file_video,$p_row_news, $p_row_cat, $v_arr_config_video);
    //B4: Thiết lập dữ liệu gán tracking,ga
    $v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    $v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);
    //B5: BƯỚC NÀY BỎ  Thay thế dạng script write tùy theo cấu hình (hàm _vd_chuyen_doi_giua_cac_ma_script_video)

    //B6: Thiết lập dữ liệu highlight video
    $v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news,$v_arr_config_video); /* edit: Tytv - 1/11/2017 - fix_loi_highlight_box_video_chon_loc */

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
 * Hàhực hiện tạo html player theo chuỗi file video
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

    // Gán lại GA load trang cho zplayer
	// nếu ko phải là trang video và các video đều chạy qua iframe thì loại bỏ code để bắt sự kiện load trang
    if(!$v_is_trang_video && IFRAME_VIDEO_PLAYER){
        $v_str_player = str_replace('/*{event_load_trang}*/','',$p_str_html_video);
    }else{// trang video các video ko chạy qua iframe nên event load trang ko được load từ js nên phải có load ở trong video
        $v_str_player = str_replace('/*{event_load_trang}*/',$v_arr_data_tracking['v_ga_load_trang'],$p_str_html_video);
    }
    /* begin 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_mobile */
    $v_str_player = str_replace('/*{event_load_trang_content}*/',$v_arr_data_tracking['v_ga_load_trang_content'],$v_str_player);
    /* end 5/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_mobile */
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

    // thay thế script drm video theo cấu hình
    $v_str_player = _24h_player_use_drm_video_by_config($arr_file_video,$v_str_player);

    // xử lý thay thế các thông số hightlight video
    if(check_array($v_arr_data_highlight)){
        $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_highlight_zplayer_html5($v_arr_data_highlight,$arr_file_video,$v_str_player);
    }
    $v_ad_postroll = count($arr_file_video)-1;
    $v_str_player = str_replace('{postroll_only}', $v_ad_postroll, $v_str_player);


    // thay thế chuỗi thiết lập bắt sự kiện onpins zplayer
    $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onpins_zplayer_html5($v_zplayer_id,$v_str_player);
    // thay thế chuỗi thiết lập bắt sự kiện onStop zplayer
    $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onstop_change_zplayer_html5($v_zplayer_id,$v_str_player);

    // Gắn thêm cấu hình thời lượng video
    $v_str_player = _config_viewership_video($v_str_player,$arr_file_video);

    # XLCYCMHENG-39836 - thay thế script hỗ trợ tiếp tục xem tiếp khi load trang
    $v_cookie_key = 'player__resume_watching_'.intval($v_row_news['ID']).'_'.md5(json_encode($arr_file_video));
    $v_str_player = _vd_script_resume_watching_events($v_str_player, $v_zplayer_id, $v_cookie_key, intval($v_row_news['CategoryID']));

    if($v_is_trang_video){
        // thay thế mã thiết lập onplay xác định vị trí video đang phát trên trang
        $v_str_player = _24h_player_thay_the_chuoi_de_xac_dinh_video_dang_phat($v_zplayer_id,$v_str_player);
        // thay thế chuỗi thiết lập bắt sự kiện onplayExtraVideo zplayer
        $v_str_player = _24h_player_thay_the_chuoi_de_thiet_lap_onplay_extra_change_zplayer_html5($v_zplayer_id,$v_str_player);
    }

    return $v_str_player;
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
    // Xử lý them quangcao write
    /* Begin: Tytv - 26/09/2017 - toi_uu_code_xu_ly_video */
	if (strpos($row_news['Body'], 'flashWrite') || strpos($row_news['Body'], 'antvWrite') || strpos($row_news['Body'], 'ballball') || strpos($row_news['Body'],'vtvWrite') || ($row_news['videoCode'] != '') || ($row_news['Video_code'] > 0) || _vd_check_is_quang_cao_write($row_news['Body']) || strpos($row_news['Body'], 'quangcaoWrite') || strpos($row_news['Body'], 'emobi_write')) {
    /* End: Tytv - 26/09/2017 - toi_uu_code_xu_ly_video */
		return true;
	} else {
		return false;
	}
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
/*
 * Hàm thực hiện xử lý code video thông thường (video insite: flash,..) sang chạy qua dạng iframe
 * @param array $p_body : Code chứa video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * return: tring html
 */
function _vd_xu_ly_code_video_chay_qua_iframe($p_body,$p_row_news, $p_row_cat, $p_param_extension){
    // thiết lập các thông số cần dùng
    $v_news_id      = intval($p_row_news['ID']);
    $v_url_news     = $p_param_extension['v_url_news'];
    $v_is_bai_tuong_thuat   = $p_param_extension['v_is_bai_tuong_thuat'];
    $v_is_quang_cao_write   = _vd_check_is_quang_cao_write($p_body);
	$v_type_quang_cao       = _vd_check_is_quang_cao_write($p_body,true);
	$v_list_ma_content      = $p_row_news['c_list_ma_content'];
    $p_param_extension['v_is_quang_cao_write']  = $v_is_quang_cao_write;
	$p_param_extension['v_type_quang_cao']      = $v_type_quang_cao;
	/* Begin ducnq 08/07/2018  fix_loi_xem_video_ban_quyen_tren_us */
    $v_arr_config_video['v_region_value'] = get_region_value();
	/* begin 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
	$v_list_event = _lay_list_su_kien_cho_1_bai_viet($p_row_news['ID']);
    $v_arr_config_video['v_event'] = $v_list_event;
	/* end 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
    /* Begin: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    // Bổ sung thêm ngày xuất bản
    $v_arr_config_video['PublishedDate2'] = $p_row_news['PublishedDate2'];
    // Xử lý loại bỏ video đối với chiến dịch được cấu hình theo n tiếng
    $p_body = _24h_player_loai_bo_video_khong_duoc_hien_thi_sau_n_tieng($p_body,$v_arr_config_video);
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */

	//B2: Loại bỏ video không được phép hiển thị
    $p_body = _vd_loai_bo_video_khong_duoc_hien_thi($p_body,$v_arr_config_video);
	/* end ducnq 08/07/2018  fix_loi_xem_video_ban_quyen_tren_us */
    //Lay danh sach file video
    if(_vd_check_is_quang_cao_write($p_body)){
        $p_body = str_replace(array('quangcaoWrite','heinekenWrite'), 'flashWrite', $p_body);
    }
    // kiểm tra nếu bài viết có video emobi_write thì replace sang vtvWrite
    $p_body = _thay_the_emobi_write_ve_video_doi_tac_write($p_body);
    // Lấy danh sách div video
    preg_match_all('/<div id="(video-.*)".*>/msU', $p_body, $id_div_video);
    // lấy danh dách file video
    preg_match_all('/file=(.*)"/msU',$p_body,$v_file_video);
    // lấy danh dách code video cần thay thế để chạy qua iframe
    preg_match_all('/<script type=\"text\/javascript\">[\s]*flashWrite.*<\/script>/msU',$p_body,$v_script);

	if ($p_param_extension['v_stt_video_amp'] >= 0) {
		//Lấy dữ liệu video khi BTV nhập trong dấu 2 nháy
		preg_match_all('/vtvWrite\("(.*)"\)/msU',$p_body,$v_file_video_vtv);
		//Nếu không có dữ liệu thì check trường hợp BTV nhập trong dấu 1 nháy
		if (!check_array($v_file_video_vtv[1])) {
			preg_match_all('/vtvWrite\(\'(.*)\'\)/msU',$p_body,$v_file_video_vtv);
		}
		if (check_array($v_file_video_vtv[1])) {
			preg_match_all('/<script type=\"text\/javascript\">[\s]*vtvWrite.*<\/script>/msU',$p_body,$v_script_vtv);
			$v_file_video[1] = array_merge($v_file_video[1],$v_file_video_vtv[1]);
			$v_script[0] = array_merge($v_script[0], $v_script_vtv[0]);
		}
        //Lấy dữ liệu video khi BTV nhập trong dấu 2 nháy
        preg_match_all('/videoDoiTacWrite\("(.*)"\)/msU',$p_body,$v_file_video_doi_tac);
        //Nếu không có dữ liệu thì check trường hợp BTV nhập trong dấu 1 nháy
        if (!check_array($v_file_video_doi_tac[1])) {
            preg_match_all('/videoDoiTacWrite\(\'(.*)\'\)/msU',$p_body,$v_file_video_doi_tac);
        }
        if (check_array($v_file_video_doi_tac[1])) {
            preg_match_all('/<script type=\"text\/javascript\">[\s]*videoDoiTacWrite.*<\/script>/msU',$p_body,$v_script_doi_tac);
            $v_news_arr_video_doi_tac = array();
            foreach ($v_file_video_doi_tac[1] as $v_str_video_doi_tac) {
                $v_arr_video_doi_tac = explode('","', $v_str_video_doi_tac);
                if (check_array($v_arr_video_doi_tac)) {
                    $v_news_arr_video_doi_tac[1][] = $v_arr_video_doi_tac[1];
                }
            }
            if (check_array($v_news_arr_video_doi_tac[1])) {
                $v_file_video[1] = array_merge($v_file_video[1], $v_news_arr_video_doi_tac[1]);
            }
            $v_script[0] = array_merge($v_script[0], $v_script_doi_tac[0]);
        }
	}
    if (check_array($id_div_video[1])) {
        $v_count = count($id_div_video[1]);
        $v_is_bai_tuong_thuat = true;
    }
    // xác định các đối tượng html cần bổ xung cho html chứa code video
    $v_add = '';
    $v_start_div = '';
    $v_end_div = '';
    $v_height = HEIGHT_ZPLAYER_VIDEO;
    if (USE_ZPLAYER && !_kiem_tra_ios_7()) {
        $v_add = "style='position: absolute;left: -7px;right:-7px'";
        $v_start_div = "<div align='center' style='position: relative;'>";
        $v_end_div = "</div>";
    }
    // Thực hiện với bài là bài tường thuật
    if ($v_is_bai_tuong_thuat) {
        $p_body = preg_replace('/(<div id="video-hd.*>).*(<\/div>)/msU','$1$2',$p_body);
        $v_type_video = TYPE_ADS_DEFAULT;
        if(intval($v_type_quang_cao) > 0){ // nếu là quảng cáo write thì gán mã loại video chính bằng mã loại quảng cáo
            $v_type_video = $v_type_quang_cao;
        }
        $v_param_extension_tt = array(
            'v_arr_script'=>$v_script,
            'v_width'=>HEIGHT_ZPLAYER_VIDEO,
            'v_height'=> $v_height,
            'v_start_div'=>$v_start_div,
            'v_end_div'=>$v_end_div,
            'v_row_news'=> $p_row_news,
            'v_row_cat'=> $p_row_cat,
            'v_type_quang_cao'=> $v_type_quang_cao,
            'v_type_video'=> $v_type_video,
            'v_url_news' => $v_url_news,
            'v_is_bai_tuong_thuat'=>$v_is_bai_tuong_thuat,
            'v_is_quang_cao_write'=>$v_is_quang_cao_write,
            'v_list_ma_content'=> $v_list_ma_content,
            'v_stt_video_amp'=>$p_param_extension['v_stt_video_amp']
        );
		$v_param_extension_tt = array_merge($v_param_extension_tt,$p_param_extension);
        $p_body = _vd_xu_ly_code_video_ngoai_tuong_thuat($p_body, $v_file_video, $v_param_extension_tt);
        // thay thế hàm javascript ẩn hiện video
        for ($i=0;$i<$v_count;$i++) {
            if (strpos($v_file_video[1][$i],'http://') === false && strpos($v_file_video[1][$i],'https://') === false) {
                $v_file_video[1][$i] = IMAGE_VIDEO.$v_file_video[1][$i];
            }
            $p_body = str_replace("show_hide_block('".$id_div_video[1][$i]."');","show_hide_block99('".$id_div_video[1][$i]."');if(document.getElementById('".$id_div_video[1][$i]."').style.display == 'block'){xoa_noi_dung_cac_div(".str_replace('"',"'",json_encode($id_div_video[1])).",'".$id_div_video[1][$i]."');write_outsite_video_player('".$id_div_video[1][$i]."', ".$v_type_video.", '".$v_file_video[1][$i]."', '', '100%', '300','','','','','','','','','$v_url_news','',$v_is_bai_tuong_thuat)};",$p_body);
        }
    } else {
        // xử lý hiện script video ko phải là bài tường thuật
        $v_param_extension_tt = array(
            'v_arr_script'=>$v_script,
            'v_width'=>WIDTH_ZPLAYER,
            'v_height'=> $v_height,
            'v_start_div'=>$v_start_div,
            'v_end_div'=>$v_end_div,
            'v_row_news'=> $p_row_news,
            'v_row_cat'=> $p_row_cat,
            'v_type_quang_cao'=> $v_type_quang_cao,
            'v_type_video'=> $v_type_video,
            'v_url_news' => $v_url_news,
            'v_is_bai_tuong_thuat'=>$v_is_bai_tuong_thuat,
            'v_is_quang_cao_write'=>$v_is_quang_cao_write,
            'v_list_ma_content'=> $v_list_ma_content,
        );
        $v_param_extension_tt = array_merge($v_param_extension_tt,$p_param_extension);
        $p_body = _vd_xu_ly_code_video_ngoai_tuong_thuat($p_body, $v_file_video, $v_param_extension_tt);
    }
    return $p_body;
}
/* Xu ly code video bài tường thuật
 *param : $p_body  chuỗi nội dung chưa code video
 *param : $p_file_video mảng video
 *param : $v_param_extension_tt mảng chứa các tham số cần truyền vào
 *return string
 */
function _vd_xu_ly_code_video_ngoai_tuong_thuat($p_body, $p_file_video, $p_param_extension) {
    // thiết lập các tham số cần dùng được truyền từ ngoài vào
    $v_arr_script  = $p_param_extension['v_arr_script'];
    $v_width       = $p_param_extension['v_width'];
    $v_height      = $p_param_extension['v_height'];
    $v_start_div   = $p_param_extension['v_start_div'];
    $v_end_div     = $p_param_extension['v_end_div'];
    $v_row_news    = $p_param_extension['v_row_news'];
    $v_row_cat     = $p_param_extension['v_row_cat'];
    $v_type_quang_cao = $p_param_extension['v_type_quang_cao'];
    $v_type_video  = $p_param_extension['v_type_video'];
	$v_is_quang_cao_write = $p_param_extension['v_is_quang_cao_write'];
    $v_region       = strtolower($p_param_extension['v_region_value']);
    $v_url_news     = $p_param_extension['v_url_news'];
    $v_news_id      = intval($v_row_news['ID']);
    $v_date_create_news_video = empty($v_row_news['DateCreated'])?$v_row_news['Date']:$v_row_news['DateCreated'];
    $v_is_bai_tuong_thuat    = intval($p_param_extension['v_is_bai_tuong_thuat']);
    $v_stt_video_amp    = intval($p_param_extension['v_stt_video_amp']);

	$v_arr_ga_video_content = _get_module_config('get_video_outsite','v_arr_ga_video_content');
    // Lấy mã loại giải đấu.
    $p_param_extension['v_ma_loai_giai_dau'] = _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($v_row_news['Body']);
    // Lấy thông số tracking quảng cáo
    $v_tracking_tong_video = _vd_lay_thong_so_tracking_theo_loai($v_row_news,$v_row_cat,$p_param_extension);
    $v_url_html_ga_load_trang_content = $v_arr_ga_video_content['load_trang_video_content'].$v_tracking_tong_video;
	/* end 11/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_mobile */
    $v_type = $v_type_video;
    $v_count_video = count($p_file_video[1]);
    if(intval($v_type_quang_cao) > 0){
        $v_type = $v_type_quang_cao;
    }

	// khởi tạo global load trang
	global $gl_ga_load_trang;

    /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
	global $gl_have_banner_sponsor;

	for($i=0;$i<$v_count_video;$i++) {

		//Begin 10-10-2017 : Thangnb xu_ly_video_amp
		$v_tmp_file_video = str_replace('",', '', $p_file_video[1][$i]);
		if(empty($gl_ga_load_trang) || intval($gl_ga_load_trang)==0){
			$gl_ga_load_trang = 1;
		}else{
			$v_url_html_ga_load_trang = '';
		}
		/* begin 11/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_mobile */
		if ($v_stt_video_amp >= 0) {
			$v_is_box_video_chon_loc = intval($_GET['p_box_video_chon_loc']);
			if (strpos($v_arr_script[0][$i],'vtvWrite') === false &&  strpos($v_arr_script[0][$i],'videoDoiTacWrite') === false) {
                // Với trương hợp video được gọi từ amp thì ga load trang chỉ hiển thị cho video đầu tiên
				if($v_stt_video_amp >= 1){
					$v_url_html_ga_load_trang_content ='';
				}
                $v_html_video = "$v_start_div<div id='video_player$v_news_id".$i."' style='margin-left:-3px' $v_add><script type='text/javascript'>window.addEventListener('load', function(){write_outsite_video_player('video_player$v_news_id".$i."', '$v_type', '$v_tmp_file_video', '".$v_url_html_ga_load_trang."', '".$v_width."', '".$v_height."', true,'iframe_video_player$v_news_id".$i."',$v_count_video,$i,".$v_is_box_video_chon_loc.",0,0, '$v_region', '$v_url_news','$v_date_create_news_video',$v_is_bai_tuong_thuat,'$v_url_html_ga_load_trang_content',1)});</script></div>$v_end_div";
			} else {
				$v_html_video = $v_arr_script[0][$i];
			}
		} else {
			$v_html_video = "$v_start_div<div id='video_player$v_news_id".$i."' class='video_player minus-margin-v' style='margin-left:-3px;margin-bottom:13px;' $v_add><script type='text/javascript'>window.addEventListener('load', function(){write_outsite_video_player('video_player$v_news_id".$i."', '$v_type', '$v_tmp_file_video', '".$v_url_html_ga_load_trang."', '".$v_width."', '".$v_height."', true,'iframe_video_player$v_news_id".$i."',$v_count_video,$i,0,0,0, '$v_region', '$v_url_news','$v_date_create_news_video',$v_is_bai_tuong_thuat,'$v_url_html_ga_load_trang_content',1)});</script></div>$v_end_div";
		}
        /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
        // bài tường thuật nếu chưa video ngoài thì ko add qc ngoài iframe, quảng cáo sẽ được add trong iframe giống video trong nội dung tường thuật
        if(!$gl_have_banner_sponsor && !$v_is_bai_tuong_thuat){ // banner chưa được gán vào video nào

            $p_param_extension['v_add_banner_sponsor'] = true;
            $p_param_extension['v_add_class_banner_sponsor'] = 'outiframe_banner_sponsor';
            $v_html_video = _vd_thiet_lap_banner_sponsor_video($v_html_video,$v_row_news, $v_row_cat,$p_param_extension);
            $gl_have_banner_sponsor = 1;
        }
        /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
		/* end 11/10/2017 TuyenNT xu_ly_gan_ga_video_theo_loai_giai_dau_frontend_mobile */
		if ($v_stt_video_amp >= 0 && $i == $v_stt_video_amp) {
			return $v_html_video;
		}
		/* Begin anhpt1 11/07/2016 xu_ly_anh_dai_dien_video_bai_tuong_thuat */
        $p_body = str_replace($v_arr_script[0][$i],$v_html_video,$p_body);
		/* End anhpt1 11/07/2016 xu_ly_anh_dai_dien_video_bai_tuong_thuat */
		//End 10-10-2017 : Thangnb xu_ly_video_amp
	}
	return $p_body;
}
/**
 * Hàm thực hiện thiết lập banner sponser video cho video
 * @return array
 */
function _vd_thiet_lap_banner_sponsor_video($p_code_video,$p_row_news, $p_row_cat,$p_param_extension){
    // lấy cấu hình banner
    $v_is_box_video_chon_loc = $p_param_extension['v_is_box_video_chon_loc'];
    $v_add_banner_sponsor = $p_param_extension['v_add_banner_sponsor'];
    $v_add_class_banner_sponsor = $p_param_extension['v_add_class_banner_sponsor'];
    if(!$v_is_box_video_chon_loc && _hien_thi_banner_san_pham($p_row_cat['ID'],$p_row_news['c_list_ma_content'])){
        $v_str_html_banner_sponser_video = _get_module_config('cau_hinh_dung_chung', 'v_str_html_banner_sponser_video');
        // thay thế vị trí banner cho video đầu tiên
        if($v_add_banner_sponsor){
            if($v_add_class_banner_sponsor){// nếu là quảng cáo ngoài iframe
                $v_script_resize_banner = '
                    window.addEventListener("resize",function(){
                        var v_width     = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
						v_width     	= v_width -9;
                        post_message_banner_video_sponsor(v_width);
                    });';
                $v_str_html_banner_sponser_video = str_replace(array('class="zone_banner_sponsor_video"','</script>'), array('class="'.$v_add_class_banner_sponsor.'"',$v_script_resize_banner.'</script>'), $v_str_html_banner_sponser_video);
            }else{// quảng cáo trong iframe (bài tường thuật)
                $v_script_resize_banner = '
                    <!--TEXT_JS_LOAD_QUANG_CAO_BEFORE-->
                        if (window.addEventListener){ addEventListener("message", process_mesage_out_iframe_video, false);} else {attachEvent("onmessage", process_mesage_out_iframe_video);}
                    <!--TEXT_JS_LOAD_QUANG_CAO_AFTER-->';
                $v_str_html_banner_sponser_video = str_replace('</script>', $v_script_resize_banner.'</script>', $v_str_html_banner_sponser_video);
            }
            $p_code_video .= '<!--Begin:position_banner_sponsor_video-->'.$v_str_html_banner_sponser_video.'<!--End:position_banner_sponsor_video-->';
        }else{
			$v_script_resize_banner = '
                <!--TEXT_JS_LOAD_QUANG_CAO_BEFORE-->
                    if (window.addEventListener){ addEventListener("message", process_mesage_out_iframe_video, false);} else {attachEvent("onmessage", process_mesage_out_iframe_video);}
                <!--TEXT_JS_LOAD_QUANG_CAO_AFTER-->';
            $v_str_html_banner_sponser_video = str_replace('</script>', $v_script_resize_banner.'</script>', $v_str_html_banner_sponser_video);
            $p_code_video = preg_replace('#<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->#', '<!--Begin:position_banner_sponsor_video-->'.$v_str_html_banner_sponser_video.'<!--End:position_banner_sponsor_video-->', $p_code_video, 1);
        }
    }
    return $p_code_video;
}
function _vd_thiet_lap_banner_sponsor_video_no_iframe($p_code_video,$p_row_news, $p_row_cat,$p_param_extension){
    // lấy cấu hình banner
    $v_is_box_video_chon_loc = $p_param_extension['v_is_box_video_chon_loc'];
    if(!$v_is_box_video_chon_loc && _hien_thi_banner_san_pham($p_row_cat['ID'],$p_row_news['c_list_ma_content'])){
        $v_str_html_banner_sponser_video = _get_module_config('cau_hinh_dung_chung', 'v_str_html_banner_sponser_video');
        // thay thế vị trí banner cho video đầu tiên
        $p_code_video = preg_replace('#<!--Begin:position_banner_sponsor_video--><!--End:position_banner_sponsor_video-->#', '<!--Begin:position_banner_sponsor_video-->'.$v_str_html_banner_sponser_video.'<!--End:position_banner_sponsor_video-->', $p_code_video, 1);
    }
    return $p_code_video;
}
/** Author: Tytv 08/12/2016
 * Hàm kiểm tra bài viết có video hay không?
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return boolean
 */
 /* Begin - Tytv 11/09/2017 - fix_loi_anh_dai_dien_video_trang_tong_hop */
function _vd_kiem_tra_bai_viet_co_video($p_body) {
    /* begin 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
    if ((strpos($p_body, 'flashWrite')!== false) || (strpos($p_body, 'antvWrite')!== false) || (strpos($p_body, 'ballball')!== false) || (strpos($p_body, 'vtvWrite')!== false) || (strpos($p_body, 'quangcaoWrite')!== false) || (strpos($p_body, 'ballballWrite2')!== false) || (strpos($p_body, 'emobi_write')!== false)) {
    /* end 19/9/2017 TuyenNT xu_ly_frontend_ho_tro_video_emobi_3_phien_ban */
        return true;
    }
    return false;
}
/**
 * Hàm lấy chế độ xem video theo chuyên mục
 * @params $p_cate_id  Id chuyên mục
 * @return int giá trị quy ước theo cấu hình
 */
function _vd_get_che_do_play_video($p_cate_id = '', $p_row_news = []) {
	$v_gia_tri_play_video = 0;
	$v_arr_gia_tri_play_video = fe_danh_sach_gia_tri_theo_ma_danh_muc('CHE_DO_PLAY_VIDEO');
	if(check_array($v_arr_gia_tri_play_video)){
		$v_arr_gia_tri_play_video = _array_convert_index_to_key($v_arr_gia_tri_play_video, 'c_ma_gia_tri');
		$v_gia_tri_play_video = trim(strtolower($v_arr_gia_tri_play_video['CHE_DO_PLAY_VIDEO_MOBILE']['c_ten']));
        $v_gia_tri_play_video = ($v_gia_tri_play_video == 'true' || $v_gia_tri_play_video == 1)?1:0;
	}
	if (_is_test_domain()) {
		//$v_gia_tri_play_video = 1;
	}

	if ($v_gia_tri_play_video != 0) {
        $v_region = get_region_value();

        # 20230508 check thời gian hiệu lực của các cấu hình chuyên mục/giải đấu
        $v_arr_chuyen_muc_theo_danh_muc_gia_tri = fe_read_key_and_decode('data_danh_sach_chuyen_muc_theo_danh_muc_gia_tri', _CACHE_TABLE);

        # XLCYCMHENG-40913 - video autoplay theo giải đấu
        if (check_array($p_row_news) && trim($p_row_news['Body']) != ''){
            if (!isset($p_row_news['p_str_file_video'])){
                $v_giai_dau = strtolower(_24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_row_news['Body']));
            }
            else {# hỗ trợ check theo url video
                $v_giai_dau = strtolower(_24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_row_news['p_str_file_video']));
            }
            if ($v_giai_dau != ''){
                $v_giai_dau_autoplay_video = strtolower($v_region) == 'us' ? 'DANH_SACH_GIAI_DAU_AUTOPLAY_VIDEO_MOBILE_US' : 'DANH_SACH_GIAI_DAU_AUTOPLAY_VIDEO_MOBILE';

                # 20230508 check thời gian hiệu lực của các cấu hình chuyên mục/giải đấu
                $check_active_time = true;
                if (check_array($v_arr_chuyen_muc_theo_danh_muc_gia_tri)){
                    foreach ($v_arr_chuyen_muc_theo_danh_muc_gia_tri as $v_chuyen_muc_theo_danh_muc_gia_tri){
                        if (
                            $v_chuyen_muc_theo_danh_muc_gia_tri['c_ma_danh_muc'] == 'CHE_DO_PLAY_VIDEO'
                            && $v_chuyen_muc_theo_danh_muc_gia_tri['c_ma_gia_tri'] == $v_giai_dau_autoplay_video
                            ){
                                $v_start_time = trim($v_chuyen_muc_theo_danh_muc_gia_tri['c_ngay_bat_dau']);
                                $v_end_time = trim($v_chuyen_muc_theo_danh_muc_gia_tri['c_ngay_ket_thuc']);
                                if ($v_start_time != ''){
                                    if (strtotime($v_start_time) > time() || strtotime($v_end_time) < time()){
                                        $check_active_time = false;
                                    }

                                    break;
                                }
                        }
                    }
                }

                if ($check_active_time){
                    $v_ds_giai_dau_autoplay = get_gia_tri_danh_muc_dung_chung('CHE_DO_PLAY_VIDEO', $v_giai_dau_autoplay_video);

                    if (strtolower($v_ds_giai_dau_autoplay) == 'all'){
                        return 3;// tất cả giải đấu cho phép auto -> trả kết quả luôn ko cần check chuyên mục
                    }

                    if ($v_ds_giai_dau_autoplay != '') {
                        $v_arr_giai_dau_autoplay = explode(',', strtolower($v_ds_giai_dau_autoplay));
                        if (in_array($v_giai_dau, $v_arr_giai_dau_autoplay)){
                            return 3;// bài viết nằm trong giải đấu cho phép auto -> trả kết quả luôn ko cần check chuyên mục
                        }
                    }
                }
            }
        }

        $v_autoplay_video = 'DANH_SACH_CHUYEN_MUC_AUTOPLAY_VIDEO_MOBILE';
        if (strtolower($v_region) == 'us') {
            $v_autoplay_video = 'DANH_SACH_CHUYEN_MUC_AUTOPLAY_VIDEO_MOBILE_US';
        }

        # 20230508 check thời gian hiệu lực của các cấu hình chuyên mục/giải đấu
        $check_active_time = true;
        if (check_array($v_arr_chuyen_muc_theo_danh_muc_gia_tri)){
            foreach ($v_arr_chuyen_muc_theo_danh_muc_gia_tri as $v_chuyen_muc_theo_danh_muc_gia_tri){
                if (
                    $v_chuyen_muc_theo_danh_muc_gia_tri['c_ma_danh_muc'] == 'CHE_DO_PLAY_VIDEO'
                    && $v_chuyen_muc_theo_danh_muc_gia_tri['c_ma_gia_tri'] == $v_autoplay_video
                    ){
                        $v_start_time = trim($v_chuyen_muc_theo_danh_muc_gia_tri['c_ngay_bat_dau']);
                        $v_end_time = trim($v_chuyen_muc_theo_danh_muc_gia_tri['c_ngay_ket_thuc']);
                        if ($v_start_time != ''){
                            if (strtotime($v_start_time) > time() || strtotime($v_end_time) < time()){
                                $check_active_time = false;
                            }

                            break;
                        }
                }
            }
        }

        if ($check_active_time){
            $v_danh_sach_id_chuyen_muc_autoplay = $v_arr_gia_tri_play_video[$v_autoplay_video]['c_ten'];
            if ($v_danh_sach_id_chuyen_muc_autoplay != '') {
                if(strtoupper($v_danh_sach_id_chuyen_muc_autoplay)== 'ALL'){
                    return 3;
                }
                $v_arr_danh_sach_id_chuyen_muc_autoplay = explode(',', $v_danh_sach_id_chuyen_muc_autoplay);
                if ($p_cate_id > 0) {
                    // Kiểm tra chuyên mục nếu là chuyên mục con thì thiết lập chế độ theo chuyên mục cha
                    $row_cat = fe_chuyen_muc_theo_id($p_cate_id);
                    $p_cate_id = (intval($row_cat['Parent']) == 0 ? $p_cate_id : intval($row_cat['Parent']));
                    if (in_array($p_cate_id, $v_arr_danh_sach_id_chuyen_muc_autoplay)) {
                        $v_gia_tri_play_video = 3;
                    } else {
                        $v_gia_tri_play_video = 1;/* edit: Tytv - 18/07/2017 - off_countdown_play_video_cm_ko_autoplay */
                    }
                } else {
                    $v_gia_tri_play_video = 1;/* edit: Tytv - 18/07/2017 - off_countdown_play_video_cm_ko_autoplay */
                }
            } else {
                $v_gia_tri_play_video = 3;
            }
        }
        else {
            $v_gia_tri_play_video = 1;
        }
	}
    if (_is_test_domain()) {
		//$v_gia_tri_play_video = 1;
	}
	return $v_gia_tri_play_video;
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
	/* begin 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
	$v_list_event = _lay_list_su_kien_cho_1_bai_viet($p_row_news['ID']);
    $p_param_extension['v_event'] = $v_list_event;
	/* end 09/07/2019 ducnq chong_tran_lanh_tho_video_theo_su_kien */
    /* Begin: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    // Bổ sung thêm ngày xuất bản
    $p_param_extension['PublishedDate2'] = $p_row_news['PublishedDate2'];
    /* End: 19-08-2019 TuyenNT xu_ly_cac_giai_dau_dac_biet_cho_phep_xem_video_trong_n_tieng */
    $p_param_extension['v_check_16_9'] = _vd_check_config_size_player($p_row_news);
    // nếu đã được set mặc định 16/9 từ bên người box
    if($p_param_extension['v_is_video_16_9']){
        $p_param_extension['v_check_16_9'] = true;
    }
    return $p_param_extension;
}
/**
 * Hàm thực hiện thiết lập các thông số quảng cáo cho video outside
 * @param array $p_url_video : Chuỗi đường dẫn video
 * @param array $p_row_news : mảng chứa các dữ liệu của 1 bài viết
 * @param array $p_row_cat : mảng chứa các dữ liệu chuyên mục
 * @param array  $p_param_extension : mảng dữ liệu chứa các tham số cần truyền vào (nếu cần truyền vào tham số nào thì gán vào 1 mảng với tên cụ thể. Đảm bảo để ko phải thêm tham số khác cho hàm)
 * @return string
 */
function _vd_thiet_lap_thong_so_quang_cao_video_outsite($p_url_video,$p_row_news, $p_row_cat, $p_param_extension) {

    // Thực hiện lấy các thông số quảng cáo video theo độ ưu tiên
    // ưu tiên 1: video đối tác + banner đối tác nhập trong tool quan ly video doi tac
    $v_region           = $p_param_extension['v_region_value'];
    $v_type_video       = $p_param_extension['v_type_video'];
    $v_type_quang_cao   = intval($p_param_extension['v_type_quang_cao']);
    $v_is_box_video_chon_loc  = $p_param_extension['v_is_box_video_chon_loc'];
    $v_url_news = $p_param_extension['v_url_news'];
	$v_is_bai_pr = $p_param_extension['v_is_bai_pr'];
    $v_is_show_banner_video = $p_param_extension['v_is_show_banner_video'];
    $v_cat_id = $p_row_cat['ID'];
	$v_start_time = microtime(true);
	$v_start_mem = memory_get_usage();

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

	if(trim($v_type_video)=='quang_cao_Write' || trim($v_type_video)=='quangcaoWrite'){// là bài video từ bài tường thuật
		$v_is_quang_cao_write = _vd_check_is_quang_cao_write($v_type_video);
		$v_type_quang_cao   = _vd_check_is_quang_cao_write($v_type_video,true);
	}else{// nếu là loại video kiểu số
		// Begin: tytv - 1/11/2017 - fix_loi_hien_thi_quang_cao_khong_dung_chien_dich
		if(!empty($v_ma_nguon_source)){ // nếu có mã nguồn loại video được xác định từ loại video
			$v_is_quang_cao_write = _vd_check_is_quang_cao_write($v_ma_nguon_source);
		}else{
			$v_is_quang_cao_write = _vd_check_is_quang_cao_write($p_url_video);
		}
		// End: tytv - 1/11/2017 - fix_loi_hien_thi_quang_cao_khong_dung_chien_dich
		$v_type_quang_cao   = intval($v_type_video);
	}
	$v_ma_loai_giai_dau = _24h_player_lay_ma_loai_giai_dau_theo_cau_hinh($p_url_video);
    log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 1 : Xu ly PHP ham _vd_thiet_lap_thong_so_quang_cao_video_outsite . Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    // lấy dữ liệu quảng cáo
    // phải là bài viết ko set layout ko hiện thị quảng cáo
    // Kiểm tra xem bài viết có thuộc layout preroll độc quyền không. Nếu thuộc thì sẽ lấy theo layout
    $v_cat_id_banner = lay_chuyen_muc_layout_quang_cao($p_row_news,$v_is_quang_cao_write);
    if(!$v_cat_id_banner && ($v_is_quang_cao_write || _vd_is_video_doi_tac($v_type_video)) && $v_is_show_banner_video == 1){
		$v_start_time = microtime(true);
		$v_start_mem = memory_get_usage();

        // nếu giải đấu là ngoại hạng anh thì thay thế file vast riêng
        $v_preroll = _24h_player_lay_file_vast_preroll_theo_chien_dich($v_ma_loai_giai_dau,$v_preroll);
        $v_overlay  = _24h_player_lay_file_vast_overlay_theo_chien_dich($v_ma_loai_giai_dau,$v_overlay);
        $v_end      = _24h_player_lay_file_vast_postroll_theo_chien_dich($v_ma_loai_giai_dau,$v_end);
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 2 : Xu ly ham _vd_thiet_lap_thong_so_quang_cao_video_outsite. Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    }else{
        $v_type_video = intval($v_type_video);
        $v_start_time = microtime(true);
        $v_start_mem = memory_get_usage();
        if ($v_type_video >= 0) {
            // lay video quang cao theo chuyên mục nếu xác định rõ loại video ( > 0 )
            $v_ten_key_quang_cao = ($v_is_box_video_chon_loc)? TEN_KEY_QUANG_CAO_VIDEO_CHON_LOC : TEN_KEY_QUANG_CAO_VIDEO;
            /* End: 17-06-2020 TuyenNT dieu_chinh_co_che_hien_thi_quang_cao_tren_video_noi_dung */
            $xmlAds = getKeyValue($v_ten_key_quang_cao.$v_cat_id_banner.'_'.$v_region, _CACHE_TABLE_QUANG_CAO);
            // bài pr và bài set chuyên mục layout không hiện thị quảng cáo video
			if(intval($v_is_bai_pr) == 1 || intval($v_is_show_banner_video) == 0){
				if (_24h_player_xu_ly_bai_viet_ko_chay_quang_cao_video()){
					$xmlAds = '';
				}
			}
			if ($xmlAds != '') {
				// lấy thông số quảng cáo từ chuỗi xml ads
				$v_arr_data_xmlAds = _vd_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds);
				$v_logo     = $v_arr_data_xmlAds['v_logo'];
				$v_before   = $v_arr_data_xmlAds['v_before'];
				$v_overlay  = $v_arr_data_xmlAds['v_overlay'];
				$v_preroll  = $v_arr_data_xmlAds['v_preroll'];
				$v_end      = $v_arr_data_xmlAds['v_end'];
			}
        }
        log_thoi_gian_thuc_thi($v_start_time, $v_start_mem, "Doan 4 : Xu ly Quang cao OCM. Loai video $v_type_video - URL bai viet : $v_url_news",WEB_ROOT.'logs/log_box_player_video_by_url.log');
    }

    # XLCYCMHENG-40731 - player - preroll - vast - add inventory_scope
    $v_preroll = _24h_player_ads_add_plus_params($v_preroll, ['row_news' => $p_row_news], 'mobile');

    // thực hiện tối ưu các tham số quảng cáo
    _vd_toi_uu_thong_so_quang_cao($v_before,$v_preroll,$v_overlay,$v_end,$v_url_news,$v_region_value,$v_ma_nguon_source);

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
 * hàm link ga dựa vào mã loại giải đấu của chiến dịch nivea
 * @author:Tytv // đổi tên từ hàm  get_link_theo_ma_loai_giai_dau
 *
 */
function _vd_get_link_theo_ma_loai_giai_dau($p_body){
    // Loại giải đấu nivea
    $v_arr_loai_giai_dau_nivea = _get_module_config('cau_hinh_dung_chung', 'v_arr_loai_giai_dau_nivea');
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
/*
* 20150528_haiLT_video_doi_tac
* check kiểu video có phải của đối tác hay không
* param p_type: kiểu video
*/
function _vd_is_video_doi_tac($p_type) {
    $p_type = intval($p_type);

	return ($p_type > 0 && $p_type < 999);
}
/**
 * Hàm lấy các thông sô quảng cáo từ chuỗi xmlads
 * @param array $xmlAds : Chuỗi xml chứa các thông tin về quảng cáo
 * @return array
 */
function _vd_xu_ly_lay_thong_so_quang_cao_tu_chuoi_xml_ads($xmlAds) {
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
 * Hàm thực hiện thay thế đường dẫn video theo cấu hình
 * @param array $p_file_video : file video cần thay thế
 * @return string
 */
function _vd_thay_the_duong_dan_video_theo_cau_hinh($p_file_video,$rows_news = array()){
    // Kiểm tra file video có tồn tại
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
        /* begin 04/07/2018 anhpt1 su_dung_domain_cdn_theo_cau_hinh */
        // sử dụng domain cdn theo cấu hình
        $p_file_video = _vd_use_domain_cdn_by_config($p_file_video);
        /* End 04/07/2018 anhpt1 su_dung_domain_cdn_theo_cau_hinh */
    }
    return $p_file_video;
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
                    $v_ghi_chu = $value1['c_ghi_chu'];
                    $v_arr_icon = explode('/',$v_url_icon);
                    $v_icon_name = end($v_arr_icon);
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
	$v_ngay_bai_viet = ((empty($p_row_news['PublishedDate2']))?$p_row_news['PublishedDate']:$p_row_news['PublishedDate2']);
    $v_time_moc = strtotime($v_moc_ngay_lay_thong_tin_file_video);
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
/* Tytv - 23/12/2016
 * Hàm thực hiện lấy mã sctip video trong bai viet theo vi tri cần lấy
 * @pram: $p_body       nội dung
 * @param: $p_vi_tri    Vị trí mã script cần lấy thứ tự tính từ 0
 * return: boolean
 */
/* Begin: Tytv - 16/06/2017 - fix_loi_anh_dai_dien_video_trong_bai_chua_nhieu_video */
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
/** Author: Tytv 06/02/2017
 * Hàm thực hiện lấy ảnh đại diện video html theo cơ chế:
    + Ưu tiên 1: Ảnh được cắt từ video (file ảnh là file video đầu tiên)
    + Ưu tiên 2: Nếu video không có ảnh cắt thì lấy ảnh upload cho video trên trang chủ
    + Ưu tiên 3: nếu không có ảnh upload cho video trên trang chủ thì lấy ảnh đại diện mặc định của video là màn hình xanh
 * @return string
 */
function _vd_lay_anh_dai_dien_video_player_trang_video($row_news,$p_body = ''){ /* edit: Tytv - 28/08/2017 - fix_loi_anh_dai_dien_video_trong_bai_chua_nhieu_video */
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
            if ((_vd_config_video_code_has_exits_in_string($v_ten_ma_video) && strtolower($v_region) == 'us') || strpos($v['Body'],'eplayer.js') !== false) {
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

/** Author: Tytv 26/12/2016
 * Hàm thực hiện lấy dữ liệu video liên quan của bài viết video
 * @return string
 */
function _vd_lay_video_lien_quan_cua_video_dang_phat($p_arr_data,$p_arr_id_loai){
    $v_data = array();
    if(check_array($p_arr_data)){
        $v_total = count($p_arr_data);
        $v_temp_arr = array();
        foreach ($p_arr_data as $key => $value) {
            if(_vd_is_video_news($value) && !in_array($value['ID'],$p_arr_id_loai)){
                $v_temp_arr[] = $value;
            }
        }
        if(check_array($v_temp_arr)){
            $v_data = array_slice($v_temp_arr, 0, SO_LUONG_VIDEO_LIEN_QUAN_TRANG_VIDEO);
        }
    }
    return $v_data;
}

/** Author: Tytv 26/12/2016
 * Hàm thực hiện lọc lấy bài viết có chứa video
 * @return string
 */
function _vd_loc_bai_viet_video($p_arr_data){
    if(!check_array($p_arr_data)){ return $p_arr_data;}
    $v_temp_arr = array();
    foreach ($p_arr_data as $key => $value) {
        if(_vd_is_video_news($value)){
            $v_temp_arr[] = $value;
        }
    }
    return $v_temp_arr;
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
    $v_is_box_video_chon_loc = intval($p_arr_extra_data['is_box_video_chon_loc']) ? true : false;
    // chỉ lấy video đầu tiên của bài viết
    $p_code_video = _vd_lay_ma_script_video_trong_bai($p_code_video,0);

    // Lấy cấu hình theo bài viết
    $v_arr_configs_bai_viet = _lay_thong_so_cau_hinh_bai_viet($row_news,$row_cat);
    $v_param_extension_video = $v_arr_configs_bai_viet;
    // thiết lập thêm 1 số tham số cần thiết xác định là trang video
    $v_param_extension_video['v_is_trang_bai_viet'] = false;
    $v_param_extension_video['v_is_box_video_chon_loc'] = $v_is_box_video_chon_loc;
    $v_param_extension_video['v_is_trang_video'] = true;
    $v_param_extension_video['v_is_box_xem_video_trang_video'] = true;
    // bổ xung 2 tham số để tạo url xác định video tiếp theo cho video đang phát trên trang video
    $v_param_extension_video['v_type_extraDataUrl'] = $p_arr_extra_data['type_extraDataUrl'];
    $v_param_extension_video['v_cat_video_id']      = $p_arr_extra_data['cat_video_id'];
    $v_param_extension_video['v_is_video_16_9']      = $p_arr_extra_data['v_is_video_16_9'];
	$v_param_extension_video['v_size_img_video_highlight']      = $p_arr_extra_data['v_size_img_video_highlight'];
	$v_param_extension_video['v_size_img_trong_ngay']      = $p_arr_extra_data['v_size_img_trong_ngay'];
    // kiểm code hiển thị video ở trang video là code video outside hay ko?
    if(_vd_check_is_code_video_outsite($p_code_video)){  // nếu video outside
        $p_code_video =  _vd_xu_ly_code_video_outsite_trang_video($p_code_video, $row_news, $row_cat, $v_param_extension_video);
    }else{ // nếu không phải là video outside
        // anhpt11
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
function _vd_html_chuyen_doi_vtv_write_sang_zplayer($p_body,$p_cat_id,$p_width_zplayer,$p_height_zplayer){
	if(empty($p_body)) return $p_body;
    if((strpos($p_body,'vtvWrite')===false)){
        return $p_body;
    }
    $v_url_video = '';

    preg_match('#vtvWrite\(([^\)]*)\);#ism', $p_body, $v_arr_matches_url);
    $v_url_video = trim($v_arr_matches_url[1]);
    $v_url_video = trim($v_url_video,'"');
    $v_url_video = trim($v_url_video,"'");

    if(!empty($v_url_video)){
        $v_html_video_zplayer = '';
        ob_start();
        $v_obj = new box_player_video_by_url_block();
		$v_obj->setParam('is_zplayer_html5', true);
        $v_obj->index($p_cat_id,3,$p_width_zplayer,$p_height_zplayer,$v_url_video);
        $v_html_video_zplayer = ob_get_clean();
        preg_match('#<!-- begin_media_player -->(.*?)<!-- end_media_player -->#ism', $v_html_video_zplayer, $v_arr_matches_player);
        $p_body = preg_replace('#vtvWrite\(([^\)]*)\);#', '</script>'.$v_arr_matches_player[0].'<script type="text/javascript">', $p_body, 1);
    }
    return $p_body;
}
/** Author: Tytv 08/12/2016
 * Hàm xử lý thay thế mã script antvWrite sang html video zplayer (video chạy bằng HTML 5)
 * @return string
 */
function _vd_html_chuyen_doi_antv_write_sang_zplayer($p_body,$p_cat_id,$p_width_zplayer,$p_height_zplayer){
	if(empty($p_body)) return $p_body;
    if((strpos($p_body,'antvWrite')===false)){
        return $p_body;
    }
    $v_id_video = 0;
    preg_match('#antvWrite\(([^\)]*)\);#ism', $p_body, $v_arr_matches_video);
    $v_id_video  = trim($v_arr_matches_video[1]);
    $v_id_video  = trim($v_id_video,"'");
    $v_id_video  = trim($v_id_video,'"');
    $v_id_video  = intval($v_id_video);
    if($v_id_video>0){
        $v_html_script_video = '';
        // lấy
        ob_start();
        $v_obj = new box_get_video_outsite_block();
        $v_player_id = 'div_video_antv_'.time();
        $v_obj->index(1,$v_id_video,$v_player_id,$p_width_zplayer,$p_height_zplayer);
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
            $v_obj2->index($p_cat_id,$v_type,$p_width_zplayer,$p_height_zplayer,$v_url_get_video);
            $v_html_video_zplayer = ob_get_clean();
            preg_match('#<!-- begin_media_player -->(.*?)<!-- end_media_player -->#ism', $v_html_video_zplayer, $v_arr_matches_player);
            $v_html_script_video = str_replace($v_arr_matches_video_outsite[0],$v_html_video_zplayer,$v_html_script_video);
            $p_body = preg_replace('#antvWrite\(([^\)]*)\);#', '</script>'.$v_html_script_video.'<script type="text/javascript">', $p_body, 1);
        }
    }
    return $p_body;
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
    $p_code_video = _vd_loai_bo_video_khong_duoc_hien_thi($p_code_video,$v_arr_config_video);

    //B3: Thiết lập dữ liệu quảng cáo  (thực hiện lấy các giá trị quảng cáo từ key, theo các yêu cầu cấu hình)
    $v_arr_data_ads = _24h_player_thiet_lap_thong_so_quang_cao($p_code_video,$p_row_news, $p_row_cat, $v_arr_config_video);

    //B4: Thiết lập dữ liệu gán tracking,ga
    $v_param_extension_tracking =  array_merge($v_arr_config_video, $v_arr_data_ads);
    $v_arr_data_tracking = _24h_player_thiet_lap_thong_so_tracking($p_row_news, $p_row_cat, $v_param_extension_tracking);

    //B5: Thay thế dạng script write tùy theo cấu hình
    $p_code_video = _24h_player_chuyen_doi_giua_cac_ma_script_video($p_code_video,$v_arr_config_video);

    //B6: Thiết lập dữ liệu highlight video
    $v_arr_data_highlight_video = _24h_player_thiet_lap_du_lieu_highlight_video($p_row_news,$v_arr_config_video); /* edit: Tytv - 1/11/2017 - fix_loi_highlight_box_video_chon_loc */

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

    $v_date = ($row_news['PublishedDate2'] > '1970')? $row_news['PublishedDate2']:$row_news['Date'];
    $v_str_date = date('d/m/Y H:i', strtotime($v_date));

    $right_icon = '';
    $v_comment = fe_tong_so_comment_1_bai_viet($row_news['ID']);
    if ($v_comment > 0) {
        $right_icon .= "<span class=\"icon-cmt\">$v_comment</span>";
    }
    if($v_is_tuong_thuat = check_is_news_tuong_thuat($row_news['c_icon_truc_tiep'])){
        $right_icon .= $v_is_tuong_thuat;
    }
    $v_post_id = _vd_tao_ma_gia_tri_post_id_cho_video_html5($v_url_news);  // thiet_lap_gia_tri_post_id
	// lấy thời lượng của video
    $v_html_thoi_luong_video = _vd_html_lay_thoi_luong_video_trong_bai($row_news);

    # XLCYCMHENG-40459 - [24H] Điều chỉnh kích thước ảnh resize sử dụng ở trang Video tổng hợp
    $v_anh_dai_dien_video = $row_news['SummaryImg_chu_nhat'] == '' ? $row_news['SummaryImg'] : $row_news['SummaryImg_chu_nhat'];
    $thumbnail_name = '255x170';
    $get_image_thumbnail = strtotime($row_news['PublishedDate2']) > strtotime('2022-01-01 11:00:00');

    $v_html .= '<article class="bxVid bxVidSht">
                <div class="bxVidImg">
                    <div class="mnItImg">
                        <a href="'.$v_url_video_news.'" title="'.$Title.'" class="vidImgVid">
                            '.html_image_thumbnail($v_anh_dai_dien_video, $v_anh_dai_dien_video, $row_news['Title'], $thumbnail_name, $get_image_thumbnail).'
                        </a>';
				if(!empty($v_html_thoi_luong_video)){
					 $v_html .= '<div class="lgVid">
									<span><a title="'.$Title.'"><img src="'.html_image('images/arrow-video.png',false).'" width="11" height="12" alt="'.$Title.'"></a></span><span class="tmLg1"> '.$v_html_thoi_luong_video.'</span>
								</div>';
				}
				$v_html .= '	<span style="'.(($news_id>0 && $news_id==$row_news['ID'])?'display:block':'display:none').'" title="Đang chạy" class="dangchay">Đang phát</span>
                    </div>
                </div>
                <div class="bxVidTit">
                    <header class="mnVidTit">
                        <div class="mnItTit">
                            <a href="'.$v_url_video_news.'" title="'.$Title.'">'.$Title.$right_icon.'</a>
                        </div>
                    </header>
                    <span class="tmPst clrGr" datetime="'.$v_str_date.'">'.$v_str_date.'</span>
                </div>
            </article>';
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
/** Author: Tytv 08/02/2017 thiet_lap_gia_tri_post_id
 * Hàm tạo mã giá trị postId cho video chạy html5
 * @param string chuỗi dữ liệu (có thể là chuối url bài viết hoặc ID bài viết)
 * @return string
 */
function _vd_tao_ma_gia_tri_post_id_cho_video_html5($p_value) {
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
/*
 * Ham check quang cao rong hay khong
 * Author: Thangnb 23-03-2015
 * Param: $p_xml_ads
 * Return : true or false
*/

function _vd_check_rong_quang_cao($p_xml_ads) {
	preg_match('#<corner>(.*)</corner>#', $p_xml_ads, $corner);
	preg_match('#<before>(.*)</before>#', $p_xml_ads, $before);
	preg_match('#<overlay>(.*)</overlay>#', $p_xml_ads, $overlay);
	preg_match('#<preroll>(.*)</preroll>#', $p_xml_ads, $preroll);
	preg_match('#<end>(.*)</end>#', $p_xml_ads, $end);
	if (($corner[1] == '' || $corner[1] == '/') && ($before[1] == '' || $before[1] == '/') && ($overlay[1] == '' || $overlay[1] == '/') && ($preroll[1] == '' || $preroll[1] == '/')	&& ($end[1] == '' || $end[1] == '/')) {
		return true;
	} else {
		return false;
	}
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
/** Author: Tytv 08/12/2016
 * Hàm hiển thị code html player video đầu trang video
 * @param array $p_code_video : chuỗi có chứa code video
 * @param array $p_arr_extra_data : mảng chứa các dữ liệu của 1 bài viết
 * @return string
 */
function _vd_hien_thi_video_box_video_chon_loc($p_code_video,$p_arr_extra_data) {

    $row_news   = $p_arr_extra_data['row_news'];
    $row_cat    = $p_arr_extra_data['row_cat'];
    // chỉ lấy video đầu tiên của bài viết

    // Lấy cấu hình theo bài viết
    $v_arr_configs_bai_viet = _lay_thong_so_cau_hinh_bai_viet($row_news,$row_cat);
    $v_param_extension_video = $v_arr_configs_bai_viet;
    // thiết lập thêm 1 số tham số cần thiết xác định là trang video
    $v_param_extension_video['v_is_trang_bai_viet'] = true;
    $v_param_extension_video['v_is_box_video_chon_loc'] = true;
    $v_param_extension_video['v_is_trang_video'] = false;
    $v_param_extension_video['v_is_box_xem_video_trang_video'] = false;
    $v_param_extension_video['v_use_no_highlight_video'] = 1;
    // bổ xung 2 tham số để tạo url xác định video tiếp theo cho video đang phát trên trang video
    $v_param_extension_video['v_type_extraDataUrl'] = $p_arr_extra_data['type_extraDataUrl'];
    $v_param_extension_video['v_cat_video_id']      = $p_arr_extra_data['cat_video_id'];

    $p_code_video = _vd_xu_ly_code_video_thong_thuong($row_news['Body'], $row_news, $row_cat, $v_param_extension_video);

    return $p_code_video;
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
    $v_arr_data_ads = _vd_thiet_lap_thong_so_quang_cao_video_outsite($p_str_file_video,$p_row_news, $p_row_cat, $v_arr_config_video);

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
 * Hàm check code cấu hình size player
 *  * Return : true or false ; true: player 16:9, false: hiển thị như bthuong
 */
function _vd_check_config_size_player($p_row_news){
    if(!check_array($p_row_news)){
        return false;
    }
    // Uu tiên kiểm tra theo chuyên mục
    $arr_config_cate = fe_read_key_and_decode('data_danh_sach_config_size_player_type_1', _CACHE_TABLE);
    if(check_array($arr_config_cate)){
        $v_list_category_id = $p_row_news['c_list_category_id'];
        if($v_list_category_id != ''){
            $v_arr_cate = explode(',', $v_list_category_id);
            for($i=0;$i<count($v_arr_cate);$i++){
                $v_cate_id = intval($v_arr_cate[$i]);
                if($v_cate_id > 0 && in_array($v_cate_id, $arr_config_cate)){
                    return true;
                }
            }
        }
        // Kiểm tra theo layout
        $v_arr_bai_viet_gan_banner_rieng = fe_danh_sach_bai_viet_xuat_ban_banner_rieng($p_row_news['ID']);
        if (isset($v_arr_bai_viet_gan_banner_rieng[$p_row_news['ID']]['category_banner']) && $v_arr_bai_viet_gan_banner_rieng[$p_row_news['ID']]['category_banner'] != '') {
            $v_cat_id_banner = $v_arr_bai_viet_gan_banner_rieng[$p_row_news['ID']]['category_banner'];
            if(in_array($v_cat_id_banner, $arr_config_cate)){
                return true;
            }
        }
    }
    // Kiểm tra theo sự kiện
    if(intval($p_row_news['ID']) >0){
        $arr_config_event = fe_read_key_and_decode('data_danh_sach_config_size_player_type_2', _CACHE_TABLE);
        if(!check_array($arr_config_event)){
            return false;
        }
        $v_list_event_id = $p_row_news['c_list_event_id'];
        if($v_list_event_id != ''){
            $v_arr_event = explode(',', $v_list_event_id);
            for($i=0;$i<count($v_arr_event);$i++){
                $v_event_id = intval($v_arr_event[$i]);
                if($v_event_id > 0 && in_array($v_event_id, $arr_config_event)){
                    return true;
                }
            }
        }
    }
    return false;
}
