<?php
/**
* @desc: Cac ham su dung chung cho ung dung
*
* @author: dungpt@24h.com.vn @date: 2010/10/06 @desc: * fix loi ko tuong thik voi php5.3
* 
*/

function _setLayout($layoutName)
{
	global $fwLayout;
	$fwLayout = $layoutName.'.php';
	return $fwLayout;
}

function _setTheme($themeName)
{
	global $fwTheme;
	$fwTheme = $themeName;
	return $fwTheme;
}

function __get_db_functions($model)
{
	include_once WEB_ROOT.'includes/db_functions/'.$model.'.inc.php';
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
function fw24h_array_walk_recursive( $arr, $func )
{
    $newArr = array();
    var_dump($func);
    foreach ( $arr as $key => $value ) {
        $newArr[$key] = (is_array($value)?fw24h_array_walk_recursive($value, $func):$func($value));
    }
    return $newArr;
}


/**
 * fw24h_replace_bad_char()
 * 
 * @param mixed $string
 * @return
 */
function fw24h_replace_bad_char($p_string)
{
    if ( ini_get('magic_quotes_gpc')) {
        $p_string = stripslashes($p_string);
    }
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

/**
 * fw24h_replace_bad_char_to_null()
 * 
 * @param mixed $string
 * @return
 */
function fw24h_replace_bad_char_to_null($string)
{
    if ( ini_get('magic_quotes_gpc')) {
        $string = stripslashes($string);
    }
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
    if ( ini_get('magic_quotes_gpc')) {
        $string = stripslashes($string);
    }
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
    return $string;
}

/**
 * fw24h_security_code()
 * 
 * @param integer $p_len
 * @return
 */
function fw24h_security_code($len = 6)
{
    return substr(hexdec(md5(microtime())), 2, $len);
}

/**
 * fw24h_genID()
 * 
 * @return
 */
function fw24h_genID()
{
    $tmp = str_replace(' ', '', str_replace('.', '', microtime()));	
    return date('YmdHis').$tmp;
}


/**
 * Chuyen trang - redirect toi url khac 
 * @param string $p_link URL can chuyen toi
 * @return 
 */
function fw24h_url_redirect( $link)
{
    echo '<script language="JavaScript">location.href="'.$link
    .'"</script><meta http-equiv="Refresh" content="0;URL='.$link.'" />';
    exit();
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
    if ( !ini_get('magic_quotes_gpc')) {
        $string = addslashes($string);
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

/*
convert utf8 to NCR
*/
function _utf82ncr ($string)
{
    /* Only do the slow convert if there are 8-bit characters */
    /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
    if (! preg_match("#[\200-\237]#", $string) and ! preg_match("#[\241-\377]#", $string))
        return $string;

    // decode three byte unicode characters
    $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e", "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",   
    $string);

    // decode two byte unicode characters
    $string = preg_replace("/([\300-\337])([\200-\277])/e", "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
    $string);

    return $string;
} 

// convert ncr 2 utf8
function _ncr2utf8 ($source)
{
    $utf8Str = '';
    $entityArray = explode("&#", $source);
    $size = count($entityArray);
    for ($i = 0; $i < $size; $i++) {
        $subStr = $entityArray[$i];
        $nonEntity = strstr($subStr, ';');
        if ($nonEntity !== false) {
            $unicode = intval(substr($subStr, 0, (strpos($subStr, ';') + 1)));
            // determine how many chars are needed to reprsent this unicode char
            if ($unicode < 128) {
                $utf8Substring = chr($unicode);
            } else if ($unicode >= 128 && $unicode < 2048) {
                $binVal = str_pad(decbin($unicode), 11, "0", STR_PAD_LEFT);
                $binPart1 = substr($binVal, 0, 5);
                $binPart2 = substr($binVal, 5);
           
                $char1 = chr(192 + bindec($binPart1));
                $char2 = chr(128 + bindec($binPart2));
                $utf8Substring = $char1 . $char2;
            } else if ($unicode >= 2048 && $unicode < 65536) {
                $binVal = str_pad(decbin($unicode), 16, "0", STR_PAD_LEFT);
                $binPart1 = substr($binVal, 0, 4);
                $binPart2 = substr($binVal, 4, 6);
                $binPart3 = substr($binVal, 10);
           
                $char1 = chr(224 + bindec($binPart1));
                $char2 = chr(128 + bindec($binPart2));
                $char3 = chr(128 + bindec($binPart3));
                $utf8Substring = $char1 . $char2 . $char3;
            } else {
                $binVal = str_pad(decbin($unicode), 21, "0", STR_PAD_LEFT);
                $binPart1 = substr($binVal, 0, 3);
                $binPart2 = substr($binVal, 3, 6);
                $binPart3 = substr($binVal, 9, 6);
                $binPart4 = substr($binVal, 15);
       
                $char1 = chr(240 + bindec($binPart1));
                $char2 = chr(128 + bindec($binPart2));
                $char3 = chr(128 + bindec($binPart3));
                $char4 = chr(128 + bindec($binPart4));
                $utf8Substring = $char1 . $char2 . $char3 . $char4;
            }
           
            if (strlen($nonEntity) > 1)
                $nonEntity = substr($nonEntity, 1); // chop the first char (';')
            else
                $nonEntity = '';

            $utf8Str .= $utf8Substring . $nonEntity;
        } else {
            $utf8Str .= $subStr;
        }
    }

    return $utf8Str;
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

function pre( $array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
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
        $file = WEB_ROOT.'modules/models/'.$model.'.php';
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

// Ham day du lieu ve mst 
function Gnud_AutoPro_PostCurl($p_post, $url='xxxxx') {
	/*Begin 24-05-2018 trungcq XLCYCMHENG_31574_on_off_ket_noi_khampha*/
	if(preg_match("#khampha.vn#", $url)!==false && !ON_OFF_PUSH_DATA_TO_KHAMPHA){
		return false;
	}
	/*End 24-05-2018 trungcq XLCYCMHENG_31574_on_off_ket_noi_khampha*/
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

// Ham chuyen doi ky tu tieng viet sang khong dau
function _iso_to_ascii( $str) {

	$chars = array(
		'a'	=>	array('a&#803;','&#7845;','a&#768;','&#7847;','&#7849;','&#7851;','&#7853;','&#7844;','&#7846;','&#7848;','&#7850;','&#7852;','&#7855;','&#7857;','&#7859;','&#7861;','&#7863;','&#7854;','&#7856;','&#7858;','&#7860;','&#7862;','&#225;','&#224;','&#7843;','&#227;','&#7841;','&#226;','&#259;','&#193;','&#192;','&#7842;','&#195;','&#7840;','&#194;','&#258;','a&#769;','á','â','À'),
		'e' =>	array('&#7871;','&#7873;','&#7875;','&#7877;','&#7879;','&#7870;','&#7872;','&#7874;','&#7876;','&#7878;','&#233;','&#232;','&#7867;','&#7869;','&#7865;','&#234;','&#201;','&#200;','&#7866;','&#7868;','&#7864;','&#202;'),
		'i'	=>	array('&#237;','&#236;','&#7881;','&#297;','&#7883;','&#205;','&#204;','&#7880;','&#296;','&#7882;'),
		'o'	=>	array( 'ó', 'ô&#769;','&#417;&#769;','o&#803;','o&#769;','&#7889;','&#7891;','&#7893;','&#7895;','&#7897;','&#7888;','&#7890;','&#7892;','&#212;','&#7896;','&#7899;','&#7901;','&#7903;','&#7905;','&#7907;','&#7898;','&#7900;','&#7902;','&#7904;','&#7906;','&#243;','&#242;','&#7887;','&#245;','&#7885;','&#244;','&#417;','&#211;','&#210;','&#7886;','&#213;','&#7884;','&#212;','&#416;','ó','&#7894;'),
		'u'	=>	array('u&#769;','&#7913;','&#7915;','&#7917;','&#7919;','&#7921;','&#7912;','&#7914;','&#7916;','&#7918;','&#7920;','&#250;','&#249;','&#7911;','&#361;','&#7909;','&#432;','&#218;','&#217;','&#7910;','&#360;','&#7908;','&#431;'),
		'y'	=>	array('y&#769;','&#253;','&#7923;','&#7927;','&#7929;','&#7925;','&#221;','&#7922;','&#7926;','&#7928;','&#7924;'),
		'd'	=>	array('&#273;','&#272;'),
	);
	foreach ( $chars as $key=>$arr) {
		$str = str_replace( $arr, $key, $str);
	}	
	$str =  preg_replace("/(ã|â|à|á)/", 'a', $str );
	$str =  preg_replace("/(è|é|ê)/", 'e', $str );
	$str =  preg_replace("/(ò|ô|ó|õ)/", 'o', $str );
	$str =  preg_replace("/(ù|ú)/", 'u', $str );
	$str =  preg_replace("/(ì|í)/", 'i', $str );
	$str =  preg_replace("/(ý)/", 'y', $str );	
	return $str;
}


/**
* @desc:  Ham cat chuoi ky tu
* Tham so:	
		$str : Chuoi can cat
		$len : Do dai chuoi can cat	
*/
function cutBrief($str,$len)
{
	$space=" ";
	if(strlen($str)<=$len)
	{
		return $str;
	}
	else
	{
		$thelen=strlen($str);
		for($i=$len;$i<$thelen;$i++)
		{
			if(substr($str,$i,1)==$space)
			{
				$result=substr($str,0,$i);
				return $result."...";
			}
		}
		if($thelen=="")
		{
			return $str;
		}
	}
	return $str;
}

/**
* @desc:  Ham lay mang gia tri tu file XML_RSS
* Tham so:	
		$xml : Chuoi xml		
*/
function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null)
{
	if($childrenKey && !is_string($childrenKey)){$childrenKey = '@children';}
	if($attributesKey && !is_string($attributesKey)){$attributesKey = '@attributes';}
	if($valueKey && !is_string($valueKey)){$valueKey = '@values';}

	$return = array();
	$name = $xml->getName();
	$_value = trim((string)$xml);
	if(!strlen($_value)){$_value = null;};

	if($_value!==null){
		if($valueKey){$return[$valueKey] = $_value;}
		else{$return = $_value;}
	}

	$children = array();
	$first = true;
	foreach($xml->children() as $elementName => $child){
		$value = simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
		if(isset($children[$elementName])){
			if(is_array($children[$elementName])){
				if($first){
					$temp = $children[$elementName];
					unset($children[$elementName]);
					$children[$elementName][] = $temp;
					$first=false;
				}
				$children[$elementName][] = $value;
			}else{
				$children[$elementName] = array($children[$elementName],$value);
			}
		}
		else{
			$children[$elementName] = $value;
		}
	}
	if($children){
		if($childrenKey){$return[$childrenKey] = $children;}
		else{$return = array_merge($return,$children);}
	}

	$attributes = array();
	foreach($xml->attributes() as $name=>$value){
		$attributes[$name] = trim($value);
	}
	if($attributes){
		if($attributesKey){$return[$attributesKey] = $attributes;}
		else{$return = array_merge($return, $attributes);}
	}

	return $return;
}

function fw24h_replace_bad_char2($p_string) {
	if( ini_get('magic_quotes_gpc')) {
		$p_string = stripslashes($p_string);
	}
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
 * Kiem tra du lieu nhap vao co phai la so ten dang nhap hop le, co the them chuoi ky tu hop le 
 * @param string $p_username Ten dang nhap can kiem tra
 * @param string $p_allow_chars Chuoi ky tu hop le 
 * @param int $p_min Do rong ngan nhat cua chuoi username, mac dinh la 4
 * @param int $p_max Do dai nhat cua chuoi username, mac dinh la 14
 * @return boolean
 */
function fw24h_isUsername( $p_username, $p_allow_chars='') {	
	if( !preg_match( "#^[a-zA-Z0-9$p_allow_chars]+$#", $p_username)) {
		return false;
	}
	return true;
}

/**
 * Lay row trong mang rows ....
 * @param bool $p_get_one=true: tim thay thi return luon
				$p_get_one=false: Lay tat ca cac row thoa man dieu kien search

 * @return array
 */
function fw24h_array_filter($p_arrays, $p_column_search, $p_arr_value=array(), $p_get_one=true) {
	$ret_array = array();
	if (_check_array($p_arrays)) {
		foreach ($p_arrays as $v_array){
			if (in_array($v_array[$p_column_search], $p_arr_value)) {
				$ret_array[] = $v_array;
				if ($p_get_one) return $ret_array;
			}
		}
	}
	return $ret_array;
}

/**
 * include cac func cua module
 * @param  string $filename fileName without ext
 */
function fw24h_add_module_function($filename)
{
    include WEB_ROOT.'includes/module_functions/'.$filename.'.php';
}
/* Begin: Tytv - 28/07/2016 - Bổ xung quản trị giá trị và loại danh mục */
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

/* End: Tytv - 28/07/2016 - Bổ xung quản trị giá trị và loại danh mục */
//Begin 22-11-2019 : Thangnb xu_ly_day_lai_du_lieu_anh_video_24h_cho_bgt
/**
//Begin : 18-01-2016 : Thangnb chinh_sua_co_che_bat_loi
 * Ghi log de theo doi
 * @param string $message Noi dung can ghi log
 * @param string $file Ten file day du duong dan
 */
function fw24h_write_log($message, $file)
{
    $v_owner = shell_exec( 'whoami' );//Lay owner
    $v_owner = preg_replace('/\s/','',$v_owner);//Xoa ki tu trang
    $basename = basename($file,'.log');
    $file = str_replace($basename.'.log',$v_owner.'_'.$basename.'.log',$file);
    if (file_exists($file)) {
        @error_log($message, 3, $file);
    } else {
        @error_log($message, 3, $file);
        chmod($file, 0777);
    }
}
//End 22-11-2019 : Thangnb xu_ly_day_lai_du_lieu_anh_video_24h_cho_bgt