<?php
__get_db_functions('db.sitemap', NAME_THIET_BI_PC);
class box_xml_block extends Fw24H_Block
{
	var $_cache_key = 'box_xml_2018_responsive';
    function index($p_ten_file, $view_type = 0, $noindex = 0, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items','page'));
		$this->setParam('noindex', 0);
        $this->generate();
    }

    function doAction()
    {
		
		// đặt log
        $v_start_time = microtime(true);
        $v_ten_file = fw24h_replace_bad_char($this->getParam('p_ten_file'));
		//begin: sua_doi_he_thong_sitemap_24h
                // xác định kiểu file sitemap
		$v_phien_ban = 'web';
		$v_kieu_sitemap = 'mac_dinh';
		//end: sua_doi_he_thong_sitemap_24h
		
		$v_kieu_sitemap = str_replace('sitemap-', '', $v_ten_file);
        $v_date_sitemap = trim(str_replace(array('tags-','tag-'),'',trim($v_kieu_sitemap)));
		$v_arr_tmp = explode('-', $v_kieu_sitemap);
		$v_kieu_sitemap = $v_arr_tmp[0];
		
        $v_ngay_off_simaptags = _get_module_config('cau_hinh_dung_chung','v_ngay_off_simaptags', NAME_THIET_BI_PC);
        $v_arr_sitemap = fe_chi_tiet_1_sitemap($v_ten_file);    
        $v_user_agent = $_SERVER['HTTP_USER_AGENT'];
        log_thoi_gian_thuc_thi($v_start_time, 0, "Doan 1 : Xu ly goi SP. User agent: $v_user_agent  - File XML : $v_ten_file",WEB_ROOT.'logs/log_box_xml.log',1);
        $v_start_time = microtime(true);
        /* End LuanAD 19/05/2017 XLCYCMHENG_21656_off_gen_sitemap_profile */
		
		$v_html = $v_arr_sitemap['c_html'];
		
		// xóa ký tự phân cách folder ảnh/video bị thừa
		$v_html = str_replace('//upload', '/upload', $v_html);
		
		//end: sua_doi_he_thong_sitemap_24h
		// xóa toàn bộ comment url gốc
		//Begin 10-03-2016 : Thangnb toi_uu_sitemap
		$v_html = preg_replace('/<!-- start_url_goc:.*:end_url_goc -->/msU', '', $v_html);
		//End 10-03-2016 : Thangnb toi_uu_sitemap
		
		// load header/footer
		$v_arr_header_footer = _get_module_config('xml', 'v_arr_header_footer', NAME_THIET_BI_PC);
		
		//Begin 06-01-2016 : Thangnb chinh_sua_sitemap_video_image_tag
		if (($v_kieu_sitemap == 'video' || $v_kieu_sitemap == 'image' || $v_kieu_sitemap == 'tags') && $v_count > 1) {
			$v_arr_tmp = $v_arr_header_footer[$v_kieu_sitemap.'_'.$v_phien_ban.'1'];	
		} else {
			$v_arr_tmp = $v_arr_header_footer[$v_kieu_sitemap.'_'.$v_phien_ban];
		}
		//end 06-01-2016 : Thangnb chinh_sua_sitemap_video_image_tag

		if (is_null($v_arr_tmp) || !is_array($v_arr_tmp) || sizeof($v_arr_tmp) == 0) {
			$v_arr_tmp = $v_arr_header_footer['mac_dinh_'.$v_phien_ban];
		}
		$v_html_end = '<?xml version="1.0" encoding="UTF-8" ?>';
		$v_html_end .= "\n".$v_arr_tmp['header'];
		$v_html_end .= "\n".$v_html;
		$v_html_end .= "\n".$v_arr_tmp['footer'];
		
		$this->setParam('v_html_end', $v_html_end);
		$this->render();
        log_thoi_gian_thuc_thi($v_start_time, 0, "Doan 2 : Xu ly noi dung. User agent: $v_user_agent Loai video $v_type - File XML : $v_ten_file",WEB_ROOT.'logs/log_box_xml.log',1);
	}
	
}