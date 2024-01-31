<?php
$str = $_POST['paste_content'];
$str = preg_replace('#<li[^>]*>#', '<p>', $str);
$str = str_replace('</li>', '</p>', $str);
$str = preg_replace('#<div[^>]*>#', '<p>', $str);
$str = str_replace('</div>', '</p>', $str);
$str = replace_br($str);
$str = strip_tags($str, '<p><a><img>');
$str = stripAttributes($str, array('src', 'href', 'width', 'height'));
$str = str_replace(array('<p><p>', '<p><p >', '</p></p>', '<p></p>'), array('<p>', '<p>', '</p>', ''), $str);
$str = align_center_image($str);
$str = resize_image($str);
/* begin 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */
$str = _replace_special_tags_in_content($str,'script/language(.*?)vbscript(.*?),script/language(.*?)VBScript(.*?)');
/* end 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */
echo $str;

function stripAttributes($s, $allowedattr = array()) {
    if (preg_match_all("/<[^>]*\\s([^>]*)\\/*>/msiU", $s, $res, PREG_SET_ORDER)) {
        foreach ($res as $r) {
            $tag = $r[0];
            $attrs = array();
            preg_match_all("/\\s.*=(['\"]).*\\1/msiU", " " . $r[1], $split, PREG_SET_ORDER);
            foreach ($split as $spl) {
                $attrs[] = $spl[0];
            }
            $newattrs = array();
            foreach ($attrs as $a) {
                $tmp = explode("=", $a);
                if (trim($a) != "" && (!isset($tmp[1]) || (trim($tmp[0]) != "" && !in_array(strtolower(trim($tmp[0])), $allowedattr)))) {
                    // do nothing
                } else {
                    $newattrs[] = $a;
                }
            }
            $attrs = implode(" ", $newattrs);
            $rpl = str_replace($r[1], $attrs, $tag);
            $s = str_replace($tag, $rpl, $s);
        }
    }
    return $s;
}

function replace_br($data) {
    $data = preg_replace('#(?:<br\s*/?>\s*?){2,}#', '</p><p>', $data);
    return "<p>$data</p>";
}

/*
	Ham tim cac the img va thuc hien can giua anh.
*/
function align_center_image($data)
{
	$data = preg_replace('#<p>(<img [^>]*>)</p>#', "<p align=\"center\">$1</p> ", $data);
	return $data;
}

function resize_image($data)
{
    defined('MAX_WITH_IN_BODY') or define('MAX_WITH_IN_BODY', '660');         /* add: Trungcq - 13/06/2017 - XLCYCMHENG_22717_toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
    preg_match_all('#<img [^>]*>#', $data, $v_img_tag);
    foreach ($v_img_tag[0] as $img) {
        preg_match('#<img[^>]*src\=[\'|"]{0,1}([^>|\'|"]*)[\'|"]{0,1}[^>]*>#i', $img, $match);
        if (isset($match[1]) && !empty($match[1])) {
            $v_img_path = $match[1];
            $v_size = @getimagesize($v_img_path);
            /* Begin: Trungcq - 13/06/2017 - XLCYCMHENG_22717_toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
            if (!preg_match('#width#', $img) && intval($v_size[0]) > MAX_WITH_IN_BODY) {
                $v_img_resized = str_replace('<img ', '<img width="'.MAX_WITH_IN_BODY.'"', $img);
                $data = str_replace($img, $v_img_resized, $data);
            }
            /* End: Trungcq - 13/06/2017 - XLCYCMHENG_22717_toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
        }
    }
    return $data;
}
/* begin 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */
function _replace_special_tags_in_content($p_content, $p_tags = ''){
    // Nội dung cần loại bỏ các thẻ html đặc biệt
    $v_content = $p_content;
    
    // Kiểm tra nội dung
    if($v_content == ''){
        return $v_content;
    }
    // Cấu hình các thẻ html cần xóa
    $v_tags = $p_tags;
    if($v_tags == ''){
        // Lấy cấu hình các thẻ html không cho phép chèn vào editor
        $v_config = _get_all_lists_by_listtype_code('GENERAL_CONFIG', true, true);
        // Lấy cấu hình các thẻ html không được phép chèn vào nội dung html trong editor
        $v_tags = strtolower($v_config['GENERAL_EXCLUTION_HTML_TYPE']['c_ten']);
    }
    //========== Xử lý loại bỏ các element html không dùng trong nội dung html 
    // Kiểm tra thẻ html không đc chèn vào editor
    if($v_tags){
        // Chuyển chuỗi thành mảng
        $v_arr_tags = explode(',', $v_tags);
        foreach ($v_arr_tags as $key => $tag) {
            // Thuộc tính của thẻ html cần xóa
            $v_attr_tag = '';
            // Thẻ html cần xóa
            $v_tag = $tag;
            // KIỂM TRA tag
            if($v_tag == ''){
                continue;
            }
            // Tách chuỗi
            $v_arr_tag_tmp  = explode('/', $tag);
            // Kiểm tra tag và thuộc tính của tag có tồn tại không
            if(sizeof($v_arr_tag_tmp) >= 2){
                $v_attr_tag = $v_arr_tag_tmp[1];
                $v_tag      = $v_arr_tag_tmp[0];    
            }

            // Nếu tồn tại xóa theo thẻ và thuộc tính của thẻ
            if($v_attr_tag && $v_tag){
                // tạo chuỗi tìm kiếm các thẻ html thay thế
                $v_str_preg_replace = str_replace(array('{tag}', '{attributes}'), array($v_tag, $v_attr_tag), '#<{tag}(.*?){attributes}<\/{tag}>#');
            } else {
                // tạo chuỗi tìm kiếm các thẻ html thay thế
                $v_str_preg_replace = str_replace('{tag}', $tag, '/<{tag}(.*?)<\/{tag}>/i');
            }
            if (strpos($v_content,'VBScript') !== false || strpos($v_content,'vbscript') !== false || strpos($v_content,'VBscript') !== false) {
                $v_content = str_replace(array('&lt;','&gt;'),array('<','>'),$v_content);
            }
            $v_content = preg_replace($v_str_preg_replace, '', $v_content);
        }
    }
    
    return $v_content;
}

/* end 26/9/2017 TuyenNT xu_ly_loai_bo_the_vbscript_trong_cac_cho_dung_editor_24h */