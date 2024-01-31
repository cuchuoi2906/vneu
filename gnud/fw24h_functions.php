<?php
/**
 * changes the layout
 * @param string $layoutName t�n file layout c?n d?i, ko c� .php
 */
function fw24h_set_layout($layoutName)
{
    global $fwLayout;
    $fwLayout = $layoutName.'.php';
    return $fwLayout;
}

function fw24h_set_theme($ThemeName)
{
    global $fwTheme;
    $fwTheme = $ThemeName;
    return $fwTheme;
}

/**
* @desc: Cac ham su dung chung cho ung dung
*
* @author: dungpt@24h.com.vn @date: 2010/10/06 @desc: * fix loi ko tuong thik voi php5.3
*
*/

function st_get_url(){
    return 'http://test.diemthi.24h.com.vn/';
}

function __get_db_functions($model,$p_device_name='pc')
{
    // Lấy biến thiết bị theo 
    global $v_device_global;
    $v_device_name = $p_device_name;
    if ($v_device_global == NAME_THIET_BI_MOBILE || $v_device_global == 'botmobi') {
        $v_device_name = 'mobile';
    } else if ($v_device_global == NAME_THIET_BI_AMP || $v_device_global == 'botamp'){
        $v_device_name = 'amp';
    } else if ($v_device_global == NAME_THIET_BI_TABLET || $v_device_global == 'bottablet') {
        $v_device_name = 'ipad';
    }
    include_once WEB_ROOT.'includes/db_functions/'.$model.'_'.$v_device_name.'.inc.php';
}

function __get_module_path()
{
    global $fwModuleName;
    return WEB_ROOT.'modules/'.$fwModuleName.'/';
}

function _get_theme_path()
{
    global $fwTheme;
    return WEB_ROOT.'templates/'.$fwTheme.'/';
}

/**
 * fw24h_array_walk_recursive()
 *
 * @param mixed $arr
 * @param mixed $func
 * @return
 */
function fw24h_array_walk_recursive( &$arr, $func )
{
    // $arr = array();
    foreach( $arr as $key => $value ) {
        $arr[$key] = (is_array($value )?fw24h_array_walk_recursive($value, $func):$func($value));
    }
    return $arr;
}

/**
 * fw24h_replace_bad_char()
 *
 * @param mixed $string
 * @return
 */
function fw24h_replace_bad_char($p_string)
{
	$p_string = trim($p_string);
	$p_string = stripslashes($p_string);
    $p_string = str_replace('<', '&lt;', $p_string);
    $p_string = str_replace('>', '&gt;', $p_string);
    $p_string = str_replace('"', '&#34;', $p_string);
    $p_string = str_replace("'", '&#39;', $p_string);
    $p_string = str_replace('\\', '&#92;', $p_string);
    $p_string = str_replace('=', '&#61;', $p_string);
    $p_string = str_replace('(', '&#40;', $p_string);
    $p_string = str_replace(')', '&#41;', $p_string);
    $p_string = str_replace("|", '&#124;', $p_string);
    return $p_string;
}

function fw24h_replace_bad_char2($p_string) {
	$p_string = trim($p_string);
	$p_string = stripslashes($p_string);
    $p_string = str_replace('<','&lt;', $p_string);
    $p_string = str_replace('>','&gt;',$p_string);
    $p_string = str_replace('"','&#34;', $p_string);
    $p_string = str_replace("'",'&#39;', $p_string);
    $p_string = str_replace("\\",'&#92;', $p_string);
    $p_string = str_replace("(",'&#40;', $p_string);
    $p_string = str_replace(")",'&#41;', $p_string);
    //$p_string = str_replace("-",'&#45;', $p_string);
    $p_string = str_replace("|",'&#124;', $p_string);
    return $p_string;
}

/**
 * fw24h_replace_bad_char_to_null()
 *
 * @param mixed $string
 * @return
 */
function fw24h_replace_bad_char_to_null($string)
{
	$p_string = trim($p_string);
	$string = stripslashes($string);
    $string = str_replace('&', '', $string);
    $string = str_replace('<', '', $string);
    $string = str_replace('>', '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('\\', '', $string);
    $string = str_replace('=', '', $string);
    $string = str_replace('&#38;','', $string);
    $string = str_replace('&lt;','', $string);
    $string = str_replace('&gt;','', $string);
    $string = str_replace('&#34;','', $string);
    $string = str_replace('&#39;','', $string);
    $string = str_replace('&#92;','', $string);
    $string = str_replace('&#61;','', $string);
    return $string;
}

/**
 * fw24h_restore_bad_char()
 *
 * @param mixed $string
 * @return
 */
function fw24h_restore_bad_char($string)
{	
	$p_string = trim($p_string);
	$string = stripslashes($string);
    $string = str_replace('&#38;', '&', $string);
    $string = str_replace('&lt;', '<', $string);
    $string = str_replace('&gt;', '>', $string);
    $string = str_replace('&#34;', '"', $string);
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#92;', '\\', $string);
    $string = str_replace('&#61;', '=', $string);
    $string = str_replace('&quot;', '"', $string);
    $string = str_replace('&#40;', '(', $string);
    $string = str_replace('&#41;', ')', $string);
    $string = str_replace("&#124;", '|', $string);
    return $string;
}



function Gnud_Cache_FileName($key)
{
    $filename = md5($key);
    $sub_dir = substr($filename, -2);
    $filename = GNUD_CACHE_DIR.$sub_dir.'/'.$filename;
    return $filename;
}

function Gnud_Cache_Get($key, $ttl=1800)
{
    $filename = Gnud_Cache_FileName($key);
    if (!file_exists($filename)) {
        return false;
    }

    if (time(0) - filemtime($filename) > $ttl) {
        return false;
    }

    $data = unserialize(file_get_contents($filename));
    return $data['data'];
}

function Gnud_Cache_Store($key, $data)
{
    $filename = Gnud_Cache_FileName($key);

    $rows['data'] = $data;
    $rows['key']  = $key;

    $result = file_put_contents($filename, serialize($rows));
    chmod($filename, 0777);
}


/**
 * fw24h_security_code()
 * @param integer $p_len
 * @return
 */
function fw24h_security_code($len = 6)
{
    return substr(hexdec(md5(microtime())), 2, $len);
}

/**
 * Chuyen trang - redirect toi url khac
 * @param string $p_link URL can chuyen toi
 * @return
 */
function fw24h_url_redirect($link, $p_use_301=false)
{
	if (!$p_use_301){
		echo '<script language="JavaScript">location.href="'.$link
		.'"</script><meta http-equiv="Refresh" content="0;URL='.$link.'" />';
		exit();
	}else{
		header("location: $link ", true, 301);
		exit();
	}	
}

function fw24h_base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

function fw24h_base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}

// Get Remote IP Address in PHP
function fw24h_Get_IP_Address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function fw24h_add_slashes($string)
{
    if( ini_get('magic_quotes_gpc')) {
		$p_string = stripslashes($p_string);
	}
    return $string;
}

/**
* Generates an UUID
*
* @author     Anis uddin Ahmad <admin@ajaxray.com>
* @param      string  an optional prefix
* @return     string  the formatted uuid
*/
function _uuid( $prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid  = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    return $prefix . $uuid;
}
// ham cat chuoi theo tu
function fw24h_split_string ($string, $num) {
    $string = strip_tags( str_replace( '&nbsp;', ' ', $string));
    $string = trim(preg_replace( '# +#', ' ', $string));
    $array = explode(" ", $string);
    $new_array = array();
    $total = count($array);
    $i = 0;
    while ($i<$total && $i<$num)
    {
        $new_array[] = $array[$i];
        $i++;
    }
    $str = implode(" ", $new_array);
    $str.= ($total > $num) ? '...' : '';
    return $str;
}
// cat chuoi utf8, sau khi cat su dung them preg_replace( '#[^ ]*$#', '', $txt); de lam dep chuoi
function _utf8_substr($str, $mbstart, $mblen = null)
{
    // Clean parameters
    $str = strval($str);

    // PHP 5 optimisation
    #   if (PHP5 && function_exists('iconv_substr'))
    #       return iconv_substr($str, $mbstart, $mblen, 'UTF-8');
    // TODO,FIXME: iconv_substr returns false when it isn't supposed to. can't use that function

    $pos = 0;         // current ascii string index
    $mbpos = 0;       // current multibyte symbol index
    $start = false;   // where to start copy then
    $len = 0;         // number of bytes to copy
    while ($pos < strlen($str)) {
        $value = ord($str[$pos]);

        if ($value > 127) {            
			if($value >= 224 && $value <= 239) $bytes = 3;
            elseif ($value >= 240 && $value <= 247) $bytes = 4;
            else   $bytes = 2;  /* 192...223 */
        } elseif ($value == 34 || $value == 39) {
			//Begin 06-01-2017 : Thangnb dieu_chinh_cat_chu_box_tin_tuc_the_thao_bong_da
			$bytes = 1;	 // dau nhay don hoac nhay kep
			//End 06-01-2017 : Thangnb dieu_chinh_cat_chu_box_tin_tuc_the_thao_bong_da
		} else {
			$bytes = 1;		
		}
		
        if ($mbpos == $mbstart) $start = $pos;   // this may be our starting symbol index
        if ($start !== false) $len += $bytes;   // if we're in-index, add number of bytes of this symbol
        if (isset($mblen) && $mbpos - $mbstart + 1 == $mblen) break;   // stop condition

        $mbpos++;
        $pos += $bytes;
    }
    if ($start === false) return '';
    return substr($str, $start, $len);
}

function cutBrief( $str, $num=11, $ext='...') {
    $str = trim( $str);
	
	$v_fw24h_restore_bad_char = false;
	if (str_replace(array('&lt;', '&gt;', '&#34;', '&#39;', '&#92;', '&#61;', '&#40;', '&#41;', '&#124;', '&quot;'), '', $str) != $str) { // co cac ky tu dac biet da replace truoc thi can doi lai truoc khi cat
		$str = fw24h_restore_bad_char($str);
		$v_fw24h_restore_bad_char = true;
	}
	//Begin 06-01-2017 : Thangnb dieu_chinh_cat_chu_box_tin_tuc_the_thao_bong_da
    $newStr = _utf8_substr( $str.' ', 0, $num+1);
	//End 06-01-2017 : Thangnb dieu_chinh_cat_chu_box_tin_tuc_the_thao_bong_da
	
    $newStr = preg_replace( '#[^ ]*$#', '', $newStr);
    $newStr = trim( $newStr);
    if ( strlen($str) > strlen($newStr)) {
        return $v_fw24h_restore_bad_char ? fw24h_replace_bad_char($newStr . $ext) : $newStr . $ext;
    }
    else {
        return $v_fw24h_restore_bad_char ? fw24h_replace_bad_char($newStr) : $newStr;
    }
}

// substring of ncr string, sau khi cat su dung them preg_replace( '#[^ ]*$#', '', $txt); de lam dep chuoi
//
function _ncr_substr($text,$start,$limit=0)
{
    $return = '';
    preg_match_all('/((?:&(?:#[0-9]{2,}|[a-z]{2,});)|(?:[^&])|(?:&(?!\w;)))/s', $text, $textarray);
    $textarray = $textarray[0];
    $numchars = count($textarray)-1;
    if ($start>=$numchars)
        return false;
    if ($start<0) {
        $start = ($numchars)+$start+1;
    }
    if ($start>=0) {
        if ($limit==0) {
            $end=$numchars;
        } elseif ($limit>0) {
            $end = $start+($limit-1);
        } else {
            $end = ($numchars)+$limit;
        }

        for ($i=$start;($i<=$end && isset($textarray[$i]));$i++) {
            $return .= $textarray[$i];
        }
        return $return;
    }
}

/**
 * ham doc key ma giai ma
 * @param string $p_key: Ten key-value
 * @return string
 */
function fw24h_read_key_and_decode($p_key){
    $rs_data = Gnud_Db_read_get_key($p_key);
    if($rs_data!=''){
        $rs_data = unserialize(gzuncompress(base64_decode($rs_data)));
    }
    return $rs_data;
}

/**
* @desc: Ham lay mang tu cache data
* Tham so:
    $p_file_url: file chua mang can chuyen
* @author: ducnq@24h.com.vn @date: 2012/2/01 @desc: create new
*/
// function convert_array_from_cache_data($p_file_url){
    // $rs = array();
    // if (file_exists($p_file_url)) {
        // $rs = file_get_contents($p_file_url);
        // if($rs!=''){
            // $rs = unserialize(gzuncompress(base64_decode($rs)));
        // }
    // }
    // return $rs;
// }

/**
 * Kiem tra du lieu nhap vao co phai la so ten dang nhap hop le, co the them chuoi ky tu hop le
 *
 * @param string $p_username Ten dang nhap can kiem tra
 * @param string $p_allow_chars Chuoi ky tu hop le
 * @param int $p_min Do rong ngan nhat cua chuoi username, mac dinh la 4
 * @param int $p_max Do dai nhat cua chuoi username, mac dinh la 14
 * @return boolean
 */
function fw24h_isUsername( $p_username, $p_allow_chars='', $p_min=4, $p_max=14) {
    $f_len = strlen( $p_username);

    if( $f_len < $p_min || $f_len > $p_max) {
        return false;
    }

    if( !preg_match( "#^[a-zA-Z0-9$p_allow_chars]+$#", $p_username)) {
        return false;
    }
    return true;
}

function fw24h_isPassword( $p_username, $p_allow_chars='', $p_min=4, $p_max=14) {
    return fw24h_isUsername( $p_username, $p_allow_chars, $p_min, $p_max);
}

/* Ducnq
 * Ham thuc hien khoi phuc cac ky tu da bi chuyen doi truoc khi replace
 */
function fw24h_replace_bad_char_after_restore($p_string) {
    return fw24h_replace_bad_char(fw24h_restore_bad_char($p_string));
}


/**
 * __autoload()
 *
 * @param mixed $name ten cua class can autoload
    *
 * @return true
 */
function autoload( $name)
{
    if (preg_match('#_Block_Model$#', $name)) {
        $name = strtolower($name);
        $m = explode('_', $name);
        $file = str_replace( '_block_model', '', $name);
        $file = WEB_ROOT.'modules/blocks/'.$m[0].'/model/'.$file.'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
    }
    if (preg_match('#_Block_View$#', $name)) {
        $name = strtolower($name);
        $m = explode('_', $name);
        $file = str_replace( '_block_view', '', $name);
        $file = WEB_ROOT.'modules/blocks/'.$m[0].'/view/'.$file.'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
        $name = strtolower($name);
        $m = explode('_', $name);
        $file = WEB_ROOT.'modules/'.$m[0].'/'.$m[2].'/'.$m[1].'_'.$m[2].'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
    }
    if (preg_match('#_Model$#', $name)) {
        $model = str_replace('_model', '', strtolower($name));
        $file = WEB_ROOT.'modules/_models/'.$model.'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
        $name = strtolower($name);
        $m = explode('_', $name);
        $file = WEB_ROOT.'modules/'.$m[0].'/'.$m[2].'/'.$m[1].'_'.$m[2].'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
    }
    if (preg_match('#_View$#', $name)) {
        $file = WEB_ROOT.'modules/_views/'.strtolower($name).'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
        $name = strtolower($name);
        $m = explode('_', $name);
        $file = WEB_ROOT.'modules/'.$m[0].'/'.$m[2].'/'.$m[1].'_'.$m[2].'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
    }
    if (preg_match('#_Block$#im', $name)) {
        $name = strtolower($name);
        $file = str_replace('_block', '', $name);
        $m = explode('_', $name);
        $file = WEB_ROOT.'modules/blocks/'.$file.'/'.$file.'.php';
        if (file_exists($file)) {
            include $file;
            return true;
        }
    }
}
spl_autoload_register('autoload');
function fw24h_set( $key, $value, $overwrite=true)
{
    global $fwAppKeyValue;
    if ($overwrite) {
        $fwAppKeyValue[$key] = $value;
        return true;
    }
    if (fw24h_get( $key)) {
        return false;
    } else {
        $fwAppKeyValue[$key] = $value;
        return true;
    }
}

function fw24h_get( $key, $value=null) {
    global $fwAppKeyValue;
    if (isset($fwAppKeyValue[$key])) {
        return $fwAppKeyValue[$key];
    }
    return $value;
}

// Ham day du lieu ve mst nhac vui tu cac server slaves
function Gnud_AutoPro_PostCurl2($p_post, $url='xxxxx') {

    $opts = array('http' =>
    array(
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => http_build_query($p_post, '', '&'),
    'timeout' => 500,
    )
    );
    $context = stream_context_create($opts);
    $text = file_get_contents($url, 0, $context);
    return $text;
}

/**
 * include cac func cua module
 * @param  string $filename fileName without ext
 */
function fw24h_add_module_function($filename)
{
    include_once WEB_ROOT.'includes/module_functions/'.$filename.'.php'; 
}

/** Ham kiem tra dinh dang EMAIL
 *  Param string $strEmail: chuoi email can kiem tra
 */
function fw24h_isEmail( $strEmail){
    //pre( $strEmail);
    $strRegular = '#[A-Za-z0-9_\.\-]+@[A-Za-z0-9_\.\-]+\.';
    $strRegular = $strRegular . '[A-Za-z0-9_\-][A-Za-z0-9_\-]+$#';
    if ( !preg_match( $strRegular, $strEmail)) {
        return false;
    }
    if (_utf8_to_ascii($strEmail) != $strEmail) {
        return false;
    }
    return true;
}

function fw24h_get_uri($encode=false, $p_giu_nguyen_trang = 0)
{
    $uri = str_replace('/ajax/', '/', $_SERVER['REQUEST_URI']);
    if ($p_giu_nguyen_trang == 0) {
        $uri = preg_replace('/&page=[0-9]*/', '', $uri);
        $uri = preg_replace('/\?page=[0-9]*/', '', $uri);
    }
    if ($encode) {
        return fw24h_base64_url_encode($uri);
    }
    return $uri;
}

function pre( $array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/**
 * Lay row trong mang rows ....
 * @param bool $p_get_one=true: tim thay thi return luon
                $p_get_one=false: Lay tat ca cac row thoa man dieu kien search

 * @return array
 */
function fw24h_array_filter($p_arrays, $p_column_search, $p_arr_value=array(), $p_get_one=true) {
    $ret_array = array();
    if (check_array($p_arrays)) {
        foreach ($p_arrays as $v_array){
            if (in_array($v_array[$p_column_search], $p_arr_value)) {
                $ret_array[] = $v_array;
                if ($p_get_one) return $ret_array;
            }
        }
    }
    return $ret_array;
}

function cat_chuoi_tv($string, $len, $ext='...' , $start = 0){
    $string = trim($string);
    $str = _utf8_to_ascii($string);
    $nleng = strlen($str);
    if($len > $nleng) return $string;
    $reStr = substr($str, $start, $len);
    if($str[$len] == " "){
        $str = $reStr;
        $nSpace = substr_count($str, ' ');
    }else{
        $nSpace = substr_count($reStr, ' ');
    }
    $strArr = explode(" " , $string);
    $strArr = array_chunk($strArr, $nSpace);
    return $string = implode($strArr[0], " " ) . $ext;
}

/**
 * Ghi log de theo doi
 * @param string $message Noi dung can ghi log
 * @param string $file Ten file day du duong dan
 */
function fw24h_write_log($message, $file)
{
    if (file_exists($file)) {
        error_log($message, 3, $file);
    } else {
        error_log($message, 3, $file);
        chmod($file, 0777);
    }
}
