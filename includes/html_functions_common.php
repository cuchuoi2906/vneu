<?php

/**
 * anhnt1 08/5/2015: Hàm load file css
 * p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML: true - sẽ chèn nội dung CSS vào html
 * p_MINIFY_JS_CSS: dùng file CSS đã minify hay dùng file gốc
 * return: Chuỗi HTML load css
 */
function html_load_header_css($p_header_content, $p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML=false, $p_MINIFY_JS_CSS=false, $p_module = '',$p_module_add = '', $p_device = 'pc',$p_name_file_common = 'common_092018'){
    if ($p_header_content==''){
		return $p_header_content;
	}
    $p_arr_module_add = array();
	$p_arr_module_add = explode(',',$p_module_add);
	$html_css   = '<link href="'.BASE_URL_FOR_PUBLIC.'fontawesome/css/all.min.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
    $v_common_file = $p_name_file_common;
	if (_is_thiet_bi_mobile($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_mobile';
		}
        if ($p_module_add != '') {
            $p_module_add = $p_module_add.'_mobile';
        }
        $v_common_file = $v_common_file.'_mobile';
	}
	if (_is_thiet_bi_pc($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_pc';
		}
		if ($p_module_add != '') {
			$p_module_add = $p_module_add.'_pc';
		}
        $v_common_file = $v_common_file.'_pc';
	}
	if (_is_thiet_bi_ipad($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_ipad';
		}
		if ($p_module_add != '') {
			$p_module_add = $p_module_add.'_ipad';
		}
        $v_common_file = $v_common_file.'_ipad';
	}
    
    if (_is_thiet_bi_amp($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_amp';
		}
		if ($p_module_add != '') {
			$p_module_add = $p_module_add.'_amp';
		}
        $html_css   .= '<style amp-custom>';
	}
	if(!$p_MINIFY_JS_CSS){
        /** Kiểm tra có chèn css trực tiếp vào html không */
        if($p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML) {
            $html_css   .= '<style>';
			$html_css   .= _read_file(WEB_ROOT.'css/'.$v_common_file.'.css');
			if ($p_module != '') {
				$html_css   .= _read_file(WEB_ROOT.'css/'.$p_module.'.css');
			}
            if($p_module_add != ''){
                $html_css   .= _read_file(WEB_ROOT.'css/'.$p_module_add.'.css');
            }
			$html_css   .= '</style>';
        } else {
			$html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$v_common_file.'.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
			if ($p_module != '') {
				$html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$p_module.'.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
			}
            if($p_module_add != ''){
                $html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$p_module_add.'.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
            }
        }		
	} else {
        /** Kiểm tra có chèn css trực tiếp vào html không */
        if($p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML){
			$html_css   .= '<style>';
            $html_css   .= _read_file(WEB_ROOT.'css/'.$v_common_file.'.min.css');
			if ($p_module != '') { 
				$html_css   .= _read_file(WEB_ROOT.'css/'.$p_module.'.min.css');
			}
            if($p_module_add != ''){
                $html_css   .= _read_file(WEB_ROOT.'css/'.$p_module_add.'.css');
            }
			$html_css   .= '</style>';
        } else {
            $html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$v_common_file.'.min.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
			if ($p_module != '') { 
				$html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$p_module.'.min.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
			}
            if($p_module_add != ''){
                $html_css   .= '<link href="'.BASE_URL_FOR_PUBLIC.'css/'.$p_module_add.'.min.css?v='.JS_CSS_VERSION.'" type="text/css" rel="stylesheet">';
            }
        }		
	}
    // Thay the <!--@css@-->
	$p_header_content = str_replace('<!--@css@-->',$html_css,$p_header_content);
    return $p_header_content;
}
/**
 * anhnt1 08/5/2015: Hàm load file js
 * p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML: true - sẽ chèn nội dung JS vào html
 * p_MINIFY_JS_CSS: dùng file JS đã minify hay dùng file gốc
 * return: Chuỗi HTML load file js
 */
function html_load_header_js($p_header_content, $p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML=false, $p_MINIFY_JS_CSS=false, $p_module = '',$p_module_add = '',$p_device = 'pc'){
	if ($p_header_content==''){
		return $p_header_content;
	}
	$html_js    = '<script  src="'.BASE_URL_FOR_PUBLIC.'js/jquery.min.js?v='.JS_CSS_VERSION.'"></script>';
	$v_common_file = 'common_092018';
	if ($p_module != '') {
		$p_module = $p_module.'_pc';
	}
	if ($p_module_add != '') {
		$p_module_add = $p_module_add.'_pc';
	}
	$v_common_file = $v_common_file.'_pc';
	if (_is_thiet_bi_mobile($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_mobile';
		}
		if ($p_module_add != '') {
			$p_module_add = $p_module_add.'_mobile';
		}
		$v_common_file = $v_common_file.'_mobile';
	}
	if (_is_thiet_bi_ipad($p_device)) {
		if ($p_module != '') {
			$p_module = $p_module.'_ipad';
		}
		if ($p_module_add != '') {
			$p_module_add = $p_module_add.'_ipad';
		}
		$v_common_file = $v_common_file.'_ipad';
	}
    if(!$p_MINIFY_JS_CSS){
        /** Kiểm tra có chèn css trực tiếp vào html không */
        if($p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML){
            $html_js    .= '<script>'._read_file(WEB_ROOT.'js/'.$v_common_file.'.js');
			if ($p_module != '') {
				$html_js    .= _read_file(WEB_ROOT.'js/'.$p_module.'.js');
			}
            if($p_module_add != ''){
                $html_js    .= _read_file(WEB_ROOT.'js/'.$p_module_add.'.js');
            }
			$html_js    .='</script>';
        } else {
			if ($p_device == NAME_THIET_BI_PC || $p_device == NAME_THIET_BI_TABLET) {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$v_common_file.'.js?v='.JS_CSS_VERSION.'"></script>';
			} else {
            	$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$v_common_file.'.js?v='.JS_CSS_VERSION.'"></script>';
			}
			if ($p_module != '') {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module.'.js?v='.JS_CSS_VERSION.'"></script>';
			}
            if($p_module_add != ''){
                $html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module_add.'.js?v='.JS_CSS_VERSION.'"></script>';
            }
        }
    } else {
        /** Kiểm tra có chèn css trực tiếp vào html không */
        if($p_CHEN_NOI_DUNG_JS_CSS_VAO_HTML){
            $html_js    .= '<script>'._read_file (BASE_URL_FOR_PUBLIC.'js/'.$v_common_file.'.min.js');
			$html_js    .='</script>';
			if ($p_module != '') {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			}
            if ($p_module_add != '') {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module_add.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			}			
        } else {
			if ($p_device == NAME_THIET_BI_PC || $p_device == NAME_THIET_BI_TABLET) {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$v_common_file.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			} else {
            	$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$v_common_file.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			}
			if ($p_module != '') {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			}
            if ($p_module_add != '') {
				$html_js    .= '<script '.JS_MODE.' src="'.BASE_URL_FOR_PUBLIC.'js/'.$p_module_add.'.min.js?v='.JS_CSS_VERSION.'"></script>';
			}
        }
	}
    // Thay the <!--@css@-->
	$p_header_content = str_replace('<!--@js@-->',$html_js,$p_header_content);
    return $p_header_content;
}