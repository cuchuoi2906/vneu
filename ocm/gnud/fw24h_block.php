<?php
class Fw24H_Block
{
    var $blockContent = '';
    var $viewVars = array();
	var $array_filter = array();
	var $array_field_update = array();
    var $autoRender = true;
	var $_arr_permision = array ();
	
	function __construct() {
		$this->setPermision();
        $this->time_start = microtime(true);
	}
    
    function __destruct() {
        $time_end = microtime(true);
        $time = $time_end - $this->time_start;
        if (GNUD_DEBUG_MODE) {
            global $SQL_IN_BLOCK;
            // $SQL_IN_BLOCK = '';
            $time = number_format($time, 12);
            $time = substr($time, 0, 8);
            $file_name = WEB_ROOT.'/logs/blocks/'.get_called_class().'.txt';
            $file_name_sql = WEB_ROOT.'/logs/blocks/'.get_called_class().'_sql.txt';
            error_log(date('Y-m-d H:i:s').": time: $time class: ".get_called_class()." URI: ".$_SERVER['REQUEST_URI']." ".$this->time_start." {$time_end}\n", 3, $file_name);
            @chmod($file_name, 0777);
            if ($SQL_IN_BLOCK!='') {
                error_log($SQL_IN_BLOCK."\n", 3, $file_name_sql);
                @chmod($file_name_sql, 0777);
            }
            error_log(date('Y-m-d H:i:s').": time: $time class: ".get_called_class()." URI: ".$_SERVER['REQUEST_URI']." ".$this->time_start." {$time_end}\n", 3, WEB_ROOT.'/logs/blocks_profile.txt');
            @chmod(WEB_ROOT.'/logs/blocks_profile.txt', 0777);
        }
    }
	
	function _getCurrentUri($encode=true, $has_page=true)
	{
		$uri = str_replace('/ajax/', '/', $_SERVER['REQUEST_URI']);
		if (!$has_page) {
			$uri = preg_replace('/&page=[0-9]*/', '', $uri);
		}
		if ($encode) {
			return fw24h_base64_url_encode($uri);
		}
		return $uri;
	}
	
	function getRequest()
	{
		//Begin 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss
		if (check_array($_GET)) {
			foreach ($_GET as $k=>$v) {
				if (!check_array($this->_arr_arg[$k])) {
					if (check_array($_GET[$k])) {
						foreach ($_GET[$k] as $k1=>$v1) {
							$_GET[$k][$k1] = fw24h_replace_bad_char($v1);
						}
					} else {
						$_GET[$k] = fw24h_replace_bad_char($_GET[$k]);
					}
				}
			}
		}
		if (check_array($_REQUEST)) {
			foreach ($_REQUEST as $k=>$v) {
				if (!check_array($this->_arr_arg[$k])) {
					if (check_array($_REQUEST[$k])) {
						foreach ($_REQUEST[$k] as $k1=>$v1) {
							$_REQUEST[$k][$k1] = fw24h_replace_bad_char($v1);
						}						
					} else {
						$_REQUEST[$k] = fw24h_replace_bad_char($_REQUEST[$k]);
					}
				}
			}
		}
		
		if (check_array($this->_arr_arg)) {
			foreach ($this->_arr_arg as $k=>$v) {
				if (!isset($_GET[$k]) || $_GET[$k] == '' || $_GET[$k] == null) {
					$this->_GET[$k] = $v[0];
				} else {
					if (($v[1] == 'intval' || $v[1] == 'page_val') && $_GET[$k] < 0) {
						$this->_GET[$k] = $v[0];	
					} else {
						if ($v[1] == 'strval' || $v[1] == '_utf8_to_ascii') {
							if (check_array($_GET[$k])) {
								foreach ($_GET[$k] as $k1=>$v1) {
									$_GET[$k][$k1] = fw24h_replace_bad_char($v[1]($v1));	
								}	
								$this->_GET[$k] = $_GET[$k]	;				
							} else {
								$this->_GET[$k] = fw24h_replace_bad_char($v[1]($_GET[$k]));
							}
						} else {
							if (check_array($_GET[$k])) {
								foreach ($_GET[$k] as $k1=>$v1) {
									$_GET[$k][$k1] = $v[1]($v1);	
								}
								$this->_GET[$k] = $_GET[$k];
							} else {
								$this->_GET[$k] = $v[1]($_GET[$k]);
							}					
						}
					}
				}
				$_GET[$k] = $this->_GET[$k];
				$_REQUEST[$k] = $this->_GET[$k];
				$this->setParam($k, $this->_GET[$k]);
			}
		}
		//End 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss
		return $this->_GET;
	}
	
	function getStringFilter()
	{
		$array_temp = array();
		foreach ($this->_GET as $k=>$v) {
			if ($v != '') {
				$array_temp[] = "$k=$v";
			}
		}
		return implode('&',$array_temp);
	}
	
    function Fw24H_Block()
    {
        
    }

    function index()
    {

    }

    function render( $name='')
    {		
        if ( $name == '') {
			$className = $this->className();
			$file = WEB_ROOT.'modules/blocks/'.$className.'/view/'.$className.'.php';
        } else {
            $file = $name;
        }
        extract($this->viewVars);
        ob_start();  
        include( $file);  // Include the file
        $this->blockContent = ob_get_contents(); // Get the contents of the buffer
        ob_end_clean();
        
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
	
   function setParam($key, $value)
    {
		if (isset($_REQUEST[$key])) {
			$value = $_REQUEST[$key];
			$this->viewVars[$key] = $value;
		} else {
			$this->viewVars[$key] = $value;
		}
    }

    function getParam( $key)
    {
        if ( isset($this->viewVars[$key])) {
            return $this->viewVars[$key];
        }
        return null;
    }

	function getVar($var, $val='intval')
	{
		$cat_id = $this->getParam($var);
        if ($cat_id == '') {
           $cat_id = $val($_REQUEST[$var]);
           $this->setParam($var, $cat_id);
        }
		return $cat_id;
	}
	
	function setParamAll($array_param)
	{
		foreach($array_param as $k=>$v) {
			if (isset($_REQUEST[$k])) {
				$v = $_REQUEST[$k];
				$this->setParam($k, $_REQUEST[$k]);
			} else {
				$this->setParam($k, $v);
			}
		}
	}
	
	function setFilter()
	{
		$array_filter = $this->array_filter;
		$array_temp = array();
		foreach($array_filter as $k=>$v) {
			if (isset($_REQUEST[$k])) {
				$v = $_REQUEST[$k];				
			}
			$array_temp[$k] = $v;
		}
		$this->array_filter = $array_temp;
	}
	
	function setPermision()
	{
		$array_temp = array();
		foreach($this->_arr_permision as $k=>$v) {
			$value = _check_permision($v);
			$array_temp[$k] = $value;
		}
		$this->_arr_permision = $array_temp;
	}
	
	function setFieldUpdate()
	{
		$array_field_update = $this->array_field_update;
		$array_temp = array();
		foreach($array_field_update as $k=>$v) {
			if (isset($_REQUEST[$k])) {
				$v['value'] = $_REQUEST[$k];				
			}
			$array_temp[$k] = $v;
		}
		$this->array_field_update = $array_temp;
	}
	
	function generate()
	{		
		return $this->doAction();
	}
    	
	function setPagination($p_record_count=500, $str_var = '', $is_ajax = false)
	{
		$className = $this->className();
		$pagination = new Fw24h_paginationComponent();
		$pagination->arrMess = array('next'=>'Cuối &gt;&gt;', 'prev'=>'&lt;&lt; Đầu');
		$pagination->isAjax = $is_ajax;		
		$pagination->ajaxFunction = 'AjaxAction';
		$pagination->ajaxWhere = 'div_'.$className;
/* 		$link = '/ajax/'.$className.'/index/';		
		if($str_var!=''){
			$link .= $str_var;
		}	
		$link .= $this->getVar('page').'/'.$this->getVar('number_items').'/0'; */
		$link = $this->_getCurrentUri();
		$str_pagination = $pagination->Pagination( $link, $p_record_count, $this->getVar('number_items'), PAGINATION_NUMBER, $this->getVar('page'));
		$this->setParam('str_pagination',$str_pagination);
	}
	
	function getPerm($perm='admin,view')
	{
        return true;
		$arr_perm = explode(',', $perm);
		foreach( $arr_perm as $p) {
			if ($this->_arr_permision[trim($p)]) {
				return true;
			}
		}
		return false;
	}
	
	/*
    * set thoi gian bat dau thuc hien 1 thao tac
    */
    function setTimeUpdate($p_session_name='time_start') {
        $_SESSION[$p_session_name] = date('H:i:s');
    }
   
    /*
    * Lay thoi gian thuc hien 1 thao tac
    */
    function getTimeUpdate($p_time_start, $p_time_end) {
        $v_start_time = $v_end_time = 0;
        if(isset($_SESSION[$p_time_start])) {
            $v_start_time = strtotime($_SESSION[$p_time_start]);                
            unset($_SESSION[$p_time_start]);
        }
        if(isset($_SESSION[$p_time_end])) {
            $v_end_time = strtotime($_SESSION[$p_time_end]);    
            unset($_SESSION[$p_time_end]);
        }                          
        return $v_end_time>$v_start_time? ($v_end_time - $v_start_time):0; // return so giay chenh lech giua 2 thoi diem
    }
}

