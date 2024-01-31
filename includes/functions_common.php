<?php
/**
 * Tra lai array chua danh sach may chu
*/
function get_server_list($p_server_file = '')
{
	// Neu ko truyen tham so thi lay d/s may chu phien ban web
	if ($p_server_file==''){
		$p_server_file = dirname(WEB_ROOT) . "/deploy_code/scp_template.txt";
	}	

    $lines = file($p_server_file);
    
    $arrServer= array();
    $intServer = 0;

    for( $i=0, $s=sizeof($lines); $i<$s; ++$i) {
        $line = trim($lines[$i]);
        // $curLine = $line_template;
        if (preg_match('#^scp#', $line)) { // dong can xy ly
            $port = 8080;
            $ssh_port = '20122';
            if( preg_match('#20222#', $line)) {
                $port = 8081;
                $ssh_port = '20222';
            }
            if (preg_match('#@([a-z\.0-9]*24h.com.vn):#', $line, $result)) {
                $server = $result[1];
            } else if (preg_match('#@([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+):#', $line, $result)) {
                $server = $result[1];
            }else {
                continue;
            }
            $port = 80;
            $arrServer[$intServer]['port'] = $port;
            $arrServer[$intServer]['server'] = $server;
            $arrServer[$intServer]['ssh_port'] = $ssh_port;
            ++$intServer;
        }
    }

    return $arrServer;
}
/**
 * Hàm thực hiện replace content cuối cùng
*/
function _qc_static_replace($content){
    return $content;
}
/*
 * hàm thực hiện kiếm tra có phải là phiên bản pc
 * Params :
 	$p_link : Link nhap vao de kiem tra
 * return : Array or Link
*/
function _is_thiet_bi_pc($p_device_global = '') {
	if ($p_device_global == NAME_THIET_BI_PC || $p_device_global == NAME_BOT_PC) {
		return true;
	}
	return false;
}
/*
 * hàm thực hiện kiếm tra có phải là phiên bản mobile
 * Params :
 	$p_link : Link nhap vao de kiem tra
 * return : Array or Link
*/
function _is_thiet_bi_mobile($p_device_global = '') {
	if ($p_device_global == NAME_THIET_BI_MOBILE || $p_device_global == NAME_BOT_MOBILE) {
		return true;
	}
	return false;
}
/*
 * hàm thực hiện kiếm tra có phải là phiên bản ipad
 * Params :
 	$p_link : Link nhap vao de kiem tra
 * return : Array or Link
*/
function _is_thiet_bi_ipad($p_device_global = '') {
	if ($p_device_global == NAME_THIET_BI_TABLET) {
		return true;
	}
	return false;
}
/*
 * hàm thực hiện kiếm tra có phải là phiên bản amp 
 * Params :
 	$p_link : Link nhap vao de kiem tra
 * return : Array or Link
*/
function _is_thiet_bi_amp($p_device_global = '') {
	if ($p_device_global == NAME_THIET_BI_AMP) {
		return true;
	}
	return false;
}
/********************************************************************************
Ten ham		:_read_file
Chuc nang	:Doc file
Tham so		
********************************************************************************/
function _read_file($p_file_path){
	$v_ret = "";
	$handle = fopen($p_file_path,"r");
	if($handle){
		while(!feof($handle)){
			$v_ret .= fread($handle,1000000);
		}
	}
	return $v_ret;
}
function asset($p_patch_css_js = ''){
    return BASE_URL_FOR_PUBLIC.$p_patch_css_js;
}

/**
 * Ham kiem tra mang du lieu
 * @param array $array : mang can kiem tra
 * @return boolean
 */
function check_array($p_array){
    if(is_array($p_array) and sizeof($p_array)>0){
        return true;
    }else{
        return false;
    }
}
/**
* Ham redirect về trang 404
* @param : Không có
* @return: Không
* @author: anhnt1 @date: 20/3/2014 @desc: create new
*/
function _redirect_to_404_page() {
    include WEB_ROOT . 'templates/missing_pc.php';
    die();
}

// begin 23-8-2018 BangND XLCYCMHENG_25639_xd_giao_dien_FE_magazine
function xu_ly_load_magazine_head_files($p_head_html, $p_arr_magazine_content)
{
    $v_css_html = $v_js_html = '';

    if (check_array($p_arr_magazine_content)) {
		$v_arr_head_file = json_decode($p_arr_magazine_content['c_head_files'], true);

		// xu ly remote url
		if (!empty($v_arr_head_file['remote'])) {
			foreach ($v_arr_head_file['remote'] as $file_type => $files) {
				if (!check_array($files)) continue;
				foreach ($files as $file) {
                    # 20220117 check file trong upload mới dùng
                    if (!filter_var($file['data'], FILTER_VALIDATE_URL) && strpos($file['data'], 'upload/') === false){
                        continue;
                    }

					if ($file['type'] == 'css') {
						$v_css_html .= '<link rel="stylesheet" type="text/css" href="'. $file['data'] .'" />';
					} elseif ($file['type'] == 'js') {
						$v_js_html .= '<script src="'. $file['data'] .'"></script>';
					}
				}
			}
		}
		$v_arr_local_files = array();

		if (!empty($v_arr_head_file['min'])) {
			$v_arr_local_files = $v_arr_head_file['min'];
		} elseif (!empty($v_arr_head_file['uploaded'])) {
			$v_arr_local_files = $v_arr_head_file['uploaded'];
		}

		if (!empty($v_arr_local_files)) {
			foreach ($v_arr_local_files as $file_type => $files) {
				if (!check_array($files)) continue;
				foreach ($files as $file) {
					// cac file da dc upload len server
					if (!empty($file['fileupload_id'])) {
						$v_url = rtrim(IMAGE_STATIC, '/') . '/' . ltrim($file['data'], '/');
					} else {
						$v_url = $file['data'];
					}

                    # 20220117 check file trong upload mới dùng
                    if (!filter_var($v_url, FILTER_VALIDATE_URL) && strpos($v_url, 'upload/') === false){
                        continue;
                    }

					if (strtolower($file['type']) == 'css') {
						$v_css_html .= '<link rel="stylesheet" type="text/css" href="'. $v_url . '?v=' . $file['hash'] .'" />';
					} elseif (strtolower($file['type']) == 'js') {
						$v_js_html .= '<script src="'. $v_url . '?v=' . $file['hash'] .'"></script>';
					}
				}
			}
		}
    }

    $p_head_html = str_replace('<!--@css_magazine@-->',$v_css_html,$p_head_html);
    $p_head_html = str_replace('<!--@js_magazine@-->',$v_js_html,$p_head_html);
    return $p_head_html;
}

function get_title_desc_keyword_canonical_bai_viet($cat_id, $news_id, $row_news=array(), $row_event=array(), $row_seo_news=array()) {
/* End 02-11-2016 : Thangnb toi_uu_meta_bai_viet_xuat_ban_nhieu_chuyen_muc */
	if (!check_array($row_news)) {
		$row_news = fe_bai_viet_theo_id($news_id);
	}
	if (!check_array($row_cat)) {
		$row_cat = fe_chuyen_muc_theo_id($cat_id);
	}
	$row_event = array();
    
	$rs_template = template_by_seo_chi_tiet($rs_template, $row_seo_news);
    $rs_template = template_by_mac_dinh($rs_template, $row_cat, $row_event, $row_news);
    
	return $rs_template;
}

function template_by_seo_chi_tiet($rs_template, $row_seo_chi_tiet)
{
	if ((int)$row_seo_chi_tiet['c_trang_thai_xuat_ban'] == 1) {
		$v_title = fw24h_strip_tags($row_seo_chi_tiet['c_title']);
		$v_desc = fw24h_strip_tags($row_seo_chi_tiet['c_desc']);
		$v_keyword = fw24h_strip_tags($row_seo_chi_tiet['c_keyword']);
		$v_canonical = fw24h_strip_tags($row_seo_chi_tiet['c_canonical']);
        // begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
        $v_title_mxh = fw24h_strip_tags($row_seo_chi_tiet['c_title_mxh']);
        $v_desc_mxh = fw24h_strip_tags($row_seo_chi_tiet['c_des_mxh']);
        // end 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh

		//Begin 15-03-2017 : Thangng sua_loi_ki_tu_dac_biet_title_desc
		if ($v_title) {
			$rs_template['title'] = _fix_ki_tu_dac_biet_title_desc($v_title);
		}
		if ($v_desc) {
			$rs_template['desc'] = _fix_ki_tu_dac_biet_title_desc($v_desc);
		}
		if ($v_keyword) {
			$rs_template['keyword'] = _fix_ki_tu_dac_biet_title_desc($v_keyword);
		}
		if ($v_canonical) {
			$rs_template['canonical'] = $v_canonical;
		}
        if ($v_title_mxh) {
			$rs_template['c_title_mxh'] = $v_title_mxh;
		}
        if ($v_desc_mxh) {
			$rs_template['c_des_mxh'] = $v_desc_mxh;
		}
	}
    return $rs_template;
}

/**
 * Hàm thực hiện xóa bỏ kí tự đặc biệt
 * @param string $p_content :Nội dung cần replace
 * @return string
 */
function _fix_ki_tu_dac_biet_title_desc($p_content = '') {
	if ($p_content != '') {
		$p_content = fw24h_strip_tags($p_content);
		$p_content = str_replace('"','“',$p_content);
	}
	return $p_content;
}
function template_by_mac_dinh($rs_template, $row_cat=array(), $row_event=array(), $row_news=array(), $row_tag=array())
{
	if ($rs_template['title'] == '') {
		if (check_array($row_news)) {
            // Begin: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
            $rs_template['title'] = fw24h_strip_tags($row_news['title_for_seo'] != ''?$row_news['title_for_seo'] :$row_news['Title']);
            // Begin: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
		} elseif (check_array($row_event)) {
            /* Begin anhpt1 27/07/2016 replace_tempalte_title_desc_su_kien */
            $row_event['title_tag'] = ($row_event['title_tag'] != '') ? $row_event['title_tag'] : $row_event['Name'];
            /* End anhpt1 27/07/2016 replace_tempalte_title_desc_su_kien */
			$rs_template['title'] = htmlspecialchars($row_event['title_tag']);
		} elseif (check_array($row_cat)) {
			$rs_template['title'] = $row_cat['Title'];
		} elseif (check_array($row_tag)) {
			$rs_template['title'] = ($row_tag['Title'] != '') ? $row_tag['Title'] : $row_tag['tag'];
		}
	}
	if ($rs_template['desc'] == '') {
		if (check_array($row_news)) {
            // Begin: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
            $rs_template['desc'] = fw24h_strip_tags(($row_news['motabaiviet'] != '')?$row_news['motabaiviet']:$row_news['Summary']);
            // End: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
		} elseif (check_array($row_event)) {
            /* Begin anhpt1 27/07/2016 replace_tempalte_title_desc_su_kien */
            $row_event['description_tag'] = ($row_event['description_tag'] != '') ? $row_event['description_tag'] : $row_event['sapo'];
			/* End anhpt1 27/07/2016 replace_tempalte_title_desc_su_kien */
            $rs_template['desc'] = htmlspecialchars($row_event['description_tag']);
		} elseif (check_array($row_cat)) {
			$rs_template['desc'] = $row_cat['Description'];
		} elseif (check_array($row_tag)) {
			$rs_template['desc'] = ($row_tag['Description'] != '') ? $row_tag['Description'] : $row_tag['tag'];
		}
	}
	if ($rs_template['keyword'] == '') {
		if (check_array($row_news)) {
			$rs_template['keyword'] = $row_news['keywords'];
		} elseif (check_array($row_event)) {
			$rs_template['keyword'] = ($row_event['keyword_tag'] != '')?$row_event['keyword_tag']:$row_cat['Keyword'];
		} elseif (check_array($row_cat)) {
			$rs_template['keyword'] = $row_cat['Keyword'];
		} elseif (check_array($row_tag)) {
			$rs_template['keyword'] = strtolower(fw24h_iso_ascii($row_tag['tag'], '')).', '.$row_tag['tag'];
		}
	}
	if ($rs_template['canonical'] == '') {
		if (check_array($row_news)) {
			$rs_template['canonical'] = get_url_canonical_of_news($row_news, $row_cat);
		} elseif (check_array($row_event)) {
			$rs_template['canonical'] = get_url_canonical_of_event($row_event, $row_cat);
		} elseif (check_array($row_cat)) {
			$rs_template['canonical'] = get_url_canonical_of_category($row_cat);
		} elseif (check_array($row_tag)) {
			$rs_template['canonical'] = get_tag_canonical($row_tag);
		}
	}
	if ($rs_template['keyword'] == '') {
		$rs_template['keyword'] = $rs_template['title'];
	}
	if ($rs_template['desc'] == '') {
		$rs_template['desc'] = '3do.vn - Mạng tin tức và thông tin giải trí Việt Nam';
	}
	//Begin 15-03-2017 : Thangng sua_loi_ki_tu_dac_biet_title_desc
	$rs_template['title'] = _fix_ki_tu_dac_biet_title_desc($rs_template['title']);
	$rs_template['desc'] = _fix_ki_tu_dac_biet_title_desc($rs_template['desc']);
	$rs_template['keyword'] = _fix_ki_tu_dac_biet_title_desc($rs_template['keyword']);
	//End 15-03-2017 : Thangng sua_loi_ki_tu_dac_biet_title_desc

    return $rs_template;
}
/**
 * Ham loại bỏ thẻ html và cắt xâu ký tự theo 1 độ dài tối đa
 * @param string $p_str : Chuỗi cần xử lý
 * @param boolean $p_special_char : Có thay thế ký tự đặc biệt hay không
 * @param number $p_limit_char : Độ dài tối đa của xâu được giữ lại
 * @return string
 * @author: none @date: none @desc: create new
 */
function fw24h_strip_tags($p_str, $p_special_char=false, $p_limit_char=0)
{
    $p_str = trim(fw24h_restore_bad_char($p_str));
    $p_str = strip_tags($p_str);
    $p_str = ($p_limit_char > 0) ? cutBrief($p_str, $p_limit_char) : $p_str;
    $p_str = $p_special_char ? htmlspecialchars($p_str) : $p_str;
    return $p_str;
}

/*
 * Hàm Thực hiện lấy url gốc của bài viết
 * @author  anhpt1 - 06/12/2017
 * @param array $rs_data mảng một chiều
 * @return boolean
 */
function get_url_canonical_of_news($p_news,$p_category = ''){
    // Kiểm tra xem có tồn tại bài viết không. Mặc định return về trang chủ
    if(!check_array($p_news)){
        return BASE_URL_FOR_PUBLIC;
    }
    $v_url_canonical = '';
    // Lấy url gốc của bài viết đã được tính toán từ trước
    if($p_news['c_canonical_url'] != ''){
        $v_url_canonical = $p_news['c_canonical_url'];
    }else{
        // Khai báo biến class tạo url
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
        $p_news['v_slug'] = get_news_slug($p_news, $p_category); // Lay slug bai viet
        $v_url_canonical = BASE_URL_FOR_PUBLIC.ltrim($urlHelper->url_news(array('ID'=>$p_news['ID'], 'cID'=>$p_news['CategoryID'], 'slug'=>$p_news['v_slug'])), '/');
    }
    return $v_url_canonical;
}
/*
 * Hàm Thực hiện lấy url gốc của bài viết
 * @author  anhpt1 - 06/12/2017
 * @param array $rs_data mảng một chiều
 * @return boolean
 */
function get_url_canonical_of_category($p_row_cat){
    // Kiểm tra xem có tồn tại bài viết không. Mặc định return về trang chủ
    if(!check_array($p_row_cat)){
        return BASE_URL_FOR_PUBLIC;
    }
    $v_url_canonical = '';
    // Lấy url gốc của bài viết đã được tính toán từ trước
    if(!is_null($p_row_cat['Link']) && $p_row_cat['Link'] != ''){
        $v_url_canonical = $p_row_cat['Link'];
    }else if($p_row_cat['c_canonical_url'] != ''){
        $v_url_canonical = $p_row_cat['c_canonical_url'];
    }else{
        // Khai báo biến class tạo url
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
        $v_url_canonical = BASE_URL_FOR_PUBLIC.ltrim($urlHelper->url_cate(array('ID'=>$p_row_cat['ID'], 'slug'=>get_category_slug($p_row_cat))), '/');
    }
    return $v_url_canonical;
}
/*
 * Hàm lấy slug bài viết
 * Params :
 	$p_news : arr Mảng dữ liệu chứa bài viết
 * return : string
*/
function get_news_slug($p_news, $p_category)
{
    $v_category_slug = get_category_slug($p_category);
    $v_news_slug = ($p_news['SlugTitle'] != '') ? $p_news['SlugTitle'] : $p_news['Title'];
    $v_news_slug = $v_category_slug.'/'.$v_news_slug;

    return $v_news_slug;
}
/**
 * Hàm thực hiện lấy slug của chuyên mục
 * @param bool $p_category: Mảng chuyên mục
 * @return String chuối slug
 */
function get_category_slug($p_category)
{
    $v_category_slug = ($p_category['Urlslugs'] != '') ? $p_category['Urlslugs'] : $p_category['Name'];
    return $v_category_slug;
}
/**
 * Ham chuyển đổi tiếng việt dạng ISO thành tiếng việt không dấu
 * @param array $string : Chuỗi ký tự cần chuyển đổi
 * @return string
 * @author: none @date: none @desc: create new
 */
function fw24h_iso_ascii( $string, $ext = '')
{
    // remove all characters that aren"t a-z, 0-9, dash, underscore or space
    $string = strip_tags($string);
    $string = str_replace('&nbsp;', ' ', $string);
    $string = str_replace('&quot;', '', $string);
    $string = str_replace('-', ' ', $string);
    //$string = ereg_replace( ' +', ' ', $string);
    $string = preg_replace( '#[ ]+#', ' ', $string);
    $string = _utf8_to_ascii( $string);
    $NOT_acceptable_characters_regex = '#[^-a-zA-Z0-9_\/ ]#';
	//Begin 12-12-2016 : Thangnb fix_loi_slug_front_end
	$string = fw24h_restore_bad_char($string);
	//End 12-12-2016 : Thangnb fix_loi_slug_front_end
    $string = preg_replace( $NOT_acceptable_characters_regex, '', $string);
    // remove all leading and trailing spaces
    $string = trim( $string);
    // change all dashes, underscores and spaces to dashes
    $string = preg_replace( '#[-_]+#', '-', $string);
    if( $ext != '') {
        $string = str_replace( ' ', '-', $string);
    }
    // return the modified string
    return $string.$ext;
}
/**
 * Ham chuyển đổi tiếng việt dạng UTF8 thành tiếng việt không dấu
 * @param string $str : Chuỗi ký tự cần chuyển đổi
 * @return string
 * @author: none @date: none @desc: create new
 */
function _utf8_to_ascii($str) {
    $chars = array(
        'a' =>  array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
        'e' =>  array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
        'i' =>  array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
        'o' =>  array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
        'u' =>  array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
        'y' =>  array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
        'd' =>  array('đ','Đ'),
    );
    foreach ( $chars as $key=>$arr) {
        $str = str_replace( $arr, $key, $str);
    }
    return $str;
}

/**
* @desc:  Thay the template trong noi dung header
* Tham so:
        $v_content : noi dung header header
        $rs_template : template(title, desc, keyword)
* @author: tuanna@24h.com.vn @date: 2012/2/29 @desc: create new
*/
function replace_template_header_footer($v_content,$rs_template) {
    if ($v_content != '') {
        if (!$rs_template['robots_index']) {
            $rs_template['robots_index']='<meta name="robots" content="index,follow,noodp" />';
        }
        // Begin: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
        $rs_template['desc'] = str_replace('"', '“', fw24h_restore_bad_char($rs_template['desc']));
        // End: Tytv - 06/09/2016 - cho_phep_hien_thi_ky_tu_dac_biet_title_va_desc
		$rs_template['keyword'] = str_replace(array('"', '&#34;', "'", '&#39;', '&quot;'), '', $rs_template['keyword']); // bỏ các ký tự nháy
        $v_content = str_replace('<!--@title@-->','<title>'.str_replace('“','"',$rs_template['title']).'</title>',$v_content);
        $v_content = str_replace('<!--@description@-->','<meta name="description" content="'.$rs_template['desc'].'" />',$v_content);
        $v_content = str_replace('<!--@keywords@-->','<meta name="keywords" itemprop="keywords" content="'.$rs_template['keyword'].'" />'."\n".'<meta name="news_keywords" content="'.$rs_template['keyword'].'" />',$v_content);
        $v_content = str_replace('<!--@social_network_meta@-->',$rs_template['social_network_meta'],$v_content);
		$v_alternate = $rs_template['canonical'];
        // gắn link canonical
        $v_canonical = '<link rel="canonical" href="'.$rs_template['canonical'].'" />';
        /* begin 30/11/2017 TuyenNT dieu_chinh_gan_the_alternate */
        $v_content = str_replace('<!--@canonical@-->',$v_canonical,$v_content);
        /* end 30/11/2017 TuyenNT dieu_chinh_gan_the_alternate */
        //End 06-04-2016 : Thangnb xu_ly_bo_the_hreflang
        $v_content = str_replace('<!--@seo_title_in_footer@-->',$rs_template['seo_title_in_footer'],$v_content);
        $v_content = str_replace('<!--@ga_code@-->',fw24h_restore_bad_char($rs_template['ga_code']),$v_content);
		$rs_template['robots_index'] = $rs_template['robots_index'];
		//End 18-08-2016 : Thangnb them_the_meta_noarchive
        $v_content = str_replace('<!--@robots_index@-->',fw24h_restore_bad_char($rs_template['robots_index']),$v_content);
        $v_content = str_replace('<!--@server_id@-->','
		<script type="text/javascript">_SERVER=\''.$_SERVER['SERVER_ID'].'\'; </script>',$v_content);
    }
    return $v_content;
}

/*
* lấy config cho 1 module / HaiLT chuyển từ 24H sang
* @param String $p_module_name: tên module
* @param String $p_config Congfig cần lấy
* return Giá trị của config
*/
function _get_module_config($p_module_name, $p_config)
{
    // Lấy biến thiết bị theo
    $v_device_name = 'pc';
    include WEB_ROOT.'includes/module_configs/'.$p_module_name.'_'.$v_device_name.'.conf.php';
    $v_conf = ${$p_config};
    return $v_conf;
}

/**
 * Replace đường dẫn ảnh, video sử dụng CDN
 * Sử dụng ở tất cả các nơi hiện thị dữ liệu từ DB
 * @author  tuanna@24h.com.vn
 */
function replace_domain_static_images($contents)
{
    return $contents;
}


function log_thoi_gian_thuc_thi($p_start_time=0,$p_start_mem = 0,$p_ghi_chu='', $p_log_file = '')
{
	$v_time_end = microtime(true);
	$v_duration = $v_time_end - $p_start_time;
	$v_duration = number_format($v_duration, 12);
	$v_duration = substr($v_duration, 0, 8);
    global $v_device_global;
	if ($v_duration>=1){
        // Thêm thiết bị vào đuôi file log
        $p_log_file = str_replace('.log', '_'.$v_device_global.'.log', $p_log_file);
        $v_memory = (memory_get_usage() - $p_start_mem) / 1024;
        @error_log("+ ".date('Y-m-d H:i:s')." $p_ghi_chu : $v_duration giây \n"." So luong bo nho : $v_memory Kb \n - Link : ".$_SERVER['REQUEST_URI']."\n", 3, $p_log_file);
	}
	return;
}
/**
 * Lay row trong mang rows ....
 * @param bool $p_get_one=true: tim thay thi return luon
				$p_get_one=false: Lay tat ca cac row thoa man dieu kien search

 * @return array
 */
function get_sub_array_in_array($p_arrays, $p_column_search, $p_value_search, $p_get_one=true)
{
    if (!is_array($p_value_search)) {
        $p_value_search = (array)$p_value_search;
    }
	$ret_array = array();
	if (check_array($p_arrays)) {
		foreach ($p_arrays as $v_array){
			if (in_array($v_array[$p_column_search], $p_value_search)) {
				$ret_array[] = $v_array;
				if ($p_get_one) return $ret_array;
			}
		}
	}
	return $ret_array;
}
function hien_thi_noi_dung_magazine($p_body_html, $p_arr_magazine_content)
{
    if (check_array($p_arr_magazine_content)) {
        $replace_str = '[[@magazine_'.$p_arr_magazine_content['pk_magazine'].'#]]';
        // clean body html
        $p_body_html = trim(strip_tags($p_body_html));
		$p_arr_magazine_content['c_html'] = xu_ly_background_bai_magazine($p_arr_magazine_content['c_html']);
        $p_body_html = str_replace($replace_str,$p_arr_magazine_content['c_html'], $p_body_html);
    }
    return $p_body_html;
}
/*
 * @author trungcq - 15-01-2019
 * @desc: Xử lý background bài magazine
 * @param: $p_html_content string Nội dung HTML bài magazine
 * @return: string
 *  */
function xu_ly_background_bai_magazine ($p_html_content=''){
	$v_string = fw24h_restore_bad_char($p_html_content);
	// Nếu có sử dụng template background-image
	if(strpos($v_string,'@@begin-header-background-img-magazine@@') !== false) {
		// Xử lý header background-image
		$v_string = preg_replace('/<!--@@begin-header-background-img-magazine@@-->.*(<div.*class="mgz_bg_img".*>).*<!--@@end-header-background-img-magazine@@-->/msU','$1',$v_string);
		// Xử lý footer background-image
		$v_string .='</div>';
	}
	return $v_string;
}

function replace_alt_images_magazine($news, $row_seo_news=array(), $row_cat, $v_url_news = '') {
    $v_arr_alt = array();
    if(check_array($row_seo_news)) {
        /* Begin anhpt1 21/4/201 chinh_title_seo_chi_tiet_bai_viet */
        if(strpos($row_seo_news['c_danh_sach_alt_anh'],'~') !== false  ){
            $v_arr_alt = explode('~',$row_seo_news['c_danh_sach_alt_anh']);
        }else{
            $v_arr_alt = explode(',',$row_seo_news['c_danh_sach_alt_anh']);
        }
    }/* End anhpt1 21/4/201 chinh_title_seo_chi_tiet_bai_viet */


    $news['Body'] = str_replace('alt=""', '', $news['Body']);
    // tim danh sach anh trong noi dung bai viet
    preg_match_all('/<img[^>]+>/i',$news['Body'], $imgs);
    $v_alt = str_replace('"', "'",  htmlspecialchars( strip_tags(trim($news['Title']))));
    if (sizeof($imgs[0]))
    {
		// Lấy url bài viết
		// 16-03-2015 : Thangnb them check dieu kien $v_url_news
		if ($v_url_news == '') {
			$urlHelper = new UrlHelper();$urlHelper->getInstance();
			$v_slug = get_news_slug($news, $row_cat); // Lay slug bai viet
			$v_url_news = $urlHelper->url_news(array('ID'=> $news['ID'], 'cID'=> $news['CategoryID'], 'slug' => $v_slug, 'VideoCode' => $news['VideoCode']));
			$v_url_news = substr($v_url_news, 1);
		}

        $i = 0;
        foreach($imgs[0] as $img) {
			if (defined ('MAX_REPLACE_ALT_IMG') && MAX_REPLACE_ALT_IMG > 0 && $i >= MAX_REPLACE_ALT_IMG){
				break;
			}
            $v_seo_chi_tiet_alt = ($v_arr_alt[$i]!='')? $v_arr_alt[$i] : $v_alt;
			//Begin 20-04-2016 :Thangnb off_box_chia_se_mxh
			$newimg = str_replace( '<img', '<img alt="'.$v_seo_chi_tiet_alt.' - '.($i+1).'"', $img);
			//End 20-04-2016 :Thangnb off_box_chia_se_mxh
            $news['Body'] = str_replace($img, $newimg, $news['Body']);
            $i++;
        }
    }

    return $news;
}

function get_url_origin_of_news($p_news,$p_category = ''){
    // Kiểm tra xem có tồn tại bài viết không. Mặc định return về trang chủ
    if(!check_array($p_news)){
        return BASE_URL_FOR_PUBLIC;
    }
    $v_url_origin = '';
    // Lấy url gốc của bài viết đã được tính toán từ trước
    if($p_news['url'] != ''){
        $v_url_origin = $p_news['url'];
    }else{
        // Khai báo biến class tạo url
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
        $p_news['v_slug'] = get_news_slug($p_news, $p_category); // Lay slug bai viet
		$p_news['CategoryID'] = intval($p_news['CategoryID']) <= 0 ? intval($p_category['ID']) : $p_news['CategoryID']; // Xử lý trường hợp preview
        $v_url_origin = BASE_URL_FOR_PUBLIC.ltrim($urlHelper->url_news(array('ID'=>$p_news['ID'], 'cID'=>$p_news['CategoryID'], 'slug'=>$p_news['v_slug'])), '/');
    }
    return $v_url_origin;
}

function get_url_origin_of_category($p_row_cat){
    // Kiểm tra xem có tồn tại bài viết không. Mặc định return về trang chủ
    if(!check_array($p_row_cat)){
        return BASE_URL_FOR_PUBLIC;
    }
    $v_url_origin = '';
    // Lấy url gốc của bài viết đã được tính toán từ trước
        // Lấy url gốc của bài viết đã được tính toán từ trước
    if(!is_null($p_row_cat['Link']) && $p_row_cat['Link'] != ''){
        $v_url_origin = $p_row_cat['Link'];
    }else{
        // Khai báo biến class tạo url
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
        $v_url_origin = BASE_URL_FOR_PUBLIC.ltrim($urlHelper->url_cate(array('ID'=>$p_row_cat['ID'], 'slug'=>get_category_slug($p_row_cat))),'/');
    }
    return $v_url_origin;
}
/**
 * Ham unique mot array theo mot truong
 * @param array $array: array can unique
 * @param string $key: truong can unique
 * @return string
 * @author: none @date: none @desc: create new
 */
function array_unique_key(&$array,$key)
{
    if (!is_array($array)) {
        return;
    }
    $temp_array = array();
    foreach ($array as $v) {
		if (!isset($temp_array[$v[$key]]))
			$temp_array[$v[$key]] = $v;
    }
    $array = array_values($temp_array);
}
/**
 *
 * Ham sap xep mang theo nhieu cot
 * @param array $data mang can sap xep
 * @param array $keys mang chua cac cot can sap xep
 * @return array
 * @author: phuonghv@24h.com.vn @date: 2012/12/04 @desc: copy from php.net
 * vidu:
 * $_DATA[] = array("name" => "Sebastian", "age" => 18, "male" => true);
 * $_DATA[] = array("name" => "Lawrence",  "age" => 16, "male" => true);
 * $_DATA[] = array("name" => "Olivia",    "age" => 10, "male" => false);
 * $_DATA[] = array("name" => "Dad",       "age" => 50, "male" => true);
 * $_DATA[] = array("name" => "Mum",       "age" => 40, "male" => false);
 * $res=php_multisort($_DATA, array(array('key'=>'name'),array('key'=>'age','sort'=>'desc')));
 * source: php.net
*/
function php_multisort($data,$keys){
    // List As Columns
    if (check_array($data)) {
        foreach ($data as $key => $row) {
            foreach ($keys as $k){
                $cols[$k['key']][$key] = $row[$k['key']];
            }
        }
        // List original keys
        $idkeys=array_keys($data);
        // Sort Expression
        $i=0;
        foreach ($keys as $k){
            if ($i>0) {$sort.=',';}
            $sort.='$cols['.$k['key'].']';
            if ($k['sort']) {$sort.=',SORT_'.strtoupper($k['sort']);}
            if ($k['type']) {$sort.=',SORT_'.strtoupper($k['type']);}
            $i++;
        }
        $sort.=',$idkeys';
        // Sort Funct
        $sort='array_multisort('.$sort.');';
        eval($sort);
        // Rebuild Full Array
        foreach($idkeys as $idkey){
            $result[$idkey]=$data[$idkey];
        }
        return $result;
    }
}
/*
 * Thay the cac ky tw dac biet trong file xml
 * params :
 	$str : chuỗi cần thay thế kí tự đặc biệt
 * returms : string
*/
function _replace_xml_special_char($str) {
    // Begin: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap
    $str = fw24h_restore_bad_char($str);
    $v_arr_replace = array(
        '/&nbsp;/'    => ' ',
        '/ç/'         => 'c',
        '/Ç/'         => 'C',
        '/ñ/'         => 'n',
        '/Ñ/'         => 'N',
        '/–/'         => '-',
        '/[’‘‹›‚]/u'    => ' ',
        '/[“”«»„�]/u'  => ' ',
        '/&/'         => ' &amp; ',
        '/\'/'        => ' &apos; ',
        '/\"/'        => ' &quot; ',
        '/\>/'        => ' &gt; ',
        '/\</'        => ' &lt; '
    );
    return preg_replace(array_keys($v_arr_replace), array_values($v_arr_replace), $str);
    // End: 05-01-2016 trungcq bổ sung xu_ly_ky_tu_dac_biet_sitemap
}