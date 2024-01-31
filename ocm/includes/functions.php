<?php

/**
 * Sử dụng để tạo các đường link phân trang
 * @author DungPT <dungpt@24h.com.vn>
 * @param array $phan_trang Du lieu dc tra ve tu func _db_page
 * @param string $url URL cua trang hien tai
 * @param string $ajax Su dung neu can phan trang ajax, mac dinh la false, $ajax la id cua div tag
 * @return string
 */
function _page_nav($phan_trang, $url, $ajax=false)
{
	if (!preg_match('#\?#', $url)) {
		$url .= '?';
	}
	if (!preg_match('#&page=#', $url)) {
		$url .= '&page=1';
	}
	if (preg_match('#/ajax/#', $url)) {
		$url = str_replace('ajax/', '', $url);
	}
    $url = str_replace('%27', '', $url); // loai bo dau nhay don trong url
	$page = '';
	// prev-page
	if ($phan_trang['prev_page'] != '') {
		if ($ajax) {
			$page .= '<a href="javascript:AjaxAction(\''.$ajax.'\',\''.preg_replace('#&page=[0-9]+#', '&page='.$phan_trang['prev_page'], $url).'\')"> << Trang trước</a>&nbsp;';
		} else {
			$page .= '<a href="'.preg_replace('#page=[0-9]+#', 'page='.$phan_trang['prev_page'], $url).'"> << Trang trước</a>&nbsp;';
		}
	}
	// hien thi link phan trang
	if(check_array($phan_trang['dsp_page'])) {
		if (count($phan_trang['dsp_page']) > 1) {
			foreach($phan_trang['dsp_page'] as $v_page) {
				if ($phan_trang['current_page'] == $v_page) {
					$page .= ' <b>['.$v_page.']</b> ';
				} else {
					if ($ajax) {
						$page .= '<a href="javascript:AjaxAction(\''.$ajax.'\',\''.preg_replace('#&page=[0-9]+#', '&page='.$v_page, $url).'\')">'.$v_page.'</a>&nbsp;';
					} else {
						$page .= '<a href="'.preg_replace('#page=[0-9]+#', 'page='.$v_page, $url).'"> '.$v_page.'</a>&nbsp;';
					}
				}
			}		
		}
	} else {
		$page .= ' <b>[Trang '.$phan_trang['current_page'].']</b> ';
	}	
	// next-page
	if ($phan_trang['next_page'] != '') {
		if ($ajax) {
			$page .= '<a href="javascript:AjaxAction(\''.$ajax.'\',\''.preg_replace('#&page=[0-9]+#', '&page='.$phan_trang['next_page'], $url).'\')"> Trang sau >></a>&nbsp;';
		} else {
			$page .= '<a href="'.preg_replace('#page=[0-9]+#', 'page='.$phan_trang['next_page'], $url).'"> Trang sau >></a>&nbsp;';
		}
	}
	if ($ajax && !preg_match('#/ajax#', $page)) {
		$page = str_replace(BASE_URL2, BASE_URL2.'ajax/', $page);
	}
	return $page;
}

/**
 * Sử dụng để tạo dữ liệu cho phân trang _page_nav
 * @author DungPT <dungpt@24h.com.vn>
 * @param array $rs Mảng dữ liệu đc trả về, gồm 2 chỉ số data & tong_so_dong
 * @param int $page Trang hiện tại đang lấy dữ liệu
 * @param int $number_items Số bản ghi trên 1 page
 * @param int $p_dsp_page hiển thị link phân trang
 * @param int $p_numpage số trang hiển thị trong link phân trang
 * @return array
 */
function _db_page($record_found, $page, $number_items, $p_dsp_page = 0, $p_numpage = 5, $p_offset_page = 2)
{
	$total_pages  = 0;
	if ($p_dsp_page  == 1) {
		// tinh tong so trang		
		$total_pages = ($record_found < $number_items)? $page:$page + $p_offset_page;
		// tao so trang hien thi
		$v_arr_page = array();
		if ($total_pages <= $p_numpage) {
			for ($i = 1; $i <= $p_numpage; $i++) {
				if( $i > $total_pages) {
					break;
				}
				$v_arr_page[] = $i;
			}
		} else {
			if( $page >= $p_numpage-1) {
				$start = $page - floor($p_numpage / 2);
				$start = ( $start>0?$start:1);
			}else{
				$start = 1;
			}
			for( $ii = $start; $ii<$start+$p_numpage; ++$ii) {
				if( $ii > $total_pages) {
					break;
				}
				$v_arr_page[] = $ii;
			}		
		}
		$return['dsp_page'] = $v_arr_page;
	}
	$return['current_page'] = $page;	
	$return['next_page'] = $record_found >= $number_items? (($total_pages == $page)? '':$page+1):'';
	$return['prev_page'] = ($page==1?'':$page-1);
	$return['record_count'] = $record_found;
	return $return;
}

function _vn_format_date($str_date) {
	$date_obj=date_create($str_date);
	return _thu_trong_tuan_english_to_vn(date_format($date_obj, 'l')).', '.date_format($date_obj, 'd-m-Y H:i A');
}

/**
 * Sử dụng để tạo định dạng hiển thị dữ liệu kiểu date, theo các định dạng khác nhau
 * @author phuonghv <phuonghv@24h.com.vn>
 * @param string $p_str_date chuỗi dữ liệu kiểu date
 * @param string $v_format kiểu định dạng hiển thị
 * @return string chuỗi kiểu date
 */
function _dinh_dang_ngay($p_str_date, $v_format = 'd/m/Y H:i:s') {
	$v_arr_invalid_date = array('','0000-00-00','0000-00-00 00:00:00');
	if(!in_array($p_str_date, $v_arr_invalid_date)) {
		$date_object = date_create($p_str_date);
		if (is_object($date_object)) {
			return date_format($date_object, $v_format);			
		}
	}
	return '';
}

function _thu_trong_tuan_english_to_vn($p_str_date) {
	 switch ($p_str_date) {
		case "Sunday":
		case "Sun":
            return "Chủ nhật";
		case "Monday":
		case "Mon":
            return "Thứ hai";
        case "Tuesday":
		case "Tue":
            return "Thứ ba";
        case "Wednesday":
		case "Wed":
            return "Thứ tư";
        case "Thursday":
		case "Thu":
            return "Thứ năm";
		case "Friday":
		case "Fri":
            return "Thứ sáu";
		case "Saturday":
		case "Sat":
            return "Thứ bảy";
    }
	return '';
}

function _sql_format_date($str_date, $str_format ='Y-m-d')
{
	return _dinh_dang_ngay($str_date, $str_format);
}
function _utf8_to_ascii($str) {
	$chars = array(
		'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
		'e' =>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
		'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
		'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
		'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
		'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
		'd'	=>	array('đ','Đ'),
	);
	foreach ( $chars as $key=>$arr) {
		$str = str_replace( $arr, $key, $str);
	}
	return $str;
}
//function check 1 dia chi mail co dung dinh dang
function fw24h_isEmail( $strEmail){
	//pre( $strEmail);
	$strRegular = '/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9_\.\-]+\.';
	$strRegular = $strRegular . '[A-Za-z0-9_\-][A-Za-z0-9_\-]+$/';
	if ( !preg_match( $strRegular, $strEmail)) {
		return false;
	}
	return true;
}

function fw24h_iso_ascii( $string, $ext = '.24h')
{
	// remove all characters that aren"t a-z, 0-9, dash, underscore or space
	$string = strip_tags($string);
	$string = str_replace('&nbsp;', ' ', $string);
	$string = str_replace('&quot;', '', $string);
	$string = str_replace('-', ' ', $string);
	$string = preg_replace( '#[-_]+#', ' ', $string);
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
 * Hiện thị js ra màn hình
 * @author DungPT <dungpt@24h.com.vn>
 * @param string $js_code Nội dung js cần hiện thị
 * @param bool $output Hiện thị trực tiếp hay trả lại giá trị, mặc định là true
 * @return string
 */
function js_set($js_code, $output=true)
{
	$text = "<script language=\"javascript\" type=\"text/javascript\">".$js_code."</script>";
	if ($output) {
		echo $text;
	}
	return $text;
}

/**
 * Gọi file js
 * @author HoangNV <hoangnv@24h.com.vn>
 * @param string $js_url Đường dẫn file js
 * @param bool $output Hiện thị trực tiếp hay trả lại giá trị, mặc định là true
 * @return string
 */
function js_load($js_url, $output=true)
{
	$text = "<script language=\"javascript\" type=\"text/javascript\" src=\"".$js_url."\"></script>";
	if ($output) {
		echo $text;
	}
	return $text;
}

/**
 * Hiện thị thông tin ra màn hình bằng alert func của js
 * @author DungPT <dungpt@24h.com.vn>
 * @param string $message Nội dung cần hiện thị ra màn hình
 */
function js_message($message)
{
	js_set("alert('".$message."')");
}

/**
 * Đặt lại giá trị cho thẻ div theo id
 * @author DungPT <dungpt@24h.com.vn>
 * @param string $div_id ID của div cần gán giá trị
 * @param string $message Nội dung giá trị cần gán cho div
 */
function set_message_for_div($div_id, $message)
{
	echo '<script language="javascript" type="text/javascript">
		if(top.document.getElementById("'.$div_id.'")!=null){
		top.document.getElementById("'.$div_id.'").innerHTML = "'.$message.'";
		top.document.getElementById("'.$div_id.'").style.display = "";
		}
		</script> ';
}

/**
 * Hiện thị thông báo lỗi kiểm tra dữ liệu
 * @author DungPT <dungpt@24h.com.vn>
 * $arr_error lấy từ ds lỗi của Base:validator
 * HTML phải có các div id là err_XXX
 * @param array $arr_error Danh sách các id lỗi
 */
function set_error_message_for_div($arr_error)
{
	foreach ($arr_error as $k=>$v) {
		set_message_for_div('err_'.$k, $v);
	}
}

/**
 * Reset ko hiện thị thông báo lỗi trên HTML
 * $arr_error lấy từ reset_error_message_for_div(Base::get_arr_id_div_error());
 * <code>
 * $list_model = new List_Model();
 * reset_error_message_for_div($list_model->get_arr_id_div_error());
 * </code>
 * @author DungPT <dungpt@24h.com.vn>
 * @param array $arr_error Danh sách các id lỗi
 */
function reset_error_message_for_div($arr_error)
{
	foreach ($arr_error as $k=>$v) {
		set_message_for_div('err_'.$v, '');
	}
}

/**
 * Chuyen den url moi bằng js
 * @author DungPT <dungpt@24h.com.vn>
 * @param string $url url cần chuyển đến
 */
function js_redirect($url)
{
	$url = str_replace('/ajax/', '/', $url);
	echo "<script>top.location.href=\"" .$url. "\"</script>";
	exit();
}

/**
 * Chuyen den url moi bằng header của HTML
 * @author DungPT <dungpt@24h.com.vn>
 * @param string $url url cần chuyển đến
 */
function header_redirect($url)
{
	$url = str_replace('/ajax/', '/', $url);
	header('location: '.$url);
	exit();
}

//'********************************************************************************************************************
//'Ham AddEmptyRow : tra lai xau chua lenh HTML dien them cac dong trang vao mot table
//'********************************************************************************************************************
function _add_empty_row($pCurrentRow,$pTotalRow,$pCurrentStyleName,$pNextStyleName,$pTotalColumn) {
	if($pCurrentRow>=$pTotalRow) {
		return "";
	}
	$strHTML = "";
	$style_name = $pCurrentStyleName;
	for($ii=$pCurrentRow+1;$ii<=$pTotalRow;$ii++) {
		if($style_name == $pCurrentStyleName) {
			$style_name = $pNextStyleName;
		} else {
			$style_name = $pCurrentStyleName;
		}
		$strHTML.='<tr class='.'"'."$style_name".'"'.'>';
		for($jj=1;$jj<=$pTotalColumn;$jj++) {
			$strHTML.="<td>&nbsp;</td>";
		}
		$strHTML.="</tr>";
	}
	return $strHTML;
}

//---Thay doi tong so dong tren mot trang khong su dung xml ---
function _change_record_number_of_page($arr_list_item,$v_value, $extend=" onchange='frm_submit(document.frm_dsp_filter)'") {
	$v_html_string = "";
	//Chuyen doi mang mot chieu->mang hai chieu de dua vao selectbox
	if(sizeof($arr_list_item) > 0){
		$v_index = 0;
		foreach($arr_list_item as $arr_item){
			$arr_list[$v_index][0] = $arr_item['c_code'];
			$arr_list[$v_index][1] = $arr_item['c_name'];
			$v_index++;
		}
	}
	//Tao xau html
	$v_html_string = $v_html_string . "Hiển thị ";
	$v_html_string = $v_html_string . "<select class='dropdown' name='number_per_page' {$extend} >";
	$v_html_string = $v_html_string . _generate_select_option($arr_list,0,0,1,$v_value);
	$v_html_string = $v_html_string . "</select>";
	$v_html_string = $v_html_string . "&nbsp; dòng / 1 trang";
	return $v_html_string;
}


/**
 * Chuc nang: Phan trang tren danh sach
 * @param $totalRows:	Tong so ban ghi
 * @param $currentPage:	Trang hien tai
 * @param $numberPage: So trang tren danh sach
 * @param $numberRowsofPage: So ban ghi tren 1 trang
 * @return Chuoi HTML de sinh ra danh sach trang
 **/
function _pagebreak_selectbox($p_total_record,$p_current_page,$p_number_record_per_page, $extend="onchange='frm_submit(document.frm_dsp_filter)' "){
	$v_html_string = "";
	if($p_total_record % $p_number_record_per_page == 0){
		$v_number_page = (int)($p_total_record/$p_number_record_per_page);
	}else{
		$v_number_page = (int)($p_total_record/$p_number_record_per_page)+1;
	}
	$v_html_string = $v_html_string . "Tổng số " . $v_number_page . "&nbsp;trang.&nbsp;&nbsp;Xem &nbsp;" . "<select class='dropdown' name='page' {$extend}>";
	for ($i=1; $i<=$v_number_page;$i++){
		if ($p_current_page == $i){
			$v_select = " selected ";
		}else{
			$v_select = " ";
		}
		$v_html_string = $v_html_string . "<option id='' value='$i' name='$i' $v_select> " . "Trang &nbsp;" . $i  . "</option>";
		// Chi hien thi toi da 20
		if($i>=_CONST_MAX_NUMBER_PAGE) break;
	}
	$v_html_string = $v_html_string . "</select>";
	return $v_html_string;
}

//***************************************************************************************
//'Muc dich : Sinh ra doan ma HTML the hien cac option cuar mot SelectBox
//'			dua tren mot arr
//'Tham so  :
//			arr_list	: mang du lieu
//			ValueColumn		: Ten cot lay gia tri gan cho moi option
//			DisplayColumn	: Ten cot lay de hien thi cho moi option
//			SelectedValue	: Gia tri duoc lua chon )
//****************************************************************************************
function _generate_select_option($arr_list,$IdColumn,$ValueColumn,$NameColumn,$SelectedValue) {
	$strHTML = "";
	$i=0;
	$count=sizeof($arr_list);
	for($row_index = 0;$row_index< $count;$row_index++){
		$strID=trim($arr_list[$row_index][$IdColumn]);
		$strValue=trim($arr_list[$row_index][$ValueColumn]);
		$gt=$SelectedValue;
		if($strID != $SelectedValue) {
			$optSelected="";
		} else {
			$optSelected="selected";
		}
		$DspColumn=trim($arr_list[$row_index][$NameColumn]);
		$strHTML.='<option id='.'"'.$strID.'"'.' '.'name='.'"'.$DspColumn.'"'.' ';
		$strHTML.='value='.'"'.$strValue.'"'.' '.$optSelected.'>'.$DspColumn.'</option>';
		$i++;
	}
	return $strHTML;
}

/**
* @desc:  Hàm khai báo select box lọc
* @author: ducnq@24h.com.vn @date: 2011/02/11 @desc: create new
* Tham so:
		$p_title : Tiêu đề selectbox
		$p_width : Độ rộng của selectbox nếu có
		$p_name : Tên + ID của selectbox
		$is_event_onchange : nếu = 1 khi chọn giá trị sẽ submit form; nếu = 0 không dùng đến
		$fuseaction : sử dụng nếu $is_event_onchange = 1, submit đến fuseaction này
		$is_value_defaul: Giá trị mặc định
		$arr_value : mảng giá trị cho selectbox
		$p_value_field : Tên trường giá trị của selectbox
		$p_name_field : Tên trường hiển thị của selectbox
		$p_extent: Thuộc tính mở rộng
		$p_value_selected : Giá trị được chọn
*/
function _gen_selectbox_filter($p_name, $p_width, $is_event_onchange, $is_value_defaul, $arr_value, $p_value_field, $p_name_field, $p_extent, $p_value_selected){
	$v_selectbox = '
	<select class="dropdown" ';
	if($p_width!=''){
		$v_selectbox .='style="width:'.$p_width.'%"';
	}
	$v_selectbox .=' name="'.$p_name.'" id="'.$p_name.'"';
	if($is_event_onchange==1){
		$v_selectbox .= ' onChange="frm_submit(document.frm_dsp_all_item)" ';
	}
	$v_selectbox .= $p_extent.' >';
	if($is_value_defaul==1){
		$v_selectbox .= '<option value="">--Chọn--</option>';
	}
	for($i=0;$i<sizeof($arr_value);$i++){
		$v_selectbox .= '<option value="'.$arr_value[$i][$p_value_field].'"';
		if(''.$arr_value[$i][$p_value_field].'' == ''.$p_value_selected.''){
			$v_selectbox .= ' selected ';
		}
		$v_selectbox .='>'.$arr_value[$i][$p_name_field].'</option>';
	}

	$v_selectbox .= '</select>';
	return	$v_selectbox;
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

function _get_config($p_config)
{
    include WEB_ROOT.'includes/app_configs.php';
	$v_conf = ${$p_config};
	return $v_conf;
}

function _get_module_config($p_module_name, $p_config)
{
	include WEB_ROOT.'includes/module_configs/'.$p_module_name.'.conf.php';
	$v_conf = ${$p_config};
	return $v_conf;
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
/**
* Ham  them cot ten doi tuong khong dau vao 1 doi tuong
* param $p_data array: mang du lieu nguon
        $p_column_name string : Ten cot can chuyen dang chu khong dau
*/
function add_ascii_column($p_data, $p_column_name) {
    $rs_return = array();
    if(check_array($p_data)){
        foreach($p_data as $v_row) {
            $v_row[$p_column_name.'_ascii'] = strtolower(_utf8_to_ascii($v_row[$p_column_name]));
            $rs_return[] = $v_row;
        }
    }
    return $rs_return;
}

/**
 * tao string js suggestion
 * @author  ducnq
 * @param  array @arr mang gia tri gan gen  suggestion
 * @param  string @file_name ten mang
 * @param  string @c_column_id Ten cot id
 * @param  string @c_column_ascii cot ten khong dau
 * @param  string @c_column_name cot ten
 * @return  string
 */
function _tao_file_js_suggestion($arr,$file_name,$c_column_id,$c_column_ascii,$c_column_name) {
	$arr_source = array('"',"'",'&nbsp;','&#45;','&#40;','&#41;');
	$arr_repalce = array('','','','-','(',')');
	$v_category_string = '[';
	for($i=0;$i<sizeof($arr);$i++){
		$v_temp = '{';
		if($c_column_id!=null && $c_column_id!=''){
			$v_temp =$v_temp.'id:"'.str_replace($arr_source,$arr_repalce,$arr[$i][$c_column_id]).'"';
		}
		if($c_column_ascii!=null && $c_column_ascii!=''){
			$v_temp =$v_temp.',ascii_name:"'.str_replace($arr_source,$arr_repalce,$arr[$i][$c_column_ascii]).'"';
		}
		if($c_column_name!=null && $c_column_name!=''){
			$v_temp =$v_temp.',name:"'.str_replace($arr_source,$arr_repalce,$arr[$i][$c_column_name]).'"';
		}
		$v_temp =$v_temp.'}';
		if($i==0){
			$v_category_string .= $v_temp;
			}else{
			$v_category_string .=",".$v_temp;
		}
	}
	$v_category_string .= ']';
	return ("var ".$file_name." =".$v_category_string.";");
}

function quarterByDate($date)
{
	$date = (int)$date;
	if ($date < 4) {
		return 1;
	}
	if ($date < 7) {
		return 2;
	}
	if ($date < 10) {
		return 3;
	}
	if ($date < 13) {
		return 4;
	}
}
/*
* function chmode 1 thư mục, file
* phuonghv add 31/03/2015
* @param $p_path đường dẫn file hoặc thư mục
* @return array
*/
function _chmode_777($p_path) {
	return;
	// @chmod($path, 0777);
}
/*
* Ham xử lý ký tự đặc biệt ở tên file
* phuonghv add 31/03/2015
* @param $p_file_name tên file cần kiểm tra
* @param $p_extension_allow Phần mở rộng của file hợp lệ
* @return boolean
*/
function _xu_ly_ten_file($p_file_name){
	$v_file = strtolower(preg_replace('#[^\-a-zA-Z0-9_\.]#', '-', $p_file_name));
	return $v_file;
}

/*
* Ham kiem tra phan mo rong cua file
* phuonghv add 31/03/2015
* @param $p_file_name tên file cần kiểm tra
* @param $p_extension_allow Phần mở rộng của file hợp lệ
* @return boolean
*/
function _valid_file_extension($p_file_name, $p_extension_allow=''){
	$v_file = _xu_ly_ten_file($p_file_name);
	//Tách phần tên file và phần mở rộng
	$v_vi_tri_dau_cham_dau_tien = strpos($v_file, ".");
	$v_file_name = substr($v_file,0,$v_vi_tri_dau_cham_dau_tien);

	if($v_file_name=='') return false;

	$v_file_extenstion = substr($v_file,$v_vi_tri_dau_cham_dau_tien,strlen($v_file));
	$v_arr_ext = explode('.',$v_file_extenstion);
	$p_extension_allow = ($p_extension_allow=='')? BANNER_EXTENSION_ALLOW.','.IMAGE_EXTENSION_ALLOW.','.VIDEO_EXTENSION_ALLOW : $p_extension_allow;
	$v_arr_ext_valid = explode(',',strtolower($p_extension_allow));
	if(is_array($v_arr_ext) && is_array($v_arr_ext_valid)) {
		$v_arr_ext_valid = array_unique($v_arr_ext_valid);
		foreach($v_arr_ext as $v_ext) {
			if($v_ext!=''&&!in_array($v_ext, $v_arr_ext_valid)) {
				return false;
			}
		}
		return true;
	}
	return false;
}
/*
 *@desc: Hàm hàm check dung lượng ảnh đại diện dạng gif
 * @author: anhpt1 25-07-2016
 * @param:  string $v_name_file_image Tên ảnh đại diện
 * @return boolean
 */
function lay_dung_luong_anh_dai_dien_bai_viet_dang_gif($v_name_file_image){
    $v_size_anh_dai_dien_news = MAX_SUMMARY_IMAGE_SIZE;
    $v_arr_name_file = explode('.', $v_name_file_image);
    // Nếu ảnh đại diện là dạng gif thì size giới hạn 200kb
    if ($v_arr_name_file[count($v_arr_name_file) -1] == 'gif') {
        $v_max_size_anh_dai_dien_bai_dang_gif = _get_module_config('news','v_max_size_anh_dai_dien_bai_dang_gif');
        $v_size_anh_dai_dien_news = $v_max_size_anh_dai_dien_bai_dang_gif;
    }
    return $v_size_anh_dai_dien_news;
}
/*
 *@desc: Hàm hàm check dung lượng ảnh đại diện dạng gif
 * @author: anhpt1 25-07-2016
 * @param:  string $v_name_file_image Tên ảnh đại diện
 * @return boolean
 */
function check_anh_dang_gif_theo_duong_dan($p_name_file_image){
    // nếu khong có đường dẫn ảnh thì return
    if($p_name_file_image == '' || strpos($p_name_file_image,'.') === false){
        return false;
    }
    $v_arr_name_file = explode('.', $p_name_file_image);
    // Nếu ảnh đại diện là dạng gif thì size giới hạn 200kb
    if ($v_arr_name_file[count($v_arr_name_file) -1] == 'gif') {
        return true;
    }
    return false;
}
/*
 *@desc: chuyển ảnh gif sang jpg
 * @author: anhpt1 25-07-2016
 * @param:  string
 * @return boolean
 */
function convert_image_gif_to_jpg($v_url_imge){
    if($v_url_imge == ''){return '';}
    $v_file_path = ROOT_FOLDER.$v_url_imge;
    // Đổi đuôi ảnh
    $v_file_path_news = str_replace('.gif', '.jpg', $v_file_path);
    // Lấy thông tin ảnh
    $v_image_tmp = imagecreatefromgif($v_file_path);
    // Lưu lại ảnh
    imagejpeg($v_image_tmp, $v_file_path_news);
    // Destroy ảnh
    imagedestroy($v_image_tmp);
    // trả về đường dần gần đúng
    return str_replace(ROOT_FOLDER,'', $v_file_path_news);
}
/**
 * Trả về kiểu số int cho $value, nếu số nhỏ hơn 1 thì trả về là 1
 * Sử dụng trong trường hợp kiểm tra dữ liệu xem trang hiện tại
 * <code>
 * var $_arr_arg = array (
 *  'sel_order_by' => array('', 'strval')
 *  ,'page' => array(1, 'page_val')
 *  ,'number_per_page' => array(4, 'intval')
 * );
 * </code>
 * @example modules/blocks/list/list.php
 * @author DungPT <dungpt@24h.com.vn>
 * @param int $value
 * @param int $default_val dữ liệu mặc định nếu $value < 1,
 */
function page_val($value, $default_val=1)
{
	$value = intval($value);
	if ($value < 1) {
		$value = $default_val;
	}
	return $value;
}

/**
 * json_encode — Returns the JSON representation of a value
 */
if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
			return 'null';
            case 'boolean':
			return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
			return $data;
            case 'string':
			return '"' . str_replace("/", "\\/", str_replace("\\'", "'", addslashes($data) )) . '"';
            case 'object':
			$data = get_object_vars($data);
            case 'array':
			$output_index_count = 0;
			$output_indexed = array();
			$output_associative = array();
			foreach ($data as $key => $value) {
				$output_indexed[] = json_encode($value);
				$output_associative[] = json_encode(strval($key)) . ':' . json_encode($value);
				if ($output_index_count !== NULL && $output_index_count++ !== $key) {
					$output_index_count = NULL;
				}
			}
			if ($output_index_count !== NULL) {
				return '[' . implode(',', $output_indexed) . ']';
			} else {
				return '{' . implode(',', $output_associative) . '}';
			}
            default:
			return ''; // Not supported
        }
    }
}
/**
 * lấy mảng chiển dịch đặc biệt
 * @return mảng chiến dịch
 */
function get_arr_campaign(){
    $v_arr_campain = array();
    return $v_arr_campain;
}
/*
 * Thay the cac ky tw dac biet trong file xml
 * params :
 	$str : chuỗi cần thay thế kí tự đặc biệt
 * returms : string
*/
function _replace_xml_special_char($str) {
    $str = fw24h_restore_bad_char($str);
    $v_arr_replace = array(
        '/&nbsp;/'    => ' ',
    );
    return preg_replace(array_keys($v_arr_replace), array_values($v_arr_replace), $str);
}
/**
* This function takes a css-string and compresses it, removing
* unneccessary whitespace, colons, removing unneccessary px/em
* declarations etc.
*
* @param string $css
* @return string compressed css content
* @author Steffen Becker
*/
function minifyCss24h($css) {
    // some of the following functions to minimize the css-output are directly taken
    // from the awesome CSS JS Booster: https://github.com/Schepp/CSS-JS-Booster
    // all credits to Christian Schaefer: http://twitter.com/derSchepp
    // remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // backup values within single or double quotes
    preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
    }
    // remove traling semicolon of selector's last property
    $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
    // remove any whitespace between semicolon and property-name
    $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
    // remove any whitespace surrounding property-colon
    $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
    // remove any whitespace surrounding selector-comma
    $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
    // remove any whitespace surrounding opening parenthesis
    $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
    // remove any whitespace between numbers and units
    $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
    // shorten zero-values
    $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
    // constrain multiple whitespaces
    $css = preg_replace('/\p{Zs}+/ims',' ', $css);
    // remove newlines
    $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
    // Restore backupped values within single or double quotes
    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
    }
    return $css;
}
/*
* Hàm minify chuỗi JS truyền vào
* $p_string_js : chuỗi js truyền vào
* return : String
*/
function minifyjs24h($p_string_js) {
   $url = 'https://javascript-minifier.com/raw';
   $postdata = array('http' => array(
       'method'  => 'POST',
       'header'  => 'Content-type: application/x-www-form-urlencoded',
       'content' => http_build_query( array('input' => $p_string_js) ) ) );
   $minify = file_get_contents($url, false, stream_context_create($postdata));
   return $minify;
}