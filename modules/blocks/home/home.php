<?php
__get_db_functions('db.general');
class home_block extends Fw24H_Block
{
	var $_cache_key = 'header_092018';
    function index($cat_id=1, $region_id= '0',$v_device_global = 'pc', $view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
		$this->generate();
	}
		
    function doAction()
    {
        $v_device_global = fw24h_replace_bad_char($this->getParam('v_device_global'));
        if($v_device_global == ''){
            global $v_device_global;
        }
        $cat_id = $this->getParam('cat_id');
        // Chuyển sang view PC
        $this->dsp_header();
    }
    function dsp_header(){
        $this->render($this->thisPath().'view/home.php');
		
    }
}