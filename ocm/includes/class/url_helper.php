<?php
class UrlHelper {
	var $_BASE_URL = '/';
	var $subfix = '.html';
	
	function &getInstance () {
        static $instance;
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c;
        }
        return $instance;
    }
	
	function iso_ascii( $string) {
		$string = fw24h_iso_ascii($string, '');
		$string = str_replace(array(' / ',' /','/ '), '/', $string);
		$string = str_replace( ' ', '-', $string);
        $string = preg_replace( '#[-_]+#', '-', $string);
		return $string;
	}
	
	function strnpos( $haystack, $needle, $nth, $offset = 0) {
		for ($retOffs=$offset-1; ($nth>0)&&($retOffs!==FALSE); $nth--) $retOffs = strpos($haystack, $needle, $retOffs+1);
		return $retOffs;
	} 
	
	function trimSlashes( $string, $hold=1) {
		$pos = $this->strnpos( $string, '/', $hold);
		if ( $pos>0) {
			$str1 = substr( $string, 0, $pos);
			$str2 = str_replace( '/', '-', substr( $string, $pos));
			return $str1.$str2;
		}
		else {
			return $string;
		}
	}
	
	function preSlug( $str) {
		$str = $this->iso_ascii($str);
		$str = strtolower($str);
		return str_replace('//', '/', $str);
	}

	function url_cate( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-c'.$arr['ID'].$this->subfix;
	}
	
	function url_news( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-a'.$arr['ID'].$this->subfix;
	}
	
	function url_overview( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-c'.$arr['cID'].'o'.$arr['ID'].$this->subfix;
	}
	
	function url_event( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 1).'-c'.$arr['cID'].'e'.$arr['ID'].$this->subfix;
	}
	
	function url_special( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-s'.$arr['cID'].$this->subfix;
	}
	
	function url_ttdt( $arr) {
		$arr['id_province'] = ($arr['id_province']>0) ? $arr['id_province'] : 0;
		$arr['id_chuyennganh'] = ($arr['id_chuyennganh']>0) ? $arr['id_chuyennganh'] : 0;		
		return $this->_BASE_URL.$this->preSlug($arr['slug']).'-c'.$arr['cID'].'t'.$arr['ID'].'p'.$arr['id_province'].'m'.$arr['id_chuyennganh'].''.$this->subfix;
	}
	
	function url_tag( $arr) {
		return $this->_BASE_URL.'su-kien/'.$this->trimSlashes( $this->preSlug($arr['slug'])).(($arr['page']!='') ? '/'.$arr['page'] : '/');
	}
	/* Begin: Tytv - 22/09/2017 - toi_uu_tinh_chinh_menu_ngang_header */
    function url_home_video() {
		return $this->_BASE_URL.'tong-hop-video.html';
	}
    function url_cate_video( $arr) { 
		return $this->_BASE_URL.'video/'.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-cvd'.$arr['ID'].$this->subfix;
	}
    function url_video_news( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-c'.$arr['cID'].'vd'.$arr['ID'].$this->subfix;
	}
    /* Begin: Tytv - 22/09/2017 - toi_uu_tinh_chinh_menu_ngang_header */
}