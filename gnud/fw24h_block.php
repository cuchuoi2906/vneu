<?php
/**
 * view_type = 1 - goi tu ajax
 * view_type = 2 - goi tu gnud_request_services_tier - curl 
 * $_REQUEST['BLOCK_VIEW_NOINDEX']
 * $_REQUEST['BLOCK_VIEW_HEAD_TAG']
 */
class Fw24H_Block
{
    var $blockContent = '';
    var $viewVars = array();
    var $autoRender = true;
    var $_block_path = '';
    var $_prefix_key = '';
    public $_cache_key = '';
    var $time_start = 0;
    var $html_cache_time = 3600;
    var $_cache_table = 'key_value_common';

    function __construct()
    {
        global $SQL_IN_BLOCK;
        $SQL_IN_BLOCK = '';
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->setParam( 'view_type', 1);
        }

        if (isset($_SERVER['HTTP_USER_AGENT']) && strtolower($_SERVER['HTTP_USER_AGENT']) == 'gnud_request_services_tier') {
            $this->setParam( 'view_type', 2);
        }
        $this->time_start = microtime(true);
        
        global $first_block_call;
        if (is_null($first_block_call)) {
            $first_block_call = true;
        }
    }
    function __destruct()
    {
        
    }
    
    function __log_stats()
    {
        $time_end = microtime(true);
        $time = $time_end - $this->time_start;
        if (GNUD_DEBUG_MODE) {
            global $SQL_IN_BLOCK;
            $time = number_format($time, 12);
            $time = substr($time, 0, 8);
            $file_name = WEB_ROOT.'/logs/blocks/'.get_called_class().'.txt';
            $file_name_sql = WEB_ROOT.'/logs/blocks/'.get_called_class().'_sql.txt';
            fw24h_write_log(date('Y-m-d H:i:s').": time: $time class: ".get_called_class()." URI: ".$_SERVER['REQUEST_URI']." ".$this->time_start." {$time_end}\n", $file_name);
            if ($SQL_IN_BLOCK!='') {
                fw24h_write_log($SQL_IN_BLOCK."\n", $file_name_sql);
            }
            
            global $first_block_call;
            if ($first_block_call == true) {
                fw24h_write_log("\n\n", WEB_ROOT.'/logs/blocks_profile.txt');
                $first_block_call = false;
            }
            fw24h_write_log(date('Y-m-d H:i:s').": time: $time class: ".get_called_class()." URI: ".$_SERVER['REQUEST_URI']." ".$this->time_start." {$time_end}\n", WEB_ROOT.'/logs/blocks_profile.txt');
        }
		if ($time > 1){
			$errorMsg = date('Y-m-d H:i:s '). " block ".get_called_class()." URI: ".$_SERVER['REQUEST_URI']." ".": user_agent: ".$_SERVER['HTTP_USER_AGENT']." chay chậm: $time giay \n";;
			@error_log($errorMsg, 3, WEB_ROOT.'logs/block_slow.log');	
		}
    }

    function Fw24H_Block()
    {

    }

    function index()
    {

    }

    function render( $name='')
    {

        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        if ( $name == '') {
            $file = WEB_ROOT.'modules/blocks/'.$className.'/view/'.$className.'.php';
        } else {
            $file = $name;
        }

        extract($this->viewVars);
        ob_start();
        include( $file);  // Include the file
        $this->blockContent = ob_get_contents(); // Get the contents of the buffer
        ob_end_clean();
        
        $this->__log_stats();
        if ($this->autoRender) {
            echo $this->blockContent;
        }
        return $this->blockContent;
        
    }

    function className()
    {
        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        return $className;
    }

    function thisPath()
    {
        return WEB_ROOT.'modules/blocks/'.$this->className().'/';
    }

    function setParam( $key, $value)
    {
        $this->viewVars[$key] = $value;
    }

    function getParam( $key)
    {
        if ( isset($this->viewVars[$key])) {
            return $this->viewVars[$key];
        }
        return null;
    }

    function getCacheFile()
    {
        return CACHE_HTML_DIR.strtolower(get_class($this)).'.html';
    }

    function getRssFile()
    {
        return UPLOAD_RSS_FILE_DIR.strtolower(get_class($this)).'.rss';
    }

    function buildHtmlCache()
    {
        $fileName = $this->getCacheFile();
        $this->autoRender = false;
        $this->doAction();
        $content = $this->blockContent;
        file_put_contents($fileName, $content);
        chmod($fileName, 0777);
        echo '<!-- buildHtmlCache:DONE -->';
    }

    function buildHtmlCacheLite($fileName='')
    {
        $v_html_value = '';

        $this->autoRender = false;
        $this->doAction();
        $content = $this->blockContent;

        if (!$fileName) {
            $v_key = $this->getKey();
            $fileName = CACHE_HTML_DIR.$v_key;
        }

        file_put_contents($fileName, $content);
        chmod( $fileName, 0777);
        return $content;
    }

    function buildRssFile()
    {
        $GLOBALS["QUICKCACHE_ON"] = 0;
        $fileName = $this->getRssFile();
        $this->autoRender = false;
        $this->doRssAction();
        $content = $this->blockContent;
        $data['fileName'] = $fileName;
        $data['fileContent'] = $content;
        Gnud_AutoPro_PostCurl($data, MASTER_WS_UPDATE_DATA);
        echo '<!-- buildRssFile:DONE -->';
    }

    function buildHtmlCache2MySQL()
    {
        $GLOBALS["QUICKCACHE_ON"] = 0;
        $this->setParam('gen_key', 1);
        $this->autoRender = false;
        $this->doAction();
        $content = $this->blockContent;
        $fileName = $this->getKey();
        if (preg_match('#^header#', $fileName) || (!preg_match('#^header#', $fileName) && _check_html_key_value($content))){
            $data['C_KEY'] = $fileName;
            $data['C_VALUE'] = trim($content);
            Gnud_Db_write_update_key($data, $this->_cache_table);
        } else {
			echo '<!-- content:FAIL -->';
		}
        echo '<!-- buildHtmlCache2MySQL:DONE -->';
    }

    function setKey($key='')
    {
        if ($this->_cache_key == '') {
            $this->_cache_key = strtolower(get_class($this));
        }
        $this->_cache_key .= $key;
        //echo $this->_cache_key;die;
    }

    function getKey()
    {
        if ($this->_cache_key == '') {
            $this->_cache_key = strtolower(get_class($this));
        }
        return $this->_cache_key;
    }

    function getVar($var, $val='intval')
    {
        $cat_id = $this->getParam($var);
        if ($cat_id == '') {
           if ($val!='') {
                $cat_id = $val($_GET[$var]);
           } else {
                $cat_id = $_GET[$var];
           }
           $this->setParam($var, $cat_id);
        }
        return $cat_id;
    }

    function setParamAll($array_param, $ignore=array())
    {
        $key = '';
        $ignore[] = 'noindex';
        $ignore[] = 'view_type';
        $ignore[] = 'gen_key';

        if (isset($_GET['view_type'])) {
            $array_param['view_type'] = $_GET['view_type'];
        }
        foreach($array_param as $k=>$v) {
            if (isset($_GET[$k])) {
                $v = $_GET[$k];
                $this->setParam($k, $_GET[$k]);
            } else {
                $this->setParam($k, $v);
            }

            if (in_array($k, $ignore)) {
                continue;
            }
            if ($array_param['view_type'] == 1 && $k == 'page') continue;
            $key .= '-'.$v;
        }
        $this->setKey($key);
        $_REQUEST['BLOCK_VIEW_NOINDEX'] = $this->getVar('noindex');
        $_REQUEST['BLOCK_VIEW_HEAD_TAG'] = $this->getVar('view_type');
    }

    function generate()
    {
        if ($this->getVar('gen_key') == 0) {
            return $this->doAction();
        } else if ($this->getVar('gen_key') == 1) {
            $this->buildHtmlCache2MySQL();
        } else if ($this->getVar('gen_key') == 2) {
            $this->buildHtmlCacheLite();
        }  else if ($this->getVar('gen_key') == 3) {
            $this->_new_cache();
        }
    }

    function _new_cache()
    {
        // echo __FUNCTION__;
        // $GLOBALS["QUICKCACHE_ON"] = 0;
        $v_key = $this->getKey();
        $filename = CACHE_HTML_DIR.$v_key;
        // $this->html_cache_time;
        $data = $this->_get_cache($filename);
        if (!$data) {
            $this->autoRender = false;
            $this->doAction();
            $content = $this->blockContent;
            file_put_contents($filename, $content);
        } else {
            $this->blockContent = $data.'<!-- from cache file -->';
        }
    }

    function _get_cache($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }

        if (time(0) - filemtime($filename) > $this->html_cache_time) {
            return false;
        }

        $data = file_get_contents($filename);

        if ($data != '') {
            return $data;
        }
        return false;
    }

    function isHeader()
    {
        $view_type = $this->getVar('view_type');
        return ($view_type == 1);
    }

    function isNoIndex()
    {
        $noindex = $this->getVar('noindex');
        return ($noindex == 1 || $noindex == 2);
    }

    function isGenKey()
    {
        return $this->getVar('gen_key') > 0;
    }

    /**
     * lay cache tu CSDL
     */
    function getCache()
    {
        if ($this->isGenKey()) {
            return false;
        }

        $v_html_value = '';
        $v_key = $this->getKey();
        $v_html_value  = Gnud_Db_read_get_key($v_key, $this->_cache_table);
        if ($v_html_value != '') {
            $this->blockContent = '<!--CACHE '.$v_key.'-->'.$v_html_value;
            if ($this->autoRender) {
                echo $this->blockContent;
            }
            return $v_html_value;
        }
        return false;
    }

    /**
     * lay cache tu File
     */
    function getCacheFromFile()
    {
        if ($this->isGenKey()) {
            return false;
        }

        $v_html_value = '';
        $v_key = $this->getKey();
        $filename = CACHE_HTML_DIR.$v_key;

        if (!file_exists($filename)) {
            return false;
        }

        if (time(0) - filemtime($filename) > $this->html_cache_time) {
            return false;
        }

        $data = file_get_contents($filename);

        if ($data != '') {
            $this->blockContent = '<!--CACHE '.$v_key.'-->'.$data;
            if ($this->autoRender) {
                echo $this->blockContent;
            }
            return $data;
        }
        return false;
    }

    function setPagination($p_record_count=0, $str_var = '', $is_ajax = true, $p_pagination_number = PAGINATION_NUMBER, $p_page_max_number = PAGE_MAX_NUMBER)
    {
        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        $pagination = new Fw24h_paginationComponent();
        /* Begin - Tytv 12/01/2017 - toi_uu_tim_kiem_theo_theo_ngay_box_tin_cap_muc */ 
        $str_next = $this->getParam('str_next');
        $str_next = empty($str_next)?'Trang sau':$str_next;
        $str_prev = $this->getParam('str_prev');
        $str_prev = empty($str_prev)?'Trang trước':$str_prev;
        $loai_giao_dien = intval($this->getParam('style_pagination'));
        $no_param_page = intval($this->getParam('no_param_page'));
        
        $pagination->arrMess = array('next'=>$str_next, 'prev'=>$str_prev);
		if (check_array($this->getParam('arrMess'))) {
			$pagination->arrMess = $this->getParam('arrMess');
		}
        $pagination->isAjax = $is_ajax;
        $pagination->ajaxFunction = 'AjaxAction';
        $v_ajax_where = $this->getParam('ajaxWhere');// truong hop 1 hox su dung lai nhieu lan trong mot trang thi can phai chi ro id div cua tung box
		$pagination->ajaxWhere = ($v_ajax_where!='')? $v_ajax_where : 'div_'.$className;		
        $link = '/ajax/'.$className.'/index/';

        if($str_var!=''){
            $link .= $str_var;
        }
        // $link .= $this->getVar('page').'/'.$this->getVar('number_items').'/0';
        if($no_param_page ==0){
            $link .= '1/'.$this->getVar('number_items').'/0';
        }
        if($loai_giao_dien==1){// giao dien phân trang mới
            $str_pagination = $pagination->PaginationBox( $link, $p_record_count, $this->getVar('number_items'), $p_pagination_number, $this->getVar('page'), $p_page_max_number);
        }else{// mặc định 
            $str_pagination = $pagination->Pagination( $link, $p_record_count, $this->getVar('number_items'), $p_pagination_number, $this->getVar('page'), $p_page_max_number);
        }
        
        /* End - Tytv 12/01/2017 - toi_uu_tim_kiem_theo_theo_ngay_box_tin_cap_muc */ 
        $this->setParam('str_pagination',$str_pagination);
    }

     function setPagination_GH($p_record_count=0,$p_start=0, $str_var = '', $is_ajax = true)
    {
        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        $pagination = new Fw24h_paginationComponent();
        $pagination->arrMess = array('next'=>'>', 'prev'=>'<','start'=>'<<', 'alt_next'=>'Các trang tiếp theo', 'alt_prev'=>'Các trang trước đó', 'alt_start'=>'Trang đầu tiên');
        $pagination->isAjax = $is_ajax;
        $pagination->ajaxFunction = 'AjaxAction';
        $pagination->ajaxWhere = 'div_'.$className;

        $link = '/ajax/'.$className.'/index/';
        if($str_var!=''){
            $link .= $str_var;
        }

        $str_pagination = $pagination->Pagination_GH( $link, $p_record_count, $p_start, intval($this->getVar('number_items')), intval($this->getVar('max_items')), 5, intval($this->getVar('page')),1);

        $this->setParam('str_pagination',$str_pagination);
    }

    function setPagination1($p_record_count=0, $str_var = '')
    {
        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        $pagination = new Fw24h_paginationComponent();
        $pagination->arrMess = array('next'=>'>', 'prev'=>'<');
        $link = $_SERVER['REQUEST_URI'];
        /*$arr_link = explode('?',$link);
        $link = $arr_link[1];
        echo $link; */
        if ($str_var != '' && preg_match('~'.$str_var.'~', $link) <= 0) {
            if (preg_match( '#\?#', $link)) {
                $link .= '&'.$str_var;
            } else {
                $link .= '?'.$str_var;
            }
        }
        //$link .= '&number_items='.$this->getVar('number_items');
        $str_pagination = urldecode($pagination->Pagination( $link, $p_record_count, $this->getVar('number_items'), PAGINATION_NUMBER, $this->getVar('page')));

        $this->setParam('str_pagination',$str_pagination);
    }
	
	//Begin : 06-07-2016 : Thangnb tao_html_sitemap
	function setPagination_html_sitemap($p_record_count=0)
	{
		$className = strtolower(get_class($this));
		$className = str_replace('_block', '', $className);
		$pagination = new Fw24h_paginationComponent();
		$pagination->arrMess = array('next'=>'Trang sau', 'prev'=>'Trang trước');
		$link = $_SERVER['REQUEST_URI'];
		$str_pagination = urldecode($pagination->Pagination_html_sitemap( $link, $p_record_count, $this->getVar('number_items'), 10, $this->getVar('page'),999999));
	
		$this->setParam('str_pagination',$str_pagination);
	}
	//End : 06-07-2016 : Thangnb tao_html_sitemap

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
            $value = $this->simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
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
    /* Begin: Tytv - 29/12/2016 - trang_video (Thiết lập danh phân trang hiển thị trang hiện tại và tổng số trang) */
    function setPaginationCurrentAndTotal($p_record_count=0, $str_var = '', $is_ajax = true, $p_pagination_number = PAGINATION_NUMBER, $p_page_max_number = PAGE_MAX_NUMBER)
    {
        $className = strtolower(get_class($this));
        $className = str_replace('_block', '', $className);
        $pagination = new Fw24h_paginationComponent();
        $pagination->arrMess = array('next'=>'', 'prev'=>'');
        $pagination->classPageFirst = ' class="Prev" ';
        $pagination->classPageLast = ' class="Next" ';
        
        $pagination->isAjax = $is_ajax;
        $pagination->ajaxFunction = 'AjaxAction';
        $v_ajax_where = $this->getParam('ajaxWhere');// truong hop 1 hox su dung lai nhieu lan trong mot trang thi can phai chi ro id div cua tung box
		$pagination->ajaxWhere = ($v_ajax_where!='')? $v_ajax_where : 'div_'.$className;		
        $link = '/ajax/'.$className.'/index/';

        if($str_var!=''){
            $link .= $str_var;
        }
        // $link .= $this->getVar('page').'/'.$this->getVar('number_items').'/0';
        $link .= '1/'.$this->getVar('number_items').'/0';
        
        //$link .= '&number_items='.$this->getVar('number_items');
        $str_pagination = urldecode($pagination->PaginationCurrentAndTotal( $link, $p_record_count, $this->getVar('number_items'), PAGINATION_NUMBER, $this->getVar('page')));

        $this->setParam('str_pagination',$str_pagination);
    }
    /* End: Tytv - 29/12/2016 - trang_video (Thiết lập danh phân trang hiển thị trang hiện tại và tổng số trang) */
}

