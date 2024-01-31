<?php

include_once('class/uploader.php');
include_once('minify/autoloader.php');
use MatthiasMullie\Minify;

function mzGetAttributeTitle($attr) {
	$arrAttrTitles = array(
		'poster' 	=> 'Ảnh đại diện video',
		'alt' 		=> 'Tiêu đề ẩn của ảnh',
		'title' 	=> 'Tiêu đề ẩn của link',
		'width'		=> 'Chiều rộng',
		'height'	=> 'Chiều cao',
	);

	return empty($arrAttrTitles[$attr]) ? $attr : $arrAttrTitles[$attr];
}

function mzBuildElementFormInputHtml($title, $body, $format_html = '') {
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

function mzGetElementId($code, $mz_content_index) {
	return vsprintf('map__%s__%s', array($code, $mz_content_index));
}

function mzGetCommonAttribute($elementId, array $data) {
	return vsprintf('id="%s" name="%s" data-type="%s" data-code="%s" data-dtype ="%s" data-is-required="%s" data-attr="%s"', array($elementId, $elementId, $data['type'], $data['code'], $data['data_type'], (int)$data['is_required'], $data['attr']));
}

function mzGetMetadataJson($arr_metadata) {
	return empty($arr_metadata) ? '' : htmlentities( json_encode( $arr_metadata) );
}

function mzGetFileAccept($elementType, $config) {
	$str_accept = '';
	if(!empty($config['allow_extensions'])) {
		$str_accept .= '.' . implode(',.',$config['allow_extensions']);
	}
	if (!empty($config['allow_mime_types'])) {
	 	$str_accept .= ',' . $v_str_allow_mime_types;
	}
	return $str_accept;
}

function mzGetFileAllowExtensions($elementType, $config) {
	$str_extensions = '';
	if(!empty($config['allow_extensions'])) {
		$str_extensions =   implode(',',$config['allow_extensions']);
	}
	return $str_extensions;
}

function mzGetFileAllowMimeTypes($elementType, $config) {
	$str_mimetypes = '';
	if(!empty($config['allow_mime_types'])) {
		$str_mimetypes =   implode(',',$config['allow_mime_types']);
	}
	return $str_mimetypes;
}

function mzGetHtmlInputHtml(array $params) {
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

function mzGetFileInputHtml(array $params) {
	$data = $params['data'];

	$v_html = '<div>'; // begin html
	
	// accept attribute
	$str_accept 		= mzGetFileAccept($params['type'], $params['config']);	
	$str_mimetypes 		= mzGetFileAllowMimeTypes($params['type'], $params['config']);

	if (!empty($data['metadata']['type'])) {
		$str_extensions = $data['metadata']['type']['value'];
	} else {
		$str_extensions = mzGetFileAllowExtensions($params['type'], $params['config']);
	}
	
	$v_html .= '<div class="input-wrapper"'. (!empty($data['mzc_is_uploaded']) ? 'style="width: 95%;
    display: inline-block;"' : '') .'>'; // begin 
	$v_html .= vsprintf( '<input type="file" %s class="map-element fluid" data-init="%s" data-metadata="%s" accept="%s" data-extension="%s" data-mime-type="%s" data-stt="%s" onchange="onChangeFile(this)">', array($params['commonAttr'], $data['data'], $params['metadata'], $str_accept, $str_extensions, $str_mimetypes, $params['index']) );

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
	}
	$v_html .= '</div>'; // end
	if (!empty($data['mzc_is_uploaded'])) {
		$v_html .= '<a target="_blank" href="javascript:void(0)" onclick="window.open(\''. BASE_DOMAIN . $data['data'] .'\', \'new_window\', \'width=1200, height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes\')" style="display: inline-block;position: relative;top: 5px;width:5%;padding-left:8px;"><img src="'. html_image('images/imgpreview.gif', false) . '" width="16" height="16" /></a>';
		$v_html .= vsprintf('<input type="hidden" name="old_%s" value="%s" readonly>', array($params['elementId'], $data['data']));
	}
	// hien thi cac quy tac
	if (!empty($data['metadata'])) {
		$v_html .= '<p class="mz-metadata"><strong style="color:green">Quy tắc: </strong>';
		foreach ($data['metadata'] as $name => $meta) {
		    if (empty($meta['value'])) continue;
		    	$v_html .= '<span>'. mzGetMetadataTitle($name, $meta['operator']) .'</span>';
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

function mzGetOtherInputHtml(array $params) {
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
		    	$v_html .= '<span>'. mzGetMetadataTitle($name, $meta['operator']) .'</span>';
		    	$v_html .= '<strong>'. $meta['value'] .'</strong>';
			}
			$v_html .= '</p>';
			$v_html .= '<span class="error-map-element"></span>';
		}
	}
	return $v_html;
}

function mzGetInputHtml(array $data, $mz_content_index, $config, $type = null) {
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
	$params['elementId'] 	= $elementId 	= mzGetElementId($data['code'], $mz_content_index);
	// cac thuoc tinh bat buoc phai co
	$params['commonAttr'] 	= $commonAttr 	= mzGetCommonAttribute($elementId, $data);
	// metadata
	$params['metadata'] 	= $metadata 	= mzGetMetadataJson($data['metadata']);

	if ($data['data_type'] == 'html') {
		$html = mzGetHtmlInputHtml( $params );
	} elseif ($data['data_type'] == 'file') {
		$html = mzGetFileInputHtml( $params );
	} else {
		$html = mzGetOtherInputHtml( $params );
	}

	return $html;
}

/**
 * [mzGetElementFormInput description]
 * @param  [type] $element_data           [description]
 * @param  [type] $element_index          [description]
 * @param  [type] $mz_content_index 	  [index cua noi dung]
 * @param  array  $element_config         [description]
 * @return [type]                         [description]
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
	unset($arr_data[$main_attr]);
	$other_data 	= $arr_data;						// data cua cac attributes phu

	$v_title = mzGetElementLabel( $element_data['type'], $element_index + 1 );

	$v_html = '';
	// main data
	$v_html .= mzGetInputHtml( $main_data, $mz_content_index, $element_config[ $main_data['type'] ] );
	if (!empty($other_data)) {
		// other attribute
		$v_html .= '<a href="javascript:void(0)" onclick="toggleHiddenZone(this)" data-target="#hiddenAttribute__' . $mz_content_index . $element_type . $element_index . '" style="text-align:right;display:block;padding:3px 0;font-size:11px">Xem thêm</a>';
		$v_html .= '<div id="hiddenAttribute__' . $mz_content_index . $element_type . $element_index .'" style="display:none">';
		foreach ($other_data as $attribute => $data) {
			// ---- tieu de ---- //
			$v_html .= '<p style="margin:3px 0">' . mzGetAttributeTitle( $attribute ) . '</p>';
			// ---- noi dung ---- //
			$type = $data['type'];
			// thuoc tinh "poster" cua <video> su dung image
			if ($data['type'] == 'video' && $data['attr'] == 'poster') {
				$type = 'image';
			}

			$v_html .= mzGetInputHtml( $data, $mz_content_index, $element_config[ $type ], $type );
		}
		$v_html .= '</div>';
	}
	
	return mzBuildElementFormInputHtml($v_title, $v_html);
}

/**
 * chuyển đổi các chuỗi dạng: 2KB, 10MB thành byte
 * @author dinhbang <bangnd@24h.com.vn>
 * @param  string 	$file_size [ex: 2KB, 10MB, ...]
 * @return integer
 */
function mzConvertToByte($file_size){
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

function mzGetImageCropInfo($data, $new_data, $mz_content_index) {
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

function mzProcessFile(array &$data, $new_data, $mz_content_index, $element_index, &$errors) {
	// construct input name
	$inputName 	= mzGetElementId( $data['code'], $mz_content_index);
	$oldName 	= 'old_' . $inputName;
	// ten magazine su dung de tao ten file tai len
	$v_mz_name = empty($new_data['data']['magazine_name']) ? '' : $new_data['data']['magazine_name'];
	// neu ko ton tai file input thi bo qua
	if ( empty($new_data['file'][$inputName]) ) return;
	$file = $new_data['file'][$inputName];
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
	$crop_info = mzGetImageCropInfo( $data, $new_data, $mz_content_index );
	// xu ly upload file
	$result = mzUploadMagazineFiles($file, $data, $v_mz_name, $crop_info);
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

function mzUpdateMapData(array &$element_data, $new_data, $mz_content_index, &$errors) {
	if ( !empty($element_data['arr_data']) ) {
		foreach ($element_data['arr_data'] as $attribute => &$data) {
			$element_index = $element_data['stt'];
			// ========= validate POST data ============== //
			if ( $data['data_type'] == 'file' ) {
				mzProcessFile( $data, $new_data, $mz_content_index, $element_index, $errors);
			} else {
				$inputName 	= mzGetElementId( $data['code'], $mz_content_index);
				
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

							if (!mzCheckByOparator($v_wc, $v_operator, $v_valid_wc)) {
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
 * thông tin mới vào cấu hình của template
 * @author bangnd <bangnd@24h.com.vn>
 * @param  array  	$maps 				[cấu hình mặc định của template]
 * @param  array  	$new_data          	[mảng các file, text mới]
 * @param  integer 	$mz_content_index   [số thứ tự của nội dung]
 * @return $html_defined_map
 */
function mz_update_magazine_template_with_new_data(array &$maps, array $new_data, $mz_content_index, &$errors) {
	if ( !empty($maps) ) {
		foreach ($maps as $mkind => &$map) { // mkind: auto/defined
			if ( $mkind == 'defined' && !empty($map) ) {
				foreach ($map as $element_type => &$arr_element) { // element_type: image,js,css,title,...
					if (empty($arr_element)) continue;
					foreach ($arr_element as $element_index => &$element_data) {
						mzUpdateMapData($element_data, $new_data, $mz_content_index, $errors);
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

function mzCheckByOparator($left_number, $operator, $right_number) {
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
function mzUploadBase64StringImage($base64string, $v_file_path, $v_old_path, $v_extension) {
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
 * @param  [type] 	$data        [description]
 * @param  integer 	$mz_content_index         [số thứ tự của nội dung]
 * @param  integer 	$p_magazine_id [id magazine]
 * @return array
 */
function mzUploadMagazineFiles($file, $data, $p_magazine_name = '' , array $crop_info = array()) {
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

		$v_upload_info 	= $uploader->info();
		$v_file_name 	= $uploader->getFileName();
		$v_origin_name 	= $uploader->getOriginalFileName(); // ten file luc ban dau
		$v_file_path 	= rtrim($v_upload_path, '/') . '/' . $v_file_name;
		// neu image duoc crop
		if ( !empty( $crop_info ) && $type == 'image' ) { 
			// neu ton tai anh crop duoi dang base64string
			if (!empty($crop_info['image'])) {
				$v_extension = $uploader->getFileExtension();
				// neu khong phai jpg image, thi cap nhat lai mot so thong tin tra ve
				if ($v_extension != 'jpg') {
					// duong dan cua file anh tai len chua crop
					$v_old_path = $v_file_path;
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
				if ( !mzUploadBase64StringImage($crop_info['image'], $v_file_path, $v_old_path, $v_extension) ) {
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
		if ( !mzCheckFileSize($v_file_size, $data, $config) ) {
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

			if ( !mzCheckMediaWidth($v_width, $data, $config) ) {
				unlink($v_file_path);
				return array('error' => true, 'msg' => vsprintf("Chiều rộng %s \'%s\' không hợp lệ", [($type == 'image' ? 'ảnh' : 'video'), $v_origin_name]));
			}
			if ( !mzCheckMediaHeight($v_height, $data, $config) ) {
				unlink($v_file_path);
				return array('error' => true, 'msg' => vsprintf("Chiều cao %s \'%s\' không hợp lệ", [($type == 'image' ? 'ảnh' : 'video'), $v_origin_name]));
			}
		}
		
		return array('error' => false, 'info' => $v_upload_info);

	} catch (\Exception $ex) {
		return array('error' => true, 'msg' => $ex->getMessage());
	}
}

function mzCheckFileSize($file_size, array $data, array $config) {
	// dung luong toi da => "<"
	$operator 	= '<';
	// neu co dinh nghia quy tac ve file_size
	if( !empty( $data['metadata']['file_size'] ) ) {
		// chuyen tu data dang humanreable sang byte, ex: 1KB -> 1024
		$valid_size = mzConvertToByte( $data['metadata']['file_size']['value'] );
	} else {
		$valid_size = $config['max_file_size'];
	}

	if ( !mzCheckByOparator($file_size, $operator, intval($valid_size)) ) {
		return false;
	}
	return true;
}

function mzCheckMediaWidth($image_width, array $data, array $config) {

	if( !empty( $data['metadata']['width'] ) ) {
		$valid_width = $data['metadata']['width']['value'];
		$operator 	 = $data['metadata']['width']['operator'];
	} else {
		$valid_width = $config['max_width'];
		$operator 	 = '<';
	}

	if ( !mzCheckByOparator($image_width, $operator, intval($valid_width)) ) {
		return false;
	}
	return true;
}

function mzCheckMediaHeight($image_height, array $data, array $config) {

	if( !empty( $data['metadata']['height'] ) ) {
		$valid_width = $data['metadata']['height']['value'];
		$operator 	 = $data['metadata']['height']['operator'];
	} else {
		$valid_width = $config['max_height'];
		$operator 	 = '<';
	}

	if ( !mzCheckByOparator($image_height, $operator, intval($valid_width)) ) {
		return false;
	}
	return true;
}

/**
 * lấy ra các file css, js đặt trên <head></head>
 * loai bo cac file trung noi dung
 * @author bangnd <bangnd@24h.com.vn>
 * @param  array  $html_map  [mảng chứa các file của template]
 * @param  array  $p_arr_files [mảng các file css và js lấy ra]
 */
function mz_get_head_files(array $html_map, array &$arr_files) {
    if( !check_array($html_map) )  return;
	mzLoopThroughMap($html_map, function ($element_data) use (&$arr_files) {
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

function mz_minify_head_files(&$p_arr_head_files, $p_magazine_id)
{
	// var_dump($p_arr_head_files['uploaded']['css']);
	if(!check_array($p_arr_head_files) || empty($p_arr_head_files['uploaded'])) return;

	$v_root_path 	= rtrim(ROOT_FOLDER, '/') . '/';
	$css_minifier 	= $js_minifier = null;
	$css_min_path 	= $js_min_path = '';

	if (!empty($p_arr_head_files['uploaded']['css'])) {

		foreach ($p_arr_head_files['uploaded']['css'] as $file_hash => $file) {
		    // cac file da dc upload len server
		    $v_file_path =  $v_root_path . ltrim($file['data'], '/');

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
	// minify css
	if (!is_null($css_minifier)) {
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
	// minify js
	if (!is_null($js_minifier)) {
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
 * hàm thực hiện gom các thông tin cần thiết để cập nhật các nội dung trong magazine
 * @param  array  $v_arr_magazine_content [mảng các nội dung ]
 * @return [type]                         [description]
 */
function mz_gather_magazine_content_data(array $v_arr_magazine_content)
{
	$v_arr_data = array();
	if (check_array($v_arr_magazine_content)) {
		foreach ($v_arr_magazine_content as $mz_content_index => $mzc) {

	        if (empty($mzc['template_id'])) {
	        	continue;
	        }

	        if (intval($mzc['content_id']) > 0 ) {
	        	// nếu content đã được tạo trước đó rồi thì lấy từ html template và map từ magazine content
	        	// thay vì lấy từ template phòng trường hợp template bị xóa
	        	$v_magazine_content = be_get_single_magazine_content(intval($mzc['content_id']));

	        	$v_template_id = $v_magazine_content['fk_magazine_template'];

	        	$v_html_template    = mzt_restore_bad_char($v_magazine_content['c_html_template']);
	        	$v_html_map         = json_decode($v_magazine_content['c_html_map'], true);
	        } else {
	        	// các nội dung tạo mới
	        	$v_template_id  = intval($mzc['template_id']);
	        	$v_template     = be_get_magazine_template($v_template_id);

	        	if (!check_array($v_template)) {
	        	    continue;
	        	}

	        	$v_html_template = mzt_restore_bad_char($v_template['c_html_template']);
	        	$v_html_map = json_decode($v_template['c_html_map'], true);
	        }
	        
	        $v_position     = intval($mzc['position']);
	        
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

function mzPrintMagazineHeadFiles($head_files)
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

/***
* @link: https://gist.github.com/abhineetmittal/d250083def7c356bbf161ff74444ebcc
**/
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