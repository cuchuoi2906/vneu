<?php
__get_db_functions('db.general');
class header_block extends Fw24H_Block
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
        if(_is_thiet_bi_pc($v_device_global)){
            $this->dsp_header_pc();
        }
        // Chuyển sang view Mobile
        if(_is_thiet_bi_mobile($v_device_global)){
            $this->dsp_header_mobile();
        }
        // Chuyển sang view ipad
        if(_is_thiet_bi_ipad($v_device_global)){
            $this->dsp_header_ipad();
        }
    }
    /*
        * Hàm thực hiện hiển thị header ipad
        * return : Array or Link
     */
    function dsp_header_ipad(){
        $cat_id = $this->getParam('cat_id');
        $this->render($this->thisPath().'view/header_ipad.php');
    }
    /*
        * Hàm thực hiện hiển thị header PC
        * Params :
           $p_link : Link nhap vao de kiem tra
        * return : Array or Link
     */
    function dsp_header_pc(){
        $this->render($this->thisPath().'view/header_pc.php');
		
    }
    /*
    * Hàm thực hiện hiển thị header mobile
        * Params : Không
    */
    function dsp_header_mobile(){
        // Lay gia tri tham so
		$cat_id = $this->getParam('cat_id');
		$v_region_id = $this->getParam('region_id');      
        $this->render($this->thisPath().'view/header_mobile.php');
    }
}