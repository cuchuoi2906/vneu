<?php

include_once('class/uploader.php');
include_once('minify/autoloader.php');
use MatthiasMullie\Minify;

//============================================== TEMPLATE MAGAZINE FUNCTIONS ===========================================================//

/**
 * chỉ lấy html element trong thẻ <body></body>
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string $html
 * @return string
 */
function mzt_get_body_inner_html($html)
{
    preg_match("/<body[^>]*>(.*?)<\/body>/is", $html, $matches);

    return empty($matches[1]) ? $html: $matches[1];
}

function mzt_restore_bad_char($html)
{
    $str = html_entity_decode($html);
    if ( get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    $str = str_replace('&lt;', '<', $str);
    $str = str_replace('&gt;', '>', $str);
    $str = str_replace('&#34;', '"', $str);
    $str = str_replace('&#39;', "'", $str);
    $str = str_replace('&#92;', '\\', $str);
    $str = str_replace('&#61;', '=', $str);
    $str = str_replace('&#40;', '(', $str);
    $str = str_replace('&#41;', ')', $str);
    $str = str_replace("&#124;", '|', $str);

    return $str;
}

/**
 * tái lập đường dẫn upload /download file của 1 template
 * @param  string 	$path_format 	[format của path, vd: /path/to/%s/file.abc]
 * @param  integer 	$template_id 	[khóa chính template]
 * @param  string 	$file_type   	[kiểu file]
 * @return string
 */
function mzt_get_upload_path($path_format = 'upload/magazine/template_magazine/%s/%s/%s', $template_id, $file_type)
{
    // TODO: move upload path format to config file
    $v_quater = quarterByDate(date('m')) . '-' . date('Y'); // 3-2018

    switch ($file_type) { // images
        case 'image': $v_type_dir = 'images'; break;
        case 'video': $v_type_dir = 'videoclip'; break;
        case 'audio': $v_type_dir = 'audio'; break;
        default: $v_type_dir = 'other'; break;
    }

    $v_date = date('Y-m-d');

    return rtrim(ROOT_FOLDER, '/') . '/' .ltrim(vsprintf($path_format, array( $v_quater, $template_id, $v_type_dir, $v_date)), '/');
}

/**
 * thông báo dạng json cho các ajax request
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string  	$message  	[đoạn text thông báo]
 * @param  boolean 	$is_error 	[true : lỗi, false: thông báo thường ]
 * @param  array   	$data     	[mảng dữ liệu bổ sung để trả về cho client]
 * @param  boolean $is_die   	[description]
 * @return json
 */
function mzt_ajax_message($message, $is_error = false, array $data = array(), $is_die = true) {
    echo json_encode(['error' => $is_error, 'msg' => $message, 'data' => $data]);
    if($is_die) die;
}

/**
 * lấy các thông tin cần thiết để insert 1 fileupload vào database
 * @author bangnd <bangnd@24h.com.vn>
 * @param  integer 	$p_template_id 	[khóa chính template]
 * @param  array  	$info          	[mảng thông tin file sau khi upload thành công]
 * @return array
 */
function mzt_update_template_fileupload($p_template_id, array $p_info) {
    $v_file_name        = $p_info['file_name'];
    $v_file_type        = $p_info['file_type'];
    $v_path             = $p_info['path'];
    $v_file_path        = $p_info['file_path'];
    $v_file_meta        = $p_info['file_meta'];
    $v_file_extension   = $p_info['extension'];
    $v_mime_type        = $p_info['mime_type'];
    $v_file_hash        = $p_info['hash'];
    $v_user_id          = $_SESSION['user_id'];
    // $v_file_status      = 1;


    $v_file_original_name       = $p_info['file_name_origin'];
    $v_file_original_content    = $p_info['file_content'];
    // gia tri mac dinh, se duoc cap nhat sau khi trich xuat du lieu sau nay doi voi cac file (html,css)
    $v_file_content             = $v_file_original_content;
    $v_file_content_template    = $v_file_original_content;
    $v_file_content_map         = array();

    // tạo mới nếu file chưa được upload hoặc cập nhật nếu file đã được upload trước đó
    $v_fileupload_id = be_update_magazine_template_fileupload($v_file_name, $v_file_original_name, $v_path, $v_file_extension, $v_mime_type, $v_file_type, $p_template_id, $v_user_id, $v_file_path,  $v_file_meta, $v_file_content, $v_file_original_content, $v_file_content_template, $v_file_content_map, $v_file_hash
    );

    $v_result = $p_info;
    $v_result['fileupload_id']          = $v_fileupload_id;

    return $v_result;
}

/**
 * chỉ thay thế phần tử tìm thấy đầu tiên
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string 	$search  [chuỗi dùng tìm kiếm]
 * @param  string 	$replace [chuỗi dùng để thay thế vào vị trí tìm được]
 * @param  string 	$subject [chuỗi nguồn]
 * @return string
 */
function mzt_str_replace_once($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

/**
 * tái lập html preview với data từ html_map và template
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string 	$html_template [nội dung template]
 * @param  array  	$maps      [mảng các resource và phần tử tự định nghĩa]
 * @return string
 */
function mzt_rebuild_html($html_template, $maps) {
    $v_html = $html_template;
    if ( check_array($maps) ) {
        mzt_loop_through_map($maps, function ($element_data) use (&$v_html) {
            if ( !empty($element_data['arr_data']) ) {
                foreach ($element_data['arr_data'] as $attribute => $data) {
                    $v_html = str_replace($data['placeholder'], $data['data'], $v_html);
                }
            }
        });
    }
    return $v_html;
}

/**
 * lặp qua mảng cấu hình để gọi 1 hàm callback xác định
 * @param  array   	$maps     [mảng cấu hình các phần tử tự động (auto) và tự định nghĩa (defined)]
 * @param  callable $callback [hàm callback sử dụng]
 * @param  string   $map_kind [auto: / defined, mac dinh lap qua toan bo $maps]
 * @return void
 */
function mzt_loop_through_map($maps, callable $callback, $map_kind = '', $p_template_id = 0, $p_html_body = '') {
    if ( !empty($maps) ) {
        foreach ($maps as $mkind => $map) { // mkind: auto/defined
            
            // neu map rong hoac khong phai map can lay thi bo qua
            if ( ( empty($map_kind) || $map_kind == $mkind ) && !empty($map) ) {
                foreach ($map as $element_type => $arr_element) { // element_type: image,js,css,title,...
                    if (empty($arr_element)) continue;
                    foreach ($arr_element as $element_index => $element_data) {
                        // Id template
                        $element_data['temp_id'] = $p_template_id;
                        $element_data['html_body'] = $p_html_body;
                        call_user_func_array ( $callback, array($element_data, $element_index, $map_kind) );
                    }
                }
            }
        }
    }
}

/**
 * lấy ra chú thích cho các phần tử tự định nghĩa
 * @author bangnd <bangnd@24h.com.vn>
 * @param  string 	$element_type [kiển phần tử html: image,iframe,title,...]
 * @param  string 	$stt          [so thu tu cua phan tu]
 * @return string
 */
function mzt_get_html_element_label($element_type, $stt = '',$imgName = '') {
    $arrLabelFormat = array(
        'title' 		=> 'Tiêu đề %s',
        'image' 		=> 'Hình ảnh %s %s',
        'iframe'	 	=> 'Iframe %s',
        'audio' 		=> 'Audio %s',
        'video' 		=> 'Video %s',
        'link' 			=> 'Link %s',
        'paragraph' 	=> 'Đoạn văn %s %s',
        'paragraph_ghichu' 	=> 'Ghi chú ảnh %s %s',
    );

    if ( empty($arrLabelFormat[$element_type]) ) {
        return '';
    } else {
        return sprintf( $arrLabelFormat[$element_type], $stt ,$imgName);
    }
}

/**
 * lấy ra chú thích sử dụng cho thuộc tính tự định nghĩa (width, height, ...)
 * @param $name
 * @param string $operator [toán tử logic: >, <, >=, <=,]
 * @return string
 */
function mzt_get_metadata_explanation_format($name, $operator = '') {
    $arr = array(
        'width' 		=> 'chiều rộng %s ',
        'height' 		=> 'chiều cao %s ',
        'file_size' 	=> 'dung lượng tối đa: ',
        'type' 			=> 'định dạng file: ',
        'word_count' 	=> 'số từ tối đa: ',
        'file_size_gif' 	=> 'dung lượng tối đa ảnh gif: ',
        'poster_video' 	=> 'Tỷ lệ ảnh: ',
    );

    return empty($arr[$name]) ? $name : sprintf($arr[$name], $operator);
}

/**
 * lay ra mang cac file css, js cua template
 * @param  array $html_map
 * @return array
 */
function mzt_get_template_css_js_files($html_map)
{
    $result = array('css' => array(), 'js' => array());
    if (!empty($html_map)) {
        foreach ($html_map as $mkind => $map) { // mkind: auto/defined
            if (empty($map)) continue; // bo qua
            foreach ($map as $element_type => $arr_data) { // element_type: image,js,css,title,...
                if ( empty($arr_data) || !in_array($element_type ,array('css', 'js')) ) continue; // bo qua

                foreach ($arr_data as $index => $element_data) {
                    if ( !empty($element_data['arr_data']) ) {
                        foreach ($element_data['arr_data'] as $attribute => $data) {
                            $result[$element_type][] = $data['data'];
                        }
                    }
                }
            }
        }
    }

    return $result;
}

/**
 * chuyển đổi dung lượng file sang dạng dễ đọc hơn
 * @param $bytes [dung lượng file tính theo bytes]
 * @param int $decimals [số chũ số sau dấu ,]
 * @return string
 */
function mzt_readable_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

//============================================== MAGAZINE FUNCTIONS ===========================================================//

/**
 * lấy chú thích của một thuộc tính (attribute) của 1 phần tử html
 * @param $attr
 * @return mixed
 */
function mz_get_html_element_attribute_explanation($attr) {
	$arrAttrTitles = array(
		'poster' 	=> 'Ảnh đại diện video',
		'alt' 		=> 'Tiêu đề ẩn của ảnh',
		'title' 	=> 'Tiêu đề ẩn của link',
		'width'		=> 'Chiều rộng',
		'height'	=> 'Chiều cao',
	);

	return empty($arrAttrTitles[$attr]) ? $attr : $arrAttrTitles[$attr];
}

/**
 * cấu trúc nên html form nhập của một loại phần tử tự định nghĩa
 * các phần tử tự định nghĩa bao gồm: iframe, paragraph, title, ...
 * @param $title [nội dung đoạn tiêu đề]
 * @param $body [nội dung đoạn thân]
 * @param string $format_html
 * @return string
 */
function mz_build_element_form_input_html($title, $body, $format_html = '') {
	if (empty($format_html)) {
		$format_html = '
			<div class="row m-tb-5" style="border-top:1px dotted #ddd;padding: 5px 0 0 0">
				<div class="col-1-6">
					<label class="fluid p-lr-6">%s</label>
				</div>
				<div class="col-5-6">%s</div>
			</div>';
	}

	return vsprintf($format_html, [ $title, $body ]);
}

/**
 * cấu trúc nên id attribute của một phần tử html
 * @param $code [mã của phần tử, xem trong maps (mảng cấu hình các phần tử được bóc tách)
 * @param $mz_content_index [số thứ tự của phần tử]
 * @return string
 */
function mz_build_element_id($code, $mz_content_index) {
	return vsprintf('map__%s__%s', array($code, $mz_content_index));
}

/**
 * tạo nên các attribute chung cho 1 input
 * @param $elementId [id attribute của phần tử được trả về từ hàm mz_build_element_id]
 * @param array $data [data cấu hình của phần tử được bóc tách]
 * @return string
 */
function mz_build_element_common_attributes($elementId, array $data) {
	return vsprintf('id="%s" name="%s" data-type="%s" data-code="%s" data-dtype ="%s" data-is-required="%s" data-attr="%s"', array($elementId, $elementId, $data['type'], $data['code'], $data['data_type'], (int)$data['is_required'], $data['attr']));
}

/**
 * đưa mảng các rules (hay gọi là metadata) của phần tử về dạng json,
 * encode để có thể echo sử dụng cho attribute data-metadata=""
 * @param $arr_metadata
 * @return string
 */
function mz_encode_metadata_json($arr_metadata) {
	return empty($arr_metadata) ? '' : htmlentities( json_encode( $arr_metadata) );
}

/**
 * @param $elementType
 * @param $config
 * @return string
 */
function mz_build_file_accept_string($elementType, $config) {
	$str_accept = '';
	if(!empty($config['allow_extensions'])) {
		$str_accept .= '.' . implode(',.',$config['allow_extensions']);
	}
//	if (!empty($config['allow_mime_types'])) {
//	 	$str_accept .= ',' . $v_str_allow_mime_types;
//	}
	return $str_accept;
}


function mz_get_file_allow_extensions_string($elementType, $config) {
	$str_extensions = '';
	if(!empty($config['allow_extensions'])) {
		$str_extensions =   implode(',',$config['allow_extensions']);
	}
	return $str_extensions;
}

function mz_build_allow_mime_string($elementType, $config) {
	$str_mimetypes = '';
	if(!empty($config['allow_mime_types'])) {
		$str_mimetypes =   implode(',',$config['allow_mime_types']);
	}
	return $str_mimetypes;
}

/**
 * cấu trúc nên html form input của 1 phần tử (element_type = html)
 * @param array $params
 * @return string
 */
function mz_build_paragraph_form_input_html(array $params) {
	$data = $params['data'];
	$html_tag = empty($data['html_tag']) ? '' : $data['html_tag'];
	$v_html = vsprintf('<textarea %s class="map-element" data-metadata="%s" data-stt="%s" data-html-tag="%s">%s</textarea>', array($params['commonAttr'], $params['metadata'], $params['index'], $html_tag, $data['data']));
	
	$v_html .= "<script>
				CKEDITOR.replace( '" .$params['elementId']. "', {
                    toolbar: 'Basic',
                    height: 100,
                    removePlugins: 'elementspath',
                    resize_enabled: false,
                    fillEmptyBlocks: false,
                    ignoreEmptyParagraph: true,
                    extraAllowedContent: '*(*){color,background-color}',
                    stylesSet: 'custom_styles'
                });
                CKEDITOR.config.toolbar_Basic = [
                    [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
                    [ 'Link', 'Unlink' ],
                    [ 'TextColor', 'BGColor' ],
                    [ 'Styles' ],
                    [ 'Undo', 'Redo' ]
                ];
                window.CKEDITOR.instances.". $params['elementId'] .".on('change', function() { 
                    onParagraphChange(this);
                });
                
                window.CKEDITOR.instances.". $params['elementId'] .".on('instanceReady', function() { 
                    setIframeHeight('preview__". $params['index'] ."', 'LoadZoneLeft_". $params['index'] ."');
                });
        </script>";
    // hien thi cac quy tac
    if (!empty($data['metadata'])) {
    	$v_html .= '<p class="mz-metadata h12">';
    	$v_html .= 		'<span class="error-map-element"></span>';
    	foreach ($data['metadata'] as $name => $meta) {
    	    if (empty($meta['value'])) continue;
	    	$v_html .= '<span style="position:absolute;top:0;right:0">Đã nhập <strong class="word_count">'. str_word_count_utf8($data['data']) .'</strong>/<strong>'. $meta['value'] .'</strong> từ</span>';
    	}
    	$v_html .= '</p>';
    }
    return $v_html;
}

/**
 * cấu trúc nên html form input của 1 phần tử (element_type = file)
 * @param array $params
 * @return string
 */
function mz_build_file_form_input_html(array $params) {
	$data = $params['data'];

	$v_html = '<div>'; // begin html
	
	// accept attribute
	$str_accept 		= mz_build_file_accept_string($params['type'], $params['config']);
	$str_mimetypes 		= mz_build_allow_mime_string($params['type'], $params['config']);

	if (!empty($data['metadata']['type'])) {
		$str_extensions = $data['metadata']['type']['value'];
	} else {
		$str_extensions = mz_get_file_allow_extensions_string($params['type'], $params['config']);
	}
	
	$v_html .= '<div class="input-wrapper"'. (!empty($data['mzc_is_uploaded']) ? 'style="width: 95%;
    display: inline-block;"' : '') .'>'; // begin 
	$v_html .= vsprintf( '<input type="file" %s class="map-element fluid input_image_magazine" data-init="%s" data-metadata="%s" accept="%s" data-extension="%s" data-mime-type="%s" data-stt="%s" onchange="onChangeFile(this)">', array($params['commonAttr'], $data['data'], $params['metadata'], $str_accept, $str_extensions, $str_mimetypes, $params['index']) );

	if ($params['type'] == 'image') {
		$cmm = vsprintf( '%s__%s', array( $data['code'], $params['index'] ) );
		// crop button
		$v_html .= vsprintf( '<a id="crop_btn__%s" class="crop_image" href="javascript:void(0);" onclick="window.open(\'%s\', \'crop_window_%s\', \'width=700, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes\')" title="Cắt ảnh" style="display:none;margin-left:10px"><img src="%s" align="absmiddle" width="16" height="16" /></a>', array($cmm, html_link('magazine/dsp_crop_image?stt=' . $params['index'] . '&code=' . $data['code'] . '&target=' . $params['elementId'], false), $cmm, html_image('images/image-crop-icon.png', false)) );
		// data after cropped
		$v_html .= vsprintf( '<input type="hidden" id="crop_x__%s" name="crop_x__%s" value="">', array($cmm,$cmm) );
		$v_html .= vsprintf( '<input type="hidden" id="crop_y__%s" name="crop_y__%s" value="">', array($cmm,$cmm) );
		$v_html .= vsprintf( '<input type="hidden" id="crop_w__%s" name="crop_w__%s" value="">', array($cmm,$cmm) );
		$v_html .= vsprintf( '<input type="hidden" id="crop_h__%s" name="crop_h__%s" value="">', array($cmm,$cmm) );
		$v_html .= vsprintf( '<input type="hidden" id="crop_image__%s" name="crop_image__%s" value="">', array($cmm,$cmm) );
        // id temp 
        $v_temp_id = intval($params['data']['temp_id']);
        if($v_temp_id > 0){
            // Lấy cấu hình list template cho phép chọn hiệu ứng
            $v_list_id_temp = '140,172,164,163,205,167,141,198,185,208,209,195,136,135,179,182,187,188,189,190,191,208,209';
            if($v_list_id_temp != ''){
                $v_arr_temp_id = explode(',', $v_list_id_temp);
                // nếu template thuộc danh sách template cho phép chọn hiệu ứng
                if(check_array($v_arr_temp_id) && in_array($v_temp_id, $v_arr_temp_id)){
                    // lấy cấu hình danh sách hiệu ứng
                    $v_arr_effect_image = _get_module_config('template_magazine', 'v_arr_effect_image');
                    $v_body = $params['data']['html_body'];
                    $v_count_img = $params['data']['count_index'];
                    // lấy class ảnh trong body
                    preg_match_all('/<img[^>]+class=\"(.*)\"[^>]+>/iU', $v_body, $v_arr_class_img);
                    $v_list_class = '';
                    if($v_arr_class_img[1][$v_count_img] != ''){
                        // cắt lấy class cuối
                        $v_arr_class = explode(' ', $v_arr_class_img[1][$v_count_img]);
                        if(check_array($v_arr_class)){
                            $v_list_class = $v_arr_class[(count($v_arr_class)-1)];
                        }
                    }
                    // select box hiệu ứng
                    if(check_array($v_arr_effect_image)){
                        $v_html .= vsprintf('<select name="effect_image__%s" style="width: 150px;margin-left: 5px;">', array($cmm,$cmm) );
                            $v_html .= '<option value="">Chọn hiệu ứng</option>';
                            foreach($v_arr_effect_image as $v_effect_image){
                                $v_selected = ($v_list_class == $v_effect_image['c_code_effect_image']) ? 'selected' : '';
                                $v_html .= '<option '.$v_selected.' value="'.$v_effect_image['c_code_effect_image'].'">'.$v_effect_image['c_name'].'</option>';
                            }
                        $v_html .= '</select>';
                    }
                }
            }
        }
    }else{
        $cmm = vsprintf( '%s__%s', array( $data['code'], $params['index'] ) );
        $v_style = 'display:none';
        $v_arr_ghi_chu_quang_cao_theo_loai = _get_module_config('doi_tac_quang_cao_video','v_arr_ghi_chu_quang_cao_theo_loai');
        $v_html .='<br />';
        $v_html .= html_select_box('sel_type_quang_cao_doi_tac'.$cmm, $v_arr_ghi_chu_quang_cao_theo_loai, 'c_code', 'c_name', intval($v_type_quang_cao), $extend='style="width:200px;float: left;margin-top: 10px;margin-right: 10px;" onchange="load_div_loai_video_24h(this.value,\''.$cmm.'\')"');
        
        $v_html .= '<div id="div_loai_video_nivea'.$cmm.'" style="margin-top: 10px;'.$v_style.'" ><select size="5" id="sel_loai_giai_dau'.$cmm.'" name="sel_loai_giai_dau'.$cmm.'">';
            // Lấy loại giải đấu nivea
            $v_arr_loai_giai_dau_nivea = get_arr_campaign();
            $sel_loai_video = $sel_loai_video==''? $rs_upload_video_conf['loai_video_mac_dinh_duoc_chon']:$sel_loai_video;
            for($i=0, $s= sizeof($v_arr_loai_giai_dau_nivea); $i<$s; $i++) { 
                $selected ='';
                if($v_arr_loai_giai_dau_nivea[$i]['c_code'] == $v_value) {
                    $selected ='selected';
                }
                $v_html .= '<option'.$selected.' value='.$v_arr_loai_giai_dau_nivea[$i]['c_code'].' title="'.$v_arr_loai_giai_dau_nivea[$i]['c_name'].'" >'.$v_arr_loai_giai_dau_nivea[$i]['c_name'].'</option>';
            }
       $v_html .= '</select></div>';
    }
	$v_html .= '</div>'; // end
	if (!empty($data['mzc_is_uploaded'])) {
		$v_html .= '<a target="_blank" href="javascript:void(0)" onclick="window.open(\''. BASE_DOMAIN . $data['data'] .'\', \'new_window\', \'width=1200, height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes\')" style="display: inline-block;position: relative;top: 5px;width:5%;padding-left:8px;"><img src="'. html_image('images/imgpreview.gif', false) . '" width="16" height="16" /></a>';
		$v_html .= vsprintf('<input type="hidden" name="old_%s" value="%s" readonly>', array($params['elementId'], $data['data']));
	}
    // Hiển thị ghi chú video bản quyền
    if ($params['type'] != 'image') {
        $v_html .='<span style="color:red;">*(Việc chọn giải đấu bắt buộc đối với loại "Chiến dịch đặc biệt" để phục vụ TTQC tracking số liệu video theo từng giải)</span>';
    }
	// hien thi cac quy tac
	if (!empty($data['metadata'])) {
		$v_html .= '<p class="mz-metadata"><strong style="color:green">Quy tắc: </strong>';
		foreach ($data['metadata'] as $name => $meta) {
		    if (empty($meta['value'])) continue;
                if($params['type'] == 'video' && $name != 'type'){
                    $meta['operator'] = '<=';
                }
		    	$v_html .= '<span>'. mzt_get_metadata_explanation_format($name, $meta['operator']) .'</span>';
                // xử lý cho poster video
                if($name == 'poster_video'){
                    $meta['value'] = str_replace(',',':',$meta['value']);
                }
		    	$v_html .= '<strong>'. $meta['value'] .', ' .'</strong>';
		}
		$v_html .= '</p>';
	}
	if ($params['type'] == 'image') {
		$v_html .= '<div class="image-info"></div>';
	}
	$v_html .= '<span class="error-map-element"></span>';
	$v_html .= '</div>'; // end html

	return $v_html;
}

/**
 * cấu trúc nên html form input của 1 phần tử không phải là file hoặc paragraph (html)
 * @param array $params
 * @return string
 */
function mz_build_other_form_input_html(array $params) {
	$data = $params['data'];
	$v_html ='';
	$v_html .= vsprintf('<input type="text" %s class="map-element fluid" data-metadata="%s" value="%s" data-stt="%s" onkeyup="onTitleOrLinkChange(this)">', array($params['commonAttr'], $params['metadata'], $data['data'], $params['index']));
	// hien thi cac quy tac
	if (!empty($data['metadata'])) {
		if ($params['type'] == 'title') {
			$v_html .= '<p class="mz-metadata h12">';
			$v_html .= '<span class="error-map-element"></span>';
			foreach ($data['metadata'] as $name => $meta) {
			    if (empty($meta['value'])) continue;
		    	$v_html .= '<span style="position:absolute;top:0;right:0">Đã nhập <strong class="word_count">'. str_word_count_utf8($data['data']) .'</strong>/<strong>'. $meta['value'] .'</strong> từ</span>';
			}
			$v_html .= '</p>';
		} else {
			$v_html .= '<p class="mz-metadata"><strong style="color:green">Quy tắc: </strong>';
			foreach ($data['metadata'] as $name => $meta) {
			    if (empty($meta['value'])) continue;
		    	$v_html .= '<span>'. mzt_get_metadata_explanation_format($name, $meta['operator']) .'</span>';
		    	$v_html .= '<strong>'. $meta['value'] .'</strong>';
			}
			$v_html .= '</p>';
			$v_html .= '<span class="error-map-element"></span>';
		}
	}
	return $v_html;
}

/**
 * Cấu thành nên html form input của 1 phần tử tự định nghĩa
 * @param array $data
 * @param $mz_content_index [số thứ tự của phần tử]
 * @param $config
 * @param null $type
 * @return string
 */
function mz_get_input_html(array $data, $mz_content_index, $config, $type = null) {
	// $type su dung cho truong hop dac biet
	if (is_null( $type )) {
		$type = $data['type'];
	}
	$params = array(
		'type' 			=> $type,
		'data'			=> $data,
		'index' 		=> $mz_content_index,
		'config'		=> $config,
	);
	// id cua phan tu
	$params['elementId'] 	= $elementId 	= mz_build_element_id($data['code'], $mz_content_index);
	// cac thuoc tinh bat buoc phai co
	$params['commonAttr'] 	= $commonAttr 	= mz_build_element_common_attributes($elementId, $data);
	// metadata
	$params['metadata'] 	= $metadata 	= mz_encode_metadata_json($data['metadata']);
	if ($data['data_type'] == 'html') {
		$html = mz_build_paragraph_form_input_html( $params );
	} elseif ($data['data_type'] == 'file') {
		$html = mz_build_file_form_input_html( $params );
	} else {
		$html = mz_build_other_form_input_html( $params );
	}

	return $html;
}

/**
 * tạo html form input của 1 phần tử
 * @param  $element_data       [cấu hình của phần tử được bóc tách]
 * @param  $element_index      [số thứ tự của phần tử]
 * @param  $mz_content_index   [số thử tự của nội dung]
 * @return string
 * @throws Exception
 */
function mzGetElementFormInput($element_data, $element_index, $mz_content_index) {
	if ( empty($element_data['arr_data']) ) {
		return '';
	}
	$element_type 	= $element_data['type'];

	$element_config = _get_module_config('template_magazine', 'template_element_config');
	if ( !check_array($element_config) ) {
		throw new \Exception("Template element config not found");
	}
	$arr_data 		= $element_data['arr_data'];
	$main_attr 		= $element_data['main_attribute'];
	$main_data  	= $arr_data[$main_attr];			// data cua attribute chinh
    $main_data['temp_id'] = $element_data['temp_id'];
    $main_data['html_body'] = $element_data['html_body'];
    $main_data['count_index'] = $element_index;
	unset($arr_data[$main_attr]);
	$other_data 	= $arr_data;						// data cua cac attributes phu
    $elementName = '';
    if($element_type=='image' && (check_array($element_data['arr_data']['src']) || check_array($element_data['arr_data']['style']))){
        $arrDataImg = !check_array($element_data['arr_data']['src']) ? $element_data['arr_data']['style'] : $element_data['arr_data']['src'];
        $elementName = str_replace('_', ' ', $arrDataImg['metadata']['imgname']['value']);
    }elseif($element_type=='paragraph'){
        $elementName = $element_data['arr_data']['text']['extra']['paragrapname'];
    }
    if($elementName != ''){
        $elementName = '('.$elementName.')';
    }
    $v_index = $element_index + 1;
    global $v_index_chu_thich_anh;
    if(strpos($main_data['data'],'chu_thich_anh_mg') !== false){
        $v_index_chu_thich_anh = $v_index_chu_thich_anh + 1;
        $element_data['type'] = 'paragraph_ghichu';
        $v_index = $v_index_chu_thich_anh;
    }else{
        $v_index = $v_index - $v_index_chu_thich_anh;
    }
	$v_title = mzt_get_html_element_label( $element_data['type'], $v_index,$elementName);
    
	$attributes = $element_data['attributes'];
	if($element_type=='image' && $attributes['data-anchor'] !=='' && strpos($attributes['class'],'section')!==false){
		$v_stt_img = preg_replace("/[^0-9]/", '', $v_title);
		$v_stt_img = intval($v_stt_img/2)+1;
		$v_title =  '<span style="color:red;margin-top: -8px;display: block;">Hình ảnh '.$v_stt_img.'</span><br/>';
		$v_title .=  '&nbsp;&nbsp;&nbsp;- Ảnh PC ';
	}
    if($element_type=='image' && strpos($attributes['class'],'img-background-slide')!==false){
        $v_stt_img = preg_replace("/[^0-9]/", '', $v_title);
		$v_stt_img = intval($v_stt_img/3)+1;
		$v_title =  '<span style="color:red;margin-top: -8px;display: block;">Hình ảnh '.$v_stt_img.'</span><br/>';
        $v_text_bg = (check_array($element_data['arr_data']['src']['metadata']['type_background'])) ? '<span style="color: red;">(không bắt buộc)</span>' : '';
		$v_title .=  '&nbsp;&nbsp;&nbsp;- Background '.$v_text_bg;
    }
    if($element_type=='image' && strpos($attributes['class'],'pc_slide')!==false){
        $v_title =  '&nbsp;&nbsp;&nbsp;- Ảnh PC ';
    }
            
	if($element_type=='image' && (strpos($attributes['class'],'mg-op-mobile')!==false || strpos($attributes['class'],'mg-story-mobile')!==false)){
		$v_title =  '&nbsp;&nbsp;&nbsp;- Ảnh Mobile';
	}
    // nếu là ảnh poster
    if($attributes['class'] == 'img_poster_video'){
        $v_title = 'Ảnh đại diện video '.$v_index;
    }
	// main data
	$v_html .= mz_get_input_html( $main_data, $mz_content_index, $element_config[ $main_data['type'] ] );
	if (!empty($other_data)) {
		// other attribute
		$v_html .= '<a href="javascript:void(0)" onclick="toggleHiddenZone(this)" data-target="#hiddenAttribute__' . $mz_content_index . $element_type . $element_index . '" style="text-align:right;display:block;padding:3px 0;font-size:11px">Xem thêm</a>';
		$v_html .= '<div id="hiddenAttribute__' . $mz_content_index . $element_type . $element_index .'" style="display:none">';
		foreach ($other_data as $attribute => $data) {
			// ---- tieu de ---- //
			$v_html .= '<p style="margin:3px 0">' . mz_get_html_element_attribute_explanation( $attribute ) . '</p>';
			// ---- noi dung ---- //
			$type = $data['type'];
			// thuoc tinh "poster" cua <video> su dung image
			if ($data['type'] == 'video' && $data['attr'] == 'poster') {
				$type = 'image';
			}

			$v_html .= mz_get_input_html( $data, $mz_content_index, $element_config[ $type ], $type );
		}
		$v_html .= '</div>';
	}
	
	return mz_build_element_form_input_html($v_title, $v_html);
}

/**
 * chuyển đổi các chuỗi dạng: 2KB, 10MB thành byte
 * @author dinhbang <bangnd@24h.com.vn>
 * @param  string 	$file_size [ex: 2KB, 10MB, ...]
 * @return integer
 */
function mz_readable_to_byte($file_size){
    $number=substr($file_size,0,-2);
    switch(strtoupper(substr($file_size,-2))){
        case "KB":
            return $number*1024;
        case "MB":
            return $number*pow(1024,2);
        case "GB":
            return $number*pow(1024,3);
        case "TB":
            return $number*pow(1024,4);
        case "PB":
            return $number*pow(1024,5);
        default:
            return $file_size;
    }
}

/**
 * lấy thông tin crop ảnh
 */
function mz_get_image_crop_info($data, $new_data, $mz_content_index) {
	$crop_info = array();
	// neu la image hoac thuoc tinh poster cua <video>
	if ($data['type'] == 'image' || ($data['type'] == 'video' && $data['attr'] == 'poster') ) {
		$v_crop_x = vsprintf('crop_x__%s__%s', array($data['code'], $mz_content_index));
		$v_crop_y = vsprintf('crop_y__%s__%s', array($data['code'], $mz_content_index));
		$v_crop_w = vsprintf('crop_w__%s__%s', array($data['code'], $mz_content_index));
		$v_crop_h = vsprintf('crop_h__%s__%s', array($data['code'], $mz_content_index));
		$v_crop_image = vsprintf('crop_image__%s__%s', array($data['code'], $mz_content_index));
		// neu anh duoc crop
		if (isset($new_data['data'][$v_crop_w]) && intval($new_data['data'][$v_crop_w])
			&& isset($new_data['data'][$v_crop_h]) && intval($new_data['data'][$v_crop_h])) {
			$crop_info['x'] 	= $new_data['data'][$v_crop_x];
			$crop_info['y'] 	= $new_data['data'][$v_crop_y];
			$crop_info['w'] 	= $new_data['data'][$v_crop_w];
			$crop_info['h'] 	= $new_data['data'][$v_crop_h];
			$crop_info['image'] = $new_data['data'][$v_crop_image];
		}
	}
	return $crop_info;
}

/**
 * thực hiện xử lý file tải lên
 */
function mz_process_file(array &$data, $new_data, $mz_content_index, $element_index, &$errors) {
	// construct input name
	$inputName 	= mz_build_element_id( $data['code'], $mz_content_index);
	$oldName 	= 'old_' . $inputName;
	// ten magazine su dung de tao ten file tai len
	$v_mz_name = empty($new_data['data']['magazine_name']) ? '' : $new_data['data']['magazine_name'];
	// neu ko ton tai file input thi bo qua
	if ( empty($new_data['file'][$inputName]) ) return;
	$file = $new_data['file'][$inputName];
    // nếu là ảnh đại diện video
    if(empty($file['name']) && $data['code'] == 'image_0' && intval($new_data['data']['map__video_0__width__'.$mz_content_index]) > 0){
        return;
    }
    // nếu là background slide story 1
    if(empty($file['name']) && $data['type'] == 'image' && $data['metadata']['type_background']['value'] == 1){
        return;
    }
	// neu khong chon file de tai len
	if (empty($file['name'])) {
		// neu bat buoc tai len ma khong co file cu
		if ( $data['is_required'] && empty($new_data['data'][$oldName]) ) { 
			// hien thi thong bao loi
			$errors[$inputName] = "Bạn chưa chọn file";
			return;
		} elseif ( !empty($new_data['data'][$oldName]) ) {
			// neu co thi lay file cu
			$data['data'] 				= $new_data['data'][$oldName];
			$data['mzc_is_uploaded']	= 1; // danh dau file upload len noi dung magazine
		}
		// bo qua upload
		return;
	}
	// lay thong tin crop anh
	$crop_info = mz_get_image_crop_info( $data, $new_data, $mz_content_index );
	// xu ly upload file
	$result = mz_upload_magazine_file($file, $data, $v_mz_name, $crop_info);
	//  co loi xay ra trong qua trinh upload
	if ($result['error']) {
		$errors[$inputName] = $result['msg'];
		return;
	}
	// upload thanh cong
	$data['data'] = $result['info']['file_path']; 
	// danh dau file da upload len noi dung magazine
	$data['mzc_is_uploaded'] = 1;
}

/**
 * Thực hiện cập nhật dữ liệu map mới cho 1 phần tử
 * @param array $element_data [dữ liệu map cũ của phần tử ]
 * @param $new_data [dữ liệu map mới của phần tử ]
 * @param $mz_content_index [số thử tự nội dung magazine ]
 * @param $errors [mảng chứa thông báo lỗi trả về]
 * @throws Exception
 */
function mz_update_map_data(array &$element_data, $new_data, $mz_content_index, &$errors) {
	if ( !empty($element_data['arr_data']) ) {
		foreach ($element_data['arr_data'] as $attribute => &$data) {
			$element_index = $element_data['stt'];
			// ========= validate POST data ============== //
			if ( $data['data_type'] == 'file' ) {
				mz_process_file( $data, $new_data, $mz_content_index, $element_index, $errors);
			} else {
				$inputName 	= mz_build_element_id( $data['code'], $mz_content_index);
				
				if ( isset($new_data['data'][$inputName]) ) {
					if ($data['data_type'] == 'html') {
						// mot so html tag cho phep trong noi dung doan van
						$allow_html_tag = '<br><u><a><strong><em><span>';
						// neu paragraph la div thi noi dung cho phep chua <p>
						if (!empty($data['html_tag']) && $data['html_tag'] == 'div') {
							$allow_html_tag .= '<p>';
						}
						// chi giu lai cac html tag cho phep
						$data['data'] = strip_tags($new_data['data'][$inputName], $allow_html_tag);
					} else { // loai bo toan bo html tag
						$data['data'] = strip_tags($new_data['data'][$inputName]);
					}

					// kiem tra dinh dang du lieu
					if ($data['data_type'] == 'url') {
						if (filter_var($data['data'], FILTER_VALIDATE_URL) === false) {
							$errors[$inputName] = 'Link không đúng định dạng';
							continue;
						}
					} elseif ($data['data_type'] == 'int') {
						if ( !intval($data['data']) ) {
							$errors[$inputName] = 'Dữ liệu nhập phải là số nguyên';
							continue;
						}
					}
					// kiem tra so tu toi da doi voi tieu de va doan van
					if ( $data['type'] == 'title' || $data['type'] == 'paragraph' ) {
						if( !empty($data['metadata']['word_count']['value']) ) {
							$v_wc = str_word_count_utf8($data['data']);
							$v_valid_wc = (int) $data['metadata']['word_count']['value'];
							$v_operator = '<='; // so tu toi da => mac dinh la  "<="

							if (!mz_check_by_oparator($v_wc, $v_operator, $v_valid_wc)) {
								$errors[$inputName] = 'Bạn nhập quá số ký tự cho phép';
								continue;
							}
						}
					}
				}
			}
		}
	}
}

/**
 * cập nhật thông tin map mới cho các phần tử tự định nghĩa
 * @author bangnd <bangnd@24h.com.vn>
 * @param  array $maps [cấu hình mặc định của template]
 * @param  array $new_data [mảng các file, text mới]
 * @param  integer $mz_content_index [số thứ tự của nội dung]
 * @param $errors
 * @return void
 * @throws Exception
 */
function mz_update_magazine_template_with_new_data(array &$maps, array $new_data, $mz_content_index, &$errors) {
	if ( !empty($maps) ) {
		foreach ($maps as $mkind => &$map) { // mkind: auto/defined
			if ( $mkind == 'defined' && !empty($map) ) {
				foreach ($map as $element_type => &$arr_element) { // element_type: image,js,css,title,...
					if (empty($arr_element)) continue;
					foreach ($arr_element as $element_index => &$element_data) {
                        // Nếu có chọn hiệu ứng
                        if($new_data['data']['sel_loai_giai_dauvideo_'.$element_index.'__'.$mz_content_index] != ''){
                            $v_arr_ghi_chu_quang_cao_theo_loai = _get_module_config('doi_tac_quang_cao_video','v_arr_ghi_chu_quang_cao_theo_loai');
                            $v_type_quang_cao_doi_tac = $new_data['data']['sel_type_quang_cao_doi_tacvideo_'.$element_index.'__'.$mz_content_index];
                            $v_dau_hieu_quang_cao  = $v_arr_ghi_chu_quang_cao_theo_loai[$v_type_quang_cao_doi_tac]['c_dau_hieu'];
                            $new_data['file']['map__video_'.$element_index.'__'.$mz_content_index]['c_ma_giai_dau'] = $v_dau_hieu_quang_cao.$new_data['data']['sel_loai_giai_dauvideo_'.$element_index.'__'.$mz_content_index];
                        }
						mz_update_map_data($element_data, $new_data, $mz_content_index, $errors);
                        if($new_data['data']['effect_image__image_'.$element_index.'__'.$mz_content_index] != ''){
                            $v_effect_image = $new_data['data']['effect_image__image_'.$element_index.'__'.$mz_content_index];
							$element_data['attributes']['class'] = $element_data['attributes']['class']." ".$v_effect_image;
                            // gắn vào trong  html_preview
                            preg_match_all('#class=\s*"([^\"]*)"#ism', $element_data['html_preview'], $match_html_preview);
                            if(check_array($match_html_preview[0])){
                                // lặp danh sách clas
                                foreach($match_html_preview[1] as $preview){
                                    $v_preview_replace = str_replace($v_effect_image, '', $preview);
                                    // gắn class
                                    $v_preview_replace  = $v_preview_replace.' '.$v_effect_image;
                                    // replace vào trong html_preview
                                    $element_data['html_preview'] = str_replace($preview, $v_preview_replace, $element_data['html_preview']);
                                }
                            }
                            
                            // gắn vào trong  html_template
                            preg_match_all('#class=\s*"([^\"]*)"#ism', $element_data['html_template'], $match_html_template);
                            if(check_array($match_html_template[1])){
                                // lặp danh sách clas
                                foreach($match_html_template[1] as $template){
                                    $v_template_replace = str_replace($v_effect_image, '', $template);
                                    // gắn class
                                    $v_template_replace  = $v_template_replace.' '.$v_effect_image;
                                    // replace vào trong html_template
                                    $element_data['html_template'] = str_replace($template, $v_template_replace, $element_data['html_template']);
                                }
                            }
                        }
					    // prevent strange behavior of foreach pass-by-reference
					    unset($element_data);
					}
					unset($arr_element);
				}
			}
			unset($map);
		}
	}
}

/**
 * thực hiện kiểm tra theo các toán tử định nghĩa
 * @throws Exception
 */
function mz_check_by_oparator($left_number, $operator, $right_number) {
	if ($operator == '>') {
		return $left_number > $right_number;
	} elseif ($operator == '>=') {
		return $left_number >= $right_number;
	} elseif ($operator == '<') {
		return $left_number < $right_number;
	} elseif ($operator == '<=') {
		return $left_number <= $right_number;
	} elseif ($operator == '=') {
		return $left_number == $right_number;
	} else {
		throw new \Exception("Operator $operator is not valid");
	}
}

/**
 * tạo đường dẫn upload các file của magazine content
 * @param string $v_type
 * @param string $p_path_format
 * @return string
 */
//  ex: /upload/3-2018/images/2018-07-30/
function mz_get_magazine_upload_path($v_type = 'image', $p_path_format = 'upload/%s/magazine/%s/%s') {
	// TODO: move upload path format to config file
	$v_quater = quarterByDate(date('m')) . '-' . date('Y'); // 3-2018

	switch ($v_type) { // images
		case 'image': $v_type_dir = 'images'; break;
		case 'video': $v_type_dir = 'videoclip'; break;
		case 'audio': $v_type_dir = 'audio'; break;
		default: $v_type_dir = 'other'; break;
	}

	$v_date = date('Y-m-d');

	return vsprintf($p_path_format, array( $v_quater, $v_type_dir, $v_date));
}

/**
 * tạo tên file upload của magazine content
 * @param $uploader
 * @param string $p_magazine_name
 * @param $p_code
 * @return null|string|string[]
 */
function mz_get_magazine_file_name($uploader, $p_magazine_name='', $p_code) {
	if (!empty($p_magazine_name)) {
		$fileNameWoExt = $p_magazine_name; 
	} else {
		$fileNameWoExt = basename($uploader->getOriginalFileName(), '.'. $uploader->getFileExtension());
	}
	// loại bỏ các kí tự đặc biệt khỏi tên file
	$fileNameWoExt = preg_replace('#[^\-a-zA-Z0-9_\.]#', '-', _utf8_to_ascii($fileNameWoExt));
	// ex: -image_0
	$fileNameWoExt .= '-' . $p_code; 
	// random thời gian upload và random numer tránh trùng tên file
	$fileNameWoExt .= '- ' . round(microtime(true)) . '-' . mt_rand();

	return $fileNameWoExt;
}

/**
 * tai len image dang base64string
 * @param  string 	$base64string 	[image duoi dang chuoi base64]
 * @param  string 	$v_file_path  	[duong dan luu file crop]
 * @param  string 	$v_old_path   	[duong dan luu file anh tai len]
 * @param  string 	$v_extension  	[phan mo rong, mac dinh crop image luu duoi dang jpg]
 * @return boolean
 */
function mz_upload_base64_image($base64string, $v_file_path, $v_old_path, $v_extension) {
	$v_crop_image = $base64string;
	$v_arr_image_data = explode(',', $v_crop_image);
	if (empty($v_arr_image_data[1])) {
		return false;
	}

	$v_image_base64_string = $v_arr_image_data[1];
	$v_dst_img = imagecreatefromstring(base64_decode($v_image_base64_string));
	if ($v_dst_img === false) {
		return false;
	}
    $v_is_ok = imagejpeg($v_dst_img, $v_file_path, 100);
    // Free up memory
    imagedestroy($v_dst_img);
    if ($v_is_ok === false) {
    	return false;
    }
    // delete old file
    if ($v_extension != 'jpg') {
    	unlink($v_old_path);
    }

    return true;
}

/**
 * hàm thực hiện upload các file của magazine
 * @author bangnd <bangnd@24h.com.vn>
 * @param  array 	$file        [$_FILE]
 * @param  array 	$data        [dữ liệu map của phần tử]
 * @param string    $p_magazine_name [tên nôi dung magazine, sử dụng trong tên file tải lên]
 * @param  array 	$crop_info [thông tin crop của ảnh nếu có]
 * @return array
 */
function mz_upload_magazine_file($file, $data, $p_magazine_name = '' , array $crop_info = array()) {
	try {
		$type = $data['type'];
		// truong hop: thuoc tinh poster cua <video>
		if ($data['type'] == 'video' && $data['attr'] == 'poster') {
			$type = 'image';
		}
		// config mac dinh cua element
		$element_config = _get_module_config('template_magazine', 'template_element_config');
		if ( !check_array($element_config) || !check_array( $config = $element_config[$type] )) {
			throw new \Exception("Template element config not found");
		}
		$uploader = new Uploader( $file );
		// TODO: ghi đè các thuộc tính validate nếu có: file_size, width, height
		$uploader->setConfig( $config );
		// tao file name tu ten magazine
		$v_file_name = mz_get_magazine_file_name( $uploader, $p_magazine_name, $data['code'] );
        // gắn thêm đánh dấu video bản quyền
        if($file['c_ma_giai_dau'] != ''){
            $uploader->setextendFileName(fw24h_replace_bad_char($file['c_ma_giai_dau']));
        }
        
		// dat ten file tai len
		$uploader->setFileName($v_file_name);
		// absolute path
		$v_upload_path = rtrim(ROOT_FOLDER, '/') . '/' .ltrim(mz_get_magazine_upload_path($type), '/');
		// duong dan tuyet doi folder upload
		$uploader->setUploadPath($v_upload_path);
		// co loi xay ra
		if(!$uploader->upload()) {
			return array('error' => true, 'msg' => $uploader->getError());
		}
		// đưỡng dẫn tuyệt đối tới folder chứa file
        $v_upload_path = rtrim(ROOT_FOLDER, '/') . '/' . ltrim($uploader->getUploadPath(), '/');
		$v_upload_info 	= $uploader->info();
		$v_file_name 	= $uploader->getFileName();
		$v_origin_name 	= $uploader->getOriginalFileName(); // ten file luc ban dau
		$v_file_path 	= rtrim($v_upload_path, '/') . '/' . $v_file_name;
        $v_old_path     = $v_file_path;
		// neu image duoc crop
		if ( !empty( $crop_info ) && $type == 'image' ) {
			// neu ton tai anh crop duoi dang base64string
			if (!empty($crop_info['image'])) {
				$v_extension = $uploader->getFileExtension();
				// neu khong phai jpg image, thi cap nhat lai mot so thong tin tra ve
				if ($v_extension != 'jpg') {
					// duong dan cua file anh tai len chua crop

					// doi phan mo rong cua file
					$v_name_no_ext 	= basename($v_file_name, '.' . $v_extension);
					$v_file_name 	= $v_name_no_ext . '.jpg';
					$v_file_path 	= rtrim($v_upload_path, '/') . '/' . $v_file_name;
					// cap nhat thong tin tra ve, mac dinh luu anh crop duoi dang jpg
					$v_upload_info['file_path'] = '/'. trim(str_replace(ROOT_FOLDER, '', $v_upload_path), '/') . '/' . $v_file_name;
					$v_upload_info['file_name'] = $v_file_name;
					$v_upload_info['mime_type'] = 'image/jpeg';
					$v_upload_info['extension'] = 'jpg';
				}
				// thuc hien upload anh duoi dang base64string 
				if ( !mz_upload_base64_image($crop_info['image'], $v_file_path, $v_old_path, $v_extension) ) {
					unlink($v_old_path); // xoa file da upload
					return array('error' => true, 'msg' => vsprintf("Lỗi cắt ảnh \'%s\'", [$v_origin_name ]));
				}
			} else {
				$crop_x = (int) $crop_info['x'];
				$crop_y = (int) $crop_info['y'];
				$crop_w = (int) $crop_info['w'];
				$crop_h = (int) $crop_info['h'];
				// need retest
				$uploader->cropImage($crop_x, $crop_y, $crop_w, $crop_h);
			}
		}

		// kiem tra dung luong file
		$v_file_size = filesize($v_file_path);
		if ( !mz_check_file_size($v_file_size, $data, $config) ) {
			unlink($v_file_path);
			return array( 'error' => true, 'msg' => vsprintf("File \'%s\' vượt quá dung lượng cho phép", [$v_origin_name]));
		}
		// kiem tra chieu rong va chieu cao cua image / video
		if ( $type == 'image' || $type == 'video' ) {
			if ( $type == 'image' ) {
				list($v_width, $v_height) = getimagesize($v_file_path);
			} else {
                $cmd = '/usr/local/bin/ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of json '.$v_file_path;
                exec($cmd, $output);
                $list = array("width"=>intval(explode(":",$output[6])[1]),"height"=>intval(explode(":",$output[7])[1]));
                $v_height = intval($list['height']); 
                $v_width = intval($list['width']);
			}
            if ( $type == 'image' ) {
                if ( !mz_check_media_width($v_width, $data, $config) ) {
                    unlink($v_file_path);
                    return array('error' => true, 'msg' => vsprintf("Chiều rộng %s \'%s\' không hợp lệ", ['ảnh', $v_origin_name]));
                }
                if ( !mz_check_media_height($v_height, $data, $config) ) {
                    unlink($v_file_path);
                    return array('error' => true, 'msg' => vsprintf("Chiều cao %s \'%s\' không hợp lệ", ['ảnh', $v_origin_name]));
                }
			}
            // kiểm tra riêng cho video
            if ( $type == 'video' ) {
                if ($v_width > intval($config['max_width'])) {
                    unlink($v_file_path);
                    return array('error' => true, 'msg' => vsprintf("Chiều rộng %s \'%s\' không hợp lệ", ['video', $v_origin_name]));
                }
                if ($v_height > intval($config['max_height'])) {
                    unlink($v_file_path);
                    return array('error' => true, 'msg' => vsprintf("Chiều cao %s \'%s\' không hợp lệ", ['video', $v_origin_name]));
                }
			}
		}
		
		return array('error' => false, 'info' => $v_upload_info);

	} catch (\Exception $ex) {
		return array('error' => true, 'msg' => $ex->getMessage());
	}
}

/**
 * kiểm tra dung lượng file tải lên
 * @param $file_size
 * @param array $data [dữ liệu map của phần tử ]
 * @param array $config [cấu hình của từng loại phần tử html: image, paragraph, ... (xem file template_magazine.conf.php)]
 * @return bool
 * @throws Exception
 */
function mz_check_file_size($file_size, array $data, array $config) {
	// dung luong toi da => "<"
	$operator 	= '<';
	// neu co dinh nghia quy tac ve file_size
	if( !empty( $data['metadata']['file_size_gif'] ) ) {
        $valid_size = mz_readable_to_byte( $data['metadata']['file_size_gif']['value'] );
    }elseif( !empty( $data['metadata']['file_size'] ) ) {
		// chuyen tu data dang humanreable sang byte, ex: 1KB -> 1024
		$valid_size = mz_readable_to_byte( $data['metadata']['file_size']['value'] );
    }else {
		$valid_size = $config['max_file_size'];
	}

	if ( !mz_check_by_oparator($file_size, $operator, intval($valid_size)) ) {
		return false;
	}
	return true;
}

/**
 * kiểm tra kích thước chiều rộng ảnh
 * @param $image_width
 * @param array $data [dữ liệu map của phần tử ]
 * @param array $config [cấu hình của từng loại phần tử html: image, paragraph, ... (xem file template_magazine.conf.php)]
 * @return bool
 * @throws Exception
 */
function mz_check_media_width($image_width, array $data, array $config) {

	if( !empty( $data['metadata']['width'] ) ) {
		$valid_width = $data['metadata']['width']['value'];
		$operator 	 = $data['metadata']['width']['operator'];
	} else {
		$valid_width = $config['max_width'];
		$operator 	 = '<';
	}

	if ( !mz_check_by_oparator($image_width, $operator, intval($valid_width)) ) {
		return false;
	}
	return true;
}

/**
 * kiểm tra kích thước chiều cao ảnh
 * @param $image_height
 * @param array $data [dữ liệu map của phần tử]
 * @param array $config [cấu hình của từng loại phần tử html: image, paragraph, ... (xem file template_magazine.conf.php)]
 * @return bool
 * @throws Exception
 */
function mz_check_media_height($image_height, array $data, array $config) {

	if( !empty( $data['metadata']['height'] ) ) {
		$valid_width = $data['metadata']['height']['value'];
		$operator 	 = $data['metadata']['height']['operator'];
	} else {
		$valid_width = $config['max_height'];
		$operator 	 = '<';
	}

	if ( !mz_check_by_oparator($image_height, $operator, intval($valid_width)) ) {
		return false;
	}
	return true;
}

/**
 * lấy ra các file css, js đặt trên <head></head>
 * loai bo cac file trung noi dung
 * @author bangnd <bangnd@24h.com.vn>
 * @param  array $html_map [mảng chứa các file của template]
 * @param array $arr_files
 */
function mz_get_head_files(array $html_map, array &$arr_files) {
    if( !check_array($html_map) )  return;
	mzt_loop_through_map($html_map, function ($element_data) use (&$arr_files) {
		if (!empty($element_data['arr_data'])) {
			foreach ($element_data['arr_data'] as $attribute => $data) {
				if ( !in_array($data['type'], ['css','js']) ) continue;
				if (empty($data['hash'])) {
					// mac dinh phan tu key = 0 la mang chua cac file ko co hash
					// cac file ko co hash thuong la remote url
					$arr_files['remote'][$data['type']][sha1($data['data'])] = $data;
				} else {
					$arr_files['uploaded'][$data['type']][$data['hash']] = $data;
				}
			}
		}
	});
}

/**
 * nén file css và js đặt trên head
 * @param $p_arr_head_files
 * @param $p_magazine_id
 */
function mz_minify_head_files(&$p_arr_head_files, $p_magazine_id)
{
	// var_dump($p_arr_head_files['uploaded']['css']);
	if(!check_array($p_arr_head_files) || empty($p_arr_head_files['uploaded'])) return;

	$v_root_path 	= rtrim(ROOT_FOLDER, '/') . '/';
	$css_minifier 	= $js_minifier = null;
	$css_min_path 	= $js_min_path = '';
    $v_arr_css_minifier = array();
    $v_arr_js_minifier = array();
    
	if (!empty($p_arr_head_files['uploaded']['css'])) {

		foreach ($p_arr_head_files['uploaded']['css'] as $file_hash => $file) {
		    // cac file da dc upload len server
		    $v_file_path =  $v_root_path . ltrim($file['data'], '/');
            if(!file_exists($v_file_path)){
                $v_url_css_file = MST_DOMAIN.ltrim($file['data']);
                $v_csscontent = file_get_contents($v_url_css_file);
                $v_csscontentmin = minifyCss24h($v_csscontent);
                $css_min_path = dirname(ltrim($file['data'], '/'));
                $css_min_file_path = rtrim($css_min_path, '/') . '/' . 'magazine_' . $p_magazine_id . '.min.css';
                file_put_contents(ROOT_FOLDER.$css_min_file_path, $v_csscontentmin);
                $v_arr_css_minifier['css_min'] = '/' .$css_min_file_path;
                $v_arr_css_minifier['hash'] = sha1($v_csscontentmin);
            }else{
                if (!is_null($css_minifier)) {
                    $css_minifier->add( $v_file_path );
                } else {
                    $css_minifier = new Minify\CSS( $v_file_path );
                }
                if (empty($css_min_path)) {
                    $css_min_path = dirname($v_file_path);
                }
            }
		}
	}
    // Tạo mảng head files
    if(check_array($v_arr_css_minifier)){
		$p_arr_head_files['min']['css'][$v_arr_css_minifier['hash']] = array(
			'type' => 'css',
			'data_type' => 'file',
			'data' => $v_arr_css_minifier['css_min'],
			'hash' => $v_arr_css_minifier['hash']
		);
    }elseif (!is_null($css_minifier)) {
		$css_min_file_path = rtrim($css_min_path, '/') . '/' . 'magazine_' . $p_magazine_id . '.min.css';
		$css_minifier->minify( $css_min_file_path );
		$css_hash = sha1_file($css_min_file_path);
        
		$p_arr_head_files['min']['css'][$css_hash] = array(
			'type' => 'css',
			'data_type' => 'file',
			'data' => '/'. trim(str_replace(ROOT_FOLDER, '', $css_min_file_path), '/'),
			'hash' => $css_hash
		);
	}
	if (!empty($p_arr_head_files['uploaded']['js'])) {
		foreach ($p_arr_head_files['uploaded']['js'] as $file_hash => $file) {
		    // cac file da dc upload len server
		    $v_file_path =  $v_root_path . ltrim($file['data'], '/');
            if(!file_exists($v_file_path)){
                $v_url_js_file = MST_DOMAIN.ltrim($file['data']);
                $v_jscontent = file_get_contents($v_url_js_file);
                $v_jscontentmin = minifyjs24h($v_jscontent);
                $js_min_path = dirname(ltrim($file['data'], '/'));
                $js_min_file_path = rtrim($js_min_path, '/') . '/' . 'magazine_' . $p_magazine_id . '.min.js';
                file_put_contents(ROOT_FOLDER.$js_min_file_path, $v_jscontentmin);
                $v_arr_js_minifier['js_min'] = '/' .$js_min_file_path;
                $v_arr_js_minifier['hash'] = sha1($v_jscontentmin);
            }else{
                if (!is_null($js_minifier)) {
                    $js_minifier->add( $v_file_path );
                } else {
                    $js_minifier = new Minify\JS( $v_file_path );
                }
                if (empty($js_min_path)) {
                    $js_min_path = dirname($v_file_path);
                }
            }
		}
	} // Tạo mảng head files
    if(check_array($v_arr_js_minifier)){
		$p_arr_head_files['min']['js'][$v_arr_js_minifier['hash']] = array(
			'type' => 'js',
			'data_type' => 'file',
			'data' => $v_arr_js_minifier['js_min'],
			'hash' => $v_arr_js_minifier['hash']
		);
    }elseif (!is_null($js_minifier)) {
		$js_min_file_path = rtrim($js_min_path, '/') . '/' . 'magazine_' . $p_magazine_id . '.min.js';
		$js_minifier->minify( $js_min_file_path );
		$js_hash = sha1_file($js_min_file_path);
		$p_arr_head_files['min']['js'][$js_hash] = array(
			'type' => 'js',
			'data_type' => 'file',
			'data' => '/'. trim(str_replace(ROOT_FOLDER, '', $js_min_file_path), '/'),
			'hash' => $js_hash
		);
	}
}

/**
 * chuẩn bị các dữ liệu để cập nhật một magazine content
 * @param array $v_arr_magazine_content
 * @return array
 */
function mz_gather_magazine_content_data(array $v_arr_magazine_content)
{
	$v_arr_data = array();
	if (check_array($v_arr_magazine_content)) {
		foreach ($v_arr_magazine_content as $mz_content_index => $mzc) {

	        if (empty($mzc['template_id'])) {
	        	continue;
	        }
	        
	        $v_position     = intval($mzc['position']);

            if (intval($mzc['content_id']) > 0) {
                $v_magazine_content = be_get_single_magazine_content(intval($mzc['content_id']));

                $v_template_id = $v_magazine_content['fk_magazine_template'];

                $v_html_template    = mzt_restore_bad_char($v_magazine_content['c_html_template']);
                $v_html_map         = json_decode($v_magazine_content['c_html_map'], true);
            } else {
                $v_template_id  = intval($mzc['template_id']);
                $v_template     = be_get_magazine_template($v_template_id);

                if (!check_array($v_template)) {
                    continue;
                }

                $v_html_template = mzt_restore_bad_char($v_template['c_html_template']);
                $v_html_map = json_decode($v_template['c_html_map'], true);
            }

	        // create magazine content
	        $v_arr_data[] = array(
	            'v_template_id'     => $v_template_id,
	            'v_html_template'   => $v_html_template,
	            'v_html_map'        => $v_html_map,
	            'v_position'        => $v_position,
	            'v_stt'             => $mz_content_index
	        );
	    }
	}
    return $v_arr_data;
}

/**
 * hiển thị các file css và js nằm trên head
 * @param $head_files
 */
function mz_echo_magazine_head_files($head_files)
{
	$v_css_html = $v_js_html = '';

    if (check_array($head_files)) {
    	// xu ly remote url
    	if (!empty($head_files['remote'])) {
    	    foreach ($head_files['remote'] as $file_type => $files) {
    	        if (!check_array($files)) continue;
    	        foreach ($files as $file) {
    	            if ($file['type'] == 'css') {
    	                $v_css_html .= '<link rel="stylesheet" type="text/css" href="'. $file['data'] .'" />';
    	            } elseif ($file['type'] == 'js') {
    	                $v_js_html .= '<script src="'. $file['data'] .'"></script>';
    	            }
    	        }
    	    }
    	}
    	$v_arr_local_files = array();

    	if (!empty($head_files['min'])) {
    	    $v_arr_local_files = $head_files['min'];
    	} elseif (!empty($head_files['uploaded'])) {
    	    $v_arr_local_files = $head_files['uploaded'];
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
    	            if ($file['type'] == 'css') {
    	                $v_css_html .= '<link rel="stylesheet" type="text/css" href="'. $v_url .'" />';
    	            } elseif ($file['type'] == 'js') {
    	                $v_js_html .= '<script src="'. $v_url .'"></script>';
    	            }
    	        }
    	    }
    	}
    }

    echo $v_css_html;
    echo $v_js_html;
}

/**
 * hàm đếm số từ trong một chuỗi, có sử dụng các ký tự utf-8
 * @link: https://gist.github.com/abhineetmittal/d250083def7c356bbf161ff74444ebcc
 * @param $unicode_string
 * @return int
 */
function str_word_count_utf8($unicode_string) {
	//exclude  start and end white-space
	$unicode_string = trim($unicode_string);
	// Remove all the html tag
	$unicode_string = strip_tags(html_entity_decode($unicode_string));
	// First remove all the punctuation marks & digits
  	$unicode_string = preg_replace('/[[:punct:]]/', '', $unicode_string);
  	// normalize '&nbsp;' to space, 
  	// because &nbsp; is being converted to a UTF-8 "NO-BREAK SPACE" character, \u00A0
  	$unicode_string = str_replace("\xC2\xA0", " ", $unicode_string);
  	// Now replace all the whitespaces (tabs, new lines, multiple spaces) by single space
  	$unicode_string = preg_replace('/[[:space:]]/', ' ', $unicode_string);
  	// The words are now separated by single spaces and can be splitted to an array
  	// I have included \n\r\t here as well, but only space will also suffice
  	$words_array = preg_split( "/[\n\r\t ]+/", $unicode_string, 0, PREG_SPLIT_NO_EMPTY );
  	// Now we can get the word count by counting array elments
  	return count($words_array);
}

/*
 * @author trungcq - 15-01-2019
 * @desc: Xử lý background bài magazine 
 * @param: $p_html_content string Nội dung HTML bài magazine
 * @return: string
 *  */
function xu_ly_background_bai_magazine ($p_html_content=''){
	$v_string = $p_html_content;
	// Nếu có sử dụng template background-image
	if(strpos($v_string,'@@begin-header-background-img-magazine@@') !== false) {
		// Xử lý header background-image
		$v_string = preg_replace('/<!--@@begin-header-background-img-magazine@@-->.*(<div.*class="mgz_bg_img".*>).*<!--@@end-header-background-img-magazine@@-->/msU','$1',$v_string);
		// Xử lý footer background-image
		$v_string .='</div>';
	}
	return $v_string;
}
/*
 * Hàm chuẩn hóa dữ liệu cho template slide story
 *  */
function mz_convert_data_template_slide_story($p_new_data=array(), $v_stt=0){
    $v_new_data = $p_new_data;
    // kiểm tra tham số
    if(!check_array($v_new_data['file'])){
        return $v_new_data;
    }
    $v_key_map = 'map__image_0__'.$v_stt;
    $v_key_replace = 'map__image_1__'.$v_stt;
    // nếu background ko nhập mà ảnh pc nhập => lấy ảnh Background theo PC
    if($v_new_data['file'][$v_key_map]['name'] == '' && $v_new_data['file'][$v_key_replace]['name'] != ''){
        $v_new_data['file'][$v_key_map] = $v_new_data['file'][$v_key_replace];
    }
    return $v_new_data;
}
function mz_read_file($p_file_path){
	$v_ret = "";
	$handle = fopen($p_file_path,"r");
	if($handle){
		while(!feof($handle)){
			$v_ret .= fread($handle,1000000);
		}
	}
	return $v_ret;
}