<?php
class UrlHelper {
	var $_BASE_URL = BASE_URL;
	var $subfix = '.html';
	
	function getInstance () {
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
		if(@$arr['Img']){
			return $this->_BASE_URL.$this->preSlug($arr['slug']).'-c'.$arr['cID'].'a'.$arr['ID'].'i'.$arr['Img'].$this->subfix;
		}/* Begin 29/11/2017 Tytv xay_dung_quizz_6(FE) */
        else if(@$arr['quizzIndex']){			
            return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-c'.$arr['cID'].'a'.$arr['ID'].'q'.$arr['quizzIndex'].$this->subfix;
		}/* End 29/11/2017 Tytv xay_dung_quizz_6(FE) */
		else{
            /*Begin 23-08-2016 trungcq ghi_log_truong_hop_sai_url_news*/
            $v_url_news = $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-a'.$arr['ID'].$this->subfix;
            /*End 23-08-2016 trungcq ghi_log_truong_hop_sai_url_news*/
			return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 2).'-a'.$arr['ID'].$this->subfix;
		}
	}
	function url_event( $arr) {
		return $this->_BASE_URL.$this->trimSlashes( $this->preSlug($arr['slug']), 1).'-c'.$arr['cID'].'e'.$arr['ID'].$this->subfix;
	}
}