<?php

/* SVN FILE: $Id: html_functions.php 2055 2011-11-24 01:50:00Z dungpt $ */
/**
 *
 * @author $Author: dungpt $
 * @version $Revision: 2055 $
 * @lastrevision $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 * @modifiedby $LastChangedBy: dungpt $
 * @lastmodified $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
 * @filesource $URL: http://svn.24h.com.vn/svn_24h/services-tier/includes/html_functions.php $
 */

/**
 * Dat gia tri cho Title tag
 * @param string $title
 */
function html_set_title ($title)
{
    $GLOBALS['__PAGE_TITLE__'] = $title;
}

/**
 * Lay gia tri da dat cho Title tag
 * @return string
 */
function html_get_title ()
{
    $title = isset($GLOBALS['__PAGE_TITLE__']) ? $GLOBALS['__PAGE_TITLE__'] : DEFAULT_PAGE_TITLE;
    return $title;
}

/**
 * Lay gia tri da dat cho keyword su dung cho keyword tag
 * @return string
 */
function html_get_keywords ()
{
    return isset($GLOBALS['__PAGE_KEYWORDS__']) ? $GLOBALS['__PAGE_KEYWORDS__'] : '';
}

/**
 * @return Ambigous <string, unknown>
 */
function html_get_description ()
{
    return isset($GLOBALS['__PAGE_DESCRIPTION__']) ? $GLOBALS['__PAGE_DESCRIPTION__'] : '';
}

/**
 * @param unknown_type $des
 */
function html_set_description ($des)
{
    $GLOBALS['__PAGE_DESCRIPTION__'] = $des;
}

/**
 * @param unknown_type $keywords
 */
function html_set_keywords ($keywords)
{
    $GLOBALS['__PAGE_KEYWORDS__'] = $keywords;
}

/**
 * @param unknown_type $canonical
 */
function html_set_canonical ($canonical)
{
    $canonical = str_replace(BASE_URL_FOR_PUBLIC, 'http://nhac.vui.vn/',  $canonical);
    $GLOBALS['__PAGE_CANONICAL__'] = $canonical;
}

/**
 * @return Ambigous <string, unknown>
 */
function html_get_canonical ()
{
    return isset($GLOBALS['__PAGE_CANONICAL__']) ? $GLOBALS['__PAGE_CANONICAL__'] : '';
}

/**
 * @param unknown_type $robots
 */
function html_set_robots ($robots)
{
    $GLOBALS['__PAGE_ROBOTS__'] = $robots;
}

/**
 * @return Ambigous <string, unknown>
 */
function html_get_robots ()
{
    return isset($GLOBALS['__PAGE_ROBOTS__']) ? $GLOBALS['__PAGE_ROBOTS__'] : 'index,follow,noodp';
}

/**
 * @param unknown_type $rss
 */
function html_set_rss ($rss)
{
    $GLOBALS['__PAGE_RSS__'] = $rss;
}

/**
 * @return Ambigous <string, unknown>
 */
function html_get_rss ()
{
    return isset($GLOBALS['__PAGE_RSS__']) ? $GLOBALS['__PAGE_RSS__'] : '';
}

/**
 * @return string
 */
function html_get_base_url ()
{
    return BASE_URL;
}

function html_get_image_url($image)
{	
	if (!$image) return DEFAULT_IMG;
	$image = IMAGE_URL.preg_replace('#^/#', '', $image);
    $image = str_replace('/../','/',$image);
	return $image;
}

function html_quote_decode($p_string, $p_both=false)
{
    if ($p_both) {
        return str_replace(array('"', "'"), array('&quot;', '&#039;'), $p_string);
    } else {
        return str_replace('"', '&quot;', $p_string);
    }
}

function html_get_css_url($css)
{
    return IMAGE_URL.preg_replace('#^/#', '', $css);
}

function get_news_thumb ($image)
{
	return $image;
}

function html_select_box($name, $data, $option_value, $option_label, $default_value='', $extend='', $add_option=0)
{
	if (!is_array($data)) {
		$data = array();
	}
	
	$text = '<select name="'.$name.'" id="'.$name.'" '.$extend.'>';
	if ($add_option) {
		array_unshift($data, array($option_value=>'', $option_label=>'-- Chọn --'));
	}
	foreach($data as $value) {
		$selected = '';
		if ((string)$value[$option_value] == $default_value) {
			$selected = ' selected ';
		}
		//Begin 24-11-2016 : Thangnb fix_loi_bao_mat
		$text .= '<option value="'.fw24h_replace_bad_char($value[$option_value]).'" '.$selected.'>'.fw24h_replace_bad_char(strip_tags($value[$option_label])).'</option>';
		//End 24-11-2016 : Thangnb fix_loi_bao_mat
	}
	$text .= '</select>';
	return $text;
}

function html_link($link, $echo=true)
{
	$url = BASE_URL.$link;
	if ($echo) {
		echo $url;
	}
	return $url;
}

function html_image_upload($image, $echo=true)
{
    if (preg_match('#^http://#', $image)) {
        $url = $image;
    } else {
        $url = BASE_DOMAIN.$image;
    }
	if ($echo) {
		echo $url;
	}
	return $url;
}
function html_image($image, $echo=true)
{
    if (preg_match('#^http://#', $image)) {
        $url = $image;
    } else {
        $url = BASE_URL.$image;
    }
	if ($echo) {
		echo $url;
	}
	return $url;
}

function html_css($css, $echo=true)
{
	$url = BASE_URL.$css;
	if ($echo) {
		echo $url;
	}
	return $url;
}

function html_js($js, $echo=true)
{
	$url = BASE_URL.$js;
	if ($echo) {
		echo $url;
	}
	return $url;
}

function html_no_data($v_count)
{
	if ($v_count == 0) {
		echo "<br><div>
               <span style='color:red;font-weight:bold'> KHÔNG CÓ DỮ LIỆU </span>
               <br>
                Ðề xuất:
                <br>
                <p style='padding-left:15px;'> - Hãy thử tìm kiếm khoảng thời gian dài hơn.</p>
                <p style='padding-left:15px;'> - Hãy thử những tiêu thức tìm kiếm khác.</p>
                <p style='padding-left:15px;'>- Hãy thử bớt tiêu thức tìm kiếm.</p>
            <br>
        </div>
        <br>";
	}
}
function html_get_all_category_by_one_news_new($p_arr_cat, $p_list_status_public, $p_delimiter='<br/>',$p_arr_url =array()){
    $v_arr_cat_name = array();
    $v_main_category = intval($p_arr_url['fk_main_category']);
	if (check_array($p_arr_cat)) {
		foreach ($p_arr_cat as $row) {
			$v_cat_name = $row['Name'];
            $v_main_cate = false;
            $v_arr_cat_name[] = $v_cat_name;
		}
	}
	return implode($p_delimiter, $v_arr_cat_name);
}

function html_get_all_category_by_one_news($p_arr_cat, $p_list_status_public, $p_delimiter='<br/>',$p_arr_url =array())
{
	$v_arr_cat_name = array();
    $v_main_category = intval($p_arr_url['fk_main_category']);
	if (check_array($p_arr_cat)) {
		foreach ($p_arr_cat as $row) {
			$v_cat_name = $row['Name'];
            $v_main_cate = false;
            if(check_array($p_arr_url) && intval($row['CategoryID']) > 0 && $v_main_category == intval($row['CategoryID'])){
                $v_cat_name = '<span class="redBoldText">'.$v_cat_name.'</span>';
                $v_main_cate =true;
            }
			if (check_array($p_list_status_public) && $row['pending_status']>0 && $row['Status'] == 0) {
				$v_cat_name .= '<i>('.get_name_in_array($p_list_status_public, 'c_code', 'c_name', $row['pending_status']).')</i>';
			}
            // nếu là chuyên mục chính thì đưa dữ liệu lên mảng đầu tien
            if($v_main_cate){
                array_unshift($v_arr_cat_name,$v_cat_name);
                continue;
            }
            $v_arr_cat_name[] = $v_cat_name;
		}
	}
	return implode($p_delimiter, $v_arr_cat_name);
}

function html_get_all_category_khampha_by_one_news($p_arr_cat, $p_list_status_public, $p_delimiter='<br/>')
{
	$v_arr_cat_name = array();	
	if (check_array($p_arr_cat)) {
		foreach ($p_arr_cat as $row) {
			$v_cat_name = $row['Name'];
			$v_arr_cat_name[] = $v_cat_name;
		}
	}
	
	return implode($p_delimiter, $v_arr_cat_name);
}

function html_hen_gio_xuat_ban_info($p_arr_cat)
{
	$v_html = '';
	if (is_array($p_arr_cat) && count($p_arr_cat) > 0) {
		$row = $p_arr_cat[0];
		if ($row['pending_status'] > 0 && $row['Status'] == 0) {
			$v_html = '<br/><span class="redBoldText" style="font-size:12px">&gt;&gt; Hẹn giờ XB lúc: '.date_format(date_create($row['pending_date']), 'd-m-Y H:i:s').'</span><input type="hidden" name="hdn_pending_date" value="'.$row['pending_date'].'"/>';
		}
	}
	return $v_html;
}

function html_get_all_xuat_ban_them_by_one_news($p_news_id)
{
	$rs_xuat_ban_them = be_get_all_xuat_ban_them_by_one_news($p_news_id);
	$rs_xuat_ban_them_typeext = get_list_xuat_ban_them_typeext();
	$v_arr_temp = array();
	if (is_array($rs_xuat_ban_them) && count($rs_xuat_ban_them) > 0) {
        // begin: tytv - 30/3/2018 - toi_uu_co_che_bai_video_trang_video_tong_hop
        foreach ($rs_xuat_ban_them as $row) {
			$v_arr_temp[$row['TypeExt']] .= $row['Name'].',';
		}
        $v_str_xuat_ban_them = '';$i= 0;
        foreach ($v_arr_temp as $key => $value) {
            $v_str_xuat_ban_them .= (($i!=0)?';':'').get_name_of_xuat_ban_them_typeext($key,$rs_xuat_ban_them_typeext).'('.trim($value,',').')';
            $i++;
		}
		$v_xuat_ban_them_short = cutBrief($v_str_xuat_ban_them, 75);
        // end: tytv - 30/3/2018 - toi_uu_co_che_bai_video_trang_video_tong_hop
		$v_xuat_ban_them = '<br>';
		//$v_xuat_ban_them .= '<span onmouseout="tinytip.hide();" onmouseover="tinytip.show(\''.implode('; ', $v_arr_temp).'\')">';
		$v_xuat_ban_them .= '<b>Box xb thêm:</b> '.$v_xuat_ban_them_short;
		//$v_xuat_ban_them .= '</span>';
	}
	return  $v_xuat_ban_them;
}

function html_get_all_special_box_by_one_news($p_news_id)
{
    // 30-12-2020 DanNC begin bo sung loc loai bai mobile
    global $v_device_global;
    // 30-12-2020 DanNC end bo sung loc loai bai mobile
	$rs_special_box = be_get_all_special_box_by_one_news($p_news_id);	
	$v_arr_temp = array();
	if (is_array($rs_special_box) && count($rs_special_box) > 0) {
		foreach ($rs_special_box as $row) {
			$v_arr_temp[] = strip_tags($row['c_name']);
		}
        // 30-12-2020 DanNC begin bo sung loc loai bai mobile
        if($v_device_global == 'mobile') {
            $v_special_box = '<b>Box đề cử:</b> '.implode(', ', $v_arr_temp);
        } else {
            $v_special_box = '<br/><b>Box đề cử:</b> '.implode(', ', $v_arr_temp);
        }
        // 30-12-2020 DanNC end bo sung loc loai bai mobile
	}
	return  $v_special_box;
}
/**
* Ham lay danh sach ten chuyen muc xuat ban cua 1 menu ngang
* @param int $p_menu_id
* return string
*/
function html_get_all_cat_published_by_menu($p_menu_id, $p_array_cat_search)
{
    $rs_cat = be_get_all_menu_ngang_published($p_menu_id);
	$v_category_name_list = '';
	if (check_array($rs_cat )) {
		// lay danh sach chuyen muc
		$v_arr_category =  be_get_all_category_by_select(-1, -1,-1);
		$v_arr_category_id = get_sub_array_by_key($rs_cat,'categoryID');	
		$v_category_name_list = html_get_category_list($v_arr_category_id,';', $v_arr_category);			
	}
	return  $v_category_name_list;
}
/**
* Hien thi danh sach ten chuyen muc theo ID
* @param array p_array_category : mang id chuyen muc
* @param array $p_array_all_category :  mang chua tat ca cac chuyen muc
* return string
*/
function html_get_category_list($p_array_category, $p_separation=', ', $p_array_all_category =array())
{
    $v_arr_temp = array(); 
	$v_html_return = '';
	if (check_array($p_array_category )) {
		// lay danh sach chuyen muc
		$v_arr_category = $p_array_all_category;
		if (!check_array($p_array_all_category)) {
			$v_arr_category =  be_get_all_category_by_select(-1, -1, 1);
		} 
		$v_arr_category_temp = array();
		// lay them thong tin chi tiet cho cac chuyen muc duoc chon
		foreach ($p_array_category as $row) {
           	$v_category = get_sub_array_in_array($v_arr_category['data'], 'ID', $row, false);			
			if (check_array($v_category)) {
				$v_arr_category_temp[] = $v_category[0];				
			}
		}
		
		if (check_array($v_arr_category_temp)) {
			// sap xep lai mang theo thu tu chuyen muc cap1, chuyen muc cap 2 		
			$v_arr_category_temp = php_multisort($v_arr_category_temp, array(array('key'=>'parent_position', 'sort'=>'asc'), array('key'=>'Parent', 'sort'=>'asc'), array('key'=>'Position', 'sort'=>'asc')));
			foreach($v_arr_category_temp as $v_category) {
				$v_name = ($v_category['Parent'] == 0) ? '<b>'.$v_category['Name'].'</b>' : $v_category['Name'];
				$v_name = str_replace('&nbsp;&nbsp;&nbsp;&nbsp;', '', $v_name);
				$v_arr_temp[] = $v_name;	
			}
		}
		$v_html_return = implode($p_separation, $v_arr_temp) ;
    }
	return  $v_html_return;
}

/**
* Sử dụng để tạo các box lọc tìm cho form ( tìm kiếm, cập nhật ). Hàm này đi cùng với hàm js chon_nhanh(id_textbox, id_selectbox);
* @author cuongnx <cuongnx@24h.com.vn>
* @param unknown $p_gia_tri_key Giá trị hiện tại của box tìm kiếm
* @param array $p_arr_danh_sach Mảng danh sách các giá trị có thể tìm kiếm
* @param string $p_ten_key Tên cột key ở mảng danh sách
* @param string $p_ten_value Tên cột giá trị ở mảng danh sách (giá trị hiển thị + tìm kiếm)
* @param string $p_ten_value2 Tên cột giá trị ở mảng danh sách (giá trị tìm kiếm)
* @param string $p_ten_textbox ID/name của textbox (tên tham số url lấy gía trị)
* @param string $p_ten_selectbox ID/name của selectbox (tên tham số url lấy gía trị)
* @param string $p_thong_bao thuộc tính title của html element,giá trị của textbox
* @param string $p_div_script CSS/javascript của div element
* @param string $p_textbox_script CSS/javascript của textbox element
* @param string $p_selectbox_script CSS/javascript của selectbox element
* @return string
*/
function html_selectbox_loc_tim($p_gia_tri_key, $p_arr_danh_sach, $p_ten_key, $p_ten_value, $p_ten_value2, $p_ten_textbox, $p_ten_selectbox, $p_thong_bao,$p_div_script,$p_textbox_script,$p_selectbox_script, $v_column_extent = '',$v_script_keydown='') {

	$v_html ='';
	
	$size_of_arr = sizeof($p_arr_danh_sach);
	
	$v_html .='<div '.$p_div_script.'>
		<input type="text" name="'.$p_ten_textbox.'" id="'.$p_ten_textbox.'" value="'.$p_thong_bao.'" '.$p_textbox_script.' onkeydown="goi_ham_tim_kiem_select_box(\''.$p_ten_textbox.'\',\''.$p_ten_selectbox.'\',event);'.$v_script_keydown.'"	'.html_script_onfocus($p_thong_bao).' >
	</div>';
	
	$v_html .='<select  title="'.$p_thong_bao.'" id="'.$p_ten_selectbox.'" name="'.$p_ten_selectbox.'" '.$p_selectbox_script.' >';
	for($i=0,$s= sizeof($p_arr_danh_sach);$i<$s;$i++){ 
		$v_gia_tri_tim_kiem2='';
		
		if($p_ten_value2 !=''){
			$v_gia_tri_tim_kiem2 =$p_arr_danh_sach[$i][$p_ten_value2];
		} else {
			$v_gia_tri_tim_kiem2 =_utf8_to_ascii($p_arr_danh_sach[$i][$p_ten_value]);
		}
		
		$selected ='';
		if($p_arr_danh_sach[$i][$p_ten_key] == $p_gia_tri_key){
			$selected ='selected';
		}
		
		$v_html .='<option class="sel_option_select" value="'.$p_arr_danh_sach[$i][$p_ten_key].'" title="'.$p_arr_danh_sach[$i][$p_ten_value].' '.$v_gia_tri_tim_kiem2.'" '.$selected;
		if ($v_column_extent!='') {
			$v_html .= ' '.$v_column_extent.'='.$p_arr_danh_sach[$i][$v_column_extent];
		}
		$v_html .=' >';
		
		$v_html .=$p_arr_danh_sach[$i][$p_ten_value].'</option>';
	}
	$v_html .='</select>';
				
	return $v_html;
	
}
/**
 * gen multi radio button
 * @param string @name tên củaa select box 
 * @param  string @data dữ liệu cần hiển thi
 * @param  string @option_value Cột chứa giá trị của option
 * @param  string @option_label Cột chứa nội dung hiển thị của option
 * @param  string @default_value giá trị hiển thị mặc định
 * @param  string @extend Bổ sung thêm các thuộc tính cho thẻ <select>
 * @param  boolean @add_option Bổ sung thêm option chọn
 * @return  string
 */ 
function html_multi_radio_box($name, $data, $option_value, $option_label, $default_value='', $extend='', $add_option=0)
{	
	// Kiem tra mang
	if (!is_array($data)) {
		$data = array();
	}		
	// Bo xung checkbox chon
	if ($add_option) {
		array_unshift($data, array($option_value=>'', $option_label=>'-- Chọn --'));
	}
	// gen multi radio button group
	// radio button duoc chon se cho hien thi len vi tri dau tien trong danh sach	
	$html_control = '';
    $i = 0;
	foreach ($data as $row) {
		$v_checked = '';
		if ($row[$option_value] == $default_value) {							
			$v_checked = 'checked = "checked" ';
		}
        $v_label = ($row['Parent'] == 0) ? '<b>'.$row[$option_label].'</b>' : $row[$option_label];
        $html_control .= '<input type="radio" '.$v_checked.' name = "'.$name.'" id = "'.$name.$i.'" value="'.$row[$option_value].'" '.$extend.'/>&nbsp;';				
        $html_control .= '<label for="'.$name.$i.'">'.$v_label.'</label><br/>';
        $i++;
	} 
	return $html_control;
}
function html_multi_radio_box_su_kien_dac_biet($name, $data, $option_value, $option_label, $default_value='', $extend='', $add_option=0)
{	
	// Kiem tra mang
	if (!is_array($data)) {
		$data = array();
	}		
	// Bo xung checkbox chon
	if ($add_option) {
		array_unshift($data, array($option_value=>'', $option_label=>'-- Chọn --'));
	}
	// gen multi radio button group
	// radio button duoc chon se cho hien thi len vi tri dau tien trong danh sach	
	$html_control = '';
    $i = 0;
	foreach ($data as $row) {
		$v_checked = '';
		if ($row[$option_value] == $default_value) {							
			$v_checked = 'checked = "checked" ';
		}
        $v_label = ($row['Parent'] == 0) ? '<b>'.$row[$option_label].'</b>' : $row[$option_label];
        $html_control .= '<input type="radio" '.$v_checked.'onclick="toggle_chuyen_muc_su_kien_dac_biet(\''.$row[$option_value].'\')" name = "'.$name.'" id = "'.$name.$i.'" value="'.$row[$option_value].'" '.$extend.'/>&nbsp;';				
        $html_control .= '<label for="'.$name.$i.'">'.$v_label.'</label><br/>';
        $i++;
	} 
	return $html_control;
}

/**
* Sử dụng để tạo javascript onfocus cho các textbox
* @author cuongnx <cuongnx@24h.com.vn>
* @param string $v_thong_bao Giá trị hiển thị/thay thế khi click vào textbox
* @return string
*/
function html_script_onfocus($v_thong_bao){
	$v_script_thong_bao = ' onfocus="if (this.value==\''.$v_thong_bao.'\') this.value=\'\';" onblur="if (this.value==\'\') this.value=\''.$v_thong_bao.'\';"';
	return $v_script_thong_bao;
}

function show_image($p_image, $p_width, $p_height, $p_style='')
{
    if ($p_image == '') {
        return '';
    }
	if (!(preg_match('#^\/#', $p_image)) && !preg_match('#http#', $p_image)) {
		$p_image = '/'.$p_image;
	} 
	if($p_image) {
		if (strpos($p_image,".swf") > 0) {
			return '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$p_width.'" height="'.$p_height.'">
			  <param name="movie" value="'.$p_image.'">
			  <param name="quality" value="high">
			  <param name="wmode" value="transparent">
			  <embed src="'.$p_image.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" width="'.$p_width.'" height="'.$p_height.'"></embed>
			</object>';
		} elseif (strpos($p_image,".flv")>0) {
			return '<script type="text/javascript">flashWrite(\'file='.$p_image.'\')</script>';
		} elseif (strpos($p_image,".htm")>0 || strpos($p_image,".php")>0) {
			if (!preg_match('#http#', $p_image)) {
				$p_image = BASE_DOMAIN.$p_image;
			}
			return '<iframe width="'.$p_width.'" height="'.$p_height.'" src="'.$p_image.'" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" '.$p_style.'></iframe>';
		} else {
			return '<img border="0" src="'.$p_image.'" width="'.$p_width.'" height="'.$p_height.'" '.$p_style.' />';
		}
	}	
}


/**
* Hien thi danh sach ten box tin theo ID
* @param array p_array_box_id : mang id box tin
* @param array $p_array_all_box :  mang chua tat ca cac box tin
* return string
*/
function html_get_box_list($p_array_box_id, $p_separation=', ', $p_array_all_box =array())
{
    $v_arr_temp = array();
	$v_html_return = '';
	if (check_array($p_array_box_id )) {
		// lay danh sach chuyen muc
		$v_arr_all_box = $p_array_all_box;
		if (!check_array($v_arr_all_box)) {
			$v_arr_all_box =  be_get_all_seo_box();
		}		
		foreach ($p_array_box_id as $row) {
           	$v_box_tin = get_sub_array_in_array($v_arr_all_box, 'pk_box_campaign', $row, false);		
			if (check_array($v_box_tin)) {
				$v_name = $v_box_tin[0]['c_ten_box'];
				$v_arr_temp[] = $v_name;
			}
		}
		$v_html_return = implode($p_separation, $v_arr_temp);
    }
	return  $v_html_return;
}

/*
	Ham kiem tra tu dong them tien to http:// neu link chua co tien to nay
*/
function html_add_http($p_link, $echo=true)
{
	if ($p_link == '') {
		$url = $p_link;
	} else if (preg_match('#^http://#', $p_link)) {
        $url = $p_link;
    } else {
        $url = 'http://'.$p_link;
    }
	if ($echo) {
		echo $url;
	}
	return $url;
}

function get_url_video($url, $echo=true)
{
	if (preg_match('#^http://#', $url)) {
		$new_image = $url;
	} else if (preg_match('#^/#', $url)) {
		$new_image = $url;
	} else {
		$new_image = '/'.$url;
	}
	$url = $new_image;
   
    if ($echo) {
        echo $url;
    }
    return $url;	
}

function call_js($url, $echo=true)
{
    $v_js = '<script type="text/javascript" src="'.html_js($url, false).'"></script>';
    if ($echo) {
        echo $v_js;
    }
    return $v_js;
}

/* Begin: Tytv - Bổ xung quản trị giá trị và loại danh mục */
/**
* Ham tạo html textbox lọc tìm ở box tìm kiếm
* @author: cuongnx - 22/10/2012
* @param: p_ten_inputbox Tên inputbox
* @param: p_gia_tri Giá trị của box
* @param: p_gia_tri_mac_dinh Giá trị mặc định của box
* @param: p_textbox_class tên class của textbox
* @param: p_kieu_so Kiểu dữ liệu là số :0, text: 1
* @return: html
*/

function html_textbox_loc_tim ($p_ten_textbox, $p_gia_tri, $p_gia_tri_mac_dinh, $p_textbox_class, $p_kieu_so =0,$v_script='',$v_hien_thi_luon=true)
{	
	
	$v_gia_tri = $p_gia_tri==''?$p_gia_tri_mac_dinh:$p_gia_tri;	
	if ($p_kieu_so==1) {
		$v_gia_tri = $p_gia_tri <=0?$p_gia_tri_mac_dinh:$p_gia_tri;
	}
	
	$v_html ='<input type="text" style="width:150px" name="'.$p_ten_textbox.'" id="'.$p_ten_textbox.'" value="'.$v_gia_tri.'" title="'.$v_gia_tri.'" class="'.$p_textbox_class.'" '.$v_script.'/>'; // '.html_script_onfocus($p_gia_tri_mac_dinh).'
	if ($v_hien_thi_luon) {
		echo $v_html;
	} else {
		return $v_html;
	}
	
}

/*
 * hàm tạo select box mã màu
 * @param:
 *  $name           tên mã màu
 *  $data           mảng dữ liệu
 *  $option_value   
 * return string
 **/
function html_select_box_ma_mau($name, $data, $option_value, $option_label, $default_value='', $extend='', $add_option=0)
{
	if (!is_array($data)) {
		$data = array();
	}
    $v_style = '';
    if($default_value != ''){
        $v_style = 'style="background-color: #'.$default_value.';"';
    }
	$text = '<select name="'.$name.'" id="'.$name.'" '.$extend.' onchange="show_background_ma_mau()" '.$v_style.'>';
	if ($add_option) {
		array_unshift($data, array($option_label=>'', $option_label=>'-- Chọn --'));
	}
	foreach($data as $value) {
		$selected = '';
		if ((string)$value[$option_label] == $default_value) {
			$selected = ' selected ';
		}
		$text .= '<option value="'.fw24h_replace_bad_char($value[$option_label]).'" '.$selected.'>'.fw24h_replace_bad_char(strip_tags($value[$option_label])).'</option>';
	}
	$text .= '</select>';
	return $text;
}